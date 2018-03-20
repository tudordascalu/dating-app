<?php
    session_start();
// helpers
include_once './helpers/response.php';
include_once './helpers/authHelper.php';
include_once './helpers/storage.php';
include_once './helpers/tinderHelper.php';

// controllers
include_once './controllers/auth.php';
include_once './controllers/tinder.php';
include_once './controllers/location.php';
include_once './controllers/admin.php';

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

    case 'saveLocation':
        verifyLogin();
        saveNewLocation($ajUsers);
    break;

    case 'adminGetUsers': 
        adminGetUsers($ajUsers);
    break;

    case 'adminSaveUsers': 
        adminSaveUsers($ajUsers);
    break;
}