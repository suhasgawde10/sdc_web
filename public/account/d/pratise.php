<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <link href="assets/css/colorpicker.min.css" rel="stylesheet" type="text/css">
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css">

    <style>


    </style>
</head>
<body>
<!-- =========================
    SIDEBAR -> SETTINGS
============================== -->
<aside id="sidebar">
    <button id="nav-btn">
        <i class="fa-set fa-cogs"></i>
    </button>
    <h3>Settings</h3>
    <div id="sidecontent">
        <div class="pcolor">
            <p>Elements colour:</p>
            <input type="text" id="picker-elements-colour" class="picker" value="933132"></input>
        </div>
        <div class="pcolor">
            <p>Text colour:</p>
            <input type="text" id="picker-text-colour" class="textpicker picker" value="ffffff"></input>
        </div>
        <div class="pcolor">
            <p class="colorsch">Colour scheme:</p>
            <ul class="colorScheme">
                <li class="red"></li>
                <li class="blue"></li>
                <li class="green"></li>
                <li class="yellow"></li>
                <li class="purple"></li>
                <li class="orange"></li>
            </ul>
        </div>
    </div>
</aside>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo FULL_DESKTOP_URL; ?>assets/css/colorpicker.js" type="text/javascript"></script>

</body>
</html>