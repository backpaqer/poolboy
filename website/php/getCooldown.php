<?php
    $query = sprintf("
    SELECT COUNT(*) AS CooldownCount 
    FROM Cooldown C 
    WHERE 0 =
        (SELECT 1 FROM MainTimer M 
        WHERE M.RelayID =  C.MainRelayID 
        AND M.TimerDay = dayofweek(now()) 
        AND M.TimerStart <= now() 
        AND M.TimerEnd > now() 
        AND M.TimerType IN ('override', 'normal'));");
    $result = mysqli_query($connect, $query);
    $cooldown_count = 0;
    while($row=mysqli_fetch_array($result))
    {
        $cooldown_count = $row["CooldownCount"];
    }


