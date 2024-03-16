let markedPages = [];
console.error("EXTENSIOn jon");
//https://spectrumcomputing.co.uk/entry/
function extractIdFromUrl(url) {
  //const regex = /\/entry\/(\d+)\//;
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
      // Check if the page with the same ID already exists in markedPages
      const existingPage = markedPages.find(page => page.id === id);

      if (!existingPage) {
        markedPages.push({ id, url });
      } else {
        console.log('Page with ID', id, 'already marked.');
      }
    } else {
      console.error('Could not extract ID from the URL:', url);
    }
    alert("ACTIONmarkPage ID="+id+" ,url="+url+ " , markedpages="+JSON.stringify(markedPages));
 
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

function getnamefromurl(url){
  //const url = "https://spectrumcomputing.co.uk/entry/1775/ZX-Spectrum/Firelord";

  // Split the URL by '/'
  const parts = url.split('/');

  // Get the last part of the array
  const lastPart = parts[parts.length - 1];

  return(lastPart);
}

function generateHtml(pages) {
  console.log("generatehtml");
  let htmlContent = '<html><head><title>Marked Pages</title></head><body>';
  pages.forEach(page => {
    gamename=getnamefromurl(page.url);
    htmlContent += `<p><a href="${page.url}" target="_blank">${gamename} ${page.id}</a></p>`;

    //htmlContent += "<p><a href="${page.url}" target='_blank'>"+gamename+" "+${page.id}+"</a></p>";
  });
  htmlContent += '</body></html>';
  return htmlContent;
}


function downloadList(pages) {
  const htmlContent = generateHtml(pages);
  const blob = new Blob([htmlContent], { type: 'text/html' });
  const url = URL.createObjectURL(blob);

  // Trigger the download
  chrome.downloads.download({
    url: url,
    filename: 'marked_pages.html',
    saveAs: true  // Allow the browser to choose the filename
  });

  // Cleanup: revoke the object URL
  URL.revokeObjectURL(url);
}