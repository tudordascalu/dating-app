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

    function dbCheckSwapCount($sId, $db) {
        try {
            // get all users excluded yourself
            $stmt = $db->prepare('SELECT users.id, swipe_count, swipe_date, roles.role FROM users JOIN roles ON users.role_id = roles.id WHERE users.id = :id');
            $stmt->bindValue(':id', $sId); // prevent sql injections
            $stmt->execute();
            $jU = $stmt->fetch();
            
            if($jU['role'] == 'vip') {
                return;
            }

            $dCurrentDate = date("Y-m-d");
            if(empty($jU['swipe_date']) || $jU['swipe_date'] != $dCurrentDate) {
                $jU['swipe_date'] = $dCurrentDate;
                $jU['swipe_count'] = 1;
                dbUpdateSwipeDate($jU, $db);
                return;
            }
    
            if($jU['swipe_count'] > 5) {
                sendResponse(405, 'maximum number of users exceeded', null);   
            }

        } catch (PDOException $ex){
            echo $ex;
        }
    }

    function dbUpdateSwipeDate($jU, $db) {
        try {
            // get all users excluded yourself
            $stmt = $db->prepare('UPDATE users SET swipe_count = :swapCount, swipe_date = :swapDate WHERE id = :id');
            $stmt->bindValue(':swapDate', $jU['swipe_date']); // prevent sql injections
            $stmt->bindValue(':swapCount', $jU['swipe_count']);
            $stmt->bindValue(':id', $jU['id']);
            $stmt->execute();
        } catch (PDOException $ex){
            sendResponse(500, "server error", null);
        }
    }

    function dbFetchUsers($id, $db) {
        try {
            // get all users excluded yourself
            $stmt = $db->prepare('SELECT * FROM users WHERE id != :id');
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $ex){
            sendResponse(500, "server error", null);
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

    function dbIncreaseSwipeCount($id, $db) {
        try{
            $stmt = $db->prepare('UPDATE users SET swipe_count = swipe_count + 1 WHERE id = :id');
            $stmt->bindValue(':id', $id); // prevent sql injections
            $stmt->execute();
            
            // sendResponse(400, 'user id is not valid', NULL);
        }catch (PDOException $ex){
            sendResponse(500, "server error", null);
        }
    }

    function dbGetNextUser($jMatrix, $db) {
        $accessToken = $_GET['id'];
        $iInterest = $_POST['interest'];
        $sId = dbGetUserId($accessToken, $db);
        dbCheckSwapCount($sId, $db);
        try {
            // get all users excluded yourself
            $stmt = $db->prepare('SELECT * FROM users WHERE id != :id AND users.gender = :gender AND users.verified = 1 ');
            $stmt->bindValue(':id', $sId); // prevent sql injections
            $stmt->bindValue(':gender', $iInterest); 
            $stmt->execute();
            $ajUsers = $stmt->fetchAll();
            if(count($ajUsers) < 1) {
                // wrong credentials
                echo '{"status":"error","message":"there are no users who match your interest"}';
                exit;
            }

            foreach($ajUsers as $jUser) {
                if(!$jMatrix[$sId][$jUser['id']]) {
                    $jData->id = $jUser['access_token'];
                    $jData->first_name = $jUser['first_name'];
                    $jData->last_name = $jUser['last_name'];
                    $jData->imageUrl = $jUser['profile_image'];
                    $jData->age = $jUser['age'];
                    $jData->gender = $jUser['gender'];
                    $jData->description = $jUser['motto'];
                    $jData->nextUserInterest = $iInterest;
                    
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

    function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}