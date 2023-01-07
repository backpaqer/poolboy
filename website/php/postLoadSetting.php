<?php
    $ComboID = $_POST["comboID"];
    $RelayID = $_POST["relayID"];
    $RelayState = $_POST["relayState"];

    if(isset($_POST["comboID"]) && !empty($_POST["comboID"])) {
        if($RelayID!=0) {
            /* RelayState cycles thru 
                0 = Doesn't exist so create ComboItem for Combo/Relay with State = 1, Delay = 0, RelayRun = 180
                1 = Exists in ComboItem so set RelayState to 0
                2 = Exists in ComboItem so delete it
            */
            if ($RelayState==0) {
                $sql = "INSERT INTO ComboItem (ComboID, RelayID, RelayState, RelayStartPad, RunMins) VALUES (".$ComboID.",".$RelayID.",1,0,180)";
            } elseif ($RelayState==1) {
                $sql = "UPDATE ComboItem SET RelayState = 0 WHERE ComboID = ".$ComboID." AND RelayID = ".$RelayID;
            } else {
                $sql = "DELETE FROM ComboItem WHERE ComboID = ".$ComboID." AND RelayID = ".$RelayID;
            }
            mysqli_begin_transaction($connect);
            mysqli_query($connect, $sql);
            mysqli_commit($connect);
        }

        /* load the relay buttons */
        $sql = "SELECT R.RelayDescription AS RelayDescription, 
                    R.RelayID AS RelayID,
                    (SELECT RelayState FROM ComboItem WHERE ComboID = ".$ComboID." AND RelayID = R.RelayID) AS RelayState,
                    (SELECT RelayStartPad FROM ComboItem WHERE ComboID = ".$ComboID."  AND RelayID = R.RelayID) AS RelayDelay,
                    (SELECT RunMins FROM ComboItem WHERE ComboID = ".$ComboID."  AND RelayID = R.RelayID) AS RelayRun
                FROM Relays R
                WHERE R.RelayActive = 1";

        /* clear out $_POST */
        $_POST = array();

        $result = mysqli_query($connect, $sql);
        $relays = array();

        while($row = mysqli_fetch_array($result))
        {
            array_push($relays, array(
                "ComboID" => $ComboID,
                "RelayID" => $row["RelayID"],
                "Name" => $row["RelayDescription"],
                "State" => $row["RelayState"],
                "Delay" => $row["RelayDelay"],
                "Run" => $row["RelayRun"]
            ));
        }
        $RelayCount = count($relays);
    }
