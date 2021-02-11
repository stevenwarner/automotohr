<div class="main-content">
    <div class="container-fluid">
        <div class="row">					
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <header class="hr-header-sec">
                    <h2><?php echo $title; ?></h2>
                </header>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">				
                <?php $this->load->view('manage_employer/profile_left_menu'); ?>
            </div>
            <?php echo form_open_multipart('', array('id' => 'usermanagement')); ?>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="form-col-100">
                    <div class="info-text">
                        <p><?php echo STORE_NAME; ?> gives you the facility to invite unlimited colleagues to the system. You can also set their access level as per their rank in your company.</p>
                    </div>
                </div>
                <div class="universal-form-style">
                    <ul>
                        <li class="form-col-50-left">
                            <label>First Name <span class="hr-required">*</span></label>
                            <input type="first_name" name="first_name" value="<?php if(isset($formpost['first_name'])){ echo $formpost['first_name']; } ?>" class="invoice-fields" required>
                            <?php echo form_error('first_name'); ?>
                        </li>
                        <li class="form-col-50-right">
                            <label>Last Name <span class="hr-required">*</span></label>
                            <input type="last_name" name="last_name" value="<?php if(isset($formpost['last_name'])){ echo $formpost['last_name']; } ?>" class="invoice-fields" required>
                            <?php echo form_error('last_name'); ?>
                        </li>
                        <li class="form-col-50-left">
                            <label>E-Mail <span class="hr-required">*</span></label>
                            <input type="email" name="email" value="<?php if(isset($formpost['email'])){ echo $formpost['email']; } ?>" class="invoice-fields" required>
                            <?php echo form_error('email'); ?>
                        </li>
                        <li class="form-col-50-right">
                            <label>username <span class="hr-required">*</span></label>
                            <input type="username" name="username" value="<?php if(isset($formpost['username'])){ echo $formpost['username']; } ?>" class="invoice-fields" required>
                            <?php echo form_error('username'); ?>
                        </li>
                        <li class="form-col-50-left">
                            <label>Password <span class="hr-required">*</span></label>
                            <input type="password" name="password" value="<?php if(isset($formpost['password'])){ echo $formpost['password']; } ?>" class="invoice-fields" required>
                            <?php echo form_error('password'); ?>
                        </li>
                        <li class="form-col-50-right">
                            <label>Access Level <span class="hr-required">*</span></label>
                            <div class="hr-select-dropdown">
                                <select class="invoice-fields" name="access_level" required>
                                    <?php foreach ($access_level as $al) { ?>
                                            <option value="<?php echo $al; ?>" 
                                            <?php if(isset($formpost['access_level']) && $al == $formpost['access_level']) { echo "selected"; } ?>> 
                                                <?php echo $al; ?>
                                            </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php echo form_error('access_level'); ?>
                        </li>                 
                        <li class="form-col-100">
                            <div class="btn-wrp">
                                <input class="reg-btn" type="submit" onclick="validate_form()" value="Send Invite">
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/additional-methods.min.js"></script>
<script>
    function validate_form() {
        $("#usermanagement").validate({
            ignore: ":hidden:not(select)",
             rules: {
                first_name:     {
                                    pattern: /^[a-zA-Z0-9\- ]+$/
                                },
                last_name:      {
                                    pattern: /^[a-zA-Z0-9\- ]+$/
                                },
                email:          {
                                    email:true
                                },
                username:       {
                                    pattern: /^[a-zA-Z0-9\- ]+$/
                                },
                password:       {
                                    required: true
                                },
                access_level:   {
                                    required: true
                                },
                },
            messages: {
                first_name:     {
                                    required: 'First Name is required',
                                    pattern:  'Alphabits and Numbers only'
                                },
                last_name:      {
                                    required: 'Last Name is required',
                                    pattern:  'Alphabits and Numbers only'
                                },
                email:          {
                                    required: 'E-Mail Address is required',
                                    email:  'Please provide valid E-Mail'
                                },
                username:       {
                                    required: 'Username is required',
                                    pattern:  'Alphabits and Numbers only'
                                },
                password:       {
                                    required: 'Password is required'
                                },
                access_level:   {
                                    required: 'Access Level is required',
                                },
                },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
</script>