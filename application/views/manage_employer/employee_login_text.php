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
                            <?php echo form_open('', array('id' => 'loginlink')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2 enable-social-profiles">
                                        <ul>
                                            <li class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <label class="control control--checkbox">
                                                            Enable Login Link
                                                            <input class="select-domain" type="checkbox" id="employee_login_text_status" name="employee_login_text_status" value="1" <?php echo ($company['employee_login_text_status'] == 1 ? ' checked="checked" ' : ''); ?> />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                        <input type="text" id="employee_login_text" name="employee_login_text" value="<?= $company['employee_login_text']?>" class="invoice-fields">
<!--                                                        --><?php //echo form_input('employee_login_text', set_value('employee_login_text', $company['employee_login_text']), 'class="invoice-fields"'); ?>
                                                    </div>   
                                                </div>                     
                                            </li>
                                            <li class="form-col-100">
                                                <div class="btn-wrp">
                                                    <input type="submit" value="Save" class="submit-btn">
                                                    <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('my_settings') ?>'" />
                                                </div>
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

<script>
    $(document).ready(function(){
        $('#loginlink').submit(function(){
            if($('#employee_login_text_status').is(":checked") && $('#employee_login_text').val()==''){
                alertify.error('Footer Login Text is required');
                return false;
            }
        });
    })
</script>