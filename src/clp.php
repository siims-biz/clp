<?php
declare(strict_types=1);
namespace Siims\clp;

/**
 * Class clp Command Line Parser for $argv
 * The constructor expects three array Arguments, the command line, your supported parameters and a container for the parsed arguments
 * Both $options and $config is an associative array
 * @see Documentation and Examples for details
 *
 * @package clp
 * @author  maat.junit@gmail.com
 * @license OSL-3.0
 * @version 1.0.0
 * 
 */
class clp {
    public function __construct(array $argv, array $options, array $config = []) {
        $this->argv=$argv;
        $this->config=$config;
        $this->noOptions=true;
        $this->process($options);
    }

    public function process($options) {
        if(isset($options["flags"])) { $this->checkFlags($options["flags"]); }
        if(isset($options["values"])) { $this->assignValues($options["values"]); }
        if(isset($options["events"]["onAfterValues"])) { $this->callEvent($options["events"]["onAfterValues"]); }
        if(isset($options["actions"])) { $this->checkActions($options["actions"]); }
        if($this->noOptions) {
            if(isset($options["events"]["onNoOptions"])) { $this->callEvent($options["events"]["onNoOptions"]); }
        }
        if(isset($options["events"]["onAfterProcess"])) { $this->callEvent($options["events"]["onAfterProcess"]); }        
    }

    public function getConfig():array {
        return $this->config;
    }

    public function getFilename():string { return $this->argv[0];}
        
    private function checkFlags($flags) {
        foreach($flags as $flag) {
            $this->checkFlag($flag);
        }        
    }
        
    private function checkFlag($flag) {
        $this->config["flags"][$flag]=false;
        $clone=array($flag);
        array_push($clone,"/" . $flag);
        array_push($clone,"-" . $flag);
        array_push($clone,"--" . $flag);
        if(count(array_intersect($clone,$this->argv)) > 0 ? true : false) {
            $this->config["flags"][$flag]=true;
            $this->noOptions=false;
        }
    }
        
    private function checkActions($actions) {
        foreach($actions as $keys=>$action) {
            foreach(explode("|",$keys) as $key) {
                $this->checkAction($key,$action);
            }
            if(count(explode(" ",$keys)) >=2 ) { $this->checkAndActions(explode(" ",$keys),$action);};
        }
    }
    
    private function checkAction($key,$action) {
        $clone=array($key);
        array_push($clone,"/" . $key);
        array_push($clone,"-" . $key);
        array_push($clone,"--" . $key);
        if(count(array_intersect($clone,$this->argv)) > 0 ? true : false) {
            $this->noOptions=false;
            $this->callEvent($action);
        }
        if(isset($this->config["values"])) {
            if(array_key_exists($key,$this->config["values"])) {
                $this->noOptions=false;
                $this->callEvent($action);
            }
        }
    }

    private function checkAndActions(array $para,$action) {
        $clone=$para;
        for($i=0;$i<count($para);$i++) {
            array_push($clone,"/" . $para[$i]);
            array_push($clone,"-" . $para[$i]);
            array_push($clone,"--" . $para[$i]);
        }

        if(isset($this->config["values"])) {
        if(count(array_merge(array_intersect($clone,$this->argv),array_intersect($clone,array_keys($this->config["values"])))) >= count($para) ? true : false) {
            $this->noOptions=false;
            $this->callEvent($action);
        };
        } else {
            if(count(array_intersect($clone,$this->argv)) === count($para) ? true : false) {
                $this->noOptions=false;
                $this->callEvent($action);
            };
    
        }
    }

    private function callEvent($event) {
        if(function_exists($event)) { if(is_callable($event)) {$event($this->config,$event);}; }
        if(method_exists($this,$event)) {
            $this->$event($this->config,$event);
        }
    }

    private function assignValues($values) {
        foreach($values as $keys=>$value) {
            $this->assignValue($keys);
        }        
    }
    
    private function assignValue($value) {
        if($this->argvHasPara($value)) {
            $this->config["values"][$value]=$this->argvGetPara($value);
            $this->noOptions=false;
        }
    }

    private function argvHasPara(string $para):bool {
        $clone=[];
        array_push($clone,"\/" . $para . "=\S");
        array_push($clone,"-" . $para . "=\S");
        array_push($clone,"--" . $para . "=\S");
        array_push($clone,"-set-" . $para . "=\S");
        array_push($clone,"--set-" . $para . "=\S");
        array_push($clone, $para . "=\S");
        for($i=0;$i<count($this->argv);$i++) {
            for($j=0;$j<count($clone);$j++) {
                $pattern='/' . $clone[$j] . '/';
                if(preg_match($pattern,$this->argv[$i])) { return true;} 
            }
        }
        return false;
    }   

    private function argvGetPara($get):string {
        foreach($this->argv as $arg) {
            if(strpos($arg,$get)!==false) {
                $matches=explode("=",$arg);
                if(count($matches)>=2) {
                    return $matches[1];
                }
            }
        }
    }
}
