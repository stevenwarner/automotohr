<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="hr-login-section">
                    <div class="executive-login-screen-heading"><h1><?php echo $page_title; ?></h1></div>
                    <div class="hr-box-wrap">					
                        <div class="hr-login-box">
                            <h4 class="password_forgot">Please generate a secure password for your Login ID: <b><?php echo $username; ?></b></h4><br>
                            <?php   if($this->session->flashdata('message')){ ?>
                                        <div class="flash_error_message">
                                            <div class="alert alert-info alert-dismissible" role="alert">
                                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              <?php echo $this->session->flashdata('message');?>
                                            </div>
                                        </div>
                            <?php   } ?> 
                            <form action="" class="form-horizontal" method="post" id="generate-pass">
                                <ul>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input name="password" id="password" value="" class="form-fileds" placeholder="Password" type="password">
                                            <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                            <?php echo form_error('password'); ?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input name="cpassword" id="cpassword" value="" class="form-fileds" placeholder="Confirm Password" type="password">
                                            <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                            <?php echo form_error('cpassword'); ?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input name="submit" value="Generate Password" class="hr-login-btn" type="submit">
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="login-copyright">&copy;<?php echo date('Y') .' '. STORE_NAME;?>. All Rights Reserved.</div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <?php $this->load->view('main/demobuttons'); ?>
        </div>
    </div>
</div> 
<?php //echo validation_errors(); ?>
<script>
$(document).ready(function() {
    $("#generate-pass").validate({
        rules: {
            password: {
                required: true,
                minlength: 6
            } ,

            cpassword: {
                equalTo: "#password",
                minlength: 6
            }
        },
        messages:{
            password: {
                required:"Password is required of min 6 digits"
            }
        }
    });
});
</script>