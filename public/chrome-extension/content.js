// Content script to extract data from social media pages

chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === 'extract') {
        const source = request.source;
        let data = [];
        
        try {
            switch (source) {
                case 'facebook':
                    data = extractFacebookData();
                    break;
                case 'linkedin':
                    data = extractLinkedInData();
                    break;
                case 'instagram':
                    data = extractInstagramData();
                    break;
                case 'twitter':
                    data = extractTwitterData();
                    break;
                case 'tiktok':
                    data = extractTikTokData();
                    break;
            }
            
            sendResponse({ success: true, data: data });
        } catch (error) {
            sendResponse({ success: false, error: error.message });
        }
    }
    
    return true; // Keep message channel open for async response
});

function extractFacebookData() {
    const data = [];
    const seenNames = new Set();
    let id = 1;
    
    // Method 1: Extract from Friends List page (https://www.facebook.com/friends)
    const friendsList = document.querySelectorAll('[data-pagelet="ProfileFriends"] a, [role="link"][href*="/user/"], [role="link"][href*="/profile.php"], a[href*="/user/"], a[href*="/profile.php"]');
    friendsList.forEach((element) => {
        const name = element.textContent.trim();
        const profileLink = element.href || element.getAttribute('href');
        
        if (name && name.length > 2 && name.length < 100 && profileLink && !seenNames.has(name.toLowerCase())) {
            // Skip common Facebook UI elements
            if (!name.match(/^(See All|View All|More|Friends|Friend|Add Friend|Message|Follow|Following|Unfollow|Remove|Block|Report)$/i)) {
                seenNames.add(name.toLowerCase());
                data.push({
                    id: id++,
                    name: name,
                    profile_url: profileLink.startsWith('http') ? profileLink : 'https://www.facebook.com' + profileLink,
                    source: 'facebook',
                    type: 'person'
                });
            }
        }
    });
    
    // Method 2: Extract from Friends List grid/container
    const friendCards = document.querySelectorAll('[data-pagelet="ProfileFriends"] [role="article"], .x1i10hfl[role="article"], [data-testid="friend-card"]');
    friendCards.forEach((card) => {
        const nameElement = card.querySelector('a[href*="/user/"], a[href*="/profile.php"], [role="link"]');
        if (nameElement) {
            const name = nameElement.textContent.trim();
            const profileLink = nameElement.href || nameElement.getAttribute('href');
            
            if (name && name.length > 2 && name.length < 100 && profileLink && !seenNames.has(name.toLowerCase())) {
                if (!name.match(/^(See All|View All|More|Friends|Friend|Add Friend|Message|Follow|Following)$/i)) {
                    seenNames.add(name.toLowerCase());
                    data.push({
                        id: id++,
                        name: name,
                        profile_url: profileLink.startsWith('http') ? profileLink : 'https://www.facebook.com' + profileLink,
                        source: 'facebook',
                        type: 'person'
                    });
                }
            }
        }
    });
    
    // Method 3: Extract from Group Members page
    const groupMembers = document.querySelectorAll('[data-pagelet="GroupMembers"] a, .x1i10hfl a[href*="/user/"], .x1i10hfl a[href*="/profile.php"]');
    groupMembers.forEach((element) => {
        const name = element.textContent.trim();
        const profileLink = element.href || element.getAttribute('href');
        
        if (name && name.length > 2 && name.length < 100 && profileLink && !seenNames.has(name.toLowerCase())) {
            if (!name.match(/^(See All|View All|More|Members|Member|Add|Invite)$/i)) {
                seenNames.add(name.toLowerCase());
                data.push({
                    id: id++,
                    name: name,
                    profile_url: profileLink.startsWith('http') ? profileLink : 'https://www.facebook.com' + profileLink,
                    source: 'facebook',
                    type: 'person'
                });
            }
        }
    });
    
    // Method 4: Extract from any link with user/profile pattern
    const allUserLinks = document.querySelectorAll('a[href*="/user/"], a[href*="/profile.php?id="]');
    allUserLinks.forEach((element) => {
        const name = element.textContent.trim();
        const profileLink = element.href || element.getAttribute('href');
        
        if (name && name.length > 2 && name.length < 100 && profileLink && !seenNames.has(name.toLowerCase())) {
            // More aggressive filtering
            if (!name.match(/^(See All|View All|More|Friends|Friend|Add|Message|Follow|Following|Unfollow|Remove|Block|Report|Like|Comment|Share|Save|Send|Copy|Download|Upload|Edit|Delete|Cancel|Close|OK|Yes|No|Search|Filter|Sort|Settings|Menu|Home|Profile|Timeline|About|Photos|Videos|Posts|Events|Groups|Pages|Marketplace|Watch|Gaming|Jobs|Weather|COVID-19|Fundraisers|Memories|Saved|Find Friends|Friend Requests|Messages|Notifications|Account|Privacy|Help|Log Out|Logout|Sign Out|Signout)$/i)) {
                // Check if it looks like a person's name (has space or is reasonable length)
                if (name.includes(' ') || (name.length >= 3 && name.length <= 50)) {
                    seenNames.add(name.toLowerCase());
                    data.push({
                        id: id++,
                        name: name,
                        profile_url: profileLink.startsWith('http') ? profileLink : 'https://www.facebook.com' + profileLink,
                        source: 'facebook',
                        type: 'person'
                    });
                }
            }
        }
    });
    
    // Method 6: Extract business pages
    const businessPages = document.querySelectorAll('a[href*="/pages/"], a[href*="/page/"]');
    businessPages.forEach((element) => {
        const name = element.textContent.trim();
        const pageLink = element.href || element.getAttribute('href');
        
        if (name && name.length > 2 && name.length < 200 && pageLink && !seenNames.has(name.toLowerCase())) {
            seenNames.add(name.toLowerCase());
            data.push({
                id: id++,
                company_name: name,
                name: name,
                profile_url: pageLink.startsWith('http') ? pageLink : 'https://www.facebook.com' + pageLink,
                source: 'facebook',
                type: 'business'
            });
        }
    });
    
    // Method 7: Try to extract from any visible text that looks like a name with a link
    // This is a fallback for dynamic content
    const allLinksWithNames = document.querySelectorAll('a');
    allLinksWithNames.forEach((link) => {
        const href = link.href || link.getAttribute('href');
        const name = link.textContent.trim();
        
        // Check if this is a Facebook profile link
        if (href && (href.includes('/user/') || href.includes('/profile.php') || href.includes('/profile/')) && name && name.length > 2 && name.length < 100) {
            if (!seenNames.has(name.toLowerCase()) && !name.match(/^(See All|View All|More|Friends|Friend|Add|Message|Follow|Following|Unfollow|Remove|Block|Report)$/i)) {
                if (name.includes(' ') || (name.length >= 3 && name.length <= 50)) {
                    seenNames.add(name.toLowerCase());
                    data.push({
                        id: id++,
                        name: name,
                        profile_url: href.startsWith('http') ? href : 'https://www.facebook.com' + href,
                        source: 'facebook',
                        type: 'person'
                    });
                }
            }
        }
    });
    
    console.log(`Extracted ${data.length} items from Facebook`);
    return data;
}

function extractLinkedInData() {
    const data = [];
    
    // Extract connections
    const connections = document.querySelectorAll('.mn-connection-card, .search-result__info, .entity-result__item');
    connections.forEach((element, index) => {
        const nameElement = element.querySelector('.mn-connection-card__name, .search-result__result-link, .entity-result__title-text a');
        const titleElement = element.querySelector('.mn-connection-card__occupation, .search-result__snippets, .entity-result__primary-subtitle');
        const companyElement = element.querySelector('.search-result__subtitle, .entity-result__secondary-subtitle');
        
        if (nameElement) {
            const name = nameElement.textContent.trim();
            const profileLink = nameElement.href || element.querySelector('a')?.href;
            const title = titleElement?.textContent.trim() || '';
            const company = companyElement?.textContent.trim() || '';
            
            data.push({
                id: index + 1,
                name: name,
                title: title,
                company_name: company,
                profile_url: profileLink,
                source: 'linkedin',
                type: 'person'
            });
        }
    });
    
    // Extract company pages
    const companies = document.querySelectorAll('.search-result__info, .org-top-card');
    companies.forEach((element, index) => {
        const nameElement = element.querySelector('.search-result__result-link, .org-top-card-summary__title');
        if (nameElement) {
            const name = nameElement.textContent.trim();
            const companyLink = nameElement.href;
            
            data.push({
                id: data.length + 1,
                company_name: name,
                profile_url: companyLink,
                source: 'linkedin',
                type: 'business'
            });
        }
    });
    
    return data;
}

function extractInstagramData() {
    const data = [];
    
    // Extract followers/following
    const users = document.querySelectorAll('a[href*="/"] span, ._7UhW9, .x1i10hfl a');
    users.forEach((element, index) => {
        const name = element.textContent.trim();
        if (name && name.length > 0 && name !== 'Follow' && name !== 'Following' && name !== 'Message') {
            const link = element.closest('a')?.href;
            if (link && link.includes('/')) {
                data.push({
                    id: index + 1,
                    name: name,
                    profile_url: link,
                    source: 'instagram',
                    type: 'person'
                });
            }
        }
    });
    
    return data;
}

function extractTwitterData() {
    const data = [];
    
    // Extract users from timeline
    const users = document.querySelectorAll('[data-testid="User-Name"] a, .css-901oao[href*="/"]');
    users.forEach((element, index) => {
        const name = element.textContent.trim();
        const profileLink = element.href;
        if (name && name.length > 0 && profileLink && !data.find(d => d.name === name)) {
            data.push({
                id: index + 1,
                name: name,
                profile_url: profileLink,
                source: 'twitter',
                type: 'person'
            });
        }
    });
    
    return data;
}

function extractTikTokData() {
    const data = [];
    
    // Extract users
    const users = document.querySelectorAll('a[href*="/@"]');
    users.forEach((element, index) => {
        const name = element.textContent.trim();
        const profileLink = element.href;
        if (name && name.length > 0 && profileLink && !data.find(d => d.name === name)) {
            data.push({
                id: index + 1,
                name: name,
                profile_url: profileLink,
                source: 'tiktok',
                type: 'person'
            });
        }
    });
    
    return data;
}

