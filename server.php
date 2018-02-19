<?php

$sRequestType = $_GET['reqType'];

echo $sRequestType;
$sajUsers = file_get_contents('./storage/users.txt');
$ajUsers = json_decode($sajUsers);

switch($sRequestType) {
    case 'getUsers': {
        echo $sajUsers;
    }
    break;

    case 'login': {
        $sEmail = $_POST['email'];
        $sPassword = $_POST['password'];
        foreach($ajUsers as $jUser) {
            if($jUser->email === $sEmail && $jUser->password === $sPassword) {
                echo "{'status':'success', 'message':'user logged in', 'data':$jUser}";
            }
        }
    }
    break;

    case 'signup': {
        $sEmail = $_POST['email'];
        $sPassword = $_POST['password'];
        $sFirstName = $_POST['firstName'];
        $sLastName = $_POST['lastName'];
        $iAge = $_POST['age'];
        $sImageUrl = $_POST['imgUrl'];

        
    }
}