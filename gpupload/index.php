<?php
/*
gpupload
v231001d - v004a upload ok filename encoding ok, reverse sort scan_dir_datesort
v231001a - upload ok

*/
mb_internal_encoding('UTF-8');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the uploaded file and password
    $file = $_FILES['file'];
    $password = $_POST['password'];

    // Generate the expected password with the current date
    //$expectedPassword = "password" . date('dmY');
    //$expectedPassword = "pass" . date('d');
    $expectedPassword = "aaa2023" ;
    //echo "$expectedPassword"; exit();
    $expectedPassword2 = "bbb2023";
    $expectedPassword3 = "ccc2023";
    $expectedPassword4 = "ddd2023";
    
    // Verify the password
    if ($password === $expectedPassword || $password === $expectedPassword2 || $password === $expectedPassword3 || $password === $expectedPassword4 ) {
        // Define the directory where files will be uploaded
        $uploadDir = 'uploads/';

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
        <input type="file" name="file" id="file" required>
        <input type="password" name="password" id="password" autocomplete="off" placeholder="Enter Password" required>
        <input type="submit" value="Upload File">
    </form>

    <div id="response"></div>

    <!-- <script src="upload.js"></script> -->
</body>
</html>
<?php

function scan_dir_datesort($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();    
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return $files;
}



//if ($password !== $expectedPassword) exit();
// List uploaded files
//$uploadedFiles = scandir('uploads/');
$uploadedFiles = scan_dir_datesort('uploads/');
if ($uploadedFiles !== false) {
    echo 'Uploaded Files:<br>';
    foreach ($uploadedFiles as $file) {
        if ($file !== '.' && $file !== '..') {
            //$file = urlencode($file); //$encodedFileName = urlencode($file);
            echo '<a href="uploads/' . $file . '">' . $file . '</a><br>';
        }
    }
}
?>
