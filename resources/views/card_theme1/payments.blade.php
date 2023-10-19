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

                    <div class="img-body com-area">
                        <div class="p-title">Pay Using</div>
                        <div class="p-card d-flex">
                            <div class="p-card-left">
                                <i class="fa-sharp fa-solid fa-building-columns"></i><a href="#">HDFC Bank</a>
                            </div>
                            <div class="p-card-right">
                                <a href="#" type="button" data-toggle="modal" data-target="#exampleModalCenter">Account Details</a>
                            </div>
                        </div>
                        @if ($upiDetails)
                            @php
                                if ($upiDetails) {
                                    $upi_qr_link = 'upi://pay?cu=INR%26pa=' . $upiDetails->upi_id . '%26pn=' . $user->name;
                                    $upi_qr_link = str_replace(' ', '%20', $upi_qr_link);
                                } else {
                                    $upi_qr_link = 'upi@upi';
                                }
                            @endphp
                            <div class="p-card">
                                <div class="p-card1 d-flex">
                                    <div class="barcode">
                                        <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $upi_qr_link; ?>&choe=UTF-8" style="width: 70%" title="Bank Details" />
                                    </div>
                                    <div class="upi-id">
                                        <input type="text" value="{{$upiDetails->upi_id}}" id="copyUpi" hidden>
                                        <button class="btn btn-warning" id="copyButton">Copy UPI ID</button>
                                    </div>
                                </div>
                                <div class="p-card-2 d-flex">
                                    <div class="p-item">
                                        <img src="{{ url('/') }}/dist/theme_assets/img/gp.png" alt="">
                                    </div>
                                    <div class="p-item">
                                        <img src="{{ url('/') }}/dist/theme_assets/img/pp.png" alt="">
                                    </div>
                                    <div class="p-item">
                                        <img src="{{ url('/') }}/dist/theme_assets/img/pay.png" alt="">
                                    </div>
                                </div>
                                <div class="p-desc">
                                    <h4>Configure Your Wallet</h4>
                                    <p>Please Check For Your UPI Details And Fill This form</p>
                                </div>
                            </div>
                        @endif
                        @if ($paypalDetails)
                            <div class="p-card">
                                <div class="p-card1 d-flex">
                                    <div class="barcode">
                                        @php
                                            if ($paypalDetails->paypal_email) {
                                                $paypal_qr_code_link = 'https://www.paypal.com/cgi-bin/webscr?business=' . $paypalDetails->paypal_email . '%26cmd=_xclick%26currency_code=USD%26amount=%26item_name=';
                                            } else {
                                                $paypal_qr_code_link = 'https://www.paypal.com/';
                                            }
                                        @endphp

                                        <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={{ $bank_details_content }}&choe=UTF-8" style="" title="Bank Details" />
                                        {{-- <img src="{{url('/')}}/dist/theme_assets/img/barcode.png" alt=""> --}}
                                    </div>
                                    <div class="upi-id">
                                        {{-- <a href="#" type="button" data-toggle="modal" data-target="#exampleModalCenter">Account Details</a> --}}
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#payPalModal">PayPal</button>
                                    </div>
                                </div>
                                <div class="p-card-2 d-flex">
                                    <div class="p-item">
                                        <img src="{{ url('/') }}/dist/theme_assets/img/paypal.png" alt="">
                                    </div>

                                </div>
                                <div class="p-desc">
                                    <h4>Configure Your PayPal Account</h4>
                                    <p>Registered Email Id : {{ $paypalDetails->paypal_email }}</p>
                                    <p>Payme Link : <a href="{{ $paypalDetails->paypal_link }}">{{ $paypalDetails->paypal_link }}</a></p>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="modal-img barcode m-auto modal-image-box">
                                <img src="{{ url('/') }}/dist/theme_assets/img/barcode.png" alt="">
                            </div>
                            <div class="cross-button">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i class="fa-sharp fa-solid fa-close"></i>
                                </button>
                            </div>
                            <div class="modal-title">
                                <h4>Scan To Pay</h4>
                            </div>
                            <!-- <div class="p-card"> -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col"><i class="fa fa-user"></i></th>
                                        <th scope="col">Name</th>
                                        <th scope="col">{{ $bankDetail['name'] }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row"><i class="fa-solid fa-indian-rupee-sign"></i></th>
                                        <td>Bank Name</td>
                                        <td>{{ $bankDetail['bank_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><i class="fa fa-info"></i></th>
                                        <td>Account Number</td>
                                        <td>{{ $bankDetail['account_number'] }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><i class="fa fa-info"></i></th>
                                        <td>IFSC Code</td>
                                        <td>{{ $bankDetail['ifsc_code'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- </div> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="copy-button" data-text="{{ $bank_details_content }}" class="btn btn-success">Copy Bank Details</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paypal Modal -->
            <div class="modal fade" id="payPalModal" tabindex="-1" role="dialog" aria-labelledby="payPalModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document1">
                    <div class="modal-content" style="width: 92%;">
                        <form action="" method="post" id="payPal">
                            <input type="text" name="payPalEmail" id="payPalEmail" value="{{ $paypalDetails->paypal_email }}" hidden>
                            <div class="modal-header bg-info p-2 font-weight-bold" id="headerText">
                                Pay Using PayPal
                            </div>
                            <div class="modal-body p-2">
                                <div>
                                    <label for="Amount">Amount In Dollar <span class="text-danger">*</span></label>
                                    <input type="text" name="amount" id="payPalAmt" class="form-control">
                                </div>
                                <div>
                                    <label for="Reamrk">Remark (Optional)</label>
                                    <textarea name="reamark" id="payPalRemark" class="form-control"></textarea>
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

            <!-- Footer Start -->
            <x-footer :slug="$slug" />
            <!-- Footer End -->



        </div>
    </div>
@endsection
@section('footer')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    <script>
        document.getElementById("copy-button").addEventListener("click", function() {
            // Get the data-text attribute
            const textToCopy = this.getAttribute("data-text");

            // Create a new text area element
            const textarea = document.createElement("textarea");
            textarea.value = textToCopy;

            // Append the textarea to the document
            document.body.appendChild(textarea);

            // Select the text in the textarea
            textarea.select();

            try {
                // Attempt to copy the selected text to the clipboard
                document.execCommand("copy");
                this.innerText = "Bank Details Copied!";
            } catch (err) {
                console.error("Unable to copy text: ", err);
            }

            // Remove the textarea from the document
            document.body.removeChild(textarea);

            // Provide user feedback
            setTimeout(() => {
                this.innerText = "Copy Bank Details";
            }, 2000);
        });


        $('#payPal').submit(function(e) {
            e.preventDefault();

            var serviceId = $('#serviceId').val();

            var desc = $("#payPalRemark").val();
            var amount = $("#payPalAmt").val();
            var paypal_email = $("#payPalEmail").val();

            if (amount == '') {
                $("#amountReguired1").text('Please enter amount');
            } else {
                window.open('https://www.paypal.com/cgi-bin/webscr?business=' + paypal_email + '&cmd=_xclick&currency_code=USD&amount=' + amount + '&item_name=' + encodeURIComponent(desc) + '', '_blank', '');
            }
        });

        $(document).ready(function () {
            $("#copyButton").click(function () {
                // Select the text in the input element
                $("#copyUpi")[0].select();

                // Copy the selected text to the clipboard
                document.execCommand("copy");

                // Provide some feedback to the user (optional)
                // alert("Text copied to clipboard: " + $("#copyUpi")[0].value);
            });
        });

    </script>
@endsection
