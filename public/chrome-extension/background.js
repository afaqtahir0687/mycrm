// Background service worker for Chrome extension

chrome.runtime.onInstalled.addListener(() => {
    console.log('CRM Data Scraper Extension installed');
});

// Listen for messages from content scripts
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === 'log') {
        console.log('Extension log:', request.message);
    }
    return true;
});

