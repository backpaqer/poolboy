<?php
    if(isset($_POST["comboID"]) && !empty($_POST["comboID"])) {
        $ActiveCombo = $_POST["comboID"];
    }

    if(isset($_POST["dayID"]) && !empty($_POST["dayID"])) {
        $ActiveDay = $_POST["dayID"];
    }

    if (isset($_POST["timerstart"]) && !empty($_POST["timerstart"])) {
        $TimerStart = $_POST["timerstart"];

    }

    if (isset($_POST["timerend"]) && !empty($_POST["timerend"])) {
        $TimerEnd = $_POST["timerend"];
    }

    if (isset($_POST["commitadd"]) && !empty($_POST["commitadd"])) {
        $Command = $_POST["commitadd"];
    }

    if($TimerStart<0) {
        $TimerStart=0;
    }

    if($TimerStart>86359) {
        $TimerStart=86359;
    }

    if($TimerEnd<0) {
        $TimerEnd=0;
    }

    if($TimerEnd>86359) {
        $TimerEnd=86359;
    }

    if(($TimerStart>$TimerEnd) && ($Command==1)) {
        $TimerEnd=$TimerStart;
    }

    if(($TimerEnd<$TimerStart) && ($Command==2)) {
        $TimerStart=$TimerEnd;
    }

    if($Command==99) { 
        $TimeStartHour = floor($TimerStart/3600);
        $TimeStartMin = floor(($TimerStart-($TimeStartHour*3600))/60);
        $TimeStartSec = $TimerStart % 60;

        $TimeEndHour = floor($TimerEnd/3600);
        $TimeEndMin = floor(($TimerEnd-($TimeEndHour*3600))/60);
        $TimeEndSec = $TimerEnd % 60;

        $StartTime = substr("0".$TimeStartHour,-2).":".substr("0".$TimeStartMin,-2).":".substr("0".$TimeStartSec,-2);
        $EndTime = substr("0".$TimeEndHour,-2).":".substr("0".$TimeEndMin,-2).":".substr("0".$TimeEndSec,-2);

        if($ActiveDay==8) {
            $sql = "INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState) 
            SELECT C.ComboID, C.RelayID, 'normal',X.seq,'$StartTime','$EndTime',C.RelayState 
            FROM ComboItem C  
            CROSS JOIN (SELECT seq FROM seq_1_to_7) X 
            WHERE C.ComboID = $ActiveCombo;";
        } else {
            $sql = "INSERT INTO MainTimer (ComboID, RelayID, TimerType, TimerDay, TimerStart, TimerEnd, RelayState) 
                SELECT C.ComboID, C.RelayID, 'normal',$ActiveDay,'$StartTime','$EndTime',C.RelayState 
                FROM ComboItem C  
                WHERE C.ComboID = $ActiveCombo;";
        }
        $_POST = array();
        if(mysqli_query($connect, $sql)) {
            header("location:schedulelist.php");
        } else {
            echo("SQL:" . $sql);
            echo "<BR>";
            echo("Error description - " . mysqli_error($connect));
        }
    }


