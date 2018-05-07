<?php
    function dbSaveLocation($sId, $sLat, $sLong, $db) {
        try {
            $stmt = $db->prepare('UPDATE users SET latitude = :latitude, longitude = :longitude WHERE access_token = :accessKey');
            $stmt->bindValue(':latitude', $sLat);
            $stmt->bindValue(':longitude', $sLong);
            $stmt->bindValue(':accessKey', $sId);
            $stmt->execute();
            sendResponse(200, 'saved new location', null);
        } catch (PDOException $ex) {
            echo $ex;
        }
    }