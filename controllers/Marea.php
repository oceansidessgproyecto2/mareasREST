<?php
    require dirname(dirname(__FILE__)) . '/controllersCore/Controller.php';
    class Marea Extends Controller {
        public function __construct($qh,$sm) {
            $this->qh = $qh;
            $this->sm = $sm;
        }
        public function doGet() {
            if(!$this->paramExists("dinnie") || !$this->paramExists("finnie")) {
                $this->response = ["error"=>"required fields not sent"];
                return $this->returnResponse();
            }
            $dinnie = DateTime::createFromFormat("Y-m-d",$this->getParam("dinnie"));
            $finnie = DateTime::createFromFormat("Y-m-d",$this->getParam("finnie"));
            $date_errors = [];
            if(!$dinnie)
                $date_errors [] = "fecha de comienzo invalida";
            if(!$finnie)
                $date_errors [] = "fecha de fin invalida";
            if(count($date_errors) != 0) {
                $this->response = ["errors"=>$date_errors];
                return $this->returnResponse();
            }
            $days = $dinnie->diff($finnie)->format("%r%a");
            if(strpos($days,"-") !== false) {
                $this->response = ["error"=>"la fecha de inicio debe de ser anterior a la del final"];
                return $this->returnResponse();
            }
            $days = (int) $days;
            $data = [];
            $WorkingDate = clone $dinnie;
            for($i=0;$i <= $days;$i++) {
                $data [$WorkingDate->format('d/m/Y')] = ["sin datos"];
                $date_data = $this->qh->fetch("SELECT * FROM predictions WHERE thedate=:date",["date"=>$WorkingDate->format("Y-m-d")]);
                if($date_data) {
                    $data [$WorkingDate->format('d/m/Y')] = [];
                    $data [$WorkingDate->format('d/m/Y')] = json_decode($date_data ["response"],false);
                }
                $WorkingDate->modify('+1 day');
            }
            $this->response = ["msg"=>$data];
            return $this->returnResponse();
        }
    }