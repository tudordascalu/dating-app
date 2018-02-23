<?php
include './api/users.php';
$sRequestType = $_GET['reqType'];

// echo $sRequestType;
$sajUsers = file_get_contents('./storage/users.txt');
$ajUsers = json_decode($sajUsers);

switch($sRequestType) {
    case 'getUsers': 
        echo $sajUsers;
    break;

    case 'login': 
        login($ajUsers);
    break;

    case 'signup': 
        signup($ajUsers);
    break;
}