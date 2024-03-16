document.addEventListener('DOMContentLoaded', function() {
	document.getElementById('markButton').addEventListener('click', function() { /*alert("markButtonCLICK"); console.log("markButtonCLICK");*/
	  chrome.tabs.query({ active: true, currentWindow: true }, function(tabs) {
	    chrome.runtime.sendMessage({ action: 'markPage', url: tabs[0].url });
	  });
	});

	document.getElementById('exportButton').addEventListener('click', function() {/*alert("exportButtonCLICK"); console.log("exportButtonCLICK");*/
	  chrome.runtime.sendMessage({ action: 'exportList' });
	});

	// Add a listener for the download link click
	document.getElementById('downloadLink').addEventListener('click', function() {
  	chrome.runtime.sendMessage({ action: 'downloadList' });
});
});