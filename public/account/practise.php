<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>jQuery ChangeBackground Plugin Demo</title>
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>

<body>
<div class="container" style="margin:150px auto; max-width:728px;">
    <h1>jQuery ChangeBackground Plugin Demo</h1>
    <div class="jquery-script-ads"><script type="text/javascript"><!--
            google_ad_client = "ca-pub-2783044520727903";
                /* jQuery_demo */
            google_ad_slot = "2780937993";
            google_ad_width = 728;
            google_ad_height = 90;
                //-->
        </script>
        <script type="text/javascript"
                src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script></div>
    <select id="change-background" class="form-control">
        <option value="Background-1" data-image="https://unsplash.it/1800/1200?image=655">Background-1</option>
        <option value="Background-2" data-image="https://unsplash.it/1800/1200?image=653">Background-2</option>
        <option value="Background-3" data-image="https://unsplash.it/1800/1200?image=298">Background-3</option>
        <option value="Background-4" data-image="https://unsplash.it/1800/1200?image=651">Background-4</option>
    </select>
</div>
<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>

<script src="jquery.changebackground.js"></script>
<script>
    $('#change-background').changeBackground();
</script>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>


</body>
</html>
