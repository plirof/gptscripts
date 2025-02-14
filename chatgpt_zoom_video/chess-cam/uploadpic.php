<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $imageData = json_decode($_POST['image'], true); // Get image data from JSON

  // Decode data URL to get image data
  $imageData = explode(',', $imageData);
  $imageData = base64_decode($imageData[1]);

  // Save image to your server (adjust path as needed)
  $filePath = '../chatgpt_zoom_video/uploads/' . uniqid() . '.jpg';
  file_put_contents($filePath, $imageData);

  // Optional: Send a response indicating success
  echo 'Image uploaded successfully!';
}
?>