<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Bragbook</title>
    <?php include "assets/common-includes/header_includes.php" ?>
    <style>

        .btn {
            display: inline-block;
            margin-bottom: 0;
            font-weight: normal;
            text-align: center;
            vertical-align: middle;
            touch-action: manipulation;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            white-space: nowrap;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857;
            border-radius: 8px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            font-size: 16px;
            letter-spacing: 2px;
            text-decoration: none;
            text-transform: uppercase;
        }

        .btn-default {
            color: #094524;
            background-color: #a8bdb2;
            border-color: #ccc;
            transition: background-color 300ms ease;
            -webkit-transition: background-color 300ms ease;
        }

        .btn-inverse {
            color: #fff;
            font-weight: bold;
            background-color: #094524;
            border-color: #ccc;
            transition: background-color 300ms ease;
            -webkit-transition: background-color 300ms ease;
        }

        /* end buttons */
        /* Breadcrumbs */
        .breadcrumbs {
            list-style: none;
            margin: 0;
            padding: 0;
            background-color: #bbb;
            color: #888;
            font-weight: bold;
            display: flex;
            font-size: 14px;
            position: relative;
            cursor: not-allowed;
            border-radius: 15px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }
        .breadcrumbs .breadcrumb-item {
            padding-left: 30px;
        }
        .breadcrumbs .breadcrumb-item .number {
            box-sizing: content-box;
            display: block;
            background-color: #003333;
            color: #a8bdb2;
            width: 8px;
            height: 16px;
            border-radius: 15px;
            padding: 6px 10px;
            float: left;
            margin: 13px 0 0 10px;
        }
        .breadcrumbs .breadcrumb-item .label {
            display: block;
            padding: 20px 0 20px 10px;
            float: left;
        }
        .breadcrumbs .breadcrumb-item:first-child {
            padding-left: 10px;
        }
        .breadcrumbs .breadcrumb-item:after {
            content: "";
            display: block;
            width: 37px;
            height: 37px;
            -webkit-transform: rotate(130deg) skew(-10deg);
            transform: rotate(130deg) skew(-10deg);
            float: right;
            position: relative;
            top: 10px;
            left: 19px;
            border-top: 1px solid #003333;
            border-left: 1px solid #003333;
            background-color: #bbb;
        }
        .breadcrumbs .breadcrumb-item:last-child {
            flex: 1 0 auto;
        }
        .breadcrumbs .breadcrumb-item:last-child:after {
            content: "";
            border: 0;
            width: 0;
            height: 0;
            padding-right: 20px;
        }
        .breadcrumbs .breadcrumb-item.visited, .breadcrumbs .breadcrumb-item.visited:after {
            background-color: #a8bdb2;
            color: #094524;
            cursor: pointer;
        }
        .breadcrumbs .breadcrumb-item.visited + li {
            cursor: pointer;
        }
        .breadcrumbs .breadcrumb-item:hover, .breadcrumbs .breadcrumb-item:hover:after {
            background-color: #5e856f;
        }
        .breadcrumbs .breadcrumb-item.active, .breadcrumbs .breadcrumb-item.active::after {
            background-color: #003333;
            color: #a8bdb2;
            cursor: pointer;
        }
        .breadcrumbs .breadcrumb-item.active .number {
            background-color: #a8bdb2;
            color: #094524;
        }

        /* end breadcrumbs */
        /* layout and general */
        * {
            /*border: solid 1px rgba(0, 0, 0, .6);*/
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            box-sizing: border-box;
        }


        .navbar {
            grid-area: nav;
            -ms-display: grid;
            display: grid;
            grid-gap: 20px;
            grid-template-columns: auto 1fr 2fr;
            grid-template-rows: auto;
            grid-template-areas: 'navbarbrand navbarlinks navbaraccount';
            align-items: center;
            align-content: center;
        }
        .navbar .navbar-brand {
            grid-area: navbarbrand;
            -ms-display: grid;
            display: grid;
            grid-gap: 10px;
            grid-template-columns: auto auto;
            grid-template-areas: 'navbarlogo navbarhome';
            align-items: center;
        }
        .navbar .navbar-brand .navbar-logo {
            grid-area: navbarlogo;
        }
        .navbar .navbar-brand .navbar-home-link {
            grid-area: navbarhome;
        }
        .navbar .navbar-links-wrapper {
            grid-area: navbarlinks;
        }
        .navbar .navbar-account {
            grid-area: navbaraccount;
        }

        .nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav .tab-link {
            cursor: pointer;
        }

        .page-content {
            min-height: 95vh;
            grid-area: pagecontent;
            -ms-display: grid;
            display: grid;
            /* Default for pages */
            width: 100%;
            margin: 0 auto;
            grid-template-rows: auto auto 1fr;
            grid-template-areas: 'pagehead' 'main' 'pagefooter';
        }
        .page-content #mainSection {
            grid-area: main;
            padding-left: 10vw;
            padding-right: 10vw;
        }
        .page-content .page-header {
            grid-area: pagehead;
            padding-left: 10vw;
            padding-right: 10vw;
        }
        .page-content .page-footer {
            grid-area: pagefooter;
            padding-left: 10vw;
            padding-right: 10vw;
        }

        .site-footer {
            grid-area: sitefooter;
            -ms-display: grid;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-areas: 'copyinfo devinfo';
        }
        .site-footer .copy-info {
            grid-area: copyinfo;
        }
        .site-footer .dev-info {
            grid-area: devinfo;
            text-align: right;
        }

        .navbar {
            position: fixed;
            width: 100%;
            padding: 0 10px;
            height: 80px;
            background-color: rgba(168, 189, 178, 0.2);
            font-weight: bold;
            font-size: 1.2em;
        }
        .navbar a {
            color: #094524;
            text-decoration: none;
        }
        .navbar .navbar-brand .navbar-logo {
            width: 160px;
            height: 72px;
            padding: 5px;
            border-radius: 10px;
        }
        .navbar .navbar-brand .navbar-home-link {
            font-size: 1.4em;
        }
        .navbar .navbar-links li {
            padding-right: 10px;
            display: inline-block;
        }
        .navbar .navbar-account {
            justify-self: end;
        }

        .page-header {
            margin-top: 80px;
        }

        .site-footer {
            border-top: 1px solid #b9b9b9;
            margin-bottom: 0;
            padding: 10px;
        }

        /* end layout and general */
        /* From Boostrap */
        /* End From Boostrap */
        /* Page Specific */
        /* Grid Setup */
        #applyPage {
            grid-template-columns: repeat(12, 1fr);
            grid-template-areas: "s s s s s s s s s s s s" ". l l l l l l l l l l ." ". a a a a a a a a a a .";
        }
        #applyPage .breadcrumbs {
            grid-area: s;
        }
        #applyPage #listingTitle {
            grid-area: l;
        }
        #applyPage #applicationForm {
            grid-area: a;
            display: grid;
            grid-gap: .5em;
            grid-template-columns: repeat(2, 1fr) minmax(640px, 4fr) repeat(2, 1fr);
            grid-auto-rows: minmax(640px, 60vh);
        }
        #applyPage #applicationForm .tab-page.active {
            grid-column: 3;
            grid-row: 1;
        }
        #applyPage #applicationForm .tab-page.prev {
            grid-column: 1 / 3;
            grid-row: 1;
        }
        #applyPage #applicationForm .tab-page.next {
            grid-column: 4 / 6;
            grid-row: 1;
        }
        #applyPage #applicationForm .prev-arrow {
            grid-column: 1 / 3;
            grid-row: 1;
        }
        #applyPage #applicationForm .next-arrow {
            grid-column: 4 / 6;
            grid-row: 1;
        }

        /* end Grid Setup */
        /* Set the site nav bar to the top */
        .navbar {
            grid-row: 1;
            position: relative;
        }

        .application-question {
            margin-left: 10px;
            margin-bottom: .5em;
        }
        .application-question p {
            margin-bottom: .5em;
        }
        .application-question label {
            display: block;
            margin-left: 10px;
        }

        #applicationForm .tab-page {
            padding: 0 10px;
            overflow-y: auto;
            border-radius: 2px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
            display: none;
        }
        #applicationForm .tab-page.prev {
            text-align: right;
        }
        #applicationForm .tab-page.prev, #applicationForm .tab-page.next {
            display: block;
            opacity: .4;
            overflow-x: hidden;
        }
        #applicationForm .tab-page.prev p, #applicationForm .tab-page.prev .application-question, #applicationForm .tab-page.prev .review, #applicationForm .tab-page.prev input, #applicationForm .tab-page.next p, #applicationForm .tab-page.next .application-question, #applicationForm .tab-page.next .review, #applicationForm .tab-page.next input {
            display: none;
        }
        #applicationForm .tab-page.active {
            display: block;
        }
        #applicationForm .prev-arrow, #applicationForm .next-arrow {
            z-index: 999;
            background: linear-gradient(var(--grad-rot, 90deg), rgba(255, 255, 255, 0), white);
            margin-top: -1px;
            margin-bottom: -1px;
            overflow-y: hidden;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            align-content: center;
            font-size: 10em;
            font-weight: bold;
            color: rgba(9, 69, 36, 0.4);
            text-shadow: 2px 4px #a8bdb2;
            cursor: pointer;
        }
        #applicationForm .prev-arrow p, #applicationForm .next-arrow p {
            display: none;
        }
        #applicationForm .prev-arrow:hover, #applicationForm .next-arrow:hover {
            background: linear-gradient(var(--grad-rot, 90deg), rgba(9, 69, 36, 0), rgba(94, 133, 111, 0.2), 60%, #5e856f);
        }
        #applicationForm .prev-arrow:hover p, #applicationForm .next-arrow:hover p {
            display: block;
        }
        #applicationForm .prev-arrow {
            --grad-rot: -90deg;
            margin-left: -1px;
            padding-left: 10px;
        }
        #applicationForm .prev-arrow p {
            grid-column: 1 / 3;
        }
        #applicationForm .next-arrow {
            --grad-rot: 90deg;
            margin-right: -1px;
            padding-right: 10px;
            text-align: right;
        }
        #applicationForm .next-arrow p {
            grid-column: 3 / 5;
        }

        /* End Page Specific */
        /* Testing Modal */
        .overlay {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.6);
            pointer-events: none;
            opacity: 0;
            -webkit-transform: scale(0.5);
            transform: scale(0.5);
        }

        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 1001;
            -webkit-transform: translate(-50%, -50%) scale(0.5);
            transform: translate(-50%, -50%) scale(0.5);
            width: 100%;
            max-width: 640px;
            pointer-events: none;
            opacity: 0;
        }
        .modal .modal_content {
            display: block;
            padding: 30px;
            background: #fff;
        }

        .modal-toggle:checked ~ .overlay {
            pointer-events: auto;
            opacity: 1;
            -webkit-transform: scale(1);
            transform: scale(1);
            transition: opacity 0.5s ease, -webkit-transform 0.5s ease;
            transition: transform 0.5s ease, opacity 0.5s ease;
            transition: transform 0.5s ease, opacity 0.5s ease, -webkit-transform 0.5s ease;
        }
        .modal-toggle:checked ~ .modal {
            pointer-events: auto;
            opacity: 1;
            -webkit-transform: translate(-50%, -50%) scale(1);
            transform: translate(-50%, -50%) scale(1);
            transition: opacity 0.5s ease, -webkit-transform 0.5s ease;
            transition: transform 0.5s ease, opacity 0.5s ease;
            transition: transform 0.5s ease, opacity 0.5s ease, -webkit-transform 0.5s ease;
            transition-delay: 0.1s;
        }

        /* End Testing Modal */

    </style>

</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">

    <div>
        <main>
            <div class="page-content" id="applyPage">
                <ul class="breadcrumbs">
                    <li class="tab-link breadcrumb-item active visited" id="crumb1">
                        <span class="number">1</span>
                        <span class="label">General</span>
                    </li>
                    <li class="tab-link breadcrumb-item" id="crumb2">
                        <span class="number">2</span>
                        <span class="label">Eligibility</span>
                    </li>
                    <li class="tab-link breadcrumb-item" id="crumb3">
                        <span class="number">3</span>
                        <span class="label">Questions</span>
                    </li>
                    <li class="tab-link breadcrumb-item" id="crumb4">
                        <span class="number">4</span>
                        <span class="label">Required Documents</span>
                    </li>
                    <li class="tab-link breadcrumb-item" id="crumb5">
                        <span class="number">5</span>
                        <span class="label">Additional Documents</span>
                    </li>
                    <li class="tab-link breadcrumb-item" id="crumb6">
                        <span class="number">6</span>
                        <span class="label">Review</span>
                    </li>
                </ul>

            </div>

        </main>
    </div>


    <div class="clearfix">

        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Add Services</h2>
                    <div class="col-xs-12 col-sm-6 align-right">
                        <div class="switch panel-switch-btn">
                            <!--<span class="m-r-10 font-12">REAL TIME</span>-->
                            <label>OFF<input type="checkbox" id="realtime" checked><span class="lever switch-col-cyan"></span>ON</label>
                        </div>
                    </div>
                </div>
                <div class="body">
                    <form id="form_validation" method="POST" action="">
                        <div>
                            <label class="form-label">Upload Image</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <!--<asp:FileUpload ID="fileupload_cat_img" CssClass="form-control" runat="server" />-->
                                    <input type="file" name="fileupload_cat_img" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Name</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->
                                    <input name="txt_category_name" class="form-control" placeholder="Name Of Service">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Description</label>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <!-- <asp:TextBox ID="txt_category_name" CssClass="form-control" placeholder="Name" runat="server"></asp:TextBox>-->

                                    <textarea name="txt_category_address" rows="4" cols="50" class="form-control" placeholder="Please Enter Service Description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="form-group form_inline">
                                <div>
                                    <!--   <asp:Button ID="btn_save" runat="server" Text="Save" CssClass="btn btn-primary waves-effect" />-->
                                    <input value="Add" type="submit" name="btn_save" class="btn btn-primary waves-effect">
                                </div>
                                &nbsp;&nbsp;
                                <div>
                                    <!--<asp:Button ID="btn_add_reset" runat="server" Text="Reset" CssClass="btn btn-success waves-effect" />-->
                                    <input type="reset" name="btn_-add-reset" class="btn btn-primary waves-effect">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
            <div class="row">
                <div class="card">
                    <div class="header">
                        <h2>Manage Service <span class="badge">1</span>
                        </h2>
                    </div>
                    <div class="freelancer_search_box">
                        <ul class="profile-ul">
                            <h4>Filter</h4>
                            <li class="li_event">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <!--<asp:TextBox ID="txt_name" class="form-control" runat="server" placeholder="Name"></asp:TextBox>-->
                                        <input name="txt_name" class="form-control" placeholder="Name">
                                    </div>
                                </div>
                            </li>


                            <li class="li_event">

                                <!--<asp:Button ID="btn_search" runat="server" Text="Search" CssClass="btn btn-primary"/>-->
                                <input type="submit" value="Search" name="btn_search" class="btn btn-primary waves-effect">
                                &nbsp;&nbsp;
                                <!--<asp:Button ID="btn_search_reset" runat="server" Text="Search" CssClass="btn btn-danger"/>-->
                                <input type="reset" value="reset" name="btn_-add-reset" class="btn btn-primary waves-effect">

                        </ul>
                    </div>
                    <div class="body table-responsive table_scroll">
                        <table class="table table-condensed table-bordered table-striped">
                            <thead>
                            <tr class="back-color">
                                <th>IMAGE</th>
                                <th>NAME</th>
                                <th>DESCRIPTION</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            <td>
                                <img src="assets/images/image-gallery/thumb/thumb-11.jpg" style="width: 60px" /></td>
                            <td>Software Testing</td>
                            <td>We Test Your Software for minimum error.We Can Also Do Automation Testing.</td>

                            <td>
                                <ul class="header-dropdown m-r--5">
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                           role="button" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li><a href="javascript:void(0);"><i class="fas fa-edit"></i>&nbsp;&nbsp;Edit</a></li>
                                            <li><a href="javascript:void(0);"><i class="fas fa-trash-alt"></i>&nbsp;&nbsp;Delete</a></li>
                                            <li><a href="javascript:void(0);"><i class="fas fa-upload"></i>&nbsp;&nbsp;Active</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<?php include "assets/common-includes/footer_includes.php" ?>

</body>
</html>