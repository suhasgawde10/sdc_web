<script type="text/javascript">
    $('.list-group-item').on('click', function () {
        return false;
    });
    $(document).bind("contextmenu", function (e) {
        e.preventDefault();
    });
    $(document).keydown(function (e) {
        if (e.which === 123) {
            return false;
        }
    });
</script>
<!--<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>-->

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

<!--<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<?php
include "share-card-flyout.php";

?>


<script>


    $(document).ready(function () {
        $("div.bhoechie-tab-menu>div.list-group>a").click(function (e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
            $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
        });
    });
</script>
<script>
    $(window).load(function () {
        setTimeout(function(){
            $('.spinner').fadeOut();
            $('.back-color').fadeOut();
            $('.path').fadeOut();
        }, 20);
    });
</script>

<script>
    function setClipboard(value,text) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        if ("copy") {
            var x = document.getElementById("snackbar");
            x.innerHTML = text;
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
    }
</script>
<script>
    $(document).ready(function () {
        $("#testimonial-slider").owlCarousel({
            items: 1,
            itemsDesktop: [1199, 2],
            itemsDesktopSmall: [979, 2],
            itemsTablet: [767, 1],
            pagination: true,
            autoPlay: true
        });
    });
</script>
<script>

    var modal = document.getElementById('myModal2');
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById('myImg2');
    var modalImg = document.getElementById("img02");
    var captionText = document.getElementById("caption");
    if(img!=null){
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
            modalImg.alt = this.alt;
            // captionText.innerHTML = this.alt;
        }
    }
    // When the user clicks on <span> (x), close the modal
    modal.onclick = function () {
        img02.className += " out";
        setTimeout(function () {
            modal.style.display = "none";
            img02.className = "modal-content";
        }, 400);

    }

</script>
<script>

    // Get the modal
    var modal = document.getElementById('myModal1');

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById('myImg');
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    img.onclick = function(){
        modal.style.display = "block";
        modalImg.src = this.src;
        modalImg.alt = this.alt;
        //   captionText.innerHTML = this.alt;
    }


    // When the user clicks on <span> (x), close the modal
    modal.onclick = function() {
        img01.className += " out";
        setTimeout(function() {
            modal.style.display = "none";
            img01.className = "modal-content";
        }, 400);

    }


</script>
<script>
    jQuery("#carousel").owlCarousel({
        autoplay: true,
        lazyLoad: true,
        loop: true,
        items:1,
        margin: 20,
        /*
       animateOut: 'fadeOut',
       animateIn: 'fadeIn',
       */
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        nav: true,
        responsive: {
            0: {
                items: 1
            },

            600: {
                items: 1
            },

            1024: {
                items: 1
            },

            1366: {
                items: 1
            }
        }
    });
</script>