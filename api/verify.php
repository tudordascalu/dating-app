<?php
    include('./controllers/database.php');
    $sKey = $_GET['key'];
    
    // verify user in db
    dbVerifyUser($sKey, $db);

    echo 'Invalid verification key!';
    exit;
    // methods
    function dbVerifyUser($sKey, $db) {
        $access_token = uniqid();
        try {
            $stmt = $db->prepare('SELECT user_id FROM account_activation WHERE activation_key = :activationKey');
            $stmt->bindValue(':activationKey', $sKey);
            if($stmt->execute()) {
                $row = $stmt->fetchObject();
                $user_id = $row->user_id;
                $stmt = $db->prepare('UPDATE users SET verified = true WHERE id = :id');
                $stmt->bindValue(':id', $user_id);
                $stmt->execute();
                if($stmt->rowCount() > 0) {
                    $stmt = $db->prepare('INSERT INTO account_verification VALUES (:id, :verificationKey)');
                    $stmt->bindValue(':id', $user_id);
                    $stmt->bindValue(':verificationKey', $access_token);
                    $stmt->execute();

                    echo 'Your account is now verified!';
                    exit;
                }
            }
        } catch (PDOException $ex) {
            echo $ex;
        }
    }