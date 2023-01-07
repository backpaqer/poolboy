<?php
    $RelayID = $_POST["relayID"];
    $RelayState = $_POST["relayState"];
    
    if(isset($_POST["relayID"]) && !empty($_POST["relayID"])) {
        $sql = "CALL loadOverrideRelay(".$RelayID.",".$RelayState.",60)";
        $_POST = array();
        if(mysqli_query($connect, $sql)) {
            header("location:settingrelays.php");
        }
    }




