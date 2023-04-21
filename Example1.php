<?php
/* License: OSL-3.0
   To be launched in shell environment
   php Example1.php
*/

require 'vendor/autoload.php';
use Siims\clp\clp;

$options = [
    "actions" => [
        "h|help" => "displayHelp",
        "hello" => "callHello"
    ],
    "flags" => [
        "try-run","verbose","debug"
    ],
    "values" => [
        "hello" => "world"
    ],
    "events" => [
        "onAfterProcess" => "parsingCommandLineFinished",
        "onNoOptions" => "displayHelp"
    ]
    ];

$hello = new clp($argv,$options);

function displayHelp() {
    global $argv;
    echo "$argv[0] hello=\"your_name\" | --h | -help | verbose | debug\n";
}

function parsingCommandLineFinished($config) {
    echo "Finished parsing command line.\n";
    print_r($config);
}

function callHello($config,$method) {
    echo "Hello {$config["values"]["hello"]}\n";
    echo "Implemented by $method\n";
}
