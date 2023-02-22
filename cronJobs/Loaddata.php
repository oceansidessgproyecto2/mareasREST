<?php
    require_once dirname(__FILE__) . "/../cronCore/CronJob.php";
    class Loaddata Extends cronjob {
        private $args;
        public function __construct($args) {
            $this->args = $args;
            parent::__construct();
            $this->run();
        }
        private function run() {
            if(sizeof($this->args) == 1)
                self::vm_die("this cronjob needs arguments, usage: loaddata.php years <yearList> or loaddata.php nearmonths");
            if(sizeof($this->args) > 3)
                self::vm_die("this cronjob doesn't support more than 2 arguments");
            $valid = false;
            $options = [];
            $mode = "years";
            self::vm_log("validating and setting up mode");
            switch($this->args[1]) {
                case "years":
                    if(sizeof($this->args) != 3)
                        self::vm_log("years option must have one and only argument");
                    else {
                        $option_n = 0;
                        $options = explode(",",$this->args [2]);
                        $elpringuemp4 = false;
                        foreach($options as $option) {
                            if(!is_numeric($option)) {
                                $elpringuemp4 = true;
                                self::vm_log("year with ordinal " . ($option_n + 1) . " must be a numeric value");
                            }
                            $option_n++;
                        }
                        $valid = !$elpringuemp4;
                    }
                break;
                case "nearmonths":
                    $mode = "months";
                    $curDate = new DateTime('now');
                    for($i=0;$i<5;$i++) $options [$i] = clone $curDate;
                    for($i=1;$i>=0;$i--) {
                        $options [$i]->modify('first day of previous month');
                        if($i != 0) $options [$i-1] = clone $options [$i];
                    }
                    for($i=3;$i<=4;$i++) {
                        $options [$i]->modify('first day of next month');
                        if($i != 4) $options [$i+1] = clone $options [$i];
                    }
                    $valid = true;
                break;
                default:
                    self::vm_log("cronjob option not found");
                break;
            }
            if(!$valid) self::CurrentData("cronjob cancelled due to errors");
            if($mode == "years")
                for($i=0;$i<sizeof($options);$i++) $options [$i] = DateTime::createFromFormat("Y-m-d", $options [$i] . "-1-1");
            $dates = [];
            self::vm_log("setting up dates list");
            switch($mode) {
                case "years":
                    foreach($options as $option) {
                        $end = clone $option;
                        $end->modify('last day of December this year');
                        $days = $end->diff($option)->format("%a") + 1;
                        $ptr = clone $option;
                        for($i=0;$i<$days*2;$i++) {
                            $dates [] = clone $ptr;
                            if($ptr->format("Y") != $option->format("Y")) {
                                $tokens = explode("-",$option->format("Y-m"));
                                $day = $ptr->format("d");
                                $day++;
                                $ptr = DateTime::createFromFormat("Y-m-d", $day . "-" . $tokens [1] . "-" . $tokens [2]);
                            }
                            $ptr->modify("+1 day");
                            $i++;
                        }
                    }
                break;
                case "months":
                    foreach($options as $option) {
                        $end = clone $option;
                        $end->modify('last day of this month');
                        $days = $end->diff($option)->format("%a") + 1;
                        $ptr = clone $option;
                        for($i=0;$i<$days;$i++) {
                            $dates [] = clone $ptr;
                            $ptr->modify("+1 day");
                        }
                    }
                break;
            }
            self::vm_log("checking possible already created data");
            $skips_raw = $this->qh->fetchAll("SELECT * FROM predictions WHERE thedate >=:dinnie AND thedate <=:finnie",["dinnie"=>$dates[0]->format("Y-m-d"),"finnie"=>$dates[sizeof($dates)-1]->format("Y-m-d")]);
            $skips = [];
            foreach($skips_raw as $skipper)
                $skips [] = DateTime::createFromFormat("Y-m-d", $skipper ["thedate"]);
            self::vm_log("starting import process");
            foreach($dates as $date) {
                self::vm_log("starting import from date " . $date->format("d/m/Y"));
                self::vm_log("fetching from API");
                $response = file_get_contents("https://ideihm.covam.es/api-ihm/getmarea?request=gettide&id=57&format=json&date=".$date->format("Ymd"));
                $json_response = @json_encode(json_decode($response,true)["mareas"] ["datos"] ["marea"]);
                if(!$json_response) $json_response = "[\"sin datos\"]";
                self::vm_log("inserting database entry");
                $this->qh->insert("INSERT INTO predictions VALUES(null,:date,:json)",["date"=>$date->format("Y-m-d"),"json"=>$json_response]);
                self::vm_log("database entry inserted");
            }
        }
    }
