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
         verifyLogin();  
        getUsers($sajUsers);
    break;

    case 'login': 
        login($ajUsers);
    break;

    case 'logout':
        logout();
    break;

    case 'signup': 
        signup($ajUsers);
    break;
    
    case 'like': 
        // verifyLogin();
        onLike($ajUsers, $jMatrix);
    break;

    case 'getUser':
        verifyLogin();
        getNextUser($ajUsers, $jMatrix);
    break;

    case 'getMatches':
        getMatches($ajUsers, $jMatrix);
    break;
    
    case 'newMatch':
        checkNewMatch($jMatrix);
    break;
}