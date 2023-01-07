<?php
include("php/config.php");
include("php/getRelays.php");
include("php/postRelayNames.php");
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
    EDIT RELAY NAMES<BR>
    <form method="POST">
    <?php   /* post(RelayID, RelayState) */
    for ($i=0;$i<$countArrayLength;$i++){
        echo "<input type=\"text\" size=4 name=\"relayName".$i."\" value=\"".$table[$i]['desc']."\">";
    }
    ?>
        <BR>
        <button class="button button12" onclick="window.location='settingrelays.php';return false;">CANCEL</button><button class="button button12" type="submit">SUBMIT</button>
    </form>
    <BR>
</div>
</body>
</html>
