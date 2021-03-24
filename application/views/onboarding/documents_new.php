<?php

if($this->uri->segment(1) == 'hr_documents_management') $sideBar = '';
else $sideBar = onboardingHelpWidget($company_info['sid']);

$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';
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
    $next_btn = '<a href="' . base_url('onboarding/my_profile/' . $unique_sid) . '" class="btn btn-success btn-block">  Proceed To Next <i class="fa fa-angle-right"></i></a>';
    $center_btn = '<a href="' . base_url('onboarding/my_profile/' . $unique_sid) . '" class="btn btn-warning btn-block"> Bypass This Step <i class="fa fa-angle-right"></i></a>';
    $back_btn = 'Review Previous Step';
    $document_d_base = base_url('onboarding/sign_hr_document/d/' . $unique_sid);
    $document_offer_letter_base = base_url('onboarding/sign_hr_document/o/' . $unique_sid);
    $i9_url = base_url('onboarding/form_i9/' . $unique_sid);
    $w4_url = base_url('onboarding/form_w4/' . $unique_sid);
    $w9_url = base_url('onboarding/form_w9/' . $unique_sid);
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');
    $document_d_base = base_url('hr_documents_management/sign_hr_document/d');
    $document_offer_letter_base = base_url('hr_documents_management/sign_hr_document/o');
    $i9_url = base_url('form_i9');
    $w4_url = base_url('form_w4');
    $w9_url = base_url('form_w9');
}
$tab_data = array();
$tab_data['document_d_base'] = $document_d_base;
$tab_data['document_offer_letter_base'] = $document_offer_letter_base;

?>

<div class="main">
    <div class="container">
        <div class="row">
           <?php if($this->uri->segment(1) != 'hr_documents_management'){ ?>
            <div class="col-sm-12">
                <p style="color: #cc0000;"><b><i>We suggest that you only use Google Chrome to access your account
                            and use its Features. Internet Explorer is not supported and may cause certain feature
                            glitches and security issues.</i></b></p>
                             <?=$sideBar;?>
            </div>
            <?php } ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message');?>
                <?php if (isset($applicant)) {?>
                <div class="btn-panel">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <a href="<?php echo $back_url; ?>" class="btn btn-info btn-block"><i
                                    class="fa fa-angle-left"></i> <?=$back_btn;?></a>
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <?php echo $center_btn; ?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <?php echo $next_btn; ?>
                        </div>
                    </div>
                </div>
                <?php } elseif (isset($employee)) {?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>"
                            class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
                <?php }?>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">Document Management</h1>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-blue">Below is a list of company documents assigned to you. Some of the
                            document(s) on this list may only require that you Acknowledge receiving them and a
                            Signature etc may not be needed. For instance an 'Employee Handbook' may just require an
                            "Acknowledgement"</p>
                        <p class="text-blue"><b>Please review these company documents and take appropriate action. Many
                                of these documents are extremely time sensitive and need to be completed as soon as
                                possible.</b></p>
                        <p class="text-blue"><b><i class="fa fa-asterisk" style="color: #cc1100;" aria-hidden="true"></i> means the document is required and needs to be signed to complete the onboarding process.</b></p>
                        <?php if($this->uri->segment(2) == 'my_documents'){ ?>
                        <p class="text-blue red-paragragh "><b>We suggest that you only use Google Chrome to access your
                                account and
                                use its Features. Internet Explorer is not supported and may cause certain feature
                                glitches and security issues.</b></p>
                        <?php } ?>
                        <!--                        <h4><b>Download the "Adobe Scan: Doc Scanner" for Free from the <span class="text-success">Apple App Store</span> and <span class="text-danger">Google Play</span>.</b></h4>-->

                        <?php if (!empty($documents) || !empty($eev_w4) || !empty($w4_form) || !empty($eev_i9) || !empty($i9_form) || !empty($eev_w9) || !empty($w9_form) || !empty($assigned_offer_letter) || !empty($NotCompletedGeneralDocuments) || !empty($CompletedGeneralDocuments)) {?>
                            <div class="panel panel-default ems-documents">
                                <div class="panel-heading">
                                    <strong>Documents Details</strong>
                                </div>
                                <div class="panel-body">
                                    <?php $this->load->view('hr_documents_management/my_documents_tab_pages', $tab_data);?>
                                </div>
                            </div>
                        <?php }?>
                        <?php $hider = false; ?>
                        <?php if ((!empty($i9_form) || !empty($w4_form) || !empty($w9_form)) && $hider) {?>
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
                                            <?php if (!empty($w4_form)) {?>
                                            <tr>
                                                <td class="col-lg-3">
                                                    W4 Fillable
                                                    <?php if ($w4_form['user_consent']) {?>
                                                    <img class="img-responsive pull-left"
                                                        style=" width: 22px; height: 22px; margin-right:5px;"
                                                        title="Signed" data-toggle="tooltip" data-placement="top"
                                                        src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                    <?php } else {?>
                                                    <img class="img-responsive pull-left"
                                                        style=" width: 22px; height: 22px; margin-right:5px;"
                                                        title="Unsigned" data-toggle="tooltip" data-placement="top"
                                                        src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                    <?php }?>
                                                </td>
                                                <td class="col-lg-1 text-center">
                                                    <i class="fa fa-2x fa-file-text"></i>
                                                </td>
                                                <td class="col-lg-2 text-center">
                                                    <?php if (isset($w4_form['sent_date'])) {?>
                                                        <i class="fa fa-check fa-2x text-success"></i>
                                                        <div class="text-center">
                                                            <?=reset_datetime(array('datetime' => $w4_form['sent_date'], '_this' => $this));?>
                                                        </div>
                                                        <?php } else {?>
                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                    <?php }?>
                                                </td>
                                                <td class="col-lg-1 text-center">
                                                    <a href="<?php echo $w4_url; ?>" class="btn btn-info">View Sign</a>
                                                </td>
                                            </tr>
                                            <?php }?>
                                            <?php if (!empty($i9_form)) {?>
                                            <tr>
                                                <td class="col-lg-3">
                                                    I9 Fillable
                                                    <?php if ($i9_form['user_consent']) {?>
                                                    <img class="img-responsive pull-left"
                                                        style=" width: 22px; height: 22px; margin-right:5px;"
                                                        title="Signed" data-toggle="tooltip" data-placement="top"
                                                        src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                    <?php } else {?>
                                                    <img class="img-responsive pull-left"
                                                        style=" width: 22px; height: 22px; margin-right:5px;"
                                                        title="Unsigned" data-toggle="tooltip" data-placement="top"
                                                        src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                    <?php }?>
                                                </td>
                                                <td class="col-lg-1 text-center">
                                                    <i class="fa fa-2x fa-file-text"></i>
                                                </td>
                                                <td class="col-lg-2 text-center">
                                                    <?php if (isset($i9_form['sent_date'])) {?>
                                                        <i class="fa fa-check fa-2x text-success"></i>
                                                        <div class="text-center">
                                                            <?=reset_datetime(array('datetime' => $i9_form['sent_date'], '_this' => $this));?>
                                                        </div>
                                                        <?php } else {?>
                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                    <?php }?>
                                                </td>
                                                <td class="col-lg-1 text-center">
                                                    <a href="<?php echo $i9_url; ?>" class="btn btn-info">View Sign</a>
                                                </td>
                                            </tr>
                                            <?php }?>
                                            <?php if (!empty($w9_form)) {?>
                                            <tr>
                                                <td class="col-lg-3">
                                                    W9 Fillable
                                                    <?php if ($w9_form['user_consent']) {?>
                                                    <img class="img-responsive pull-left"
                                                        style=" width: 22px; height: 22px; margin-right:5px;"
                                                        title="Signed" data-toggle="tooltip" data-placement="top"
                                                        src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                    <?php } else {?>
                                                    <img class="img-responsive pull-left"
                                                        style=" width: 22px; height: 22px; margin-right:5px;"
                                                        title="Unsigned" data-toggle="tooltip" data-placement="top"
                                                        src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                    <?php }?>
                                                </td>
                                                <td class="col-lg-1 text-center">
                                                    <i class="fa fa-2x fa-file-text"></i>
                                                </td>
                                                <td class="col-lg-2 text-center">
                                                    <?php if (isset($w9_form['sent_date'])) {?>
                                                        <i class="fa fa-check fa-2x text-success"></i>
                                                        <div class="text-center">
                                                            <?=reset_datetime(array('datetime' => $w9_form['sent_date'], '_this' => $this));?>
                                                        </div>
                                                    <?php } else {?>
                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                    <?php }?>
                                                </td>
                                                <td class="col-lg-1 text-center">
                                                    <a href="<?php echo $w9_url; ?>" class="btn btn-info">View Sign</a>
                                                </td>
                                            </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php }?>

                        <?php if (empty($eev_i9) && empty($i9_form) && empty($eev_w4) && empty($w4_form) && empty($eev_w9) && empty($w9_form) && empty($assigned_offer_letter) && empty($documents) && empty($NotCompletedGeneralDocuments) && empty($CompletedGeneralDocuments)) {?>
                            <div class="panel panel-default ems-documents">
                                <div class="panel-heading">
                                    <strong>Documents Details</strong>
                                </div>
                                <div class="panel-body text-center">
                                    <span class="no-data">No Documents Assigned</span>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if($sideBar != ''){ ?>
        </div>
    <?php } ?>
</div>

<?php if(isset($GID)) { ?>
<!--  -->
<?php $this->load->view('hr_documents_management/general_document_assignment_blue', [
    'company_sid' => $GID['company_sid'],
    'user_sid' => $GID['user_sid'],
    'user_type' => $GID['user_type'],
    'GID' => true
]); ?>
<?php } ?>

<script>
    function loadTT(){
       $('.jsTooltip').tooltip({
           placement: "top auto",
           trigger: "hover"
       });
    };

    loadTT();
</script>