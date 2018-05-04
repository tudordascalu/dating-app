<?php
    include('./controllers/database.php');
    $sKey = $_GET['key'];
    // verify user in db
    dbVerifyUser($db, $sKey);

    $saUsers = file_get_contents('./storage/users.txt');
    $aUsers = json_decode($saUsers);
    foreach($aUsers as $jUser) {
        if($jUser->activation_key == $sKey) {
            $jUser->verified = 1;
            $jUser->activation_key = "";
            $saUsers = json_encode($aUsers);
            file_put_contents('./storage/users.txt', $saUsers);
            echo "{'status':'success', 'message':'you are verified'}";
            exit;
        }
    }

    echo "{'status':'error', 'message':'invalid activation token'}";

    function dbUpdateAccountVerification($id, $db) {
        try {
            $stmt = $db->prepare('UPDATE account_verification SET verified = true, activation_key = NULL WHERE user_id = :id');
            $stmt->bindValue(':id', $id);
            $stmt->execute();
        } catch (PDOException $ex) {
            echo 'exception';
        }
    }

    function dbVerifyUser($db, $sKey) {
        try {
            $stmt = $db->prepare('SELECT user_id FROM account_verification WHERE activation_key = :sKey');
            $stmt->bindValue(':sKey', $sKey);
            $stmt->execute();
            $jData = $stmt->fetch();
            dbUpdateAccountVerification($jData['user_id'], $db);
        } catch (PDOException $ex) {
            echo 'exception';
        }
    }