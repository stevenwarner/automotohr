<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/documents/' . $unique_sid);

    $save_post_url = current_url();

    $first_name = $applicant['first_name'];
    $last_name = $applicant['last_name'];
    $email = $applicant['email'];

    $view_data = array();
    $view_data['company_sid'] = $company_sid;
    $view_data['users_type'] = $users_type;
    $view_data['users_sid'] = $users_sid;
    $view_data['first_name'] = $first_name;
    $view_data['last_name'] = $last_name;
    $view_data['email'] = $email;
    $view_data['save_post_url'] = $save_post_url;
    $view_data['documents_assignment_sid'] = $document['sid'];

} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];


    $back_url = base_url('documents_management/my_documents');
    $save_post_url = current_url();

    $first_name = $employee['first_name'];
    $last_name = $employee['last_name'];
    $email = $employee['email'];

    $view_data = array();
    $view_data['company_sid'] = $company_sid;
    $view_data['users_type'] = $users_type;
    $view_data['users_sid'] = $users_sid;
    $view_data['first_name'] = $first_name;
    $view_data['last_name'] = $last_name;
    $view_data['email'] = $email;
    $view_data['save_post_url'] = $save_post_url;
    $view_data['documents_assignment_sid'] = $document['sid'];
}

?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a href="<?php echo $back_url; ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Documents</a>
                </div>
                <div class="page-header">
                    <h1 class="section-ttile">Sign Document</h1>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="document_container" style="padding: 40px; background-color: #FFFFFF;">
                            <div class="text-center">
                                <h3 class=""><?php echo $document['document_title']; ?></h3>
                            </div>
                            <?php echo html_entity_decode($document['document_content']); ?>
                            <hr />
                            <?php $this->load->view('onboarding/e_signature_partial', $view_data); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
