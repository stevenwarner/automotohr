
<?php if (!$load_view) { ?>

<style>
    .green_panel_consent_btn {
        display: inline-block;
        padding: 10px 20px;
        color: #fff;
        background-color: #81b431;
        border: none;
        max-width: 210px;
        min-width: 97px;
        text-align: center;
        margin: 0 5px;
        border-radius: 5px;
        font-weight: 600;
        text-transform: capitalize;
        font-style: italic;
    }

    .green_panel_consent_btn:hover {
        background-color: #518401;
    }

    .get_signature {
        background-color: #81b431 !important;
    }

    .replace_signature{
        background-color: #81b431 !important;
    }

    .font_plus_sign {
        display: none;
    }
</style>


<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <span class="pull-left" id="jsGeneralDocumentArea"></span>
                                    <a class="btn btn-success pull-right" href="<?php echo base_url('direct_deposit/pd').'/'.$dd_user_type.'/'.$dd_user_sid.'/'.$company_id.'/download'; ?>" target="_blank">Download</a>
                                    <a class="btn btn-success pull-right" href="<?php echo base_url('direct_deposit/pd').'/'.$dd_user_type.'/'.$dd_user_sid.'/'.$company_id.'/print'; ?>" target="_blank" style="margin-right: 10px;">Print</a>
                                    <button 
                                            class="btn btn-success JsSendReminderEmailLI pull-right"
                                            data-id="<?=$dd_user_sid;?>"
                                            data-type="<?=$dd_user_type;?>"
                                            data-slug="direct-deposit-information"
                                            style="margin-right: 10px;"
                                        >Send An Email Reminder</button>
                                </div>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <br />
                                <?php $this->load->view('direct_deposit/form'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.green_panel_consent_btn').removeClass('pull-right');
        $('.green_panel_consent_btn').addClass('pull-center');
    });    
</script>

<?php $this->load->view('hr_documents_management/general_document_assignment_single', [
    'generalActionType' => 'direct_deposit',
    'companySid' => $company_id,
    'userSid' => $dd_user_sid,
    'userType' => $dd_user_type
]);?>

<?php } else { ?>
    <?php //$this->load->view('onboarding/bank_details'); ?>
<?php } ?>


