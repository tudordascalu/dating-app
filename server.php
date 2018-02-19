<?php

$sRequestType = $_GET['reqType'];

// echo $sRequestType;
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
                $sjUser = json_encode($jUser);
                echo '{"status":"success", "message":"user logged in", "data":'.$sjUser.'}';
                exit;
            }
        }

        echo '{"status":"error","message":"username or password is incorrect"}';
        exit;
    }
    break;

    case 'signup': {
        $sFirstName = $_POST['firstName'];
        $sLastName = $_POST['lastName'];
        $iAge = $_POST['age'];
        $sEmail = $_POST['email'];
        $sPassword = $_POST['password'];
        // $sImageUrl = $_POST['imgUrl'];
/*
        if(!$sFirstName || !$sLastName || !$iAge || !$sEmail || !$sPassword || !$sImageUrl) {
            echo '{"status":"error","message":"make sure you fill up all the required fields"}';
            exit;
        }
*/  
        if(strlen($sFirstName) > 25 || strlen($sFirstName) < 2) {
            echo '{"status":"error","message":"first name length is not correct"}';
            exit;
        }

        if(strlen($sLastName) > 25 || strlen($sLastName) < 2) {
            echo '{"status":"error","message":"last name length is not correct"}';
            exit;
        }

        if(!filter_var($iAge, FILTER_VALIDATE_INT) || $iAge < 18 || $iAge > 100) {
            echo '{"status":"error","message":"age is not appropriate"}';
            exit;
        }
       
        if(strlen($sEmail) > 30 || strlen($sEmail) < 2) {
            echo '{"status":"error","message":"email length is not correct"}';
            exit;
        }

        if(strlen($sPassword) > 40 || strlen($sPassword) < 2) {
            echo '{"status":"error","message":"password too short"}';
            exit;
        }

        // create new user
        $jUser->id = uniqid();
        $jUser->first_name = $sFirstName;
        $jUser->last_name = $sLastName;
        $jUser->email = $sEmail;
        $jUser->password = $sPassword;
        $jUser->age = $iAge;
        
        // add user to array
        array_push($ajUsers, $jUser);
        
        // insert data into 'db'
        $sajUsers = json_encode($ajUsers);
        file_put_contents('./storage/users.txt', $sajUsers);

        echo "{'status':'success', 'message':'user logged in', 'data':$jUser}";
        exit;
    }
}