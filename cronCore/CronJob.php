<?php
    require_once dirname(__FILE__) . "/../core/db/QueryHelper.php";
    require_once dirname(__FILE__) . "/../core/BaseUtils.php";
    class Cronjob Extends BaseUtils {
        protected $qh;
        
        public function __construct() {
            self::vm_log("starting query helper");
            $this->qh = new QueryHelper();
            self::vm_log("query helper started");
            self::vm_log("starting cronjob now");
        }
        public function loadTool($tname) {
            $toolPath = dirname(__FILE__) . '/../tools/' . $tname . "Tool.php";
            if(!file_exists($toolPath))
                return null;
            require_once $toolPath;
            return true;
        }
        public function getArgs() {
            return $argv;
        }
    }