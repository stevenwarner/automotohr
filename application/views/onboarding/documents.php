<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';
$document_g_base = '';
$document_offer_letter_base = '';
$document_u_base = '';
$next_btn = '';
$center_btn = '';
$back_btn = 'Dashboard';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/e_signature/' . $unique_sid);
    $next_btn = '<a href="'.base_url('onboarding/eeoc_form/' . $unique_sid).'"class="btn btn-success btn-block" id="go_next"> Proceed To Next <i class="fa fa-angle-right"></i></a>';
    $center_btn = '<a href="'.base_url('onboarding/eeoc_form/' . $unique_sid).'" class="btn btn-warning btn-block"> Bypass This Step <i class="fa fa-angle-right"></i></a>';
    $back_btn = 'Review Previous Step';

    $document_g_base = base_url('onboarding/sign_g_document/' . $unique_sid . '/');
    $document_u_base = base_url('onboarding/sign_u_document/' . $unique_sid . '/');
    $document_offer_letter_base = base_url('onboarding/sign_offer_letter/' . $unique_sid . '/');

    $i9_url = base_url('onboarding/form_i9/'.$unique_sid);
    $w4_url = base_url('onboarding/form_w4/'.$unique_sid);

} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');

    $document_g_base = base_url('documents_management/sign_g_document/');
    $document_u_base = base_url('documents_management/sign_u_document/');
    $document_offer_letter_base = base_url('documents_management/sign_offer_letter/');

    $i9_url = base_url('form_i9');
    $w4_url = base_url('form_w4');
}

?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <a href="<?php echo $back_url; ?>" class="btn btn-info btn-block"><i class="fa fa-angle-left"></i>  <?= $back_btn;?></a>
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <?php echo $center_btn;?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <?php echo $next_btn;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">    
                <div class="page-header">
                    <h1 class="section-ttile">Document Management</h1>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-blue">Below is a list of company documents assigned to you. Some of the document(s) on this list may only require that you Acknowledge receiving them and a Signature etc may not be needed. For instance an 'Employee Handbook' may just require an "Acknowledgement"</p>
                        <p class="text-blue"><b>Please review these company documents and take appropriate action. Many of these documents are extremely time sensitive and need to be completed as soon as possible.</b></p>
                        
                        <?php if(!empty($i9_form) || !empty($w4_form)) { ?>
                            <div class="panel panel-default ems-documents">
                                <div class="panel-heading">
                                    <strong>Employment Eligibility Verification Documents</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-plane">
                                            <thead>
                                                <tr>
                                                    <th class="col-lg-3">Document Name</th>
                                                    <th class="col-lg-3 text-center">Type</th>
                                                    <th class="col-lg-3 text-center">Sent On</th>
                                                    <th class="col-lg-3 text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              <?php if (!empty($w4_form)) { ?>
                                                    <tr>
                                                        <td class="col-lg-3">
                                                            W4 Fillable
                                                        </td>
                                                        <td class="col-lg-1 text-center">
                                                           <i class="fa fa-2x fa-file-text"></i>
                                                        </td>
                                                        <td class="col-lg-2 text-center">
                                                            <?php 
                                                                if (isset($w4_form['sent_date'])) { ?>
                                                                  <i class="fa fa-check fa-2x text-success"></i>
                                                                    <div class="text-center">
                                                                      <?php echo  date_format (new DateTime($w4_form['sent_date']), 'M d Y h:m a'); ?>
                                                                    </div>
                                                          <?php } else { ?>
                                                                <i class="fa fa-times fa-2x text-danger"></i>
                                                          <?php } ?>
                                                        </td>
                                                        <td class="col-lg-1 text-center">
                                                            <a href="<?php echo $w4_url; ?>" class="btn btn-info">View Sign</a>
                                                        </td>
                                                    </tr>
                                              <?php } ?>
                                                <?php if(!empty($i9_form)) { ?>
                                                    <tr>
                                                        <td class="col-lg-3">
                                                            I9 Fillable
                                                        </td>
                                                        <td class="col-lg-1 text-center">
                                                            <i class="fa fa-2x fa-file-text"></i> 
                                                        </td>
                                                        <td class="col-lg-2 text-center">
                                                            <?php 
                                                                if (isset($i9_form['sent_date'])) { ?>
                                                                    <!-- <i class="fa fa-check fa-2x text-success"></i> -->
                                                                    <div class="text-center">
                                                                        <?php echo  date_format (new DateTime($i9_form['sent_date']), 'M d Y h:m a'); ?>
                                                                    </div>
                                                            <?php } else { ?>
                                                                <i class="fa fa-times fa-2x text-danger"></i>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="col-lg-1 text-center">
                                                            <a href="<?php echo $i9_url; ?>" class="btn btn-info">View Sign</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>        
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php }  ?>
                        <?php if(!empty($documents) || !empty($old_system_documents) ) { ?>
                            <div class="panel panel-default ems-documents">
                                <div class="panel-heading">
                                    <strong>Documents Details</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-plane">
                                            <thead>
                                                <tr>
                                                    <th class="col-lg-3">Document Name</th>
                                                    <th class="col-lg-1 text-center">Type</th>
                                                    <th class="col-lg-2 text-center">Sent On</th>
                                                    <th class="col-lg-2 text-center">Acknowledged</th>
                                                    <th class="col-lg-2 text-center">Downloaded</th>
                                                    <th class="col-lg-1 text-center">Uploaded</th>
                                                    <th class="col-lg-1 text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    <?php if(!empty($documents)) { ?>
                                                        <?php foreach($documents as $document) { ?>
                                                            <tr>
                                                                <td class="col-lg-3"><?php echo $document['document_type'] == 'uploaded' ? $document['document_name'] : $document['document_title']; ?></td>
                                                                <td class="col-lg-1 text-center">
                                                                    <?php $doc_type = ''; ?>
                                                                    <?php if(!empty($document['document_s3_file_name'])) {
                                                                        $ext = pathinfo($document['document_s3_file_name'], PATHINFO_EXTENSION);
                                                                        $doc_type = $ext;
                                                                    } ?>
                                                                    <?php if($doc_type == 'pdf'){ ?>
                                                                        <i class="fa fa-2x fa-file-pdf-o"></i>
                                                                    <?php } else if(in_array($doc_type, [ 'ppt', 'pptx'])){ ?>
                                                                       <i class="fa fa-2x fa-file-powerpoint-o"></i>                          
                                                                    <?php } else if(in_array($doc_type, ['doc', 'docx'])){ ?>
                                                                          <i class="fa fa-2x fa-file-o"></i>
                                                                    <?php } else if(in_array($doc_type, ['xlsx'])){ ?> 
                                                                        <i class="fa fa-2x fa-file-excel-o"></i>
                                                                    <?php } else if($doc_type == ''){ ?> 
                                                                        <i class="fa fa-2x fa-file-text"></i>         
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php 
                                                                        if (isset($document['assigned_date'])) { ?>
                                                                            <i class="fa fa-check fa-2x text-success"></i>
                                                                            <div class="text-center">
                                                                                <?php echo  date_format (new DateTime($document['assigned_date']), 'M d Y h:m a'); ?>
                                                                            </div>
                                                                    <?php } else { ?>
                                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php 
                                                                        if (isset($document['acknowledged_date'])) { ?>
                                                                            <?php if ($document['acknowledged'] == 0) {?>
                                                                                <i class="fa fa-times fa-2x text-danger"></i>
                                                                            <?php } else { ?>
                                                                                <i class="fa fa-check fa-2x text-success"></i>
                                                                            <?php } ?>
                                                                            <div class="text-center">
                                                                                <?php echo  date_format (new DateTime($document['acknowledged_date']), 'M d Y h:m a'); ?>
                                                                            </div>
                                                                     <?php } elseif ($document['user_consent'] == 1) { ?> 
                                                                        <i class="fa fa-check fa-2x text-success"></i>
                                                                        <div class="text-center">
                                                                            <?php echo  date_format (new DateTime($document['signature_timestamp']), 'M d Y h:m a'); ?>
                                                                        </div>       
                                                                    <?php } else { ?>
                                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php 
                                                                        if (isset($document['downloaded_date'])) { ?>
                                                                            <?php if ($document['downloaded'] == 0) {?>
                                                                                <i class="fa fa-times fa-2x text-danger"></i>
                                                                            <?php } else { ?>
                                                                                <i class="fa fa-check fa-2x text-success"></i>
                                                                            <?php } ?>
                                                                            <div class="text-center">
                                                                                <?php echo  date_format (new DateTime($document['downloaded_date']), 'M d Y h:m a'); ?>
                                                                            </div>
                                                                    <?php } elseif ($document['user_consent'] == 1) { ?> 
                                                                        <i class="fa fa-check fa-2x text-success"></i>
                                                                        <div class="text-center">
                                                                            <?php echo  date_format (new DateTime($document['signature_timestamp']), 'M d Y h:m a'); ?>
                                                                        </div>         
                                     s                               <?php } else { ?>
                                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php 
                                                                        if (isset($document['uploaded_date'])) { ?>
                                                                            <?php if ($document['uploaded'] == 0) {?>
                                                                                <i class="fa fa-times fa-2x text-danger"></i>
                                                                            <?php } else { ?>
                                                                                <i class="fa fa-check fa-2x text-success"></i>
                                                                            <?php } ?>
                                                                            <div class="text-center">
                                                                                <?php echo  date_format (new DateTime($document['uploaded_date']), 'M d Y h:m a'); ?>
                                                                            </div>
                                                                    <?php } elseif ($document['user_consent'] == 1) { ?> 
                                                                        <i class="fa fa-check fa-2x text-success"></i>
                                                                        <div class="text-center">
                                                                            <?php echo  date_format (new DateTime($document['signature_timestamp']), 'M d Y h:m a'); ?>
                                                                        </div>         
                                                                    <?php } else { ?>
                                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-1 text-center">
                                                                    <?php if($document['document_type'] == 'uploaded') { ?>
                                                                        <a href="<?php echo  $document_u_base . '/' . $document['document_sid']; ?>" class="btn btn-info">View Sign</a>
                                                                    <?php } else if($document['document_type'] == 'generated' && $document['is_offer_letter'] == 0) { ?>
                                                                        <a href="<?php echo $document_g_base . '/' . $document['document_sid']; ?>" class="btn btn-info">View Sign</a>
                                                                    <?php } else if($document['is_offer_letter'] == 1) { ?>
                                                                        <a href="<?php echo $document_offer_letter_base . '/' . $document['document_sid']; ?>" class="btn btn-info">View Sign</a>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    
                                                    <?php if(!empty($old_system_documents)){
                                                        foreach($old_system_documents as $document) { ?>
                                                            <tr>
                                                                <td class="col-lg-3">
                                                                    <?php echo $document['document_original_name']; ?>
                                                                </td>
                                                                <td class="col-lg-1 text-center">
                                                                    <?php if ($document['document_type'] == 'docx') { ?>
                                                                        <i class="fa fa-file-excel-o"></i>
                                                                    <?php } else { ?>
                                                                        <i class="fa fa-upload"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php echo  date_format (new DateTime($document['sent_on']), 'M d Y h:m a'); ?>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php 
                                                                        if (isset($document['acknowledged_date'])) { ?>
                                                                            <?php if ($document['acknowledged'] == 0) {?>
                                                                                <i class="fa fa-times fa-2x text-danger"></i>
                                                                            <?php } else { ?>
                                                                                <i class="fa fa-check fa-2x text-success"></i>
                                                                            <?php } ?>    
                                                                            <div class="text-center">
                                                                                <?php echo  date_format (new DateTime($document['acknowledged_date']), 'M d Y h:m a'); ?>
                                                                            </div>
                                                                    <?php } else { ?>
                                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php 
                                                                        if (isset($document['downloaded_date'])) { ?>
                                                                            <?php if ($document['downloaded'] == 0) {?>
                                                                                <i class="fa fa-times fa-2x text-danger"></i>
                                                                            <?php } else { ?>
                                                                                <i class="fa fa-check fa-2x text-success"></i>
                                                                            <?php } ?>
                                                                            <div class="text-center">
                                                                                <?php echo  date_format (new DateTime($document['downloaded_date']), 'M d Y h:m a'); ?>
                                                                            </div>
                                                                    <?php } else { ?>
                                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-2 text-center">
                                                                    <?php 
                                                                        if (isset($document['uploaded_date'])) { ?>
                                                                            <?php if ($document['uploaded'] == 0) {?>
                                                                                <i class="fa fa-times fa-2x text-danger"></i>
                                                                            <?php } else { ?>
                                                                                <i class="fa fa-check fa-2x text-success"></i>
                                                                            <?php } ?>
                                                                            <div class="text-center">
                                                                                <?php echo  date_format (new DateTime($document['uploaded_date']), 'M d Y h:m a'); ?>
                                                                            </div>
                                                                    <?php } else { ?>
                                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="col-lg-1 text-center">
                                                                    <a href="<?php echo base_url('documents_management/old_system_document/' . $document['sid']); ?>" class="btn btn-info">View Sign</a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(empty($i9_form) && empty($w4_form) && empty($documents) && empty($old_system_documents)) { ?>
                            <div class="panel panel-default ems-documents">
                                <div class="panel-heading">
                                    <strong>Documents Details</strong>
                                </div>
                                <div class="panel-body text-center">
                                    <span class="no-data">No Documents Assigned</span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>                              
    </div>
</div>
