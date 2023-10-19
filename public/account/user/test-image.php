<?php
/**
 * Created by PhpStorm.
 * User: intel i3
 * Date: 11/04/2019
 * Time: 2:17 PM
 */

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        .CoverModal {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: transparent;
            visibility: hidden;
        }

        .CoverModal .content {
            position: absolute;
            left: 50%;
            top: 30%;
            width: 50%;
            padding: 50px;
            border-radius: 3px;
            transform: translate(-50%, -30%) scale(0);
        }
        .content .toshow{
            background-repeat:no-repeat;
            width:100%;
            height:100px;
            background-position:center;
            background-size:contain;
        }

        .CoverModal .close {
            position: absolute;
            top: 8px;
            right: 8px;
            display: block;
            width: 18px;
            height: 18px;
            padding: 5px;
            line-height: 18px;
            border-radius: 50%;
            text-align: center;
            cursor: pointer;
            background: #2ecc71;
            color: #fff;
        }

        .CoverModal .close:before { content: '\2715'; }

        .CoverModal.is-visible {
            visibility: visible;
            background: rgba(0, 0, 0, 0.5);
            -webkit-transition: background .35s;
            -moz-transition: background .35s;
            transition: background .35s;
            -webkit-transition-delay: .1s;
            -moz-transition-delay: .1s;
            transition-delay: .1s;
        }

        .CoverModal.is-visible .content {
            -webkit-transform: translate(-50%, -30%) scale(1);
            -moz-transform: translate(-50%, -30%) scale(1);
            transform: translate(-50%, -30%) scale(1);
            -webkit-transform: transition: transform .35s;
            -moz-transform: transition: transform .35s;
            transition: transform .35s;
        }
        input[type="file"]{
            display: block;
        }
    </style>
</head>
<body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<input type="file" id="cover_image" value="Choose a file">

<a href="#Popup" class="button">Open Popup</a>
<!--popup content here-->

<div id="Popup" class="CoverModal">
    <div class="content">
        <div class="toshow"></div>
        <a href="#" class="cancel">Cancel</a> <span class="close"></span></div>
</div>
<script>
    $.fn.expose = function(options) {

        var $modal = $(this),
            $trigger = $("a[href=" + this.selector + "]");

        $modal.on("expose:open", function() {

            $modal.addClass("is-visible");
            $modal.trigger("expose:opened");
        });

        $modal.on("expose:close", function() {

            $modal.removeClass("is-visible");
            $modal.trigger("expose:closed");
        });

        $trigger.on("click", function(e) {

            e.preventDefault();
            $modal.trigger("expose:open");
        });

        $modal.add( $modal.find(".close") ).on("click", function(e) {

            e.preventDefault();

            // if it isn't the background or close button, bail
            if( e.target !== this )
                return;

            $modal.trigger("expose:close");
        });

        return;
    }

    $("#Popup").expose();

    // Example Cancel Button

    $(".cancel").on("click", function(e) {

        e.preventDefault();
        $(this).trigger("expose:close");
    });

    $("#cover_image").on("change", function(event1) {
        src1 = URL.createObjectURL(event1.target.files[0]);
        $(".toshow").css('background-image','none');
        $(".toshow").css('background-image','url(' + src1 + ')');
        $(".CoverModal").trigger("expose:open");
    });
</script>
</body>
</html>