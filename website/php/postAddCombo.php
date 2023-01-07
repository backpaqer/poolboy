<?php
    if ( isset( $_POST["comboName"] ) && !empty($_POST["comboName"])) {
        $comboName = $_POST["comboName"];
        $comboDesc = $_POST["comboDesc"];
    }

    $comboName = strtoupper(preg_replace( "#[^\w]#", " ", $comboName, 8 ));
    $comboDesc = preg_replace( "#[^\w]#", " ", $comboDesc, 512 );

    if (strlen($comboName) > 2) {
        $sql = "CALL insertCombo('$comboName','$comboDesc')";
        $_POST = array();
        if(mysqli_query($connect, $sql)) {
            header("location:settingcombos.php");
        }
    }




