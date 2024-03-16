<?php
$input_file = 'input.txt';
$output_file = 'output.srt';
$interval_seconds = 5;

$handle_input = fopen($input_file, "r");
$handle_output = fopen($output_file, "w");

$line_number = 1;
$time_start = 0;
$time_end = $interval_seconds;

while (!feof($handle_input)) {
    $line = trim(fgets($handle_input));
    
    if (!empty($line)) {
        fwrite($handle_output, $line_number . "\n");
        fwrite($handle_output, formatSubtitleTime($time_start) . " --> " . formatSubtitleTime($time_end) . "\n");
        fwrite($handle_output, $line . "\n
");
        
        $line_number += 1;
        $time_start = $time_end;
        $time_end += $interval_seconds;
    }
}

fclose($handle_input);
fclose($handle_output);

function formatSubtitleTime($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds / 60) % 60);
    $seconds = $seconds % 60;

    return sprintf("%02d:%02d:%02d,000", $hours, $minutes, $seconds);
}
?>