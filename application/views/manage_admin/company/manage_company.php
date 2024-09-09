<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Companies</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <h1 class="page-title">Training Session Status</h1> <img data-attr="<?= $company_info['sid']; ?>" data-key="<?= !$company_info['training_session_status'] ?>" id="training-status" src="<?= !$company_info['training_session_status'] ? base_url('assets/manage_admin/images/bulb-red.png') : base_url('assets/manage_admin/images/bulb-green.png'); ?>">
                                            <a href="<?php echo STORE_PROTOCOL_SSL . db_get_sub_domain($company_sid); ?>" class="site-btn pull-right" target="_blank">Career Website</a>

                                            <?php if ($show_trial_period_button == true) { ?>
                                                <a href="<?php echo base_url('manage_admin/companies/activate_trial_period/' . $company_info['sid']); ?>" class="site-btn pull-right"><?php echo $trial_button_text; ?></a>
                                            <?php } ?>

                                            <a href="<?php echo base_url('manage_admin/employers/add_employer/' . $company_sid) ?>" class="site-btn pull-right">Add Employer</a>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <article class="information-box">
                                                    <header class="hr-box-header">Company Details <a href="<?php echo base_url('manage_admin/companies/edit_company') . '/' . $company_sid; ?>" class="site-btn pull-right">Edit</a></header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <li>
                                                                    <label>Company Name</label>
                                                                    <div class="text">
                                                                        <?php echo ucwords($company_info['CompanyName']); ?>

                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Company Type</label>
                                                                    <div class="text">
                                                                        <?php echo ($company_info['is_paid'] == 1) ? 'Main' : 'Secondary'; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Company Status</label>
                                                                    <div class="text">
                                                                        <?php if ($company_info['company_status'] == 0) { ?>
                                                                            <button class="btn btn-danger btn-xs" title="The store is closed." placement="top">
                                                                                Closed
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <button class="btn btn-success btn-xs" title="The store is open." placement="top">
                                                                                Open
                                                                            </button>
                                                                        <?php } ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Address</label>
                                                                    <div class="text">
                                                                        <?php echo ucwords($company_info['Location_Address']) . ', ' . ucwords($company_info['Location_City']) . ', ' . ucwords($company_info['state_name']) . ', ' . ucwords($company_info['Location_ZipCode']) . ', ' . ucwords($company_info['country_name']); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Registration Date</label>
                                                                    <div class="text">
                                                                        <?php echo date('m-d-Y', strtotime(str_replace('-', '/', $company_info['registration_date']))); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Expiration Date</label>
                                                                    <div class="text">
                                                                        <?php echo date('m-d-Y', strtotime(str_replace('-', '/', $company_info['expiry_date']))); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Current Employees</label>
                                                                    <div class="text">
                                                                        <?php echo $company_info['number_of_employees']; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Current Applicants</label>
                                                                    <div class="text">
                                                                        <?php echo $company_info['number_of_applicants']; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Phone Number</label>
                                                                    <div class="text">
                                                                        <?= phonenumber_format(str_replace('(___) ___-____', 'N/A', $company_info['PhoneNumber'])); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Website</label>
                                                                    <div class="text">
                                                                        <?php echo $company_info['WebSite']; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Timezone</label>
                                                                    <div class="text">
                                                                        <?= isset($company_info['timezone']) ? $company_info['timezone'] : STORE_DEFAULT_TIMEZONE_ABBR; ?>
                                                                    </div>
                                                                </li>
                                                                <?php if ($company_info['has_job_approval_rights'] == 1) { ?>
                                                                    <li class="inclueded-state">
                                                                        <label>Jobs Approval</label>
                                                                        <div style="color:green;" class="text">
                                                                            Active
                                                                        </div>
                                                                    </li>
                                                                <?php } else { ?>
                                                                    <li class="exclueded-state">
                                                                        <label>Jobs Approval</label>
                                                                        <div style="color:red;" class="text">
                                                                            In-Active
                                                                        </div>
                                                                    </li>
                                                                <?php } ?>
                                                                <?php if ($company_info['has_applicant_approval_rights'] == 1) { ?>
                                                                    <li class="inclueded-state">
                                                                        <label>Applicant Approval</label>
                                                                        <div style="color:green;" class="text">
                                                                            Active
                                                                        </div>
                                                                    </li>
                                                                <?php } else { ?>
                                                                    <li class="exclueded-state">
                                                                        <label>Applicant Approval</label>
                                                                        <div style="color:red;" class="text">
                                                                            In-Active
                                                                        </div>
                                                                    </li>
                                                                <?php } ?>
                                                                <li>
                                                                    <label>Accounts Payable Contact Person</label>
                                                                    <div class="text">
                                                                        <?php echo ucwords($company_info['accounts_contact_person']); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Accounts Payable Contact Number</label>
                                                                    <div class="text">
                                                                        <?php echo ucwords($company_info['accounts_contact_number']); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Full Billing Address</label>
                                                                    <div class="text">
                                                                        <?php echo ucwords($company_info['full_billing_address']); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Company Payment Type</label>
                                                                    <div class="text">
                                                                        <?php echo $company_info['payment_type']; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Company Past Due</label>
                                                                    <div class="text">
                                                                        <?php echo $company_info['past_due']; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Development Fee</label>
                                                                    <div class="text">
                                                                        $ <?php echo number_format($company_info['development_fee'], 2, '.', ','); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Shift</label>
                                                                    <div class="text">
                                                                        <?php echo $company_info['user_shift_hours']; ?> hours <?php echo $company_info['user_shift_minutes']; ?> minutes
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                                <article class="information-box">
                                                    <header class="hr-box-header">
                                                        current packages
                                                        <a href="<?php echo base_url('manage_admin/companies/manage_packages') . '/' . $company_sid; ?>" class="site-btn pull-right">Packages</a>
                                                    </header>
                                                    <?php if (!empty($company_trial_period_detail) && $company_trial_period_detail['status'] == 'enabled') { ?>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        <label>Name</label>
                                                                        <div class="text">
                                                                            Trial Package
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Description</label>
                                                                        <div class="text">
                                                                            Fully Featured Trial Package of the Complete System.
                                                                        </div>
                                                                    </li>
                                                                    <li class="inclueded-state">
                                                                        <label>Facebook API</label>
                                                                        <div style="color:green;" class="text">
                                                                            Included
                                                                        </div>
                                                                    </li>
                                                                    <li class="inclueded-state">
                                                                        <label>Deluxe Theme</label>
                                                                        <div style="color:green;" class="text">
                                                                            Included
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Expiration date</label>
                                                                        <div class="text"><?php echo date('m-d-Y', strtotime(str_replace('-', '/', $company_trial_period_detail['end_date']))); ?></div>
                                                                    </li>
                                                                    <li>
                                                                        <label>No. Of Rooftops</label>
                                                                        <div class="text">Unlimited</div>
                                                                    </li>

                                                                    <?php if ($company_info['per_job_listing_charge'] == 1) { ?>
                                                                        <li class="inclueded-state">
                                                                            <label>Per job Listing charge</label>
                                                                            <div style="color:green;" class="text">
                                                                                <a href="javascript:;" id="change_per_job_status" data-status="0" data-attr="per_job_listing_charge" class="site-btn btn-sm btn-danger">De-Activate It</a>
                                                                            </div>
                                                                        </li>
                                                                    <?php   } else { ?>
                                                                        <li class="exclueded-state">
                                                                            <label>Per job Listing charge</label>
                                                                            <div style="color:red;" class="text">
                                                                                <a href="javascript:;" id="change_per_job_status" data-status="1" data-attr="per_job_listing_charge" class="site-btn btn-sm">Activate It</a>
                                                                            </div>
                                                                        </li>
                                                                    <?php   } ?>
                                                                    <?php if ($company_info['career_site_listings_only'] == 1) { ?>
                                                                        <li class="inclueded-state">
                                                                            <label>Career Site listings only</label>
                                                                            <div style="color:green;" class="text">
                                                                                <a href="javascript:;" id="change_per_job_status" data-status="0" data-attr="career_site_listings_only" class="site-btn btn-sm btn-danger">De-Activate It</a>
                                                                            </div>
                                                                        </li>
                                                                    <?php   } else { ?>
                                                                        <li class="exclueded-state">
                                                                            <label>Career Site listings only</label>
                                                                            <div style="color:red;" class="text">
                                                                                <a href="javascript:;" id="change_per_job_status" data-status="1" data-attr="career_site_listings_only" class="site-btn btn-sm">Activate It</a>
                                                                            </div>
                                                                        </li>
                                                                    <?php   } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <?php if ($company_info['account_package_sid'] > 0) { ?>
                                                            <div class="table-outer">
                                                                <div class="info-row">
                                                                    <ul>
                                                                        <li>
                                                                            <label>Name</label>
                                                                            <div class="text">
                                                                                <?php echo ucwords($company_info['package_name']); ?>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <label>Description</label>
                                                                            <div class="text">
                                                                                <?php echo ucwords($company_info['package_description']); ?>
                                                                            </div>
                                                                        </li>
                                                                        <?php if ($company_info['package_includes_facebook_api'] == 1) { ?>
                                                                            <li class="inclueded-state">
                                                                                <label>Facebook API</label>
                                                                                <div style="color:green;" class="text">
                                                                                    Included
                                                                                </div>
                                                                            </li>
                                                                        <?php } else { ?>
                                                                            <li class="exclueded-state">
                                                                                <label>Facebook API</label>
                                                                                <div style="color:red;" class="text">
                                                                                    Not Included
                                                                                </div>
                                                                            </li>
                                                                        <?php } ?>
                                                                        <?php if ($company_info['package_includes_deluxe_theme'] == 1) { ?>
                                                                            <li class="inclueded-state">
                                                                                <label>Deluxe Theme</label>
                                                                                <div style="color:green;" class="text">
                                                                                    Included
                                                                                </div>
                                                                            </li>
                                                                        <?php } else { ?>
                                                                            <li class="exclueded-state">
                                                                                <label>Deluxe Theme</label>
                                                                                <div style="color:red;" class="text">
                                                                                    Not Included
                                                                                </div>
                                                                            </li>
                                                                        <?php } ?>
                                                                        <li>
                                                                            <label>Expiration date</label>
                                                                            <div class="text"><?php echo date('m-d-Y', strtotime(str_replace('-', '/', $company_info['expiry_date']))); ?></div>
                                                                        </li>
                                                                        <li>
                                                                            <label>No. Of Rooftops</label>
                                                                            <div class="text"><?php echo $company_info['number_of_rooftops']; ?></div>
                                                                        </li>
                                                                        <?php if ($company_info['per_job_listing_charge'] == 1) { ?>
                                                                            <li class="inclueded-state">
                                                                                <label>Per job Listing charge</label>
                                                                                <div style="color:green;" class="text">
                                                                                    <a href="javascript:;" id="change_per_job_status" data-status="0" data-attr="per_job_listing_charge" class="site-btn btn-sm btn-danger">De-Activate It</a>
                                                                                </div>
                                                                            </li>
                                                                        <?php   } else { ?>
                                                                            <li class="exclueded-state">
                                                                                <label>Per job Listing charge</label>
                                                                                <div style="color:red;" class="text">
                                                                                    <a href="javascript:;" id="change_per_job_status" data-status="1" data-attr="per_job_listing_charge" class="site-btn btn-sm">Activate It</a>
                                                                                </div>
                                                                            </li>
                                                                        <?php   } ?>
                                                                        <?php if ($company_info['career_site_listings_only'] == 1) { ?>
                                                                            <li class="inclueded-state">
                                                                                <label>Career Site listings only</label>
                                                                                <div style="color:green;" class="text">
                                                                                    <a href="javascript:;" id="change_per_job_status" data-status="0" data-attr="career_site_listings_only" class="site-btn btn-sm btn-danger">De-Activate It</a>
                                                                                </div>
                                                                            </li>
                                                                        <?php   } else { ?>
                                                                            <li class="exclueded-state">
                                                                                <label>Career Site listings only</label>
                                                                                <div style="color:red;" class="text">
                                                                                    <a href="javascript:;" id="change_per_job_status" data-status="1" data-attr="career_site_listings_only" class="site-btn btn-sm">Activate It</a>
                                                                                </div>
                                                                            </li>
                                                                        <?php   } ?>
                                                                        <!-- <li>
                                                                            <label>Per job Listing charge</label>
                                                                            <div class="text">De-Active</div>
                                                                        </li>
                                                                        <li>
                                                                            <label>Career Site listings only</label>
                                                                            <div class="text">De-Active</div>
                                                                        </li>-->
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="table-outer">
                                                                <div class="info-row">
                                                                    <ul>
                                                                        <li class="text-center red"><b>No Package Assigned Yet</b></li>
                                                                        <?php if ($company_info['per_job_listing_charge'] == 1) { ?>
                                                                            <li class="inclueded-state">
                                                                                <label>Per job Listing charge</label>
                                                                                <div style="color:green;" class="text">
                                                                                    <a href="javascript:;" id="change_per_job_status" data-status="0" data-attr="per_job_listing_charge" class="site-btn btn-sm btn-danger">De-Activate It</a>
                                                                                </div>
                                                                            </li>
                                                                        <?php   } else { ?>
                                                                            <li class="exclueded-state">
                                                                                <label>Per job Listing charge</label>
                                                                                <div style="color:red;" class="text">
                                                                                    <a href="javascript:;" id="change_per_job_status" data-status="1" data-attr="per_job_listing_charge" class="site-btn btn-sm">Activate It</a>
                                                                                </div>
                                                                            </li>
                                                                        <?php   } ?>
                                                                        <?php if ($company_info['career_site_listings_only'] == 1) { ?>
                                                                            <li class="inclueded-state">
                                                                                <label>Career Site listings only</label>
                                                                                <div style="color:green;" class="text">
                                                                                    <a href="javascript:;" id="change_per_job_status" data-status="0" data-attr="career_site_listings_only" class="site-btn btn-sm btn-danger">De-Activate It</a>
                                                                                </div>
                                                                            </li>
                                                                        <?php   } else { ?>
                                                                            <li class="exclueded-state">
                                                                                <label>Career Site listings only</label>
                                                                                <div style="color:red;" class="text">
                                                                                    <a href="javascript:;" id="change_per_job_status" data-status="1" data-attr="career_site_listings_only" class="site-btn btn-sm">Activate It</a>
                                                                                </div>
                                                                            </li>
                                                                        <?php   } ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                                <article class="information-box">
                                                    <header class="hr-box-header">Company Status</header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <form enctype="multipart/form-data" method="post" action="<?php base_url('manage_admin/companies/manage_company/') ?>">
                                                                <input type="hidden" id="perform_action" name="perform_action" value="set_company_status" />
                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                <ul>
                                                                    <li class="lineheight">
                                                                        <div class="row">
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="company_status_active" name="company_status" value="1" <?php echo ($company_info['active'] == 1 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="company_status_active" style="color: green; float: none;">Active</label>
                                                                            </div>
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="company_status_inactive" name="company_status" value="0" <?php echo ($company_info['active'] == 0 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="company_status_inactive" style="color: red; float: none;">In Active</label>
                                                                            </div>
                                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                                <button type="submit" href="javascript:;" class="site-btn pull-right">update</button>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                                <!-- Sms Module -->
                                                <article class="information-box">
                                                    <header class="hr-box-header">SMS Module
                                                        <a href="<?php echo base_url('manage_admin/companies/manage_sms') . '/' . $company_sid; ?>" class="site-btn pull-right">Manage</a>
                                                    </header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <p><strong>Use SMS module</strong></p>
                                                            <form enctype="multipart/form-data" method="post" action="<?= base_url('manage_admin/companies/manage_company/' . ($company_sid) . ''); ?>">
                                                                <input type="hidden" id="perform_action" name="perform_action" value="set_company_sms_status" />
                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                <ul>
                                                                    <li class="lineheight">
                                                                        <div class="row">
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="sms_module_status_active" name="sms_module_status" value="1" <?php echo ($company_info['sms_module_status'] == 1 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="sms_module_status" style="color: green; float: none;">Active</label>
                                                                            </div>
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="sms_module_status_inactive" name="sms_module_status" value="0" <?php echo ($company_info['sms_module_status'] == 0 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="sms_module_status" style="color: red; float: none;">In Active</label>
                                                                            </div>
                                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                                <button type="submit" href="javascript:;" class="site-btn pull-right">update</button>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- Phone Pattern -->
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <p><strong>Use Phone pattern</strong></p>
                                                            <form enctype="multipart/form-data" method="post" action="<?= base_url('manage_admin/companies/manage_company/' . ($company_sid) . ''); ?>">
                                                                <input type="hidden" id="perform_action" name="perform_action" value="set_company_phone_pattern_status" />
                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                <ul>
                                                                    <li class="lineheight">
                                                                        <div class="row">
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="phone_pattern_module_active" name="phone_pattern_module" value="1" <?php echo ($company_info['phone_pattern_module'] == 1 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="sms_module_status" style="color: green; float: none;">Active</label>
                                                                            </div>
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="phone_pattern_module_inactive" name="phone_pattern_module" value="0" <?php echo ($company_info['phone_pattern_module'] == 0 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="sms_module_status" style="color: red; float: none;">In Active</label>
                                                                            </div>
                                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                                <button type="submit" href="javascript:;" class="site-btn pull-right">update</button>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>

                                                <!-- <article class="information-box">
                                                    <header class="hr-box-header">Career Site Powered By Logo</header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <form enctype="multipart/form-data" method="post" action="<?php //base_url('manage_admin/companies/manage_company/') 
                                                                                                                        ?>" >
                                                                <input type="hidden" id="perform_action" name="perform_action" value="set_career_site_powered_by" />
                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php //echo $company_sid; 
                                                                                                                                ?>" />
                                                                <ul>
                                                                    <li class="lineheight">
                                                                        <div class="row">
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="powered_by_active" name="footer_powered_by_logo" value="1" <?php //echo ($company_portal_status['footer_powered_by_logo'] == 1 ? 'checked="checked"' : '' ); 
                                                                                                                                                                    ?> />&nbsp;<label for="powered_by_active" style="color: green; float: none;">Enable</label>
                                                                            </div>
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="powered_by_inactive" name="footer_powered_by_logo" value="0" <?php //echo ($company_portal_status['footer_powered_by_logo'] == 0 ? 'checked="checked"' : '' ); 
                                                                                                                                                                        ?> />&nbsp;<label for="powered_by_inactive" style="color: red; float: none;">Disable</label>
                                                                            </div>
                                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                                <button type="submit" href="javascript:;" class="site-btn pull-right">update</button>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>-->

                                                <article class="information-box">
                                                    <header class="hr-box-header">Career Site & Employee Portal Footer Logo</header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <li class="<?php echo ($company_portal_status['footer_powered_by_logo'] == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                    <label>Footer Logo Status</label>
                                                                    <div style="<?php echo ($company_portal_status['footer_powered_by_logo'] == 1 ? 'color:green;' : 'color:red;'); ?>" class="text">
                                                                        <?php echo ($company_portal_status['footer_powered_by_logo'] == 1 ? 'Enabled' : 'Disabled'); ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <strong>Manage Footer Logo</strong>
                                                                    <a href="<?php echo base_url('manage_admin/companies/footer_logo/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>

                                                <article class="information-box">
                                                    <header class="hr-box-header">Document Management
                                                        <a class="site-btn pull-right" href="<?php echo base_url('manage_admin/documents/' . $company_sid); ?>">Manage</a>
                                                        <a class="site-btn pull-right" href="<?php echo base_url('manage_admin/documents/send/' . $company_sid); ?>">Send</a>
                                                    </header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <li>
                                                                    <label>Form Name</label>
                                                                    <div class="text text-center col-xs-6">
                                                                        <strong>Status</strong>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Credit Card Authorization</label>
                                                                    <div class="text text-center">
                                                                        <?php $cc_auth_status =  ($company_documents_status['cc_auth_status'] != '' ? $company_documents_status['cc_auth_status'] : 'Not Generated'); ?>
                                                                        <span class="<?php echo strtolower(str_replace(' ', '-', $cc_auth_status)); ?>">
                                                                            <?php echo ucwords(str_replace('-', ' ', $cc_auth_status)) ?>
                                                                            <?php if (strtolower($cc_auth_status) == 'not sent' || strtolower($cc_auth_status) == 'generated' || strtolower($cc_auth_status) == 'pre-filled' || strtolower($cc_auth_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($cc_auth_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </span>
                                                                    </div>
                                                                </li>

                                                                <li>
                                                                    <label>Payroll Credit Card Authorization</label>
                                                                    <div class="text text-center">
                                                                        <?php $payroll_cc_auth_status =  ($company_documents_status['payroll_cc_auth_status'] != '' ? $company_documents_status['payroll_cc_auth_status'] : 'Not Generated'); ?>
                                                                        <span class="<?php echo strtolower(str_replace(' ', '-', $payroll_cc_auth_status)); ?>">
                                                                            <?php echo ucwords(str_replace('-', ' ', $payroll_cc_auth_status)) ?>
                                                                            <?php if (strtolower($payroll_cc_auth_status) == 'not sent' || strtolower($payroll_cc_auth_status) == 'generated' || strtolower($payroll_cc_auth_status) == 'pre-filled' || strtolower($payroll_cc_auth_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($payroll_cc_auth_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>End User License Agreement</label>
                                                                    <div class="text text-center">
                                                                        <?php $eula_status = ($company_documents_status['eula_status'] != '' ? $company_documents_status['eula_status'] : 'Not Generated'); ?>
                                                                        <span class="<?php echo strtolower(str_replace(' ', '-', $eula_status)); ?>">
                                                                            <?php echo ucwords(str_replace('-', ' ', $eula_status)) ?>
                                                                            <?php if (strtolower($eula_status) == 'not sent' || strtolower($eula_status) == 'generated' || strtolower($eula_status) == 'pre-filled' || strtolower($eula_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($eula_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </span>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Company Contacts</label>
                                                                    <div class="text text-center">
                                                                        <?php $company_contacts_status = ($company_documents_status['company_contacts_status'] != '' ? $company_documents_status['company_contacts_status'] : 'Not Generated'); ?>
                                                                        <span class="<?php echo strtolower(str_replace(' ', '-', $company_contacts_status)); ?>">
                                                                            <?php echo ucwords(str_replace('-', ' ', $company_contacts_status)) ?>
                                                                            <?php if (strtolower($company_contacts_status) == 'not sent' || strtolower($company_contacts_status) == 'generated' || strtolower($company_contacts_status) == 'pre-filled' || strtolower($company_contacts_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($company_contacts_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </span>
                                                                    </div>
                                                                </li>



                                                                <li>
                                                                    <label>Payroll Agreement</label>
                                                                    <div class="text text-center">
                                                                        <?php $fpa_status = ($company_documents_status['fpa_status'] != '' ? $company_documents_status['fpa_status'] : 'Not Generated'); ?>
                                                                        <span class="<?php echo strtolower(str_replace(' ', '-', $fpa_status)); ?>">
                                                                            <?php echo ucwords(str_replace('-', ' ', $fpa_status)) ?>
                                                                            <?php if (strtolower($fpa_status) == 'not sent' || strtolower($fpa_status) == 'generated' || strtolower($fpa_status) == 'pre-filled' || strtolower($fpa_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($fpa_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </span>
                                                                    </div>
                                                                </li>


                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>


                                                <article class="information-box">
                                                    <header class="hr-box-header">Company Default Categories</header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <li>
                                                                    <strong>Manage Company Default Categories </strong>
                                                                    <a href="<?php echo base_url('manage_admin/companies/default_document_category_listing/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>

                                                <article class="information-box">
                                                    <header class="hr-box-header">
                                                        Notification Emails Management
                                                    </header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <li>
                                                                    <label>Manage Emails</label>
                                                                    <a href="<?php echo base_url('manage_admin/notification_emails/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                                <!-- oem brands for company -->
                                                <article class="information-box">
                                                    <header class="hr-box-header">
                                                        Oem, Indepedent, Vendors
                                                    </header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <li>
                                                                    <label>View Members of Group</label>
                                                                    <a href="<?php echo base_url('manage_admin/companies/company_brands/' . $company_sid); ?>" class="site-btn pull-right">View</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                                <!-- oem brands for company -->
                                                <!-- automotive groups -->
                                                <article class="information-box">
                                                    <header class="hr-box-header">Members of Corporate Groups</header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <?php if (isset($automotive_groups) && sizeof($automotive_groups) > 0) { ?>
                                                                    <?php foreach ($automotive_groups as $group) { ?>
                                                                        <li><label><?php echo ucwords($group['group_name']); ?></label></li>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <li>No groups found</li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                                <!-- automotive groups -->
                                                <!-- Contact Info for company article -->
                                                <article class="information-box">
                                                    <header class="hr-box-header">
                                                        Manage Incident Reporting
                                                        <a href="<?php echo base_url('manage_admin/companies/manage_incident_configuration/' . $company_sid); ?>" class="site-btn pull-right">Manage Configuration</a>

                                                    </header>

                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <?php if ($company_info['incidents']) {
                                                                    $status = 'Enabled';
                                                                    $btn_value = 'Disable';
                                                                } else {
                                                                    $status = 'Disabled';
                                                                    $btn_value = 'Enable';
                                                                } ?>
                                                                <li class="<?php echo ($company_info['incidents'] == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                    <label>Status</label>
                                                                    <div style="<?php echo ($company_info['incidents'] ? 'color:green;' : 'color:red;'); ?>" class="text" id="status-label">
                                                                        <?php echo $status; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="text">
                                                                        <a href="javascript:;" id="change-incident" data-status="<?= $company_info['incidents'] ?>" data-attr="<?= $company_info['sid'] ?>" class="site-btn pull-right"><?= $btn_value ?></a>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>


                                                        <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                                <!-- Contact Info for company article -->

                                                <!-- Company Groups Article-->
                                                <article class="information-box">
                                                    <header class="hr-box-header">
                                                        Company jobs can be shared with
                                                        <a href="<?php echo base_url('manage_admin/company_job_share_management/' . $company_sid) ?>" class="site-btn pull-right">Add Company</a>
                                                    </header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <?php if (sizeof($configured_companies) > 0) {
                                                                    foreach ($configured_companies as $company) { ?>
                                                                        <li>
                                                                            <label><?php echo $company['CompanyName'] ?></label>
                                                                        </li>
                                                                <?php }
                                                                } else {
                                                                    echo '<li><label>No Company Configured</label></li>';
                                                                } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                                <!-- Contact Info for company article -->

                                                <!-- EMS Panel - Start -->
                                                <article class="information-box">
                                                    <header class="hr-box-header">
                                                        Employee Management System
                                                    </header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <?php if ($company_info['ems_status']) {
                                                                    $status = 'Enabled';
                                                                    $btn_value = 'Disable';
                                                                } else {
                                                                    $status = 'Disabled';
                                                                    $btn_value = 'Enable';
                                                                } ?>
                                                                <li class="<?php echo ($company_info['ems_status'] == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                    <label>Status</label>
                                                                    <div style="<?php echo ($company_info['ems_status'] ? 'color:green;' : 'color:red;'); ?>" class="text" id="status-label">
                                                                        <?php echo $status; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="text">
                                                                        <a href="javascript:;" id="change-ems" data-status="<?= $company_info['ems_status'] ?>" data-attr="<?= $company_info['sid'] ?>" class="site-btn pull-right"><?= $btn_value ?></a>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                                <article class="information-box">
                                                    <header class="hr-box-header">Header Video Overlay Status</header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <form enctype="multipart/form-data" method="post" action="<?php base_url('manage_admin/companies/manage_company/') ?>">
                                                                <input type="hidden" id="perform_action" name="perform_action" value="set_header_video_overlay_status" />
                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                <ul>
                                                                    <li class="lineheight">
                                                                        <div class="row">
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="header_video_overlay_status_active" name="header_video_overlay_status" value="1" <?php echo ($company_portal_status['header_video_overlay'] == 1 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="header_video_overlay_status_active" style="color: green; float: none;">Active</label>
                                                                            </div>
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="header_video_overlay_status_inactive" name="header_video_overlay_status" value="0" <?php echo ($company_portal_status['header_video_overlay'] == 0 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="header_video_overlay_status_inactive" style="color: red; float: none;">In Active</label>
                                                                            </div>
                                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                                <button type="submit" href="javascript:;" class="site-btn pull-right">update</button>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                                <!-- EMS -  End  -->
                                                <article class="information-box">
                                                    <header class="hr-box-header">
                                                        ComplyNet Status
                                                        <a href="<?= base_url('manage_admin/companies/manage_complynet/' . $company_sid) ?>" id="complynet-manage" class="site-btn pull-right">Manage</a>
                                                    </header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <?php if ($company_info['complynet_status']) {
                                                                    $status = 'Enabled';
                                                                    $btn_value = 'Disable';
                                                                } else {
                                                                    $status = 'Disabled';
                                                                    $btn_value = 'Enable';
                                                                } ?>
                                                                <li class="<?php echo ($company_info['complynet_status'] == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                    <label>Status</label>
                                                                    <div style="<?php echo ($company_info['complynet_status'] ? 'color:green;' : 'color:red;'); ?>" class="text" id="status-label">
                                                                        <?php echo $status; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="text">
                                                                        <a href="javascript:;" id="change-complynet" data-status="<?= $company_info['complynet_status'] ?>" data-attr="<?= $company_info['sid'] ?>" class="site-btn pull-right"><?= $btn_value ?></a>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>


                                                <article class="information-box">
                                                    <header class="hr-box-header">Send Bulk Email</header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <form enctype="multipart/form-data" method="post" action="<?php base_url('manage_admin/companies/manage_company/') ?>">
                                                                <input type="hidden" id="perform_action" name="perform_action" value="set_bulk_email_status" />
                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                <ul>
                                                                    <li class="lineheight">
                                                                        <div class="row">
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="bulk_email_status" name="bulk_email_status" value="1" <?php echo ($company_portal_status['bulk_email'] == 1 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="bulk_email_status_active" style="color: green; float: none;">Active</label>
                                                                            </div>
                                                                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                <input type="radio" id="bulk_email_status" name="bulk_email_status" value="0" <?php echo ($company_portal_status['bulk_email'] == 0 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="bulk_email_status_inactive" style="color: red; float: none;">In Active</label>
                                                                            </div>
                                                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                                <button type="submit" href="javascript:;" class="site-btn pull-right">update</button>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>





                                                <!-- Captcha-->
                                                <!--  <article class="information-box">
                                                    <header class="hr-box-header">
                                                        Captcha Status
                                                    </header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <?php if ($company_info['enable_captcha']) {
                                                                    $status = 'Enabled';
                                                                    $btn_value = 'Disable';
                                                                } else {
                                                                    $status = 'Disabled';
                                                                    $btn_value = 'Enable';
                                                                } ?>
                                                                <li class="<?php echo ($company_info['enable_captcha'] == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                    <label>Status</label>
                                                                    <div style="<?php echo ($company_info['enable_captcha'] ? 'color:green;' : 'color:red;'); ?>" class="text" id="status-label">
                                                                        <?php echo $status; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="text">
                                                                        <a href="javascript:void(0)" id="js-captcha-btn"  data-status="<?= (int)$company_info['enable_captcha'] ?>" data-attr="<?= $company_info['sid'] ?>" class="site-btn pull-right"><?= $btn_value ?></a>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article> -->
                                            </div>
                                            <?php if (sizeof($company_admin)) { ?>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Administrator Detail <a href="<?php echo base_url('manage_admin/employers/edit_employer/' . $company_admin['sid']); ?>" class="site-btn pull-right">Edit</a></header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        <label>Name</label>
                                                                        <div class="text">
                                                                            <?php echo ucwords($company_admin['first_name'] . ' ' . $company_admin['last_name']); ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Email</label>
                                                                        <div class="text">
                                                                            <?php echo $company_admin['email']; ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Alternative Email</label>
                                                                        <div class="text">
                                                                            <?php echo $company_admin['alternative_email']; ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Job Title</label>
                                                                        <div class="text">
                                                                            <?php echo ucwords($company_admin['job_title']); ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Direct Business Number</label>
                                                                        <div class="text">
                                                                            <?php echo $company_admin['direct_business_number']; ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Cell Number</label>
                                                                        <div class="text">
                                                                            <?php echo $company_admin['cell_number']; ?>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Company Credit Cards</header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li class="lineheight">
                                                                        <strong>Manage all credit cards </strong>
                                                                        <a href="<?php echo base_url('manage_admin/misc/cc_management') . '/' . $company_sid; ?>" class="site-btn pull-right">Manage Cards</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Administrator Status</header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <form enctype="multipart/form-data" method="post" action="<?php echo base_url('manage_admin/companies/manage_company/' . $company_info['sid']) ?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="set_administrator_status" />
                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_info['sid']; ?>" />
                                                                    <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $company_admin['sid']; ?>" />
                                                                    <ul>
                                                                        <li class="lineheight">
                                                                            <div class="row">
                                                                                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                    <input type="radio" id="company_admin_active" name="company_admin_status" value="1" <?php echo ($company_admin['active'] == 1 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="company_admin_active" style="color: green; float: none;">Active</label>
                                                                                </div>
                                                                                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                    <input type="radio" id="company_admin_inactive" name="company_admin_status" value="0" <?php echo ($company_admin['active'] == 0 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="company_admin_inactive" style="color: red; float: none;">In Active</label>
                                                                                </div>
                                                                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                                    <button type="submit" href="javascript:;" class="site-btn pull-right">update</button>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Facebook API Status <a href="<?php echo base_url('manage_admin/companies/manage_addons') . '/' . $company_sid; ?>" class="site-btn pull-right">Addons</a></header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <?php if ($company_info['facebook_purchased_status'] == 1) { ?>
                                                                        <li class="inclueded-state">
                                                                            <label>Status</label>
                                                                            <div style="color:green;" class="text">
                                                                                Active
                                                                            </div>
                                                                        </li>
                                                                    <?php   } else { ?>
                                                                        <li class="exclueded-state">
                                                                            <label>Status</label>

                                                                            <div style="color:red;" class="text">
                                                                                In-Active
                                                                            </div>
                                                                        </li>
                                                                    <?php   } ?>
                                                                    <li>
                                                                        <label>Expiration Date</label>
                                                                        <div class="text"><?php echo date('m-d-Y', strtotime(str_replace('-', '/', $company_info['facebook_expiry_date']))); ?></div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>

                                                    <article class="information-box">
                                                        <header class="hr-box-header">
                                                            General Information Documents Management
                                                        </header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        <label>Form Name</label>
                                                                        <div class="text text-center col-xs-6">
                                                                            <strong>Status</strong>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Dependents</label>
                                                                        <div class="text text-center">
                                                                            <?php $dependentStatus =  ($company_general_documents_status['dependents_flag'] == 1 ? 'active' : 'de-active'); ?>
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $dependentStatus)); ?>">
                                                                                <?php echo ucwords(str_replace('-', ' ', $dependentStatus)) ?>
                                                                                <?php if ( $dependentStatus == 'active') { ?>
                                                                                    <img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                                                    <a href="javascript:;" data-status="0" data-attr="dependents_flag" data-label="Dependents Information" class="site-btn btn-sm btn-danger pull-right jsChangeGeneralInformationDocumentStatus">De-Activate</a>
                                                                                <?php } else { ?>
                                                                                    <img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                                                    <a href="javascript:;" data-status="1" data-attr="dependents_flag" data-label="Dependents Information" class="site-btn btn-sm pull-right jsChangeGeneralInformationDocumentStatus">Activate</a>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </div>
                                                                    </li>

                                                                    <li>
                                                                        <label>Direct Deposit Information</label>
                                                                        <div class="text text-center">
                                                                            <?php $directDepositStatus =  ($company_general_documents_status['direct_deposit_flag'] == 1 ? 'active' : 'de-active'); ?>
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $directDepositStatus)); ?>">
                                                                                <?php echo ucwords(str_replace('-', ' ', $directDepositStatus)) ?>
                                                                                <?php if ($directDepositStatus == 'active') { ?>
                                                                                    <img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                                                    <a href="javascript:;" data-status="0" data-attr="direct_deposit_flag" data-label="Direct Deposit Information" class="site-btn btn-sm btn-danger pull-right jsChangeGeneralInformationDocumentStatus">De-Activate</a>
                                                                                <?php } else { ?>
                                                                                    <img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                                                    <a href="javascript:;" data-status="1" data-attr="direct_deposit_flag" data-label="Direct Deposit Information" class="site-btn btn-sm pull-right jsChangeGeneralInformationDocumentStatus">Activate</a>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Drivers License Information</label>
                                                                        <div class="text text-center">
                                                                            <?php $driversLicenseStatus =  ($company_general_documents_status['drivers_license_flag'] == 1 ? 'active' : 'de-active'); ?>
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $driversLicenseStatus)); ?>">
                                                                                <?php echo ucwords(str_replace('-', ' ', $driversLicenseStatus)) ?>
                                                                                <?php if ($driversLicenseStatus == 'active') { ?>
                                                                                    <img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                                                    <a href="javascript:;" data-status="0" data-attr="drivers_license_flag" data-label="Drivers License Information" class="site-btn btn-sm btn-danger pull-right jsChangeGeneralInformationDocumentStatus">De-Activate</a>
                                                                                <?php } else { ?>
                                                                                    <img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                                                    <a href="javascript:;" data-status="1" data-attr="drivers_license_flag" data-label="Drivers License Information" class="site-btn btn-sm pull-right jsChangeGeneralInformationDocumentStatus">Activate</a>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Emergency Contacts</label>
                                                                        <div class="text text-center">
                                                                            <?php $emergencyContactStatus =  ($company_general_documents_status['emergency_contacts_flag'] == 1 ? 'active' : 'de-active'); ?>
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $emergencyContactStatus)); ?>">
                                                                                <?php echo ucwords(str_replace('-', ' ', $emergencyContactStatus)) ?>
                                                                                <?php if ($emergencyContactStatus == 'active') { ?>
                                                                                    <img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                                                    <a href="javascript:;" data-status="0" data-attr="emergency_contacts_flag" data-label="Emergency Contacts" class="site-btn btn-sm btn-danger pull-right jsChangeGeneralInformationDocumentStatus">De-Activate</a>
                                                                                <?php } else { ?>
                                                                                    <img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                                                    <a href="javascript:;" data-status="1" data-attr="emergency_contacts_flag" data-label="Emergency Contacts" class="site-btn btn-sm pull-right jsChangeGeneralInformationDocumentStatus">Activate</a>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>Occupational License Information</label>
                                                                        <div class="text text-center">
                                                                            <?php $occupationalLicenseStatus =  ($company_general_documents_status['occupational_license_flag'] == 1 ? 'active' : 'de-active'); ?>
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $occupationalLicenseStatus)); ?>">
                                                                                <?php echo ucwords(str_replace('-', ' ', $occupationalLicenseStatus)) ?>
                                                                                <?php if ($occupationalLicenseStatus == 'active') { ?>
                                                                                    <img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                                                    <a href="javascript:;" data-status="0" data-attr="occupational_license_flag" data-label="Occupational License Information" class="site-btn btn-sm btn-danger pull-right jsChangeGeneralInformationDocumentStatus">De-Activate</a>
                                                                                <?php } else { ?>
                                                                                    <img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                                                    <a href="javascript:;" data-status="1" data-attr="occupational_license_flag" data-label="Occupational License Information" class="site-btn btn-sm pull-right jsChangeGeneralInformationDocumentStatus">Activate</a>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>

                                                    <article class="information-box">
                                                        <header class="hr-box-header">Deluxe Theme Status </header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <?php if ($company_info['deluxe_theme_purchased'] == 1) { ?>
                                                                        <li class="inclueded-state">
                                                                            <label>Status</label>
                                                                            <div style="color:green;" class="text">
                                                                                Active
                                                                            </div>
                                                                        </li>
                                                                    <?php   } else { ?>
                                                                        <li class="exclueded-state">
                                                                            <label>Status</label>
                                                                            <div style="color:red;" class="text">
                                                                                In-Active
                                                                            </div>
                                                                        </li>
                                                                    <?php   } ?>
                                                                    <li>
                                                                        <label>&nbsp;</label>
                                                                        <div class="text">&nbsp;</div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Company Notes</header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        <label>Billing Notes</label>
                                                                        <div class="text">
                                                                            <a class="site-btn pull-right" href="<?php echo base_url('manage_admin/companies/list_company_notes/billing/' . $company_sid); ?>">View All</a>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label>General Notes</label>
                                                                        <div class="text">
                                                                            <a class="site-btn pull-right" href="<?php echo base_url('manage_admin/companies/list_company_notes/general/' . $company_sid); ?>">View All</a>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Company Security Access Manager</header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        <label>Security Access Level</label>
                                                                        <div class="text">
                                                                            <a class="site-btn pull-right" href="<?php echo base_url('manage_admin/company_security_settings/' . $company_sid); ?>">Update Settings</a>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Portal Company Email Templates</header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        <strong>Email Templates</strong>
                                                                        <a href="<?php echo base_url('manage_admin/portal_email_templates/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Employer Portal Maintenance Mode
                                                            <a href="<?php echo base_url('manage_admin/companies/manage_maintenance_mode/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>
                                                        </header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li class="<?php echo ($company_portal_status['maintenance_mode'] == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                        <label>Status</label>
                                                                        <div style="<?php echo ($company_portal_status['maintenance_mode'] == 1 ? 'color:green;' : 'color:red;'); ?>" class="text">
                                                                            <?php echo ($company_portal_status['maintenance_mode'] == 1 ? 'Enabled' : 'Disabled'); ?>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <!-- job category industry article -->
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Job Industry Category
                                                            <a href="<?php echo base_url('manage_admin/companies/edit_company/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>
                                                        </header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        <label>Industry Category</label>
                                                                        <div class="text">
                                                                            <?php
                                                                            if (isset($job_category_industry) && !empty($job_category_industry)) {
                                                                                echo $job_category_industry['industry_name'];
                                                                            } else {
                                                                                echo 'No Industry Category assigned';
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <!-- job category industry article -->
                                                    <!-- Job Fairs and College student recruitment article -->
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Job Fairs and Student Recruitment</header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li class="<?php echo ($job_fair_page_status == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                        <label>Status</label>
                                                                        <div style="<?php echo ($job_fair_page_status == 1 ? 'color:green;' : 'color:red;'); ?>" class="text">
                                                                            <?php echo ($job_fair_page_status == 1 ? 'Enabled' : 'Disabled'); ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <strong>Manage Title & Description</strong>
                                                                        <a href="<?php echo base_url('manage_admin/companies/job_fairs_recruitment/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <!-- Job Fairs and College student recruitment article -->
                                                    <!-- Contact Info for company article -->
                                                    <article class="information-box">
                                                        <header class="hr-box-header">
                                                            Contact Info
                                                        </header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        <label><?php echo isset($info_flag) && $info_flag > 0 ? "Specific" : "Default" ?></label>
                                                                        <a href="<?php echo base_url('manage_admin/companies/manage_contact_info/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>


                                                    <article class="information-box">
                                                        <header class="hr-box-header">
                                                            Company Help Box
                                                        </header>
                                                        <div class="table-outer">
                                                            <div class="info-row text-center">
                                                                <ul>
                                                                    <li>
                                                                        <a href="<?php echo base_url('manage_admin/companies/manage_company_help_box/' . $company_sid); ?>" class="btn btn-success">Manage</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>


                                                    <!-- Contact Info for company article -->
                                                    <!-- Addition theme4 sections for career page  -->
                                                    <article class="information-box">
                                                        <header class="hr-box-header">
                                                            Enable Additional Content Boxes
                                                        </header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        You can enable or disable additional content boxes for the company
                                                                    </li>
                                                                    <li>
                                                                        <a href="<?php echo base_url('manage_admin/additional_content_boxes/' . $company_sid); ?>" class="site-btn pull-right">Manage Boxes</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <!-- Addition theme4 sections for career page -->
                                                    <!-- additional status bar in ATS - Start -->
                                                    <article class="information-box">
                                                        <header class="hr-box-header">
                                                            Additional Status Bar Configurations
                                                        </header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <?php if ($company_info['enable_applicant_status_bar']) {
                                                                        $status = 'Enabled';
                                                                        $btn_value = 'Disable';
                                                                    } else {
                                                                        $status = 'Disabled';
                                                                        $btn_value = 'Enable';
                                                                    } ?>
                                                                    <li class="<?php echo ($company_info['enable_applicant_status_bar'] == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                        <label>Status</label>
                                                                        <div style="<?php echo ($company_info['enable_applicant_status_bar'] ? 'color:green;' : 'color:red;'); ?>" class="text" id="status-label">
                                                                            <?php echo $status; ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="text">
                                                                            <a href="javascript:;" id="change-status" data-status="<?= $company_info['enable_applicant_status_bar'] ?>" data-attr="<?= $company_info['sid'] ?>" class="site-btn pull-right"><?= $btn_value ?></a>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <!-- additional status bar in ATS -  End  -->
                                                    <!-- resource center - Start -->
                                                    <article class="information-box">
                                                        <header class="hr-box-header">
                                                            Manage Resource Center
                                                        </header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <?php if ($company_info['enable_resource_center']) {
                                                                        $status = 'Enabled';
                                                                        $btn_value = 'Disable';
                                                                    } else {
                                                                        $status = 'Disabled';
                                                                        $btn_value = 'Enable';
                                                                    } ?>
                                                                    <li class="<?php echo ($company_info['enable_resource_center'] == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                        <label>Status</label>
                                                                        <div style="<?php echo ($company_info['enable_resource_center'] ? 'color:green;' : 'color:red;'); ?>" class="text" id="status-label">
                                                                            <?php echo $status; ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="text">
                                                                            <a href="javascript:;" id="change-resource" data-status="<?= $company_info['enable_resource_center'] ?>" data-attr="<?= $company_info['sid'] ?>" class="site-btn pull-right"><?= $btn_value ?></a>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <article class="information-box">
                                                        <header class="hr-box-header">EEO Footer Text Status</header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <form enctype="multipart/form-data" method="post" action="<?php base_url('manage_admin/companies/manage_company/') ?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="set_eeo_footer_text_status" />
                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                    <ul>
                                                                        <li class="lineheight">
                                                                            <div class="row">
                                                                                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                    <input type="radio" id="eeo_footer_text_status_active" name="eeo_footer_text_status" value="1" <?php echo ($company_portal_status['eeo_footer_text'] == 1 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="eeo_footer_text_status_active" style="color: green; float: none;">Active</label>
                                                                                </div>
                                                                                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                                                                    <input type="radio" id="eeo_footer_text_status_inactive" name="eeo_footer_text_status" value="0" <?php echo ($company_portal_status['eeo_footer_text'] == 0 ? 'checked="checked"' : ''); ?> />&nbsp;<label for="eeo_footer_text_status_inactive" style="color: red; float: none;">In Active</label>
                                                                                </div>
                                                                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                                                    <button type="submit" href="javascript:;" class="site-btn pull-right">update</button>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <!-- resource center -  End  -->
                                                    <article class="information-box">
                                                        <header class="hr-box-header">
                                                            Access Level Plus
                                                        </header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        <div class="text">
                                                                            <a href="<?= base_url('manage_admin/companies/access_level_plus/' . $company_sid) ?>" id="access-plus" class="site-btn pull-right">Manage</a>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <!-- resource center -  End  -->
                                                    <article class="information-box">
                                                        <header class="hr-box-header">
                                                            Time off Approvers
                                                        </header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li>
                                                                        <div class="text">
                                                                            <a href="<?= base_url('manage_admin/companies/timeoff_approvers/' . $company_sid) ?>" class="site-btn pull-right">Manage</a>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Customize Career Site</header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li class="<?php echo ($customize_career_site_status == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                        <label>Status</label>
                                                                        <div style="<?php echo ($customize_career_site_status == 1 ? 'color:green;' : 'color:red;'); ?>" class="text">
                                                                            <?php echo ($customize_career_site_status == 1 ? 'Enabled' : 'Disabled'); ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="text">
                                                                            <a href="<?php echo base_url('manage_admin/companies/customize_career_site/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>

                                                                        </div>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                                    <article class="information-box">
                                                        <header class="hr-box-header">Remarket jobs settings</header>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li class="<?php echo ($remarket_company_settings_status == 1 ? 'inclueded-state' : 'exclueded-state'); ?>">
                                                                        <label>Status</label>
                                                                        <div style="<?php echo ($remarket_company_settings_status == 1 ? 'color:green;' : 'color:red;'); ?>" class="text">
                                                                            <?php echo ($remarket_company_settings_status == 1 ? 'Enabled' : 'Disabled'); ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="text">
                                                                            <a href="<?php echo base_url('manage_admin/remarket/remarket_company_settings/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>

                                                                        </div>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>

                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row">
                                            <!-- Dynamic Modules -->
                                            <?php if (sizeof($dynamicModules)) {
                                                foreach ($dynamicModules as $k => $v) { ?>
                                                    <article class="col-sm-6 information-box">
                                                        <header class="hr-box-header">
                                                            <?= $v['module_name']; ?>
                                                            <?php if ($v['sid'] == 3) : ?>
                                                                <span class="pull-right">
                                                                    <button class="btn btn-success jsModifyFeedEmail">
                                                                        <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update Details
                                                                    </button>
                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if ($v['module_name'] == "Learning Management System") { ?>
                                                                <a href="<?php echo base_url('sa/lms/courses/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>
                                                            <?php } ?>
                                                            <?php if ($v['module_name'] == "Payroll") { ?>
                                                                <a href="<?php echo base_url('sa/payrolls/' . $company_sid); ?>" class="site-btn pull-right">Manage</a>
                                                            <?php } ?>
                                                        </header>
                                                        <div class="clearfix"></div>
                                                        <div class="table-outer">
                                                            <div class="info-row">
                                                                <ul>
                                                                    <li class="<?= $v['status'] == 1 ? 'inclueded-state' : 'exclueded-state'; ?>">
                                                                        <label>Status</label>
                                                                        <div class="text" style="<?= $v['status'] == 1 ? 'color:green;' : 'color:red;'; ?>">
                                                                            <?= $v['status'] == 0 ? 'Disabled' : 'Enabled'; ?>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="text">
                                                                            <a data-id="<?= $v['sid']; ?>" data-status="<?= $v['status']; ?>" class="site-btn <?= $v['status'] == 1 ? 'btn-danger' : ''; ?> pull-right js-dynamic-module-btn"><?= $v['status'] == 1 ? 'Disable' : 'Enable'; ?></a>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <header class="hr-box-header hr-box-footer"></header>
                                                    </article>
                                            <?php }
                                            } ?>
                                        </div>


                                        <div class="row">
                                            <article class="col-sm-6 information-box">
                                                <header class="hr-box-header"> Company Secure Documents</header>
                                                <div class="table-outer">
                                                    <div class="info-row">
                                                        <div class="row">
                                                            <div class="col-sm-12 text-center">
                                                                <a href="<?= base_url('manage_admin/company/documents/secure/listing/' . ($company_sid) . ''); ?>" class="btn btn-success">View Documents</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <header class="hr-box-header hr-box-footer"></header>
                                            </article>

                                        </div>


                                        <div class="heading-title page-title">
                                            <h1 class="page-title">Company Admin Invoices</h1>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="hr-promotions table-responsive">
                                                    <div class="scrollable-area">
                                                        <table class="table table-bordered table-stripped fixTable-header">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="4" class="text-center">Invoice Summary</th>
                                                                    <th rowspan="2" colspan="1" class="text-center">Actions</th>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="1"></th>
                                                                    <th class="text-center">Value</th>
                                                                    <th class="text-center">Discount</th>
                                                                    <th class="text-center">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($company_admin_invoices)) { ?>
                                                                    <?php foreach ($company_admin_invoices as $invoice) { ?>
                                                                        <tr>
                                                                            <td class="col-lg-6">
                                                                                <div class="invoice-date">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-12">
                                                                                            <div class="dotted-border">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Company</strong></div>
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo ucwords($invoice['company_name']); ?></div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="dotted-border">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Created Date</strong></div>
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo convert_date_to_frontend_format($invoice['created'], true); ?></div>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="dotted-border">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Invoice #</strong></div>
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo $invoice['invoice_number']; ?></div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="dotted-border">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Payment Status</strong></div>
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 <?php echo ucwords($invoice['payment_status']); ?>"><?php echo ucwords($invoice['payment_status']); ?></div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="dotted-border">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Payment Date</strong></div>
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo convert_date_to_frontend_format($invoice['payment_date'], true); ?></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h5><strong>Item Summary</strong></h5>
                                                                                    <ul class="item-name-summary">
                                                                                        <?php foreach ($invoice['item_names'] as $item_name) { ?>
                                                                                            <li><?php echo $item_name['item_name']; ?></li>
                                                                                        <?php } ?>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                            <!-- Start Invoice Summary -->
                                                                            <td class="text-right col-xs-2">
                                                                                $<?php echo number_format($invoice['value'], 2, '.', ',') ?></td>
                                                                            <td class="text-right col-xs-2">
                                                                                $<?php echo number_format($invoice['discount_amount'], 2, '.', ',') ?></td>
                                                                            <td class="text-right col-xs-2">
                                                                                <!--
                                                                                <?php /*if ($invoice['is_discounted'] == 1) { */ ?>
                                                                                    $<?php /*echo number_format($invoice['total_after_discount'], 2, '.', ',') */ ?>
                                                                                <?php /*} else { */ ?>
                                                                                    $<?php /*echo number_format($invoice['value'], 2, '.', ',') */ ?>
                                                                                <?php /*} */ ?>
                                                                                -->
                                                                                $<?php echo number_format($invoice['total_after_discount'], 2, '.', ',') ?>
                                                                            </td>
                                                                            <!-- End Invoice Summary commission_invoice_sid-->
                                                                            <td class="text-center">
                                                                                <a class="hr-edit-btn invoice-links" href="<?php echo base_url('manage_admin/invoice/view_admin_invoice') . '/' . $invoice['sid']; ?>">View Invoice</a>
                                                                                <?php if ($invoice['payment_status'] == 'unpaid') { ?>
                                                                                    <a class="hr-edit-btn invoice-links" href="<?php echo base_url('manage_admin/invoice/apply_discount_admin_invoice') . '/' . $invoice['sid']; ?>">Apply Discount</a>
                                                                                <?php } else { ?>
                                                                                    <a class="hr-edit-btn invoice-links disabled-btn" href="javascript:void(0);">Apply Discount</a>
                                                                                <?php } ?>
                                                                                <?php if ($invoice['commission_invoice_sid'] == 0) { ?>
                                                                                    <a class="hr-edit-btn invoice-links" href="<?php echo base_url('manage_admin/companies/generate_commisson_invoice') . '/' . $invoice['company_sid'] . '/' . $invoice['sid']; ?>">Generate CI</a>
                                                                                <?php } else { ?>
                                                                                    <a class="hr-edit-btn invoice-links disabled-btn" href="javascript:void(0);">Generate CI</a>
                                                                                <?php } ?>
                                                                                <?php if ($invoice['payment_status'] == 'unpaid') { ?>
                                                                                    <a class="hr-edit-btn invoice-links jsSendInvoice" data-invoice="<?= $invoice['sid']; ?>" href="javascript:void(0);">Send Invoice</a>
                                                                                <?php } ?>
                                                                                <?php if ($invoice['payment_status'] == 'unpaid' && $invoice['discount_amount'] < $invoice['value']) { ?>
                                                                                    <a class="hr-edit-btn invoice-links" href="<?php echo base_url('manage_admin/misc/process_payment_admin_invoice') . '/' . $invoice['sid']; ?>">Process Payment</a>
                                                                                <?php } elseif ($invoice['discount_amount'] == $invoice['value'] && $invoice['payment_status'] == 'unpaid') { ?>
                                                                                    <button type="button" class="hr-edit-btn invoice-links" onclick="fActivateInvoiceFeatures(<?php echo $invoice['company_sid']; ?>, <?php echo $invoice['sid'] ?>);"> Activate Invoice </button>
                                                                                <?php } else { ?>
                                                                                    <a class="hr-edit-btn invoice-links disabled-btn" href="javascript:void(0);">Process Payment</a>
                                                                                <?php } ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="9">
                                                                            <h3 class="no-data">Admin Invoices Not Found!</h3>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="heading-title page-title">
                                            <h1 class="page-title">Company Marketplace Invoices</h1>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="hr-promotions table-responsive">
                                                    <div class="scrollable-area">
                                                        <table class="table table-bordered table-stripped fixTable-header">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="4" class="text-center">Invoice Summary</th>
                                                                    <th rowspan="2" colspan="1" class="text-center">Actions</th>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="1"></th>
                                                                    <th class="text-center">Value</th>
                                                                    <th class="text-center">Discount</th>
                                                                    <th class="text-center">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($company_portal_invoices)) { ?>
                                                                    <?php foreach ($company_portal_invoices as $invoice) { ?>
                                                                        <tr>
                                                                            <td class="col-lg-6">
                                                                                <div class="invoice-date">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-12">
                                                                                            <div class="dotted-border">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Created Date</strong></div>
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo date('m-d-Y', strtotime($invoice['date'])); ?></div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="dotted-border">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Invoice #</strong></div>
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo $invoice['sid']; ?></div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="dotted-border">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Payment Date</strong></div>
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?= convert_date_to_frontend_format($invoice["payment_date"], true); ?></div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="dotted-border">
                                                                                                <div class="row">
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Payment Status</strong></div>
                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 <?php echo ucwords($invoice['status']); ?>"><?php echo ucwords($invoice['status']); ?></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <h5><strong>Item Summary</strong></h5>
                                                                                    <ul class="item-name-summary">
                                                                                        <?php foreach ($invoice['item_names'] as $item_name) { ?>
                                                                                            <li><?php echo $item_name['name']; ?></li>
                                                                                        <?php } ?>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                            <!-- Start Invoice Summary -->
                                                                            <td class="text-right col-xs-2">
                                                                                $ <?php echo number_format($invoice['sub_total'], 2, '.', ',') ?></td>
                                                                            <td class="text-right col-xs-2">
                                                                                $ <?php echo number_format($invoice['total_discount'], 2, '.', ',') ?></td>
                                                                            <td class="text-right col-xs-2">
                                                                                $ <?php echo number_format($invoice['total'], 2, '.', ',') ?>
                                                                            </td>
                                                                            <!-- End Invoice Summary -->
                                                                            <td class="text-center">
                                                                                <a class="hr-edit-btn invoice-links" href="<?php echo base_url('manage_admin/invoice/edit_invoice/' . $invoice['sid']); ?>">View Invoice</a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="9">
                                                                            <h3 class="no-data">Marketplace Invoices Not Found!</h3>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
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
</div>
<script>
    $('#training-status').click(function() {
        var company_sid = $(this).attr('data-attr');
        var key = $(this).attr('data-key');
        var message = 'Are you sure you want to mark it uncompleted? <br /> This action is reversable!';
        if (key == '1') {
            message = 'Are you sure you want to mark it completed? <br /> This action is reversable!';
        }
        alertify.confirm(
            'Please Confirm!',
            message,
            function() {
                var myUrl = '<?php echo base_url('manage_admin/companies/ajax_responder') ?>';
                var myRequest;

                myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: {
                        perform_action: 'mark_training_completed',
                        company_sid: company_sid,
                        key: key
                    }
                });

                myRequest.done(function(response) {
                    //console.log(response);
                    if (response == 'success') {
                        myUrl = window.location.href;
                        window.location = myUrl;
                    }
                })
            },
            function() {
                //Cancel
            });
    });

    function fActivateInvoiceFeatures(company_sid, invoice_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Activate all Products against this Invoice? <br /> This action is irreversable!',
            function() {
                var myUrl = '<?php echo base_url('manage_admin/invoice/ajax_responder') ?>';
                var myRequest;

                myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: {
                        perform_action: 'activate_invoice_features',
                        company_sid: company_sid,
                        invoice_sid: invoice_sid
                    }
                });

                myRequest.done(function(response) {
                    //console.log(response);
                    if (response == 'success') {
                        myUrl = window.location.href;
                        window.location = myUrl;
                    }
                })
            },
            function() {
                //Cancel
            });
    }

    $(document).ready(function() { // get the states
        var myid = $('#state_id').html();
        setTimeout(function() {
            $("#country").change();
        }, 1000);
        if (myid) {
            setTimeout(function() {
                $('#state').val(myid);
            }, 1200);
        }
    });

    $(document).on('click', '#change-status', function() {
        var status = $('#change-status').attr('data-status');
        var id = $('#change-status').attr('data-attr');
        alertify.confirm('Confirmation', "Are you sure you want to " + $(this).html(),
            function() {
                $.ajax({
                    url: "<?= base_url() ?>manage_admin/companies/change_applicant_status",
                    type: 'POST',
                    data: {
                        status: status,
                        sid: id
                    },
                    success: function(data) {
                        alertify.success('Employee has been Activated.');
                        data = JSON.parse(data);
                        window.location.href = '<?php echo current_url() ?>';
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    //change_per_job_listing_status
    $(document).on('click', '#change_per_job_status', function() {
        var status = $(this).attr('data-status');
        var db_field = $(this).attr('data-attr');
        var company_id = '<?php echo $company_sid; ?>';

        alertify.confirm('Confirmation', "Are you sure you want to " + $(this).html(),
            function() {
                $.ajax({
                    url: "<?= base_url() ?>manage_admin/companies/ajax_change_status",
                    type: 'POST',
                    data: {
                        status: status,
                        db_field: db_field,
                        sid: company_id
                    },
                    success: function(data) {
                        alertify.success('Status updated successfully.');
                        window.location.href = '<?php echo current_url() ?>';
                    },
                    error: function() {}
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    $(document).on('click', '#change-resource', function() {
        var status = $('#change-resource').attr('data-status');
        var id = $('#change-resource').attr('data-attr');
        alertify.confirm('Confirmation', "Are you sure you want to " + $(this).html(),
            function() {
                $.ajax({
                    url: "<?= base_url() ?>manage_admin/companies/change_resource_center",
                    type: 'POST',
                    data: {
                        status: status,
                        sid: id
                    },
                    success: function(data) {
                        alertify.success('Resource center has been Activated.');
                        data = JSON.parse(data);
                        window.location.href = '<?php echo current_url() ?>';
                    },
                    error: function() {}
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    $(document).on('click', '#change-ems', function() {
        var status = $('#change-ems').attr('data-status');
        var id = $('#change-ems').attr('data-attr');
        alertify.confirm('Confirmation', "Are you sure you want to " + $(this).html(),
            function() {
                $.ajax({
                    url: "<?= base_url() ?>manage_admin/companies/change_ems_status",
                    type: 'POST',
                    data: {
                        status: status,
                        sid: id
                    },
                    success: function(data) {
                        alertify.success('EMS Status has been Activated.');
                        data = JSON.parse(data);
                        if (status == 0) {
                            window.location.href = '<?php echo base_url('setup_default_config') . '/' ?>' + id;
                        } else {
                            window.location.href = '<?php echo current_url() ?>';
                        }
                    },
                    error: function() {}
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    $(document).on('click', '#change-complynet', function() {
        var status = $('#change-complynet').attr('data-status');
        var id = $('#change-complynet').attr('data-attr');
        alertify.confirm('Confirmation', "Are you sure you want to " + $(this).html() + " Complynet",
            function() {
                $.ajax({
                    url: "<?= base_url() ?>manage_admin/companies/change_comply_status",
                    type: 'POST',
                    data: {
                        status: status,
                        sid: id
                    },
                    success: function(data) {
                        alertify.success('ComplyNet Status has been Activated.');
                        data = JSON.parse(data);
                        window.location.href = '<?php echo current_url() ?>';
                    },
                    error: function() {}
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    //
    // $('#js-captcha-btn').click(function(){
    //     //
    //     var status = parseInt($(this).data('status')) === 0 ? 1 : 0,
    //     id = $(this).data('attr');
    //     //
    //     alertify.confirm('Confirmation', "Are you sure you want to "+$(this).html()+" Captcha.",
    //         function () {
    //             //
    //             var xhr = $.post("<?php //echo base_url('manage_admin/companies/change_captcha_status');
                                        ?>", {
    //                 status: status,
    //                 sid: id
    //             });
    //             //
    //             xhr.success(function(data){
    //                 alertify.success('Captcha Status has been '+( status == 0 ? 'Enabled' : 'Disabled' )+'.');
    //                 data = JSON.parse(data);
    //                 window.location.href = '<?php //echo current_url()
                                                ?>';
    //             });
    //         },
    //         function () {
    //             alertify.error('Canceled');
    //         });
    // });

    function getStates(val, states) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option>');
        } else {
            allstates = states[val];

            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }

            $('#state').html(html);
        }
    }

    $(function() {
        $('.js-dynamic-module-btn').click(function(e) {
            e.preventDefault();
            let megaOBJ = {};
            var _this = $(this);
            megaOBJ.Status = $(this).data('status');
            megaOBJ.Id = $(this).data('id');
            megaOBJ.CompanyId = <?= $company_sid; ?>;
            //
            alertify.confirm('Do you really want to ' + (megaOBJ.Status === 1 ? 'disable' : 'enable') + ' this MODULE?', function() {
                //
                $.post("<?= base_url('manage_admin/companies/update_module_status'); ?>", megaOBJ, function(resp) {
                    if (resp.Status === false) {
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    alertify.alert('SUCCESS!', 'Module has been ' + (megaOBJ.Status === 1 ? 'Disabled' : 'Enabled') + '.');
                    _this.text(megaOBJ.Status === 0 ? 'Disable' : 'Enable');
                    //
                    if (megaOBJ.Status === 0) {
                        _this.data('status', 1);
                        _this.addClass('btn-danger');
                        _this.parent().parent().parent().find('.exclueded-state').removeClass('exclueded-state').addClass('inclueded-state');
                        _this.parent().parent().parent().find('.inclueded-state div').attr('style', 'color:green;').text('Enabled');
                    } else {
                        _this.data('status', 0);
                        _this.removeClass('btn-danger');
                        _this.parent().parent().parent().find('.inclueded-state').removeClass('inclueded-state').addClass('exclueded-state');
                        _this.parent().parent().parent().find('.exclueded-state div').attr('style', 'color:red;').text('Disabled');
                    }
                });
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });
    })

    //
    $(function() {
        //
        $('.jsModifyFeedEmail').click(function(event) {
            //
            event.preventDefault();
            //
            var modal = $('#jsModalContainer').html();
            $('#jsModalContainer').remove();
            $('body').append(modal)
            //
            $('#jsEmailModal .jsName').val("<?= $CompanyIndeedDetails['contact_name']; ?>");
            $('#jsEmailModal .jsPhone').val("<?= $CompanyIndeedDetails['contact_phone']; ?>");
            $('#jsEmailModal .jsEmail').val("<?= $CompanyIndeedDetails['contact_email']; ?>");
            //
            $('#jsEmailModal').modal();
        });

        //
        var xhr = null;

        //
        $(document).on('click', '.jsSaveEmail', function(event) {
            //
            event.preventDefault();
            //
            if (xhr !== null) {
                return;
            }
            //
            var o = {};
            o.email = $('#jsEmailModal .jsEmail').val().trim();
            o.name = $('#jsEmailModal .jsName').val().trim();
            o.phone = $('#jsEmailModal .jsPhone').val().trim();
            o.companyId = <?= $company_sid; ?>;



            //
            if (o.email && !validateEmail(o.email)) {
                return alertify.alert(
                    'Error!',
                    'Email is not valid.'
                );
            }
            //
            $(this).text('Updating email...');
            //
            xhr = $.post(
                "<?= base_url("manage_admin/companies/update_company_email"); ?>",
                o
            ).done(function(resp) {
                alertify.alert(
                    "Success!",
                    "You have successfully updated the email.",
                    function() {
                        window.location.reload();
                    }
                );
            });
        });

        function validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    });


    //
    $(document).on('click', '#change-incident', function() {
        var status = $('#change-incident').attr('data-status');
        var id = $('#change-incident').attr('data-attr');
        alertify.confirm('Confirmation', "Are you sure you want to " + $(this).html(),
            function() {
                $.ajax({
                    url: "<?= base_url() ?>manage_admin/companies/change_incident_status",
                    type: 'POST',
                    data: {
                        status: status,
                        sid: id
                    },
                    success: function(data) {
                        alertify.success('Incident Reporting Status has been Activated.');
                        data = JSON.parse(data);
                        if (status == 0) {
                            window.location.href = '<?php echo base_url('setup_default_config') . '/' ?>' + id;
                        } else {
                            window.location.href = '<?php echo current_url() ?>';
                        }
                    },
                    error: function() {}
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });
</script>


<!-- Email Modal -->
<div id="jsModalContainer">
    <div class="modal fade" id="jsEmailModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Update details for Indeed feed</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Contact Person</label>
                            <input type="text" class="form-control jsName" placeholder="Jhon Doe" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Contact Phone Number</label>
                            <input type="email" class="form-control jsPhone" placeholder="+1 (123)-4567891" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Contact Email Address</label>
                            <input type="email" class="form-control jsEmail" placeholder="jhon.doe@automotohr.com" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success jsSaveEmail">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function invoiceEmail() {
        //
        let invoiceIds = [];
        //
        $('.jsSendInvoice').click(function(event) {
            //
            event.preventDefault();
            //
            let invoiceId = $(this).data('invoice');
            //
            if ($.inArray(invoiceId, invoiceIds) !== -1) {
                return;
            }
            //
            invoiceIds.push(invoiceId);
            //
            return alertify.confirm(
                'Invoice will be sent through email to the people listed in "Billing And Invoice Notifications".<br /><br />Are you sure you want to do it?',
                function() {
                    startProcess(invoiceId);
                }
            );
        });

        //
        function startProcess(invoiceId) {
            //
            $('.jsSendInvoice[data-invoice="' + (invoiceId) + '"]').text('Sending...');
            //
            $.post(
                "<?= base_url('send_invoice_by_email'); ?>", {
                    invoiceId: invoiceId,
                    companyId: <?= $company_sid; ?>
                }
            ).done(function(resp) {
                $('.jsSendInvoice[data-invoice="' + (invoiceId) + '"]').text('Send Invoice');
                invoiceIds.splice(invoiceIds.indexOf(invoiceId, 1));
                //
                if (resp.error) {
                    return alertify.alert('Error!', resp.error, function() {});
                }
                return alertify.alert('Success!', resp.success, function() {});
            });
        }
    });

     //change_per_job_listing_status
     $(document).on('click', '.jsChangeGeneralInformationDocumentStatus', function() {
        var documentStatus = $(this).attr('data-status');
        var documentField = $(this).attr('data-attr');
        var documentLabel = $(this).attr('data-label');
        var companyId = '<?php echo $company_sid; ?>';

        alertify.confirm('Confirmation', "Are you sure you want to " + $(this).html() + " <strong>" + documentLabel + "</strong>",
            function() {
                $.ajax({
                    url: "<?= base_url() ?>manage_admin/companies/ajax_change_general_documents_status",
                    type: 'POST',
                    data: {
                        status: documentStatus,
                        fieldName: documentField,
                        sid: companyId
                    },
                    success: function(data) {
                        alertify.success('Status updated successfully.');
                        window.location.href = '<?php echo current_url() ?>';
                    },
                    error: function() {}
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });
</script>