<?php
    $sKey = $_GET['key'];

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