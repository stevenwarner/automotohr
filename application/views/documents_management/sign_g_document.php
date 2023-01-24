<?php

$load_view = 'old';

if ($this->session->userdata('logged_in')) {
    if (!isset($session)) {
        $session = $this->session->userdata('logged_in');
    }
    $access_level = $session['employer_detail']['access_level'];

    if ($access_level == 'Employee') {
        $load_view = 'new';
    }
}

?>
<?php if ($load_view == 'old') { ?>
    <div class="main-content">
        <div class="dashboard-wrp">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                        <?php $this->load->view('main/employer_column_left_view'); ?>
                    </div>
                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="page-header-area">
                                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('documents_management'); ?>"><i class="fa fa-chevron-left"></i>Dashboard</a>
                                        Sign Document
                                    </span>
                                </div>
                                <div class="dashboard-conetnt-wrp">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="document_container" style="padding: 40px; background-color: #FFFFFF;">
                                                        <div class="text-center">
                                                            <h3 class=""><?php echo ucwords($document['document_title']); ?></h3>
                                                        </div>
                                                        <?php echo html_entity_decode($document['document_content']); ?>
                                                        <?php

                                                            $view_data = array();
                                                            $view_data['company_sid'] = $company_sid;
                                                            $view_data['users_type'] = 'employee';
                                                            $view_data['users_sid'] = $employer_sid;
                                                            $view_data['first_name'] = $first_name;
                                                            $view_data['last_name'] = $last_name;
                                                            $view_data['email'] = $email;
                                                            $view_data['save_post_url'] = current_url();
                                                            $view_data['documents_assignment_sid'] = $document['sid'];

                                                        ?>
                                                        <?php $this->load->view('onboarding/e_signature_partial', $view_data); ?>
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

<?php }  else if ($load_view == 'new') { ?>
    <?php $this->load->view('onboarding/sign_g_document'); ?>
<?php } ?>