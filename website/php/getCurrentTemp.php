<?php
    $query = sprintf("
          SELECT 
            FORMAT(TH.TempValue,1) AS CurrentTemp,
            SE.SensorName AS SensorName,
            SE.SensorCode AS SensorCode  
          FROM Sensors SE 
          INNER JOIN TempHistory TH ON SE.SensorCode = TH.TempSensor
          WHERE SE.SensorType = 'Temp'  
          AND TH.TempTime IN (SELECT MAX(TempTime) FROM TempHistory GROUP BY TempSensor) 
          ORDER BY TempSensor");

    $CurrentSensors = array();

    $result = mysqli_query($connect, $query);
    if(! $result) {
        array_push($CurrentSensors,
            array("CurrentTemp" => "0",
                "SensorName" => "No Sensor",
                "SensorCode" => "No Sensor"
            )
        );
    } else {
        while ($row = mysqli_fetch_array($result)) {
            array_push($CurrentSensors,
                array("CurrentTemp" => $row["CurrentTemp"],
                    "SensorName" => $row["SensorName"],
                    "SensorCode" => $row["SensorCode"]
                )
            );
        }
    }

    $NumberOfSensors = count($CurrentSensors);

    if($NumberOfSensors==0){
        array_push($CurrentSensors,
            array("CurrentTemp" => "0",
                "SensorName" => "No Sensor",
                "SensorCode" => "No Sensor"
            )
        );
    }




