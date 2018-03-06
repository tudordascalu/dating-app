<?php   
    // $sjMatrix = file_get_contents('./storage/matches.txt');
    // $jMatrix = json_decode($sjMatrix);
    function onLike($ajUsers, $jMatrix) {
       verifyLogin();
       $sId = $_GET['id'];
       $sLikeId = $_POST['likeId'];
       $sLike = $_POST['like'];
       if($sId == $sLikeId) {
        echo '{"status":"error","message":"user id is not valid"}';
        exit;
       }
       checkIfValidId($ajUsers, $sLikeId);

       $sLike = $_POST['like'];
       $jMatrix[$sId][$sLikeId] = $sLike;
       // check if it s a match and add new_match: 1 in jMatrix
       if(isMatch($sId, $sLikeId)) {
           $jMatrix[$sId]['new_match'] = 1;
           $jMatrix[$sLikeId]['new_match'] = 1;
       }
       
       $sjMatrix = json_encode($jMatrix);
       file_put_contents('./storage/matches.txt', $sjMatrix);
       echo '{"status":"success", "message":"like registered", "data":'.$sjMatrix.'}';
    }

    function getNextUser($ajUsers, $jMatrix) {
        $sId = $_GET['id'];
        // echo json_encode($ajUsers);
        foreach($ajUsers as $jUser) {
            $sUserId = $jUser->id;
            if(!$jMatrix[$sId][$sUserId] && $sId != $sUserId) {
                $jData->id = $jUser->id;
                $jData->first_name = $jUser->first_name;
                $jData->last_name = $jUser->last_name;
                $jData->imageUrl = $jUser->imageUrl;
                $jData->age = $jUser->age;
                $jData->gender = $jUser->gender;
                
                $sjData = json_encode($jData);
                echo '{"status":"success", "message":"this is the next user", "data":'.$sjData.'}';
                exit;
            }
        }
        echo '{"status":"error","message":"no more users"}';
        exit;
    }

    function checkIfValidId($ajUsers, $sLikeId) {
        foreach($ajUsers as $jUser) {
            if($sLikeId == $jUser->id) {
                // existing user
                return;
            }
        }
        echo '{"status":"error","message":"user id is not valid"}';
        exit;
    }

    function isMatch($jMatrix, $sId1, $sId2) { 
        if($jMatrix[$sId1][$sId2] == $jMatrix[$sId2][$sId1] && $jMatrix[$sId1][$sId2] == 'like') {
            return true;
        }
        return false;
    }

    function getMatches($ajUsers, $jMatrix) {
        $sId = verifyLogin();
        $aMatches = [];
        foreach($ajUsers as $jUser) {
            $sUserId = $jUser->id;
            if($jMatrix[$sId][$sUserId] == $jMatrix[$sUserId][$sId] && $jMatrix[$sId][$sUserId] == 'like') {
                array_push($aMatches, $jUser);
            }
        }
        if(count($aMatches) > 0) {
            $jMatrix[$sId]['new_match'] = 0;
            $sjMatrix = json_encode($jMatrix);
            file_put_contents('../storage/matches.txt');
            $saMatches = json_encode($aMatches);
            echo '{"status":"success", "message":"here are your matches", "data":'.$saMatches.'}';
            exit;
        }
        
        echo '{"status":"error","message":"there are no matches"}';
        exit;
    }

    function isNewMatch($jMatrix) {
        $sId = verifyLogin();
        if($jMatrix[$sId]['new_match'] == 1) {
            echo '{"status":"success","message":"you have a match"}';
            exit;
        }
        
        echo '{"status":"error","message":"there are no matches"}';
        exit;
    }