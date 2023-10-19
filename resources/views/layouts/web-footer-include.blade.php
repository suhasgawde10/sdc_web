<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>


<script src="{{ asset('dist/assets/js/jquery-3.2.0.min.js') }}"></script>
<script src="{{ asset('dist/assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('dist/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('dist/assets/js/jquery.slicknav.min.js') }}"></script>
<script src="{{ asset('dist/assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('dist/assets/js/counterup.js') }}"></script>
<script src="{{ asset('dist/assets/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('dist/assets/js/theme.js') }}"></script>
<script type="text/javascript">
    function isNumberKey(evt) {
        var k = evt.keyCode;
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode != 46 && (charCode < 48 || charCode > 57)))
            return false;
        return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || (k >= 48 && k <= 57));
    }
</script>

<script>
    $(document).ready(function () {
        // Activate Carousel
        $("#myCarousel").carousel();

        // Enable Carousel Indicators
        $(".item1").click(function () {
            $("#myCarousel").carousel(0);
        });
        $(".item2").click(function () {
            $("#myCarousel").carousel(1);
        });
        $(".item3").click(function () {
            $("#myCarousel").carousel(2);
        });
        // Enable Carousel Controls
        $(".carousel-control-prev").click(function () {
            $("#myCarousel").carousel("prev");
        });
        $(".carousel-control-next").click(function () {
            $("#myCarousel").carousel("next");
        });
    });
</script>
<script>
    $(function () {
        $("#subForm").ebcaptcha();
    });

    (function ($) {
        jQuery.fn.ebcaptcha = function (options) {
            var element = this;
            var input = this.find("#ebcaptchainput");
            var label = this.find(".ebcaptchatext");
            $(element).find("#request_send").attr("disabled", "disabled");

            var randomNr1 = 1;
            var randomNr2 = 1;
            var totalNr = 0;

            randomNr1 = Math.floor(Math.random() * 10);
            randomNr2 = Math.floor(Math.random() * 10);
            totalNr = randomNr1 + randomNr2;
            var texti = randomNr1 + " + " + randomNr2;
            $(label).text(texti);

            $(input).keyup(function () {
                var nr = $(this).val();
                if (nr == totalNr) {
                    $('#request_send').removeAttr('disabled');
                    $('#ebcaptchainput').css("border-bottom", "2px solid green");
                    $( ".line-remove" ).removeClass("form-line");
//                    $(element).find("#request_send").removeAttr("disabled");
                } else {
                    $('#ebcaptchainput').css("border-bottom", "2px solid red");
                    $('#request_send').attr("disabled", true);
                    $( ".line-remove" ).removeClass("form-line");
                }
            });

            $(document).keypress(function (e) {
                if (e.which == 13) {
                    if (element.find("#request_send").is(":disabled") == true) {
                        e.preventDefault();
                        return false;
                    }
                }
            });
        };
    })(jQuery);

</script>
<script>
    (function () {
        var calculateHeight;

        calculateHeight = function () {
            var $content, contentHeight, finalHeight, windowHeight;
            $content = $('#overlay-content');
            contentHeight = parseInt($content.height()) + parseInt($content.css('margin-top')) + parseInt($content.css('margin-bottom'));
            windowHeight = $(window).height();
            finalHeight = windowHeight > contentHeight ? windowHeight : contentHeight;
            return finalHeight;
        };

        $(document).ready(function () {
            $(window).resize(function () {
                if ($(window).height() < 560 && $(window).width() > 600) {
                    $('#overlay').addClass('short');
                } else {
                    $('#overlay').removeClass('short');
                }
                return $('#overlay-background').height(calculateHeight());
            });
            $(window).trigger('resize');

            // open
            $('#popup-trigger').click(function () {
                return $('#overlay').addClass('open').find('.signup-form input:first').select();
            });

            // close
            return $('#overlay-background,#overlay-close').click(function () {
                return $('#overlay').removeClass('open');
            });
        });

    }).call(this);

</script>

    <script type="text/javascript">

        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/5e83ee8135bcbb0c9aac6fd4/default';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>

