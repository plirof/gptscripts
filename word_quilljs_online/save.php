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

// Get the content sent from the client
$content = $_POST['content'];

// Perform authentication
$username = $_POST['username'];
$password = $_POST['password'];

if (authenticateUser($username, $password)) {
    // Save the content to a file or database
    // Replace this with your own logic to store the content

    // For example, save to a file
    $file = 'saved_document.html';
    file_put_contents($file, $content);

    // Send a response to the client
    $response = ['status' => 'success'];
    echo json_encode($response);
} else {
    // Authentication failed
    $response = ['status' => 'error', 'message' => 'Authentication failed'];
    echo json_encode($response);
}
?>
