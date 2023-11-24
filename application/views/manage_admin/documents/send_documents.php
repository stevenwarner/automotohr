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
                                        <span class="page-title"><i class="fa fa-file-o"></i>Send Documents</span>
                                        <a href="<?php echo base_url('manage_admin/documents/' . $company_sid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Documents</a>
                                        <a href="<?php echo base_url('manage_admin/companies') ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Companies</a>
                                        <?php if (isset($company_sid) && $company_sid > 0) { ?>
                                            <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid) ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                        <?php } ?>
                                    </div>
                                    <div class="add-new-company">
                                        <form id="form_documents">
                                            <?php if (!empty($companies_documents)) { ?>
                                                <div class="heading-title">
                                                    <span class="page-title">Automated Forms</span>
                                                </div>
                                                <div class="row">
                                                    <?php $companies_documents = $companies_documents[0]; ?>
                                                    <?php if (!empty($companies_documents['cc_auth'])) { ?>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label class="package_label" for="cc_auth">
                                                                <div class="img-thumbnail text-center package-info-box eq-height">
                                                                    <figure><i class="fa fa-file-o" style="font-size: 75px"></i></figure>
                                                                    <br />
                                                                    <div class="caption">
                                                                        <p>Credit Card Authorization Form</p>
                                                                    </div>
                                                                    <input class="select-package" type="checkbox" id="cc_auth" name="forms[]" value="cc_auth" data-k="<?php echo $companies_documents['cc_auth']['verification_key']; ?>" />
                                                                </div>
                                                            </label>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if (!empty($companies_documents['eula'])) { ?>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label class="package_label" for="eula">
                                                                <div class="img-thumbnail text-center package-info-box eq-height">
                                                                    <figure><i class="fa fa-file-o" style="font-size: 75px"></i></figure>
                                                                    <br />
                                                                    <div class="caption">
                                                                        <p>End User License Agreement</p>
                                                                    </div>
                                                                    <input class="select-package" type="checkbox" id="eula" name="forms[]" value="eula" data-k="<?php echo $companies_documents['eula']['verification_key']; ?>" />
                                                                </div>
                                                            </label>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if (!empty($companies_documents['contacts'])) { ?>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label class="package_label" for="contacts">
                                                                <div class="img-thumbnail text-center package-info-box eq-height">
                                                                    <figure><i class="fa fa-file-o" style="font-size: 75px"></i></figure>
                                                                    <br />
                                                                    <div class="caption">
                                                                        <p>Company Contacts Form</p>
                                                                    </div>
                                                                    <input class="select-package" type="checkbox" id="contacts" name="forms[]" value="contacts" data-k="<?php echo $companies_documents['contacts']['verification_key']; ?>" />
                                                                </div>
                                                            </label>
                                                        </div>
                                                    <?php } ?>



                                                    <?php if (!empty($companies_documents['fpa'])) { ?>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label class="package_label" for="fpa">
                                                                <div class="img-thumbnail text-center package-info-box eq-height">
                                                                    <figure><i class="fa fa-file-o" style="font-size: 75px"></i></figure>
                                                                    <br />
                                                                    <div class="caption">
                                                                        <p>Payroll Agreement</p>
                                                                    </div>
                                                                    <input class="select-package" type="checkbox" id="fpa" name="forms[]" value="fpa" data-k="<?php echo $companies_documents['fpa']['verification_key']; ?>" />
                                                                </div>
                                                            </label>
                                                        </div>
                                                    <?php } ?>


                                                </div>

                                                <div class="heading-title">
                                                    <span class="page-title">Uploaded Documents</span>
                                                </div>
                                                <div class="row">
                                                    <?php if (!empty($companies_documents['uploaded_documents'])) { ?>
                                                        <?php foreach ($companies_documents['uploaded_documents'] as $uploaded_document) { ?>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <label class="package_label" for="document_checkbox_<?php echo $uploaded_document['sid']; ?>">
                                                                    <div class="img-thumbnail text-center package-info-box eq-height">
                                                                        <figure>
                                                                            <i class="fa fa-file-o" style="font-size: 75px"></i>
                                                                        </figure>
                                                                        <br />
                                                                        <div class="caption">
                                                                            <p><?php echo $uploaded_document['document_name']; ?></p>
                                                                        </div>
                                                                        <input class="select-package" type="checkbox" id="document_checkbox_<?php echo $uploaded_document['sid']; ?>" name="documents[]" value="document_<?php echo $uploaded_document['sid']; ?>" data-k="<?php echo $uploaded_document['verification_key']; ?>" />
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <div class="col-xs-12">
                                                            <h4>No Document Found</h4>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </form>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="heading-title">
                                                    <span class="page-title">Send Email To</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row send-email-to">
                                            <div class="col-xs-12">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a data-toggle="tab" href="#to_single_email">To Email</a></li>
                                                    <li><a data-toggle="tab" href="#to_employees">To Employees</a></li>
                                                    <li><a data-toggle="tab" href="#to_company_admin">To Company Admin</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div id="to_single_email" class="tab-pane fade in active">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="hr-user-form">
                                                                    <form id="form_send_to_single_email" method="post" enctype="multipart/form-data" action="<?php echo base_url('manage_admin/documents/send/' . $company_sid); ?>">
                                                                        <input type="hidden" id="cc_auth" name="cc_auth" value="0" class="cc_auth" />
                                                                        <input type="hidden" id="eula" name="eula" value="0" class="eula" />
                                                                        <input type="hidden" id="contacts" name="contacts" value="0" class="contacts" />
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="send_to_single_email" />
                                                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                        <input type="hidden" id="company_name" name="company_name" value="<?php echo $companies_documents['CompanyName']; ?>" />
                                                                        <input type="hidden" id="fpa" name="fpa" value="0" class="fpa" />


                                                                        <?php if (!empty($companies_documents['uploaded_documents'])) { ?>
                                                                            <?php foreach ($companies_documents['uploaded_documents'] as $uploaded_document) { ?>
                                                                                <input type="hidden" class="document_<?php echo $uploaded_document['sid']; ?>" id="document_<?php echo $uploaded_document['sid']; ?>" name="documents[]" value="0" />
                                                                            <?php } ?>
                                                                        <?php } ?>

                                                                        <ul>
                                                                            <li>
                                                                                <label for="email">Email <span class="hr-required">*</span></label>
                                                                                <div class="hr-fields-wrap">
                                                                                    <input type="email" data-rule-email="true" data-msg-email="Please Provide a valid Email" class="hr-form-fileds" name="email" id="email" />
                                                                                </div>
                                                                            </li>
                                                                            <li>
                                                                                <label for="message">Message</label>
                                                                                <div class="hr-fields-wrap">
                                                                                    <textarea style="padding:10px; height:200px;" class="hr-form-fileds ckeditor" name="message"><?php echo set_value('message'); ?></textarea>
                                                                                </div>
                                                                            </li>
                                                                            <li>
                                                                                <button type="button" class="site-btn" onclick="fValidateDocumentsAndSubmit('form_send_to_single_email');">Send To Email</button>
                                                                            </li>
                                                                        </ul>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="to_employees" class="tab-pane fade">
                                                        <form id="form_send_to_employees" method="post" enctype="multipart/form-data" action="<?php echo base_url('manage_admin/documents/send/' . $company_sid); ?>">
                                                            <input type="hidden" id="cc_auth" name="cc_auth" value="0" class="cc_auth" />
                                                            <input type="hidden" id="eula" name="eula" value="0" class="eula" />
                                                            <input type="hidden" id="contacts" name="contacts" value="0" class="contacts" />
                                                            <input type="hidden" id="perform_action" name="perform_action" value="send_to_employees" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                            <input type="hidden" id="company_name" name="company_name" value="<?php echo $companies_documents['CompanyName']; ?>" />
                                                            <input type="hidden" id="fpa" name="fpa" value="0" class="fpa" />


                                                            <?php if (!empty($companies_documents['uploaded_documents'])) { ?>
                                                                <?php foreach ($companies_documents['uploaded_documents'] as $uploaded_document) { ?>
                                                                    <input type="hidden" class="document_<?php echo $uploaded_document['sid']; ?>" id="document_<?php echo $uploaded_document['sid']; ?>" name="documents[]" value="0" />
                                                                <?php } ?>
                                                            <?php } ?>

                                                            <div class="multiselect-wrp">
                                                                <select style="width:350px; height:40px;" multiple class="chosen-select" name="employee_emails[]">
                                                                    <?php foreach ($employees as $employee) { ?>
                                                                        <option value="<?php echo $employee['email']; ?>"><?php echo ucwords($employee['first_name'] . ' ' . $employee['last_name']); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="hr-user-form">
                                                                <ul>
                                                                    <li>
                                                                        <label for="message">Message</label>
                                                                        <div class="hr-fields-wrap">
                                                                            <textarea style="padding:10px; height:200px;" class="hr-form-fileds ckeditor" name="message"><?php echo set_value('message'); ?></textarea>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <button type="button" class="site-btn" onclick="fValidateDocumentsAndSubmit('form_send_to_employees');">Send To Employees</button>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div id="to_company_admin" class="tab-pane fade">
                                                        <div class="hr-user-form">
                                                            <form id="form_send_to_admin" method="post" enctype="multipart/form-data" action="<?php echo base_url('manage_admin/documents/send/' . $company_sid); ?>">
                                                                <input type="hidden" id="cc_auth" name="cc_auth" value="0" class="cc_auth" />
                                                                <input type="hidden" id="eula" name="eula" value="0" class="eula" />
                                                                <input type="hidden" id="contacts" name="contacts" value="0" class="contacts" />
                                                                <input type="hidden" id="perform_action" name="perform_action" value="send_to_company_admin" />
                                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                                <input type="hidden" id="company_name" name="company_name" value="<?php echo $companies_documents['CompanyName']; ?>" />
                                                                <input type="hidden" id="fpa" name="fpa" value="0" class="fpa" />

                                                                <?php if (!empty($companies_documents['uploaded_documents'])) { ?>
                                                                    <?php foreach ($companies_documents['uploaded_documents'] as $uploaded_document) { ?>
                                                                        <input type="hidden" class="document_<?php echo $uploaded_document['sid']; ?>" id="document_<?php echo $uploaded_document['sid']; ?>" name="documents[]" value="0" />
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <ul>
                                                                    <li>
                                                                        <label for="admin_full_name">Admin Full Name</label>
                                                                        <div class="hr-fields-wrap">
                                                                            <?php $admin_full_name = $companies_documents['administrator']['first_name'] . ' ' . $companies_documents['administrator']['last_name']; ?>
                                                                            <input readonly="readonly" type="text" class="hr-form-fileds" value="<?php echo ucwords($admin_full_name); ?>" name="admin_full_name" id="admin_full_name" />
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label for="admin_full_name">Email Address</label>
                                                                        <div class="hr-fields-wrap">
                                                                            <input readonly="readonly" type="text" class="hr-form-fileds" value="<?php echo $companies_documents['administrator']['email'] ?>" name="admin_email_address" id="admin_email_address" />
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label for="admin_full_name">Alternate Email Address</label>
                                                                        <div class="hr-fields-wrap">
                                                                            <input readonly="readonly" type="text" class="hr-form-fileds" value="<?php echo $companies_documents['administrator']['alternative_email'] ?>" name="admin_alt_email_address" id="admin_alt_email_address" />
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="hr-fields-wrap">
                                                                            <div class="row">
                                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                                    <label class="control control--radio admin-access-level">
                                                                                        <input type="radio" name="send_to" id="send_to_primary" value="primary" checked="checked" />
                                                                                        Send to Primary Email
                                                                                        <div class="control__indicator"></div>
                                                                                    </label><br />
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                                    <label class="control control--radio admin-access-level">
                                                                                        <input type="radio" name="send_to" id="send_to_alt" value="alternate" />
                                                                                        Send to Alternate Email
                                                                                        <div class="control__indicator"></div>
                                                                                    </label><br />
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                                    <label class="control control--radio admin-access-level">
                                                                                        <input type="radio" name="send_to" id="send_to_both" value="both" />
                                                                                        Send to Both Emails
                                                                                        <div class="control__indicator"></div>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <label for="message">Message</label>
                                                                        <div class="hr-fields-wrap">
                                                                            <textarea style="padding:10px; height:200px;" class="hr-form-fileds ckeditor" name="message"><?php echo set_value('message'); ?></textarea>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <button type="button" class="site-btn" onclick="fValidateDocumentsAndSubmit('form_send_to_admin');">Send To Employees</button>
                                                                    </li>
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
    </div>
</div>
<script type="text/javascript">
    function fValidateDocumentsAndSubmit(form_id) {
        var checked_docs = $('.select-package:checked');

        if (checked_docs.length > 0) {
            $('#' + form_id).submit();
        } else {
            alertify.error('Please select document(s)');
        }
    }

    $(document).ready(function() {
        $('.select-package').click(function() {
            $('.select-package:not(:checked)').parent().removeClass("selected-package");
            $('.select-package:checked').parent().addClass("selected-package");

            var checked = $(this).prop('checked');
            //console.log($(this).attr('data-k'));
            if (checked) {
                $('.' + $(this).val()).val($(this).attr('data-k'));
            } else {
                $('.' + $(this).val()).val(0);
            }
        });

        $('.select-package').trigger('click');
    });

    // Multiselect
    var config = {
        '.chosen-select': {}
    }

    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
</script>