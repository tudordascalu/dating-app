<?php
    session_start(); //CRUD FOR SESSIONS
    function verifyLogin() {
        $id = $_GET['id'];
        echo $_SESSION['id'];
        if($id === $_SESSION['id']) return true;
        
        echo '{"status":"error","message":"user is not logged in"}';
        exit;
    }
    
    function getUsers($sajUsers) {
       verifyLogin();
        
        echo '{"status":"success", "message":"user logged in", "data":'.$sajUsers.'}';
        exit;
    }

    function login($ajUsers) {
        $sEmail = $_POST['email'];
        $sPassword = $_POST['password'];
        foreach($ajUsers as $jUser) {
            if($jUser->email === $sEmail && $jUser->password === $sPassword) {
                $sjUser = json_encode($jUser);
                $_SESSION['id'] = $jUser->id;
                echo '{"status":"success", "message":"user logged in", "data":'.$sjUser.'}';
                exit;
            }
        }
        echo '{"status":"error","message":"username or password is incorrect"}';
        exit;
    }

    function signup($ajUsers) {
        $sFirstName = $_POST['firstName'];
        $sLastName = $_POST['lastName'];
        $iAge = $_POST['age'];
        $sEmail = $_POST['email'];
        $sPassword = $_POST['password'];
        $aImage = $_FILES['image'];

        if(!$sFirstName || !$sLastName || !$iAge || !$sEmail || !$sPassword || !$aImage['tmp_name']) {
            echo '{"status":"error","message":"make sure you fill up all the required fields"}';
            exit;
        }
  
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

        if(!isset($aImage)) {
            echo '{"status":"error","message":"please upload an image"}';
            exit;
        }

        // create new user
        $jUser->id = uniqid();
        $jUser->first_name = $sFirstName;
        $jUser->last_name = $sLastName;
        $jUser->email = $sEmail;
        $jUser->password = $sPassword;
        $jUser->age = $iAge;
        $jUser->imageUrl = saveImage($aImage);
        $sjUser = json_encode($jUser);
        // add user to array
        array_push($ajUsers, $jUser);
        
        // insert data into 'db'
        $sajUsers = json_encode($ajUsers);
        file_put_contents('./storage/users.txt', $sajUsers);

        // save image to file
        
        echo '{"status":"success", "message":"user signed up", "data":'.$sjUser.'}';
        exit;
    }


    function saveImage($aImage) {
        $sName = $aImage['name'];
        $sOldPath = $aImage['tmp_name'];

        $aName = explode('.', $sName);
        $sExt = $aName[count($aName)-1];
        $sId = uniqid();

        $sName = $sId.'.'.$sExt;
        move_uploaded_file($sOldPath, './assets/images/'.$sName);
        return '/assets/images/'.$sName;
    }