<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="application-header">

                        <article>
                            <figure>
                                <img src="<?php echo AWS_S3_BUCKET_URL;
                                if (isset($applicant_info['pictures']) && $applicant_info['pictures'] != "") {
                                    echo $applicant_info['pictures'];
                                } else {
                                    ?>default_pic-ySWxT.jpg<?php } ?>" alt="Profile Picture">
                            </figure>
                            <div class="text">
                                <h2><?php echo $applicant_info["first_name"]; ?> <?= $applicant_info["last_name"] ?></h2>


                                <div class="start-rating">
                                    <input readonly="readonly"
                                           id="input-21b" <?php if (!empty($applicant_average_rating)) { ?> value="<?php echo $applicant_average_rating; ?>" <?php } ?>
                                           type="number" name="rating" class="rating" min=0 max=5 step=0.2
                                           data-size="xs">
                                </div>
                            </div>
                        </article>
                    </div>

                    <div class="page-header-area margin-top">
                        <span class="page-heading down-arrow">
                            <a class="dashboard-link-btn" href="<?php echo $current_url?>"><i class="fa fa-chevron-left"></i>Applicant Profile</a>

                            <?php echo $subtitle; ?>
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="dashboard-conetnt-wrp">
                                <div class="tagline-heading">
                                    <h4><?php echo ucwords($job_title)?></h4>
                                </div>
                                <form id="resendQueForm" action="" method="post">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100">
                                                <label>Questionnaire:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="questionnaire" id="questionnaire" required>
                                                        <option value="">Select Questionnaire</option>
                                                        <?php foreach($questionnaires as $questionnaire){
                                                            $select = $pre_que_id == $questionnaire['sid'] ? 'selected="selected"' : '';
                                                            if($questionnaire['que_count']>0)
                                                                echo '<option value="'.$questionnaire['sid'].'" '.$select.' >'.$questionnaire['name'].'</option>';
                                                        }?>
                                                    </select>
                                                </div>
                                            </li>
                                            <div class="btn-panel">
                                                <input type="submit" class="submit-btn" value="Send">
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?php echo $current_url; ?>'" />

                                            </div>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript">
    $("#resendQueForm").validate({
        ignore: ":hidden:not(select)",
        rules: {
            questionnaire: {
                required: true
            }
        },
        messages: {
            questionnaire: {
                required: 'Please Select Questionnaire'
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    $(document).ready(function () {
        $('.response_panel').hide();

        $('.question_date_link').on('click', function(){
            $('.question_date_link').each(function(){
                $(this).removeClass('active');
            });

            $(this).addClass('active');
        });
    });
    function show_response(sent_question_sid){
        $('.response_panel').hide();
        $('#sent_question_' + sent_question_sid).show();
    }
</script>


