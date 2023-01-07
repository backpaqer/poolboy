<?php
include("php/config.php");
include("php/postDeleteCombo.php");
include("php/postLoadSetting.php");
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
    <script src="js/postSettings.js"></script>
</head>
<body>
<video autoplay muted loop id="myVideo" class="backvideo">
    <source src="water2.mp4" type="video/mp4">
</video>
<div>
    <button class="poolbutton button12" onclick="location.href='index.php'">POOLBOY</button><BR>
    <?php   
    if ($RelayCount==0){    
        echo "COMBO EDIT SELECT<BR>";
    } else {
        echo "COMBO EDIT<BR>";
    }
    $deleteOverride = 0;
    for ($i=0;$i<$countArrayLength;$i++){
        $settingArray = $table[$i]['ComboID'].",0,0,0,0";
        echo "<button class=\"";
        if ($ComboID==$table[$i]['ComboID']) {
            echo "active";
            if ($table[$i]['override']==0) {
                $deleteOverride = $ComboID;
            }
        }
        echo "button button12\" onclick=\"post(".$settingArray.")\">" . $table[$i]['name'] . "</button>";
    }
    ?>
</div>
<div>
    <?php
    for ($i=0;$i<$RelayCount;$i++){
        $currentState = $relays[$i]['State'];
        $settingArray = $relays[$i]['ComboID'].",".$relays[$i]['RelayID'];
        if (strlen($currentState) == 0) {
            $curstate = 0;
        } elseif ($currentState == 0) {
            $curstate = 2;
        } else {
            $curstate = 1;
        }
        $settingArray = $settingArray.",".$curstate.",0,0";
        echo "<button class=\"relaybutton";
        if (strlen($currentState) == 0) {
            echo "UNUSED button12\" onclick=\"post(".$settingArray.")\">";
        } elseif ($currentState == 0) {
            echo "OFF button12\" onclick=\"post(".$settingArray.")\">";
        } else {
            echo "ON button12\" onclick=\"post(".$settingArray.")\">";
        }
        echo $relays[$i]['Name']."</button>";
    }
    if (($RelayCount > 0) and ($deleteOverride == 0)) {
        echo "<BR><BR><button class=\"button button12\" onclick=\"post(".$ComboID.",0,0,".$ComboID.",1)\">DELETE</button>";
    }
    ?>
</div>
</body>
</html>
