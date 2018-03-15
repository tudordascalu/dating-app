<?php  
    function adminGetUsers($ajUsers) {
        verifyAdmin($ajUsers);
        sendResponse(200, 'users', $ajUsers);
    }

    function adminSaveUsers($ajUsers) {
        verifyAdmin($ajUsers);
        $aNewUsers = $_POST['users_updated'];
        $ajUsers = $aNewUsers;
        saveToStorage($ajUsers, './storage/users.txt');
    }