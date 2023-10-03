<?php

echo "<h2>Local Folder: Please select a folder to upload</h2>";

$password = "1111";
$password_enable = false;
$folderid = "TestShared/";
$FOLDERPATH = '/TESTS01/'; //.$folderid;
if ($password_enable) {
    if ($_REQUEST["kodikos"] != $password) {
        echo "Code error...sorry!!!";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $authToken = 'aaaa|aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

    // Folder ID
    $temp_folder_id = $_REQUEST["folderid"];

    if ($temp_folder_id == "1") $folderid = 'uploaded1/';
    if ($temp_folder_id == "2") $folderid = 'uploaded2/';
    if ($temp_folder_id == "3") $folderid = 'uploaded3/';
    if ($temp_folder_id == "4") $folderid = 'uploaded4/';
    if ($temp_folder_id == "5") $folderid = 'uploaded5/';
    if ($temp_folder_id == "6") $folderid = 'uploaded6/';
    if ($temp_folder_id == "7") $folderid = 'uploaded7/';

    if ($_REQUEST["folderid"] != "0") {
        $temp_folder_id = $_REQUEST["folderid"];
        echo "<br>FolderID=" . $temp_folder_id;

        if ($temp_folder_id > "0" && $temp_folder_id < "10") $folderid = 'uploaded' . $temp_folder_id . '/';
    }

    $FOLDERPATH = $FOLDERPATH . $folderid;

    // Destination server endpoint URL where the file will be uploaded.
    $destinationUrl = 'https://app.prismdrive.com/api/v1/uploads'; // Replace with the actual destination URL.

    $count = 0;

    // Create the multiple cURL handle
    $mh = curl_multi_init();

    echo "<hr>";

    foreach ($_FILES['files']['name'] as $i => $name) {
        if (strlen($_FILES['files']['name'][$i]) > 1) {

            $originalFileName = $_FILES['files']['name'][$i];
            $sourceFilePath = $_FILES['files']['tmp_name'][$i]; // Use the 'tmp_name' property to get the source path
            $mimeType = mime_content_type($sourceFilePath);

            // Set the headers for the file transfer.
            $headers = array(
                "Authorization: Bearer $authToken",
                "Content-Type: multipart/form-data",
            );

            $ch[$count] = curl_init();
            curl_setopt($ch[$count], CURLOPT_URL, $destinationUrl);
            curl_setopt($ch[$count], CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch[$count], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch[$count], CURLOPT_POST, true);
            curl_setopt($ch[$count], CURLOPT_POSTFIELDS, 
                array(
                    'file' => new CURLFile($sourceFilePath, $mimeType , $originalFileName),  
                    'relativePath' => $FOLDERPATH . $originalFileName
                )
            );
            
            // Create a progress bar div for each file
            echo "<div id='progress-bar-$count' style='width: 100%; background-color: #ccc;'>".$originalFileName.": <div id='upload-progress-$count' style='width: 0%; background-color: #4CAF50; color: white;'>0%</div></div>";

            curl_setopt($ch[$count], CURLOPT_NOPROGRESS, false);
            curl_setopt($ch[$count], CURLOPT_PROGRESSFUNCTION, function ($resource, $downloadSize, $downloaded, $uploadSize, $uploaded) use ($originalFileName, $count) {
                if ($uploadSize > 0) {
                    $percentage = round(($uploaded / $uploadSize) * 100, 2);
                    echo "<script>document.getElementById('upload-progress-$count').style.width = '$percentage%'; document.getElementById('upload-progress-$count').innerHTML = '$percentage%';</script>";
                }
                return 0;
            });

            curl_multi_add_handle($mh, $ch[$count]);

            $count++;
        } // END if
    } // end  foreach ($_FILES['files']['name'] as $i => $name) {

    // Execute the multi handle
    do {
        $status = curl_multi_exec($mh, $active);
        if ($active) {
            // Wait a short time for more activity
            curl_multi_select($mh);
        }
    } while ($active && $status == CURLM_OK);

    // Close the handles
    for ($t = 0; $t < $count; $t++) curl_multi_remove_handle($mh, $ch[$t]);

    curl_multi_close($mh);

} //end of if ($_SERVER["REQUEST_METHOD"] === "POST"

echo "<hr>";

?>
