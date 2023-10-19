<div class="flyout" xmlns="http://www.w3.org/1999/html">
    <div class="flyout_modal_open_review">
        <span class="flyout_modal_close_btn_review"></span>
        <div class="flyout_modal_open_boom">
            <div class="col-lg-12">
                <div class="body">
                    <!-- Nav tabs -->


                    <div class="card">

                        <div class="body" id="success_data" style="margin-top: 25%;background: #f7f7f7;display: none;padding: 15px 0;">
                            <div class="form-group text-center">
                                <div>
                                    <img src="<?php echo FULL_WEBSITE_URL; ?>user/assets/images/check.png" style="width: 18%">
                                </div>
                                <div>
                                    <h4>Hi <span id="review_user_name"></span>,</h4>

                                    <p>Thank You! For Your Valuable Feedback.</p>

                                    <p>We appreciate you taking the time to send us this helpful response.</p>

                                    <p>Don't hesitate to reach out if you have any more questions, comments, or
                                        concerns.</p>
                                </div>
                            </div>
                        </div>


                        <div class="body" id="review_data">
                            <div class="col-md-12 text-center m-t-5 m-b-0">
                                <div class="row">
                                    <h2>Write a review</h2>
                                    <!--                                      //  <span class="more">-->
                                    <?php //echo urldecode($get_data['message']) ?><!--</span>-->
                                    <p><b>We would like your feedback to improve our services .</p>
                                    <!--                                     //   <p>what is your opinion of the page?</b></p>-->
                                    <hr>
                                    <div>
                                        <?php if ($error) {
                                            ?>
                                            <div class="alert alert-danger">
                                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                            </div>
                                            <?php
                                        } else if (!$error && $errorMessage != "") {
                                            ?>
                                            <div class="alert alert-success">
                                                <?php if (isset($errorMessage)) echo $errorMessage; ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <form id="form_validation" method="POST" action=""
                                  enctype="multipart/form-data">

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="alert alert-danger" id="msg_alert_danger" style="display: none">
                                        </div>
                                        <div class="alert alert-success" id="msg_alert_success" style="display: none">
                                        </div>
                                    </div>
                                </div>
                                <div id="describe_div">
                                    <div class="feedback">
                                        <div class="rating">
                                            <input type="radio" name="rating" value="5" id="rating-5">
                                            <label for="rating-5"></label>
                                            <input type="radio" name="rating" value="4" id="rating-4">
                                            <label for="rating-4"></label>
                                            <input type="radio" name="rating" value="3" id="rating-3">
                                            <label for="rating-3"></label>
                                            <input type="radio" name="rating" value="2" id="rating-2">
                                            <label for="rating-2"></label>
                                            <input type="radio" name="rating" value="1" id="rating-1">
                                            <label for="rating-1"></label>
                                            <div class="emoji-wrapper">
                                                <div class="emoji">
                                                    <svg class="rating-0" xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 512 512">
                                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                                        <path d="M512 256c0 141.44-114.64 256-256 256-80.48 0-152.32-37.12-199.28-95.28 43.92 35.52 99.84 56.72 160.72 56.72 141.36 0 256-114.56 256-256 0-60.88-21.2-116.8-56.72-160.72C474.8 103.68 512 175.52 512 256z"
                                                              fill="#f4c534"/>
                                                        <ellipse transform="scale(-1) rotate(31.21 715.433 -595.455)"
                                                                 cx="166.318" cy="199.829" rx="56.146" ry="56.13"
                                                                 fill="#fff"/>
                                                        <ellipse transform="rotate(-148.804 180.87 175.82)" cx="180.871"
                                                                 cy="175.822" rx="28.048" ry="28.08" fill="#3e4347"/>
                                                        <ellipse transform="rotate(-113.778 194.434 165.995)"
                                                                 cx="194.433" cy="165.993" rx="8.016" ry="5.296"
                                                                 fill="#5a5f63"/>
                                                        <ellipse transform="scale(-1) rotate(31.21 715.397 -1237.664)"
                                                                 cx="345.695" cy="199.819" rx="56.146" ry="56.13"
                                                                 fill="#fff"/>
                                                        <ellipse transform="rotate(-148.804 360.25 175.837)"
                                                                 cx="360.252" cy="175.84" rx="28.048" ry="28.08"
                                                                 fill="#3e4347"/>
                                                        <ellipse transform="scale(-1) rotate(66.227 254.508 -573.138)"
                                                                 cx="373.794" cy="165.987" rx="8.016" ry="5.296"
                                                                 fill="#5a5f63"/>
                                                        <path d="M370.56 344.4c0 7.696-6.224 13.92-13.92 13.92H155.36c-7.616 0-13.92-6.224-13.92-13.92s6.304-13.92 13.92-13.92h201.296c7.696.016 13.904 6.224 13.904 13.92z"
                                                              fill="#3e4347"/>
                                                    </svg>
                                                    <svg class="rating-1" xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 512 512">
                                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                                        <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z"
                                                              fill="#f4c534"/>
                                                        <path d="M328.4 428a92.8 92.8 0 0 0-145-.1 6.8 6.8 0 0 1-12-5.8 86.6 86.6 0 0 1 84.5-69 86.6 86.6 0 0 1 84.7 69.8c1.3 6.9-7.7 10.6-12.2 5.1z"
                                                              fill="#3e4347"/>
                                                        <path d="M269.2 222.3c5.3 62.8 52 113.9 104.8 113.9 52.3 0 90.8-51.1 85.6-113.9-2-25-10.8-47.9-23.7-66.7-4.1-6.1-12.2-8-18.5-4.2a111.8 111.8 0 0 1-60.1 16.2c-22.8 0-42.1-5.6-57.8-14.8-6.8-4-15.4-1.5-18.9 5.4-9 18.2-13.2 40.3-11.4 64.1z"
                                                              fill="#f4c534"/>
                                                        <path d="M357 189.5c25.8 0 47-7.1 63.7-18.7 10 14.6 17 32.1 18.7 51.6 4 49.6-26.1 89.7-67.5 89.7-41.6 0-78.4-40.1-82.5-89.7A95 95 0 0 1 298 174c16 9.7 35.6 15.5 59 15.5z"
                                                              fill="#fff"/>
                                                        <path d="M396.2 246.1a38.5 38.5 0 0 1-38.7 38.6 38.5 38.5 0 0 1-38.6-38.6 38.6 38.6 0 1 1 77.3 0z"
                                                              fill="#3e4347"/>
                                                        <path d="M380.4 241.1c-3.2 3.2-9.9 1.7-14.9-3.2-4.8-4.8-6.2-11.5-3-14.7 3.3-3.4 10-2 14.9 2.9 4.9 5 6.4 11.7 3 15z"
                                                              fill="#fff"/>
                                                        <path d="M242.8 222.3c-5.3 62.8-52 113.9-104.8 113.9-52.3 0-90.8-51.1-85.6-113.9 2-25 10.8-47.9 23.7-66.7 4.1-6.1 12.2-8 18.5-4.2 16.2 10.1 36.2 16.2 60.1 16.2 22.8 0 42.1-5.6 57.8-14.8 6.8-4 15.4-1.5 18.9 5.4 9 18.2 13.2 40.3 11.4 64.1z"
                                                              fill="#f4c534"/>
                                                        <path d="M155 189.5c-25.8 0-47-7.1-63.7-18.7-10 14.6-17 32.1-18.7 51.6-4 49.6 26.1 89.7 67.5 89.7 41.6 0 78.4-40.1 82.5-89.7A95 95 0 0 0 214 174c-16 9.7-35.6 15.5-59 15.5z"
                                                              fill="#fff"/>
                                                        <path d="M115.8 246.1a38.5 38.5 0 0 0 38.7 38.6 38.5 38.5 0 0 0 38.6-38.6 38.6 38.6 0 1 0-77.3 0z"
                                                              fill="#3e4347"/>
                                                        <path d="M131.6 241.1c3.2 3.2 9.9 1.7 14.9-3.2 4.8-4.8 6.2-11.5 3-14.7-3.3-3.4-10-2-14.9 2.9-4.9 5-6.4 11.7-3 15z"
                                                              fill="#fff"/>
                                                    </svg>
                                                    <svg class="rating-2" xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 512 512">
                                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                                        <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z"
                                                              fill="#f4c534"/>
                                                        <path d="M336.6 403.2c-6.5 8-16 10-25.5 5.2a117.6 117.6 0 0 0-110.2 0c-9.4 4.9-19 3.3-25.6-4.6-6.5-7.7-4.7-21.1 8.4-28 45.1-24 99.5-24 144.6 0 13 7 14.8 19.7 8.3 27.4z"
                                                              fill="#3e4347"/>
                                                        <path d="M276.6 244.3a79.3 79.3 0 1 1 158.8 0 79.5 79.5 0 1 1-158.8 0z"
                                                              fill="#fff"/>
                                                        <circle cx="340" cy="260.4" r="36.2" fill="#3e4347"/>
                                                        <g fill="#fff">
                                                            <ellipse transform="rotate(-135 326.4 246.6)" cx="326.4"
                                                                     cy="246.6" rx="6.5" ry="10"/>
                                                            <path d="M231.9 244.3a79.3 79.3 0 1 0-158.8 0 79.5 79.5 0 1 0 158.8 0z"/>
                                                        </g>
                                                        <circle cx="168.5" cy="260.4" r="36.2" fill="#3e4347"/>
                                                        <ellipse transform="rotate(-135 182.1 246.7)" cx="182.1"
                                                                 cy="246.7" rx="10" ry="6.5" fill="#fff"/>
                                                    </svg>
                                                    <svg class="rating-3" xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 512 512">
                                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                                        <path d="M407.7 352.8a163.9 163.9 0 0 1-303.5 0c-2.3-5.5 1.5-12 7.5-13.2a780.8 780.8 0 0 1 288.4 0c6 1.2 9.9 7.7 7.6 13.2z"
                                                              fill="#3e4347"/>
                                                        <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z"
                                                              fill="#f4c534"/>
                                                        <g fill="#fff">
                                                            <path d="M115.3 339c18.2 29.6 75.1 32.8 143.1 32.8 67.1 0 124.2-3.2 143.2-31.6l-1.5-.6a780.6 780.6 0 0 0-284.8-.6z"/>
                                                            <ellipse cx="356.4" cy="205.3" rx="81.1" ry="81"/>
                                                        </g>
                                                        <ellipse cx="356.4" cy="205.3" rx="44.2" ry="44.2"
                                                                 fill="#3e4347"/>
                                                        <g fill="#fff">
                                                            <ellipse transform="scale(-1) rotate(45 454 -906)"
                                                                     cx="375.3" cy="188.1" rx="12" ry="8.1"/>
                                                            <ellipse cx="155.6" cy="205.3" rx="81.1" ry="81"/>
                                                        </g>
                                                        <ellipse cx="155.6" cy="205.3" rx="44.2" ry="44.2"
                                                                 fill="#3e4347"/>
                                                        <ellipse transform="scale(-1) rotate(45 454 -421.3)" cx="174.5"
                                                                 cy="188" rx="12" ry="8.1" fill="#fff"/>
                                                    </svg>
                                                    <svg class="rating-4" xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 512 512">
                                                        <circle cx="256" cy="256" r="256" fill="#ffd93b"/>
                                                        <path d="M512 256A256 256 0 0 1 56.7 416.7a256 256 0 0 0 360-360c58.1 47 95.3 118.8 95.3 199.3z"
                                                              fill="#f4c534"/>
                                                        <path d="M232.3 201.3c0 49.2-74.3 94.2-74.3 94.2s-74.4-45-74.4-94.2a38 38 0 0 1 74.4-11.1 38 38 0 0 1 74.3 11.1z"
                                                              fill="#e24b4b"/>
                                                        <path d="M96.1 173.3a37.7 37.7 0 0 0-12.4 28c0 49.2 74.3 94.2 74.3 94.2C80.2 229.8 95.6 175.2 96 173.3z"
                                                              fill="#d03f3f"/>
                                                        <path d="M215.2 200c-3.6 3-9.8 1-13.8-4.1-4.2-5.2-4.6-11.5-1.2-14.1 3.6-2.8 9.7-.7 13.9 4.4 4 5.2 4.6 11.4 1.1 13.8z"
                                                              fill="#fff"/>
                                                        <path d="M428.4 201.3c0 49.2-74.4 94.2-74.4 94.2s-74.3-45-74.3-94.2a38 38 0 0 1 74.4-11.1 38 38 0 0 1 74.3 11.1z"
                                                              fill="#e24b4b"/>
                                                        <path d="M292.2 173.3a37.7 37.7 0 0 0-12.4 28c0 49.2 74.3 94.2 74.3 94.2-77.8-65.7-62.4-120.3-61.9-122.2z"
                                                              fill="#d03f3f"/>
                                                        <path d="M411.3 200c-3.6 3-9.8 1-13.8-4.1-4.2-5.2-4.6-11.5-1.2-14.1 3.6-2.8 9.7-.7 13.9 4.4 4 5.2 4.6 11.4 1.1 13.8z"
                                                              fill="#fff"/>
                                                        <path d="M381.7 374.1c-30.2 35.9-75.3 64.4-125.7 64.4s-95.4-28.5-125.8-64.2a17.6 17.6 0 0 1 16.5-28.7 627.7 627.7 0 0 0 218.7-.1c16.2-2.7 27 16.1 16.3 28.6z"
                                                              fill="#3e4347"/>
                                                        <path d="M256 438.5c25.7 0 50-7.5 71.7-19.5-9-33.7-40.7-43.3-62.6-31.7-29.7 15.8-62.8-4.7-75.6 34.3 20.3 10.4 42.8 17 66.5 17z"
                                                              fill="#e24b4b"/>
                                                    </svg>
                                                    <svg class="rating-5" xmlns="http://www.w3.org/2000/svg"
                                                         viewBox="0 0 512 512">
                                                        <g fill="#ffd93b">
                                                            <circle cx="256" cy="256" r="256"/>
                                                            <path d="M512 256A256 256 0 0 1 56.8 416.7a256 256 0 0 0 360-360c58 47 95.2 118.8 95.2 199.3z"/>
                                                        </g>
                                                        <path d="M512 99.4v165.1c0 11-8.9 19.9-19.7 19.9h-187c-13 0-23.5-10.5-23.5-23.5v-21.3c0-12.9-8.9-24.8-21.6-26.7-16.2-2.5-30 10-30 25.5V261c0 13-10.5 23.5-23.5 23.5h-187A19.7 19.7 0 0 1 0 264.7V99.4c0-10.9 8.8-19.7 19.7-19.7h472.6c10.8 0 19.7 8.7 19.7 19.7z"
                                                              fill="#e9eff4"/>
                                                        <path d="M204.6 138v88.2a23 23 0 0 1-23 23H58.2a23 23 0 0 1-23-23v-88.3a23 23 0 0 1 23-23h123.4a23 23 0 0 1 23 23z"
                                                              fill="#45cbea"/>
                                                        <path d="M476.9 138v88.2a23 23 0 0 1-23 23H330.3a23 23 0 0 1-23-23v-88.3a23 23 0 0 1 23-23h123.4a23 23 0 0 1 23 23z"
                                                              fill="#e84d88"/>
                                                        <g fill="#38c0dc">
                                                            <path d="M95.2 114.9l-60 60v15.2l75.2-75.2zM123.3 114.9L35.1 203v23.2c0 1.8.3 3.7.7 5.4l116.8-116.7h-29.3z"/>
                                                        </g>
                                                        <g fill="#d23f77">
                                                            <path d="M373.3 114.9l-66 66V196l81.3-81.2zM401.5 114.9l-94.1 94v17.3c0 3.5.8 6.8 2.2 9.8l121.1-121.1h-29.2z"/>
                                                        </g>
                                                        <path d="M329.5 395.2c0 44.7-33 81-73.4 81-40.7 0-73.5-36.3-73.5-81s32.8-81 73.5-81c40.5 0 73.4 36.3 73.4 81z"
                                                              fill="#3e4347"/>
                                                        <path d="M256 476.2a70 70 0 0 0 53.3-25.5 34.6 34.6 0 0 0-58-25 34.4 34.4 0 0 0-47.8 26 69.9 69.9 0 0 0 52.6 24.5z"
                                                              fill="#e24b4b"/>
                                                        <path d="M290.3 434.8c-1 3.4-5.8 5.2-11 3.9s-8.4-5.1-7.4-8.7c.8-3.3 5.7-5 10.7-3.8 5.1 1.4 8.5 5.3 7.7 8.6z"
                                                              fill="#fff" opacity=".2"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="form-label">Describe Your Experience</label> <span
                                                class="required_field">*</span>

                                        <div class="form-group form-float">
                                            <div class="form-line">
                                        <textarea name="txt_des" rows="4" cols="50" class="form-control" required
                                                  placeholder="Describe Your Experience"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group form-float">
                                            <div class="row">
<div class="col-md-6 col-xs-8 m-b-0">
                                                    <label class="form-label profile_pic_label">Profile Pic (Optional)</label><br>
                                                    <input type="file" name="upload" id="file-7"
                                                           class="inputfile inputfile-6"
                                                           data-multiple-caption="{count} files selected"
                                                           onchange="readURL(this);" style="display: none"
                                                           accept=".png, .jpg, .jpeg,.JPG,.PNG,.JPEG"/>
                                                    <label for="file-7" style="width: 140px;"><span></span> <img
                                                                id="blah"
                                                                class="input_choose_file blah"
                                                                src=""
                                                                alt=""/><strong
                                                                class="input_choose_file">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 width="20"
                                                                 height="17" viewBox="0 0 20 17">
                                                                <path
                                                                        d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                                                            </svg>
                                                            Choose a file&hellip;</strong></label>
                                                </div>
<div class="col-xs-4">
                                                    <div id="show_img">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group text-center">
                                            <button type="button" class="btn btn-primary waves-effect" name="btn_save_client" ><i class="fa fa-paper-plane"
                                                                              style="font-size: 16px;"></i> Submit
                                                Review
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="contact_verify">
                                    <div>
                                        <label class="form-label">Name</label> <span
                                                class="required_field">*</span>

                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input name="q_name" class="form-control"
                                                       placeholder="Enter Your Name" required
                                                       value="">
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if(isset($country) && $country =="101") {
                                        ?>
                                        <div>
                                            <label class="form-label">Contact Number</label> <span
                                                class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="number" placeholder="Contact Number"
                                                           class="form-control"
                                                           name="q_contact_no">
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }else{
                                        ?>
                                        <div>
                                            <label class="form-label">Email Id</label> <span
                                                class="required_field">*</span>

                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="email" placeholder="Enter Email Id"
                                                           class="form-control"
                                                           name="q_contact_no">
                                                </div>
                                            </div>
                                        </div>
                                    <?php

                                    }
                                    ?>

                                    <div class="form-group text_box" id="open_otp">
                                        <label class="f_p text_c f_400">Enter OTP</label>
                                        <!-- <input type="number" class="form-control"
                                                name="q_send_otp"  placeholder="Enter OTP" autofocus
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="4" value="">-->
                                        <div class="otp_section">
                                            <div class="digit-group">
                                                <input class="send_textbox" type="number" id="digit-1"
                                                       name="q_send_otp[]" data-next="digit-2"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="6"/>
                                                <input class="send_textbox" type="number" id="digit-2"
                                                       name="q_send_otp[]" data-next="digit-3" data-previous="digit-1"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="6"/>
                                                <input class="send_textbox" type="number" id="digit-3"
                                                       name="q_send_otp[]" data-next="digit-4" data-previous="digit-2"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="6"/>
                                                <span class="splitter">&ndash;</span>
                                                <input class="send_textbox" type="number" id="digit-4"
                                                       name="q_send_otp[]" data-next="digit-5" data-previous="digit-3"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="6"/>
                                                <input class="send_textbox" type="number" id="digit-5"
                                                       name="q_send_otp[]" data-next="digit-6" data-previous="digit-4"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="6"/>
                                                <input class="send_textbox" type="number" id="digit-6"
                                                       name="q_send_otp[]" data-previous="digit-5"
                                                       oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                       maxlength="6"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn_hover btn btn-info app_btn view_more_btn" <?php echo (isset($get_serv_reviews[1]) && $get_serv_reviews[1] == "0") ? 'id="submit_review"':'id="get_otp"'; ?>
                                                type="button"><?php echo (isset($get_serv_reviews[1]) && $get_serv_reviews[1] == "0") ? 'Submit Review':'Send OTP'; ?>
                                        </button>
                                        <button class="btn_hover btn btn-success app_btn view_more_btn"
                                                id="verify_otp_btn" type="button">Verify OTP
                                        </button>
                                        <a href="javascript:void(0);" id="resent_otp_btn">Try to resent OTP.</a>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>


                </div>
            </div>
        </div>

    </div>
</div>
<script>
    const flyoutModalButtonReview = document.querySelector('.flyout_modal_button_review');
    const flyoutModalOpenReview = document.querySelector('.flyout_modal_open_review');
    const flyoutModalCloseReview = document.querySelector('.flyout_modal_close_btn_review');

    flyoutModalButtonReview.addEventListener('click', function () {
        flyoutModalOpenReview.classList.add('active');
        //$(".flyout_modal_open_review").addClass('active');
    });

    flyoutModalCloseReview.addEventListener('click', function () {
        flyoutModalOpenReview.classList.remove('active');
    })
</script>

<script>
    $('button[name=btn_save_client]').on('click', function () {
        var ratingNum = $("input[name=rating]:checked").val();
        var txt_des = $("textarea[name=txt_des]").val();
        var imgname = $("input[name=upload]").val();
        var fileError = false;
        if (imgname) { // returns true if the string is not empty
            data = new FormData();
            data.append('file', $("input[name=upload]")[0].files[0]);

            var size = $('input[name=upload]')[0].files[0].size;

            var ext = imgname.substr((imgname.lastIndexOf('.') + 1));
            if (ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif' || ext == 'PNG' || ext == 'JPG' || ext == 'JPEG') {
                if (size <= 1048576) {
                    fileError = false;
                } else {
                    $('#msg_alert_danger').show().append('File Size should be less than 1 mb.<br>');
                    fileError = true;
                }
            }
        }

        if (ratingNum == undefined) {
            fileError = true;
            $('#msg_alert_danger').show().append('Please give rating.<br>');
        }
        if (txt_des.trim().length < 1) {
            fileError = true;
            $('#msg_alert_danger').show().append('Please Enter Description.<br>');
        }
        if (!fileError) {
            $('#msg_alert_danger').hide();
            $('#describe_div').hide();
            $('#contact_verify').show();


        }
    });
    $(document).ready(function () {
        $('#msg_alert_danger').hide();
        $('#msg_alert_success').hide();
        $('#open_otp').hide();
        $('#verify_otp_btn,#resent_otp_btn').hide();
    });
    $('#get_otp').on('click', function () {
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        if (full_name != '' && contact_no != '') {
            var dataString = "send_otp=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name)+"&country="+'<?php echo $country; ?>';
            $.ajax({
                type: 'POST',
                url: '<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php',
                dataType: "json",
                data: dataString,
                beforeSend: function () {
                    $('#get_otp').text('Sending Otp...').attr("disabled", 'disabled');
                    $('input[name=q_name]').attr("disabled", 'disabled');
                    $('input[name=q_contact_no]').attr("disabled", 'disabled');
                },
                success: function (response) {
                    if (response.status == 'ok') {
                        $('#get_otp').css('display', 'none').removeAttr('disabled');
                        $('#verify_otp_btn,#resent_otp_btn,#open_otp').show();
                        $('#msg_alert_success').show().text('OTP has been sent successfully!');
                        $('#msg_alert_danger').hide();
                    } else {
                        $('#get_otp').text('Send OTP').removeAttr('disabled');
                        $('#msg_alert_success').hide();
                        $('input[name=q_contact_no]').removeAttr("disabled");
                        $('#msg_alert_danger').show().text('Issue while sending OTP try after some time.');
                    }
                },
                error: function (err) {
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });
        }
    });
    $('#resent_otp_btn').on('click', function () {
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        if (full_name != '' && contact_no != '') {
            var dataString = "send_otp=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name)+"&country="+'<?php echo $country; ?>';
            $.ajax({
                type: 'POST',
                url: '<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php',
                dataType: "json",
                data: dataString,
                beforeSend: function () {
                    $('#resent_otp_btn').text('Sending Otp...').attr("disabled", 'disabled');
                    $('input[name=q_name]').attr("disabled", 'disabled');
                    $('input[name=q_contact_no]').attr("disabled", 'disabled');
                },
                success: function (response) {
                    $('#resent_otp_btn').text('Try to resent OTP.').removeAttr('disabled');
                    if (response.status == 'ok') {
                        $('#verify_otp_btn,#resent_otp_btn,#open_otp').show();
                        $('#msg_alert_success').show().text('OTP has been re-sent successfully!');
                        $('#msg_alert_danger').hide();
                    } else {
                        $('#msg_alert_success').hide();
                        $('#msg_alert_danger').show().text('Issue while sending OTP try after some time.');
                    }
                },
                error: function (err) {
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });
        }
    });
    $('#verify_otp_btn').on('click', function () {

        var ratingNum = $("input[name=rating]:checked").val();
        var description = $("textarea[name=txt_des]").val();
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        // var otp_number = $('input[name=q_send_otp]').val();
        var otp_number = $("input[name='q_send_otp[]']")
            .map(function () {
                return $(this).val();
            }).get();
        var imgname = $("input[name=upload]").val();
        data = new FormData();
        if (imgname) {
            data.append('file', $("input[name=upload]")[0].files[0]);
        }
        data.append('contact_no', encodeURIComponent(contact_no));
        data.append('full_name', encodeURIComponent(full_name));
        data.append('verify_otp', otp_number);
        data.append('description', encodeURIComponent(description));
        data.append('ratingNum', ratingNum);
        data.append('user_id', <?php echo $user_id; ?>);
        data.append('admin_email', '<?php echo urlencode($email); ?>');
        data.append('admin_contact', '<?php echo urlencode($contact_no); ?>');

        if (full_name != '' && contact_no != '' && otp_number != '') {
            //  console.log(data);
            var dataString = "contact_no=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name) +
                "&verify_otp=" + otp_number + "&description=" + description + "&ratingNum=" + ratingNum + "&user_id=" +<?php echo $user_id; ?> +"&admin_email=" + '<?php echo urlencode($email); ?>' + "&admin_contact=" + '<?php echo urlencode($contact_no); ?>';
            // console.log(dataString);
            if (imgname) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php',
                    enctype: 'multipart/form-data',
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    dataType: "json",
                    data: data,
                    beforeSend: function () {
                        $('#verify_otp_btn').text('Verifying Otp...').attr("disabled", 'disabled');
                        $('input[name=q_name]').attr("disabled", 'disabled');
                        $('input[name=q_contact_no]').attr("disabled", 'disabled');
                    },
                    success: function (response) {
                        if (response.status == 'ok') {
                            $('#review_user_name').text(response.full_name);
                            $('.ul_client_review').prepend(response.data);
                            $('#review_data').hide();
                            $('#success_data').show();
                            $('input[name=q_name]').removeAttr("disabled").val('');
                            $('input[name=q_contact_no]').removeAttr("disabled").val('');
                            $('#get_otp').text('Send OTP');
                            $('#get_otp').css('display', 'block');
                            $('#msg_alert_danger').hide();
                            $('#msg_alert_success').hide();
                            $('#open_otp').hide();
                            $('#verify_otp_btn').hide();
                        } else {
                            $('#verify_otp_btn').text('Verify Otp').attr('name', 'verify_otp_btn').removeAttr('disabled');
                            $('#msg_alert_success').hide();
                            $('#msg_alert_danger').show().text(response.msg);
                        }
                    },
                    error: function (err) {
                        console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                    }
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php',
                    // enctype: 'multipart/form-data',
                    //  processData: false,  // tell jQuery not to process the data
                    //  contentType: false,   // tell jQuery not to set contentType
                    dataType: "json",
                    data: dataString,
                    beforeSend: function () {
                        $('#verify_otp_btn').text('Verifying Otp...').attr("disabled", 'disabled');
                        $('input[name=q_name]').attr("disabled", 'disabled');
                        $('input[name=q_contact_no]').attr("disabled", 'disabled');
                    },
                    success: function (response) {
                        if (response.status == 'ok') {
                            $('#review_user_name').text(response.full_name);
                            $('.ul_client_review').prepend(response.data);
                            $('#review_data').hide();
                            $('#success_data').show();
                            $('input[name=q_name]').removeAttr("disabled").val('');
                            $('input[name=q_contact_no]').removeAttr("disabled").val('');
                            $('#get_otp').text('Send OTP');
                            $('#get_otp').css('display', 'block');
                            $('#msg_alert_danger').hide();
                            $('#msg_alert_success').hide();
                            $('#open_otp').hide();
                            $('#verify_otp_btn').hide();
                        } else {
                            $('#verify_otp_btn').text('Verify Otp').attr('name', 'verify_otp_btn').removeAttr('disabled');
                            $('#msg_alert_success').hide();
                            $('#msg_alert_danger').show().text(response.msg);
                        }
                    },
                    error: function (err) {
                        console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                    }
                });
            }

        } else {
            $('#msg_alert_success').hide();
            $('#msg_alert_danger').show().text('Please enter OTP');
        }
    });
    $('#submit_review').on('click', function () {

        var ratingNum = $("input[name=rating]:checked").val();
        var description = $("textarea[name=txt_des]").val();
        var full_name = $('input[name=q_name]').val();
        var contact_no = $('input[name=q_contact_no]').val();
        // var otp_number = $('input[name=q_send_otp]').val();

        /*var otp_number = $("input[name='q_send_otp[]']")
            .map(function () {
                return $(this).val();
            }).get();*/
        var imgname = $("input[name=upload]").val();
        data = new FormData();
        if (imgname) {
            data.append('file', $("input[name=upload]")[0].files[0]);
        }
        data.append('contact_no', encodeURIComponent(contact_no));
        data.append('full_name', encodeURIComponent(full_name));
        // data.append('verify_otp', otp_number);
        data.append('description', encodeURIComponent(description));
        data.append('ratingNum', ratingNum);
        data.append('user_id', <?php echo $user_id; ?>);
        data.append('admin_email', '<?php echo urlencode($email); ?>');
        data.append('admin_contact', '<?php echo urlencode($contact_no); ?>');

        if (full_name != '' && contact_no != '') {
            //  console.log(data);
            var dataString = "contact_no=" + encodeURIComponent(contact_no) + "&full_name=" + encodeURIComponent(full_name) +
                "&description=" + description + "&ratingNum=" + ratingNum +  "&submit_review=true&user_id=" +<?php echo $user_id; ?> +"&admin_email=" + '<?php echo urlencode($email); ?>' + "&admin_contact=" + '<?php echo urlencode($contact_no); ?>';
            // console.log(dataString);
            if (imgname) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php',
                    enctype: 'multipart/form-data',
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    dataType: "json",
                    data: data,
                    beforeSend: function () {
                        $('#verify_otp_btn').text('Verifying Otp...').attr("disabled", 'disabled');
                        $('input[name=q_name]').attr("disabled", 'disabled');
                        $('input[name=q_contact_no]').attr("disabled", 'disabled');
                    },
                    success: function (response) {
                        if (response.status == 'ok') {
                            $('#review_user_name').text(response.full_name);
                            $('.ul_client_review').prepend(response.data);
                            $('#review_data').hide();
                            $('#success_data').show();
                            $('input[name=q_name]').removeAttr("disabled").val('');
                            $('input[name=q_contact_no]').removeAttr("disabled").val('');
                            $('#get_otp').text('Send OTP');
                            $('#get_otp').css('display', 'block');
                            $('#msg_alert_danger').hide();
                            $('#msg_alert_success').hide();
                            $('#open_otp').hide();
                            $('#verify_otp_btn').hide();
                        } else {
                            $('#verify_otp_btn').text('Verify Otp').attr('name', 'verify_otp_btn').removeAttr('disabled');
                            $('#msg_alert_success').hide();
                            $('#msg_alert_danger').show().text(response.msg);
                        }
                    },
                    error: function (err) {
                        console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                    }
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo FULL_WEBSITE_URL; ?>review-flyout-ajax.php',
                    // enctype: 'multipart/form-data',
                    //  processData: false,  // tell jQuery not to process the data
                    //  contentType: false,   // tell jQuery not to set contentType
                    dataType: "json",
                    data: dataString,
                    beforeSend: function () {
                        $('#verify_otp_btn').text('Verifying Otp...').attr("disabled", 'disabled');
                        $('input[name=q_name]').attr("disabled", 'disabled');
                        $('input[name=q_contact_no]').attr("disabled", 'disabled');
                    },
                    success: function (response) {
                        if (response.status == 'ok') {
                            $('#review_user_name').text(response.full_name);
                            $('.ul_client_review').prepend(response.data);
                            $('#review_data').hide();
                            $('#success_data').show();
                            $('input[name=q_name]').removeAttr("disabled").val('');
                            $('input[name=q_contact_no]').removeAttr("disabled").val('');
                            $('#get_otp').text('Send OTP');
                            $('#get_otp').css('display', 'block');
                            $('#msg_alert_danger').hide();
                            $('#msg_alert_success').hide();
                            $('#open_otp').hide();
                            $('#verify_otp_btn').hide();
                        } else {
                            $('#verify_otp_btn').text('Verify Otp').attr('name', 'verify_otp_btn').removeAttr('disabled');
                            $('#msg_alert_success').hide();
                            $('#msg_alert_danger').show().text(response.msg);
                        }
                    },
                    error: function (err) {
                        console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                    }
                });
            }

        } else {
            $('#msg_alert_success').hide();
            $('#msg_alert_danger').show().text('Please enter OTP');
        }
    });


</script>
<script>
    $(document).ready(function () {
        if ($('#blah').attr('src') == "" || $('#blah').attr('src') == "unknown") {
            $('#blah').hide();
        }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                /*$('.blah')
                    .attr('src', e.target.result);*/
                $('#show_img').html('<img src="'+e.target.result+'" style="width: 100%;">');
            };
            reader.readAsDataURL(input.files[0]);
            // $('#blah').show();

              $('#show_img').show();
        }
    }
</script>