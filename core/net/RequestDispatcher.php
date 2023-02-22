<?php
    class RequestDispatcher {
        private $sm;
        public function __construct($qh,$sm) {
            $this->qh = $qh;
            $this->sm = $sm;
            $this->init();
        }
        private function init() {
            $controllerName = ucfirst(substr(explode(".",$_SERVER ["PHP_SELF"])[0],1));
            $controllerPath = dirname(dirname(dirname(__FILE__))) . "/controllers/" . $controllerName . ".php";
            if(!file_exists($controllerPath)) {
                echo json_encode(["msg"=>"controller not found"]);
                die;
            }
            require_once $controllerPath;
            if(!class_exists($controllerName)) {
                echo json_encode(["msg"=>"controller not found"]);
                die;
            }
            $method = "do" . $_SERVER ["REQUEST_METHOD"];
            if(!method_exists($controllerName,$method)) {
                echo json_encode(["msg"=>"method not allowed"]);
                die;                
            }
            $controller = new $controllerName($this->qh,$this->sm);
            $controller->doBefore();
            if(!$controller->checkAuth()) return;
            $controller->{$method}();
            if($controller->runAfterFilters()) $controller->doAfter();
        }
    }
