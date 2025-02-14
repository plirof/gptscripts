<?php
/*

v006_240327f -
v004_240327c - 

*/
$debug=true;

if($debug) { echo "http://192.168.1.200/img/gpt_get_swf_url.php?gamenick=stick2&passcode=1234 <hr>http://192.168.1.200/img/gpt_get_swf_url.php?gamenick=artpad&passcode=1234  <hr><hr> <hr>";}
// ?gamenick=stick2&passcode=1234
//http://192.168.1.200/img/gpt_get_swf_url.php?gamenick=stick2&passcode=1234
$gamecodeURL = array(
    "pacman" => "https://www.example.com/pacman",
    "stick2" => "https://stick2.sxoleio.tk/",
    "tortuga"=> "https://tortuga.sxoleio.tk/",
    // Add more gamecode/url pairs as needed
);
echo "aaa";
@$gamenick = $_REQUEST['gamenick'];
@$passcode = $_REQUEST['passcode'];
$my_passcode = "1234";

// Check if the passcode is correct
if($passcode != $my_passcode) {  echo "<br>wrong gamecode"; exit; }
//http://192.168.1.200/swf/opengame_ruffle.php?file=./graphics_/comic_design__garfield_comic_creator_y8.swf
$url_prefix="http://192.168.1.200/swf/opengame_ruffle.php?file=" ;//used only when we read the file
$url_prefix="https://swf-dim.sxoleio.tk/opengame_ruffle_cdn.php?file=./";


if(array_key_exists($gamenick, $gamecodeURL)) {
    $url = $gamecodeURL[$gamenick];
    // Check if the passcode is correct
    if($passcode == $my_passcode) {
        header("Location: $url");
        exit;
    } else {
        echo "Invalid passcode";
    }
} else {    
    $file = "gamefile.txt"; // File containing list of gamenick and URL
    $data = file_get_contents($file);
    $lines = explode("\n", $data);

    foreach($lines as $line) {
        list($nick, $url) = explode("=", $line);
        if(trim($nick) == $gamenick) {
                header("Location: ".$url_prefix."$url");
        }
    }

    echo "Game not found";
}
?>
