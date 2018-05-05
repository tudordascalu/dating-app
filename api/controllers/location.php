<?php
    function saveNewLocation($ajUsers, $db) {
        $sId = $_GET['id'];
        $sLat = $_POST['latitude'];
        $sLong = $_POST['longitude'];
        if(!$sLat || !$sLong) {
            sendResponse(400, 'missing arguments', null);
        }
        dbSaveLocation($sId, $sLat, $sLong, $db);
        sendResponse(400, 'saved new location', null);
    }