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
        "onNoOptions" => "displayDefault"
    ]
    ];

class Parser extends clp {

    function displayDefault() {
        echo "{$this->getFilename()} expects to get called with command line parameters. \nTry {$this->getFilename()} --help for more information\n";
    }
    function displayHelp() {
        echo "{$this->getFilename()} hello=your_name | --h | -help | verbose | debug\n";
    }

    function parsingCommandLineFinished() {
        echo "Finished parsing command line.\nvar_dump:\n";
        var_dump($this->getConfig());
    }

    function callHello() {
        echo "Hello {$this->getConfig()["values"]["hello"]}. I hope you are well.\n";
    }
        
}    
$hello = new Parser($argv,$options);

