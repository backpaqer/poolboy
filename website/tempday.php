<?php
    include("php/config.php");
    include("php/getCurrentTemp.php");

    if(isset($_GET["sensor"]) && !empty($_GET["sensor"])) {
        if(substr($_GET["sensor"],0,3) == "28-" && strlen($_GET["sensor"])==15) {
            $sensorcode = $_GET["sensor"];
        } elseif (substr($_GET["sensor"],0,3) == "ALL") {
            $sensorcode = "ALL";
        } else {
            $sensorcode = "no sensor";
        }
    }

    if($sensorcode == "ALL") {
        $query = "SELECT DISTINCT CONCAT('[',TIME_FORMAT(TH.TempTime, '%H,%i,%s'),']') AS SenseTime,";
        for ($i=0;$i<$NumberOfSensors;$i++){
            $query.="(SELECT MAX(FORMAT(TempValue,3)) FROM TempHistory 
                    WHERE TIME_FORMAT(TempTime, '%H%i') IN (TIME_FORMAT(ADDTIME(TH.TempTime,'00:01:00'), '%H%i'),TIME_FORMAT(TH.TempTime, '%H%i'))
                    AND TempSensor = '".$CurrentSensors[$i]['SensorCode']."') AS Temp".$i;
            if($i<$NumberOfSensors-1){$query.=",";} else {$query.=" ";}
        }
        $query.="FROM TempHistory TH INNER JOIN Sensors S ON TH.TempSensor = S.SensorCode 
                WHERE TIME_FORMAT(TH.TempTime, '%i') IN (0,15,30,45)
                ORDER BY 1";
    } else {
        $query = "SELECT CONCAT('[',TIME_FORMAT(TH.TempTime, '%H,%i,%s'),']') AS SenseTime,";
        $query.="FORMAT(TH.TempValue,3) AS SenseTemp,
                    S.SensorName AS SenseName
                FROM TempHistory TH INNER JOIN Sensors S ON TH.TempSensor = S.SensorCode 
                WHERE S.SensorCode = '".$sensorcode."'
                AND TIME_FORMAT(TH.TempTime, '%i') IN (0,15,30,45)
                ORDER BY 1";
    }
    $result = mysqli_query($connect, $query);
    $table = array();
    $row = array();

    while($row = mysqli_fetch_array($result))
    {
        if($sensorcode=="ALL"){
            $arrStr = "";
            for($i=0;$i<$NumberOfSensors+1;$i++) {
                if($i>0) {
                    $arrStr.=",";
                }
                $arrStr.=$row[$i];
            }
            array_push($table,
                array($arrStr)
            );
        } else {
            $SensorName[0]=$row["SenseName"];
            array_push($table,
                array("time" => $row["SenseTime"],
                    "temperature" => $row["SenseTemp"]
                )
            );
        }
    }

    $countArrayLength = count($table);
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            #myVideo {
                position: fixed;
                z-index: -1;
                right: 0;
                bottom: 0;
                min-width: 100%;
                min-height: 100%;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="css/default.css">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">
   
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
        var data = new google.visualization.DataTable();
        data.addColumn('timeofday', 'Time');
        <?php
                if($sensorcode=="ALL") {
                    for ($i=0;$i<$NumberOfSensors;$i++) {
                        echo "data.addColumn('number', '".$CurrentSensors[$i]['SensorName']."');\n";
                    }
                } else {
                    echo "data.addColumn('number', 'Temperature');";
                }
        ?>

        data.addRows([
          <?php
            if($sensorcode=="ALL") {
                $SensorName[0]="ALL";
                for ($i=0;$i<$countArrayLength;$i++) {
                    echo "[".$table[$i][0]."]";
                    if ($i < $countArrayLength-1) {
                        echo ",";
                    }
                }
            } else {
                for ($i=0;$i<$countArrayLength;$i++) {
                    if ($i > 0) {
                        if ($table[$i]['temperature'] > 0) {
                            echo ",[" . $table[$i]['time'] . "," . $table[$i]['temperature'] . "]";
                        }
                    } else {
                        echo "[" . $table[$i]['time'] . "," . $table[$i]['temperature'] . "]";
                    }
                }
            }
          ?>]);

        var options = {
         title:       '<?php echo $SensorName[0] ?>',
         legend:      {position:'bottom'},
         curveType:   'function',
         lineWidth:   3,
         chartArea:   {width:'80%', height:'70%'},
         hAxis:       {title: 'Time of Day', minValue: [0,0,0], maxValue: [24,0,0]},
         vAxis:       {title: 'Temp'}
        };

        var chart = new google.visualization.LineChart(document.getElementById('line_chart'));

        chart.draw(data, options);
        }
        </script>
    </head>
    <body>
        <video autoplay muted loop id="myVideo">
            <source src="water2.mp4" type="video/mp4">
        </video>
        <button class="poolbutton button12" onclick="location.href='index.php'">POOLBOY</button><BR>
        <div id="line_chart" style="width: 100%; height: 60%;">
        </div>

        <div>
            <?php
                for($i=0;$i<count($CurrentSensors);$i++) {
                    echo "<button class=\"hbutton button12\" onclick=\"location.href='tempday.php?sensor=".
                      $CurrentSensors[$i]['SensorCode']."'\">".
                      $CurrentSensors[$i]['SensorName']."</button>\n";
                }
            ?>
            <button class="hbutton button12" onclick="location.href='tempday.php?sensor=ALL'">All Temps</button>
        </div>
        <div class="fixed">
            <?php
                echo "<button class=\"buttonwide button12\" onclick=\"location.href='settingcombos.php'\">CURRENT TIME<BR> ".date("g:i A")."</button>";
                if (is_null($nextEvent[0])) {
                    echo "<button class=\"buttonwide button12\">NO EVENTS PENDING</button>";
                } else {
                    echo "<button class=\"buttonwide button12\">".$nextEvent[1]." ".$nextEvent[2]."<BR>".date("g:i A",strtotime($nextEvent[0]))."</button>";
                }
            ?>
        </div>        
    </body>
</html>
