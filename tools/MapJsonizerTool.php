<?php
    require_once dirname(dirname(__FILE__)) . "/core/BaseUtils.php";
    class MapJsonizerTool  Extends BaseUtils {
        private $qh;
        private $map;
        private $jsonStruct = [];
        
        public function __construct($qh,$map) {
            $this->qh = $qh;
            $this->map = $map;
        }
        public function jsonize() {
            foreach($this->map as $k=>$v) {
                $this->jsonStruct [$k] = [];
                $this->jsonStruct [$k] = $this->qh->fetchAll($this->buildQuery($k,$v),[]);
            }
            return json_encode($this->jsonStruct,true);
        }
        
        private function buildQuery($name,$fields) {
            $fields_processed = self::arrayToStringList($fields);
            return " SELECT " . $fields_processed . " FROM " . $name;
        }
    }