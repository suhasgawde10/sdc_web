@extends('layouts.web-main')
@section('main-container')
    <div class="innerpage-banner" id="home" style="background: url({{url('/')}}/dist/assets/img/bread/breadcrumbs4.jpg) no-repeat center;background-size: cover;">
        <div class="inner-page-layer">
            <h5>Themes</h5>
            <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>Themes</span></h6>
        </div>
    </div>


    <div class="container process_padding screen-area ptb--30">
        <div class="section-title">
        </div>
        @include('website.screenshot-include')

    </div>
@endsection
@section('custom-js')
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
<script>
    $(document).ready(function () {
        var slider = $('.slider').bxSlider({
            mode: 'horizontal', //mode: 'fade',
            speed: 300,
            //不要自動
            auto: false,
            /*autoControls: true,
            stopAutoOnClick: true,
            pager: true,
            infiniteLoop: true,*/
            hideControlOnEnd: true,
            useCSS: false,
            onSliderLoad: function (currentIndex) {
                //初始是第一張
                $('.carousel-indicators a').filter('[data-picindex="' + (currentIndex + 1) + '"]').addClass('active').siblings().removeClass('active');
                $('.carousel-indicators2 a').filter('[data-picindex="' + (currentIndex + 1) + '"]').addClass('active').siblings().removeClass('active');

            },
            onSlideAfter: function ($slideElement, oldIndex, newIndex) {
                //切換後
                $('.carousel-indicators a').filter('[data-picindex="' + (newIndex + 1) + '"]').addClass('active').siblings().removeClass('active');
                $('.carousel-indicators2 a').filter('[data-picindex="' + (newIndex + 1) + '"]').addClass('active').siblings().removeClass('active');

            }
        });

        $('.carousel-indicators a').click(function (event) {
            event.preventDefault();
            var $this = $(this);
            slider.goToSlide($this.data('picindex') - 1);
            $('.carousel-indicators2 a').removeClass('active');
        });
        $('.carousel-indicators2 a').click(function (event) {
            event.preventDefault();
            var $this = $(this);
            slider.goToSlide($this.data('picindex') - 1);
            $('.carousel-indicators a').removeClass('active');
        });


    });
</script>

@endsection
