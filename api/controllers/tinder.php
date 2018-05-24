<?php
    function checkNewMatch($db) {
        $sId = verifyLogin();
        $sId = dbGetUserId($sId, $db);

        $sPath = '/matches/get/'.$sId;
        $iMatch = CallAPI('GET', $sPath);

        if($iMatch == 1) {
            $sPath = '/matches/update/'.$sId.'/0';
            CallAPI('GET', $sPath);
            echo '{"status":"success", "message":"desktop notification", "data":""}';
            exit;
        }

        echo '{"status":"error", "message":"desktop notification"}';
    }

    function onLike($db) {
        $sId = verifyLogin();
        $sLikeId = $_POST['likeId'];
        $sLike = $_POST['like'];
        
        $sId = dbGetUserId($sId, $db);
        $sLikeId = dbGetUserId($sLikeId, $db);
        
        if($sId == $sLikeId) {
            echo '{"status":"error","message":"user id is not valid"}';
            exit;
        }

        $sPath = '/like/'.$sId.'/'.$sLikeId.'/'.$sLike; 
        $jData = CallAPI('GET', $sPath);

        if(isMatch($sId, $sLikeId)) {
            $sPath = '/matches/update/'.$sId.'/1';
            CallAPI('GET', $sPath);

            $sPath = '/matches/update/'.$sLikeId.'/1';
            CallAPI('GET', $sPath);
        }

        dbIncreaseSwipeCount($sId, $db);
        
        echo '{"status":"success", "message":"like registered", "data":""}';
    }

    function isMatch($sId1, $sId2) { 
        $sPath = '/matches/'.$sId1;
        $jUserLikes1 = CallAPI('GET', $sPath);

        $sPath = '/matches/'.$sId2;
        $jUserLikes2 = CallAPI('GET', $sPath);

        if($jUserLikes1[$sId2] == $jUserLikes2[$sId1] && $jUserLikes2[$sId1] == 'like') {
            return true;
        }
        return false;
    }


    function dbGetMatches($db) {
        $accessToken = verifyLogin();
        $sId = dbGetUserId($accessToken, $db);
        
        $sPath = '/matches/'.$sId;
        $jUserLikes = CallAPI('GET', $sPath);
        $aMatches = [];
        $aUsers = dbFetchUsers($sId, $db);
        
        foreach($aUsers as $aUser) {
            $sUserId = $aUser['id'];
            $sPath = '/matches/'.$sUserId;
            $jMatchLikes = CallAPI('GET', $sPath);
            if($jMatchLikes[$sId] == $jUserLikes[$sUserId] && $jMatchLikes[$sId] == 'like') {
                $aUser['imageUrl'] = $aUser['profile_image'];
                array_push($aMatches, $aUser);
            }
        }

        if(count($aMatches) > 0) {
            file_put_contents('../storage/matches.txt');
            $saMatches = json_encode($aMatches);
            echo '{"status":"success", "message":"here are your matches", "data":'.$saMatches.'}';
            exit;
        }
        
        echo '{"status":"error","message":"there are no matches"}';
        exit;
    }