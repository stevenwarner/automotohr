<?php
if ($this->session->userdata('logged_in')) {
    $data['session'] = $this->session->userdata('logged_in');
    $company_sid = $data['session']['company_detail']['sid'];
    if(!isset($applicant_jobs)){
        $applicant_jobs = $this->application_tracking_system_model->get_single_applicant_all_jobs($id, $company_sid);
    } 
    // echo "appliid = ".$id." = company _sid = ".$company_sid;
    // echo "<pre>"; print_r($applicant_jobs); exit; 
}

?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="application-header">
                                <article>
                                    <figure>
                                        <img src="<?php echo isset($user_info['pictures']) && $user_info['pictures'] != NULL && $user_info['pictures'] != '' ? AWS_S3_BUCKET_URL . $user_info['pictures'] : base_url('assets/images/default_pic.jpg'); ?>" alt="Profile Picture" />
                                    </figure>
                                    <div class="text">
                                        <h2><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></h2>
                                        <div class="start-rating">
                                            <input readonly="readonly" id="input-21b" value="<?php echo isset($user_average_rating) ? $user_average_rating : 0; ?>" type="number" name="rating" class="rating" min=0 max=5 step=0.2  data-size="xs" />
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/' . $user_info['sid']); ?>">
                                        <i class="fa fa-chevron-left"></i>Applicant Profile</a>
                                    Resume Library
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header full-width">
                            <h1 class="section-ttile"> Resume Library</h1>
                            <strong> Information:</strong> If you are unable to view the resume library, kindly reload the page.
                            <?php if(count($applicant_jobs) == 1){ ?>
                            <a class="btn btn-success float-right confirmation" href="javascript:;" src="<?php echo base_url("onboarding/send_applicant_resume_request/applicant") . "/" . $user_info["sid"] . "/" . $job_list_sid; ?>"><i class="fa fa-envelope"></i> Send Resume Request</a>
                            <?php } else { ?>
                                        <a class="btn btn-success float-right" href="javascript:0;" data-toggle="modal" data-target="#send_resume_request"><i class="fa fa-envelope"></i> Send Resume Request</a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> 
                        <?php if (!empty($resume_listing)) { ?>
                            <?php foreach ($resume_listing as $key => $job) { ?>
                                <?php 
                                    $colspan_id = str_replace(' ', '_', $job['job_name']);
                                ?>
                                <div class="accordion-colored-header header-bg-gray">
                                    <div class="panel-group" id="onboarding-configuration-accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                       href="#<?php echo $colspan_id; ?>">
                                                        <span class="glyphicon glyphicon-plus js-main-gly"></span>
                                                        <?php 
                                                            echo $job['job_name'].' ( '.reset_datetime(array('datetime' => $job['last_update'], '_this' => $this)).' )';  
                                                        ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="<?php echo $colspan_id; ?>" class="panel-collapse collapse js-main-coll">
                                                <div class="panel-body">
                                                    <?php if (!empty($job['resumes'])) { ?>
                                                        <?php foreach ($job['resumes'] as $key => $resume) { ?>
                                                            <div class="accordion-colored-header header-bg-gray">
                                                                <div class="panel-group" id="onboarding-configuration-accordions">
                                                                    <div class="panel panel-default parent_div">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a data-toggle="collapse" data-parent="#onboarding-configuration-accordions"
                                                                                   href="#<?php echo $colspan_id.'_'.$key; ?>" ><span class="glyphicon glyphicon-plus js-child-gly"></span>
                                                                                    <div class="btn btn-xs btn-success pull-right" id="<?php echo 'viewc_'.$key; ?>">
                                                                                        View Resume
                                                                                    </div>
                                                                                    <?php 
                                                                                        echo $resume['type'];
                                                                                    ?> 
                                                                                </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="<?php echo $colspan_id.'_'.$key; ?>" class="panel-collapse collapse js-child-coll">
                                                                            <div class="panel-body">
                                                                                <?php 
                                                                                    $resume_url = $resume['resume_url'];
                                                                                    $resume_name = explode(".", $resume_url);
                                                                                    $resume_extension = $resume_name[1];
                                                                                    
                                                                                    if (in_array($resume_extension, ['pdf', 'csv'])) { 
                                                                                        $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $resume_url . '&embedded=true';
                                                                                    } else if (in_array($resume_extension, ['doc', 'docx', 'xlsx', 'xlx'])) {
                                                                                        $iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $resume_url);
                                                                                    } else {
                                                                                        $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $resume_url . '&embedded=true';
                                                                                    }
                                                                                ?>
                                                                                <object style="width:100%; height:80em;" data="<?php echo $iframe_url; ?>"></object>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                        
                            <?php } ?> 
                            <?php if (!empty($applicant_resume_list)) { ?>
                                <div class="accordion-colored-header header-bg-gray">
                                    <div class="panel-group" id="onboarding-configuration-accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                       href="#applicant_resume">
                                                        <span class="glyphicon glyphicon-plus js-main-gly"></span>
                                                        <?php 
                                                            echo 'Applicant Resume';  
                                                        ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="applicant_resume" class="panel-collapse collapse js-main-coll">
                                                <div class="panel-body">
                                                    <?php foreach ($applicant_resume_list as $applicant_key => $applicant_resume) { ?>
                                                        <div class="accordion-colored-header header-bg-gray">
                                                            <div class="panel-group" id="onboarding-configuration-accordions">
                                                                <div class="panel panel-default parent_div">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <a data-toggle="collapse" data-parent="#onboarding-configuration-accordions"
                                                                               href="#<?php echo 'applicant_colspan_'.$applicant_key; ?>" ><span class="glyphicon glyphicon-plus js-child-gly"></span>
                                                                                <div class="btn btn-xs btn-success pull-right" id="<?php echo 'viewc_'.$applicant_key; ?>">
                                                                                    View Resume
                                                                                </div>
                                                                                <?php
                                                                                    echo $applicant_resume['type'];
                                                                                ?>
                                                                            </a>
                                                                        </h4>
                                                                    </div>
                                                                    <div id="<?php echo 'applicant_colspan_'.$applicant_key; ?>" class="panel-collapse collapse js-child-coll">
                                                                        <div class="panel-body">
                                                                            <?php
                                                                                $resume_url = $applicant_resume['resume_url'];
                                                                                $resume_name = explode(".", $resume_url);
                                                                                $resume_extension = $resume_name[1];

                                                                                if (in_array($resume_extension, ['pdf', 'csv'])) {
                                                                                    $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $resume_url . '&embedded=true';
                                                                                } else if (in_array($resume_extension, ['doc', 'docx', 'xlsx', 'xlx'])) {
                                                                                    $iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $resume_url);
                                                                                } else {
                                                                                    $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $resume_url . '&embedded=true';
                                                                                }
                                                                            ?>
                                                                            <object style="width:100%; height:80em;" data="<?php echo $iframe_url; ?>"></object>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            <?php } ?>    
                        <?php } else if (!empty($applicant_resume_list)) { ?> 
                            <div class="accordion-colored-header header-bg-gray">
                                <div class="panel-group" id="onboarding-configuration-accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                   href="#applicant_resume">
                                                    <span class="glyphicon glyphicon-plus js-main-gly"></span>
                                                    <?php 
                                                        echo 'Applicant Resume';  
                                                    ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="applicant_resume" class="panel-collapse collapse js-main-coll">
                                            <div class="panel-body">
                                                <?php foreach ($applicant_resume_list as $applicant_key => $applicant_resume) { ?>
                                                    <div class="accordion-colored-header header-bg-gray">
                                                        <div class="panel-group" id="onboarding-configuration-accordions">
                                                            <div class="panel panel-default parent_div">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a data-toggle="collapse" data-parent="#onboarding-configuration-accordions"
                                                                           href="#<?php echo 'applicant_colspan_'.$applicant_key; ?>" ><span class="glyphicon glyphicon-plus js-child-gly"></span>
                                                                            <div class="btn btn-xs btn-success pull-right" id="<?php echo 'viewc_'.$applicant_key; ?>">
                                                                                View Resume
                                                                            </div>
                                                                            <?php 
                                                                                echo $applicant_resume['type'];
                                                                            ?> 
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="<?php echo 'applicant_colspan_'.$applicant_key; ?>" class="panel-collapse collapse js-child-coll">
                                                                    <div class="panel-body">
                                                                        <?php 
                                                                            $resume_url = $applicant_resume['resume_url'];
                                                                            $resume_name = explode(".", $resume_url);
                                                                            $resume_extension = $resume_name[1];

                                                                            if (in_array($resume_extension, ['pdf', 'csv'])) { 
                                                                                $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $resume_url . '&embedded=true';
                                                                            } else if (in_array($resume_extension, ['doc', 'docx', 'xlsx', 'xlx'])) {
                                                                                $iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $resume_url);
                                                                            } else {
                                                                                $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $resume_url . '&embedded=true';
                                                                            }
                                                                        ?>
                                                                        <object style="width:100%; height:80em;" data="<?php echo $iframe_url; ?>"></object>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        <?php } else { ?>
                            <div id="print_offer_letter" class="hr-box text-center" style="background: #fff; padding: 20px;">
                                <h1 class="section-ttile">No Resume Found!</h1> 
                            </div>          
                        <?php } ?>       
                    </div>
                </div>
                <?php if ($user_type == 'applicant') { ?>
                    <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
                <?php } elseif($user_type == 'employee'){
                    $this->load->view('manage_employer/employee_management/profile_right_menu_employee_new');
                } ?>
            </div>
        </div>
    </div>
</div>

<!-- Main End -->
<script>
    $(document).ready(function () {
        $('#view_0').trigger('click');
        $('.js-main-coll').on('shown.bs.collapse', function (e) {
            e.stopPropagation();
            $(this).parent().find(".js-main-gly").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".js-main-gly").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });

        $('.js-child-coll').on('shown.bs.collapse', function (e) {
            e.stopPropagation();
            $(this).parent().find(".js-child-gly").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".js-child-gly").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });



    $('.confirmation').on('click', function () {
        // var url = $(this).attr('src');
        alertify.confirm(
        'Are you sure?',
        'Are You Sure You Want to Send Resume Request to This Applicant?',
        function () {
            $("#send_resume_request_form").submit();
            // window.location.replace(url);
        },
        function () {
            alertify.error('Cancelled!');
        });
        
    });
</script>