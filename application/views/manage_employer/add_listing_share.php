<!-- Main Start -->
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="multistep-progress-form">
                                <form class="msform" action="" method="POST" enctype="multipart/form-data">
                                    <!-- progressbar -->
                                    <ul class="progressbar">
                                        <li>create</li>
                                        <li>Details</li>
                                        <li id="advertise_nav">Advertise</li>
                                        <li id="share_nav" class="active">Share</li>
                                    </ul>
                                    <!-- fieldsets -->
                                    <div id="share_div">
                                        <div class="tagline-area item-title">
                                            <h4><em>Job Title</em>:&nbsp;<span style="color:#00a700;"><?php echo $jobDetail['Title']; ?></span></h4>
                                        </div>
                                        <div class="social-media-section">
                                            <div class="produt-block">
                                                <div class="social-media-tagline">
                                                    <h4>
                                                        <p style="color:#00a700;">SHARE THIS JOB ON SOCIAL NETWORKS</p>Share this job publishing on LinkedIn, Twitter, Facebook
                                                    </h4>
                                                </div>
                                                <div class="share-icons">
                                                    <div class="row">
                                                        <!--<div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 share-media-link"><a style = "background-color:#33ccff;" href="https://twitter.com/intent/tweet?text=<?/*= $jobDetail['Title'] */ ?>&url=<?php /*echo STORE_PROTOCOL . $jobDetail['sub_domain']; */ ?>/job_details/<?php /*echo $jobDetail['job_sid']; */ ?>" target="_blank"><i class = "fa fa-twitter"></i>Twitter</a></div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 share-media-link"><span class='st_facebook_custom' displayText='Facebook'></span></div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 share-media-link"><span class='st_linkedin_custom' displayText='LinkedIn'></span></div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 share-media-link"><span class='st_googleplus_custom' displayText='Google +'></div>-->

                                                        <?php echo $share_links; ?>
                                                    </div>



                                                </div>
                                            </div>
                                            <a class="submit-btn" href="<?php echo base_url('add_listing_advertise'); ?>/<?php echo $job_sid; ?>">Back To Advertise</a>
                                            <a class="submit-btn" href="<?php echo base_url('my_listings'); ?>">Go To My Jobs</a>
                                        </div>

                                        <div class="social-media-section">
                                            <div class="produt-block">
                                                <div class="social-media-tagline">
                                                    <h4>
                                                        <p style="color:#00a700;">
                                                            Ask Co-Workers for Referrals
                                                        </p>
                                                        Send an e-mail to co-workers to see if they know a candidate for this Job.
                                                    </h4>
                                                </div>
                                                <div class="universal-form-style-v2">
                                                    <ul>
                                                        <form method="post" id="form_email_job_info_to_users">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="email_job_info_to_users" />
                                                            <li class="form-col-100">
                                                                <label class="pull-left text-left">User(s):<span class="staric">*</span></label>
                                                                <div class="">
                                                                    <select data-placeholder="Please Select" multiple="multiple" name="employees[]" id="employees" class="chosen-select">
                                                                        <?php foreach ($active_users as $user) { ?>
                                                                            <option value="<?php echo $user['sid']; ?>"><?php echo ucwords($user['employee_name']); ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                                <?php echo form_error('employees'); ?>
                                                            </li>
                                                            <button class="submit-btn send-email-to-selected-user" type="submit">Send Email to Selected Users</button>
                                                        </form>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                        <!--<input type = "button" name = "previous"  class = "submit-btn" value = "Previous" />-->


                                        <div class="social-media-section">
                                            <div class="produt-block">
                                                <div class="social-media-tagline">
                                                    <h4>
                                                        <p style="color:#00a700;">
                                                            Share Job via Email Address
                                                        </p>
                                                        Share this job using email address.
                                                    </h4>
                                                </div>
                                                <div class="universal-form-style-v2">
                                                    <ul>
                                                        <form method="post" id="form_email_job">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="email_job" />
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $jobDetail['user_sid']; ?>" />
                                                            <input type="hidden" id="job_sid" name="job_sid" value="<?php echo $jobDetail['sid']; ?>" />
                                                            <li class="form-col-50-left text-left">
                                                                <label for="email_address" class="pull-left text-left">Contact Name <span class="staric">*</span></label>
                                                                <?php echo form_input('full_name', set_value('full_name'), 'class="invoice-fields" data-rule-required="true" data-msg-required="Name is required."'); ?>
                                                                <?php echo form_error('full_name'); ?>
                                                            </li>

                                                            <li class="form-col-50-right text-left">
                                                                <label for="email_address" class="pull-left text-left">Contact Email Address <span class="staric">*</span></label>
                                                                <?php echo form_input('email_address', set_value('email_address'), 'class="invoice-fields" data-rule-email="true" data-msg-email="Please input valid email address" data-rule-required="true" data-msg-required="Email Address is required."'); ?>
                                                                <?php echo form_error('email_address'); ?>
                                                            </li>
                                                            <button class="submit-btn" type="button" onclick="fShareViaEmail();">Send Via Email</button>
                                                        </form>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Main End -->

<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>


<style>
    .st_facebook_custom {
        background: url("<?php echo base_url('assets/images/facebook.png'); ?>") no-repeat scroll left top transparent !important;
        padding: 4px 184px 23px 35px;
    }

    .st_linkedin_custom {
        background: url("<?php echo base_url('assets/images/linkedin.png'); ?>") no-repeat scroll left top transparent !important;
        padding: 4px 184px 23px 35px;
    }

    .st_googleplus_custom {
        background: url("<?php echo base_url('assets/images/google+.png'); ?>") no-repeat scroll left top transparent !important;
        padding: 4px 184px 23px 35px;
    }
</style>

<script>
    $(document).ready(function() {
        $(".chosen-select").chosen();
    });


    function fShareViaEmail() {
        $('#form_email_job').validate();
        if ($('#form_email_job').valid()) {
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to share this job via Email Address?',
                function() {

                    $('#form_email_job').submit();

                },
                function() {
                    //Cancel
                }
            );
        }
    }
</script>