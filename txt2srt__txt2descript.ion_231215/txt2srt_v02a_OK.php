<?php
/*

v01d - Modified script to allow multiple occurrences of each line

*/

$input_file = '!_ads3.txt';
$output_file = 'output.srt';
$interval_seconds = 10;
$end_time = 2900; // Set your desired end time for subtitles in seconds

$lines_array = [];

$handle_input = fopen($input_file, "r");

// Read lines from the input file and store in an array
while (!feof($handle_input)) {
    $line = trim(fgets($handle_input));
    if (!empty($line)) {
        $lines_array[] = $line;
    }
}

fclose($handle_input);

$handle_output = fopen($output_file, "w");

$line_number = 1;
$time_start = 0;
$time_end = $interval_seconds;

while ($time_start <= $end_time) {
    // Pick a random line from the array
    $random_line = generateRandomSubtitle($lines_array);

    // Write subtitles to the output file
    fwrite($handle_output, $line_number . "\n");
    fwrite($handle_output, formatSubtitleTime($time_start) . " --> " . formatSubtitleTime($time_end) . "\n");
    $insert_line="{\an7}$line_number- " . $random_line . "\n";
    echo $insert_line."<br>";
    fwrite($handle_output,$insert_line);

    $line_number++;
    $time_start = $time_end;
    $time_end += $interval_seconds;
}

fclose($handle_output);

function formatSubtitleTime($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds / 60) % 60);
    $seconds = $seconds % 60;

    return sprintf("%02d:%02d:%02d,000", $hours, $minutes, $seconds);
}

function generateRandomSubtitle(&$lines_array) {
    // If the array is empty, reset it with the original lines
    if (empty($lines_array)) {
        reset($lines_array);
    }

    // Pick a random index from the array
    $random_index = array_rand($lines_array);

    // Get and remove the line from the array
    $random_line = $lines_array[$random_index];
    // Uncomment the next line if you want to allow repeated occurrences of the same line
    // unset($lines_array[$random_index]);

    return $random_line;
}
?>
