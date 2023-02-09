<?php

//$load_view = 'old';
//
//if ($this->session->userdata('logged_in')) {
//    if (!isset($session)) {
//        $session = $this->session->userdata('logged_in');
//    }
//    $access_level = $session['employer_detail']['access_level'];
//
//    if ($access_level == 'Employee') {
//        $load_view = 'new';
//    }
//}

?>
<?php if (!$load_view) { ?>

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
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <?php if(!empty($documents)) { ?>
                                            <div class="row">
                                                <?php foreach($documents as $document) { ?>
                                                    <?php if($document['document_type'] != 'uploaded') { ?>
                                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                            <div class="hr-box">
                                                                <div class="hr-innerpadding">
                                                                    <div class="text-center text-success">
                                                                        <i class="fa fa-file-o" style="font-size: 100px; margin: 30px;"></i>
                                                                        <div style="min-height: 75px;">
                                                                            <h4>
                                                                                <?php echo $document['document_type'] == 'uploaded' ? $document['document_name'] : $document['document_title']; ?>
                                                                            </h4>
                                                                        </div>
                                                                        <?php if($document['document_type'] == 'uploaded') { ?>
                                                                            <a href="<?php echo base_url('documents_management/sign_u_document/' . $document['document_sid']); ?>" class="btn btn-success">View Sign</a>
                                                                        <?php } else if($document['document_type'] == 'generated') { ?>
                                                                            <a href="<?php echo base_url('documents_management/sign_g_document/' . $document['document_sid']); ?>" class="btn btn-success">View Sign</a>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        <?php } if(!empty($w4_form)){?>
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="hr-box">
                                                    <div class="hr-innerpadding">
                                                        <div class="text-center text-success">
                                                            <i class="fa fa-file-o" style="font-size: 100px; margin: 30px;"></i>
                                                            <div style="min-height: 75px;">
                                                                <h4>
                                                                    W4 Form
                                                                </h4>
                                                            </div>
                                                            <a href="<?php echo base_url('form_w4/'); ?>" class="btn btn-success">View Sign</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } if(!empty($i9_form)){?>
                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <div class="hr-box">
                                                    <div class="hr-innerpadding">
                                                        <div class="text-center text-success">
                                                            <i class="fa fa-file-o" style="font-size: 100px; margin: 30px;"></i>
                                                            <div style="min-height: 75px;">
                                                                <h4>
                                                                    I9 Form
                                                                </h4>
                                                            </div>
                                                            <a href="<?php echo base_url('form_i9'); ?>" class="btn btn-success">View Sign</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } if(empty($i9_form) && empty($w4_form) && empty($documents)) { ?>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="text-center">
                                                        <span class="no-data">No Documents Founds</span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="page-header">
                                            <h1 class="section-ttile">Documents from Old System</h1>
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

<?php }  else { ?>
    <?php $this->load->view('onboarding/documents'); ?>
<?php } ?>