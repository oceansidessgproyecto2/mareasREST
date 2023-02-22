<?php
    require_once dirname(__FILE__) . "/../../core/BaseUtils.php";
    class CronDispatcher Extends BaseUtils {
        private $fileName;
        private $clasName;
        private $args;
        
        public function __construct($fileName,$args) {
            $this->fileName = $fileName;
            $this->args = $args;
            $this->dispatch();
        }
        public function dispatch() {
            $this->fileName = explode("/",$this->fileName);
            $this->fileName = $this->fileName [sizeof($this->fileName)-1];
            $this->fileName = ucfirst($this->fileName);
            $this->className = explode(".",$this->fileName);
            $this->className = ucfirst($this->className [0]);
            $classPath = dirname(__FILE__). "/../../cronJobs/" . $this->fileName;
            if(!$classPath) {
                self::vm_die("cron job not found\n");
            }
            require_once dirname(__FILE__). "/../../cronJobs/" . $this->fileName;
            if(!class_exists($this->className)) {
                self::vm_die("cron job class found\n");
                die;              
            }
            self::vm_log("about to run cronjob " . $this->className . PHP_EOL);
            $job = new $this->className($this->args);
        }
    }