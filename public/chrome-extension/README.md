# CRM Data Scraper Chrome Extension

## Installation Instructions

1. **Download the Extension**
   - The extension files are located in: `public/chrome-extension/` folder

2. **Load Extension in Chrome**
   - Open Chrome and go to: `chrome://extensions/`
   - Enable "Developer mode" (toggle in top-right corner)
   - Click "Load unpacked"
   - Navigate to: `C:\xampp\htdocs\crm\public\chrome-extension`
   - Click "Select Folder"

3. **Pin the Extension**
   - Click the puzzle piece icon in Chrome toolbar
   - Find "CRM Data Scraper"
   - Click the pin icon to keep it visible

4. **Usage**
   - Open your social media account in a Chrome tab (Facebook, LinkedIn, etc.)
   - Navigate to the page with data you want to extract (e.g., Friends list, Group members)
   - Click the extension icon in the toolbar
   - Select the source (Facebook, LinkedIn, etc.)
   - Click "Extract Data from Current Tab"
   - Review the extracted data
   - Click "Send to CRM" to import the data

## Notes

- Make sure your CRM is running on: http://localhost/crm
- The extension requires permissions to read tab content (only when you click "Extract")
- All data stays on your local machine until you send it to CRM
- Ensure compliance with platform Terms of Service

