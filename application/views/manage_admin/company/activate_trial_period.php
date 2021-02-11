<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>

                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-10 col-sm-12 col-md-12 col-lg-12">
                                                <div class="edit-template-from-main">
                                                    <div class="heading-title">
                                                        <h1 class="page-title">Company Information</h1>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-xs-4">Name</label>
                                                        <div class="col-xs-8">
                                                            <span><?php echo $company_info['CompanyName']; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-xs-4">Address</label>
                                                        <div  class="col-xs-8">
                                                            <?php echo ucwords($company_info['Location_Address']) . ', ' .  ucwords($company_info['Location_City']) . ', ' . ucwords($company_info['state_name']) . ', ' . ucwords($company_info['Location_ZipCode']) . ', ' . ucwords($company_info['country_name']); ?>
                                                        </div>
                                                    </div>
                                                    <?php if(!empty($trial_data) && $trial_data['status'] == 'enabled'){ ?>
                                                        <div class="row">
                                                            <label class="col-xs-4">Trial Expiration Date</label>
                                                            <div  class="col-xs-8">
                                                                <?php //echo substr($trial_data['end_date'], 0, 10); ?>
                                                                <?php echo date('m/d/Y', strtotime(str_replace('-', '/', $trial_data['end_date'])))?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label class="col-xs-4">Status</label>
                                                            <div  class="col-xs-8">
                                                                <?php //echo substr($trial_data['end_date'], 0, 10); ?>
                                                                <?php echo ucwords($trial_data['status'])?>
                                                            </div>
                                                        </div>
                                                        <?php if($trial_data['status'] == 'enabled') { ?>
                                                            <div class="row">
                                                                <div class="col-xs-4"></div>
                                                                <div class="col-xs-8">
                                                                    <form id="form_end_trial_period" enctype="multipart/form-data" method="post">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="end_trial" />
                                                                        <input type="hidden" id="sid" name="sid" value="<?php echo $trial_data['sid']?>" />
                                                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $trial_data['company_sid']?>" />

                                                                        <button type="button" class="hr-delete-btn" onclick="fEndTrialPeriod();">End Trial Period Now</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <div class="heading-title">
                                                        <h1 class="page-title">Trial Settings</h1>
                                                    </div>
                                                    <form id="form_activate_trial_period" name="form_activate_trial_period" enctype="multipart/form-data" method="post" action="<?php base_url('manage_admin/companies/activate_trial_period'); ?>">
                                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />

                                                        <ul>
                                                            <li>
                                                                <label for="number_of_days">Number of Days <span class="hr-required">*</span></label>                                                    <div class="hr-fields-wrap">
                                                                    <input type="number" class="hr-form-fileds" value="30" name="number_of_days" id="number_of_days" min="0"  />
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label class="no-margin" for="number_of_days">Enable Facebook API</label>
                                                                <div class="hr-fields-wrap">
                                                                    <input type="checkbox" checked="checked" value="1" name="enable_facebook_api" id="enable_facebook_api"  />
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label class="no-margin" for="number_of_days">Enable Deluxe Theme</label>
                                                                <div class="hr-fields-wrap">
                                                                    <input type="checkbox" checked="checked" value="1" name="enable_deluxe_theme" id="enable_deluxe_theme"  />
                                                                </div>
                                                            </li>
                                                            <li><button type="button" class="search-btn" onclick="fActivateTrialPeriod();"><?php echo $submit_button_text; ?></button></li>
                                                        </ul>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function fEndTrialPeriod(){
        alertify.confirm('Are you sure?', 'Are you sure you want to Manually End Trial Period?',
            function () {
                //Ok
                $('#form_end_trial_period').submit();
            },
            function () {

            });
    }

    function fActivateTrialPeriod(){
        alertify.confirm('Are you sure?', 'Are you sure you want to activate trial period?',
            function () {
                //Ok
                $('#form_activate_trial_period').submit();
            },
            function () {

            });
    }

</script>