<?php

require_once 'controllers/database.php';

try {
    $db->beginTransaction();
    $jUser = json_decode('{}');
    $jUser->name = 'Tudor';
    $jUser->email = 'tudorlaurentiu1@yahoo.com';
    $jUser->password = '123456';
    $jUser->phone_number = '52525252';

    $stmt = $db->prepare('INSERT INTO users VALUES(null, :sName, :sPass, :sEmail)');
    $stmt->bindValue(':sName', $jUser->name);
    $stmt->bindValue(':sEmail', $jUser->email);
    $stmt->bindValue(':sPass', $jUser->password);

    if($stmt->execute()){ // insert user into 'users' DB
        $iUserId = $db->lastInsertId(); // get user id

        // insert a phone number associated with the user id
        $stmt = $db->prepare('INSERT INTO phones VALUES(null, :iUserId, :sPhone )');
        $stmt->bindValue(':iUserId', $iUserId);
        $stmt->bindValue(':sPhone', $jUser->phone_number);
        if($stmt->execute()){
            $db->commit(); // if user + phone number was inserted we commit / save the data in the db
        } else{
            $db->rollBack();
        }
    } else {
        $db->rollBack(); // if user cannot be inserted rollback
    }
}catch(PDOException $ex){
    echo 'Server error!';
    $db->rollBack(); // if there is an error while inserting user / phone number rollback
}

function dbGetAllUsers() {
    try {
        $stmt = $db->prepare('CALL getAllUsers()');
        $stmt->execute();
        $aUsers = $stmt->fetchAll();
        
        return $aUsers;
    }   catch(PDOException $ex){
        echo 'Server error!';
        exit;
    }
}    
