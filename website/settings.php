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
</head>
<body>
<video autoplay muted loop id="myVideo" class="backvideo">
    <source src="water2.mp4" type="video/mp4">
</video>
<div>
    <button class="poolbutton button12" onclick="location.href='index.php'">POOLBOY</button><BR>
    CONFIGURATION OPTIONS<BR>
    <button class="buttonwide button12" onclick="location.href='settingcombos.php'">COMBOS</button><BR>
    <button class="buttonwide button12" onclick="location.href='settingcombosnew.php'">NEW COMBO</button><BR>
    <button class="buttonwide button12" onclick="location.href='settingrelays.php'">RELAYS</button>
</div>
</body>
</html>
