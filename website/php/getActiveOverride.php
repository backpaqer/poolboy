<?php
$query = sprintf("
          SELECT IFNULL(MAX(1),0) AS ActiveOverride
          FROM MainTimer
          WHERE TimerType = 'override'");
$result = mysqli_query($connect, $query);

$row = mysqli_fetch_array($result);
$ActiveOverride = $row["ActiveOverride"];

