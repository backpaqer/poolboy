<?php
include("php/config.php");
include("php/postAddCombo.php");
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
    CREATE NEW COMBO BUTTON<BR>
    <form method="POST">
        NAME: <input type="text" size=10 name="comboName"><br>
        DESC: <input type="text" size=50 name="comboDesc"><br>        
        <BR>
        <button class="button button12" onclick="window.location='settings.php';return false;">CANCEL</button><button class="button button12" type="submit">ADD</button>
    </form>
    <BR>
</div>
</body>
</html>
