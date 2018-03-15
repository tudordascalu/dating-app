<?php
    session_start();
// helpers
include './helpers/response.php';
include './helpers/authHelper.php';
include './helpers/storage.php';

// controllers
include './controllers/auth.php';
include './controllers/tinder.php';
include './controllers/location.php';
include './controllers/admin.php';

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
        saveNewLocation($ajUsers);
    break;

    case 'adminGetUsers': 
        adminGetUsers($ajUsers);
    break;

    case 'adminSaveUsers': 
        adminSaveUsers($ajUsers);
    break;
}