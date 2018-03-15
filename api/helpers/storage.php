<?php
    function saveToStorage($aElements, $location) {
        $saElements = json_encode($aElements);
        file_put_contents($sLocation, $saElements);
    }