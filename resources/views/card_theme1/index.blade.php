@extends('layouts.layout_theme1')
@section('head')
    <style>
        .fadeInRight {
            -webkit-animation-name: fadeInRight;
            animation-name: fadeInRight;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                -webkit-transform: translate3d(100%, 0, 0);
                transform: translate3d(100%, 0, 0);
            }

            to {
                opacity: 1;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }
        }

        @-webkit-keyframes fadeInRight {
            from {
                opacity: 0;
                -webkit-transform: translate3d(100%, 0, 0);
                transform: translate3d(100%, 0, 0);
            }

            to {
                opacity: 1;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }
        }

        .modal-dialog {
            margin: 0;
        }

        .animated {
            -webkit-animation-duration: 0.5s;
            animation-duration: 0.5s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
        }

        .modal-header {
            border-radius: 0;
            background-color: #f7f7f7;
            border-color: #d4d2d2;
            align-items: center;
            padding: 1.2rem;
        }

        .modal-content {
            border-radius: 0;
            border: 0;
            -webkit-box-shadow: -12px 0 38px -14px rgba(0, 0, 0, 0.25);
            -moz-box-shadow: -12px 0 38px -14px rgba(0, 0, 0, 0.25);
            box-shadow: -12px 0 38px -14px rgba(0, 0, 0, 0.25);
            background-clip: padding-box;
        }

        .modal-backdrop {
            background-color: transparent;
        }

        .modal-button-container {
            margin: 0 auto;
            width: 90%;
            max-width: 500px;
        }

        .flyout {
            position: fixed;
            height: 100vh;
            width: 100%;
            background: #fff;
            top: 0;
            left: 0;
        }
    </style>
@endsection
@section('content')
    <div class="sdc-one">
        <div class="sdc-main">
            <div class="sdc-top-header">
                <x-carousel :images="$carouselImages" />
            </div>
            {{-- 'clientsReviewCount', 'clientReviewsAvg' --}}
            <!-- Header Section Start -->
            <x-header :user="$user" :clientsReviewCount="$clientsReviewCount" :clientReviewsAvg="$clientReviewsAvg" />
            <!-- Header Section End -->

            <!-- Social Info Start -->
            <x-social-info :user="$user" :otherLinkArr="$otherLinkArr" />
            <!-- Social Info End -->

            <!-- Our Product Start -->
            <div class="product-outer cont">
                <div class="product-inner">
                    <div class="product-header">
                        <div class="product-header-left">
                            <h3>Our Products</h3>
                        </div>
                        <div class="product-header-right">
                            <span><a href="{{ url('/') }}/products/{{ $slug }}">See All</a></span>
                        </div>
                    </div>

                    <div class="product-body">
                        @foreach ($services[1] as $product)
                            <div class="card get-product" type="button" data-toggle="modal" data-target="#exampleModalCenter" data-id="{{ $product['id'] }}">
                                <div class="card-img">
                                    <img src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/service/{{ $product['img_name'] }}" class="card-img-top" alt="image not found">
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $product['service_name'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Our Product End -->

            <!-- Our Services Start -->
            <div class="product-outer cont services-outer">
                <div class="product-inner">
                    <div class="product-header">
                        <div class="product-header-left">
                            <h3>Our Services</h3>
                        </div>
                        <div class="product-header-right">
                            <span><a href="{{ url('/') }}/services/{{ $slug }}"> See All</a></span>
                        </div>
                    </div>
                    <div class="product-body">
                        @foreach ($services[0] as $service)
                            <div class="card get-product" type="button" data-toggle="modal" data-target="#exampleModalCenter" data-id="{{ $service['id'] }}">
                                <img src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/service/{{ $service['img_name'] }}" class="card-img-top" alt="image not found">
                                <div class="card-body">
                                    <p class="card-text">{{ $service['service_name'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Our Services End -->

            <!-- Share Modal -->
            <x-share-modal :user="$user" />
            <!-- Share Modal End -->
            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-img modal-image-box">
                                <img id="img_name" src="" alt="">
                            </div>
                            <div class="cross-button">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i class="fa-sharp fa-solid fa-close"></i>
                                </button>
                            </div>
                            <div class="modal-title">
                                <h4 id="service_name"></h4>
                            </div>
                            <div class="modal-desc">
                                <h6 id="description"></h6>
                            </div>
                        </div>
                        <div class="modal-footer" id="enquiryLink">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info"><i class="fa-solid fa-circle-info"></i> Read
                                More</button>
                            <button type="button" class="btn btn-warning"><i class="fa-solid fa-cart-shopping"></i> Buy
                                Now</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal End -->
            <!-- Enquiry Modal -->
            <div class="modal fade" id="enquiryModal" tabindex="-1" role="dialog" aria-labelledby="enquiryModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form action="" method="post" id="sendEnquiryForm">
                            <input type="text" id="serviceName" hidden>
                            <input type="text" id="serviceids" hidden>
                            <div class="modal-header bg-info p-2 font-weight-bold" id="headerText">
                            </div>
                            <div class="modal-body p-2">
                                <div>
                                    <label for="Name">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div>
                                    <label for="Contact Number">Contact Number</label>
                                    <input type="text" name="contact" id="contact" class="form-control" required>
                                </div>
                            </div>
                            <div class="model-footer p-2">
                                <button type="submit" class="btn btn-sm btn-primary">Send Enquiry</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Enquiry Modal End-->

            <!-- Message Modal -->
            <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document" style="left: 9px;">
                    <div class="modal-content" id="showMessage">
                        
                    </div>
                </div>
            </div>
            <!-- Message Modal End-->

            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-img modal-image-box">
                                <img id="img_name" src="" alt="">
                            </div>
                            <div class="cross-button">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i class="fa-sharp fa-solid fa-close"></i>
                                </button>
                            </div>
                            <div class="modal-title">
                                <h4 id="service_name"></h4>
                            </div>
                            <div class="modal-desc">
                                <h6 id="description"></h6>
                            </div>
                        </div>
                        <div class="modal-footer" id="enquiryLink">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info"><i class="fa-solid fa-circle-info"></i> Read
                                More</button>
                            <button type="button" class="btn btn-warning"><i class="fa-solid fa-cart-shopping"></i> Buy
                                Now</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal End -->
            <!-- Enquiry Modal -->
            <div class="modal fade" id="enquiryModal" tabindex="-1" role="dialog" aria-labelledby="enquiryModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form action="" method="post" id="sendEnquiryForm">
                            <input type="text" id="serviceName" hidden>
                            <input type="text" id="serviceids" hidden>
                            <div class="modal-header bg-info p-2 font-weight-bold" id="headerText">
                            </div>
                            <div class="modal-body p-2">
                                <div>
                                    <label for="Name">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div>
                                    <label for="Contact Number">Contact Number</label>
                                    <input type="text" name="contact" id="contact" class="form-control" required>
                                </div>
                            </div>
                            <div class="model-footer p-2">
                                <button type="submit" class="btn btn-sm btn-primary">Send Enquiry</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Enquiry Modal End-->

            <!-- Message Modal -->
            <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document" style="left: 9px;">
                    <div class="modal-content" id="showMessage">
                        
                    </div>
                </div>
            </div>
            <!-- Message Modal End-->

            <x-footer :slug="$slug" :masterMenus="$masterMenus" :sectionName="$sectionName" :user="$user" />

        </div>
    </div>
@endsection
@section('footer')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.get-product').on('click', function() {
                $('#enquiryLink').html('');

                var productId = $(this).closest('.get-product').data('id');
                console.log(productId);
                // Make an Ajax request to retrieve product details based on productId
                $.ajax({
                    type: 'GET',
                    url: '/get-product-details/' + productId, // Adjust the route as needed
                    success: function(data) {
                        if (data.status) {
                            console.log(data.data);
                            let id = data.data.id;
                            let service_name = data.data.service_name;
                            let description = data.data.description;
                            let img_name = data.data.img_name;
                            let path = "{{ url('/') }}" + "/account/user/uploads/{{ $user->saved_email }}/service/";

                            let text = data.enquiryData.text;
                            let country_code = data.enquiryData.country_code;
                            let number = data.enquiryData.number;

                            let enquiryBtnLink = '<button type="button" class="btn btn-primary sendEnquiry" data-toggle="modal" data-target="#enquiryModal" data-id="' + id + '" data-name="' + service_name + '"><i class="fa-solid fa-paper-plane"></i> Send Enquiry</button>';
                            let whatsAppLink = '<a href="https://api.whatsapp.com/send?phone=' + country_code + '' + number + '&text=' + text + '"target="_blank" class="btn btn-success"><i class="fa-brands fa-whatsapp"></i>WhatsApp</a>';
                            $("#service_name").html(service_name);
                            $("#description").html(description);
                            // $("#service_name").html(service_name);
                            $('#img_name').prop('src', path + img_name);
                            $('#enquiryLink').append(enquiryBtnLink);
                            $('#enquiryLink').append(whatsAppLink);

                        } else {
                            alert(data.message);
                        }
                    }
                });
            });

        });
        // $(document).ready()
        $(document).on('click', '.sendEnquiry', function() {
            
            $("#exampleModalCenter").modal('hide');

            var serviceId = $(this).attr('data-id');
            var serviceName = $(this).attr('data-name');

            $("#headerText").text(serviceName);
            $("#serviceName").val(serviceName);
            $("#serviceids").val(serviceId);

        });


        $('#sendEnquiryForm').submit(function(e) {
            e.preventDefault();
            var name = $('#name').val();
            var contact = $('#contact').val();
            var serviceId = $('#serviceids').val();
            var serviceName = $('#serviceName').val();
            $.ajax({
                url: "/send-enquiry",
                type: "POST",
                data: {
                    name: name,
                    contact: contact,
                    service_id: serviceId,
                    Service_name: serviceName,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include the CSRF token
                },
                success: function(res) {
                    if (res.success) {
                        $("#enquiryModal").modal('hide');
                        let html = `<div class="alert alert-success message" style="margin-bottom: 0px;">`+res.message+`</div>`;
                        $("#showMessage").html(html);
                        $("#messageModal").modal('show');
                        setTimeout(function() {
                            $("#messageModal").modal('hide');
                        }, 5000);

                    } else {
                        alert(res.message)
                    }
                }
            })
        });
    </script>
    <script>
        document.getElementById('shareButton').addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                        title: 'Hi, Please click on below link to check digital card',
                        text: '',
                        url: window.location.href
                    })
                    .then(() => {
                        console.log('Successfully shared');
                    })
                    .catch((error) => {
                        console.error('Error sharing:', error);
                    });
            } else {
                console.log('Web Share API not supported on this browser');
            }
        });
    </script>
@endsection
