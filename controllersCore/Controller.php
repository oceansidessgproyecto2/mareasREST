<?php
    class Controller {
        protected $qh;
        protected $sm;
        protected $response;
        protected $complements = [];
        protected $extras = [];
        protected $bag = [];
        protected $AfterFiltersAllowed = false;
        
        
        protected function returnResponse() {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($this->response);
        }
        
        public function checkAuth() {
            if(!$this->sm->isValid()) {
                $this->response = ["errors" => $this->sm->getTokenErrors()];
                $this->returnResponse();
                return false;
            }
            return true;
        }
        
        public function runAfterFilters() {
            return $this->AfterFiltersAllowed;
        }
        protected function getComplements() {
            return $this->complements;
        }
        protected function getExtras() {
            return $this->complements;
        }
        protected function addExtra($name) {
            for($i=0;$i<count($this->extras);$i++) {
                if($this->extras [$i] == $name) {
                    return "existing";
                }
            }
            $this->extras [] = ucfirst($name);
            return true;
        }
        protected function runExtra($extra) {
            foreach($this->extras as $name) {
                if($name == $extra) {
                    $cname = ucfirst($extra);
                    if(!class_exists($cname))
                        require_once dirname(dirname(__FILE__)) . "/extras/" . $cname . ".php";
                    $extra = new $cname($this->bag);
                    $extra->run();   
                }
            }
        }
        protected function delExtra($name) {
            for($i=0;$i<count($this->extras);$i++) {
                if($extras [$i] == $name) {
                    unset($extras [$i]);
                    return true;
                }
                return "not found";
            }
        }
        protected function addComplement($string,$trigger) {
            if(in_array($string,$this->complements)) return "existing";
            $this->complements [$string] = $trigger;
            return true;
        }
        protected function delComplement($string) {
            if(!in_array($string,$this->complements)) return "not found";
            unset($string);
            
        }
        public function setBag($bag) {
            $this->bag = $bag;
        }
        public function doBefore() {
            $this->searchComplements("before");
        }
        public function doAfter() {
            $this->searchComplements("after");
        }
        private function searchComplements($type) {
            foreach($this->complements as $k=>$v) {
                if($v == $type) {
                    method_exists($this,$k.'Complement');
                    $this->{$k.'Complement'}();
                }
            }
        }
        protected function paramExists($param) {
            return isset($_REQUEST [$param]);
        }
        protected function getParam($param) {
            if(isset($_REQUEST [$param]))
                return $_REQUEST [$param];
            else return false;
        }
        protected function getPart($name) {
            if(!isset($_FILES [$name])) return null;
            return $_FILES [$name];
        }
        public function loadTool($tname) {
            $toolPath = dirname(__FILE__) . '/../tools/' . $tname . "Tool.php";
            if(!file_exists($toolPath))
                return null;
            require_once $toolPath;
            return true;
        }
    }