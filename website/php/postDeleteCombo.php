<?php
    if(isset($_POST["comboID"]) && !empty($_POST["comboID"]) && ($_POST["relayRun"]==1)) { 
        $sql = "CALL deleteCombo(".$_POST["comboID"].")";
        $_POST = array();
        if(mysqli_query($connect, $sql)) {
            header("location:settingcombos.php");
        }
    }
