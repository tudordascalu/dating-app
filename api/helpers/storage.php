<?php
    function saveToStorage($aElements, $sLocation) {
        $saElements = json_encode($aElements);
        file_put_contents($sLocation, $saElements);
    }