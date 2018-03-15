<?php   
    function sendResponse($iCode, $sMessage, $jData) {
        $sjData = json_encode($jData);
        echo '{"status":'.$iCode.', "message":'.$sMessage.', "data":'.$sjData.'}';
        exit;
    }