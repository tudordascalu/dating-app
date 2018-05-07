<?php 
    function checkSwapCount($sId, $ajUsers) { 
        foreach($ajUsers as $jU) {
            $dCurrentDate = date("Y/m/d");
            $sUserId = $jU->id;
            if($sUserId == $sId) {
                if($jU->role == 'vip') {
                    return;
                }
                if(empty($jU->swipe_date) || $jU->swipe_date != $dCurrentDate) {
                    $jU->swipe_date = $dCurrentDate;
                    $jU->swipe_count = 0;
                    saveToStorage($ajUsers, './storage/users.txt');
                }
                if($jU->swipe_count > 5) {
                    sendResponse(405, 'maximum number of users exceeded', null);   
                }
            }
        }

    }

    function dbCheckSwapCount($jU) {
        $dCurrentDate = date("Y/m/d");
        if($jU->role == 'vip') {
            return;
        }

        if(empty($jU->swipe_date) || $jU->swipe_date != $dCurrentDate) {
            $jU->swipe_date = $dCurrentDate;
            $jU->swipe_count = 0;
            saveToStorage($ajUsers, './storage/users.txt');
        }
        if($jU->swipe_count > 5) {
            sendResponse(405, 'maximum number of users exceeded', null);   
        }
    }

    function increaseSwapCount($sId, $ajUsers) {
        $dCurrentDate = date("Y/m/d");
        foreach($ajUsers as $jU) {
            $sUserId = $jU->id;
            if($sUserId == $sId) {
                $jU->swipe_count += 1;
                saveToStorage($ajUsers, './storage/users.txt');
            }
        }
    }

    function dbIncreaseSwipeCount($sId, $db) {
        $dCurrentDate = date("Y/m/d");
    }

    function dbGetNextUser($jMatrix, $db) {
        $sId = $_GET['id'];
        $iInterest = $_POST['interest'];
        try {
            // get all users excluded yourself
            $stmt = $db->prepare('SELECT * FROM users JOIN roles ON users.role_id = roles.id WHERE users.access_token != :id AND users.gender = :gender AND users.verified = 1 ');
            $stmt->bindValue(':id', $sId); // prevent sql injections
            $stmt->bindValue(':gender', $iInterest); 
            $stmt->execute();
            $ajUsers = $stmt->fetchAll();
            if($ajUsers == []) {
                // wrong credentials
                echo '{"status":"error","message":"there are no users who match your interest"}';
                exit;
            }
            
            foreach($ajUsers as $jUser) {
                if(!$jMatrix[$sId][$jUser->id]) {
                    // checkSwapCount($sId, $ajUsers);
                    $jData->id = $jUser['access_token'];
                    $jData->first_name = $jUser['first_name'];
                    $jData->last_name = $jUser['last_name'];
                    $jData->imageUrl = $jUser['profile_image'];
                    $jData->age = $jUser['age'];
                    $jData->gender = $jUser['gender'];
                    $jData->description = $jUser['motto'];
                    $jData->nextUserInterest = $iInterest;
                    $jData->role = $jUser['role'];
                    
                    $sjData = json_encode($jData);
                    echo '{"status":"success", "message":"this is the next user", "data":'.$sjData.'}';
                    exit;
                }
            }

            echo '{"status":"error","message":"no more users"}';
            exit;
            
        } catch (PDOException $ex){
            sendResponse(500, "server error", null);
        }

    }