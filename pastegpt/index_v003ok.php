<?php
/*
pastegpt
Changes:
-v003 250213 - Ok working (with normal textareas)




*/
session_start();

// Define static passwords
define('STATIC_PASSWORDS', ['password1', 'password2', 'password3','1']);
define('PASTES_DIR', 'pastes/');

// Ensure the "pastes" directory exists
if (!is_dir(PASTES_DIR)) {
    mkdir(PASTES_DIR, 0777, true);
}

// Helper function to sanitize output
function sanitize($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Add paste handling
if (isset($_POST['submit_paste'])) {
    $title = $_POST['title'];
    $language = $_POST['language'];
    $content = $_POST['content'];
    $passkey = $_POST['passkey'];

    // Validate the passkey
    if (!in_array($passkey, STATIC_PASSWORDS)) {
        $error_message = "Invalid passkey!";
    } else {
        $fileName = time() . ".txt";
        $filePath = PASTES_DIR . $fileName;
        $data = "$title\n$language\n$content";
        file_put_contents($filePath, $data);
        header("Location: #");
        exit();
    }
}

// Edit paste handling
if (isset($_GET['edit']) && file_exists(PASTES_DIR . $_GET['edit'])) {
    $pasteFile = PASTES_DIR . $_GET['edit'];
    $lines = file($pasteFile);
    $title = trim($lines[0]);
    $language = trim($lines[1]);
    $content = implode("\n", array_slice($lines, 2));

    if (isset($_POST['edit_paste'])) {
        $newTitle = $_POST['title'];
        $newLanguage = $_POST['language'];
        $newContent = $_POST['content'];
        $data = "$newTitle\n$newLanguage\n$newContent";
        file_put_contents($pasteFile, $data);
        header("Location: ?view=" . $_GET['edit']);
        exit();
    }
}

// Viewing paste handling
if (isset($_GET['view']) && file_exists(PASTES_DIR . $_GET['view'])) {
    $pasteFile = PASTES_DIR . $_GET['view'];
    $lines = file($pasteFile);
    $title = trim($lines[0]); echo "<h2>TITLE view= $title </h2>";
    $language = trim($lines[1]);
    $content = implode("\n", array_slice($lines, 2));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Pastebin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism.css">
    <script src="https://cdn.jsdelivr.net/npm/prismjs/prism.js"></script>
</head>
<body>
    <h1><a href="index.php" >Simple Pastebin</a></h1>

    <h2>Latest Pastes</h2>
    <?php
    $pastes = array_diff(scandir(PASTES_DIR), ['.', '..']);
    rsort($pastes);
    echo "<table border=1 >";
    foreach ($pastes as $paste) {
        $lines1 = file(PASTES_DIR . $paste);
        $title1 = trim($lines1[0]);
        echo "<tr><td><div>
                " . sanitize($title1) . "</td>
                <td><button onclick=\"window.location.href='?view=$paste'\">View</button></td>
                <td><button onclick=\"window.location.href='?edit=$paste'\">Edit</button></td>
                
            </div></tr>";
    }
    echo "</table>";
    ?>

    <?php if (isset($_GET['view'])) { ?>
        <h2><?php echo sanitize($title); ?></h2>
        <pre class="language-<?php echo sanitize($language); ?>"><?php echo sanitize($content); ?></pre>
        <br>
        <a href="?edit=<?php echo sanitize($_GET['view']); ?>">Edit Paste</a>
    <?php } elseif (isset($_GET['edit']) && file_exists(PASTES_DIR . $_GET['edit'])) { ?>
        <h2>Edit Paste</h2>
        <form method="POST">
            <label for="title">Title:</label><br>
            <input type="text" name="title" value="<?php echo sanitize($title); ?>" required><br><br>

            <label for="language">Language:</label><br>
            <select name="language">
                <option value="php" <?php if ($language == 'php') echo 'selected'; ?>>PHP</option>
                <option value="javascript" <?php if ($language == 'javascript') echo 'selected'; ?>>JavaScript</option>
                <option value="python" <?php if ($language == 'python') echo 'selected'; ?>>Python</option>
                <option value="html" <?php if ($language == 'html') echo 'selected'; ?>>HTML</option>
            </select><br><br>

            <textarea name="content" rows="10" cols="50"><?php echo sanitize($content); ?></textarea><br><br>

            <label for="passkey">Passkey:</label><br>
            <input type="password" name="passkey" required><br><br>

            <input type="submit" name="edit_paste" value="Save Changes">
        </form>
    <?php } else { ?>
        <h2>Add New Paste</h2>
        <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>
        <form method="POST">
            <label for="title">Title:</label><br>
            <input type="text" name="title" required><br><br>

            <label for="language">Language:</label><br>
            <select name="language">
                <option value="php">PHP</option>
                <option value="javascript">JavaScript</option>
                <option value="python">Python</option>
                <option value="html">HTML</option>
            </select><br><br>

            <textarea name="content" rows="10" cols="50"></textarea><br><br>

            <label for="passkey">Passkey:</label><br>
            <input type="password" name="passkey" required><br><br>

            <input type="submit" name="submit_paste" value="Save Paste">
        </form>
    <?php } ?>

</body>
</html>
