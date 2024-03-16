<?php
/*
v001 - 231215a 

*/

$debug=true;
// Function to calculate the visual similarity between two images
function imagesSimilarity($image1, $image2) {
    $img1 = imagecreatefromstring(file_get_contents($image1));
    $img2 = imagecreatefromstring(file_get_contents($image2));
    
    // Calculate the perceptual hash for each image
    $hash1 = phash($img1);
    $hash2 = phash($img2);
    
    // Calculate the Hamming distance between the hashes
    $hammingDistance = 0;
    
    foreach ($hash1 as $key => $value) {
        if ($value !== $hash2[$key]) {
            $hammingDistance++;
        }
    }
    
    // Return the similarity percentage
    return round((1 - ($hammingDistance / 64)) * 100);
}

// Function to generate a perceptual hash for an image
function phash($image) {
    // Resize the image to a smaller size (8x8 pixels)
    $resizedImage = imagecreatetruecolor(8, 8);
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, 8, 8, imagesx($image), imagesy($image));
    
    // Convert the image to grayscale
    imagefilter($resizedImage, IMG_FILTER_GRAYSCALE);
    
    // Calculate the average pixel value
    $total = 0;
    $pixels = [];
    
    for ($y = 0; $y < 8; $y++) {
        for ($x = 0; $x < 8; $x++) {
            $pixel = imagecolorat($resizedImage, $x, $y) & 0xFF;
            $pixels[] = $pixel;
            $total += $pixel;
        }
    }
    
    $average = $total / 64;
    
    // Generate the hash based on black/white pixel values
    $hash = '';
    
    foreach ($pixels as $pixel) {
        $hash .= ($pixel >= $average) ? '1' : '0';
    }
    
    return str_split($hash);
}

// Folder path where the images are stored
$folderPath = '/tmp/a/myimages/';

// Get a list of all image files in the folder
$imageFiles = glob($folderPath . '*.jpg');
$imageFiles = array_merge($imageFiles, glob($folderPath . '/*.JPG'));

// Compare each image with every other image
$result = array();

foreach ($imageFiles as $i => $img1) {
    for ($j = $i + 1; $j < count($imageFiles); $j++) {
        $img2 = $imageFiles[$j];
        
        // Calculate the similarity between the images
        $similarity = imagesSimilarity($img1, $img2);
        if($debug) echo("<BR>comparing:".$img1." WITH ".$img2);
        // Store the result as an array
        if ($similarity > 75) { // You can adjust the similarity threshold as per your requirement
            $result[] = array(
                'image1' => $img1,
                'image2' => $img2,
                'similarity' => $similarity
            );
        }
    }
}

// Display the results
foreach ($result as $res) {
    echo '<img src="' . $res['image1'] . '" alt="Image 1" width="200" height="200">';
    echo '<img src="' . $res['image2'] . '" alt="Image 2" width="200" height="200">';
    echo 'Similarity: ' . $res['similarity'] . '%';
    echo '<input type="checkbox" name="delete[]" value="' . $res['image1'] . '"> Delete Image 1<br>';
    echo '<input type="checkbox" name="delete[]" value="' . $res['image2'] . '"> Delete Image 2<br><br>';
}

// Process the form submission for deleting images
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        foreach ($_POST['delete'] as $img) {
            unlink($img); // Delete the selected images
        }
    }
}
?>
