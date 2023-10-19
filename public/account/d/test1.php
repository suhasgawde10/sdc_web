<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>here</title>
    <style>
        .caption {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            padding: 0 10px;
            box-sizing: border-box;
            pointer-events: none;
        }
        .caption span {
            display: inline-block;
            padding: 10px;
            color: #fff;
            background: rgba(0,0,0,0.5);
            font-family: 'Myriad Pro regular';
            font-size: 15.31px;
        }
        .image_grid {
            display: inline-block;
            padding-left: 25px;
        }
        .image_grid label {
            position: relative;
            display: inline-block;
        }
        .image_grid img {
            display: block;
        }
        .image_grid input {
            display: none;
        }

        .image_grid input:checked + .caption {
            background: rgba(0,0,0,0.5);
        }
        .image_grid input:checked + .caption::after {
            content: '✔';
            position: absolute;
            top: 50%; left: 50%;
            width: 70px; height: 70px;
            transform: translate(-50%,-50%);
            color: white;
            font-size: 60px;
            line-height: 80px;
            text-align: center;
            border: 2px solid white;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<div class="grid-two imageandtext">

    <div class="imageandtext image_grid">
        <label>
            <img src="http://yaitisme.com/images/getImage.jpg" class="img-thumbnail">
            <input type="radio" name="selimg">
            <span class="caption">
        <span>Painting</span>
      </span>
        </label>
    </div>

    <div class="imageandtext image_grid">
        <label>
            <img src="http://yaitisme.com/images/getImage.jpg" class="img-thumbnail">
            <input type="radio" name="selimg">
            <span class="caption">
        <span>Painting</span>
      </span>
        </label>
    </div>

</div>

</body>
</html>