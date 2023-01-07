<?php
//index.php
$connect = mysqli_connect("localhost", "poolboy", "poolpass", "poolboy");
$query = sprintf("
  SELECT TS.TempTime AS SenseDate, 
    FORMAT(TS.TempValueMin,1) AS SenseTempMin,
    FORMAT(TS.TempValueMax,1) As SenseTempMax, 
    FORMAT(TS.TempValueAvg,1) As SenseTempAvg
  FROM TempSummary TS INNER JOIN Sensors S ON TS.TempSensor = S.SensorCode 
  WHERE S.SensorName = 'Air Temp'
  ORDER BY TS.TempTime");
$result = mysqli_query($connect, $query);
$rows = array();
$table = array();

while($row = mysqli_fetch_array($result))
{
  array_push($table, array("date" => $row["SenseDate"],
                                "mintemp" => $row["SenseTempMin"],
                                "maxtemp" => $row["SenseTempMax"],
                                "avgtemp" => $row["SenseTempAvg"])
  );
}
$table['rows'] = $rows;
$countArrayLength = count($table) - 1
?>


<html>
 <head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
   
   google.charts.load('current', {'packages':['corechart']});
   google.charts.setOnLoadCallback(drawChart);

   function drawChart()
   {
    var data = google.visualization.arrayToDataTable([
        <?php
        for ($i=0;$i<$countArrayLength;$i++){
            echo "['" . $table[$i]['date'] . "'," .
                        $table[$i]['mintemp'] . "," .
                        $table[$i]['mintemp'] . "," .
                        $table[$i]['maxtemp'] . "," .
                        $table[$i]['maxtemp'] . "]";
            if($i!=($countArrayLength-1)) echo ",";
        }
        ?>
        // Treat the first row as data.
    ], true);

    var options = {
        title: 'Daily Temp Range',
        legend: 'none',
        bar: {groupWidth: '80%'},
        candlestick: {
            fallingColor: {strokeWidth: 0, fill: '#a52714'}, //red
            risingColor: {strokeWidth: 0, fill: '#04009d'} //blue
        }
    };

    var chart = new google.visualization.CandlestickChart(document.getElementById('summary_chart'));

    chart.draw(data, options);
   }
  </script>
 </head>  
 <body>
  <div class="page-wrapper">
   <br />
   <div id="summary_chart" style="width: 100%; height: 600px"></div>
  </div>
 </body>
</html>
