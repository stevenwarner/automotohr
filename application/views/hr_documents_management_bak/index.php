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
                                <span class="page-heading down-arrow">
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

                                    <?php if (check_access_permissions_for_view($security_details, 'add_edit_offer_letter')) { ?>
                                    <!-- <a href="<?php echo base_url('hr_documents_management/upload_new_offer_letter'); ?>" class="btn btn-success">Upload Offer Letter / Pay Plans <i class="fa fa-envelope" aria-hidden="true"></i></a> -->
                                    <a href="<?php echo base_url('hr_documents_management/generate_new_offer_letter'); ?>" class="btn btn-success">Generate Offer Letter / Pay Plans <i class="fa fa-envelope" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <?php if (check_access_permissions_for_view($security_details, 'pending_document')) { ?>
                                    <a href="<?php echo base_url('hr_documents_management/people_with_pending_documents'); ?>" class="btn btn-success">Employees With Pending <i class="fa fa-files-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <a href="<?php echo base_url('hr_documents_management/documents_group_management'); ?>" class="btn btn-success">Group Management</a>
                                    <a href="<?php echo base_url('hr_documents_management/documents_category_management'); ?>" class="btn btn-success">Category Management</a>
                                    <?php if (check_access_permissions_for_view($security_details, 'view_archive_document')) { ?>
                                    <a href="<?php echo base_url('hr_documents_management/archived_documents'); ?>" class="btn btn-warning">Archived <i class="fa fa-files-o" aria-hidden="true"></i></a>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr />
                        </div>
<!--                        start-->
                        <div class="col-md-12">
                            <div class="hr-document-list">
                             <?php if(!empty($active_groups)) {
                                    foreach ($active_groups as $active_group) { ?>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel panel-default ems-documents">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $active_group['sid']; ?>" >
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                <?php echo $active_group['name']; ?>
                                                                <div class="btn btn-xs btn-success">Active Group</div>
                                                                <div class="pull-right total-records"><b><?php echo 'Total: '.$active_group['documents_count'];?></b></div>
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
                                                                 <?php if($active_group['documents_count'] > 0) {
                                                                        foreach ($active_group['documents'] as $document) { ?>
                                                                            <tr>
                                                                                <td class="col-xs-6"><?php echo $document['document_title']; ?></td>
                                                                                <td class="col-xs-2">
                                                                             <?php  if($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                        echo reset_datetime(array( 'datetime' => $document['date_created'], '_this' => $this));
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
                                                                            <?php  if($document['document_type'] == 'uploaded') {?>
                                                                                        <button onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>);" class="btn btn-primary btn-block btn-sm">Bulk Assign</button>
                                                                                <?php  } else { ?>
                                                                                        <button class="btn btn-primary btn-sm btn-block"
                                                                                                onclick="fLaunchModalGen(this);"
                                                                                                data-title="<?php echo $document['document_title']; ?>"
                                                                                                data-description="<?php echo $document['document_description']; ?>"
                                                                                                data-document-type="<?php echo $document['document_type']; ?>"
                                                                                                data-document-sid="<?php echo $document['sid']; ?>">Bulk Assign</button>
                                                                                 <?php } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <?php  if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) {?>
                                                                                        <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                                     <?php   } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                     <?php  if($document['document_type'] == 'uploaded') {
                                                                                            $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                            $document_file = pathinfo($document_filename);
                                                                                            $name = explode(".",$document_filename);
                                                                                            $url_segment_original = $name[0]; ?>

                                                                                        <button class="btn btn-info btn-sm btn-block"
                                                                                                onclick="fLaunchModal(this);"
                                                                                                data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                                data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                                data-print-url="<?php echo $url_segment_original; ?>"
                                                                                                data-document-sid="<?php echo  $document['sid']; ?>"
                                                                                                data-file-name="<?php echo $document['uploaded_document_original_name']; ?>"
                                                                                                data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                                         <?php  } else { ?>
                                                                                        <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                                        <?php   } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                        <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type']?>" />
                                                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                    </form>
                                                                                <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                    <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                                <?php } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <!-- Convert document to Pay Plan -->
                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="convertToPayPlan(<?=$document['sid'];?>, '<?=$document['document_type'];?>')">Convert To Pay Plan</button>
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

                                <?php  if(!empty($in_active_groups)) {
                                    foreach ($in_active_groups as $active_group) { ?>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel panel-default ems-documents">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $active_group['sid']; ?>" >
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                <?php echo $active_group['name']; ?>
                                                                <div class="btn btn-xs btn-danger">Inactive Group</div>
                                                                <div class="pull-right total-records"><b><?php echo 'Total: '.$active_group['documents_count'];?></b></div>
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
                                                         <?php  if($active_group['documents_count'] > 0) {
                                                                        foreach ($active_group['documents'] as $document) { ?>
                                                                            <tr>
                                                                                <td class="col-xs-6"><?php echo $document['document_title']; ?></td>
                                                                                <td class="col-xs-2">
                                                                <?php if($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                        echo reset_datetime(array( 'datetime' => $document['date_created'], '_this' => $this));
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
                                                                     <?php  if($document['document_type'] == 'uploaded') {?>
                                                                                        <button onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>);" class="btn btn-primary btn-block btn-sm">Bulk Assign</button>
                                                                             <?php  } else { ?>
                                                                                        <button class="btn btn-primary btn-sm btn-block"
                                                                                                onclick="fLaunchModalGen(this);"
                                                                                                data-title="<?php echo $document['document_title']; ?>"
                                                                                                data-description="<?php echo $document['document_description']; ?>"
                                                                                                data-document-type="<?php echo $document['document_type']; ?>"
                                                                                                data-document-sid="<?php echo $document['sid']; ?>">Bulk Assign</button>
                                                                                     <?php  } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                             <?php  if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) {?>
                                                                                        <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                                    <?php  } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                 <?php  if($document['document_type'] == 'uploaded') {
                                                                                            $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                            $document_file = pathinfo($document_filename);
                                                                                            $name = explode(".",$document_filename);
                                                                                            $url_segment_original = $name[0]; ?>

                                                                                        <button class="btn btn-info btn-sm btn-block"
                                                                                                onclick="fLaunchModal(this);"
                                                                                                data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                                data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                                data-print-url="<?php echo $url_segment_original; ?>"
                                                                                                data-document-sid="<?php echo  $document['sid']; ?>"
                                                                                                data-file-name="<?php echo $document['uploaded_document_original_name']; ?>"
                                                                                                data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                                  <?php   } else { ?>
                                                                                        <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                        <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                        <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type']?>" />
                                                                                        <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                                    </form>
                                                                                <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                                    <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                                <?php } ?>
                                                                                </td>
                                                                                <td class="col-xs-1">
                                                                                    <!-- Convert document to Pay Plan -->
                                                                                    <button class="btn btn-success btn-sm btn-block" onclick="convertToPayPlan(<?=$document['sid'];?>, '<?=$document['document_type'];?>')">Convert To Pay Plan</button>
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
                                                        <div class="pull-right total-records"><b><?php echo 'Total: '.count($uncategorized_documents);?></b></div>
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
                                                         <?php if(count($uncategorized_documents) > 0) {
                                                                foreach ($uncategorized_documents as $document) { ?>
                                                                    <tr>
                                                                        <td class="col-xs-6"><?php echo $document['document_title']; ?></td>
                                                                        <td class="col-xs-2">
                                                         <?php  if($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                echo reset_datetime(array( 'datetime' => $document['date_created'], '_this' => $this));
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
                                                                            <?php if($document['document_type'] == 'uploaded') {?>
                                                                                <button onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>);" class="btn btn-primary btn-block btn-sm">Bulk Assign</button>
                                                                            <?php } else { ?>
                                                                                <button class="btn btn-primary btn-sm btn-block"
                                                                                        onclick="fLaunchModalGen(this);"
                                                                                        data-title="<?php echo $document['document_title']; ?>"
                                                                                        data-description="<?php echo $document['document_description']; ?>"
                                                                                        data-document-type="<?php echo $document['document_type']; ?>"
                                                                                        data-document-sid="<?php echo $document['sid']; ?>">Bulk Assign</button>
                                                                             <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                     <?php   if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) {?>
                                                                                <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                        <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                        <?php if($document['document_type'] == 'uploaded') {
                                                                                    $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                    $document_file = pathinfo($document_filename);
                                                                                    $name = explode(".",$document_filename);
                                                                                    $url_segment_original = $name[0]; ?>

                                                                                <button class="btn btn-info btn-sm btn-block"
                                                                                        onclick="fLaunchModal(this);"
                                                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                        data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                        data-print-url="<?php echo $url_segment_original; ?>"
                                                                                        data-document-sid="<?php echo  $document['sid']; ?>"
                                                                                        data-file-name="<?php echo $document['uploaded_document_original_name']; ?>"
                                                                                        data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                             <?php   } else { ?>
                                                                                <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                                 <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type']?>" />
                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                            </form>
                                                                        <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                            <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                        <?php } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <!-- Convert document to Pay Plan -->
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="convertToPayPlan(<?=$document['sid'];?>, '<?=$document['document_type'];?>')">Convert To Pay Plan</button>
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
                                                        <div class="pull-right total-records"><b><?php echo 'Total: '.count($offer_letters);?></b></div>
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
                                             <?php   if(!empty($offer_letters)) {
                                                            foreach ($offer_letters as $offer_letter) { ?>
                                                                    <tr>
                                                                        <td class="col-xs-9"><?php echo $offer_letter['letter_name']; ?></td>
                                                                        <td class="col-xs-1">
                                                                        <?php if (check_access_permissions_for_view($security_details, 'add_edit_offer_letter')) { ?>
                                                                            <a href="<?php echo base_url('hr_documents_management/edit'.($offer_letter['letter_type'] == 'uploaded' ? '_uploaded' : '').'_offer_letter/' . $offer_letter['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                        <?php } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <?php if($offer_letter['letter_type'] == 'uploaded') {
                                                                                    $document_filename = !empty($offer_letter['uploaded_document_s3_name']) ? $offer_letter['uploaded_document_s3_name'] : '';
                                                                                    $document_file = pathinfo($document_filename);
                                                                                    $name = explode(".",$document_filename);
                                                                                    $url_segment_original = $name[0]; ?>

                                                                                <button class="btn btn-info btn-sm btn-block"
                                                                                        onclick="fLaunchOfferModal(this);"
                                                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $offer_letter['uploaded_document_s3_name']; ?>"
                                                                                        data-download-url="<?php echo AWS_S3_BUCKET_URL . $offer_letter['uploaded_document_s3_name']; ?>"
                                                                                        data-print-url="<?php echo $url_segment_original; ?>"
                                                                                        data-document-sid="<?php echo  $offer_letter['sid']; ?>"
                                                                                        data-file-name="<?php echo $offer_letter['uploaded_document_s3_name']; ?>"
                                                                                        data-document-title="<?php echo $offer_letter['uploaded_document_original_name']; ?>">Preview</button>
                                                                             <?php   } else { ?>
                                                                                <button onclick="func_get_generated_document_preview(<?php echo $offer_letter['sid']; ?>,'offer', '<?php echo addslashes($offer_letter['letter_name']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                                 <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                        <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                            <button onclick="func_archive_offer_letter(<?php echo $offer_letter['sid'];?>);" type="button" class="btn btn-warning btn-sm btn-block">Archive</button>
                                                                        <?php           } ?>
                                                                        <form id="form_archive_offer_letter_<?php echo $offer_letter['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="archive_offer_letter" />
                                                                                <input type="hidden" id="offer_letter_sid" name="offer_letter_sid" value="<?php echo $offer_letter['sid']; ?>" />
                                                                            </form>
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
                                                        <div class="pull-right total-records"><b><?php echo 'Total: '.count($all_documents);?></b></div>
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
                                                         <?php if(count($all_documents) > 0) {
                                                                foreach ($all_documents as $document) { ?>
                                                                    <tr>
                                                                        <td class="col-xs-6"><?php echo $document['document_title']; ?></td>
                                                                        <td class="col-xs-2">
                                                         <?php  if($document['date_created'] != NULL || $document['date_created'] != '') {
                                                                                echo reset_datetime(array( 'datetime' => $document['date_created'], '_this' => $this));
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
                                                                            <?php if($document['document_type'] == 'uploaded') {?>
                                                                                <button onclick="func_assign_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>);" class="btn btn-primary btn-block btn-sm">Bulk Assign</button>
                                                                            <?php } else { ?>
                                                                                <button class="btn btn-primary btn-sm btn-block"
                                                                                        onclick="fLaunchModalGen(this);"
                                                                                        data-title="<?php echo $document['document_title']; ?>"
                                                                                        data-description="<?php echo $document['document_description']; ?>"
                                                                                        data-document-type="<?php echo $document['document_type']; ?>"
                                                                                        data-document-sid="<?php echo $document['sid']; ?>">Bulk Assign</button>
                                                                             <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                     <?php   if (check_access_permissions_for_view($security_details, 'add_edit_upload_generate_document')) {?>
                                                                                <a href="<?php echo base_url('hr_documents_management/edit_hr_document/' . $document['sid']); ?>" class="btn btn-success btn-sm btn-block">Edit Info</a>
                                                                        <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                        <?php if($document['document_type'] == 'uploaded') {
                                                                                    $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                                                    $document_file = pathinfo($document_filename);
                                                                                    $name = explode(".",$document_filename);
                                                                                    $url_segment_original = $name[0]; ?>

                                                                                <button class="btn btn-info btn-sm btn-block"
                                                                                        onclick="fLaunchModal(this);"
                                                                                        data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                        data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>"
                                                                                        data-print-url="<?php echo $url_segment_original; ?>"
                                                                                        data-document-sid="<?php echo  $document['sid']; ?>"
                                                                                        data-file-name="<?php echo $document['uploaded_document_original_name']; ?>"
                                                                                        data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">Preview</button>
                                                                             <?php   } else { ?>
                                                                                <button onclick="func_get_generated_document_preview(<?php echo $document['sid']; ?>,'generated', '<?php echo addslashes($document['document_title']); ?>');" class="btn btn-info btn-sm btn-block">Preview</button>
                                                                                 <?php  } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <form id="form_archive_hr_document_<?php echo $document['sid']; ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="archive_uploaded_document" />
                                                                                <input type="hidden" id="document_type" name="document_type" value="<?= $document['document_type']?>" />
                                                                                <input type="hidden" id="document_sid" name="document_sid" value="<?php echo $document['sid']; ?>" />
                                                                            </form>
                                                                        <?php if (check_access_permissions_for_view($security_details, 'archive_document')) { ?>
                                                                            <button class="btn btn-warning btn-sm btn-block" onclick="func_archive_uploaded_document(<?php echo $document['sid']; ?>)">Archive</button>
                                                                        <?php } ?>
                                                                        </td>
                                                                        <td class="col-xs-1">
                                                                            <!-- Convert document to Pay Plan -->
                                                                            <button class="btn btn-success btn-sm btn-block" onclick="convertToPayPlan(<?=$document['sid'];?>, '<?=$document['document_type'];?>')">Convert To Pay Plan</button>
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
                        <?php if(!empty($sections)) { ?>
                            <?php foreach($sections as $section) { ?>
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
                                        <?php if($section['video_status'] == 1) { ?>
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

                                        <?php if($section['youtube_video_status'] == 1) { ?>
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
                        <div class="col-lg-12">
                            <div class="form-group full-width">
                                <label>Employees <span class="hr-required red">*</span></label>
                                <div class="hr-select-dropdown">
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
                                            <input type="text" class="form-control tag" readonly="" value="{{text}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control tag" readonly="" value="{{checkbox}}">
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
                        <div class="col-md-12">
                            <div class="form-group" style="min-height: 300px">
                                <label>Employees<span class="hr-required red"> * </span></label>
                                <select require multiple="multiple" name="employees[]" id="uploaded-employees" style="display: block"><option value="" selected>Please Select Employee</option></select>
                            </div>
                        </div>
                        <div class="col-lg-12" id="uploaded-empty-emp" style="display: none;"><span class="hr-required red">This Document Is Assigned To All Employees!</span></div>
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
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/selectize.css')?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/selectize.bootstrap3.css')?>">
<script src="<?= base_url('assets/js/selectize.min.js')?>"></script>

<div id="js-loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we are processing your request...
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#uploaded-form').validate({
            rules: {
                employees: {
                    required: true
                }
            },
            messages: {
                employees: {
                    required: 'Employee(s) Required'
                }
            },
            submitHandler:function(form){
                var up_emp = $('#uploaded-employees').val();
                if(up_emp != '' && up_emp != null)
                    form.submit();
                else
                    alertify.error('Please select Employee(s)');
            }
        });
    });

    function func_un_archive_offer_letter(offer_letter_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to un-archive this offer letter?',
            function () {
                $('#form_un_archive_offer_letter_' + offer_letter_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function send_bulk_document () {
        alertify.confirm(
        'Are you sure?',
        'Are you sure you want to assign this document as bulk?',
        function () {
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

            $('#register-form').submit();
        },
        function () {
            alertify.error('Cancelled!');
        });
    }

    function func_delete_offer_letter(offer_letter_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this offer letter?',
            function () {
                $('#form_delete_offer_letter_' + offer_letter_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_archive_offer_letter(offer_letter_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to archive this offer letter?',
            function () {
                $('#form_archive_offer_letter_' + offer_letter_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_delete_generated_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this document?',
            function () {
                $('#form_delete_generated_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_unarchive_generated_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to un-archive this document?',
            function () {
                $('#form_unarchive_generated_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_archive_generated_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to archive this document?',
            function () {
                $('#form_archive_generated_document_' + document_sid).submit();
            },
            function () {
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


        my_request.done(function (response) {
            $.ajax({
                'url': '<?php echo base_url('hr_documents_management/get_print_url'); ?>',
                'type': 'POST',
                'data': {
                    'request_type': 'original',
                    'document_type': doc_flag,
                    'document_sid': document_sid
                },
                success: function (urls) {
                    var obj = jQuery.parseJSON(urls);
                    var print_url = obj.print_url;
                    var download_url = obj.download_url;
                    footer_content = '<a target="_blank" class="btn btn-success" href="'+download_url+'">Download</a>';
                    footer_print_btn = '<a target="_blank" class="btn btn-success" href="'+print_url+'" >Print</a>';
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
        allowEmptyOption:false,
        persist: true,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });

    var up_emp = upemployees[0].selectize;

    function func_assign_document(type, document_sid) {
        $('#model_uploaded_doc').modal('toggle');
        $('#up-doc-type').val(type);
        $('#up-doc-sid').val(document_sid);
        $.ajax({
            type:'POST',
            url: '<?= base_url('hr_documents_management/get_document_employees')?>',
            data: {
                doc_sid: document_sid,
                doc_type: 'uploaded'
            },
            success: function (data) {
                var employees = JSON.parse(data);
                if(employees.length == 0){
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
                } else{
                    $('#uploaded-empty-emp').hide();
                    $('#send-up-doc').show();
                    up_emp.enable();
                    up_emp.clearOptions();
                    up_emp.load(function(callback) {

                        var arr = [{}];
                        var j = 0;

                        for (var i = 0; i < employees.length; i++) {
                            arr[j++] = {
                                value: employees[i].sid,
                                text: (employees[i].first_name + ' ' + employees[i].last_name)+' '+ '[ '+ remakeAccessLevel(employees[i])+' ]'
                            }
                        }

                        callback(arr);
                        up_emp.refreshItems();
                    });
                }
            },
            error: function () {

            }
        });
    }
     function remakeAccessLevel(obj){
        if((obj.is_executive_admin) && (obj.is_executive_admin != 0)){
            obj.is_executive_admin = 'Executive '+obj['access_level'];
        }
        if(obj.access_level_plus == 1 && obj.pay_plan_flag == 1) return obj.access_level+' Plus / Payroll';
        if(obj.access_level_plus == 1) return obj.access_level+' Plus';
        if(obj.pay_plan_flag == 1) return obj.access_level+' Payroll';
        return obj.access_level;
    }
    var employees = $('#employees').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        allowEmptyOption:false,
        persist: true,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });

    var emp = employees[0].selectize;

    function fLaunchModalGen(source) {
        var document_title = $(source).attr('data-title');
        var document_description = $(source).attr('data-description');
        var document_type = $(source).attr('data-document-type');
        var document_sid = $(source).attr('data-document-sid');
        var title = 'Modify and Bulk Assign This Document';
        var document_label = "Are you sure you want to bulk assign this document: [<b>"+document_title+ "</b>]";
        var button_title = 'Bulk Assign This Document';
        $('#document_sid_for_validation').val(document_sid);

        $('#model_generated_doc').modal('toggle');
//        $('#gen-doc-title').val(document_title);
        CKEDITOR.instances.gen_doc_description.setData(document_description);
//        $('#gen-doc-description').html(document_description);
        $('#gen-doc-type').val(document_type);
        $('#gen-doc-sid').val(document_sid);
        $('#send-gen-doc').val(button_title);
        $('#generated_document_title').html(title);
        $('#gen_document_label').html(document_label);
        $.ajax({
            type:'POST',
            url: '<?= base_url('hr_documents_management/get_document_employees')?>',
            data: {
                doc_sid: document_sid,
                doc_type: 'generated'
            },
            success: function (data) {
                var employees = JSON.parse(data);
                if(employees.length == 0){
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
                } else{
                    $('#empty-emp').hide();
                    emp.enable();
                    emp.clearOptions();
                    emp.load(function(callback) {

                        var arr = [{}];
                        var j = 0;

                        for (var i = 0; i < employees.length; i++) {
                            arr[j++] = {
                                value: employees[i].sid,
                                text: employees[i].first_name + ' ' + employees[i].last_name
                            }
                        }

                        callback(arr);
                        emp.refreshItems();
                    });
                }
            },
            error: function () {

            }
        });
    }

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
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original/generated'); ?>'+'/'+document_sid+'" class="btn btn-success">Print</a>';
                    break;
                default : //using google docs
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
            success: function (urls) {
                var obj = jQuery.parseJSON(urls);
                var print_url = obj.print_url;
                var download_url = obj.download_url;
                footer_content = '<a target="_blank" class="btn btn-success" href="'+download_url+'">Download</a>';
                footer_print_btn = '<a target="_blank" class="btn btn-success" href="'+print_url+'" >Print</a>';

                $('#document_modal_body').html(modal_content);
                $('#document_modal_footer').html(footer_content);
                $('#document_modal_footer').append(footer_print_btn);
                $('#document_modal_title').html(document_title);
                $('#document_modal').modal("toggle");
                $('#document_modal').on("shown.bs.modal", function () {

                    if (iframe_url != '') {
                        $('#preview_iframe').attr('src', iframe_url);
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
                    footer_print_btn = '<a target="_blank" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original/generated'); ?>'+'/'+document_sid+'" class="btn btn-success">Print</a>';
                    break;
                default : //using google docs
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
            success: function (urls) {
                var obj = jQuery.parseJSON(urls);
                var print_url = obj.print_url;
                var download_url = obj.download_url;
                footer_content = '<a target="_blank" class="btn btn-success" href="'+download_url+'">Download</a>';
                footer_print_btn = '<a target="_blank" class="btn btn-success" href="'+print_url+'" >Print</a>';

                $('#document_modal_body').html(modal_content);
                $('#document_modal_footer').html(footer_content);
                $('#document_modal_footer').append(footer_print_btn);
                $('#document_modal_title').html(document_title);
                $('#document_modal').modal("toggle");
                $('#document_modal').on("shown.bs.modal", function () {

                    if (iframe_url != '') {
                        $('#preview_iframe').attr('src', iframe_url);
                    }
                });
            }
        });
               
    }

    function func_unarchive_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to un-archive this document?',
            function () {
                $('#form_unarchive_uploaded_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_archive_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to archive this document?',
            function () {
                $('#form_archive_hr_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_delete_uploaded_document(document_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this document?',
            function () {
                $('#form_delete_uploaded_document_' + document_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    $(function () {
        $("#settings-tabs").tabs();
        $("#home-accordion").accordion({
            collapsible: true
        });

        $('#file_image').on('change', function () {
            $('#image').val('');
        });

        $(".tab_content").hide();
        $(".tab_content:first").show();
        $("ul.tabs li").click(function () {
            $("ul.tabs li").removeClass("active");
            $(this).addClass("active");
            $(".tab_content").hide();
            var activeTab = $(this).attr("rel");
            $("#" + activeTab).fadeIn();
        });

        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    // Convert a document to pay plan
    function convertToPayPlan(
        documentId,
        documentType
    ){
        // Confirm 
        alertify.confirm('Do you really want to convert this document to Pay Plan?', 
            function(){
                // Show loader
                loader('show');
                // Send ajax request to convert
                $.post("<?=base_url('hr_documents_management/convert_document_to_payplan');?>", {
                    documentId: documentId,
                    documentType: documentType
                }, function(resp) {
                    if(resp.Status === false){
                        loader('hide');
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response, function(){
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
    function loader(doShow){
        if(doShow == true || doShow == 'show' || doShow == undefined) $('#js-loader').fadeIn();
        else $('#js-loader').fadeOut();
    }

    loader('hide');
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
