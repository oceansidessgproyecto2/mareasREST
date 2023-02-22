<?php
    require_once dirname(__FILE__) . '/db/QueryHelper.php';
    require_once dirname(__FILE__) . '/session/SessionManager.php';
    require_once dirname(__FILE__) . '/net/RequestDispatcher.php';
    try {
        $qh = new QueryHelper;
        $sm = new SessionManager($qh);
        $rp = new RequestDispatcher($qh,$sm);
        
    } catch (\PDOException $e) {
        echo json_encode(["msg"=>$e->getMessage()]);
    }
