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
                                        <h1 class="page-title"><i class="fa fa-file-o"></i>Forms And Documents</h1>
                                        <a href="<?php echo base_url('manage_admin/documents/send/'. $company_sid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Send Documents</a>
                                        <a href="<?php echo base_url('manage_admin/companies') ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Companies</a>
                                        <?php if (isset($company_sid) && $company_sid > 0) { ?>
                                            <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid) ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                        <?php } ?>
                                    </div>
                                    <?php if (!isset($company_sid) || !empty($this->uri->segment(4))) { ?>
                                        <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                            <strong>Click to modify search criteria</strong>
                                        </div>
                                        <div class="hr-search-main" <?= $flag ? "style='display:block'" : "" ?>>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                                                        <?php $name = $this->uri->segment(4) == 'all' ? '' : $this->uri->segment(4); ?>
                                                        <label>Company Name</label>
                                                        <input type="text" name="name" id="name" value="<?php echo urldecode($name); ?>" class="invoice-fields">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 field-row">
                                                        <label>&nbsp;</label>
                                                        <a id="search_btn" href="#" class="btn btn-success btn-block" style="padding: 9px;">Search</a>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 field-row">
                                                        <label>&nbsp;</label>
                                                        <a id="clear" href="<?= base_url('manage_admin/documents')?>" class="btn btn-success btn-block" style="padding: 9px;">Clear</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header"><h4 class="hr-registered">Total Companies: <?php echo count($companies_documents); ?></h4></div>
                                                    <div class="table-responsive hr-innerpadding">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 250px;" class="text-left" colspan="1" rowspan="2">Company Name</th>
                                                                    <th class="text-center" colspan="3">Credit Card Authorization</th>
                                                                    <th class="text-center" colspan="3">End User License Agreement</th>
                                                                    <th class="text-center" colspan="3">Company Contacts</th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-center" colspan="2">Status</th>
                                                                    <th class="text-center">Action</th>
                                                                    <th class="text-center" colspan="2">Status</th>
                                                                    <th class="text-center">Action</th>
                                                                    <th class="text-center" colspan="2">Status</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                            <?php           $inactive_companies = array();

                                                            foreach ($companies_documents as $company_documents) {

                                                                    if($company_documents['active'] == 0) {
                                                                        $inactive_companies[] = $company_documents;
                                                                        continue;
                                                                    }
                                                                    
                                                                    $rec_status = 'Not Sent';
                                                                    $cc_status = 'Not Sent';
                                                                    $eula_status = 'Not Sent';
                                                                    $company_contacts_status = 'Not Sent';

                                                                    if (!empty($company_documents['rec_payment_auth'])) {
                                                                        $rec_status = $company_documents['rec_payment_auth']['status'];
                                                                    }

                                                                    if (!empty($company_documents['cc_auth'])) {
                                                                        $cc_status = $company_documents['cc_auth']['status'];
                                                                    }

                                                                    if (!empty($company_documents['eula'])) {
                                                                        $eula_status = $company_documents['eula']['status'];
                                                                    }

                                                                    if (!empty($company_documents['contacts'])) {
                                                                        $company_contacts_status = $company_documents['contacts']['status'];
                                                                    } ?>
                                                                    <tr>
                                                                        <td><?php echo ucwords($company_documents['CompanyName']); ?><br>
                                                                            <?php   if ($company_documents['active'] == 1) { ?>
                                                                                        <span style="color:green;">Active</span>
                                                                            <?php   } else { ?>
                                                                                        <span style="color:red;">In-Active</span>
                                                                            <?php   } ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $cc_status)); ?>"><?php echo ucwords(str_replace('-', ' ', $cc_status)) ?></span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if (strtolower($cc_status) == 'not sent' || strtolower($cc_status) == 'generated' || strtolower($cc_status) == 'pre-filled' || strtolower($cc_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($cc_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </td>
                                                                        <?php if (strtolower($cc_status) == 'not sent') { ?>
                                                                            <td class="text-center">
                                                                                <form id="form_generate_credit_card_authorization_<?php echo $company_documents['sid'] ?>" method="post" action="<?php echo base_url('manage_admin/documents'); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="generate_form" />
                                                                                    <input type="hidden" id="form_to_send" name="form_to_send" value="credit_card_authorization" />
                                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_documents['sid']; ?>" />
                                                                                    <input type="hidden" id="company_name" name="company_name" value="<?php echo $company_documents['CompanyName']; ?>" />
                                                                                    <input type="hidden" id="company_admin_email" name="company_admin_email" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['email'] : 'ahassan.egenie@gmail.com'); ?>" />
                                                                                    <input type="hidden" id="company_admin_full_name" name="company_admin_full_name" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['first_name'] . ' ' . $company_documents['administrator']['last_name'] : 'James Taylor' ); ?>" />
                                                                                    <button type="button" class="hr-edit-btn btn-block" onclick="fSendForm('credit_card_authorization', 'generate', '<?php echo ucwords($company_documents['CompanyName']); ?>', '<?php echo $company_documents['sid']; ?>');">Generate</button>
                                                                                </form>
                                                                            </td>
                                                                        <?php } elseif (strtolower($cc_status) == 'generated') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_credit_card_authorization' . '/' . $company_documents['cc_auth']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Pre-fill</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($cc_status) == 'pre-filled' || strtolower($cc_status) == 'sent') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_credit_card_authorization' . '/' . $company_documents['cc_auth']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Edit</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($cc_status) == 'signed') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_credit_card_authorization' . '/' . $company_documents['cc_auth']['verification_key'] . '/view'); ?>" class="hr-edit-btn btn-block">View</a>
                                                                                <a  href="<?php echo base_url('manage_admin/documents/regenerate_credit_card_authorization' . '/' . $company_documents['cc_auth']['verification_key']); ?>" class="hr-edit-btn btn-block">Re-Generate</a>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <td class="text-center">
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $eula_status)); ?>"><?php echo ucwords(str_replace('-', ' ', $eula_status)) ?></span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if (strtolower($eula_status) == 'not sent' || strtolower($eula_status) == 'generated' || strtolower($eula_status) == 'pre-filled' || strtolower($eula_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($eula_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </td>
                                                                        <?php if (strtolower($eula_status) == 'not sent') { ?>
                                                                            <td class="text-center">
                                                                                <form id="form_generate_eula_<?php echo $company_documents['sid'] ?>" method="post" action="<?php echo base_url('manage_admin/documents'); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="generate_form" />
                                                                                    <input type="hidden" id="form_to_send" name="form_to_send" value="eula" />
                                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_documents['sid']; ?>" />
                                                                                    <input type="hidden" id="company_name" name="company_name" value="<?php echo $company_documents['CompanyName']; ?>" />
                                                                                    <input type="hidden" id="company_admin_email" name="company_admin_email" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['email'] : 'ahassan.egenie@gmail.com'); ?>" />
                                                                                    <input type="hidden" id="company_admin_full_name" name="company_admin_full_name" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['first_name'] . ' ' . $company_documents['administrator']['last_name'] : 'James Taylor' ); ?>" />
                                                                                    <button type="button" class="hr-edit-btn btn-block" onclick="fSendForm('eula', 'generate', '<?php echo ucwords($company_documents['CompanyName']); ?>', '<?php echo $company_documents['sid']; ?>');">Generate</button>
                                                                                </form>
                                                                            </td>
                                                                        <?php } elseif (strtolower($eula_status) == 'generated') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_end_user_license_agreement' . '/' . $company_documents['eula']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Pre-fill</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($eula_status) == 'pre-filled' || strtolower($eula_status) == 'sent') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_end_user_license_agreement' . '/' . $company_documents['eula']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Edit</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($eula_status) == 'signed') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_end_user_license_agreement' . '/' . $company_documents['eula']['verification_key'] . '/view'); ?>" class="hr-edit-btn btn-block">View</a>
                                                                                <a  href="<?php echo base_url('manage_admin/documents/regenerate_enduser_license_agreement' . '/' . $company_documents['eula']['verification_key']); ?>" class="hr-edit-btn btn-block">Re-Generate</a>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <td class="text-center">
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $company_contacts_status)); ?>"><?php echo ucwords(str_replace('-', ' ', $company_contacts_status)) ?></span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if (strtolower($company_contacts_status) == 'not sent' || strtolower($company_contacts_status) == 'generated' || strtolower($company_contacts_status) == 'pre-filled' || strtolower($company_contacts_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($company_contacts_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </td>
                                                                        <?php if (strtolower($company_contacts_status) == 'not sent') { ?>
                                                                            <td class="text-center">
                                                                                <form id="form_generate_company_contacts_<?php echo $company_documents['sid'] ?>" method="post" action="<?php echo base_url('manage_admin/documents'); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="generate_form" />
                                                                                    <input type="hidden" id="form_to_send" name="form_to_send" value="company_contacts" />
                                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_documents['sid']; ?>" />
                                                                                    <input type="hidden" id="company_name" name="company_name" value="<?php echo $company_documents['CompanyName']; ?>" />
                                                                                    <input type="hidden" id="company_admin_email" name="company_admin_email" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['email'] : 'ahassan.egenie@gmail.com'); ?>" />
                                                                                    <input type="hidden" id="company_admin_full_name" name="company_admin_full_name" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['first_name'] . ' ' . $company_documents['administrator']['last_name'] : 'James Taylor' ); ?>" />
                                                                                    <button type="button" class="hr-edit-btn btn-block" onclick="fSendForm('company_contacts', 'generate', '<?php echo ucwords($company_documents['CompanyName']); ?>', '<?php echo $company_documents['sid']; ?>');">Generate</button>
                                                                                </form>
                                                                            </td>
                                                                        <?php } elseif (strtolower($company_contacts_status) == 'generated') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_company_contacts' . '/' . $company_documents['contacts']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Pre-fill</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($company_contacts_status) == 'pre-filled' || strtolower($company_contacts_status) == 'sent') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_company_contacts' . '/' . $company_documents['contacts']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Edit</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($company_contacts_status) == 'signed') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_company_contacts' . '/' . $company_documents['contacts']['verification_key'] . '/view'); ?>" class="hr-edit-btn btn-block">View</a>
                                                                                <a  href="<?php echo base_url('manage_admin/documents/regenerate_company_contacts_document' . '/' . $company_documents['contacts']['verification_key']); ?>" class="hr-edit-btn btn-block">Re-Generate</a>
                                                                            </td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                <?php } 
                                                                
                                                            if(!empty($inactive_companies)) {
                                                                foreach ($inactive_companies as $company_documents) {                                                                  
                                                                    $rec_status = 'Not Sent';
                                                                    $cc_status = 'Not Sent';
                                                                    $eula_status = 'Not Sent';
                                                                    $company_contacts_status = 'Not Sent';

                                                                    if (!empty($company_documents['rec_payment_auth'])) {
                                                                        $rec_status = $company_documents['rec_payment_auth']['status'];
                                                                    }

                                                                    if (!empty($company_documents['cc_auth'])) {
                                                                        $cc_status = $company_documents['cc_auth']['status'];
                                                                    }

                                                                    if (!empty($company_documents['eula'])) {
                                                                        $eula_status = $company_documents['eula']['status'];
                                                                    }

                                                                    if (!empty($company_documents['contacts'])) {
                                                                        $company_contacts_status = $company_documents['contacts']['status'];
                                                                    } ?>
                                                                    
                                                                    <tr>
                                                                        <td><?php echo ucwords($company_documents['CompanyName']); ?><br>
                                                                            <?php   if ($company_documents['active'] == 1) { ?>
                                                                                        <span style="color:green;">Active</span>
                                                                            <?php   } else { ?>
                                                                                        <span style="color:red;">In-Active</span>
                                                                            <?php   } ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $cc_status)); ?>"><?php echo ucwords(str_replace('-', ' ', $cc_status)) ?></span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if (strtolower($cc_status) == 'not sent' || strtolower($cc_status) == 'generated' || strtolower($cc_status) == 'pre-filled' || strtolower($cc_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($cc_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </td>
                                                                        <?php if (strtolower($cc_status) == 'not sent') { ?>
                                                                            <td class="text-center">
                                                                                <form id="form_generate_credit_card_authorization_<?php echo $company_documents['sid'] ?>" method="post" action="<?php echo base_url('manage_admin/documents'); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="generate_form" />
                                                                                    <input type="hidden" id="form_to_send" name="form_to_send" value="credit_card_authorization" />
                                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_documents['sid']; ?>" />
                                                                                    <input type="hidden" id="company_name" name="company_name" value="<?php echo $company_documents['CompanyName']; ?>" />
                                                                                    <input type="hidden" id="company_admin_email" name="company_admin_email" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['email'] : 'ahassan.egenie@gmail.com'); ?>" />
                                                                                    <input type="hidden" id="company_admin_full_name" name="company_admin_full_name" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['first_name'] . ' ' . $company_documents['administrator']['last_name'] : 'James Taylor' ); ?>" />
                                                                                    <button type="button" class="hr-edit-btn btn-block" onclick="fSendForm('credit_card_authorization', 'generate', '<?php echo ucwords($company_documents['CompanyName']); ?>', '<?php echo $company_documents['sid']; ?>');">Generate</button>
                                                                                </form>
                                                                            </td>
                                                                        <?php } elseif (strtolower($cc_status) == 'generated') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_credit_card_authorization' . '/' . $company_documents['cc_auth']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Pre-fill</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($cc_status) == 'pre-filled' || strtolower($cc_status) == 'sent') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_credit_card_authorization' . '/' . $company_documents['cc_auth']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Edit</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($cc_status) == 'signed') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_credit_card_authorization' . '/' . $company_documents['cc_auth']['verification_key'] . '/view'); ?>" class="hr-edit-btn btn-block">View</a>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <td class="text-center">
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $eula_status)); ?>"><?php echo ucwords(str_replace('-', ' ', $eula_status)) ?></span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if (strtolower($eula_status) == 'not sent' || strtolower($eula_status) == 'generated' || strtolower($eula_status) == 'pre-filled' || strtolower($eula_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($eula_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </td>
                                                                        <?php if (strtolower($eula_status) == 'not sent') { ?>
                                                                            <td class="text-center">
                                                                                <form id="form_generate_eula_<?php echo $company_documents['sid'] ?>" method="post" action="<?php echo base_url('manage_admin/documents'); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="generate_form" />
                                                                                    <input type="hidden" id="form_to_send" name="form_to_send" value="eula" />
                                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_documents['sid']; ?>" />
                                                                                    <input type="hidden" id="company_name" name="company_name" value="<?php echo $company_documents['CompanyName']; ?>" />
                                                                                    <input type="hidden" id="company_admin_email" name="company_admin_email" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['email'] : 'ahassan.egenie@gmail.com'); ?>" />
                                                                                    <input type="hidden" id="company_admin_full_name" name="company_admin_full_name" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['first_name'] . ' ' . $company_documents['administrator']['last_name'] : 'James Taylor' ); ?>" />
                                                                                    <button type="button" class="hr-edit-btn btn-block" onclick="fSendForm('eula', 'generate', '<?php echo ucwords($company_documents['CompanyName']); ?>', '<?php echo $company_documents['sid']; ?>');">Generate</button>
                                                                                </form>
                                                                            </td>
                                                                        <?php } elseif (strtolower($eula_status) == 'generated') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_end_user_license_agreement' . '/' . $company_documents['eula']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Pre-fill</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($eula_status) == 'pre-filled' || strtolower($eula_status) == 'sent') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_end_user_license_agreement' . '/' . $company_documents['eula']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Edit</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($eula_status) == 'signed') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_end_user_license_agreement' . '/' . $company_documents['eula']['verification_key'] . '/view'); ?>" class="hr-edit-btn btn-block">View</a>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <td class="text-center">
                                                                            <span class="<?php echo strtolower(str_replace(' ', '-', $company_contacts_status)); ?>"><?php echo ucwords(str_replace('-', ' ', $company_contacts_status)) ?></span>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if (strtolower($company_contacts_status) == 'not sent' || strtolower($company_contacts_status) == 'generated' || strtolower($company_contacts_status) == 'pre-filled' || strtolower($company_contacts_status) == 'sent') { ?><img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>"><?php } elseif (strtolower($company_contacts_status) == 'signed') { ?><img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>"><?php } ?>
                                                                        </td>
                                                                        <?php if (strtolower($company_contacts_status) == 'not sent') { ?>
                                                                            <td class="text-center">
                                                                                <form id="form_generate_company_contacts_<?php echo $company_documents['sid'] ?>" method="post" action="<?php echo base_url('manage_admin/documents'); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="generate_form" />
                                                                                    <input type="hidden" id="form_to_send" name="form_to_send" value="company_contacts" />
                                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_documents['sid']; ?>" />
                                                                                    <input type="hidden" id="company_name" name="company_name" value="<?php echo $company_documents['CompanyName']; ?>" />
                                                                                    <input type="hidden" id="company_admin_email" name="company_admin_email" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['email'] : 'ahassan.egenie@gmail.com'); ?>" />
                                                                                    <input type="hidden" id="company_admin_full_name" name="company_admin_full_name" value="<?php echo (!empty($company_documents['administrator']) ? $company_documents['administrator']['first_name'] . ' ' . $company_documents['administrator']['last_name'] : 'James Taylor' ); ?>" />
                                                                                    <button type="button" class="hr-edit-btn btn-block" onclick="fSendForm('company_contacts', 'generate', '<?php echo ucwords($company_documents['CompanyName']); ?>', '<?php echo $company_documents['sid']; ?>');">Generate</button>
                                                                                </form>
                                                                            </td>
                                                                        <?php } elseif (strtolower($company_contacts_status) == 'generated') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_company_contacts' . '/' . $company_documents['contacts']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Pre-fill</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($company_contacts_status) == 'pre-filled' || strtolower($company_contacts_status) == 'sent') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_company_contacts' . '/' . $company_documents['contacts']['verification_key'] . '/pre_fill'); ?>" class="hr-edit-btn btn-block">Edit</a>
                                                                            </td>
                                                                        <?php } elseif (strtolower($company_contacts_status) == 'signed') { ?>
                                                                            <td class="text-center">
                                                                                <a  href="<?php echo base_url('form_company_contacts' . '/' . $company_documents['contacts']['verification_key'] . '/view'); ?>" class="hr-edit-btn btn-block">View</a>
                                                                            </td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                <?php }
                                                            }  ?>
                                                            </tbody>
                                                        </table>
                                                    </div>                                           
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <?php if (isset($company_sid) && $company_sid > 0) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="heading-title">
                                                        <h2 class="page-title">Documents</h2>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="hr-box-header"></div>
                                                            <div class="table-responsive table-outer">
                                                                <table class="table table-bordered table-hover table-stripped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th rowspan="2" class="text-center">Document Name</th>
                                                                            <th colspan="2" class="text-center">Uploaded Date</th>
                                                                            <th rowspan="2" class="text-center">Status</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th class="text-center">By Admin</th>
                                                                            <th class="text-center">By Client</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (isset($companies_uploaded_documents) && !empty($companies_uploaded_documents)) { ?>
                                                                            <?php foreach ($companies_uploaded_documents as $document) { ?>
                                                                                <tr>
                                                                                    <td class="text-center"><?php echo $document['document_name']; ?></td>
                                                                                    <td class="text-center">
                                                                                        <p>
                                                                                            <?php echo convert_date_to_frontend_format($document['admin_upload_date'], true); ?>
                                                                                        </p>
                                                                                        <?php if ($document['admin_aws_filename'] != '') { ?>
                                                                                            <p>
                                                                                                <a class="hr-edit-btn btn-block" href="<?php echo AWS_S3_BUCKET_URL . $document['admin_aws_filename']; ?>">Download</a>
                                                                                            </p>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <p>
                                                                                            <?php echo convert_date_to_frontend_format($document['client_upload_date'], true); ?>
                                                                                        </p>
                                                                                        <?php if ($document['client_aws_filename'] != '') { ?>
                                                                                            <p>
                                                                                                <a class="hr-edit-btn btn-block" href="<?php echo AWS_S3_BUCKET_URL . $document['client_aws_filename']; ?>">Download</a>
                                                                                            </p>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                    <td class="text-center"><?php echo ucwords($document['status']); ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <tr>
                                                                                <td colspan="5" class="no-data">No Documents</td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="hr-box-header hr-box-footer"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="heading-title">
                                                                <h2 class="page-title">Upload Additional Document</h2>
                                                            </div>
                                                            <div class="">
                                                                <form id="form_upload_document" method="post" enctype="multipart/form-data" action="<?php echo base_url('manage_admin/documents/' . $company_sid) ?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="upload_document" />
                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                    <div class="field-row field-row-autoheight">
                                                                        <label>Name</label>
                                                                        <input type="text" id="document_name" name="document_name" class="hr-form-fileds" />
                                                                    </div>
                                                                    <div class="field-row field-row-autoheight">
                                                                        <label>Short Description</label>
                                                                        <textarea id="document_short_description" name="document_short_description" class="hr-form-fileds field-row-autoheight" rows="4"></textarea>
                                                                    </div>
                                                                    <input class="pull-left" type="file" name="document" id="document" />
                                                                    <button class="site-btn pull-right" type="button" onclick="fValidateDocument();">Upload</button>
                                                                    <div class="clearfix"></div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
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
    $(document).keypress(function(e) {
        if(e.which == 13) { // enter pressed
            $('#search_btn').click();
        }
    });

    $(document).ready(function(){
        $('#name').on('keyup', update_url);
        $('#name').on('blur', update_url);
        $('#search_btn').on('click', function(e){
            e.preventDefault();
            update_url();
            window.location = $(this).attr('href').toString();
        });
    });

    function update_url(){
        var url = '<?php echo isset($company_sid) ? base_url('manage_admin/documents/'.$company_sid) : base_url('manage_admin/documents/0'); ?>';
        var name = $('#name').val();

        name = name == '' ? 'all' : name;
        url = url + '/' + encodeURIComponent(name);
        $('#search_btn').attr('href', url);
    }

    function fSendForm(form_name, action, company_name, company_sid) {
        var message_name = '';
        if (form_name == 'credit_card_authorization') {
            message_name = 'Credit Card Authorization';
        } else if (form_name == 'eula') {
            message_name = 'End User License Agreement';
        }

        alertify.confirm(
                'Are You Sure?',
                'Are you sure you want to ' + action + ' <strong>' + message_name + '</strong> form to <strong>' + company_name + '</strong> ?',
                function () {
                    var form_id = 'form_' + action + '_' + form_name + '_' + company_sid;
                    console.log(form_id);
                    $('#' + form_id).submit();
                },
                function () {
                    //cancel
                }
        )
    }

    function fValidateDocument() {
        $('#form_upload_document').validate({
            rules: {
                'document_name': {
                    required: true
                },
                'document': {
                    required: true,
                    extension: 'docx|rtf|doc|pdf'
                }
            },
            messages: {
                'document_name': {
                    required: 'Document Name is Required'
                },
                'document': {
                    required: 'Please Select a File',
                    extension: 'File can be .doc, .docx, .pdf only'
                }
            }
        });

        if ($('#form_upload_document').valid()) {
            $('#form_upload_document').submit();
        } else {
            alertify.error('Invalid File Type');
        }
    }
</script>