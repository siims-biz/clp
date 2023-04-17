<?php
/* License: OSL-3.0
   To be launched in shell environment
   php ExampleHelloRequiredComposer.php
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
    echo "$argv[0] hello=your_name | --h | -help | verbose | debug\n";
}

function parsingCommandLineFinished() {
    echo "Finished parsing command line.\n";
    echo "This function won't have access to command line options\n";
}

function callHello() {
    echo "This gets called without knowing the name\n";
}
function ido($hello) {
    echo "This function does:\n";
var_dump($hello->getConfig());
}

ido($hello);