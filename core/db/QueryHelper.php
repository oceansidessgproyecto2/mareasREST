<?php
    require dirname(__FILE__) . '/ConnectionManager.php';
    class QueryHelper Extends ConnectionManager{
        public function __construct() {
            try {
                $this->connect();
            } catch(\PDOException $e) {
                throw $e;
            }
        }
        public function query($str,$params,$all=false,$insert=false) {
            try {
                $stm = $this->con->prepare($str);
                $params_ready = [];
                foreach($params as $k=>$v)
                    $params_ready [":".$k] = $v;
                try {
                    $state = $stm->execute($params_ready);
                } catch (\PDOException $e) {
                    throw $e;
                }
                if($insert == true) return $state;
                if(!$all)
                    return $stm->fetch();
                else return $stm->fetchAll();
            } catch(\PDOException $e) {
                echo $e->getMessage();
                die;
            }
        }
        public function begintransaction() {
            $this->con->beginTransaction();
        }
        public function inTransaction() {
            return $this->con->inTransaction();
        }
        public function commitTransaction() {
            $this->con->commit();
        }
        public function rollback() {
            $this->con->rollback();
        }
        public function insert($str,$params) {
            return $this->query($str,$params,false,true);
        }
        public function update($str,$params) {
            return $this->query($str,$params,false,true);
        }
        public function delete($str,$params) {
            return $this->query($str,$params,false,true);
        }
        public function fetch($str,$params) {
            return $this->query($str,$params);
        }
        public function fetchAll($str,$params) {
            return $this->query($str,$params,true);
        }
    }
