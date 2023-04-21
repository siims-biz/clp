# clp
php command line parser for $argv in composer siims/clp

# Usage 2023-04-21
```
Option1
# Scroll down for Example1.php
composer create-project siims/clp .
php Example1.php --help hello=world debug
php Example2.php --help hello="hello world" --try-run

Option2
composer require siims/clp

With create-project you get a copy of the original composer.json.
With require you get a new composer.json or the existing composer.json gets updated.
```
# Known Issues
The tool has not been designed to work with single apostophs.
```
php Example1.php --help hello='her is my world'
--> may not work as expected
php Example1.php --help hello="'her is my world'"
--> may work as expected

The package also does not support something like
php Example1.php -file Example2.php
--> will not work as expected
```
# Alternative PHP Packages
* https://www.php.net/manual/en/function.getopt.php
* https://pear.php.net/manual/en/package.php.php-compatinfo.command-line.php (a bit out of date)
* https://github.com/phalcon/cli-options-parser
* https://github.com/vanilla/garden-cli

# Example1.php
```
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

```
