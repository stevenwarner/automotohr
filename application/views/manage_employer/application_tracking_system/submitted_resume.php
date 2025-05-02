<style>
    .HorizontalTab {
        margin-top: 20px;
        display: inline-block;
    }
    .submitted_card_wrapper {
        background: #fff;
        border-radius: 2px;
        min-height: 400px;
        padding: 20px;
    }
    .submitted_card_wrapper p {
        font-size: 12px;
        color: #737373;
        margin: 10px 0;
    }
    .score_wrapper h3, .score_wrapper span { margin: 0px; font-weight: 600; }
    .submitted_card_wrapper h4 { font-size: 16px; font-weight: 600; }
    .score_wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }
    .reasoning ul {
        padding: 0px 20px;
    }
    .reasoning,
    .questions {
        line-height: 1.6;
    }
    .questions span {
        padding: 10px;
        background-color: #eee;
        margin: 10px 0px;
    }

    .skills {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .skills span {
        display: block;
        background-color: #eee;
        padding: 5px 10px;
    }

    .panel-body {
        min-height: max-content;
    }

    .action-btn {
        padding: 4px;
        display: flex;
        align-items: center;
        width: 25px;
        height: 25px;
        justify-content: center;
    }
    .action-btn i {
        margin: 0px;
    }
    .panel-heading {
        display: flex;
        justify-content: space-between;
    }

    textarea, input {
        width: 100%;
        border-radius: 3px;
        border: 1px solid #ddd;
        padding: 2px 5px;
    }

    #edit_questionnaire_body_data i.fa-trash {
        font-size: 16px;
        cursor: pointer;
    }
</style>

<?php
    $skills = json_decode($submitted_resume_data['skills']);
    $education = json_decode($submitted_resume_data['education']);
    $work_experience = json_decode($submitted_resume_data['work_experience']);
    $certifications = json_decode($submitted_resume_data['certifications']);
    $screening_questions = json_decode($submitted_resume_data['screening_questions']);
    $extra_content = $submitted_resume_data['extra_content'];
?>

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

                                <div class="" style="display: flex;justify-content:space-between;width: 100%;align-items: center;">
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
                                    <?php } ?>
                                    <span style="padding: 5px;color:white;text-transform:capitalize;"><?php echo $applicant_job_queue['status']; ?></span>
                                </div>
                                
                            </div>
                        </article>
                    </div>
                    <div id="HorizontalTab" class="HorizontalTab">
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <div class="submitted_card_wrapper hr-widget">
                                    <div class="score_wrapper">
                                        <h3>Applicant Score</h3>
                                        <div style="display: flex;align-items: center;">
                                            <span class="score_range"> <?= $submitted_resume_data['match_score']; ?> / 100</span>
                                            <a href="javascript:;" class="action-btn"
                                            onclick="displayScoring()"
                                            >
                                                <i class="fa fa-pencil"></i>
                                                <span class="btn-tooltip">Edit</span>
                                            </a>
                                        </div>
                                    </div>
                                    <p>Rate the candidate on each criterion below</p>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong> Reason </strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="reasoning">
                                                <?php echo $extra_content; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong> Skills </strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="skills">
                                                <?php foreach($skills as $skill) {
                                                    if(is_array($skill) || is_object($skill))
                                                    {
                                                        foreach($skill as $key => $value) {
                                                        ?>
                                                            <div>
                                                                <span style="text-transform: capitalize;font-weight:600;"> <?php echo $key; ?> </span>: 
                                                                <span> <?php echo $value; ?> </span>
                                                            </div>
                                                        <?php 
                                                        }    
                                                    } else {
                                                        ?>
                                                        <span style="display: block;"> <?php echo ($skill); ?> </span>
                                                        <?php
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-sm-12">
                                <div class="submitted_card_wrapper hr-widget">
                                    
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                                <strong>
                                                    Screening Questions
                                                </strong>

                                                <a href="javascript:;" class="action-btn"
                                                onclick="displayQuestions()"
                                                >
                                                    <i class="fa fa-pencil"></i>
                                                    <span class="btn-tooltip">Edit</span>
                                                </a>
                                        </div>
                                        <div class="panel-body questions">
                                            <?php foreach($screening_questions as $ques) {
                                                if(is_array($ques) || is_object($ques))
                                                {
                                                    foreach($ques as $key => $value) {
                                                    ?>
                                                        <div>
                                                            <span style="text-transform: capitalize;font-weight:600;"> <?php echo $key; ?> </span>: 
                                                            <span> <?php echo $value; ?> </span>
                                                        </div>
                                                    <?php 
                                                    }
                                                } else {
                                                    ?>
                                                    <span style="display: block;"><strong>Q.</strong> &nbsp; <?php echo ($ques); ?> </span>
                                                    <?php
                                                }
                                            } ?>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong> Education </strong>
                                        </div>
                                        <div class="panel-body">
                                            <?php foreach($education as $edu) {
                                                if(is_array($edu) || is_object($edu))
                                                {
                                                    foreach($edu as $key => $value) {
                                                    ?>
                                                        <div>
                                                            <span style="text-transform: capitalize;font-weight:600;"> <?php echo $key; ?> </span>: 
                                                            <span> <?php echo $value; ?> </span>
                                                        </div>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <span style="display: block;"> <?php echo $edu; ?> </span>
                                                    <?php
                                                }
                                            } ?>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong> Certifications </strong>
                                        </div>
                                        <div class="panel-body">
                                            <?php foreach($certifications as $certificate) {
                                                if(is_array($certificate) || is_object($certificate))
                                                {
                                                    foreach($certificate as $key => $value) {
                                                    ?>
                                                        <div>
                                                            <span style="text-transform: capitalize;font-weight:600;"> <?php echo $key; ?> </span>: 
                                                            <span> <?php echo $value; ?> </span>
                                                        </div>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <span style="display: block;"> - <?php echo $certificate; ?> </span>
                                                    <?php
                                                }
                                            } ?>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong> Work Experience </strong>
                                        </div>
                                        <div class="panel-body">
                                            <?php foreach($work_experience as $exp) {
                                                if(is_array($exp) || is_object($exp))
                                                {
                                                    foreach($exp as $key => $value) {
                                                    ?>
                                                        <div>
                                                            <span style="text-transform: capitalize;font-weight:600;"> <?php echo $key; ?> </span>: 
                                                            <span> <?php echo $value; ?> </span>
                                                        </div>
                                                    <?php 
                                                    }
                                                } else {
                                                    ?>
                                                    <span style="display: block;"> <?php echo ($exp); ?> </span>
                                                    <?php
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
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


<div id="edit_questionnaire_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form method="post" >
            <input type="hidden" name="resume_id" value="<?php echo $submitted_resume_data['sid']; ?>" />
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Questions</h4>
                </div>
                <div class="modal-body" id="edit_questionnaire_body_data">
                    <!-- Questions Input List -->
                    <div id="question_wrapper"></div>
                    <button class="btn btn-success" type="button" onclick="addQuestion()" >Add More</button>
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-success"
                        type="submit"
                        id="submit_prompt"
                    >Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="edit_scoring_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form method="post" >
            <input type="hidden" name="resume_id" value="<?php echo $submitted_resume_data['sid']; ?>" />
            <div class="modal-content">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Applicant Scoring</h4>
                </div>
                <div class="modal-body" id="edit_scoring_body_data">
                    <!-- Scoring Input -->
                    <input type="number" class="invoice-fields" name="score" id="score" value="<?= $submitted_resume_data['match_score']; ?>" min="1" max="100" required />
                    <p>Maximum scoring will be 100.</p>
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-success"
                        type="submit"
                        id="submit_prompt"
                    >Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let screening_questions = JSON.parse(`<?php echo $submitted_resume_data['screening_questions'] ?>`);
    const displayQuestions = () => {    
        let question_html_data = ``;
        for(let i = 0; i < screening_questions.length; i++) {
            question_html_data += `<div style="margin-bottom: 10px;display:grid;">
                <div style="display:flex;justify-content: space-between;align-items: center;"> <label>Question:</label> <i class="fa fa-trash"></i> </div>
                <textarea name="questions[]" key="${i}" rows="${3}" >${screening_questions[i]}</textarea>
            </div>`;
        }
        $('#edit_questionnaire_body_data #question_wrapper').html(question_html_data);
        $('#edit_questionnaire_modal').modal('show');

        removeQuestionJS();
    }

    const addQuestion = () => {
        let question_html_data = `<div style="margin-bottom: 10px;display:grid;">
            <div style="display:flex;justify-content: space-between;align-items: center;"> <label>Question:</label> <i class="fa fa-trash"></i> </div>
            <textarea name="questions[]" rows="3" >${''}</textarea>
        </div>`;
        $('#edit_questionnaire_body_data #question_wrapper').append(question_html_data);

        removeQuestionJS();
    }

    const removeQuestionJS = () => {
        $('#edit_questionnaire_body_data .fa-trash').click(function(e) {
            e.target.parentElement.parentElement.remove();
        })
    }

    const displayScoring = () => {
        $('#edit_scoring_modal').modal('show');
    }
</script>