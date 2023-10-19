@extends('layouts.layout_theme1')
@section('content')
    <div class="sdc-one">
        <div class="sdc-main">
            <div class="sdc-top-header">
                <x-carousel :images="$carouselImages" />
            </div>

            <!-- Header Section Start -->
            <x-header :user="$user" :clientsReviewCount="$clientsReviewCount" :clientReviewsAvg="$clientReviewsAvg" />
            <!-- Header Section End -->

            <!-- Social Info Start -->
            <x-social-info :user="$user" :otherLinkArr="$otherLinkArr" />
            <!-- Social Info End -->


            <!-- Images Section Start -->
            <div class="img-outer">
                <div class="img-inner cont">
                    <div class="img-header">
                        <div class="img-header-left active bor">
                            Clients
                        </div>
                        <div class="img-header-right">
                            Reviews
                        </div>
                    </div>
                    <div class="img-body com-area">
                        <div class="client-body cont">

                            <div class="client">
                                @foreach ($clients as $client)
                                    <div class="client-item">
                                        <div class="client-item-inner">
                                            <img src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/testimonials/clients/{{ $client['img_name'] }}" alt="">
                                        </div>
                                        <div class="client-name">
                                            <h4>{{ $client['name'] }}</h4>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="product2-body cont pd2 review-count d-none client1">
                            @foreach ($clientReviews as $clientReview)
                                <div class="verified_customer_section">
                                    <div class="image_review">
                                        <div class="">
                                            @if ($clientReview['img_name'])
                                                <img src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/testimonials/client_review/{{ $clientReview['img_name'] }}" alt="customer image" class="img-40 img-circle img-height-40">
                                            @else
                                                <img src="{{ asset('dist/theme_assets/img/user-placeholder.png') }}" alt="customer image" class="img-40 img-circle img-height-40">
                                            @endif
                                        </div>

                                        <div class="customer_name_review_status">
                                            <div class="customer_name">{{ $clientReview['name'] }}</div>
                                            <div class="customer_status_content">
                                                {{ \Carbon\Carbon::parse($clientReview['created_date'])->diffForHumans() }}</p>
                                            </div>
                                            {!! renderStarRating($clientReview['rating_number'], 5) !!}

                                        </div>
                                    </div>
                                    <div class="customer_comment">
                                        {{ $clientReview['description'] }}
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>



            <!-- Footer Start -->
            <x-footer :slug="$slug" />
            <!-- Footer End -->


        </div>
    </div>
@endsection
@section('footer')
    <script>
        document.getElementsByTagName("h1")[0].style.fontSize = "6vw";
    </script>
@endsection
