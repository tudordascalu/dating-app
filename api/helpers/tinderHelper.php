<?php 
    function checkSwapCount($sId, $ajUsers) { 
        foreach($ajUsers as $jU) {
            $dCurrentDate = date("Y/m/d");
            $sUserId = $jU->id;
            if($sUserId == $sId) {
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