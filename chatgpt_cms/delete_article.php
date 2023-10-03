<?php
session_start();

// Έλεγχος αν ο χρήστης έχει συνδεθεί
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Έλεγχος αν έχει παρασχεθεί ID άρθρου
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Φόρτωση των άρθρων από την flat-file βάση δεδομένων
$articles = file_get_contents('articles.txt');
$articles = json_decode($articles, true);

// Αναζήτηση και διαγραφή του άρθρου με βάση το ID
$articleId = $_GET['id'];
$articleIndex = null;

foreach ($articles as $index => $item) {
    if ($item['id'] === $articleId) {
        $articleIndex = $index;
        break;
    }
}

if ($articleIndex !== null) {
    array_splice($articles, $articleIndex, 1);

    // Ενημέρωση της flat-file βάσης δεδομένων
    $articlesData = json_encode($articles);
    file_put_contents('articles.txt', $articlesData);
}

header('Location: index.php');
exit;
