<?php
$allDocuments = [];
$requiredMessage = 'This document is required to complete the process.';
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('manage_ems'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Employee Management System
                                    </a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) { ?>
                                        <a href="<?php echo base_url('hr_documents_management/upload_new_document'); ?>" class="btn btn-success">Upload <i class="fa fa-file" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) { ?>
                                        <a href="<?php echo base_url('hr_documents_management/generate_new_document'); ?>" class="btn btn-success">Generate <i class="fa fa-file" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document') && checkIfAppIsEnabled('hybrid_document')) { ?>
                                        <a href="<?php echo base_url('hr_documents_management/hybrid_document/add'); ?>" class="btn btn-success">Hybrid Document <i class="fa fa-file" aria-hidden="true"></i></a>
                                    <?php } ?>

                                    <?php if (check_access_permissions_for_view($security_details, 'add_edit_offer_letter')) { ?>
                                        <!-- <a href="<?php echo base_url('hr_documents_management/upload_new_offer_letter'); ?>" class="btn btn-success">Upload Offer Letter / Pay Plans <i class="fa fa-envelope" aria-hidden="true"></i></a> -->
                                        <a href="<?php echo base_url('hr_documents_management/generate_new_offer_letter'); ?>" class="btn btn-success">Generate Offer Letter / Pay Plans <i class="fa fa-envelope" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'pending_document')) { ?>
                                        <a href="<?php echo base_url('hr_documents_management/people_with_pending_documents'); ?>" class="btn btn-success">Employees With Pending <i class="fa fa-files-o" aria-hidden="true"></i></a>
                                        <a href="<?php echo base_url('hr_documents_management/people_with_pending_federal_fillable'); ?>" class="btn btn-success">Employees With Pending Federal Fillable <i class="fa fa-files-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'pending_document')) { ?>
                                        <a href="<?php echo base_url('hr_documents_management/people_with_pending_employer_documents'); ?>" class="btn btn-success">Managers With Pending <i class="fa fa-files-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <a href="<?php echo base_url('hr_documents_management/documents_group_management'); ?>" class="btn btn-success">Group Management</a>
                                    <a href="<?php echo base_url('hr_documents_management/documents_category_management'); ?>" class="btn btn-success">Category Management</a>
                                    <?php if (check_access_permissions_for_view($security_details, 'view_archive_document')) { ?>
                                        <a href="<?php echo base_url('hr_documents_management/archived_documents'); ?>" class="btn btn-warning">Archived <i class="fa fa-files-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <a href="<?php echo base_url('scheduled_documents'); ?>" class="btn btn-success">Scheduled Document(s)</a>
                                </div>
                            </div>
                            <hr />
                        </div>

                        <?php if (isPayrollOrPlus()) { ?>
                            <?php $this->load->view('employee_performance_evaluation/group'); ?>
                        <?php } ?>

                        <!--start-->
                        <div class="col-md-12">
                            <div class="hr-document-list">
                                <?php if (!empty($active_groups)) {
                                    foreach ($active_groups as $active_group) { ?>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel panel-default ems-documents">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $active_group['sid']; ?>">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                <?php echo $active_group['name']; ?>
                                                                <div class="btn btn-xs btn-success">Active Group</div>
                                                                <div class="pull-right total-records"><b><?php echo 'Total: ' . $active_group['documents_count']; ?></b></div>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse_<?php echo $active_group['sid']; ?>" class="panel-collapse collapse">
                                                        <div class="table-responsive">
                                                            <table class="table table-plane">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-xs-6">Document Name</th>
                                                                        <th class="col-xs-2">Date Created</th>
                                                                        <th class="col-xs-4 text-center" colspan="4">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                    <?php if ($active_group['other_documents']) {?>
                                                                        <?php foreach ($active_group['other_documents'] as $otherDocument) : ?>
                                                                            <tr>
                                                                                <td class="col-xs-6"><?php echo $otherDocument?></td>
                                                                                <td class="col-xs-2"> </td>
                                                                                <td class="col-xs-1"></td>
                                                                                <td class="col-xs-1"></td>
                                                                                <td class="col-xs-1"></td>
                                                                                <td class="col-xs-1"></td>
                                                                                <td class="col-xs-1"></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php } ?>

                                                                    <?php if ($active_group['documents_count'] > 0) {
                                                                        foreach ($active_group['documents'] as $document) { ?>
                                                                            <tr>
                                                                                <td class="col-xs-6"><?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?><?php
                                                                                                                                                                                                                                                                                                                    if ($document['is_confidential']) :
                                                                                                                                                                                                                                                                                                                        echo "<br /><strong>(Confidential)</strong>";
                                                                                                                                                                                                                                                                                                                    endif;
                                                                                                                                                                                                                                                                                                                    ?></td>
                                                                                <td class="col-xs-2">
                                                                                    <?php if ($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                        echo reset_datetime(array('datetime' => $document['date_created'], '_this' => $this));
                                                                                    } else {
                                                                                        echo 'N/A';
                                                                                    } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <form id="form_assign_document_<?php echo $document['document_type']; ?>_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="assign_document" />
                                                                                        <input type="hidden" id="document_type" name="document_type" value="<?php echo $document['document_type']; ?>" />
                                                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                    </form>

                                                                                    <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                        <button data-id="<?= $document['sid']; ?>" class="btn btn-primary btn-block btn-sm js-bulk-assign-btn">Bulk Assign</button>
                                                                                    <?php } else if ($document['document_type'] == 'uploaded') { ?>
                                                                                        <button onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>, '<?php echo $document['document_title']; ?>');" class="btn btn-primary btn-block btn-sm">Bulk Assign</button>
                                                                                    <?php  } else { ?>
                                                                                        <button class="btn btn-primary btn-sm btn-block" onclick="fLaunchModalGen(this);" data-title="<?php echo $document['document_title']; ?>" data-description="<?php echo $document['document_description']; ?>" data-document-type="<?php echo $document['document_type']; ?>" data-fillable="<?=$document["fillable_document_slug"]?1:0;?>" data-document-sid="<?php echo $document['sid']; ?>">Bulk Assign</button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) { ?>
                                                                                        <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                                    <?php   } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                        <button data-id="<?= $document['sid']; ?>" data-from="company" class="btn btn-info btn-sm btn-block js-hybrid-preview">Preview</button>
                                                                                    <?php } else if ($document['document_type'] == 'uploaded') {
                                                                                        $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                        $document_file = pathinfo($document_filename);
                                                                                        $name = explode(".", $document_filename);
                                                                                        $url_segment_original = $name[0]; ?>

                                                                                        <button class="btn btn-info btn-sm btn-block" onclick="fLaunchModal(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-document-sid="<?php echo  $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                                    <?php } else { ?>
                                                                                        <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                                    <?php   } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                        <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                    </form>
                                                                                    <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if (!$document["fillable_document_slug"]) { ?>
                                                                                        <!-- Convert document to Pay Plan -->
                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="convertToPayPlan(<?= $document['sid']; ?>, '<?= $document['document_type']; ?>')">Convert To Pay Plan</button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <button class="btn btn-success btn-sm js-employees-with-pending-documents" data-id="<?= $document['sid']; ?>" data-title="<?= $document['document_title']; ?>">View Employee(s)</button>
                                                                                    <button class="btn btn-success btn-sm jsScheduleDocument" title="Schedule Document" data-id="<?= $document['sid']; ?>" data-title="<?= $document['document_title']; ?>">Schedule Document</button>
                                                                                </td>
                                                                            </tr>
                                                                        <?php }
                                                                    } else { ?>
                                                                        <tr>
                                                                            <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php    }
                                } ?>

                                <?php if (!empty($in_active_groups)) {
                                    foreach ($in_active_groups as $active_group) { ?>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel panel-default ems-documents">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $active_group['sid']; ?>">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                <?php echo $active_group['name']; ?>
                                                                <div class="btn btn-xs btn-danger">Inactive Group</div>
                                                                <div class="pull-right total-records"><b><?php echo 'Total: ' . $active_group['documents_count']; ?></b></div>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse_<?php echo $active_group['sid']; ?>" class="panel-collapse collapse">
                                                        <div class="table-responsive">
                                                            <table class="table table-plane">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-xs-6">Document Name</th>
                                                                        <th class="col-xs-2">Date Created</th>
                                                                        <th class="col-xs-4 text-center" colspan="4">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if ($active_group['documents_count'] > 0) {
                                                                        foreach ($active_group['documents'] as $document) { ?>
                                                                            <tr>
                                                                                <td class="col-xs-6"><?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?><?php
                                                                                                                                                                                                                                                                                                                    if ($document['is_confidential']) :
                                                                                                                                                                                                                                                                                                                        echo "<br /><strong>(Confidential)</strong>";
                                                                                                                                                                                                                                                                                                                    endif;
                                                                                                                                                                                                                                                                                                                    ?></td>
                                                                                <td class="col-xs-2">
                                                                                    <?php if ($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                        echo reset_datetime(array('datetime' => $document['date_created'], '_this' => $this));
                                                                                    } else {
                                                                                        echo 'N/A';
                                                                                    } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <form id="form_assign_document_<?php echo $document['document_type']; ?>_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="assign_document" />
                                                                                        <input type="hidden" id="document_type" name="document_type" value="<?php echo $document['document_type']; ?>" />
                                                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                    </form>
                                                                                    <?php if ($document['document_type'] == 'uploaded') { ?>
                                                                                        <button onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>, '<?php echo $document['document_title']; ?>');" class="btn btn-primary btn-block btn-sm">Bulk Assign</button>
                                                                                    <?php  } else { ?>
                                                                                        <button class="btn btn-primary btn-sm btn-block" data-fillable="<?=$document["fillable_document_slug"]?1:0;?>" onclick="fLaunchModalGen(this);" data-title="<?php echo $document['document_title']; ?>" data-description="<?php echo $document['document_description']; ?>" data-document-type="<?php echo $document['document_type']; ?>" data-document-sid="<?php echo $document['sid']; ?>">Bulk Assign</button>
                                                                                    <?php  } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) { ?>
                                                                                        <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                                    <?php  } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if ($document['document_type'] == 'uploaded') {
                                                                                        $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                        $document_file = pathinfo($document_filename);
                                                                                        $name = explode(".", $document_filename);
                                                                                        $url_segment_original = $name[0]; ?>

                                                                                        <button class="btn btn-info btn-sm btn-block" onclick="fLaunchModal(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-document-sid="<?php echo  $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                                    <?php   } else { ?>
                                                                                        <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                        <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                    </form>
                                                                                    <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                        <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if (!$document["fillable_document_slug"]) { ?>
                                                                                        <!-- Convert document to Pay Plan -->
                                                                                        <button class="btn btn-success btn-sm btn-block" onclick="convertToPayPlan(<?= $document['sid']; ?>, '<?= $document['document_type']; ?>')">Convert To Pay Plan</button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <button class="btn btn-success btn-sm js-employees-with-pending-documents" data-id="<?= $document['sid']; ?>" data-title="<?= $document['document_title']; ?>">View Employee(s)</button>
                                                                                    <button class="btn btn-success btn-sm jsScheduleDocument" title="Schedule Document" data-id="<?= $document['sid']; ?>" data-title="<?= $document['document_title']; ?>">Schedule Document</button>
                                                                                </td>
                                                                            </tr>
                                                                        <?php   }
                                                                    } else { ?>
                                                                        <tr>
                                                                            <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php    }
                                } ?>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="panel panel-default ems-documents">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncategorized_documents">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                        Uncategorized Documents
                                                        <div class="btn btn-xs btn-info">Uncategorized</div>
                                                        <div class="pull-right total-records"><b><?php echo 'Total: ' . count($uncategorized_documents); ?></b></div>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse_uncategorized_documents" class="panel-collapse collapse">
                                                <div class="table-responsive">
                                                    <table class="table table-plane">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-6">Document Name</th>
                                                                <th class="col-xs-2">Date Created</th>
                                                                <th class="col-xs-4 text-center" colspan="4">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (count($uncategorized_documents) > 0) {
                                                                foreach ($uncategorized_documents as $document) { ?>
                                                                    <tr>
                                                                        <td class="col-xs-6"><?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?><?php
                                                                                                                                                                                                                                                                                                            if ($document['is_confidential']) :
                                                                                                                                                                                                                                                                                                                echo "<br /><strong>(Confidential)</strong>";
                                                                                                                                                                                                                                                                                                            endif;
                                                                                                                                                                                                                                                                                                            ?></td>
                                                                        <td class="col-xs-2">
                                                                            <?php if ($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                echo reset_datetime(array('datetime' => $document['date_created'], '_this' => $this));
                                                                            } else {
                                                                                echo 'N/A';
                                                                            } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <form id="form_assign_document_<?php echo $document['document_type']; ?>_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="assign_document" />
                                                                                <input type="hidden" id="document_type" name="document_type" value="<?php echo $document['document_type']; ?>" />
                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                            </form>
                                                                            <?php if ($document['document_type'] == 'uploaded') { ?>
                                                                                <button onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>, '<?php echo $document['document_title']; ?>');" class="btn btn-primary btn-block btn-sm">Bulk Assign</button>
                                                                            <?php } else { ?>
                                                                                <button class="btn btn-primary btn-sm btn-block" data-fillable="<?=$document["fillable_document_slug"]?1:0;?>" onclick="fLaunchModalGen(this);" data-title="<?php echo $document['document_title']; ?>" data-description="<?php echo $document['document_description']; ?>" data-document-type="<?php echo $document['document_type']; ?>" data-document-sid="<?php echo $document['sid']; ?>">Bulk Assign</button>
                                                                            <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) { ?>
                                                                                <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                            <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if ($document['document_type'] == 'uploaded') {
                                                                                $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                $document_file = pathinfo($document_filename);
                                                                                $name = explode(".", $document_filename);
                                                                                $url_segment_original = $name[0]; ?>

                                                                                <button class="btn btn-info btn-sm btn-block" onclick="fLaunchModal(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-document-sid="<?php echo  $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                            <?php   } else { ?>
                                                                                <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                            <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                            </form>
                                                                            <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if (!$document["fillable_document_slug"]) { ?>
                                                                                <!-- Convert document to Pay Plan -->
                                                                                <button class="btn btn-success btn-sm btn-block" onclick="convertToPayPlan(<?= $document['sid']; ?>, '<?= $document['document_type']; ?>')">Convert To Pay Plan</button>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td>
                                                                            <button class="btn btn-success btn-sm js-employees-with-pending-documents" data-id="<?= $document['sid']; ?>" data-title="<?= $document['document_title']; ?>">View Employee(s)</button>
                                                                            <button class="btn btn-success btn-sm jsScheduleDocument" title="Schedule Document" data-id="<?= $document['sid']; ?>" data-title="<?= $document['document_title']; ?>">Schedule Document</button>
                                                                        </td>
                                                                    </tr>
                                                                <?php  }
                                                            } else { ?>
                                                                <tr>
                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                </tr>
                                                            <?php  } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="panel panel-default ems-documents">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseoffer_letters">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                        <?php echo 'Offer Letter / Pay Plan'; ?>
                                                        <div class="btn btn-xs btn-warning">Offer Letter / Pay Plan</div>
                                                        <div class="pull-right total-records"><b><?php echo 'Total: ' . count($offer_letters); ?></b></div>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseoffer_letters" class="panel-collapse collapse">
                                                <div class="table-responsive">
                                                    <table class="table table-plane">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-9">Offer Letter Title</th>
                                                                <th class="col-xs-3 text-center" colspan="4">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($offer_letters)) {
                                                                foreach ($offer_letters as $offer_letter) { ?>
                                                                    <tr>
                                                                        <td class="col-xs-9"><?php echo $offer_letter['letter_name']; ?>
                                                                            <?php if ($offer_letter['is_confidential'] == 1) {
                                                                                echo "<br><strong>(Confidential)</strong>";
                                                                            } ?>


                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if ($offer_letter['letter_type'] != 'hybrid_document') { ?>
                                                                                <?php if (check_access_permissions_for_view($security_details, 'add_edit_offer_letter')) { ?>
                                                                                    <a href="<?php echo base_url('hr_documents_management/edit' . ($offer_letter['letter_type'] == 'uploaded' ? '_uploaded' : '') . '_offer_letter/' . $offer_letter['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if ($offer_letter['letter_type'] == 'hybrid_document') { ?>
                                                                                <button data-id="<?= $offer_letter['sid']; ?>" data-type="offer_letter" data-from="company_offer_letters" class="btn btn-info btn-block btn-sm js-hybrid-preview">Preview</button>
                                                                            <?php } else if ($offer_letter['letter_type'] == 'uploaded') {
                                                                                $document_filename = !empty($offer_letter['uploaded_document_s3_name']) ? $offer_letter['uploaded_document_s3_name'] : '';
                                                                                $document_file = pathinfo($document_filename);
                                                                                $name = explode(".", $document_filename);
                                                                                $url_segment_original = $name[0]; ?>

                                                                                <button class="btn btn-info btn-sm btn-block" onclick="fLaunchOfferModal(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $offer_letter['uploaded_document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $offer_letter['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-document-sid="<?php echo  $offer_letter['sid']; ?>" data-file-name="<?php echo $offer_letter['uploaded_document_s3_name']; ?>" data-document-title="<?php echo $offer_letter['uploaded_document_original_name']; ?>">Preview</button>
                                                                            <?php   } else { ?>
                                                                                <button onclick="func_get_generated_document_preview(<?php echo $offer_letter['sid']; ?>,'offer', '<?php echo addslashes($offer_letter['letter_name']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                            <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                <button onclick="func_archive_offer_letter(<?php echo $offer_letter['sid']; ?>);" type="button" class="btn btn-warning btn-sm btn-block">Archive</button>
                                                                            <?php           } ?>
                                                                            <form id="form_archive_offer_letter_<?php echo $offer_letter['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="archive_offer_letter" />
                                                                                <input type="hidden" id="offer_letter_sid" name="offer_letter_sid" value="<?php echo $offer_letter['sid']; ?>" />
                                                                            </form>
                                                                        </td>
                                                                        <td>
                                                                            <button class="btn btn-success btn-sm js-employees-with-pending-documents" data-id="<?= $offer_letter['sid']; ?>" data-type="offer_letter" data-title="<?= $offer_letter['letter_name']; ?>">View Employee(s)</button>
                                                                        </td>
                                                                    </tr>
                                                                <?php   }
                                                            } else { ?>
                                                                <tr>
                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No Offer Letters Found!</b></td>
                                                                </tr>
                                                            <?php   } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="panel panel-default ems-documents">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_total_documents">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                        All Documents
                                                        <div class="btn btn-xs btn-primary">All</div>
                                                        <div class="pull-right total-records"><b><?php echo 'Total: ' . count($all_documents); ?></b></div>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse_total_documents" class="panel-collapse collapse">
                                                <div class="table-responsive">
                                                    <table class="table table-plane">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-6">Document Name</th>
                                                                <th class="col-xs-2">Date Created</th>
                                                                <th class="col-xs-4 text-center" colspan="4">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (count($all_documents) > 0) {
                                                                foreach ($all_documents as $document) {
                                                                    $allDocuments[$document['sid']] = $document; ?>
                                                                    <tr>
                                                                        <td class="col-xs-6">
                                                                            <?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?>
                                                                            <?php
                                                                            if ($document['is_confidential']) :
                                                                                echo "<br /><strong>(Confidential)</strong>";
                                                                            endif;
                                                                            ?>
                                                                        </td>
                                                                        <td class="col-xs-2">
                                                                            <?php if ($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                echo reset_datetime(array('datetime' => $document['date_created'], '_this' => $this));
                                                                            } else {
                                                                                echo 'N/A';
                                                                            } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <form id="form_assign_document_<?php echo $document['document_type']; ?>_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="assign_document" />
                                                                                <input type="hidden" id="document_type" name="document_type" value="<?php echo $document['document_type']; ?>" />
                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                            </form>

                                                                            <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                <button data-id="<?= $document['sid']; ?>" class="btn btn-primary btn-block btn-sm js-bulk-assign-btn">Bulk Assign</button>
                                                                            <?php } else if ($document['document_type'] == 'uploaded') { ?>
                                                                                <button onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>, '<?php echo $document['document_title']; ?>');" class="btn btn-primary btn-block btn-sm">Bulk Assign</button>
                                                                            <?php } else { ?>
                                                                                <button class="btn btn-primary btn-sm btn-block" data-fillable="<?=$document["fillable_document_slug"]?1:0;?>" onclick="fLaunchModalGen(this);" data-title="<?php echo $document['document_title']; ?>" data-description="<?php echo $document['document_description']; ?>" data-document-type="<?php echo $document['document_type']; ?>" data-document-sid="<?php echo $document['sid']; ?>">Bulk Assign</button>
                                                                            <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) { ?>
                                                                                <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                            <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                <button data-id="<?= $document['sid']; ?>" data-from="company" class="btn btn-info btn-sm btn-block js-hybrid-preview">Preview</button>
                                                                            <?php } else if ($document['document_type'] == 'uploaded') {
                                                                                $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                $document_file = pathinfo($document_filename);
                                                                                $name = explode(".", $document_filename);
                                                                                $url_segment_original = $name[0]; ?>

                                                                                <button class="btn btn-info btn-sm btn-block" onclick="fLaunchModal(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-document-sid="<?php echo  $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                            <?php   } else { ?>
                                                                                <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                            <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                            </form>
                                                                            <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if (!$document["fillable_document_slug"]) { ?>
                                                                                <!-- Convert document to Pay Plan -->
                                                                                <button class="btn btn-success btn-sm btn-block" onclick="convertToPayPlan(<?= $document['sid']; ?>, '<?= $document['document_type']; ?>')">Convert To Pay Plan</button>
                                                                            <?php } ?>
                                                                        </td>

                                                                        <td>
                                                                            <button class="btn btn-success btn-sm js-employees-with-pending-documents" data-id="<?= $document['sid']; ?>" data-title="<?= $document['document_title']; ?>">View Employee(s)</button>
                                                                            <button class="btn btn-success btn-sm jsScheduleDocument" title="Schedule Document" data-id="<?= $document['sid']; ?>" data-title="<?= $document['document_title']; ?>">Schedule Document</button>
                                                                        </td>
                                                                    </tr>
                                                                <?php  }
                                                            } else { ?>
                                                                <tr>
                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                </tr>
                                                            <?php  } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end-->
                        <div class="col-md-12">
                            <?php if (!empty($sections)) { ?>
                                <?php foreach ($sections as $section) { ?>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <strong class="text-center" style="font-size: 16px;">
                                                        <?php echo $section['title']; ?>
                                                    </strong>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <?php echo html_entity_decode($section['description']); ?>
                                                </div>
                                            </div>
                                            <?php if ($section['video_status'] == 1) { ?>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div align="center" class="embed-responsive embed-responsive-16by9">
                                                            <video controls class="embed-responsive-item">
                                                                <source src="https://hr-documents-videos.s3.amazonaws.com/<?php echo $section['video']; ?>" type="video/mp4">
                                                            </video>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                            <?php } ?>

                                            <?php if ($section['youtube_video_status'] == 1) { ?>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div align="center" class="embed-responsive embed-responsive-16by9">
                                                            <iframe src="https://www.youtube.com/embed/<?php echo $section['youtube_video']; ?>" frameborder="0" allowfullscreen></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="model_generated_doc" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="generated_document_title">Bulk Assign This Document</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method='post' id='register-form' name='register-form' action="<?= current_url(); ?>">
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <h4 id="gen_document_label"></h4>
                                <b>Please review this document and make any necessary modifications. Modifications will not affect the Original Document.</b> <!--<br>The Modified document will only be sent to the <?= ucwords($user_type); ?> it was assigned to.-->
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <label>Document Description<span class="hr-required red"> * </span></label>
                                <textarea required style="padding:5px; height:200px; width:100%;" class="ckeditor" id="gen_doc_description" name="document_description"></textarea>
                            </div>
                        </div>
                        <!-- Department/Employee Check -->
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <h4>Assign document to </h4>
                                <label class="control control--radio">
                                    <input type="radio" name="assign_type" value="employee" class="js-assign-type" /> Employee
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio">
                                    <input type="radio" name="assign_type" value="department" class="js-assign-type" /> Department &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio">
                                    <input type="radio" name="assign_type" value="team" class="js-assign-type" /> Team &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 js-department-box">
                            <div class="form-group full-width">
                                <label>Departments <span class="hr-required red">*</span></label>
                                <div class="">
                                    <select multiple="multiple" name="departments[]" id="department" required>
                                        <option value="" selected>Please Select Departments</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-12 js-team-box">
                            <div class="form-group full-width">
                                <label>Teams <span class="hr-required red">*</span></label>
                                <div class="">
                                    <select multiple="multiple" name="teams[]" id="team" required>
                                        <option value="" selected>Please Select Teams</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-12 js-employee-box">
                            <div class="form-group full-width">
                                <label>Employees <span class="hr-required red">*</span></label>
                                <div class="">
                                    <select multiple="multiple" name="employees[]" id="employees" required>
                                        <option value="" selected>Please Select Employee</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-12" id="empty-emp" style="display: none;">
                            <span class="hr-required red">This Document Is Assigned To All Employees!</span>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <h4>Send notification emails? </h4>
                                <label class="control control--radio">
                                    <input type="radio" name="notification_email" value="yes" class="js-notification-email-gen" /> Yes &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio">
                                    <input type="radio" name="notification_email" value="no" class="js-notification-email-gen" /> No &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="offer-letter-help-widget full-width form-group">
                                <div class="how-it-works-insturction">
                                    <strong>How it Works :</strong>
                                    <p class="how-works-attr">1. Generate a new Electronic Document</p>
                                    <p class="how-works-attr">2. Enable a Document Acknowledgment</p>
                                    <p class="how-works-attr">3. Enable an Electronic Signature</p>
                                    <p class="how-works-attr">4. Insert multiple tags where applicable</p>
                                    <p class="how-works-attr">5. Save the Document</p>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Company Information Tags:</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{company_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{company_address}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{company_phone}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{career_site_url}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Employee / Applicant Tags :</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{first_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{last_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{email}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{job_title}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Signature tags:</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{signature}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{signature_print_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{inital}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{sign_date}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{authorized_editable_date}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{text}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{checkbox}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{short_text_required}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{text_required}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{text_area_required}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{checkbox_required}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="tags-arae">
                                    <div class="col-md-12">
                                        <h5><strong>Pay Plan / Offer Letter tags:</strong></h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{hourly_rate}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{hourly_technician}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{flat_rate_technician}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_salary}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_draw}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" id="perform_action" name="perform_action" value="assign_document" />
                            <input type="hidden" name="document_type" id="gen-doc-type">
                            <input type="hidden" name="document_sid" id="gen-doc-sid">
                            <input type="hidden" id="document_sid_for_validation">
                            <input type="hidden" id="auth_sign_sid" name="auth_sign_sid" value="0" />
                            <div class="message-action-btn">
                                <input type="button" value="Bulk Assign This Document" id="send-gen-doc" class="submit-btn" onclick="send_bulk_document()">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div id="model_uploaded_doc" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content full-width">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="generated_document_title">Bulk Assign This Document</h4>
            </div>
            <div class="modal-body autoheight">
                <div class="row">
                    <form method='post' id='uploaded-form' action="<?= current_url(); ?>">
                        <!-- <div class="col-md-12">
                            <div class="form-group" style="min-height: 300px">
                                <label>Employees<span class="hr-required red"> * </span></label>
                                <select require multiple="multiple" name="employees[]" id="uploaded-employees" style="display: block"><option value="" selected>Please Select Employee</option></select>
                            </div>
                        </div> -->
                        <!-- Department/Employee Check -->
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <h4>Assign document to </h4>
                                <label class="control control--radio">
                                    <input type="radio" name="assign_type" value="employee" class="js-assign-type" /> Employee
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio">
                                    <input type="radio" name="assign_type" value="department" class="js-assign-type" /> Department &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio">
                                    <input type="radio" name="assign_type" value="team" class="js-assign-type" /> Teams &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 js-department-box">
                            <div class="form-group full-width">
                                <label>Departments <span class="hr-required red">*</span></label>
                                <div class="">
                                    <select multiple="multiple" name="departments[]" id="uploaded-departments" required>
                                        <option value="" selected>Please select departments</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-12 js-team-box">
                            <div class="form-group full-width">
                                <label>Teams <span class="hr-required red">*</span></label>
                                <div class="">
                                    <select multiple="multiple" name="teams[]" id="uploaded-teams" required>
                                        <option value="" selected>Please select teams</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-12 js-employee-box">
                            <div class="form-group full-width">
                                <label>Employees <span class="hr-required red">*</span></label>
                                <div class="">
                                    <select multiple="multiple" name="employees[]" id="uploaded-employees" required>
                                        <option value="" selected>Please Select Employee</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-12" id="uploaded-empty-emp" style="display: none;"><span class="hr-required red">This Document Is Assigned To All Employees!</span></div>

                        <!--  -->

                        <div class="col-sm-12">
                            <div class="form-group full-width">
                                <h4>Send notification emails? </h4>
                                <label class="control control--radio">
                                    <input type="radio" name="notification_email" value="yes" checked="true" class="js-notification-email" /> Yes &nbsp;
                                    <div class="control__indicator"></div>
                                </label>
                                <label class="control control--radio">
                                    <input type="radio" name="notification_email" value="no" class="js-notification-email" /> No
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <input type="hidden" id="perform_action" name="perform_action" value="assign_document">
                            <input type="hidden" name="document_type" id="up-doc-type" value="uploaded">
                            <input type="hidden" name="document_sid" id="up-doc-sid" value="">
                            <div class="message-action-btn">
                                <input type="submit" value="Bulk Assign This Document" id="send-up-doc" class="submit-btn">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $this->load->view('hr_documents_management/authorized_signature_popup'); ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/selectize.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/selectize.bootstrap3.css') ?>">
<script src="<?= base_url('assets/js/selectize.min.js') ?>"></script>

<div id="js-loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we are processing your request...
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#uploaded-form').validate({
            submitHandler: function(form) {
                var up_emp = $('#uploaded-employees').val();
                var up_dept = $('#uploaded-departments').val();
                var up_team = $('#uploaded-team').val();

                if ($('#model_uploaded_doc').find('.js-assign-type:checked').val() == 'department') {

                    if (up_dept.length == 0) {
                        alertify.alert('ERROR!', 'Please select at least one department.');
                        return;
                    }
                } else if ($('#model_uploaded_doc').find('.js-assign-type:checked').val() == 'team') {

                    if (up_team.length == 0) {
                        alertify.alert('ERROR!', 'Please select at least one team.');
                        return;
                    }
                } else {
                    if (up_emp.length == 0) {
                        alertify.alert('ERROR!', 'Please select at least one employee.');
                        return;
                    }
                }

                form.submit();
            }
        });
    });

    function func_un_archive_offer_letter(offer_letter_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to un-archive this offer letter?',
            function() {
                $('#form_un_archive_offer_letter_' + offer_letter_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function send_bulk_document() {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document as bulk?',
            function() {
                // var word = '{{authorized_signature}}';
                // var textValue = CKEDITOR.instances.gen_doc_description.getData();

                // if (textValue.indexOf(word)!=-1){
                //     if ($('#auth_sign_sid').val() > 0) {
                //         $('#register-form').submit();
                //     } else if ($('#auth_sign_sid').val() == 0) {
                //         var company_sid = '<?php echo $company_sid; ?>';
                //         var document_sid = $('#document_sid_for_validation').val();
                //         var myurl = "<?= base_url() ?>Hr_documents_management/check_active_auth_signature/"+document_sid+"/"+company_sid;
                //         var active_signature = '';

                //         $.ajax({
                //             type: "GET",
                //             url: myurl,
                //             async : false,
                //             success: function (status) {
                //                 active_signature = status;
                //             }
                //         });

                //         if(active_signature == 1){
                //             $('#register-form').submit();
                //         } else {
                //            $('#authorized_e_Signature_Modal').modal('show');
                //         }
                //     }
                // }else{
                //     $('#register-form').submit();
                // }

                // 
                if ($('.js-assign-type:checked').val() == 'department') {
                    if (dept.getValue().length == 0) {
                        alertify.alert('ERROR!', 'Please select at least one department.');
                        return;
                    }
                } else if ($('.js-assign-type:checked').val() == 'team') {
                    if (tem.getValue().length == 0) {
                        alertify.alert('ERROR!', 'Please select at least one team.');
                        return;
                    }
                } else {
                    if (emp.getValue().length == 0) {
                        alertify.alert('ERROR!', 'Please select at least one employee.');
                        return;
                    }
                }

                $('#register-form').submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function func_delete_offer_letter(offer_letter_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this offer letter?',
            function() {
                $('#form_delete_offer_letter_' + offer_letter_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function func_archive_offer_letter(offer_letter_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to archive this offer letter?',
            function() {
                $('#form_archive_offer_letter_' + offer_letter_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function func_delete_generated_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this document?',
            function() {
                $('#form_delete_generated_document_' + document_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function func_unarchive_generated_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to un-archive this document?',
            function() {
                $('#form_unarchive_generated_document_' + document_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function func_archive_generated_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to archive this document?',
            function() {
                $('#form_archive_generated_document_' + document_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function func_get_generated_document_preview(document_sid, doc_flag = 'generated', doc_title = 'Preview Generated Document') {
        var my_request;
        var footer_print_btn;
        my_request = $.ajax({
            'url': '<?php echo base_url('hr_documents_management/ajax_responder'); ?>',
            'type': 'POST',
            'data': {

                'perform_action': 'get_generated_document_preview',
                'document_sid': document_sid,
                'source': doc_flag,
                'fetch_data': 'original'
            }
        });


        my_request.done(function(response) {
            $.ajax({
                'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
                'type': 'POST',
                'data': {
                    'request_type': 'original',
                    'document_type': doc_flag,
                    'document_sid': document_sid
                },
                success: function(urls) {
                    var obj = jQuery.parseJSON(urls);
                    var print_url = obj.print_url;
                    var download_url = obj.download_url;
                    footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                    footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';
                    $('#document_modal_body').html(response);
                    $('#document_modal_footer').html(footer_content);
                    $('#document_modal_footer').append(footer_print_btn);
                    $('#document_modal_title').html(doc_title);
                    $('#document_modal').modal("toggle");
                }
            });
        });
    }

    var upemployees = $('#uploaded-employees').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        allowEmptyOption: false,
        persist: true,
        create: false
    });

    var updepartments = $('#uploaded-departments').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        allowEmptyOption: false,
        persist: true,
        create: false
    });

    var upteams = $('#uploaded-teams').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        allowEmptyOption: false,
        persist: true,
        create: false
    });

    var up_emp = upemployees[0].selectize;
    var up_dept = updepartments[0].selectize;
    var up_tem = upteams[0].selectize;

    function func_assign_document(type, document_sid, dn) {
        // Reset modal
        $('.js-department-box').hide();
        $('.js-team-box').hide();
        $('.js-employee-box').show();
        $('.js-assign-type[value="employee"]').prop('checked', true);

        $('#model_uploaded_doc').modal('toggle');
        if (dn !== undefined) $('#model_uploaded_doc #generated_document_title').text(dn);
        $('#model_uploaded_doc .js-notification-email[value="yes"]').prop('checked', true);
        $('#up-doc-type').val(type);
        $('#up-doc-sid').val(document_sid);
        $.ajax({
            type: 'POST',
            url: '<?= base_url('hr_documents_management/get_document_employees') ?>',
            data: {
                doc_sid: document_sid,
                doc_type: 'uploaded'
            },
            success: function(data) {
                // var employees = JSON.parse(data);
                var employees = data.Employees;
                var departments = data.Departments;
                var teams = data.Teams;
                if (employees.length == 0) {
                    up_emp.clearOptions();
                    up_emp.load(function(callback) {
                        var arr = [{}];
                        arr[0] = {
                            value: '',
                            text: 'Please Select Employee'
                        }
                        callback(arr);
                        up_emp.addItems('');
                        up_emp.refreshItems();
                    });
                    $('#uploaded-empty-emp').show();
                    $('#send-up-doc').hide();
                    up_emp.disable();
                } else {
                    $('#uploaded-empty-emp').hide();
                    $('#send-up-doc').show();
                    up_emp.enable();
                    up_emp.clearOptions();
                    up_emp.load(function(callback) {

                        var arr = [{}];
                        var j = 0;

                        arr[j++] = {
                            value: -1,
                            text: 'All'
                        };

                        for (var i = 0; i < employees.length; i++) {
                            arr[j++] = {
                                value: employees[i].sid,
                                text: (employees[i].first_name + ' ' + employees[i].last_name) + (employees[i].job_title != '' && employees[i].job_title != null ? ' (' + employees[i].job_title + ')' : '') + ' [' + remakeAccessLevel(employees[i]) + ']'
                            }
                        }

                        callback(arr);
                        up_emp.refreshItems();
                    });
                }

                if (departments.length == 0 && employees.length == 0) {
                    up_dept.clearOptions();
                    up_dept.load(function(callback) {
                        var arr = [{}];
                        arr[0] = {
                            value: '',
                            text: 'Please Select a Department'
                        }
                        callback(arr);
                        up_dept.addItems('');
                        up_dept.refreshItems();
                    });
                    $('#uploaded-empty-emp').show();
                    $('#send-up-doc').hide();
                    up_dept.disable();
                } else {
                    $('#uploaded-empty-emp').hide();
                    $('#send-up-doc').show();
                    up_dept.enable();
                    up_dept.clearOptions();
                    up_dept.load(function(callback) {

                        var arr = [{}];
                        var j = 0;

                        arr[j++] = {
                            value: -1,
                            text: 'All'
                        };

                        for (var i = 0; i < departments.length; i++) {
                            arr[j++] = {
                                value: departments[i].sid,
                                text: departments[i].name
                            }
                        }

                        callback(arr);
                        up_dept.refreshItems();
                    });
                }

                // 
                if (teams.length == 0 && employees.length == 0) {
                    up_tem.clearOptions();
                    up_tem.load(function(callback) {
                        var arr = [{}];
                        arr[0] = {
                            value: '',
                            text: 'Please Select a Department'
                        }
                        callback(arr);
                        up_tem.addItems('');
                        up_tem.refreshItems();
                    });
                    $('#empty-emp').show();
                    up_tem.disable();
                } else {
                    $('#empty-emp').hide();
                    up_tem.enable();
                    up_tem.clearOptions();
                    up_tem.load(function(callback) {

                        var arr = [{}];
                        var j = 0;
                        arr[j++] = {
                            value: -1,
                            text: 'All'
                        };

                        for (var i = 0; i < teams.length; i++) {

                            arr[j++] = {
                                value: teams[i].sid,
                                text: teams[i].name
                            }
                        }

                        callback(arr);
                        up_tem.refreshItems();
                    });
                }
            },
            error: function() {

            }
        });
    }

    function remakeAccessLevel(obj) {
        if ((obj.is_executive_admin) && (obj.is_executive_admin != 0)) {
            obj.is_executive_admin = 'Executive ' + obj['access_level'];
        }
        if (obj.access_level_plus == 1 && obj.pay_plan_flag == 1) return obj.access_level + ' Plus / Payroll';
        if (obj.access_level_plus == 1) return obj.access_level + ' Plus';
        if (obj.pay_plan_flag == 1) return obj.access_level + ' Payroll';
        return obj.access_level;
    }
    var employees = $('#employees').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        allowEmptyOption: false,
        persist: true,
        create: false
    });

    var departments = $('#department').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        allowEmptyOption: false,
        persist: true,
        create: false
    });

    var teams = $('#team').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        allowEmptyOption: false,
        persist: true,
        create: false
    });

    var emp = employees[0].selectize;
    var dept = departments[0].selectize;
    var tem = teams[0].selectize;

    function fLaunchModalGen(source) {
        // Reset modal
        $('.js-department-box').hide();
        $('.js-team-box').hide();
        $('.js-employee-box').show();
        $('.js-assign-type[value="employee"]').prop('checked', true);
        $('.js-notification-email-gen[value="yes"]').prop('checked', true);
        $("#jsFillableView").remove()

        var isFillable = $(source).attr('data-fillable');
        var document_title = $(source).attr('data-title');
        var document_description = $(source).attr('data-description');
        var document_type = $(source).attr('data-document-type');
        var document_sid = $(source).attr('data-document-sid');
        var title = 'Modify and Bulk Assign This Document';
        var document_label = "Are you sure you want to bulk assign this document: [<b>" + document_title + "</b>]";
        var button_title = 'Bulk Assign This Document';
        $('#document_sid_for_validation').val(document_sid);

        $('#model_generated_doc').modal('toggle');

        //        $('#gen-doc-title').val(document_title);
        if (isFillable) {
        CKEDITOR.instances.gen_doc_description
             && CKEDITOR.instances.gen_doc_description.destroy()
            $("#gen_doc_description").val((document_description))
            $("#gen_doc_description").hide()
            $("#gen_doc_description").after(`<div id="jsFillableView">${makeTheFillableView(document_description)}</div>`)
        } else {
            $("#gen_doc_description").show()
            CKEDITOR.instances.gen_doc_description.setData(document_description);
        }
        //        $('#gen-doc-description').html(document_description);
        $('#gen-doc-type').val(document_type);
        $('#gen-doc-sid').val(document_sid);
        $('#send-gen-doc').val(button_title);
        $('#generated_document_title').html(title);
        $('#gen_document_label').html(document_label);
        $.ajax({
            type: 'POST',
            url: '<?= base_url('hr_documents_management/get_document_employees') ?>',
            data: {
                doc_sid: document_sid,
                doc_type: 'generated'
            },
            success: function(data) {
                var employees = data.Employees;
                var departments = data.Departments;
                var teams = data.Teams;
                if (employees.length == 0) {
                    emp.clearOptions();
                    emp.load(function(callback) {
                        var arr = [{}];
                        arr[0] = {
                            value: '',
                            text: 'Please Select Employee'
                        }
                        callback(arr);
                        emp.addItems('');
                        emp.refreshItems();
                    });
                    $('#empty-emp').show();
                    emp.disable();
                } else {
                    $('#empty-emp').hide();
                    emp.enable();
                    emp.clearOptions();
                    emp.load(function(callback) {

                        var arr = [{}];
                        var j = 0;
                        arr[j++] = {
                            value: -1,
                            text: 'All'
                        };

                        for (var i = 0; i < employees.length; i++) {
                            var dr = '';
                            if (employees[i]['job_title'] != '' && employees[i]['job_title'] != null)
                                dr += ' (' + (employees[i]['job_title']) + ')';
                            dr += ' [' + remakeAccessLevel(employees[i]) + ']';
                            arr[j++] = {
                                value: employees[i].sid,
                                text: employees[i].first_name + ' ' + employees[i].last_name + dr
                            }
                        }

                        callback(arr);
                        emp.refreshItems();
                    });
                }
                // 
                if (departments.length == 0 && employees.length == 0) {
                    dept.clearOptions();
                    dept.load(function(callback) {
                        var arr = [{}];
                        arr[0] = {
                            value: '',
                            text: 'Please Select a Department'
                        }
                        callback(arr);
                        dept.addItems('');
                        dept.refreshItems();
                    });
                    $('#empty-emp').show();
                    dept.disable();
                } else {
                    $('#empty-emp').hide();
                    dept.enable();
                    dept.clearOptions();
                    dept.load(function(callback) {

                        var arr = [{}];
                        var j = 0;
                        arr[j++] = {
                            value: -1,
                            text: 'All'
                        };

                        for (var i = 0; i < departments.length; i++) {

                            arr[j++] = {
                                value: departments[i].sid,
                                text: departments[i].name
                            }
                        }

                        callback(arr);
                        dept.refreshItems();
                    });
                }
                // 
                if (teams.length == 0 && employees.length == 0) {
                    tem.clearOptions();
                    tem.load(function(callback) {
                        var arr = [{}];
                        arr[0] = {
                            value: '',
                            text: 'Please Select a Department'
                        }
                        callback(arr);
                        tem.addItems('');
                        tem.refreshItems();
                    });
                    $('#empty-emp').show();
                    tem.disable();
                } else {
                    $('#empty-emp').hide();
                    tem.enable();
                    tem.clearOptions();
                    tem.load(function(callback) {

                        var arr = [{}];
                        var j = 0;
                        arr[j++] = {
                            value: -1,
                            text: 'All'
                        };

                        for (var i = 0; i < teams.length; i++) {

                            arr[j++] = {
                                value: teams[i].sid,
                                text: teams[i].name
                            }
                        }

                        callback(arr);
                        tem.refreshItems();
                    });
                }
            },
            error: function() {

            }
        });
    }

    function remakeAccessLevel(obj) {
        if (obj.access_level_plus == 1 && obj.pay_plan_flag == 1) return obj.access_level + ' Plus / Payroll';
        if (obj.access_level_plus == 1) return obj.access_level + ' Plus';
        if (obj.pay_plan_flag == 1) return obj.access_level + ' Payroll';
        return obj.access_level;
    }

    $(document).on('click', '.js-assign-type', function() {
        if ($(this).val() == 'department') {
            $('.js-employee-box').hide();
            $('.js-team-box').hide();
            $('.js-department-box').show();
        } else if ($(this).val() == 'team') {
            $('.js-employee-box').hide();
            $('.js-department-box').hide();
            $('.js-team-box').show();
        } else {
            $('.js-department-box').hide();
            $('.js-team-box').hide();
            $('.js-employee-box').show();
        }
    });

    function fLaunchModal(source) {
        var url_segment_original = $(source).attr('data-print-url');
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var document_sid = $(source).attr('data-document-sid');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                case 'ppt':
                case 'pptx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original/generated'); ?>' + '/' + document_sid + '" class="btn btn-success">Print</a>';
                    break;
                default: //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $.ajax({
            'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
            'type': 'POST',
            'data': {
                'request_type': 'original',
                'document_type': 'MS',
                'document_sid': document_sid
            },
            success: function(urls) {
                var obj = jQuery.parseJSON(urls);
                var print_url = obj.print_url;
                var download_url = obj.download_url;
                footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';

                $('#document_modal_body').html(modal_content);
                $('#document_modal_footer').html(footer_content);
                $('#document_modal_footer').append(footer_print_btn);
                $('#document_modal_title').html(document_title);
                $('#document_modal').modal("toggle");
                $('#document_modal').on("shown.bs.modal", function() {

                    if (iframe_url != '') {
                        $('#preview_iframe').attr('src', iframe_url);
                        loadIframe(iframe_url, '#preview_iframe', true);
                    }
                });
            }
        });
    }

    function fLaunchOfferModal(source) {
        var url_segment_original = $(source).attr('data-print-url');
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var document_sid = $(source).attr('data-document-sid');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                case 'ppt':
                case 'pptx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original/generated'); ?>' + '/' + document_sid + '" class="btn btn-success">Print</a>';
                    break;
                default: //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $.ajax({
            'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
            'type': 'POST',
            'data': {
                'request_type': 'offer_letter',
                'document_type': 'MS',
                'document_sid': document_sid
            },
            success: function(urls) {
                var obj = jQuery.parseJSON(urls);
                var print_url = obj.print_url;
                var download_url = obj.download_url;
                footer_content = '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                footer_print_btn = '<a target="_blank" class="btn btn-success" href="' + print_url + '" >Print</a>';

                $('#document_modal_body').html(modal_content);
                $('#document_modal_footer').html(footer_print_btn);
                $('#document_modal_footer').append(footer_content);
                $('#document_modal_title').html(document_title);
                $('#document_modal').modal("toggle");
                $('#document_modal').on("shown.bs.modal", function() {

                    if (iframe_url != '') {
                        $('#preview_iframe').attr('src', iframe_url);
                        loadIframe(iframe_url, '#preview_iframe', true);
                    }
                });
            }
        });

    }

    function func_unarchive_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to un-archive this document?',
            function() {
                $('#form_unarchive_uploaded_document_' + document_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function func_archive_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to archive this document?',
            function() {
                $('#form_archive_hr_document_' + document_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function func_delete_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this document?',
            function() {
                $('#form_delete_uploaded_document_' + document_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    $(function() {
        $("#settings-tabs").tabs();
        $("#home-accordion").accordion({
            collapsible: true
        });

        $('#file_image').on('change', function() {
            $('#image').val('');
        });

        $(".tab_content").hide();
        $(".tab_content:first").show();
        $("ul.tabs li").click(function() {
            $("ul.tabs li").removeClass("active");
            $(this).addClass("active");
            $(".tab_content").hide();
            var activeTab = $(this).attr("rel");
            $("#" + activeTab).fadeIn();
        });

        $('.collapse').on('shown.bs.collapse', function() {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function() {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    // Convert a document to pay plan
    function convertToPayPlan(
        documentId,
        documentType
    ) {
        // Confirm 
        alertify.confirm('Do you really want to convert this document to Pay Plan?',
            function() {
                // Show loader
                loader('show');
                // Send ajax request to convert
                $.post("<?= base_url('hr_documents_management/convert_document_to_payplan'); ?>", {
                    documentId: documentId,
                    documentType: documentType
                }, function(resp) {
                    if (resp.Status === false) {
                        loader('hide');
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response, function() {
                        window.location.reload();
                    });
                });
                // Show message
                // Reload page
            }
        ).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }

    //
    function loader(doShow) {
        if (doShow == true || doShow == 'show' || doShow == undefined) $('#js-loader').fadeIn();
        else $('#js-loader').fadeOut();
    }

    loader('hide');
</script>

<?php $this->load->view('iframeLoader'); ?>

<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>


<!--  -->
<div class="modal fade" id="js-pending-employee-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="loader js-pde-loader"><i class="fa fa-spinner fa-spin"></i></div>
                <h4>Total Employee(s): <span>0</span></h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Employee Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        //
        $('.js-employees-with-pending-documents').click(function(e) {
            //
            e.preventDefault();
            //
            $('#js-pending-employee-modal .modal-body tbody').html('');
            $('.js-pde-loader').fadeIn(300);
            //
            $('#js-pending-employee-modal .modal-title').html(
                $(this).data('title')
            );
            //
            $('#js-pending-employee-modal').modal();
            //
            //
            $.get(
                "<?= base_url('hr_documents_management/people_with_pending_documents/all/'); ?>/" + ($(this).data('id')) + "/return",
                function(resp) {
                    //
                    var rows = '';
                    //
                    $('#js-pending-employee-modal .modal-body span').text(resp.length);
                    //
                    if (resp.length === 0) {
                        rows += '<tr><td colspan="3"><p class="alert alert-info text-center">No employee(s) found.</p></td></tr>';
                    } else {
                        $.each(resp, function(i, v) {
                            rows += '<tr>';
                            rows += '  <td>' + (remakeEmployeeName(v)) + '</td>';
                            rows += '  <td>' + (v.email) + '</td>';
                            rows += '  <td><a href="<?= base_url('/hr_documents_management/employee_document'); ?>/' + (v.sid) + '" target="_blank" class="btn btn-success btn-sm">View All</a></td>';
                            rows += '</tr>';
                        });
                    }

                    $('#js-pending-employee-modal .modal-body tbody').html(rows);
                    $('.js-pde-loader').fadeOut(300);
                });
        });

        //
        function remakeEmployeeName(o) {
            //
            var r = '';
            //
            r += o.first_name + ' ' + o.last_name;
            //
            if (o.job_title != '' && o.job_title != null) r += ' [' + (o.job_title) + ']';
            //
            r += ' (';
            //
            if (typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
            //
            if (o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1) r += o['access_level'] + ' Plus / Payroll';
            else if (o['access_level_plus'] == 1) r += o['access_level'] + ' Plus';
            else if (o['pay_plan_flag'] == 1) r += o['access_level'] + ' Payroll';
            else r += o['access_level'];
            //
            r += ')';
            //
            return r;
        }
    })
</script>

<?php $this->load->view('hr_documents_management/hybrid/scripts'); ?>
<?php $this->load->view('hr_documents_management/partials/schedule_document', [
    'allDocuments' => $allDocuments
]); ?>


<script>
    function makeTheFillableView(description)
    {

        const inputReplace = "---------------";
        const textAreaReplace = "<p>--------------------------------------------------</p>";
        const dateReplace = "--/--/----";
        const radioReplace = `
            <br />
            <input type="radio" disabled /> Yes
            <br />
            <input type="radio" disabled /> No
        `;
        const actualCheckBoxReplace = `
            <input type="checkbox" disabled /> 
        `;
        const checkboxReplace = `
            <table>
                <tr>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Absence" />
                        Absence
                    </td>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Harassment" />
                        Harassment
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Tardiness" />
                        Tardiness
                    </td>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Dishonesty" />
                        Dishonesty
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Violation of company policies and/or procedures" />
                        Violation of company policies and/or procedures
                    </td>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Violation of safety rules" />
                        Violation of safety rules
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Horseplay" />
                        Horseplay
                    </td>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Leaving work without authorization" />
                        Leaving work without authorization
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Smoking in unauthorized areas" />
                        Smoking in unauthorized areas
                    </td>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Unsatisfactory job performance" />
                        Unsatisfactory job performance
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Failure to follow instructions" />
                        Failure to follow instructions
                    </td>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Insubordination" />
                        Insubordination
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Unauthorized use of equipment, materials" />
                        Unauthorized use of equipment, materials
                    </td>
                    <td>
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Falsification of records" />
                        Falsification of records
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" value="Other" />
                        Other:
                        <textarea rows="5" class="form-control input-grey gray-background hidden js_counselling_form_fields_textarea" name="counselling_form_fields_textarea"></textarea>
                    </td>
                </tr>
            </table>
        `;

        const inputGroupReplace = `
            <div class="input-group">
                <div class="input-group-addon">$</div>
                <input type="text" class="form-control" />
            </div>
        `;

        const statusTableReplace = `
        <table>
            <tr>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Seniority increase" />
                    Seniority increase
                </td>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Retirement" />
                    Retirement
                </td>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Layoff" />
                    Layoff
                </td>
            </tr>

            <tr>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Contract change" />
                    Contract change
                </td>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Resignation" />
                    Resignation
                </td>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Discharge" />
                    Discharge
                </td>
            </tr>

            <tr>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Re-evaluation" />
                    Re-evaluation
                </td>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Demotion" />
                    Demotion
                </td>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Leave of absence" />
                    Leave of absence
                </td>
            </tr>

            <tr>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Transfer" />
                    Transfer
                </td>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Promotion" />
                    Promotion
                </td>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Merit Increase" />
                    Merit Increase
                </td>
            </tr>


            <tr>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Probation period end" />
                    Probation period end
                </td>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Re-hired" />
                    Re-hired
                </td>
                <td>
                    <input type="checkbox" disabled class="js_fillable_all_reasons" name="fillable_all_reasons[]" value="Hired" />
                    Hired
                </td>
            </tr>

        </table>
        `;
        // inputs
        description = description.replace("{{employee_name}}", inputReplace);
        description = description.replace("{{supervisor}}", inputReplace);
        description = description.replace("{{department}}", inputReplace);
        description = description.replace("{{employee_job_title}}", inputReplace);
        description = description.replace("{{signature}}", inputReplace);
        description = description.replace("{{signature_print_name}}", inputReplace);
        description = description.replace("{{authorized_signature}}", inputReplace);
        description = description.replace("{{employee_number}}", inputReplace);
        // textarea
        description = description.replace("{{reason_to_leave_company}}", textAreaReplace);
        description = description.replace("{{forwarding_information}}", textAreaReplace);
        description = description.replace("{{forwarding_information}}", textAreaReplace);
        description = description.replace("{{property_returned}}", textAreaReplace);
        description = description.replace("{{reemploying}}", textAreaReplace);
        description = description.replace("{{summary_of_violation}}", textAreaReplace);
        description = description.replace("{{summary_of_corrective_plan}}", textAreaReplace);
        description = description.replace("{{follow_up_dates}}", textAreaReplace);
        description = description.replace("{{q1}}", textAreaReplace);
        description = description.replace("{{q2}}", textAreaReplace);
        description = description.replace("{{q3}}", textAreaReplace);
        description = description.replace("{{q4}}", textAreaReplace);
        description = description.replace("{{q5}}", textAreaReplace);
        // dates
        description = description.replace("{{last_day_of_work}}", dateReplace);
        description = description.replace("{{sign_date}}", dateReplace);
        description = description.replace("{{authorized_signature_date}}", dateReplace);
        description = description.replace("{{date_of_occurrence}}", dateReplace);
        // radios
        description = description.replace("{{is_termination_voluntary}}", radioReplace);
        // checkboxes
        description = description.replace("{{counselling_form_fields}}", checkboxReplace);

        description = description.replace("{{fillable_rate}}", actualCheckBoxReplace)
        description = description.replace("{{fillable_job}}", actualCheckBoxReplace)
        description = description.replace("{{fillable_department}}", actualCheckBoxReplace)
        description = description.replace("{{fillable_location}}", actualCheckBoxReplace)
        description = description.replace("{{fillable_shift}}", actualCheckBoxReplace)
        description = description.replace("{{fillable_other}}", actualCheckBoxReplace)

        description = description.replace("{{fillable_from_rate}}", inputGroupReplace)
        description = description.replace("{{fillable_to_rate}}", inputGroupReplace)
        description = description.replace("{{fillable_all_reasons}}", statusTableReplace)
        
        return description;
    }
</script>