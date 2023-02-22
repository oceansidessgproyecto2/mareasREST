<?php
    class SessionManager {
        private $qh;
        private $headers;
        private $validToken = false;
        private $token_errors = [];
        public function __construct($qh) {
            $this->qh = $qh;
            $this->init();
        }
        public function init() {
            $this->headers = $this->getRequestHeaders();
            if(!isset($this->headers ["X-Marea-Auth"]))
                $this->token_errors = ["API key not sent"];
            //var_dump($this->token_errors);
            else {
                $token = null;
                if(!($token = $this->qh->fetch("SELECT * FROM tokens WHERE token=sha1(:token) AND expires > now();",["token"=>$this->headers ["X-Marea-Auth"]]))) {
                    $this->token_errors = ["not a valid API key"];
                } else $this->validToken = true;
                if(isset($token ["id"])) $this->qh->insert("INSERT INTO connections VALUES(:token,now(),:ip)",["token"=>$token ["id"],"ip"=>$_SERVER ["REMOTE_ADDR"]]);
            }
        }
        private function getRequestHeaders() {
            $headers = array();
            foreach($_SERVER as $key => $value) {
                if (substr($key, 0, 5) <> 'HTTP_') {
                    continue;
                }
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $headers[$header] = $value;
            }
            return $headers;
        }
        public function isValid() {
            return $this->validToken;
        }
        public function getTokenErrors() {
            return $this->token_errors;
        }
    }
