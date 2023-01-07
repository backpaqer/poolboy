<?php
    $query = sprintf("
          SELECT *  
          FROM View_NextTimer
          ");
    $result = mysqli_query($connect, $query);

    $nextEvent = mysqli_fetch_array($result);
