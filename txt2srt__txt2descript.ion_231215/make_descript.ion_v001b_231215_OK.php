<?php
/*
v001b - 231215 -ChatGPT


*/
$debug=true;
// Διαβάζουμε το αρχείο sourcetext.txt γραμμή προς γραμμή
$inputFile = 'sourcetext.txt';
$inputLines = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Δημιουργούμε τον πίνακα inputarray
$inputArray = array();

foreach ($inputLines as $line) {
    // Δημιουργούμε την εγγραφή για τον πίνακα
    $inputArray[] = $line;
}

// Ορίζουμε τον φάκελο αρχείων
$folder = '/tmp/a/myfolder_with_images/';

// Ανοίγουμε το αρχείο descript.ion για εγγραφή
$descriptFile = fopen('descript.ion', 'a');

// Εξερευνούμε τον φάκελο και επεξεργαζόμαστε το κάθε αρχείο με κατάληξη jpg, gif ή png
$fileList = glob($folder . '/*.jpg');
$fileList = array_merge($fileList, glob($folder . '/*.JPG'));
$fileList = array_merge($fileList, glob($folder . '/*.GIF'));
$fileList = array_merge($fileList, glob($folder . '/*.PNG'));
$fileList = array_merge($fileList, glob($folder . '/*.gif'));
$fileList = array_merge($fileList, glob($folder . '/*.png'));

foreach ($fileList as $file) {
    // Προσθέτουμε τη γραμμή με το όνομα του αρχείου και μια τυχαία γραμμή από τον πίνακα inputarray
    $randomIndex = array_rand($inputArray);
    $lineToAdd = '"' . basename($file) . '" ' . $inputArray[$randomIndex] . "\n";
    if($debug) echo "<BR>".$lineToAdd;
    fwrite($descriptFile, $lineToAdd);
}

// Κλείνουμε το αρχείο descript.ion
fclose($descriptFile);

echo "<hr>";

echo "<hr>";

echo "<hr>";
print_r($inputLines);
echo "<hr>";
print_r($inputArray);
echo "<hr>";

?>
