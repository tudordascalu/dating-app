<?php
    include('./controllers/database.php');
    $sKey = $_GET['key'];
    
    // verify user in db
    dbVerifyUser($sKey, $db);

    echo 'Invalid verification key!';
    exit;
    // methods
    function dbVerifyUser($sKey, $db) {
        try {
            $stmt = $db->prepare('UPDATE users SET verified = true, activation_key = NULL, access_token = :accessToken WHERE activation_key = :sKey');
            $stmt->bindValue(':sKey', $sKey);
            $stmt->bindValue(':accessToken', uniqid());
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                echo 'Your account is now verified!';
                exit;
            }
        } catch (PDOException $ex) {
            echo 'Server error';
        }
    }