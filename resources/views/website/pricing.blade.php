@extends('layouts.web-main')
@section('main-container')
    <div class="innerpage-banner " id="home" style="background: url({{ url('/') }}/dist/assets/img/bread/breadcrumbs%20price.jpg) no-repeat center;background-size: cover;">
        <div class="inner-page-layer">
            <h5>Pricing</h5>
            <h6><a href="index.php">Home</a>&nbsp;/&nbsp;<span>Pricing</span></h6>
        </div>
    </div>

    <section class="ptb--30" style="background: #f5f5f5">
        <!-- pricing area start -->
        @include('website.pricing-include')
        <!-- pricing area end -->
    </section>
@endsection
