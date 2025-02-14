<?php
/*
pastegpt
Changes:
-v005a 250215 - Filename is the title
-v004f        - list languages supported 
-v004e 250214 - Pass the language saved to edit_area for syntax hightlighting
-v004d 250214 - Seems ok saving accuratelly
-v004c 250214 - Fixed save on edit. Now must save accurate
-v004b 250214 - DOes not saves edited files
-v004a 250214 - Using editarea (need to auto-highlight languages)
-v003  250213 - Ok working (with normal textareas)

ToDO:
-Bigger paste window, paste list shown in footer

*/
session_start();
$debug=false;
$language="php";

if ($debug) {echo "<h2>"; print_r($_REQUEST);echo "</h2>";}
// Define static passwords
define('STATIC_PASSWORDS', ['password1', 'password2', 'password3','1']);
define('PASTES_DIR', 'pastes/');

// Ensure the "pastes" directory exists
if (!is_dir(PASTES_DIR)) {
    mkdir(PASTES_DIR, 0777, true);
}

// Helper function to sanitize output
function sanitize($string) {
    return $string;
    //return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function getLangCode($string) {
    $array = ['php', 'html', 'basicsinclair', 'basic','js','javascript'];
    if(strtolower($string)=='javascript' ) $string="js";
    if(in_array(strtolower($string), array_map('strtolower', $array)) )     return $string;
    return "php";
    //return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

//Generate <ptions for dropdown box
function generateLanguageOptions($selectedLanguage) {
    $options = '';

    $directory='edit_area/reg_syntax/';
    // Check if the directory exists
    if (is_dir($directory)) {
        // Scan the directory for .js files
        $files = glob($directory . '*.js');
        
        // Loop through each file found
        foreach ($files as $file) {
            // Get the filename without the directory path
            $filename = basename($file);
            
            // Remove the .js extension to get the language
            $langname = pathinfo($filename, PATHINFO_FILENAME);
            
            // Generate the option line
            $options .= '<option value="'.$langname.'"'. (strtolower($selectedLanguage) == strtolower($langname) ? 'selected' : '') . ">" . ucfirst($langname) . "</option>\n";
                       //
        }
    } else {
        return "Directory does not exist.";
    }

    // Return the options string
    return $options;
}

//use title as filename
function sanitize_filename($title) {
    // Replace spaces with underscores (optional)
    $title = str_replace(' ', '_', $title);

    // Remove any characters that are not allowed in filenames
    $title = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $title);

    // Truncate the filename to a reasonable length (e.g., 255 characters)
    $title = substr($title, 0, 80);

    return $title;
}

// Usage
$title = "My New Title: <Invalid?>|";
$filename = sanitize_filename($title);
echo $filename; // Output: My_New_Title_Invalid

//if($debug)    echo "<h2>line 70 generateLanguageOptions: <pre>".generateLanguageOptions($GLOBALS['language'])."</pre></h2>";
// Add paste handling
if (isset($_REQUEST['submit_paste'])) {
    $title = $_POST['title'];
    $language = $_POST['language'];
    $content = $_REQUEST['content_entered'];
    if($debug) echo "<h2>line 40 RECEIVED-content_entered: <pre>".sanitize($content)."</pre></h2>";
    $passkey = $_POST['passkey'];

    // Validate the passkey
    if (!in_array($passkey, STATIC_PASSWORDS)) {
        $error_message = "Invalid passkey!";
    } else {

        //$fileName = time() . ".txt";
        $fileName = sanitize_filename($title)."-".date('ymd') . ".txt"; //
        $filePath = PASTES_DIR . $fileName;
        $data = "$title\n$language\n$content";
        if ($debug) {echo "<h2>filename= $fileName <br>data=$data</h2>";}
        file_put_contents($filePath, $data);
      /* if(!$debug)*/  header("Location: #");
        exit();
    }
}

// Edit paste handling
if (isset($_REQUEST['edit']) && file_exists(PASTES_DIR . $_REQUEST['edit'])) {
   
    $pasteFile = PASTES_DIR . $_GET['edit'];
    $lines = file($pasteFile);
    $title = trim($lines[0]);
    $language = trim($lines[1]);
    //$content = implode("\n", array_slice($lines, 2)); //oroginal Adds extra spaces
    $content = implode("", array_slice($lines, 2)); //fixed extra spaces bug jon 250214
    if($debug) echo "<h2>line 66 RECEIVED-content_entered: <pre>".sanitize($content)."</pre></h2>";
    if($debug)echo "<h2>--EDIT--</h2>";

    if (isset($_POST['edit_paste_save'])) {
        if($debug)echo "<h2>--EDIT_PASTE_SAVE--</h2>";
        $newTitle = $_POST['title'];
        $newLanguage = $_POST['language'];
        $newContent = $_POST['content_entered'];
        $data = "$newTitle\n$newLanguage\n$newContent";
        file_put_contents($pasteFile, $data);
         /* if(!$debug) */header("Location: ?view=" . $_GET['edit']);
        if($debug)echo "<h2>CONTEST to update : <pre>$newContent</pre></h2>";
        exit();
    }
}

// Viewing paste handling
if (isset($_REQUEST['view']) && file_exists(PASTES_DIR . $_GET['view'])) {
    $pasteFile = PASTES_DIR . $_GET['view'];
    $lines = file($pasteFile);
    $title = trim($lines[0]); echo "<h2>TITLE view= $title </h2>";
    $language = trim($lines[1]);
    //$content = implode("\n", array_slice($lines, 2));
    $content = implode("", array_slice($lines, 2)); //fixed extra spaces bug jon 250214
}

 @$langcode=getLangCode($language);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Pastebin</title>

    <script language="javascript" type="text/javascript" src="edit_area/edit_area_full.js"></script>

      <script>
        editAreaLoader.init({
             id : "content_entered"     // textarea id content_entered
            ,syntax: "basicsinclair"            // syntax to be uses for highgliting
            ,start_highlight: true // to display with highlight mode on start-up
            ,allow_resize: "both"
            ,toolbar: "search, go_to_line, fullscreen, |, undo, redo, |, select_font,|, change_smooth_selection, highlight, reset_highlight, word_wrap,syntax_selection, |, help"
            ,min_height:800
            ,min_width:1000
            ,font_size: 18
            ,allow_toggle:false
            ,syntax: "<?php echo $langcode; ?>"

        });



            function mySubmit(){
                //alert("hello");
                //document.getElementById('theForm').submit();
                var x = document.getElementsByName('theForm');
                editAreaLoader.setValue(editAreaLoader.getValue('content_entered'));
                //alert(x[0].name+"text="+editAreaLoader.getValue('content_entered'));
                x[0].submit(); // Form submission
            }
            
      </script>

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
        <hr><h2><?php echo "Title : ".sanitize($title); ?></h2>
        <!-- <pre class="language-<?php echo sanitize($language); ?>">            <!--< ? php echo sanitize($content); ?>-->-->
             <textarea name="content_entered" id="content_entered"  rows="10" cols="50"  wrap="hard"  ><?php echo sanitize($content); ?></textarea>    
            <!--</pre> -->
        <br>
        <a href="?edit=<?php echo sanitize($_GET['view']); ?>">Edit Paste</a>
    <?php } elseif (isset($_GET['edit']) && file_exists(PASTES_DIR . $_GET['edit'])) { ?>
        <h2>Edit Paste</h2>
        <form method="POST" name="theForm" id="theForm" onsubmit="editAreaLoader.getValue('content')" >
            <label for="passkey">Passkey:</label><br>
            <input type="password" name="passkey" required><br><br>
            <button onclick="mySubmit()" name="edit_paste_save"  >ΥΠΟΒΟΛΗ.</button><br>
            <label for="title">Title:</label><br>
            <input type="text" name="title" value="<?php echo sanitize($title); ?>" required><br><br>

            <label for="language">Language:</label><br>
            <select name="language">
                <option value="php" <?php if ($language == 'php') echo 'selected'; ?>>PHP</option>
                <option value="javascript" <?php if ($language == 'javascript') echo 'selected'; ?>>JavaScript</option>
                <option value="python" <?php if ($language == 'python') echo 'selected'; ?>>Python</option>
                <option value="html" <?php if ($language == 'html') echo 'selected'; ?>>HTML</option>
                
                <?php echo generateLanguageOptions($language); ?>



            </select><br><br>

            <textarea name="content_entered" id="content_entered"  rows="10" cols="50"  wrap="hard"  ><?php echo sanitize($content); ?></textarea><br><br>


            <!--<button type="submit" name="edit_paste" value="Save Changes"  onclick="mySubmit()" >-->
        </form>
    <?php } else { ?>
        <h2>Add New Paste</h2>
        <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>
        <form action="index.php" name="theForm" target="emulator_output" id="theForm" method="post" onsubmit="editAreaLoader.getValue('content_entered')">
        <!--<form method="POST" name="theForm" id="theForm" onsubmit="editAreaLoader.getValue('content_entered')"  >-->
            <label for="passkey">Passkey:</label><br>
            <input type="password" name="passkey" required><br><br>
            <button onclick="mySubmit()" name="submit_paste"  >ΥΠΟΒΟΛΗ.</button><br>

            <label for="title">Title:</label><br>
            <input type="text" name="title" required><br><br>

            <label for="language">Language:</label><br>
            <select name="language">
                <option value="php">PHP</option>
                <option value="javascript">JavaScript</option>
                <option value="python">Python</option>
                <option value="html">HTML</option>
               
                <?php echo generateLanguageOptions($language); ?>
            </select><br><br>

            <textarea name="content_entered" id="content_entered" rows="10" cols="50"  wrap="hard"  ></textarea><br><br>

            <!-- <input type="submit" name="submit_paste" value="Save Paste" onclick="mySubmit()" >-->
        </form>
    <?php } ?>

</body>
</html>
