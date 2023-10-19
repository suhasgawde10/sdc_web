<?php

require_once "controller/ManageApp.php";
$manage = new ManageApp();

header("Location: https://www.sharedigitalcard.com/index.php");

?>
<!doctype html>
<html lang="en">
<head>
    <!--- Basic Page Needs  -->
    <meta charset="utf-8">
    <title>Dealership Program | Online business and visiting card maker in India, Maharashtra, Mumbai.</title>
    <meta name="description" content="we offer digital business card service for professional and business with exciting price and attractive feature.including website for business">
    <meta name="keywords"
          content="Dealership Program, digital business card, digital visiting card, online visiting card, online business card, visiting card design, maker in, india, maharashtra, mumbai, business card design, customized design, attractive visiting card, share digital card, business card application, visiting card application, app, price, offer, special">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Meta  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSS -->
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,900);

        body {
            font-family: 'Source Sans Pro', sans-serif;
        }
        .cust-btn-dealer{
            border-radius: 25px;
            padding: 11px 35px;
            margin-top: 10px;
        }
        table > thead > tr > th{
            font-size: 20px;
            color: #666;
            text-align: center;
        } table > tbody > tr > td{
            color: #333;
            text-align: center;
        }

        table > tbody > tr > td a{
            color: #2793e6;
        }

        .table-header {
            background-color: #327a81;
            color: white;
            font-size: 1.5em !important;
            padding: 1rem;
            text-align: center !important;
            text-transform: uppercase;
        }
        .table-users {
            border: 1px solid #e4e4e4;
            border-radius: 10px;
            /* box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);*/
          /*  max-width: calc(100% - 2em);*/
            margin: 1em auto;
            overflow: hidden;
            width: 100%;
        }
        .table-card {
            border: 1px solid #e4e4e4;
            border-radius: 5px;
            /* box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);*/
          /*  max-width: calc(100% - 2em);*/
            margin: 1em 0;
            overflow: hidden;
            width: 40%;
        }

        table {
            width: 100%;
        }
        table td, table th {
            color: #2b686e;
            padding: 10px;
        }
        .table-card table td{
            text-align: left;
        }
        table td {
            text-align: center;
            vertical-align: middle;
        }
        table td:last-child {
            font-size: 0.95em;
            line-height: 1.4;
            text-align: center;
        }
        table th {
            background-color: #daeff1;
            font-weight: 300;
        }
        table tr:nth-child(2n) {
            background-color: white;
        }
        table tr:nth-child(2n+1) {
            background-color: #edf7f8;
        }


        @media screen and (max-width: 500px) {
            .table-card {
                border: 1px solid #e4e4e4;
                border-radius: 5px;
                /* box-shadow: 3px 3px 0 rgba(0, 0, 0, 0.1);*/
                /*  max-width: calc(100% - 2em);*/
                margin: 1em 0;
                overflow: hidden;
                width: 100%;
            }
            table > thead > tr > th {
                font-size: 16px;
             }

            .table-users {
                border: none;
                box-shadow: none;
                overflow: auto;
            }
        }
        .padding_zero_model{
            padding: 0 !important;
        }
        #custom_table{
            padding: 0;
            margin: 0;
            border-radius: 0;
            border: none;
        }
        .convert-arrow{
            position: absolute;
            width: 55px;
            height: 55px;
            background: white;
            text-align: center;
            top: 151px;
            border-radius: 50%;
            padding-top: 11px;
            border: 1px solid #d6d6d6;
        }
    </style>
    <link src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css" rel="stylesheet">
</head>
<body>
<div class="visible-lg visible-md visible-sm hidden-xs">
<?php include "request-to-call-include.php"; ?>
    </div>
<!-- preloader area start -->


<!-- preloader area end -->
<!-- header area start -->
<?php include "assets/common-includes/header.php" ?>
<!-- header area end -->

<section>
    <div class="header-div">
        <div class="title">
            <h1>BECOME A PARTNER</h1>
           <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit!</p>-->
            <div>
                <button type="button" class="btn btn-primary cust-btn-dealer" onclick="document.location.href='dealership-program.php#benifits'">Benefits</button>
                <button class="btn btn-success cust-btn-dealer" type="button" onclick="document.location.href='dealer-register.php'">Get Started</button>
            </div>
        </div>
    </div>
</section>
<section class="feature-area about_back_color ptb--30" id="feature">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>What is Dealership Program?</h2>
                <div class="dearle-para">
                    <p>
                        Dealership Program allows any Businesses or Individual Person to sell Digital Card and Start Earning Extra Money out of it. Digital Card is special type of service which represent as Digital Visiting card and can be used to showcase and promote anyone’s business.
                    </p>
                    <p>
                        The Main Motive of Dealership program is to spread the concept of Digital Card Concept in order to replace traditional paper visiting card to save tree and represent your business in
                        Digital Way.
                    </p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary cust-btn-dealer" onclick="document.location.href='dealership-program.php#benifits'">Benefits</button>
                    <button class="btn btn-success cust-btn-dealer" type="button" onclick="document.location.href='dealer-register.php'">Get Started</button>
                </div>
            </div>
            <div class="col-md-6">
                <img src="assets/img/dealership-img/1.svg">
            </div>
        </div>
    </div>
</section>

<section class="pricing-area ptb--40" id="pricing">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="assets/img/dealership-img/2.jpg">
            </div>
            <div class="col-md-6">
    <h2>What is benefits of Dealership Program to Dealers?</h2>
                <div class="dearle-para dealer_icon">
    <p>As now you know What is dealership program let’s check what is actual benefits of Dealership program to Dealers.
        4 Methods of Earning.</p>
                    <p><i class="fa fa-angle-right"></i> Direct Earning</p>
                    <p><i class="fa fa-angle-right"></i> In Direct Earning</p>
                    <p><i class="fa fa-angle-right"></i> Renewal Benefits</p>
                    <p><i class="fa fa-angle-right"></i> Reference Benefits</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary cust-btn-dealer" onclick="document.location.href='dealership-program.php#benifits'">Benefits</button>
                    <button class="btn btn-success cust-btn-dealer" type="button" onclick="document.location.href='dealer-register.php'">Get Started</button>
                </div>

            </div>

        </div>
    </div>
</section>

<section class="pricing-area steps_to_create_sec" id="feature">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Who can register for dealership program?</h2>
                <div class="dearle-para dealer_icon">
                    <p>Dealership program can be joined by any Individual as well as businesses. Just before joining Dealership program it is necessary to Complete your KYC to Verify your identity.</p>
                    <p><i class="fa fa-angle-right"></i> Individual</p>
                    <p><i class="fa fa-angle-right"></i> Freelancer</p>
                    <p><i class="fa fa-angle-right"></i> Unregister Company</p>
                    <p><i class="fa fa-angle-right"></i> Register Company</p>
                    <div style="padding-bottom: 40px;">
                        <button type="button" class="btn btn-primary cust-btn-dealer" onclick="document.location.href='dealership-program.php#benifits'">Benefits</button>
                        <button class="btn btn-success cust-btn-dealer" type="button" onclick="document.location.href='dealer-register.php'">Get Started</button>
                    </div>
                </div>

            </div>
            <div class="col-md-6 text-center">
                <img src="assets/img/dealership-img/3.png">
            </div>
        </div>
    </div>
</section>

<section class="pricing-area ptb--30">
    <div class="container">
        <div class="section-title">
            <h1 class="main_heading">How we can Enroll for Dealership Program?</h1>
            <p>Go Paperless, Go Digital</p>
        </div>
        <!-- title-section -->
        <div class="row">
            <div class="angle_icon"></div>
            <div class="col-md-2 col-sm-12">

                <div class="process-item">
                    <div class="img_process">
                        <img src="assets/img/dealership-img/marriage.png" alt="image">
                        <span>01</span>
                    </div>
                    <div class="process_text">
                        <h4>Registration</h4>

                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">

                <div class="process-item">
                    <div class="img_process">
                        <img src="assets/img/dealership-img/to-do-list.png" alt="image">
                        <span>02</span>
                    </div>
                    <div class="process_text">
                        <h4>Fill Out Information / Complete Your KYC</h4>

                    </div>
                </div>

            </div>
            <div class="col-md-2 col-sm-12">

                <div class="process-item">
                    <div class="img_process">
                        <img src="assets/img/dealership-img/stopwatch.png" alt="image">
                        <span>03</span>
                    </div>
                    <div class="process_text">
                        <h4>Wait for Approval</h4>


                    </div>
                </div>

            </div>
            <div class="col-md-2 col-sm-12">

                <div class="process-item">
                    <div class="img_process">
                        <img src="assets/img/dealership-img/money.png" alt="image">
                        <span>04</span>
                    </div>
                    <div class="process_text">
                        <h4>Once Approved Pay Enrollment Fees.</h4>

                    </div>
                </div>

            </div>
            <div class="col-md-2 col-sm-12">

                <div class="process-item">
                    <div class="img_process">
                        <img src="assets/img/logo/logo.png" alt="image" class="img_process_custom">
                        <span>05</span>
                    </div>
                    <div class="process_text">
                        <h4>Create Digital Cards</h4>

                    </div>
                </div>

            </div>
            <div class="col-md-2 col-sm-12">
                <div class="process-item">
                    <div class="img_process">
                        <img src="assets/img/dealership-img/salary.png" alt="image">
                        <span>06</span>
                    </div>
                    <div class="process_text">
                        <h4>Sell and Start Earning.</h4>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- .container -->
</section>

<section class="pricing-area steps_to_create_sec" id="benifits">
    <div class="container">
        <div class="section-title" style="margin-bottom: 35px;">
            <h1 class="main_heading">Dealership Plans</h1>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="row">
                    <div class="table-users">
                        <table class="">
                            <?php
                            $get_user_plan1 = $manage->getUserSubscriptionPlan();
                            ?>
                            <thead>
                            <tr>
                                <td colspan="6" class="table-header">Customer Pricing</td>
                            </tr>
                            <tr>
                                <th>Plan</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while($row_plan = mysqli_fetch_array($get_user_plan1)) {
                                ?>
                                <tr>
                                    <td><?php echo $row_plan['year']; ?></td>
                                    <td><i class="fa fa-inr" aria-hidden="true"></i><?php echo $row_plan['amt']; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="convert-arrow">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </div>
            </div>
            <div class="col-md-6">
               <div class="row">
                   <div class="table-users">
                       <table class="">
                           <?php
                           $get_price = $manage->getDealerPriceDetails();
                           ?>
                           <thead>
                           <tr>
                               <td colspan="6" class="table-header">Dealer Pricing</td>
                           </tr>
                           <tr>
                               <th>Enrollment Fees (5 Year Validity)</th>
                               <th>Discount On Card</th>
                               <!-- <th>1 year</th>
                                <th>3 year</th>
                                <th>5 year</th>
                                <th>lifetime</th>-->
                           </tr>
                           </thead>
                           <tbody>
                           <?php
                           if($get_price !=null) {
                               $i = 1;
                               $amount = "";
                               while ($row = mysqli_fetch_array($get_price)) {
                                   $percentage = $row['percentage'];
                                   $get_user_plan = $manage->getUserSubscriptionPlan();
                                   while ($row_data = mysqli_fetch_array($get_user_plan)) {
                                       $total_percent = $percentage*$row_data['amt']/100;
                                       if($amount !=''){
                                           $amount .=  ","."'".round($row_data['amt']-$total_percent)."'";
                                       }else{
                                           $amount =  "'".round($row_data['amt']-$total_percent)."'";
                                       }

                                   }
                                   ?>

                                   <tr>
                                       <td><i class="fa fa-inr" aria-hidden="true"></i><span class="row_amount"></span><?php echo $row['pricing']; ?></td>
                                       <td><?php echo $percentage."%" ?><br><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal" onclick="setDataModalValue(<?php  echo $amount.",'".$percentage."%'"; ?>)">view pricing</a></td>
                                   </tr>

                                   <?php
                                   $i++;
                                   $amount = '';
                               }
                               ?>
                               <tr>
                                   <td colspan="2"><span class="color_red">Note : All the mention amount are excluding tax.</span></td>
                               </tr>
                                   <?php

                           }else {
                           ?>
                           </tbody>
                           <tr>
                               <td colspan="6">
                                   No Data Found.
                               </td>
                           </tr>
                       <?php
                       }
                       ?>
                       </table>
                   </div>
               </div>

            </div>
            <div class="col-md-12 text-center" style="padding-bottom: 40px">
                <button class="btn btn-success cust-btn-dealer" type="button" onclick="document.location.href='dealer-register.php'">Get Started</button>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
          <!--  <div class="modal-header">
                <h4 class="modal-title">Card Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>-->
            <div class="modal-body padding_zero_model">
                <div class="body">

                    <div class="table-users" id="custom_table">
                        <table class="">
                            <thead>
                            <tr>
                                <td colspan="6" class="table-header cust-table-header"></td>
                            </tr>
                            <tr>
                             <!--   <th>Onetime Enrollment Fees</th>
                                <th>Discount On Card</th>-->
                                 <th>1 year</th>
                                 <th>3 year</th>
                                 <th>5 year</th>
                                 <th>lifetime</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="inner_amount">
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="pricing-area ptb--40" id="feature">
    <div class="container">
        <div class="section-title" style="margin-bottom: 35px;">
            <h1 class="main_heading"> What are terms and condition for Dealership Program?</h1>
        </div>
        <div class="row d-flex">
            <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">

                <div class="about-content terms_and_condition_content_point dealer_icon">
                    <p class="dearle-para">As you are joining as dealer it is important to give your 100% in the form of dealership by selling maximum card possible, as we are not taking any Investment to join with us and there is no such monthly target the dealers have, it is important to keep Some Terms and conditions to make the Dealership account available for only Genuinely Working and Interested People.</p>

                    <p>We are happy to keep you on board as a dealer in our company</p>

                    <p>Following mentioned points you have to keep in your mind while working as dealer</p>

                    <p><i class="fa fa-angle-right"></i> To filter Out Genuinely Working and interested Dealer we are taking some amount as enrollment fees.</p>

                    <p><i class="fa fa-angle-right"></i> Validity for dealership account will be 5 years once enrolled,once dealer validity expired the digital card costing will reset to its original costing.</p>

                    <p><i class="fa fa-angle-right"></i> Once Dealership account has been Approved, You will need to pay enrollment fees.</p>

                    <!--<p><i class="fa fa-angle-right"></i> As Mentioned this security deposit is refundable and We will return this amount once you complete your 5 Customer Card Sells in first 6 Months of your dealership.</p>
-->
                    <!--<p><i class="fa fa-angle-right"></i> Once You process the Security Deposit it will get added to your Virtual Wallet.</p>
-->
                    <!--<p><i class="fa fa-angle-right"></i> If you unable to sell 5 cards in first 6 Months of dealership, your security deposit is not refundable and also your dealership account will get cancelled from our end , without affecting your previous customer card which you sold.</p>
-->
                    <p><i class="fa fa-angle-right"></i> It is important to Keep your selling price below the mentioned pricing as per the company rules and regulation.</p>
                    <div class="table-card">
                        <table>
                            <tr>
                                <th>Plan</th>
                                <th>Max Sell Price</th>
                            </tr>
                            <tr>
                                <td>1 Year Plan</td>
                                <td>30% of Original Plan Pricing</td>
                            </tr>
                            <tr>
                                <td>3 Year Plan</td>
                                <td>30% of Original Plan Pricing</td>
                            </tr>
                            <tr>
                                <td>5 Year Plan</td>
                                <td>30% of Original Plan Pricing</td>
                            </tr>
                            <tr>
                                <td>Lifetime Plan</td>
                                <td>30% of Original Plan Pricing</td>
                            </tr>
                        </table>
                    </div>
                    <p><i class="fa fa-angle-right"></i> If we found or if any customer reported that the dealer is selling the Digital card more than Maximum Price allowed or not following any terms and condition mentioned as above then that dealer's account will be cancelled.</p>
                    <p><i class="fa fa-angle-right"></i> It is Important to Sell a Digital card to your customer by verifying his identity so he will not take any wrong advantage of the Digital card, so if you are selling Digital card make sure to sell it to Genuine Person. cause as the person is creating a digital card with your reference if something wrong happens you must help the company in that situation.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- footer area start -->
<?php include "assets/common-includes/footer.php" ?>
<!-- footer area end -->
<!-- Scripts -->
<?php include "assets/common-includes/footer_includes.php" ?>
<script>
    function setDataModalValue(one,three,five,life,percent) {
        $('.inner_amount').html('<td><i class="fa fa-inr" aria-hidden="true"></i>'+one+'</td><td><i class="fa fa-inr" aria-hidden="true"></i>'+three+'</td><td><i class="fa fa-inr" aria-hidden="true"></i>'+five+'</td><td><i class="fa fa-inr" aria-hidden="true"></i>'+life+'</td>');
        $('.cust-table-header').html(percent+' Of Card Pricing');
    }
</script>
</body>
</html>