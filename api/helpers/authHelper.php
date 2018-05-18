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
        $db->beginTransaction();
        try{
            $stmt = $db->prepare('INSERT INTO users 
                VALUES (NULL, :firstName, :lastName, :email, :pass, :gender, :age, :motto, :interest, :profile_image, 1, NULL, NULL, NULL, :activation_key, false, NULL, NULL)');
            $stmt->bindValue(':firstName', $jUser->first_name); // prevent sql injections
            $stmt->bindValue(':lastName', $jUser->last_name);
            $stmt->bindValue(':email', $jUser->email);
            $stmt->bindValue(':pass', $jUser->password);
            $stmt->bindValue(':gender', $jUser->gender);
            $stmt->bindValue(':age', $jUser->age);
            $stmt->bindValue(':motto', $jUser->description);
            $stmt->bindValue(':interest', $jUser->interest);
            $stmt->bindValue(':profile_image', $jUser->imageUrl); 
            $stmt->bindValue(':activation_key', $jUser->activation_key);
            if($stmt->execute()) {
                $user_id = $db->lastInsertId();
                $stmt = $db->prepare('INSERT INTO account_activation VALUES (:userId, :activationKey)');
                $stmt->bindValue(':userId', $user_id);
                $stmt->bindValue(':activationKey',  $jUser->activation_key);
                if($stmt->execute()) {
                    // success
                    $db->commit();
                } else {
                    $db->rollback();
                    sendResponse(400, "Could not create activation key", null);
                }
            } else {
                $db->rollback();
                sendResponse(401, "This email is already being used", null);
            }

        }catch (PDOException $ex){
            $db->rollback();
            sendResponse(401, "This email is already being used", null);
        }
    }
    
    function dbLoginUser($sEmail, $sPassword, $db) {
        try{
            $stmt = $db->prepare('SELECT * FROM users WHERE email = :sEmail AND pass = :sPassword');
            $stmt->bindValue(':sEmail', $sEmail); // prevent sql injections
            $stmt->bindValue(':sPassword', $sPassword); // prevent sql injections
            $stmt->execute();
            $jData = $stmt->fetch();
            
            if($jData == false) {
                // wrong credentials
                echo '{"status":"error","message":"username or password is incorrect"}';
                exit;
            }
            
            if($jData['verified'] == true) {
                // user is verified
                $jData['access_token'] = uniqid();
                $_SESSION[$jData['access_token']] = "logged in";
                $stmt = $db->prepare('INSERT INTO account_verification VALUES(:id, :accessToken)');
                $stmt->bindValue(':accessToken', $jData['access_token']);
                $stmt->bindValue(':id', $jData['id']);
                if($stmt->execute()) {
                    sendResponse(200, 'user logged in', $jData);
                } else {
                    sendResponse(406, 'could not save access token', NULL);
                }
            } 
            
            echo '{"status":"error","code":"403", "message":"please verify your account"}';
            exit;

        } catch (PDOException $ex){
            sendResponse(500, "server error", null);
        }
    }