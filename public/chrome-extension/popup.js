let extractedData = [];

document.getElementById('extract-btn').addEventListener('click', async () => {
    const source = document.getElementById('source-select').value;
    
    if (!source) {
        showStatus('Please select a source first', 'error');
        return;
    }
    
    showStatus('Extracting data from current tab...', 'info');
    
    try {
        const [tab] = await chrome.tabs.query({ active: true, currentWindow: true });
        
        // Inject content script and extract data
        const results = await chrome.tabs.sendMessage(tab.id, {
            action: 'extract',
            source: source
        });
        
        if (results && results.data && results.data.length > 0) {
            extractedData = results.data;
            showStatus(`Successfully extracted ${results.data.length} items`, 'success');
            showPreview(results.data);
            document.getElementById('send-btn').disabled = false;
        } else {
            showStatus('No data found. Make sure you are on the correct page.', 'error');
            extractedData = [];
            document.getElementById('send-btn').disabled = true;
        }
    } catch (error) {
        console.error('Error:', error);
        showStatus('Error extracting data: ' + error.message, 'error');
    }
});

document.getElementById('send-btn').addEventListener('click', async () => {
    if (extractedData.length === 0) {
        showStatus('No data to send', 'error');
        return;
    }
    
    showStatus('Sending data to CRM...', 'info');
    
    try {
        // Get CRM URL from storage or use default
        const result = await chrome.storage.sync.get(['crmUrl']);
        let baseUrl = result.crmUrl || 'http://localhost/crm';
        
        // Remove trailing slash
        baseUrl = baseUrl.replace(/\/$/, '');
        
        // Try to detect the correct URL path
        // For Laravel in XAMPP, the URL might be:
        // 1. http://localhost/crm/public (if accessing public folder directly)
        // 2. http://localhost/crm (if Apache document root points to public folder)
        
        let crmUrl = baseUrl;
        let urlFound = false;
        
        // Test both URL patterns
        const urlPatterns = [
            baseUrl,  // Try without /public first
            baseUrl + '/public'  // Then try with /public
        ];
        
        for (const testUrl of urlPatterns) {
            try {
                const testResponse = await fetch(testUrl + '/data-scraping/csrf-token', {
                    method: 'GET',
                    credentials: 'include'
                });
                
                // Check if response is valid JSON (not 404 HTML page)
                if (testResponse.status === 200) {
                    const contentType = testResponse.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        crmUrl = testUrl;
                        urlFound = true;
                        break;
                    }
                }
            } catch (e) {
                // Continue to next URL pattern
                console.log('Trying URL pattern failed:', testUrl, e.message);
            }
        }
        
        if (!urlFound) {
            // Default to baseUrl without /public (assuming Apache is configured correctly)
            crmUrl = baseUrl;
            showStatus('Warning: Could not detect correct CRM URL. Using default: ' + crmUrl, 'info');
        }
        
        // Get CSRF token first
        let csrfToken = '';
        try {
            const csrfResponse = await fetch(`${crmUrl}/data-scraping/csrf-token`, {
                method: 'GET',
                credentials: 'include'
            });
            if (csrfResponse.ok) {
                const csrfData = await csrfResponse.json();
                csrfToken = csrfData.token || '';
            }
        } catch (e) {
            console.warn('Could not get CSRF token:', e);
        }
        
        const response = await fetch(`${crmUrl}/data-scraping/receive-extension-data`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {})
            },
            credentials: 'include',
            body: JSON.stringify({
                source: document.getElementById('source-select').value,
                data: extractedData
            })
        });
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            // If 404, provide helpful error message
            if (response.status === 404) {
                const suggestedUrl = crmUrl.includes('/public') ? baseUrl : baseUrl + '/public';
                throw new Error('404 Not Found at: ' + crmUrl + '/data-scraping/receive-extension-data\n\n' +
                    'The CRM URL might be incorrect. Try:\n' +
                    '1. ' + suggestedUrl + '\n' +
                    '2. Check if your CRM is accessible at: ' + baseUrl + '\n' +
                    '3. Make sure the .htaccess file exists in the root directory\n\n' +
                    'Response: ' + text.substring(0, 150));
            }
            throw new Error('Server returned HTML instead of JSON. Status: ' + response.status + '. Response: ' + text.substring(0, 200));
        }
        
        const result_data = await response.json();
        
        if (result_data.success) {
            showStatus(`Successfully sent ${extractedData.length} items to CRM. Opening CRM page...`, 'success');
            
            // Redirect to CRM page after 2 seconds
            setTimeout(() => {
                if (result_data.redirect) {
                    chrome.tabs.create({ url: result_data.redirect });
                } else {
                    chrome.tabs.create({ url: crmUrl + '/data-scraping' });
                }
            }, 2000);
            
            extractedData = [];
            document.getElementById('send-btn').disabled = true;
            document.getElementById('data-preview').style.display = 'none';
        } else {
            showStatus('Error: ' + (result_data.message || 'Failed to send data'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        let errorMessage = error.message || 'Unknown error';
        showStatus('Error sending data: ' + errorMessage, 'error');
    }
});

function showStatus(message, type) {
    const statusDiv = document.getElementById('status');
    statusDiv.textContent = message;
    statusDiv.className = 'status ' + type;
    statusDiv.style.display = 'block';
    
    if (type === 'success') {
        setTimeout(() => {
            statusDiv.style.display = 'none';
        }, 3000);
    }
}

function showPreview(data) {
    const previewDiv = document.getElementById('data-preview');
    const contentDiv = document.getElementById('preview-content');
    
    let html = '<ul>';
    data.slice(0, 5).forEach((item, index) => {
        html += `<li>${index + 1}. ${item.name || item.company_name || 'Unknown'}</li>`;
    });
    if (data.length > 5) {
        html += `<li>... and ${data.length - 5} more</li>`;
    }
    html += '</ul>';
    
    contentDiv.innerHTML = html;
    previewDiv.style.display = 'block';
}

