<?php
$query = sprintf("
          SELECT ComboID
          FROM MainTimer
          WHERE TimerDay = dayofweek(now())
          AND TimerStart <= now()
          AND TimerEnd > now()
          AND TimerType = 'normal'
          ORDER BY TimerType DESC 
          LIMIT 1");
$result = mysqli_query($connect, $query);

$row = mysqli_fetch_array($result);
$ActiveNormalComboID = $row["ComboID"];
