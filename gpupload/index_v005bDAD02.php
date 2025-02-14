<?php
/*
gpupload
v241225a -Dad upload/show images/version
v231001d - v004a upload ok filename encoding ok, reverse sort scan_dir_datesort
v231001a - upload ok

*/
$display_images=true;
$uploadDir = 'uploads/';


    // Generate the expected password with the current date
    //$expectedPassword = "password" . date('dmY');
    //$expectedPassword = "pass" . date('d');
    $expectedPassword = "aaa2023" ;
    //echo "$expectedPassword"; exit();
    $expectedPassword2 = "bbb2023";
    $expectedPassword3 = "ccc2023";
    $expectedPassword4 = "ddd2023";


mb_internal_encoding('UTF-8');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) ) {
    // Get the uploaded file and password
    $file = $_FILES['file'];
    $password = $_POST['password'];




    
    // Verify the password
    if ($password === $expectedPassword || $password === $expectedPassword2 || $password === $expectedPassword3 || $password === $expectedPassword4 ) {
        // Define the directory where files will be uploaded
        

        // Ensure the directory exists, create it if not
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the destination directory
        $file_name = $_FILES['file']['name'];
        //$uploadPath = $uploadDir . basename($file['name']);
        $uploadPath = $uploadDir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo 'File uploaded successfully!<br>';
        } else {
            echo 'Error uploading file.<br>';
        }
    } else {
        echo 'Incorrect password.<br>';
    }
}//if ($_SERVER['REQUEST_METHOD'] === 'POST')

?>
<!DOCTYPE html>
<html>
<head>
    <title>File Uploader</title>
    <meta charset="UTF-8">
</head>
<body>
    <form id="file-upload-form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" >
        <input type="file" name="file" id="file" required style="font-size: 20px; padding: 15px 30px; width: 400px; height: 50px;"><BR>
        <input type="password" name="password" id="password" autocomplete="off" placeholder="Enter Password" required style="font-size: 20px; padding: 15px 30px; width: 150px; height: 30px;" >
        <input type="submit" value="Upload File" style="font-size: 20px; padding: 15px 30px; width: 150px; height: 50px;" >
    </form>
<BR><HR>
    <div id="response"></div>

    <!-- <script src="upload.js"></script> -->
<?php
// LIST Uploaded Files
function scan_dir_datesort($dir) {
    if (!is_dir($dir)) {
        return false; // Return false if the directory is not valid
    }

    $ignored = array('.', '..', '.svn', '.htaccess');
    $files = array();    

    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files); // Sort files by modification time (most recent first)
    return array_keys($files); // Return the sorted file names
}

// Helper function to check if a file is an image
function is_image($file) {
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($fileExtension, $imageExtensions);
}

$uploadedFiles = scan_dir_datesort('uploads/');
if ($uploadedFiles === false) {
    echo 'Error: Could not read the uploads directory.';
    exit; // Stop further execution if directory scanning fails
}


echo 'Uploaded Files:<br>';

foreach ($uploadedFiles as $file) {
    if ($file !== '.' && $file !== '..') {
        // Check if the file is an image
        if (is_image($file) && $display_images) {
            // Create a link to the image that opens in a new tab
            echo '<a href="uploads/' . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . '" target="_blank">';
            // Display the image with a max size
            echo '<img src="uploads/' . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . '" style="max-width: 200px; max-height: 200px; margin: 5px;">';
            echo "<BR>".htmlspecialchars($file, ENT_QUOTES, 'UTF-8').'</a><br>';
        } else {
            // For non-image files, provide a link
            echo '<a href="uploads/' . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . '">' 
                 . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . '</a><br>';
        }
    }
}
?>
<Hr>
<hr>
<!-- HTML form to accept image URL -->
<form method="POST" action="">
    <label for="password">Password:</label>
    <input type="password" name="password" required><br><br>

    <label for="image_url">Image URL:</label>
    <input type="text" name="image_url" placeholder="Enter image URL" required><br><br>

    <input type="submit" value="Download Image">
</form>

<?php


// Function to download image from URL and save it in the uploads folder
function download_image($url, $destination) {
    // Get the image content from the URL
    $imageContent = file_get_contents($url);
    //echo "<BR>aaaaaaaa url".$url;
    // Check if we successfully retrieved the image content
    if ($imageContent === false) {
        return false;
    }

    // Save the image content to the destination file
    return file_put_contents($destination, $imageContent);
}
$password_check_ok=false;


// Handle the image URL submission (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST'  ) { 
    $password = $_POST['password']; 
    if ($password === $expectedPassword || $password === $expectedPassword2 || $password === $expectedPassword3 || $password === $expectedPassword4 ) {$password_check_ok=true; };    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_url']) && $password_check_ok ) {    
    $imageUrl = trim($_POST['image_url']);
    
    //echo "<BR>aaaaaaaa  imageUrl". $imageUrl;
    // Validate URL (basic check to see if it starts with http)
    if (filter_var($imageUrl, FILTER_VALIDATE_URL) === false) {
        echo 'Invalid URL.';
    } else {
        // Get image file extension from URL
        $pathInfo = pathinfo($imageUrl);
        $extension = strtolower($pathInfo['extension']);
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

        if (in_array($extension, $validExtensions)) {
            // Generate a safe file name for the uploaded image
            ///$filename = 'image_' . time() . '.' . $extension;
            ///$filePath = $uploadDir . $filename;
            // Use the original filename from the URL
            $filename = basename($pathInfo['basename']);
            $filePath = $uploadDir . $filename;

            // Check if file already exists and generate a unique filename if necessary
            $counter = 1;
            while (file_exists($filePath)) {
                $filename = pathinfo($pathInfo['basename'], PATHINFO_FILENAME) . "_" . $counter . '.' . $extension;
                $filePath = $uploadDir . $filename;
                $counter++;
            }            

            // Download and save the image
            if (download_image($imageUrl, $filePath)) {
                echo 'Image downloaded successfully! RELOAD PAGE to see <br>';
                header("Location: " . $_SERVER['PHP_SELF']);
            } else {
                echo 'Failed to download the image.<br>';
            }
        } else {
            echo 'Invalid image format. Only JPG, PNG, GIF, etc., are allowed.<br>';
        }
    }
}

?>


</body>
</html>
