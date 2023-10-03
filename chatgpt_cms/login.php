<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ελέγχουμε το αρχείο CSV για τους χρήστες
    $users = array_map('str_getcsv', file('users.csv'));

    foreach ($users as $user) {
        if ($user[0] === $username && $user[1] === $password) {
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        }
    }

    $errorMessage = 'Λανθασμένο όνομα χρήστη ή κωδικός πρόσβασης';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Σύνδεση</title>
</head>
<body>
    <h1>Σύνδεση</h1>

    <?php if (isset($errorMessage)): ?>
        <p><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="username">Όνομα Χρήστη:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Κωδικός Πρόσβασης:</label>
        <input type="password" name="password" id="password" required><br>

        <input type="submit" value="Σύνδεση">
    </form>
</body>
</html>
