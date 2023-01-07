<?php
include("php/config.php");
include("php/getRelays.php");
include("php/postRelays.php");
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
    <script src="js/postRelays.js"></script>
</head>
<body>
<video autoplay muted loop id="myVideo" class="backvideo">
    <source src="water2.mp4" type="video/mp4">
</video>
<div>
    <button class="poolbutton button12" onclick="location.href='index.php'">POOLBOY</button><BR>
    SINGLE RELAY OVERRIDE<BR>
    <?php   /* post(RelayID, RelayState, RelayDuration) */
    for ($i=0;$i<$countArrayLength;$i++){
        echo "<button class=\"";
        if ($table[$i]['status']==1) {
            echo "activebutton button12\" onclick=\"post(".$table[$i]['RelayID'].",0)\">" . $table[$i]['desc'] . "</button>";
        } else {
            echo "button button12\" onclick=\"post(".$table[$i]['RelayID'].",1)\">" . $table[$i]['desc'] . "</button>";
        }
    }
    ?>
</div>
<div>
    <button class="button button12" onclick="location.href='settingrelaynames.php'">EDIT NAMES</button><BR>
</div>
</body>
</html>
