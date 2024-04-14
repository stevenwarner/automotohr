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
                                    <a class="dashboard-link-btn" href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-chevron-left"></i>Dashboard</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                </div>
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
                                        <a href="<?php echo base_url('hr_documents_management/hybrid_document/add'); ?>" class="btn btn-success">Add Document <i class="fa fa-file" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'add_edit_offer_letter')) { ?>
                                        <a href="<?php echo base_url('hr_documents_management/generate_new_offer_letter'); ?>" class="btn btn-success">Generate Offer <i class="fa fa-envelope" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'pending_document')) { ?>
                                        <a href="<?php echo base_url('hr_documents_management/people_with_pending_documents'); ?>" class="btn btn-success">Employees With Pending <i class="fa fa-files-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <a href="<?php echo base_url('hr_documents_management/documents_group_management'); ?>" class="btn btn-success">Group Management</a>
                                    <a href="<?php echo base_url('hr_documents_management'); ?>" class="btn btn-success">Active <i class="fa fa-files-o" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <hr />
                        </div>
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
                                                                        <th class="col-xs-7">Document Name</th>
                                                                        <th class="col-xs-2">Date Created</th>
                                                                        <th class="col-xs-3 text-center" colspan="4">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if ($active_group['documents_count'] > 0) {
                                                                        foreach ($active_group['documents'] as $document) { ?>
                                                                            <tr>
                                                                                <td class="col-xs-7"><?php echo $document['document_title']; ?></td>
                                                                                <td class="col-xs-2">
                                                                                    <?php if ($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                        echo reset_datetime(array('datetime' => $document['date_created'], '_this' => $this));
                                                                                    } else {
                                                                                        echo 'N/A';
                                                                                    } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) { ?>
                                                                                        <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid'] . '/archive'); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                                    <?php                                                       } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                        <button data-id="<?= $document['sid']; ?>" class="btn btn-info btn-sm btn-block js-hybrid-preview">Preview</button>
                                                                                    <?php } else if ($document['document_type'] == 'uploaded') {
                                                                                        $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                        $document_file = pathinfo($document_filename);
                                                                                        $name = explode(".", $document_filename);
                                                                                        $url_segment_original = $name[0]; ?>
                                                                                        <button class="btn btn-info btn-sm btn-block" onclick="fLaunchModal(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-document-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                                    <?php                                                           } else { ?>
                                                                                        <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>, 'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                                    <?php                                                           } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="activate_uploaded_document" />
                                                                                        <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                    </form>
                                                                                    <button class="btn btn-default btn-sm btn-block" onclick="func_unarchive_uploaded_document(<?php echo $document['sid']; ?>)">Activate</button>
                                                                                </td>
                                                                            </tr>
                                                                        <?php                                           }
                                                                    } else { ?>
                                                                        <tr>
                                                                            <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                        </tr>
                                                                    <?php                                       } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php       }
                                }

                                if (!empty($in_active_groups)) {
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
                                                                        <th class="col-xs-7">Document Name</th>
                                                                        <th class="col-xs-2">Date Created</th>
                                                                        <th class="col-xs-3 text-center" colspan="4">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if ($active_group['documents_count'] > 0) {
                                                                        foreach ($active_group['documents'] as $document) { ?>
                                                                            <tr>
                                                                                <td class="col-xs-7"><?php echo $document['document_title']; ?></td>
                                                                                <td class="col-xs-2">
                                                                                    <?php if ($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                        echo reset_datetime(array('datetime' => $document['date_created'], '_this' => $this));
                                                                                    } else {
                                                                                        echo 'N/A';
                                                                                    } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) { ?>
                                                                                        <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid'] . '/archive'); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                                    <?php                                                       } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                        <button data-id="<?= $document['sid']; ?>" class="btn btn-info btn-sm btn-block js-hybrid-preview">Preview</button>
                                                                                    <?php } else if ($document['document_type'] == 'uploaded') {
                                                                                        $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                        $document_file = pathinfo($document_filename);
                                                                                        $name = explode(".", $document_filename);
                                                                                        $url_segment_original = $name[0]; ?>

                                                                                        <button class="btn btn-info btn-sm btn-block" onclick="fLaunchModal(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-document-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                                    <?php                                                       } else { ?>
                                                                                        <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>, 'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                                    <?php                                                       } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="activate_uploaded_document" />
                                                                                        <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                    </form>
                                                                                    <button class="btn btn-default btn-sm btn-block" onclick="func_unarchive_uploaded_document(<?php echo $document['sid']; ?>)">Activate</button>
                                                                                </td>
                                                                            </tr>
                                                                        <?php                                           }
                                                                    } else { ?>
                                                                        <tr>
                                                                            <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                        </tr>
                                                                    <?php                                       } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php       }
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
                                                                        <td class="col-xs-7"><?php echo $document['document_title']; ?></td>
                                                                        <td class="col-xs-2">
                                                                            <?php if ($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                echo reset_datetime(array('datetime' => $document['date_created'], '_this' => $this));
                                                                            } else {
                                                                                echo 'N/A';
                                                                            } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) { ?>
                                                                                <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid'] . '/archive'); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                            <?php                                                       } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                <button data-id="<?= $document['sid']; ?>" class="btn btn-info btn-sm btn-block js-hybrid-preview">Preview</button>
                                                                            <?php } else if ($document['document_type'] == 'uploaded') {
                                                                                $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                $document_file = pathinfo($document_filename);
                                                                                $name = explode(".", $document_filename);
                                                                                $url_segment_original = $name[0]; ?>

                                                                                <button class="btn btn-info btn-sm btn-block" onclick="fLaunchModal(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-document-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                            <?php                                                       } else { ?>
                                                                                <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>, 'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                            <?php                                                       } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="activate_uploaded_document" />
                                                                                <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                            </form>
                                                                            <button class="btn btn-default btn-sm btn-block" onclick="func_unarchive_uploaded_document(<?php echo $document['sid']; ?>)">Activate</button>
                                                                        </td>
                                                                    </tr>
                                                                <?php           }
                                                            } else { ?>
                                                                <tr>
                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                                                </tr>
                                                            <?php       } ?>
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
                                                                        <td class="col-xs-9"><?php echo $offer_letter['letter_name']; ?></td>
                                                                        <td class="col-xs-1">
                                                                            <?php if (check_access_permissions_for_view($security_details, 'add_edit_offer_letter')) { ?>
                                                                                <a href="<?php echo base_url('hr_documents_management/edit_offer_letter/' . $offer_letter['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                            <?php                                               } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <button onclick="func_get_generated_document_preview(<?php echo $offer_letter['sid']; ?>, 'offer', '<?php echo addslashes($offer_letter['letter_name']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <button onclick="func_unarchive_offer_letter(<?php echo $offer_letter['sid']; ?>);" type="button" class="btn btn-default btn-sm btn-block">Activate</button>
                                                                            <form id="form_archive_offer_letter_<?php echo $offer_letter['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="activate_offer_letter" />
                                                                                <input type="hidden" id="offer_letter_sid" name="offer_letter_sid" value="<?php echo $offer_letter['sid']; ?>" />
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                <?php                               }
                                                            } else { ?>
                                                                <tr>
                                                                    <td colspan="7" class="col-lg-12 text-center"><b>No Offer Letters Found!</b></td>
                                                                </tr>
                                                            <?php                           } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- All Archived Documents -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_all_archived_documents">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                        All Archived Documents
                                                        <div class="pull-right total-records"><strong><?php echo 'Total: ' . count($all_documents); ?></strong></div>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse_all_archived_documents" class="panel-collapse collapse">
                                                <div class="table-responsive">
                                                    <table class="table table-plane">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="col-xs-7">Document Name</th>
                                                                <th scope="col" class="col-xs-2">Date Created</th>
                                                                <th scope="col" class="col-xs-3 text-center" colspan="4">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if (!empty($all_documents)) :
                                                                foreach ($all_documents as $document) :
                                                            ?>
                                                                    <tr>
                                                                        <td class="col-xs-7"><?php echo $document['document_title']; ?></td>
                                                                        <td class="col-xs-2">
                                                                            <?php if ($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                echo reset_datetime(array('datetime' => $document['date_created'], '_this' => $this));
                                                                            } else {
                                                                                echo 'N/A';
                                                                            } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) { ?>
                                                                                <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid'] . '/archive'); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                            <?php                                                       } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if ($document['document_type'] == 'hybrid_document') { ?>
                                                                                <button data-id="<?= $document['sid']; ?>" class="btn btn-info btn-sm btn-block js-hybrid-preview">Preview</button>
                                                                            <?php } else if ($document['document_type'] == 'uploaded') {
                                                                                $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                $document_file = pathinfo($document_filename);
                                                                                $name = explode(".", $document_filename);
                                                                                $url_segment_original = $name[0]; ?>

                                                                                <button class="btn btn-info btn-sm btn-block" onclick="fLaunchModal(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-document-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                            <?php                                                       } else { ?>
                                                                                <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>, 'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                            <?php                                                       } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="activate_uploaded_document" />
                                                                                <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type'] ?>" />
                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                            </form>
                                                                            <button class="btn btn-default btn-sm btn-block" onclick="func_unarchive_uploaded_document(<?php echo $document['sid']; ?>)">Activate</button>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                                endforeach;
                                                            else :
                                                                ?>
                                                                <tr>
                                                                    <td colspan="4">
                                                                        <p class="alert alert-info text-center">No archived documents found.</p>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            endif;
                                                            ?>
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

<script>
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

    function func_unarchive_offer_letter(offer_letter_sid) {
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

    function func_get_generated_document_preview(document_sid, doc_flag = 'generated', doc_title = 'Preview Generated Document') {
        var my_request;
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
            $('#popupmodalbody').html(response);
            $('#popupmodallabel').html(doc_title);
            $('#popupmodal .modal-dialog').css('width', '60%');
            $('#popupmodal').modal('toggle');
        });
    }

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        let isPDF = false;

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'DOC':
                case 'DOCX':
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
                    break;
                case 'pdf':
                    isPDF = true;
                    iframe_url = 'https://docs.google.com/viewer?url=' + document_preview_url + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pdf';
                    break;

                default: //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a target="_blank" download="download" class="btn btn-success" href="' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }


        if (isPDF) {
            modal_content = '<iframe src="" id="preview_iframe" class="uploaded-file-preview jsCustomPreview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
            iframe_url = $(source).attr('data-file-name');
            $.ajax({
                    url: "<?= base_url("v1/Aws_pdf/getFileBase64"); ?>",
                    method: "POST",
                    data: {
                        fileName: iframe_url
                    }
                })
                .done(function() {})

            iframe_url = "https://automotohrattachments.s3.amazonaws.com/" + iframe_url;
        }


        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function() {

            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
                //
                loadIframe(iframe_url, '#preview_iframe', true);
            }
        });
    }

    function func_unarchive_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to activate this document?',
            function() {
                $('#form_archive_hr_document_' + document_sid).submit();
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
</script>

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


<?php $this->load->view('iframeLoader'); ?>
<?php $this->load->view('hr_documents_management/hybrid/scripts'); ?>