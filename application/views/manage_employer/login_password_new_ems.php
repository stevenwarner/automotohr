<div class="main jsmaincontent">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?> 
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                   
                    </div>
                    <?php if($employee['access_level'] == 'Employee' || $employee['access_level'] == 'Admin'){?>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo base_url('my_profile')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-pencil"></i> My Profile</a>
                        </div>
                    <?php }?>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>               
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>
                <div class="job-title-text">                
                    <p>You can update password. Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                </div>
                <div class="form-wrp">
                    <form method="POST" enctype="multipart/form-data" action="<?php echo current_url(); ?>" id ="loginform">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>E-Mail: <span>*</span></label>
                                    <input type="email" class="form-control" name="email" value="<?php echo set_value('email', $employer['email']);?>">
                                    <?php echo form_error('email'); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Username: <span>*</span></label>
                                    <input type="text" class="form-control" name="username" value="<?php echo set_value('username', $employer['username']); ?>">
                                    <?php echo form_error('username'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>New Password: <span>*</span></label>
                                    <input type="text" class="form-control" type="password" name="password" id="password" onkeyup="passwordStrength(this.value)">
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
                                    <input type="text" class="form-control" type="password" name="cpassword" id="cpassword">
                                </div>
                            </div>
                        </div>
                        <div class="btn-wrp full-width text-right">
                            <input type="hidden" name="id" value="<?php echo $employer['sid']; ?>">
                            <a class="btn btn-black margin-right" onClick="document.location.href = '<?= base_url('my_profile') ?>'">cancel</a>
                            <input class="btn btn-info" value="Update" type="submit">
                        </div>
                    </form>
                </div>
            </div>
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            <!-- </div> -->
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

    $(function () {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        
        $("#loginform").validate({
            ignore: ":hidden:not(select)",
            rules: {
                username: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\-]+$/
                },
                email: {
                    required: true,
                    email: true
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
                email: {
                    required: 'Please provide Valid email'
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
    });
</script>
<style>
    .form-control[disabled], fieldset[disabled] .form-control {
        background: lightgray;
    }
</style>