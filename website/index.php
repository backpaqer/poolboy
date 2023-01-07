<?php
    include("php/config.php");
    include("php/postLoadCombo.php");
    include("php/getActiveCombo.php");
    include("php/getActiveNormalCombo.php");
    include("php/getActiveOverride.php");
    include("php/loadComboButtons.php");
    include("php/getCurrentTemp.php");
    include("php/getNextTimer.php");
    include("php/getCooldown.php");
?>

<html>
 <head>
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta http-equiv="refresh" content="60">
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
     <script src="js/postIndex.js"></script>
     <script src="js/cooldown.js"></script>
 </head>
 <body>
    <video autoplay muted loop id="myVideo" class="backvideo">
         <source src="water2.mp4" type="video/mp4">
    </video>
    <div>
        <?php
        if ($cooldown_count > 0) {
            echo "<img src=\"img/cooldown.jpg\" class=\"cooldown\">";
        }
        ?>
        <button class="poolbutton button12">POOLBOY</button><BR>
        <?php
        for ($i=0;$i<$countArrayLength;$i++){
            if ($table[$i]['override']==1) {
                echo "<button class=\"";
                if ($table[$i]['ComboID'] == $ActiveComboID) {
                    if ($ActiveComboID == $ActiveNormalComboID && $ActiveComboType == 'override') {
                        echo "invert";
                    } else {
                        echo "active";
                    }
                }
                echo "button button12\" onclick=\"post(";
                if (($ActiveComboType == 'override') && (($table[$i]['ComboID'] == $ActiveComboID) || ($table[$i]['ComboID'] == $ActiveNormalComboID))) {
                    echo "0";
                } else {
                    echo $table[$i]['ComboID'];
                }
                echo ")\">" . $table[$i]['name'] . "</button>";
            }
        }
        ?>
    </div>
    <div>
        <?php
            for($i=0;$i<count($CurrentSensors);$i++) {
                echo "<button class=\"hbutton button12\" onclick=\"location.href='tempday.php?sensor=".
                    $CurrentSensors[$i]['SensorCode']."'\">".
                    $CurrentSensors[$i]['SensorName']."<BR>".
                    $CurrentSensors[$i]['CurrentTemp']."&#176C</button>";
            }
        ?>
    </div>
    <div class="fixed">
        <?php
            echo "<button class=\"buttonwide button12\" onclick=\"location.href='settings.php'\">CURRENT TIME<BR> ".date("g:i A")."</button>";
            if (is_null($nextEvent[0])) {
                echo "<button class=\"buttonwide button12\">NO EVENTS PENDING</button>";
            } else {
                echo "<button class=\"buttonwide button12\" onclick=\"location.href='schedulelist.php'\">".$nextEvent[1]." ".$nextEvent[2]."<BR>".date("g:i A",strtotime($nextEvent[0]))."</button>";
            }
        ?>
    </div>
 </body>
</html>
