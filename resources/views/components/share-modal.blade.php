<div>

    <!-- Modal -->
    <div class="modal" id="flyoutmodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog fadeInRight animated ml-auto" role="document">
            <div class="modal-content flyout">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Share Digital Card</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                @php
                    $final_link = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                @endphp
                <div class="modal-body">
                    <div class="d-flex justify-content-around row">
                        <div class="text-center" style="display: grid;">
                            <a href="https://api.whatsapp.com/send?phone=&text=<?php if (isset($user->company_name) && $user->company_name != '') {
                                echo '*' . urlencode(trim($user->company_name)) . '*';
                            } ?>%0A%0APlease%20click%20on%20below%20link%20to%20check%20Digital%20Card.%0A<?php echo $final_link; ?>"><img class="whats-app-logo">
                                <img class="img-70"src="{{ url('/') }}/dist/theme_assets/img/whatsapp-icon.png">
                            </a>
                            Saved
                        </div>
                        <div class="text-center" id="shareButton" style="display: grid;">
                            <img class="img-70" src="{{ url('/') }}/dist/theme_assets/img/digital-marketing.png">
                            Other Apps
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">

                        Scan QR Code
                        <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $final_link; ?>&choe=UTF-8" style="width: 100%" title="Paypal Details" />
                    </div>
                    {{-- <li>
                        <a target="_blank" href="https://api.whatsapp.com/send?phone=&text=%0A%0APlease%20click%20on%20below%20link%20to%20check%20Digital%20Card.%0A"><img class="whats-app-logo"><img src=""></a>
                        <p>Saved</p>
                    </li>
                    <li>
                        <a href=""><img class="whats-app-logo"><img src=""></a>
                        <p>Unsaved</p>
                    </li>
                   
                    <li><a href=""><img src=""></a>
                        <p>Qr Code</p>
                    </li> --}}
                </div>
            </div>
        </div>
    </div>
</div>
