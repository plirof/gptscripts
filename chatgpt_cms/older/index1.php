<?php
session_start();

// Έλεγχος αν ο χρήστης έχει συνδεθεί
$isLoggedIn = isset($_SESSION['username']);

// Φόρτωση των άρθρων από την flat-file βάση δεδομένων
$articles = file_get_contents('articles.txt');
$articles = json_decode($articles, true);

// Λειτουργία αποθήκευσης του άρθρου
if (isset($_POST['title']) && isset($_POST['content'])) {
    $newArticle = [
        'id' => uniqid(),
        'title' => $_POST['title'],
        'content' => $_POST['content']
    ];

    $articles[] = $newArticle;

    $articlesData = json_encode($articles);
    file_put_contents('articles.txt', $articlesData);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Αρχική Σελίδα</title>
</head>
<body>
    <h1>Αρχική Σελίδα</h1>

    <?php if ($isLoggedIn): ?>
        <p>Καλώς ήρθες, <?php echo $_SESSION['username']; ?>! <a href="logout.php">Αποσύνδεση</a></p>
    <?php else: ?>
        <a href="login.php">Σύνδεση</a>
    <?php endif; ?>

    <h2>Άρθρα</h2>

    <?php if ($isLoggedIn): ?>
        <a href="editor.php">Επεξεργαστής</a>
    <?php endif; ?>

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
