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
            $stmt->bindValue(':activation_key', uniqid());
            $stmt->execute();

        }catch (PDOException $ex){
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
                $jData['id'] = $jData['access_token'];
                $_SESSION[$jData['access_token']] = "logged in";
                sendResponse(200, 'user logged in', $jData);
            } 
            
            echo '{"status":"error","code":"403", "message":"please verify your account"}';
            exit;

        }catch (PDOException $ex){
            sendResponse(500, "server error", null);
        }
    }