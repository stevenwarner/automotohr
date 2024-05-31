<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management/documents_group_management'); ?>"><i class="fa fa-chevron-left"></i>Group Management</a>
                                    <?php echo $group_name; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="upload-new-doc-heading">
                                        <i class="fa fa-file-text"></i>
                                        <?php echo $title; ?>&nbsp; (<?php echo $group_name; ?>)
                                    </div>
                                    <p class="upload-file-type">You can easily Assign documents for group</p>
                                    <div class="form-wrp">
                                        <form id="form_new_document_group" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" name="perform_action" value="assign_documents" />
                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <label class="control control--checkbox pull-left">
                                                        <input type="checkbox" id="selectall">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <h4 class="hr-registered pull-left">
                                                        Select Documents
                                                    </h4>
                                                    <div class="text-right">(<span id="count_documents"></span>) Assigned</div>
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="row">
                                                        <!-- system documents start -->
                                                        <div class="col-xs-12">
                                                            <h4><strong>Employment Eligibility Verification Documents</strong></h4>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                W4 Fillable
                                                                <input class="doc_checkbox" name="system_documents[]" value="w4" type="checkbox" <?php echo $group['w4'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                W9 Fillable
                                                                <input class="doc_checkbox" name="system_documents[]" value="w9" type="checkbox" <?php echo $group['w9'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                I9 Fillable
                                                                <input class="doc_checkbox" name="system_documents[]" value="i9" type="checkbox" <?php echo $group['i9'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <?php if ($this->session->userdata('logged_in')['portal_detail']['eeo_on_document_center']) { ?>
                                                            <div class="col-xs-6">
                                                                <label class="control control--checkbox font-normal">
                                                                    EEOC Fillable
                                                                    <input class="doc_checkbox" name="system_documents[]" value="eeoc" type="checkbox" <?php echo $group['eeoc'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if ($companyStateForms) { ?>
                                                            <div class="col-xs-12">
                                                                <hr />
                                                            </div>
                                                            <!-- state forms -->
                                                            <div class="col-xs-12">
                                                                <h4><strong>State forms</strong></h4>
                                                            </div>
                                                            <?php foreach ($companyStateForms as $form) {
                                                            ?>
                                                                <div class="col-xs-12">
                                                                    <label class="control control--checkbox font-normal">
                                                                        <?= $form["title"]; ?>
                                                                        <input class="doc_checkbox" name="state_forms[]" <?= in_array(
                                                                                                                                $form["sid"],
                                                                                                                                $selected_state_forms
                                                                                                                            ) ? "checked" : ""; ?> value="<?= $form["sid"]; ?>" type="checkbox">
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                        <?php
                                                            }
                                                        } ?>
                                                        <!-- system documents end -->

                                                        <div class="col-xs-12">
                                                            <hr />
                                                        </div>
                                                        <!-- general documents start -->
                                                        <div class="col-xs-12">
                                                            <h4><strong>General Documents</strong></h4>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                Dependents
                                                                <input class="doc_checkbox" name="general_documents[]" value="dependents" type="checkbox" <?php echo $group['dependents'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                Direct Deposit Information
                                                                <input class="doc_checkbox" name="general_documents[]" value="direct_deposit" type="checkbox" <?php echo $group['direct_deposit'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                Drivers License Information
                                                                <input class="doc_checkbox" name="general_documents[]" value="drivers_license" type="checkbox" <?php echo $group['drivers_license'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                Emergency Contacts
                                                                <input class="doc_checkbox" name="general_documents[]" value="emergency_contacts" type="checkbox" <?php echo $group['emergency_contacts'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label class="control control--checkbox font-normal">
                                                                Occupational License Information
                                                                <input class="doc_checkbox" name="general_documents[]" value="occupational_license" type="checkbox" <?php echo $group['occupational_license'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <!-- general documents end -->

                                                        <?php if (checkIfAppIsEnabled('performanceevaluation')) { ?>
                                                            <div class="col-xs-12">
                                                                <hr />
                                                            </div>
                                                            <div class="col-xs-12">
                                                                <h4><strong>Employee Performance Evaluation</strong></h4>
                                                            </div>

                                                            <div class="col-xs-12">
                                                                <label class="control control--checkbox font-normal">
                                                                    Employee Performance Evaluation
                                                                    <input class="doc_checkbox" name="epe" value="epe" type="checkbox" <?php echo $group['performance_evaluation'] == 1 ? 'checked="checked"' : ''; ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        <?php
                                                        } ?>


                                                        <!-- company documents start -->
                                                        <div class="col-xs-12">
                                                            <hr>
                                                            <h4><strong>Company Documents</strong></h4>
                                                        </div>
                                                        <?php if (!empty($documents)) {
                                                            foreach ($documents as $key => $document) {
                                                                $cat_name = 'documents'; ?>
                                                                <div class="col-xs-6">
                                                                    <label class="control control--checkbox font-normal <?php echo $document['archive'] == 1 ? 'red' : ''; ?>">
                                                                        <?php $document_status = $document['archive'] == 1 ? '(Archive)' : '(Active)'; ?>
                                                                        <?php echo $document['document_title'] . '' . $document_status; ?>
                                                                        <?php if ($document['archive'] != 1) { ?>
                                                                            <input class="doc_checkbox" name="documents[]" value="<?php echo $document['sid']; ?>" type="checkbox" <?php echo in_array($document['sid'], $assigned_documents) ? 'checked="checked"' : ''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        <?php } else { ?>
                                                                            <input class="disable_doc_checkbox" name="documents[]" type="checkbox">
                                                                            <div class="control__indicator"></div>
                                                                        <?php } ?>
                                                                    </label>
                                                                </div>
                                                            <?php   } ?>
                                                        <?php } else { ?>
                                                            <div class="col-xs-12 text-center">
                                                                <span class="no-data">No Documents</span>
                                                            </div>
                                                        <?php } ?>
                                                        <!-- company documents end -->

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group autoheight">
                                                <div class="row">
                                                    <div class="col-xs-12" style="text-align: right;">
                                                        <button type="submit" id="gen_boc_btn" class="btn btn-success" onclick="validate_form();">Save</button>
                                                        <a href="<?php echo base_url('hr_documents_management/documents_group_management'); ?>" class="btn black-btn">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    $(document).ready(function() {
        $('#count_documents').text($('.doc_checkbox:checked').length);
        $(".disable_doc_checkbox").click(function(e) {
            e.preventDefault();
            alertify.error('Archive document not allowed to select!');
        });

        $('input[type="checkbox"]').click(function() {
            $('#count_documents').text($('.doc_checkbox:checked').length);
        });
    });

    $('#selectall').click(function(event) {
        if (this.checked) {
            $('.doc_checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $('.doc_checkbox').each(function() {
                this.checked = false;
            });
        }
    });
</script>