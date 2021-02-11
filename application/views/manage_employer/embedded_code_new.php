<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php echo $title; ?></span>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <?php echo form_open('', array('id'=>'loginform')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100 autoheight">
                                                <?php echo form_label('Google Analytics Tracking ID','embedded_code'); ?>
                                                <?php echo form_input('embedded_code', set_value('embedded_code', $company['embedded_code']), 'class="invoice-fields"')?>
                                                <?php echo form_error('embedded_code'); ?>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                    <span class="help_text">
                                                        <p>You can sign up for Google Analytics to track the activity on your Company Career Pages.</p>
                                                        <p>For more details: <a class="more-detail-link" href="https://support.google.com/analytics/?hl=en#topic=3544906" target="_blank">Click Here</a></p>
                                                        <p>Simply Copy and Paste the Tracking ID from Google Analytics into the Tracking ID Field and press the "Save" button.</p>
                                                    </span>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <input type="hidden" name="id" value="<?php echo $company['user_sid'];?>">
                                                <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn">
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('my_settings') ?>'" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>