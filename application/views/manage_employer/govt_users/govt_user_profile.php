<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">				
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?><!-- profile_left_menu_company -->
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">Goverment Agent Credentials</span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div>
                            <div class="alert alert-info alert-dismissible" role="alert" style="display: none;">
                              <span id="submission-response"></span>       
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="job-title-text">                
                            <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory.</p>
                        </div>
                    </div>
                    <div class="form-wrp">
                        <?php echo form_open_multipart(base_url('govt_user/create_update'), array('id' => 'govt_user_form', )); ?>
                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php echo form_label('Username <span class="hr-required">*</span>', 'Username'); ?>
                                <?php echo form_input('username', set_value('username', isset($govt_user['username']) ? $govt_user['username'] : "" ), 'class="form-control"'); ?>
                                <?php echo form_error('username'); ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php echo form_label('Password <span class="hr-required">*</span>', 'Password'); ?>
                                <?php echo form_input('password', set_value('password', isset($govt_user['password']) ? $govt_user['password'] : "" ), 'class="form-control"'); ?>
                                <?php echo form_error('password'); ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <?php echo form_label('Email <span class="hr-required">*</span>', 'Email'); ?>
                                <?php echo form_input('email', set_value('email', isset($govt_user['email']) ? $govt_user['email'] : "" ), 'class="form-control"'); ?>
                                <?php echo form_error('email'); ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="">
                                <?php echo form_label('Note', 'note'); ?>
                                <textarea name="note" class="form-control" style="min-height: 100px; max-height: 100px;" rows="10"><?php echo set_value('note', isset($govt_user['note']) ? $govt_user['note'] : "" ); ?></textarea>
                                <?php echo form_error('note'); ?>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <br />
                            <div class="form-group">
                                <button type="button" id="govt_user_save" class="submit-btn ">Save</button>
                                <button type="button" id="govt_user_send_email" class="submit-btn ">Send Credential Email</button>
                                <input type="hidden" name="sid" value="<?= isset($govt_user['sid']) ? $govt_user['sid'] : "" ?>">
                            </div>    
                        </div>
                        <?php echo form_close(); ?>
                            
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="my_loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we are processing your request
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {
        $('#govt_user_save').click(function() {
            submitForm("govt_user_save");
        });
        $('#govt_user_send_email').click(function() {
            submitForm("govt_user_send_email");
        });
        <?php
            if(!isset($govt_user['sid'])){
        ?>
                $("#govt_user_send_email").css("display","none");
        <?php } ?>
    });
    function submitForm(submit_type){
        $("#submission-response").html('').parent().hide();
        var form = $("#govt_user_form");
        var url = form.attr('action');
        validate_form();
        var data = form.serialize()+"&submit_type="+submit_type;
        
        if(! form.valid()) return false;
        loader();
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize()+"&submit_type="+submit_type, // serializes the form's elements.
            success: function(data)
            {
                loader('hide');
                var data = JSON.parse(data)
                if(data.sid > 0){
                    $("#govt_user_send_email").show();
                    $("input[name=sid]").val(data.sid);
                }
                
                if($.isEmptyObject(data.error)){
                    $("#submission-response").html(data.success).parent().show();
                }else{
                    $("#submission-response").html(data.error).parent().show();
                }
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }
        });
    }
    function validate_form() {
            $("#govt_user_form").validate({
                rules: {
                    username: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\- ]+$/
                    },
                    password: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\- .]+$/
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    username: {
                        required: 'Username is required',
                        pattern: 'Letters, numbers, and dashes only please'
                    },
                    password: {
                        required: 'Password is required',
                        pattern: 'Letters, numbers, and dashes only please'
                    },
                    email: {
                        required: 'Email is required',
                        pattern: 'Letters, numbers, and dashes only please'
                    }
                }
//                submitHandler: function (form) {
//                    form.submit();
//                }
            });
        }

        function loader(isShow){
            if(isShow === undefined) $('#my_loader').show();
            else $('#my_loader').hide();
        }

        loader('hide');
</script>

<style>
    .hr-required{ color: #cc0000; font-weight: bolder; }
</style>