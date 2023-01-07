<?php
    if(isset($_POST["comboID"])) {
        $sql = "CALL loadOverride(".$_POST["comboID"].")";
        $_POST = array();
        if(mysqli_query($connect, $sql)) {
            header("location:index.php");
        }
    }
