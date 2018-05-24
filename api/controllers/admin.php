<?php  
    function adminGetUsers($ajUsers) {
        verifyAdmin($ajUsers);
        sendResponse(200, 'users', $ajUsers);
    }

    function adminSaveUsers($ajUsers) {
        verifyAdmin($ajUsers);
        $saNewUsers = $_POST['users'];
        echo json_encode($aNewUsers);
        $ajUsers = json_decode($saNewUsers);
        saveToStorage($ajUsers, './storage/users.txt');
    }

    function dbAdminGetUsers($db) {
        try{
            $stmt->$db->prepare('SELECT access_token AS id, first_name, last_name, email FROM users WHERE access_token != "null"');
            $aUsers = $stmt->fetchAll();
            sendResponse(200, 'users', $aUsers);
        }catch(PDOException $ex) {
            echo $ex;
        }
    }