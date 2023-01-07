<?php
    $query = sprintf("
          SELECT CM.ComboID AS ComboID, 
                CM.ComboName AS ComboName,
                CM.ComboDesc AS ComboDesc,
                CM.OverrideDisplay AS ComboOverrideDisplay
          FROM ComboMain CM  
          ORDER BY CM.ComboID");
    $result = mysqli_query($connect, $query);
    $table = array();

    while($row = mysqli_fetch_array($result))
    {
        array_push($table, array(
            "ComboID" => $row["ComboID"],
            "name" => $row["ComboName"],
            "desc" => $row["ComboDesc"],
            "override" => $row["ComboOverrideDisplay"]
        ));
    }
    $countArrayLength = count($table);
