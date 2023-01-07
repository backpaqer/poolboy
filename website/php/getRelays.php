<?php
    $query = sprintf("
        SELECT R.RelayID AS RelayID, 
            R.RelayGPIOpin AS RelayGPIOpin,
            R.RelayDescription AS RelayDescription,
            R.RelayOrder AS RelayOrder,
            R.RelayActive AS RelayActive,
            (SELECT M.RelayState 
            FROM MainTimer M
            WHERE M.TimerDay = dayofweek(now())
            AND M.TimerStart <= now()
            AND M.TimerEnd > now()
            AND M.RelayID = R.RelayID
            AND M.TimerType IN ('overrider', 'override', 'normal')
            ORDER BY M.TimerType DESC
            LIMIT 1
            ) AS RelayState
        FROM Relays R");
    $query.=" ORDER BY R.RelayOrder";
    $result = mysqli_query($connect, $query);
    $table = array();

    while($row=mysqli_fetch_array($result))
    {
        array_push($table, array(
            "RelayID" => $row["RelayID"],
            "GPIO" => $row["RelayGPIOpin"],
            "desc" => $row["RelayDescription"],
            "order" => $row["RelayOrder"],
            "active" => $row["RelayActive"],
            "status" => $row["RelayState"]
        ));
    }
    $countArrayLength = count($table);


