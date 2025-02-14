onmessage = function(event) {
  // Capture screenshot and send it back to the main thread
  const canvas = document.createElement('canvas');
  const context = canvas.getContext('2d');
  const video = document.createElement('video');

  // Get user's media stream (video only)
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
      video.play();

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      // Convert canvas to data URL (image)
      const imageData = canvas.toDataURL('image/jpeg');
      postMessage(imageData); // Send data to main thread

      // Clean up (optional)
      stream.getTracks().forEach(track => track.stop());
    })
    .catch(err => {
      console.error('Error accessing camera:', err);
    });
};