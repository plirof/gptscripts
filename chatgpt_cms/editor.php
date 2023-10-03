<?php
session_start();

// Έλεγχος αν ο χρήστης έχει συνδεθεί
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Επεξεργαστής</title>
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
</head>
<body>
    <h1>Επεξεργαστής</h1>

    <a href="index.php">Αρχική Σελίδα</a>

    <div id="editor"></div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{ 'header': 1 }, { 'header': 2 }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'script': 'sub' }, { 'script': 'super' }],
            [{ 'indent': '-1' }, { 'indent': '+1' }],
            [{ 'direction': 'rtl' }],
            [{ 'size': ['small', false, 'large', 'huge'] }],
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'font': [] }],
            [{ 'align': [] }],
            ['clean']
        ];

        var quill = new Quill('#editor', {
              theme: 'snow'
            });
        /*
        var quill = new Quill('#editor', {
            modules: {
                toolbar: toolbarOptions
            },
            theme: 'snow'
        });
        */
    </script>
</body>
</html>
