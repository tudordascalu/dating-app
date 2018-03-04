<?php
include './controllers/users.php';
include './controllers/tinder.php';

$sRequestType = $_GET['reqType'];

// echo $sRequestType;
$sajUsers = file_get_contents('./storage/users.txt');
$ajUsers = json_decode($sajUsers);

$sjMatrix = file_get_contents('./storage/matches.txt');
$jMatrix = json_decode($sjMatrix, '{}');

switch($sRequestType) {
    case 'getUsers': 
        getUsers($sajUsers);
    break;

    case 'login': 
        login($ajUsers);
    break;

    case 'logout':
        session_destroy();
    break;

    case 'signup': 
        signup($ajUsers);
    break;
    
    case 'like': 
        verifyLogin();
        onLike($ajUsers, $jMatrix);
    break;

    case 'getUser':
        verifyLogin();
        getNextUser($ajUsers, $jMatrix);
    break;
}