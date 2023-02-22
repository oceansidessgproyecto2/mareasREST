<?php
    require_once dirname(__FILE__) . "/../cronCore/CronJob.php";
    class Jsonizer Extends cronjob {
        private $args;
        private $json;
        public function __construct($args) {
            $this->args = $args;
            parent::__construct();
            $this->run();
        }
        private function run() {
            $this->loadTool("DBMap");
            $this->loadTool("MapJsonizer");
            $dbmap = new DBMapTool($this->qh);
            $jsonizer = new MapJsonizerTool($this->qh,$dbmap->map());
            $this->json = $jsonizer->jsonize();
            if(!$this->json){
                self:vm_die("could not jsonize");
            } else $this->saveFile();
            
        }
        private function saveFile() {
            self::vm_log("saving file");
            $outputfile = dirname(dirname(__FILE__)) . '/jsondumps/dbdump_' . date('m-d-Yh:i:sa', time()) . ".json";
            file_put_contents($outputfile,$this->json);
            self::vm_log("file saved");
        }
    }