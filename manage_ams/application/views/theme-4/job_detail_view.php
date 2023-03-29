<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
        </div>
    </div>
</div>

<div class="main">
<?php   if((!empty($jobs_detail_page_banner_data) && $jobs_detail_page_banner_data['banner_type'] == 'default') || empty($jobs_detail_page_banner_data)) { ?>
            <div class="bottom-btn-row top-aplly-btn">
                <ul><li><a href="javascript:;" class="site-btn bg-color apply-now-large" data-toggle="modal" data-target="#myModal">apply now</a></li></ul>
            </div>
<?php   } ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="job-detail">
                <?php   if (!empty($job_details['YouTube_Video'])) {
                            $this->load->view('common/video_player_partial');
                        } ?>

                    <div class="job-description-text">
                        <header class="heading-title">
                            <span class="section-title color">Job Description:</span>
                        </header>
                        <?php echo $job_details['JobDescription']; ?>
                    </div>
                    <?php   if (!empty($job_details['JobRequirements'])) { ?>
                                <div class="job-description-text job-requirement">
                                    <header class="heading-title">
                                        <span class="section-title color">Job Requirements:</span>
                                    </header>
                                    <?php echo $job_details['JobRequirements']; ?>
                                </div>
                    <?php   }

                            if (empty($value['pictures']) && !empty($company_details['Logo'])) {
                                $image = AWS_S3_BUCKET_URL . $company_details['Logo'];
                            } elseif(!empty($value['pictures'])){
                                $image = AWS_S3_BUCKET_URL . $value['pictures'];
                            } else {
                                $image = AWS_S3_BUCKET_URL . DEFAULT_JOB_IMAGE;
                            } ?>
                    <div class="social-media job-detail">
                        <?php   if(isset($job_details['share_links'])){
                                    echo $job_details['share_links'];
                                } ?>
                    </div>
                    <div class="bottom-btn-row">
                        <ul>
                            <li><a href="javascript:;" class="site-btn bg-color apply-now-large" data-toggle="modal" data-target="#myModal">apply now</a></li>
                            <li><a href="<?php echo $more_career_oppurtunatity; ?>" class="site-btn bg-color-v3">More Career Opportunities With This Company</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media only screen and (max-width: 600px){
        .job-description-text{
            width: max-width: 100%;
            overflow-x: hidden;
        }
        .job-description-text img{ max-width: 100%; }
        .job-detail li,
        .job-detail p{ font-size: 18px !important; }
        .job-detail .section-title,
        .apply-now-large{ font-size: 25px !important; }

        .job-info li{ font-size: 18px !important; }
        .job-info li span{ font-size: 16px !important; }

    }
</style>