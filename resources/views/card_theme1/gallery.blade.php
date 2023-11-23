@extends('layouts.layout_theme1')
@section('head')
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://rawgit.com/LeshikJanz/libraries/master/Bootstrap/baguetteBox.min.css">
    <style>
        .container.gallery-container {
            background-color: #fff;
            color: #35373a;
            min-height: 100vh;
            padding: 30px 50px;
        }

        .gallery-container h1 {
            text-align: center;
            margin-top: 50px;
            font-family: "Droid Sans", sans-serif;
            font-weight: bold;
        }

        .gallery-container p.page-description {
            text-align: center;
            margin: 25px auto;
            font-size: 18px;
            color: #999;
        }

        .tz-gallery {
            padding: 0px 20px;
        }

        /* Override bootstrap column paddings */
        .tz-gallery .row>div {
            padding: 2px;
        }

        .tz-gallery .lightbox img {
            width: 100%;
            border-radius: 0;
            position: relative;
        }

        .tz-gallery .lightbox:before {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -13px;
            margin-left: -13px;
            opacity: 0;
            color: #fff;
            font-size: 26px;
            font-family: "Glyphicons Halflings";
            content: "\e003";
            pointer-events: none;
            z-index: 9000;
            transition: 0.4s;
        }



        .tz-gallery .lightbox:hover:after,
        .tz-gallery .lightbox:hover:before {
            opacity: 1;
        }

        .baguetteBox-button {
            background-color: transparent !important;
        }
    </style>
@endsection
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
                <div class="img-inner">
                    <div class="img-g">
                        <div class="img-header cont">
                            <div class="img-header-left active bor">
                                Images
                            </div>
                            <div class="img-header-right">
                                Videos
                            </div>
                        </div>
                    </div>
                    <div class="img-body com-area">
                        <div class="product1-body">
                            <div class="tz-gallery">
                                <div class="gallery-img">
                                    @foreach ($images as $image)
                                        <a class="lightbox" href="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/images/{{ $image['img_name'] }}">
                                            <div class="gallery-item">
                                                <img src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/images/{{ $image['img_name'] }}" alt="{{ $image['image_name'] }}">
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="product2-body cont pd2 d-none team">
                            <div class="gallery-video">

                                {{-- $video_link = str_replace("watch?v=","embed/",$form_data['video_link']); // &feature=youtu.be
                                                                                        $video_link = str_replace("&feature=youtu.be","",$video_link); // &feature=youtu.be --}}

                                @foreach ($videos as $video)
                                    @php
                                        $video_link = str_replace('watch?v=', 'embed/', $video['video_link']); // &feature=youtu.be
                                        $video_link = str_replace('&feature=youtu.be', '', $video_link); // &feature=youtu.be
                                    @endphp
                                    <div class="video-item">
                                       
                                        <iframe src=<?php echo $video_link; ?> frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                @endforeach
                                {{-- <div class="video-item">
                                    <video poster="{{ url('/') }}/dist/theme_assets/img/c1.jpg" data-video="https://clienti.dk/media/1140/friheden-video.mp4" data-toggle="modal" data-target="#videoModal1">
                                        Your browser does not support HTML video.
                                    </video>
                                </div>

                                <div class="video-item">
                                    <video poster="{{ url('/') }}/dist/theme_assets/img/c1.jpg" data-video="https://clienti.dk/media/1140/friheden-video.mp4" data-toggle="modal" data-target="#videoModal2">
                                        Your browser does not support HTML video.
                                    </video>
                                </div>

                                <div class="video-item">
                                    <video poster="{{ url('/') }}/dist/theme_assets/img/c1.jpg" data-video="https://clienti.dk/media/1140/friheden-video.mp4" data-toggle="modal" data-target="#videoModal3">
                                        Your browser does not support HTML video.
                                    </video>
                                </div>

                                <div class="video-item">
                                    <video poster="{{ url('/') }}/dist/theme_assets/img/c1.jpg" data-video="https://clienti.dk/media/1140/friheden-video.mp4" data-toggle="modal" data-target="#videoModal4">
                                        Your browser does not support HTML video.
                                    </video>
                                </div>

                                <div class="video-item">
                                    <video poster="{{ url('/') }}/dist/theme_assets/img/c1.jpg" data-video="https://clienti.dk/media/1140/friheden-video.mp4" data-toggle="modal" data-target="#videoModal5">
                                        Your browser does not support HTML video.
                                    </video>
                                </div>

                                <div class="video-item">
                                    <video poster="{{ url('/') }}/dist/theme_assets/img/c1.jpg" data-video="https://clienti.dk/media/1140/friheden-video.mp4" data-toggle="modal" data-target="#videoModal6">
                                        Your browser does not support HTML video.
                                    </video>
                                </div>

                                <div class="video-item">
                                    <video poster="{{ url('/') }}/dist/theme_assets/img/c1.jpg" data-video="https://clienti.dk/media/1140/friheden-video.mp4" data-toggle="modal" data-target="#videoModal">
                                        Your browser does not support HTML video.
                                    </video>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Video Modal Start -->
            <div class="modal fade" id="videoModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog new-modal">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <video controls>
                                <source src="videos/mov_bbb.mp4" type="video/mp4">
                                <source src="videos/mov_bbb.mp4" type="video/ogg">
                            </video>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="videoModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog new-modal">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <video controls>
                                <source src="videos/flower.webm" type="video/mp4">
                                <source src="videos/flower.webm" type="video/ogg">
                            </video>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="videoModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog new-modal">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <video controls>
                                <source src="videos/mov_bbb.mp4" type="video/mp4">
                                <source src="videos/mov_bbb.mp4" type="video/ogg">
                            </video>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="videoModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog new-modal">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <video controls>
                                <source src="videos/mov_bbb.mp4" type="video/mp4">
                                <source src="videos/mov_bbb.mp4" type="video/ogg">
                            </video>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="videoModal5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog new-modal">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <video controls>
                                <source src="videos/mov_bbb.mp4" type="video/mp4">
                                <source src="videos/mov_bbb.mp4" type="video/ogg">
                            </video>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="videoModal6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog new-modal">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <video controls>
                                <source src="videos/mov_bbb.mp4" type="video/mp4">
                                <source src="videos/mov_bbb.mp4" type="video/ogg">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog new-modal">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <video controls>
                                <source src="videos/mov_bbb.mp4" type="video/mp4">
                                <source src="videos/mov_bbb.mp4" type="video/ogg">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Video Modal End -->

            <!-- Footer Start -->
            <x-footer :slug="$slug" :masterMenus="$masterMenus" :sectionName="$sectionName" :user="$user" />
            <!-- Footer End -->

        </div>
    </div>
@endsection
@section('footer')
    <script>
        $(function() {
            $(".video").click(function() {
                var theModal = $(this).data("target"),
                    videoSRC = $(this).attr("data-video"),
                    videoSRCauto = videoSRC + "";
                $(theModal + ' source').attr('src', videoSRCauto);
                $(theModal + ' button.close').click(function() {
                    $(theModal + ' source').attr('src', videoSRC);
                });
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.js"></script>
    <script>
        baguetteBox.run('.tz-gallery');
    </script>
@endsection
