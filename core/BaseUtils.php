<?php
    class BaseUtils {
        public static function vm_memory_usage()
        {
            $size = memory_get_usage(true);
            $unit = array('B','KB','MB','GB','TB','PB');
            return @round($size/pow(1024,($i=floor(log($size,1024)))),2).''.$unit[$i];
        }

        public static function vm_log($msg) {
            if (substr($msg, -1, 1) !== "\n") {
                $msg = $msg . PHP_EOL;
            }

            echo sprintf("[%s]-[%s] %s", date('Y-m-d H:i:s:n'), self::vm_memory_usage(), $msg);
        }

        public static function vm_die($msg = null) {
            self::vm_log($msg);
            die();
        }
        
        public static function arrayToStringList($arr) {
            if(!is_array($arr)) return false;
            $output_str = '';
            foreach($arr as $el)
                $output_str .= sprintf("%s,",$el);
            if(is_string($output_str))
                if(strlen($output_str) > 0) $output_str = substr($output_str,0,-1);
            return $output_str;
        }
    }