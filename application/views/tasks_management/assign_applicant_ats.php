<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php echo $title; ?></span>
                    </div>
                    <div class="multistep-progress-form">
                        <form class="msform" action="" method="POST" enctype="multipart/form-data">
                            <?php foreach ($primary_applicants_data as $applicants_data) { ?>
                                    <article class="applicant-box">
                                        <div class="box-head">
                                            <div class="row date-bar">
                                                <div class="col-lg-1 col-md-1 col-xs-1 col-sm-1">
                                                    <label class="control control--checkbox"><input name="ej_check[]" type="checkbox" value="<?php echo $applicants_data["sid"]; ?>" class="ej_checkbox">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                
                                                <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                                <?php   if (empty($applicants_data['resume'])) {
                                                            $resume_direct_link = '';
                                                            $resume_download_link = '';
                                                        } else {
                                                            $resume_direct_link = AWS_S3_BUCKET_URL . $applicants_data['resume'];
                                                            $resume_download_link = AWS_S3_BUCKET_URL . $applicants_data['resume'];
                                                        } 
                                                        
                                                        if (empty($applicants_data['cover_letter'])) {
                                                            $cover_letter_direct_link = '';
                                                            $cover_letter_download_link = '';
                                                        } else {
                                                            $cover_letter_direct_link = AWS_S3_BUCKET_URL . $applicants_data['cover_letter'];
                                                            $cover_letter_download_link = AWS_S3_BUCKET_URL . $applicants_data['cover_letter'];
                                                        }
                                                        ?>
                                                    <a href="javascript:void(0);" onclick="fLaunchModal(this);" class="pull-right aplicant-documents" data-preview-url="<?php echo $resume_direct_link; ?>" data-download-url="<?php echo $resume_download_link; ?>" data-file-name="<?php echo $applicants_data['resume']; ?>" data-document-title="Resume" ><i class="fa fa-file-text-o"></i><span class="btn-tooltip">Resume</span></a>
                                                    <a href="javascript:void(0);" onclick="fLaunchModal(this);" class="pull-right aplicant-documents" data-preview-url="<?php echo $cover_letter_direct_link; ?>" data-download-url="<?php echo $cover_letter_download_link; ?>" data-file-name="<?php echo $applicants_data['cover_letter']; ?>" data-document-title="Cover Letter" ><i class="fa fa-file-text-o"></i><span class="btn-tooltip">Cover Letter</span></a>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="review-score">
                                                        <span class="job-count">
                                                            &nbsp;
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                       
                                        <div class="applicant-info">
                                            <figure>
                                                <?php   if (empty($applicants_data["pictures"])) { ?>
                                                            <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                <?php   } else { ?>
                                                            <img src="<?php echo AWS_S3_BUCKET_URL . $applicants_data["pictures"]; ?>">
                                                <?php   } ?>
                                            </figure>
                                            <div class="text">
                                                <p>
                                                    <?php echo $applicants_data["first_name"] . ' ' . $applicants_data["last_name"]; ?>
                                                </p>
                                                <div class="phone-number">
                                                    <?php   if (isset($applicants_data['phone_number']) && $applicants_data['phone_number'] != '') {
                                                                echo '<a class="theme-color" href="tel:' . $applicants_data['phone_number'] . '"><strong><i class="fa fa-phone"></i> ' . $applicants_data['phone_number'] . '</strong></a>';
                                                            } ?>
                                                </div> 
                                                <span><?php echo $applicants_data["applicant_type"]; ?></span>
                                            </div>
                                        </div>
                                    </article>
                                <?php } ?>
                        </form>
                    </div>
                </div> 
            </div>          
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/chosen.jquery.js"></script>
<script>
    $(document).ready(function () {
        $(".chosen-select").chosen();
    });
</script>