<?php
    $relayNames = array();
    $updateFlag = 0;
    for ($i=0;$i<16;$i++){
        $relayName = "relayName".$i;
        if(isset($_POST[$relayName])) {
            array_push($relayNames, $_POST[$relayName]);
            if ($_POST[$relayName] != $table[$i]['desc']) {
                $sql = "CALL editRelayName(".$table[$i]['RelayID'].",'".$_POST[$relayName]."')";
                if (mysqli_query($connect, $sql)) {
                    $updateFlag = 1;
                };
            }
        }
    }

    if ($updateFlag == 1) {
        $_POST = array();
        header("location:settingrelays.php");
    }




