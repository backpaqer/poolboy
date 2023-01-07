<?php
include("php/config.php");
include("php/postDeleteSchedule.php");
include("php/loadScheduleList.php");
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
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="js/postSchedule.js"></script>
</head>
<body>
<video autoplay muted loop id="myVideo" class="backvideo">
    <source src="water2.mp4" type="video/mp4">
</video>
<div>
    <button class="poolbutton button12" onclick="location.href='index.php'">POOLBOY</button><BR>
    SCHEDULE LISTING<BR>
    <button class="listbutton button12" style="height: 40px">FUNCTION
    </button><button class="daybutton button12" style="height: 40px">DAY
    </button><button class="listbutton button12" style="height: 40px">START/END
    </button><button class="daybutton button12" style="height: 40px; width: 80px">ACTION</button>
    <BR> 
    <?php   
    /* List of schedules loop */
    for ($i=0;$i<$schedlistArrayLength;$i++){
        $Array = $schedlist[$i]['ComboID'];
        $schedday = substr("SUNMONTUEWEDTHUFRISAT", (($schedlist[$i]['day'] - 1) * 3),3);
        $settingArray = $schedlist[$i]['ComboID'].",".$schedlist[$i]['day'].",'".$schedlist[$i]['start']."'";
        echo "<button class=\"listbutton button12\">" . $schedlist[$i]['name'] . "</button>";
        echo "<button class=\"daybutton button12\">" . $schedday . "</button>";
        echo "<button class=\"listbutton button12\">" . $schedlist[$i]['start'] . "<BR>" . $schedlist[$i]['end'] . "</button>";
        echo "<button class=\"deletebutton button12\" onclick=\"post(".$settingArray.")\">DELETE</button>";
        echo "<BR>";
    }
    ?>
</div>
<div class="fixed">
    <button class="button button12" onclick="location.href='scheduleadd.php'">NEW</button>
</div>
</body>
</html>
