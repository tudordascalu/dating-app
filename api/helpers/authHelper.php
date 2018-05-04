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
        echo json_encode($jUser);
        try{
            echo 'am ajuns aici';
            $stmt = $db->prepare('INSERT INTO users 
            VALUES (null, :firstName, :lastName, :pass, :email, :gender, :age, :motto, :interest, :profile_image, 1)');
            $stmt->bindValue(':firstName', $jUser->first_name); // prevent sql injections
            $stmt->bindValue(':lastName', $jUser->last_name);
            $stmt->bindValue(':email', $jUser->password);
            $stmt->bindValue(':pass', $jUser->last_name);
            $stmt->bindValue(':gender', $jUser->gender);
            $stmt->bindValue(':age', $jUser->age);
            $stmt->bindValue(':motto', $jUser->description);
            $stmt->bindValue(':interest', $jUser->interest);
            $stmt->bindValue(':profile_image', $jUser->imageUrl); // prevent sql injections
            $stmt->execute();
            echo 'am ajuns si aici cu bine';
        }catch (PDOException $ex){
            echo 'exception';
        }
    }
/*
    function dbAddRole() {
        echo json_encode();
        try {
            $stmt = $db->prepare('INSERT INTO roles VALUES(null, :sName)');
            $stmt->bindValue(':sName', 1);
            $stmt->execute();
            echo 'da';
        }catch( PDOException $ex){
            echo "EXCEPTION";
        }
    }
*/