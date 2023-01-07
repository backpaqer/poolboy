<?php
include("php/config.php");
include("php/postAddSchedule.php");
include("php/loadComboButtons.php");
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
    <script src="js/postScheduleAdd.js"></script>
</head>
<body>

<video autoplay muted loop id="myVideo" class="backvideo">
    <source src="water2.mp4" type="video/mp4">
</video>
<div>
    <button class="poolbutton button12" onclick="location.href='schedulelist.php'">POOLBOY</button><BR>
    CREATE SCHEDULE<BR>
    <?php
        if ($ActiveCombo==0) {
            echo "<button class=\"hbutton button12\" style=\"width:100%;height:20px;\">SELECT FUNCTION</button>";
            for ($i=0;$i<$countArrayLength;$i++){
                echo "<button class=\"";
                if ($table[$i]['ComboID'] == $ActiveCombo) {
                    echo "active";
                }
                echo "button button12\" onclick=\"post(".$table[$i]['ComboID'].")\">" . $table[$i]['name'] . "</button>";
            }
        } else {
            if ($ActiveDay==0) {
                echo "<button class=\"hbutton button12\" style=\"width:100%;height:20px;\">SELECT DAY</button>";
            } else {
                echo "<button class=\"hbutton button12\" style=\"width:100%;height:20px;\">SET TIMES</button>";
            }

            for ($i=0;$i<$countArrayLength;$i++){
                if ($ActiveCombo==$table[$i]['ComboID']) {
                    echo "<button class=\"activebutton button12\" onclick=\"post(0)\">" . $table[$i]['name'] . "</button>";
                }
            }
            if ($ActiveDay==0) {
                echo "<BR>";
                echo "<button class=\"daybutton button12\" style=\"width:55px;\" onclick=\"post(".$ActiveCombo.",1)\">SUN</button>";
                echo "<button class=\"daybutton button12\" style=\"width:55px;\" onclick=\"post(".$ActiveCombo.",2)\">MON</button>";
                echo "<button class=\"daybutton button12\" style=\"width:55px;\" onclick=\"post(".$ActiveCombo.",3)\">TUE</button>";
                echo "<button class=\"daybutton button12\" style=\"width:55px;\" onclick=\"post(".$ActiveCombo.",4)\">WED</button>";
                echo "<button class=\"daybutton button12\" style=\"width:55px;\" onclick=\"post(".$ActiveCombo.",5)\">THU</button>";
                echo "<button class=\"daybutton button12\" style=\"width:55px;\" onclick=\"post(".$ActiveCombo.",6)\">FRI</button>";
                echo "<button class=\"daybutton button12\" style=\"width:55px;\" onclick=\"post(".$ActiveCombo.",7)\">SAT</button>";
                echo "<BR>";
                echo "<button class=\"daybutton button12\" style=\"width:55px;\" onclick=\"post(".$ActiveCombo.",8)\">ALL</button>";
            } else {
                $schedday = substr("SUNMONTUEWEDTHUFRISATALL", (($ActiveDay - 1) * 3),3);

                $TimeStartHour = floor($TimerStart/3600);
                $TimeStartMin = floor(($TimerStart-($TimeStartHour*3600))/60);
                $TimeStartSec = $TimerStart % 60;

                $TimeEndHour = floor($TimerEnd/3600);
                $TimeEndMin = floor(($TimerEnd-($TimeEndHour*3600))/60);
                $TimeEndSec = $TimerEnd % 60;

                $StartSecReset = (($TimeStartHour*3600)+($TimeStartMin*60));
                $EndSecReset = (($TimeEndHour*3600)+($TimeEndMin*60));

                $StartQtrHr = ((floor($TimeStartMin/15)+1)*15);
                $StartQtrBump = (($TimeStartHour*3600)+($StartQtrHr*60));

                $EndQtrHr = ((floor($TimeEndMin/15)+1)*15);
                $EndQtrBump = (($TimeEndHour*3600)+($EndQtrHr*60));

                $StartTime = substr("0".$TimeStartHour,-2).":".substr("0".$TimeStartMin,-2).":".substr("0".$TimeStartSec,-2);
                $EndTime = substr("0".$TimeEndHour,-2).":".substr("0".$TimeEndMin,-2).":".substr("0".$TimeEndSec,-2);                

                echo "<button class=\"activebutton button12\" onclick=\"post(".$ActiveCombo.",0,0,0)\">".$schedday."</button>";
                echo "<button class=\"activebutton button12\">".$StartTime."<BR>".$EndTime."</button>";
                echo "<BR>";
                echo "<button class=\"hbutton button12\" style=\"width:180px;height:20px;\">START</button>";
                echo "<button class=\"hbutton button12\" style=\"width:180px;height:20px;\">END</button>";
                echo "<BR>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+3600).",".($TimerEnd+0).",1)\">+</button>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+60).",".($TimerEnd+0).",1)\">+</button>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+1).",".($TimerEnd+0).",1)\">+</button>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+0).",".($TimerEnd+3600).",2)\">+</button>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+0).",".($TimerEnd+60).",2)\">+</button>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+0).",".($TimerEnd+1).",2)\">+</button>";
                echo "<BR>";
                echo "<button class=\"daybutton button12\" style=\"height: 40px\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+10800).",".($TimerEnd+0).",1)\">".substr("0".$TimeStartHour,-2)."</button>";
                echo "<button class=\"daybutton button12\" style=\"height: 40px\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".$StartQtrBump.",".$TimerEnd.",1)\">".substr("0".$TimeStartMin,-2)."</button>";
                echo "<button class=\"daybutton button12\" style=\"height: 40px\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".$StartSecReset.",".$TimerEnd.",1)\">".substr("0".$TimeStartSec,-2)."</button>";
                echo "<button class=\"daybutton button12\" style=\"height: 40px\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+0).",".($TimerEnd+10800).",2)\">".substr("0".$TimeEndHour,-2)."</button>";
                echo "<button class=\"daybutton button12\" style=\"height: 40px\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".$TimerStart.",".$EndQtrBump.",2)\">".substr("0".$TimeEndMin,-2)."</button>";
                echo "<button class=\"daybutton button12\" style=\"height: 40px\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".$TimerStart.",".$EndSecReset.",2)\">".substr("0".$TimeEndSec,-2)."</button>";
                echo "<BR>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart-3600).",".($TimerEnd+0).",1)\">-</button>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart-60).",".($TimerEnd+0).",1)\">-</button>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart-1).",".($TimerEnd+0).",1)\">-</button>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+0).",".($TimerEnd-3600).",2)\">-</button>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+0).",".($TimerEnd-60).",2)\">-</button>";
                echo "<button class=\"daybutton button12\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+0).",".($TimerEnd-1).",2)\">-</button>";
                echo "<BR>";
                echo "<button class=\"hbutton button12\" style=\"width:100%\" onclick=\"post(".$ActiveCombo.",".$ActiveDay.",".($TimerStart+0).",".($TimerEnd+0).",99)\">ADD</button>";
            }
        }
    ?>
</div>
</body>
</html>
