<?php
class DBMapTool {
    private $qh;
    private $map = [];
    private $ignore = [];
    public function __construct($qh) {
        require_once dirname(dirname(__FILE__)) . "/config/dbmap.ignore.php";
        if(is_array($ignore)) $this->ignore = $ignore;
        $this->qh = $qh;
    }
    
    private function getTablesNames() {
        $tables = $this->qh->fetchAll("SHOW TABLES",[]);
        return $tables;
    }
    public function map() {
        $tables = $this->getTablesNames();
        if(!is_array($tables)) return null;
        foreach($tables as $table) {
            if(isset($this->ignore [reset($table)]))
                if($this->ignore [reset($table)] == "*") continue;
            $fields_raw = $this->qh->fetchAll("DESC " . reset($table),[]);
            if(!isset($this->map [reset($table)])) $map [reset($table)] = [];
            foreach($fields_raw as $field) {
                if(isset($this->ignore [reset($table)]))
                    if(!in_array($field ["Field"],$this->ignore [reset($table)])) $this->map [reset($table)] [] = $field ["Field"];
                else $this->map [reset($table)] [] = $field ["Field"];
            }
        }
        return $this->map;
    }
}