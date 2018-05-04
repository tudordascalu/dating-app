<?php
    function verifyLogin() {
        $id = $_GET['id'];
        if($_SESSION[$id]) return $id;
        
        echo '{"status":"forbidden","message":"user is not logged in"}';
        exit;
    }

    function verifyAdmin($ajUsers) {
        $sId = $_POST['id'];
        if(!$_SESSION[$sId]) {
            sendResponse(403, "user is not logged in", null);
        }
        // check admin credentials
        return;
        // sendResponse(403, "user is not logged in", null);
    }

    function dbSaveUser($jUser,$db) {
        try{
            $stmt = $db->prepare('INSERT INTO users 
                VALUES (null, :firstName, :lastName, :pass, :email, :gender, :age, :motto, :interest, :profile_image, 1)');
            $stmt->bindValue(':firstName', $jUser->first_name); // prevent sql injections
            $stmt->bindValue(':lastName', $jUser->last_name);
            $stmt->bindValue(':email', $jUser->email);
            $stmt->bindValue(':pass', $jUser->password);
            $stmt->bindValue(':gender', $jUser->gender);
            $stmt->bindValue(':age', $jUser->age);
            $stmt->bindValue(':motto', $jUser->description);
            $stmt->bindValue(':interest', $jUser->interest);
            $stmt->bindValue(':profile_image', $jUser->imageUrl); // prevent sql injections
            $stmt->execute();
            // add verification key to table
            dbAddVerificationKey($jUser->activation_key, $db);
        }catch (PDOException $ex){
            echo 'exception';
        }
    }

    function dbAddVerificationKey($sAccessKey, $db) {
        try{
            $stmt = $db->prepare('INSERT INTO account_verification 
                VALUES (:id, 0, :access_key)');
            $stmt->bindValue(':id', $db->lastInsertId()); // prevent sql injections
            $stmt->bindValue(':access_key', $sAccessKey); // prevent sql injections
            $stmt->execute();
        }catch (PDOException $ex){
            echo 'exception';
        }
    }
