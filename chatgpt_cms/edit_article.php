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

// Αναζήτηση του άρθρου με βάση το ID
$articleId = $_GET['id'];
$article = null;

foreach ($articles as $item) {
    if ($item['id'] === $articleId) {
        $article = $item;
        break;
    }
}

if (!$article) {
    header('Location: index.php');
    exit;
}

// Αποθήκευση των αλλαγών στο άρθρο
if (isset($_POST['title']) && isset($_POST['content'])) {
    $updatedArticle = [
        'id' => $articleId,
        'title' => $_POST['title'],
        'content' => $_POST['content']
    ];

    // Ενημέρωση του άρθρου στην flat-file βάση δεδομένων
    foreach ($articles as &$item) {
        if ($item['id'] === $articleId) {
            $item = $updatedArticle;
            break;
        }
    }

    $articlesData = json_encode($articles, JSON_UNESCAPED_UNICODE);
    file_put_contents('articles.txt', $articlesData);

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Επεξεργασία Άρθρου</title>
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

</head>
<body>
    <h1>Επεξεργασία Άρθρου</h1>

    <a href="index.php">Αρχική Σελίδα</a>

    <form action="edit_article.php?id=<?php echo $articleId; ?>" method="POST">
        <label for="title">Τίτλος:</label>
        <input type="text" name="title" id="title" value="<?php echo $article['title']; ?>" required><br>

        <label for="content">Περιεχόμενο:</label><br>
        <div id="editor" style="height: 300px;"><?php echo $article['content']; ?></div>
        <textarea name="content" id="content" style="display: none;"></textarea><br>
        

        <input type="submit" value="Αποθήκευση">
    </form>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow'
        });

        var contentInput = document.getElementById('content');
        quill.on('text-change', function() {
            contentInput.value = quill.root.innerHTML;
        });
    </script> 

</body>
</html>
