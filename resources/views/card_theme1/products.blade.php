@extends('layouts.layout_theme1')
@section('content')
    <div class="sdc-one">
        <div class="sdc-main">
            <!-- Our product1 Start -->
            <div class="product1-outer">
                <div class="product1-inner">
                    <div class="product1-header">
                        <div class="product1-header-left">
                            <a href="{{ url('/') }}/home/{{ $slug }}"><i class="fa-sharp fa-solid fa-angle-left"></i></a>
                        </div>
                        <div class="product1-header-right">
                            <h2>Products</h2>
                        </div>
                        <div class="product1-header-right">

                        </div>
                    </div>
                    <div class="product1-body prduct-count">
                        @foreach ($services[1] as $product)
                            <div class="card get-product" type="button" data-toggle="modal" data-target="#exampleModalCenter" onclick="setData({{ $product['id'] }})" data-id="{{ $product['id'] }}">
                                <div class="card-img">
                                    <img src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/service/{{ $product['img_name'] }}" class="card-img-top" alt="image not found">
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $product['service_name'] }}</p>
                                </div>
                            </div>
                        @endforeach

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
                        <!-- Footer Start -->
                        <x-footer :slug="$slug" :masterMenus="$masterMenus" :sectionName="$sectionName" :user="$user" />
                        <!-- Footer End -->
                    </div>
                </div>
            </div>
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
@endsection
