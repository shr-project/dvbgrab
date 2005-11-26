#!/usr/bin/php
<?php
//Grabs http stream
//Usage: httpGrab -b BEGIN_TIME -e END_TIME -i INPUT_CHANNEL -o OUTPUT_FILE
//Time format: "Y-m-d H:i:s" or any English textual datetime
//Channels: ct1, ct2, ct24, nova, prima
//Example:
// ./httpGrab.php -b '+10 seconds' -e '+100 seconds' -i nova -o test.ps

define("arg_BEGIN_TIME", "-b");
define("arg_END_TIME", "-e");
define("arg_INPUT_CHANNEL", "-i");
define("arg_OUTPUT_FILE", "-o");

define("URL_PREFIX", "http://localhost:1234/");

/**
* Parses command line options.
* @returns array of key=value pairs
*/
function parseArgs($argv) {
    $options = array();
    for ($i = 1; $i < count($argv); $i++) {
        if ($i % 2 == 0) {
            $options[$key] = $argv[$i];
        }
        else {
            $key = $argv[$i];
        }
    }
    return $options;
}

function waitForBegin($begin_time) {
    $delay = $begin_time - time();
    if ($delay > 0) {
        echo "Sleeping ${delay}s\n";
        sleep($delay);
    }
}

/**
* Dumps http stream until end_time.
* @param $input_channel channel number
* @param $output_file filename
* @param $end_time end time as number of seconds since epoch
*/
function dumpStream($input_channel, $output_file, $end_time) {
    $output = fopen($output_file, "w");
    if (!$output) {
        die("Error: cannot open output file: '$output_file'\n");
    }

    $stream_url = URL_PREFIX . $input_channel;
    $stream = fopen($stream_url, "r");
    if (!$stream) {
        die("Error: cannot open stream url: '$stream_url'\n");
    }

    $start = time();
    $length = $end_time - $start;
    $progress = 0;

    //NOTE: bigger buffer than 8192 does not take effect
    echo "Dumping $stream_url > $output_file\n";
    while (!feof($stream) && time() <= $end_time) {
        fwrite($output, fread($stream, 8192));

        $percent = 100 * (time() - $start) / $length;
        while ($percent >= $progress) {
            $progress += 1;
            echo ".";
        }
    }
    echo "\n";

    if (feof($stream)) {
        echo "Warning: end of stream\n";
    }

    fclose($output);
    fclose($stream);
}

function main($argv) {
    $options = parseArgs($argv);

    $begin_time = time();
    $end_time = time() + 24*3600;
    $input_channel = 1;
    $output_file = "/dev/null";

    if (isset($options[arg_BEGIN_TIME])) {
        $begin_time = strtotime($options[arg_BEGIN_TIME]);
    }
    if (isset($options[arg_END_TIME])) {
        $end_time = strtotime($options[arg_END_TIME]);
    }
    if (isset($options[arg_INPUT_CHANNEL])) {
        $input_channel = $options[arg_INPUT_CHANNEL];
    }
    if (isset($options[arg_OUTPUT_FILE])) {
        $output_file = $options[arg_OUTPUT_FILE];
    }

    echo "begin_time=$begin_time\n";
    echo "end_time=$begin_time\n";
    echo "input_channel='$input_channel'\n";
    echo "output_file='$output_file'\n";

    if ($begin_time >= $end_time) {
        die("Error: cannot set begin time after end time!\n");
    }

    waitForBegin($begin_time);
    dumpStream($input_channel, $output_file, $end_time);
}

//Clean start without any global variable
main($argv);

?>
