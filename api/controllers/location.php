<?php
    function saveNewLocation($ajUsers, $db) {
        $sId = $_GET['id'];
        $sLat = $_POST['latitude'];
        $sLong = $_POST['longitude'];
        if(!$sLat || !$sLong) {
            sendResponse(400, 'missing arguments', null);
        }
        dbSaveLocation($sId, $sLat, $sLong, $db);
        foreach($ajUsers as $jUser) {
            if($jUser->id == $sId) {
                $jUser->latitude = $sLat;
                $jUser->longitude = $sLong;
                saveToStorage($ajUsers, './storage/users.txt');
                sendResponse(200, 'saved new location', $jUser);
            }
        }
        
        sendResponse(400, 'saved new location', null);
    }