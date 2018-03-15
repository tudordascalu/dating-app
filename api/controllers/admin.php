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