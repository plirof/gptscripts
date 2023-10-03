<?php
session_start();

$isLoggedIn = isset($_SESSION['username']);
$loggedInUser = $isLoggedIn ? $_SESSION['username'] : '';

// Φόρτωση των άρθρων από την flat-file βάση δεδομένων
$articles = file_get_contents('articles.txt');
$articles = json_decode($articles, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Αρχική Σελίδα</title>
</head>
<body>
    <h1>Αρχική Σελίδα</h1>

    <?php if ($isLoggedIn): ?>
        <p>Καλωσήρθες, <?php echo $loggedInUser; ?>! <a href="logout.php">Αποσύνδεση</a></p>
    <?php else: ?>
        <p><a href="login.php">Σύνδεση</a></p>
    <?php endif; ?>

    <h2>Άρθρα</h2>
    <ul>
        <?php foreach ($articles as $article): ?>
            <li>
                <h3><?php echo $article['title']; ?></h3>
                <p><?php echo $article['content']; ?></p>

                <?php if ($isLoggedIn): ?>
                    <a href="edit_article.php?id=<?php echo $article['id']; ?>">Επεξεργασία</a>
                    <a href="delete_article.php?id=<?php echo $article['id']; ?>">Διαγραφή</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Προσθήκη Άρθρου</h2>
    <?php if ($isLoggedIn): ?>
        <form action="index.php" method="POST">
            <label for="title">Τίτλος:</label>
            <input type="text" name="title" id="title" required><br>

            <label for="content">Περιεχόμενο:</label><br>
            <textarea name="content" id="content" rows="5" cols="30" required></textarea><br>

            <input type="submit" value="Υποβολή">
        </form>
    <?php endif; ?>
</body>
</html>
