<?php
/**
 * Created by PhpStorm.
 * User: desktop
 * Date: 13-Jul-19
 * Time: 08:45 PM
 */
?>
<!--<script type="text/javascript" src="../../assets/js/detect-browser.js"></script>
<script type="application/javascript">
    $(document).ready(function () {
        var isMobile = {
            Android: function () {
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function () {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function () {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function () {
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function () {
                return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
            },
            any: function () {
                return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
            }
        };

        if (isMobile.any()) {
            location.replace("../../m/index.php?custom_url=<?php /*if(isset($_GET['custom_url'])) echo $_GET['custom_url'];*/?>");
        }
        /*else {
         location.replace("../../m/index.php?custom_url=kubictechnology123");
         }*/
    });
</script>-->
<script>
    var something = (function () {
        var executed = false;
        return function () {
            if (!executed) {
                executed = true;
                if (screen.width == 1024 || screen.height == 768) { //if 1024x768
                    window.location.replace("../../m/index.php?custom_url=<?php if(isset($_GET['custom_url'])) echo $_GET['custom_url'];?>")
                } else { //if all else
                    window.location.replace("../../m/index.php?custom_url=<?php if(isset($_GET['custom_url'])) echo $_GET['custom_url'];?>")

                }
            }
        };
    })();
</script>


