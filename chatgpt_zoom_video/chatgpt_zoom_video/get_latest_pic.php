<?php
/*
250106b- $debug flas to show random image each time
250106 - Initial chatgpt version


Scans the folder uploads for the latest uploaded image

*/
$debug=true; //Debug=true show random image each time for testing
// Path to the folder containing your images
$imageFolder = 'uploads/'; 

// Get all files in the folder
$files = scandir($imageFolder);

// Initialize latest file information
$latestFile = null;
$latestTimestamp = 0;

// Loop through files in the folder
foreach ($files as $file) {
  // Check for JPG files
  if (strtolower(pathinfo($file, PATHINFO_EXTENSION) === 'jpg')) {
    // Get the file's full path
    $filePath = $imageFolder . $file;

    // Get the file's modification timestamp
    $fileTimestamp = filemtime($filePath);

    // Update latest file information if current file is newer
    if ($fileTimestamp > $latestTimestamp) {
      $latestFile = $file;
      $latestTimestamp = $fileTimestamp;
    }
  }
}

if ($debug) {
    // Show a random image from $files
    $latestFile = $files[array_rand($files)];
    //header('Content-Type: image/jpeg');
    //readfile($imageFolder . $randomFile);
    //exit();  // Exit to avoid showing the latest image after debug image
}

// Check if a latest JPG file was found
if ($latestFile) {
  // Output the latest JPG image as a response
  header('Content-Type: image/jpeg');
  readfile($imageFolder . $latestFile);
} else {
  // If no JPG image found, output a placeholder or error image
  header('Content-Type: image/png');
  readfile('placeholder.png'); // Replace 'placeholder.png' with your desired image
}
?>
