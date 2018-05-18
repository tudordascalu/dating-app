<?php
    function dbSaveLocation($accessToken, $sLat, $sLong, $db) {
        try {
            $stmt = $db->prepare('SELECT user_id FROM account_verification WHERE access_key = :accessKey');
            $stmt->bindValue(':accessKey', $accessToken);
            if($stmt->execute()) {
                $row = $stmt->fetchObject();
                $user_id = $row->user_id;
                $stmt = $db->prepare('UPDATE users SET latitude = :latitude, longitude = :longitude WHERE id = :id');
                $stmt->bindValue(':latitude', $sLat);
                $stmt->bindValue(':longitude', $sLong);
                $stmt->bindValue(':id', $user_id);
                $stmt->execute();
                sendResponse(200, 'saved new location', null);
            }
        } catch (PDOException $ex) {
            echo $ex;
        }
    }