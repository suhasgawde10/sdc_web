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
                            About
                        </div>
                        <div class="img-header-right">
                            Our Teams
                        </div>
                    </div>
                    <div class="img-body com-area">
                        <div class="product1-body cont">
                            <div class="cmp-about-short w-100">
                                <div class="bg-info card-header">Company Information</div>
                                <table class="table table-active table-primary caption-top">
                                    
                                    <tr>
                                        <th>Name</th><td>{{$user->company_name}}</td>
                                    </tr>
                                    <tr>
                                        <th>GSTN No</th><td>{{$user->gst_no}}</td>
                                    </tr>
                                    <tr>
                                        <th>PAN No</th><td>{{$user->pan_no}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="cmp-about">
                                {!! $user->about_company !!}
                            </div>
                            <div class="com-mission">
                                <div class="mission-head">
                                    <h4>Our Mission</h4>
                                </div>
                                {!! $user->our_mission !!}
                            </div>
                            <div class="com-profile mt-20">

                                <a href="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/profile/{{ $user->company_profile }}" target="_blank" class="btn btn-info">Profile</a>
                            </div>
                        </div>

                        <div class="product2-body cont pd2 d-none team">

                            @foreach ($teams as $team)
                                <div class="card">
                                    <div class="team-img">
                                        <img class="card-img-top" src="{{ url('/') }}/account/user/uploads/{{$user->saved_email}}/our-team/{{$team['img_name']}}" alt="Card image cap">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{$team['name']}}</h5>
                                        <p class="card-text">{{$team['designation']}}</p>
                                        <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                                        <div class="t-icon">
                                            @if ($team['c_number'])
                                                <a href="tel:+{{$team['c_number']}}"><i class="fa-solid fa-phone"></i></a>
                                            @endif
                                            @if ($team['w_number'])
                                            <a href="https://wa.me/{{$team['w_number']}}"><i class="fa-brands fa-whatsapp"></i></a>
                                            @endif
                                            @if ($team['dg_link'])
                                            <a href="{{$team['dg_link']}}"><i class="fa fa-external-link"></i></a>
                                            @endif
                                            {{-- <a href="#"><i class="fa-brands fa-instagram"></i></a> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- <div class="card">
                                <div class="team-img">
                                    <img class="card-img-top" src="{{ url('/') }}/dist/theme_assets/img/c1.jpg" alt="Card image cap">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Ajay Chorge</h5>
                                    <p class="card-text">Software Developer</p>
                                    <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                                    <div class="t-icon">
                                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="team-img">
                                    <img class="card-img-top" src="{{ url('/') }}/dist/theme_assets/img/c1.jpg" alt="Card image cap">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Suhas Gawde</h5>
                                    <p class="card-text">Software Developer</p>
                                    <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                                    <div class="t-icon">
                                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="team-img">
                                    <img class="card-img-top" src="{{ url('/') }}/dist/theme_assets/img/c1.jpg" alt="Card image cap">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Akhilesh</h5>
                                    <p class="card-text">Web Developer</p>
                                    <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                                    <div class="t-icon">
                                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="team-img">
                                    <img class="card-img-top" src="{{ url('/') }}/dist/theme_assets/img/c1.jpg" alt="Card image cap">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Sadanand</h5>
                                    <p class="card-text">Tester </p>
                                    <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                                    <div class="t-icon">
                                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                                    </div>
                                </div>
                            </div> --}}


                        </div>
                    </div>
                </div>
            </div>



            <!-- Footer Start -->
            <x-footer :slug="$slug" :masterMenus="$masterMenus" :sectionName="$sectionName" :user="$user" />
            <!-- Footer End -->

        </div>
    </div>
@endsection
