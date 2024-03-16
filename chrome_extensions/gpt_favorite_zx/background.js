let markedPages = [];
console.error("EXTENSION join");

function extractIdFromUrl(url) {
  const regex = /\/entry\/(\d+)/;
  const match = url.match(regex);
  return match ? match[1] : null;
}

chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
  console.log("chrome.runtime.onMessage.addListener");
  if (request.action === 'markPage') {
    const url = request.url;
    const id = extractIdFromUrl(url);

    if (id) {
      const existingPage = markedPages.find(page => page.id === id);

      if (!existingPage) {
        markedPages.push({ id, url });
      } else {
        console.log('Page with ID', id, 'already marked.');
      }
    } else {
      console.error('Could not extract ID from the URL:', url);
    }

    alert("ACTION markPage ID=" + id + " ,url=" + url + " , markedpages=" + JSON.stringify(markedPages));
  } else if (request.action === 'exportList') {
    exportList(markedPages);
  } else if (request.action === 'downloadList') {
    downloadList(markedPages);
  }
});

function exportList(pages) {
  const htmlContent = generateHtml(pages);
  const blob = new Blob([htmlContent], { type: 'text/html' });
  const url = URL.createObjectURL(blob);

  // Open the exported content in a new tab
  chrome.tabs.create({ url: url }, function(tab) {
    // Cleanup: revoke the object URL after the tab has loaded
    chrome.tabs.onUpdated.addListener(function listener(tabId, changeInfo) {
      if (tabId === tab.id && changeInfo.status === 'complete') {
        URL.revokeObjectURL(url);
        chrome.tabs.onUpdated.removeListener(listener); // Remove the listener once it's executed
      }
    });
  });
}

function getnamefromurl(url) {
  const parts = url.split('/');
  return parts[parts.length - 1];
}

function generateHtml(pages) {
  console.log("generatehtml");
  let htmlContent = '<html><head><title>Marked Pages</title></head><body>'+"\n";
  pages.forEach(page => {
    const gamename = getnamefromurl(page.url);
    htmlContent += `<p><a href="${page.url}" target="_blank">${gamename} ${page.id}</a></p>`+"\n";
  });
  htmlContent += '</body></html>';
  //alert(htmlContent);
  return htmlContent;
}

function downloadList(pages) {
  const content = generateHtml(pages);
  const blob = new Blob([content], { type: 'text/plain' });
  const url = URL.createObjectURL(blob);

  // Use chrome.runtime.getURL to get the extension URL
  const extensionUrl = chrome.runtime.getURL('marked_pages.txt');

  // Create a Blob with the content
  const contentBlob = new Blob([content], { type: 'text/plain' });

  // Create an anchor element
  const a = document.createElement('a');
  a.href = URL.createObjectURL(contentBlob);
  a.download = 'marked_pages.txt';

  // Append the anchor to the document and trigger a click event
  document.body.appendChild(a);
  a.click();

  // Remove the anchor from the document
  document.body.removeChild(a);

  // Cleanup: revoke the object URL
  URL.revokeObjectURL(url);
}

