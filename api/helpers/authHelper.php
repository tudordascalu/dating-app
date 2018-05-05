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
                VALUES (null, :firstName, :lastName, :email, :pass, :gender, :age, :motto, :interest, :profile_image, 1)');
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
                VALUES (:id, 0, :access_key, NULL)');
            $stmt->bindValue(':id', $db->lastInsertId()); // prevent sql injections
            $stmt->bindValue(':access_key', $sAccessKey); // prevent sql injections
            $stmt->execute();
        }catch (PDOException $ex){
            echo 'exception';
        }
    }
    
    function dbLoginUser($sEmail, $sPassword, $db) {
        try{
            $stmt = $db->prepare('SELECT id FROM users WHERE email = :sEmail AND pass = :sPassword');
            $stmt->bindValue(':sEmail', $sEmail); // prevent sql injections
            $stmt->bindValue(':sPassword', $sPassword); // prevent sql injections
            $stmt->execute();
            $jData = $stmt->fetch();
            if($jData == false) {
                // wrong credentials
                echo '{"status":"error","message":"username or password is incorrect"}';
                exit;
            }
            dbCheckIfVerified($jData['id'], $db);
        }catch (PDOException $ex){
            echo 'exception';
        }
    }

    function dbCheckIfVerified($userId, $db) {
        try{
            $stmt = $db->prepare('SELECT verified, access_token FROM account_verification WHERE user_id = :id');
            $stmt->bindValue(':id', $userId); // prevent sql injections
            $stmt->execute();
            $jData = $stmt->fetch();
            if($jData['verified'] == 1) {
                // user is verified
                dbSendLoginSuccessResponse($userId, $jData['access_token'], $db);
            } 

            // user is not verified
            echo '{"status":"error","code":"403", "message":"please verify your account"}';
            exit;
        }catch (PDOException $ex){
            echo '{"status":"error","message":"server error"}';
        }
    }

    function dbSendLoginSuccessResponse($userId, $accessToken, $db) {
        try{
            $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->bindValue(':id', $userId); // prevent sql injections
            $stmt->execute();
            $jUser = $stmt->fetch();
            $jUser['id'] = $accessToken;
            $_SESSION[$jUser['id']] = "logged in";
            sendResponse(200, 'user logged in', $jUser);
        }catch (PDOException $ex) {
            echo '{"status":"error","message":"server error"}';
        }
    }
