<?php
    if(isset($_POST["comboID"]) && !empty($_POST["comboID"])) { 
        $sql = "CALL clearNormal(".$_POST["comboID"].",".$_POST["dayID"].",\"".$_POST["timerstart"]."\")";
        $_POST = array();
        if(mysqli_query($connect, $sql)) {
            header("location:schedulelist.php");
        }
    }
