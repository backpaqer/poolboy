<?php
    $query = sprintf("
          SELECT ComboID AS ComboID, 
                ComboName AS ComboName, 
                ComboDesc AS ComboDesc, 
                TimerDay AS TimerDay, 
                TimerStart AS TimerStart, 
                TimerEnd AS TimerEnd
          FROM View_ActiveSchedules
          ORDER BY TimerDay, TimerStart");
    $result = mysqli_query($connect, $query);
    $schedlist = array();

    while($row = mysqli_fetch_array($result))
    {
        array_push($schedlist, array(
            "ComboID" => $row["ComboID"],
            "name" => $row["ComboName"],
            "desc" => $row["ComboDesc"],
            "day" => $row["TimerDay"],
            "start" => $row["TimerStart"],
            "end" => $row["TimerEnd"]
        ));
    }
    $schedlistArrayLength = count($schedlist);
