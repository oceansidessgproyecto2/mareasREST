<?php
    require_once dirname(dirname(__FILE__)) . "/email/classes/Email.php";
    class EmailSender {
        private $bag;
        
        public function __construct($bag) {
            $this->bag = $bag;
        }
        
        public function run() {
            $em = new Email($this->bag);
        }
    }