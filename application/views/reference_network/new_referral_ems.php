<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                 <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('my_referral_network'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-users"></i> My Referral Network</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div> 
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile">
                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                    <?php echo $title; ?></h2>
                </div>
                <div class="job-title-text">                
                    <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                </div>
                <div class="form-wrp">
                    <form id ="new_referral" method="POST" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php echo form_label('Refer This Job: <span>*</span>', 'job_sid');?>
                                    <div class="hr-select-dropdown">
                                        <?php echo form_dropdown(array('class' => 'form-control', 'id' => 'job_sid', 'name' => 'job_sid'), $jobs, array(), set_select('job_sid')); ?>
                                    </div>
                                    <?php echo form_error('job_sid'); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <?php echo form_label('Refer To Name: <span>*</span>', 'referred_to');?>
                                    <?php echo form_input(array('class' => 'form-control', 'id' => 'referred_to', 'name' => 'referred_to'), set_value('referred_to')); ?>
                                    <?php echo form_error('referred_to'); ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <?php echo form_label('Email Address: <span>*</span>', 'reference_email');?>
                                    <?php echo form_input(array('class' => 'form-control', 'id' => 'reference_email', 'name' => 'reference_email'), set_value('reference_email')); ?>
                                    <?php echo form_error('reference_email'); ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <?php echo form_label('Personal Message', 'personal_message');?>
                                    <?php echo form_textarea(array('class' => 'form-control', 'id' => 'personal_message', 'name' => 'personal_message'), set_value('personal_message')); ?>
                                    <?php echo form_error('personal_message'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="btn-wrp full-width text-right">
                            <input type="hidden" name="id" value="<?php echo $employer['sid']; ?>">
                            <a class="btn btn-black margin-right" href="<?php echo base_url('my_referral_network'); ?>" >Cancel</a>
                            <?php echo form_submit(array('value' => 'Send', 'class' => 'btn btn-info', 'type' => 'submit'));?>
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
<script type="text/javascript">
    $(function () {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });
        
        $("#new_referral").validate({
            ignore: ":hidden:not(select)",
            rules: {
                job_sid: {
                    required: true
                },
                reference_email: {
                    required: true,
                    email: true
                },
                referred_to: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                }
            },
            messages: {
                job_sid: {
                    required: 'Please select a Job'
                },
                reference_email: {
                    required: 'Please provide valid E-mail Address'
                },
                referred_to: {
                    required: 'Please provide Referred To Name',
                    pattern: 'Please provide valid Referred To Name'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });
</script>