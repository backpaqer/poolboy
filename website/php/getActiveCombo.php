<?php
$query = sprintf("
          SELECT ComboID, TimerType
          FROM MainTimer
          WHERE TimerDay = dayofweek(now())
          AND TimerStart <= now()
          AND TimerEnd > now()
          AND TimerType <> 'silence'
          ORDER BY TimerType DESC 
          LIMIT 1");
$result = mysqli_query($connect, $query);

$row = mysqli_fetch_array($result);
$ActiveComboID = $row["ComboID"];
$ActiveComboType = $row["TimerType"];
