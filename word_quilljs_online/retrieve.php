<?php
// Function to authenticate a user
function authenticateUser($username, $password)
{
    // Load user credentials from the flat file database (users.csv)
    $users = file('users.csv', FILE_IGNORE_NEW_LINES);

    foreach ($users as $user) {
        list($storedUsername, $storedPassword) = explode(',', $user);

        // Check if the provided username and password match
        if ($storedUsername === $username && $storedPassword === $password) {
            return true; // Authentication successful
        }
    }

    return false; // Authentication failed
}

// Get the username and password sent from the client
$username = $_POST['username'];
$password = $_POST['password'];

if (authenticateUser($username, $password)) {
    // Retrieve the saved content from the file
    $file = 'saved_document.html';
    $content = file_get_contents($file);

    // Send the retrieved content to the client
    $response = ['status' => 'success', 'content' => $content];
    echo json_encode($response);
} else {
    // Authentication failed
    $response = ['status' => 'error', 'message' => 'Authentication failed'];
    echo json_encode($response);
}
?>
