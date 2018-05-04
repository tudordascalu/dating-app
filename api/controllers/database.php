<?php

try{
    $sUserName = 'root';
    $sPassword = 'root';
    $sConnection = "mysql:host=127.0.0.1; dbname=tinder; charset=utf8";
    $aOptions = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

$db = new PDO( $sConnection, $sUserName, $sPassword, $aOptions );
// echo 'db connection added';
}catch( PDOException $e){

echo 'error';
exit();

}