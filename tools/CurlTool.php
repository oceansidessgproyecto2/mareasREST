<?php
    class CurlTool {
        private $ch;
        private $url;
        private $requestMethod;
        private $files;
        private $postFields;
        private $response;
        
        public function  __construct($url,$requestMethod) {
            $requestMethod = strtoupper($requestMethod);
            if(!constant("CURLOPT_" . $requestMethod)) {
                return "metodo no encontrado";
            }
            $this->ch = curl_init();
            $this->url = $url;
            $this->requestMethod = $requestMethod;
            curl_setopt($this->ch, CURLOPT_URL,$this->url);
        }
        public function addFile($partname,$path) {
            if(!file_exists($path))
                return false;
            else {
                $this->postFields [$partname] = curl_file_create($path);
                return true;
            }
        }
        public function addPostField($k,$v) {
            $this->postFields [$k] = $v;
        }
        public function execPOST() {
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->postFields);
            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
            $this->response = curl_exec($this->ch);
        }
        public function getResponse() {
            return $this->response;
        }
        public function getFiles() {
            return $this->files;
        }
    }