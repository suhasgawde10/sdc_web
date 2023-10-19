<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Bragbook</title>
    <?php include "assets/common-includes/header_includes.php" ?>
</head>
<body>
<?php include "assets/common-includes/header.php" ?>
<?php include "assets/common-includes/left_menu.php" ?>
<section class="content">
<div class="col-xs-8 col-sm-9">
    <div class="card card_height">
        <div class="body">
            <div>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#change_password_settings" aria-controls="home" role="tab" data-toggle="tab">Profile Setting</a></li>
                </ul>

                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane fade in active" id="change_password_settings">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="OldPassword" class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-9">
                                    <div class="form-line">
                                        <!--<asp:TextBox ID="txt_name" CssClass="form-control" runat="server" placeholder="Fahim"></asp:TextBox>-->
                                        <input name="txt_name" type="text" class="form-control" placeholder="Fahim">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="NewPassword" class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-9">
                                    <div class="form-line">
                                        <!--<asp:TextBox ID="txt_email" CssClass="form-control" runat="server" placeholder="fahim@gmail.com"></asp:TextBox>-->
                                        <input name="txt_email" type="email" class="form-control" placeholder="fahim@gmail.com">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="NewPasswordConfirm" class="col-sm-3 control-label">Contact Number</label>
                                <div class="col-sm-9">
                                    <div class="form-line">
                                       <!-- <asp:TextBox ID="txt_contact" CssClass="form-control" runat="server" placeholder="9874568541"></asp:TextBox>-->
                                        <input name="txt_contact" type="text" class="form-control" placeholder="9874568541">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="form-group form_inline">
                                    <div>
                                        <!--<asp:Button ID="btn_submit" runat="server" Text="Update" CssClass="btn btn-primary waves-effect" />-->
                                        <input name="btn_update" type="submit" class="btn btn-primary waves-effect" >
                                    </div>
                                    &nbsp;&nbsp;
                                    <div>
                                        <!--<asp:Button ID="btn_reset" runat="server" Text="Reset" CssClass="btn btn-success waves-effect" />-->
                                        <input name="btn_reset" type="reset" class="btn btn-primary waves-effect" >
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </section>
<?php include "assets/common-includes/footer_includes.php" ?>
</body>
</html>