<?php
    class FTPTool {
        private $stream;
        public function __construct($init=true) {
            if($init) $this->init();   
        }
        public function init() {
            require_once dirname(__FILE__) . "/../config/ftpconfig.php";
            $check = $this->connect($host,$port,$timeout);
            if(is_string($check)) return $check;
            $check = $this->login($usr,$pss);
            if(is_string($check)) return $check;
        }
        private function connect($host,$port,$timeout) {
            if(!($this->stream = ftp_connect($host,$port,$timeout))) return "cannot connect to server";
        }
        public function login($usr,$pss) {
            if(!ftp_login($this->stream,$usr,$pss)) return "cannot login";
        }
        public function upload($orig,$dest) {
            return ftp_put($this->stream,$dest,$orig,FTP_BINARY);
        }
        public function delete($name) {
            return ftp_delete($this->stream,$name);
        }
        public function chdir($name) {
            return ftp_chdir($this->stream,$name);
        }
    }