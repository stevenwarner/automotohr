<?php
$affiliate_user_sid = '';
$user_name = '';
if (isset($affiliate_user) && !empty($affiliate_user)) {
    $affiliate_user_sid = (!empty($affiliate_user['sid'])) ? $affiliate_user['sid'] : '';;
    $user_name = (!empty($affiliate_user['username'])) ? $affiliate_user['username'] : '';
} ?>

<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left"><?= $title; ?></h1>
                    <div class="btn-panel float-right">
                        <a href="<?= base_url() ?>dashboard" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                    </div>
                </div>
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="form-wrp">
                    <form id="form_update_login_credentials" method="POST" autocomplete="off">
                        <input type="hidden" id="perform_action" name="perform_action" value="update_login_password" />
                        <input type="hidden" id="user_sid" name="user_sid" value="<?= $user_id; ?>" />
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Username: <span>*</span></label>
                                    <input type="text" id="username" class="form-control" name="username" value="<?php echo $user_name;?>">
                                    <?php echo form_error('username'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>New Password: <span>*</span></label>
                                    <input class="form-control" type="password" name="password" id="password" onkeyup="passwordStrength(this.value)">
                                    <?php echo form_error('password'); ?>
                                </div>                       
                                <div class="password-trength-wrp">                          
                                    <div id="passwordStrength" >
                                        <div class='pass0 strength0'></div>
                                        <div class='pass1 strength0'></div>
                                        <div class='pass1 strength0'></div>
                                        <div class='pass1 strength0'></div>
                                    </div>                                  
                                    <div class="passwordDescription" id="passwordDescription">Password not entered</div>
                                </div>
                            </div>                                                        
                        </div>      
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Confirm Password: <span>*</span></label>
                                    <input class="form-control" type="password" name="cpassword" id="cpassword">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                            <input id="update_credentials_submit" class="btn btn-primary" type="button" value="Update"> 
                            <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>

<script type="text/javascript">
    
    function passwordStrength(password) {
        var desc = new Array();
        
        if (password.length == 0) {
            desc[0] = "Password Not Entered";
        } else {
            desc[0] = "Very Weak";
        }
        
        desc[1] = "Very Weak";
        desc[2] = "Not secure enough";
        desc[3] = "Fair";
        desc[4] = "Strong";
        desc[5] = "Very Strong";
        var toggle_class = new Array();
        
        if (password.length == 0) {
            toggle_class[0] = "<div class='pass0 strength0'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        } else {
            toggle_class[0] = "<div class='pass0 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength0'></div>";
        }
        
        toggle_class[1] = "<div class='pass0 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength0'></div>";
        toggle_class[2] = "<div class='pass0 strength2'></div><div class='pass1 strength2'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        toggle_class[3] = "<div class='pass0 strength3'></div><div class='pass1 strength3'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        toggle_class[4] = "<div class='pass0 strength4'></div><div class='pass1 strength4'></div><div class='pass1 strength4'></div><div class='pass1 strength0'></div>";
        toggle_class[5] = "<div class='pass0 strength5'></div><div class='pass1 strength5'></div><div class='pass1 strength5'></div><div class='pass1 strength5'></div>";
        var score = 0;
        //if password bigger than 6 give 1 point
        if (password.length > 6)
            score++;
        //if password has both lower and uppercase characters give 1 point  
        if ((password.match(/[a-z]/)) && (password.match(/[A-Z]/)))
            score++;
        //if password has at least one number give 1 point
        if (password.match(/\d+/))
            score++;
        //if password has at least one special caracther give 1 point
        if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))
            score++;
        //if password bigger than 12 give another 1 point
        if (password.length > 10)
            score++;
        document.getElementById("passwordDescription").innerHTML = desc[score];
        document.getElementById("passwordStrength").innerHTML = toggle_class[score];
    }
    $('#update_credentials_submit').click(function(){
        $('#username-error').remove();
        var user_name = $('#username').val();
        var characterReg = /^\s*[a-zA-Z0-9,\s]+\s*$/;
        if (!characterReg.test(user_name)) {
            $('#username').after('<label id="username-error" class="error" for="username">No special characters allowed.</label>');
        } else {
            $("#form_update_login_credentials").submit();
        }
        
    });
    $('#username').keyup(function() {
        $('#username-error').remove();
        var inputVal = $(this).val();
        var characterReg = /^\s*[a-zA-Z0-9,\s]+\s*$/;
        if(!characterReg.test(inputVal)) {
            $(this).after('<label id="username-error" class="error" for="username">No special characters allowed.</label>');
        }
    });
    
    
    $("#form_update_login_credentials").validate({
        ignore: [],
        rules: {
            username: {
                required: true,
            },    
            password: {
                minlength: 6
            },
            cpassword: {
                minlength: 6,
                equalTo: "#password"
            }
        },
        messages: {
            username: {
                required: 'Username is required',
                pattern: 'Please provide valid username'
            },
            password: {
                minlength: 'Password should be 6 digits'
            },
            cpassword: {
                minlength: 'Confirm Password should be 6 digits',
                equalTo: "Password doesn't match"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    }); 
     
</script>