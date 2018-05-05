<?php
    function dbSaveLocation($sId, $sLat, $sLong, $db) {
        try {
            $stmt = $db->prepare('UPDATE users SET latitude = :latitude, longitude = :longitude WHERE id = (SELECT user_id FROM account_verification WHERE access_token = :accessKey)');
            $stmt->bindValue(':latitude', $sLat);
            $stmt->bindValue(':longitude', $sLong);
            $stmt->bindValue(':accessKey', $sId);
            $stmt->execute();
            sendResponse(200, 'saved new location', null);
        } catch (PDOException $ex) {
            echo $ex;
        }
    }