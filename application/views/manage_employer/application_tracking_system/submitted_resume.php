<style>
    .submitted_card_wrapper {
        background: #fff;
        box-shadow: 0px 0px 10px 0px #ddd;
        border-radius: 5px;
        min-height: 400px;
        margin-top: 20px;
        padding: 20px;
    }
    .submitted_card_wrapper p {
        font-size: 12px;
        color: #737373;
        margin: 10px 0;
    }
    .score_wrapper h3 { margin: 0px; }
    .score_wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(".tab_content").hide();
                            $(".tab_content:first").show();

                            $("ul.tabs li").click(function() {
                                $("ul.tabs li").removeClass("active");
                                $(this).addClass("active");
                                $(".tab_content").hide();
                                var activeTab = $(this).attr("rel");
                                $("#" + activeTab).fadeIn();
                            });
                        });
                    </script>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Employee Profile</span>
                        </div>
                    <?php } ?>
                    <div class="application-header">
                        <article>
                            <figure>
                                <img src="<?php if (isset($applicant_info['pictures']) && $applicant_info['pictures'] != '') {
                                                echo AWS_S3_BUCKET_URL . $applicant_info['pictures'];
                                            } else {
                                                echo AWS_S3_BUCKET_URL; ?>default_pic-ySWxT.jpg<?php } ?>" alt="Profile Picture">
                            </figure>
                            <div class="text">
                                <h2><?php echo $applicant_info['first_name']; ?> <?= $applicant_info['last_name'] ?></h2>
                                <div class="start-rating">
                                    <input readonly="readonly" id="input-21b" <?php if (!empty($applicant_average_rating)) { ?> value="<?php echo $applicant_average_rating; ?>" <?php } ?> type="number" name="rating" class="rating" min=0 max=5 step=0.2 data-size="xs">
                                </div>
                                <?php if (check_blue_panel_status() && $applicant_info['is_onboarding'] == 1) { ?>

                                    <?php $send_notification = checkOnboardingNotification($id); ?>
                                    <?php if ($send_notification) { ?>
                                        <span class="badge" style="padding:8px; background-color: green;">On-boarding Request Sent</span>
                                    <?php } else { ?>
                                        <span class="badge" style="padding:8px; background-color: red;">On-boarding Request Pending</span>
                                    <?php } ?>

                                    <span class="badge" style="padding:8px; background-color: blue;"><a href="<?php echo $onboarding_url; ?>" style="color:#fff;" target="_black">Preview On-boarding</a></span>
                                    <?php if (!$send_notification) { ?>
                                        <p class="" style="padding:18px; color: red;">
                                            <strong>
                                                <?php echo onboardingNotificationPendingText($id); ?>
                                            </strong>
                                        </p>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span class="" style="padding:8px;"><?php echo $applicant_info['applicant_type']; ?></span>
                                <?php }  ?>
                            </div>
                        </article>
                    </div>
                    <div id="HorizontalTab" class="HorizontalTab">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <div class="submitted_card_wrapper">
                                    <div class="score_wrapper">
                                        <h3>Applicant Score</h3>
                                        <span class="score_range"> 9 / 10</span>
                                    </div>
                                    <p>Rate the candidate on each criterion below</p>
                                </div>
                            </div>
                            <div class="col-lg-8 col-sm-12">
                                <div class="submitted_card_wrapper"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->
