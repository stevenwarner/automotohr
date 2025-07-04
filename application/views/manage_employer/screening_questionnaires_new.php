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
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?= $sub_title ?></span>
                    </div>
                    <div class="user-profile-wraper">
                        <?php   if (!empty($success_message)) { ?>
                                    <div class="success"><p style="color:#000;"><?= $success_message ?></p></div>
                        <?php   } ?>
                        <?php   if (!empty($error_message) || !empty($invalid_email)) { ?>
                                    <div class="error"><p style="color:#000;"><?= $invalid_email ?> <?= $error_message ?></p></div>
                        <?php   } else { ?>  
                                    <div class="question-btn">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <input class="page-heading" onclick="<?= $add_new_function ?>" type="button" name="add_note" value="<?= $new_button_title ?>">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">&nbsp;
<!--                                                <a class="page-heading tutorial-btn" target="_blank" href="<?= base_url() ?>services/questionnaires-tutorial">How To Create Questionnaire?</a>-->
                                            </div>
                                        </div>
                                    </div>
                            <section class="info-wraper">
                                <section class="personal-info" style="width:100%">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <?php if ($main_question) { ?> 
                                                <div id="questionnaire-text-block">
                                                    <div class="questionnaire-text-block">
                                                        <p><strong>Recruiters</strong> HR professionals are often tasked with quickly finding the most qualified candidates. One of the main sources used is looking through job applicants who have applied to a specific job posting, housed in the applicant tracking system.</p>
                                                        <p>Screening questions are often used as part of the online employment application, and can be used to assist in scoring, ranking, or even disqualifying applicants that fail to meet specific job qualifications.</p>
                                                        <p>One common practice is to use basic screening questions.</p>
                                                        <p> Examples might include:</p>
                                                        <p><strong>Questions about whether or not an applicant is authorized to work within a country, and if he/she will require sponsorship of any kind</strong></p>
                                                        <p>
                                                            <em>Education level</em><br>
                                                            <em>Salary history or expectations</em><br>
                                                            <em>Years of experience</em>
                                                        </p>
                                                        <p><strong>Another approach is to get more specific and ask questions related to the job posting. Examples include:</strong></p>
                                                        <p>Whether or not an applicant has a specific certification required</p>
                                                        <p>
                                                            <em>Questions on specific industry experience</em><br>
                                                            <em>Job skill or competency based questions</em>
                                                        </p>
                                                        <p>Screening questions are most effective when time is spent up front designing the questions and in many cases used more for volume recruiting where a requisition receives a higher number of candidates applying, in comparison for a niche role where a level of sourcing expertise is required.</p>
                                                    </div>
                                                </div>
                                                <!--This is main questionnaire section-->
                                                <li id="show_hide" style="display:none;">
                                                    <form name="add_question" action="" method="POST" enctype="multipart/form-data" id="add_question">  
                                                        <span class="notes_area" style="height:auto;">
                                                            <li class="form-col-100">
                                                                <label>Questionnaire Name: <samp style="color:red;">*</samp></label>
                                                                <input class="invoice-fields" type="text" name="name" id="name" value="" required />
                                                            </li>
                                                            <input type="hidden" name="auto_reply_pass" value="0">
                                                            <input type="hidden" name="auto_reply_fail" value="0">
                                                            <li class="form-col-100 autoheight send-email">
                                                                <input id="auto_reply_pass" type="checkbox" value="1" name="auto_reply_pass">
                                                                <label for="auto_reply_pass">Send Auto-Reply email to Passing Candidate</label>
                                                            </li>
                                                            <li class="form-col-100 autoheight">
                                                                <span id="show_email_box_pass" style="display:none;">
                                                                    <div class="col-md-8 col-xs-12">
                                                                        <label>Email text to passing candidates: <samp style="color:red;">*</samp></label>
                                                                        <textarea class="ckeditor" name="email_text_pass" id="email_text_pass" cols="67" rows="6" required></textarea>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-12">
                                                                        <div class="offer-letter-help-widget">
                                                                            <div class="how-it-works-insturction">
                                                                                <strong>How it's Works :</strong>
                                                                                <p class="how-works-attr">1. Add company name</p>
                                                                                <p class="how-works-attr">2. Add applicant name</p>
                                                                                <p class="how-works-attr">3. Add job title</p>
                                                                            </div>

                                                                            <div class="tags-arae">
                                                                                <strong>Tags :</strong> (select tag from below)
                                                                                <ul class="tags">
                                                                                    <li>{{company_name}} </li>
                                                                                    <li>{{applicant_name}}</li>
                                                                                    <li>{{job_title}}</li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </span>
                                                            </li>
                                                            <li class="form-col-100 autoheight send-email">
                                                                <input id="auto_reply_fail" type="checkbox" value="1" name="auto_reply_fail">
                                                                <label for="auto_reply_fail">Send Auto-Reply email to Failed Candidate</label>
                                                            </li>
                                                            <li class="form-col-100 autoheight">
                                                                <span id="show_email_box_fail" style="display:none;">
                                                                    <div class="col-md-8 col-xs-12">
                                                                        <label>Email text to failing candidates: <samp style="color:red;">*</samp></label>
                                                                        <textarea class="ckeditor" name="email_text_fail" id="email_text_fail" cols="67" rows="6" required></textarea>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-12">
                                                                        <div class="offer-letter-help-widget">
                                                                            <div class="how-it-works-insturction">
                                                                                <strong>How it's Works :</strong>
                                                                                <p class="how-works-attr">1. Add company name</p>
                                                                                <p class="how-works-attr">2. Add applicant name</p>
                                                                                <p class="how-works-attr">3. Add job title</p>
                                                                            </div>

                                                                            <div class="tags-arae">
                                                                                <strong>Tags :</strong> (select tag from below)
                                                                                <ul class="tags">
                                                                                    <li>{{company_name}} </li>
                                                                                    <li>{{applicant_name}}</li>
                                                                                    <li>{{job_title}}</li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </span>
                                                            </li>


                                                            <?php if($session['company_detail']['ems_status']){?>
                                                                <li class="form-col-50-left">
                                                                    <label>Questionnaire Type:</label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" name="que_type" id="type">
                                                                            <option value="job">Job</option>
                                                                            <option value="learning_center">Learning Center</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            <?php }?>

                                                            <input type="hidden" name="action" id="action" value="add_question">
                                                            <input type="hidden" name="sid" id="sid" value="">
                                                            <input type="hidden" name="employers_sid" value="<?= $user_sid ?>">
                                                            <li class="form-col-100 autoheight">
                                                                <input class="submit-btn" type="submit" name="add_question_submit" value="Submit">
                                                                <input class="submit-btn btn-cancel" onclick="cancel_add()" type="button" name="cancel" value="Cancel">
                                                            </li>
                                                        </span>
                                                    </form>
                                                </li>
                                                <div id="hide_questions">
                                                    <?php if (empty($questions)) { ?>
                                                        <li><span class="notes_area">No Questionnaire found!</span></li>
                                                    <?php } else { ?>
                                                        <div class="table-responsive table-outer">
                                                            <div class="table-wrp">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="35%">Questionnaire Name</th>
                                                                            <?php if($session['company_detail']['ems_status']){?>
                                                                            <th width="35%">Type</th>
                                                                            <?php }?>
                                                                            <th width="30%" colspan="3" class="last-col">Actions</th>
                                                                        </tr> 
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($questions as $question) {
                                                                            if (($question["type"] == 'job' && !$session['company_detail']['ems_status']) || ($session['company_detail']['ems_status'])) {
                                                                                ?>
                                                                                <tr id="remove_li<?php echo $question["sid"]; ?>">
                                                                                    <td><?php echo $question["name"]; ?></td>
                                                                                    <?php if($session['company_detail']['ems_status']){
                                                                                    ?>
                                                                                        <td><?php echo $question["type"] == 'job' ? 'Job' : 'Learning Center'; ?></td>
                                                                                    <?php }
                                                                                    ?>
                                                                                    <td>
                                                                                        <a href="javascript:;"
                                                                                           class="action-btn"
                                                                                           onclick="modify_question(<?= $question["sid"] ?>)">
                                                                                            <i class="fa fa-pencil"></i>
                                                                                            <span class="btn-tooltip">Edit</span>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a onclick="delete_question(<?= $question["sid"] ?>)"
                                                                                           href="javascript:;"
                                                                                           class="action-btn remove">
                                                                                            <i class="fa fa-remove"></i>
                                                                                            <span class="btn-tooltip">Delete</span>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="<?= base_url() ?>screening_questionnaires?action=questions&id=<?= $question["sid"] ?>"
                                                                                           class="action-btn manage-btn bg-btn">Manage
                                                                                            Question</a>
                                                                                    </td>
                                                                                </tr>
                                                                                <input type="hidden"
                                                                                       name="auto_reply_pass"
                                                                                       id="auto_reply_pass<?= $question["sid"] ?>"
                                                                                       value="<?= $question["auto_reply_pass"] ?>">
                                                                                <input type="hidden"
                                                                                       name="auto_reply_fail"
                                                                                       id="auto_reply_fail<?= $question["sid"] ?>"
                                                                                       value="<?= $question["auto_reply_fail"] ?>">
                                                                                <input type="hidden"
                                                                                       name="email_text_pass"
                                                                                       id="email_text_pass<?= $question["sid"] ?>"
                                                                                       value="<?= htmlentities($question["email_text_pass"]) ?>">
                                                                                <input type="hidden"
                                                                                       name="email_text_fail"
                                                                                       id="email_text_fail<?= $question["sid"] ?>"
                                                                                       value="<?= htmlentities($question["email_text_fail"]) ?>">
                                                                                <input type="hidden" name="name"
                                                                                       id="name<?= $question["sid"] ?>"
                                                                                       value="<?= $question["name"] ?>">
                                                                                <input type="hidden" name="type"
                                                                                       id="type<?= $question["sid"] ?>"
                                                                                       value="<?= $question["type"] ?>">
                                                                            <?php }
                                                                        }?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } else { ?> 
                                                <!--{* This is add question to questionannaire section *}-->
                                                <li id="show_hide_child" style="display:none;">
                                                    <form name="child_question" action="" method="POST" enctype="multipart/form-data">  
                                                        <span class="notes_area" style="height:auto;">
                                                            <li class="form-col-100">
                                                                <label>Question: <samp style="color:red;">*</samp></label>
                                                                <input class="invoice-fields" type="text" name="caption" id="caption" value="" required />
                                                            </li>
                                                            <li class="form-col-100 autoheight send-email">
                                                                <input id="is_required" type="checkbox" value="1" name="is_required">
                                                                <label for="is_required">Is Required</label>
                                                            </li>
                                                            <label>Answer Type: <samp style="color:red;">*</samp></label>
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" value="string" id="string" name="question_type" onclick="string_radio();">
                                                                    <label for="string">Text</label>
                                                                </div>
                                                            </li>
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" value="boolean" id="boolean" name="question_type" onclick="boolean_radio();">
                                                                    <label for="boolean">Yes / No</label>
                                                                </div>
                                                            </li>
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" value="multilist" id="multilist" name="question_type" onclick="multilist_radio();">
                                                                    <label for="multilist">List of answers with multiple choice</label>
                                                                </div>
                                                            </li>
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" value="list" id="list" name="question_type" onclick="list_radio();">
                                                                    <label for="list">List of answers with single choice</label>
                                                                </div>
                                                            </li>
                                                            <!-- hererererererere muzamil-->
                                                            <div id="question_yes_no" style="display:none;">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <label>Yes: <samp style="color:red;">*</samp></label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select class="invoice-fields" name="answer_boolean[]">
                                                                                        <option value="">Select Question Score</option>          
                                                                                        <option value="0">Not acceptable - 0</option>
                                                                                        <option value="1">Acceptable - 1</option>
                                                                                        <option value="2">Good - 2</option>
                                                                                        <option value="3">Very Good - 3</option>
                                                                                        <option value="4">Excellent - 4</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <label>Status: <samp style="color:red;">*</samp></label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select class="invoice-fields" name="status_boolean[]">        
                                                                                        <option value="Pass">Pass</option>
                                                                                        <option value="Fail">Fail</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <label>No: <samp style="color:red;">*</samp></label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select class="invoice-fields" name="answer_boolean[]">
                                                                                        <option value="">Select Question Score</option>          
                                                                                        <option value="0">Not acceptable - 0</option>
                                                                                        <option value="1">Acceptable - 1</option>
                                                                                        <option value="2">Good - 2</option>
                                                                                        <option value="3">Very Good - 3</option>
                                                                                        <option value="4">Excellent - 4</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <label>Status: <samp style="color:red;">*</samp></label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select class="invoice-fields" name="status_boolean[]">        
                                                                                        <option value="Pass">Pass</option>
                                                                                        <option value="Fail">Fail</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            <div id="question_multilist" class="question_multilist" style="display:none;">
                                                                <div class="form-col-100 question-row">
                                                                    <div class="container-fluid">
<!--                                                                        <div class="row">
                                                                            <div class="col-lg-11 col-md-11 col-xs-12 col-sm-11">
                                                                                <div class="row">
                                                                                    <div class="form-col-50-left">
                                                                                        <input class="invoice-fields" type="text" name="multilist_value[]" value="" />
                                                                                    </div>
                                                                                    <div class="form-col-50-right">
                                                                                        <div class="hr-select-dropdown">
                                                                                            <select class="invoice-fields answer-list" name="multilist_score_value[]">
                                                                                                <option value="">Select Question Score</option>          
                                                                                                <option value="0">Not acceptable - 0</option>
                                                                                                <option value="1">Acceptable - 1</option>
                                                                                                <option value="2">Good - 2</option>
                                                                                                <option value="3">Very Good - 3</option>
                                                                                                <option value="4">Excellent - 4</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>-->
                                                                        
                                                                        
                                                                        <div class="row">
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label>Answer Choice</label>
                                                                                    <input class="invoice-fields" type="text" name="multilist_value[]" value="" />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                                <div class="form-group">
                                                                                    <label>Question Score</label>
                                                                                    <div class="hr-select-dropdown">
                                                                                        <select class="invoice-fields answer-list" name="multilist_score_value[]">
                                                                                            <option value="">Select Question Score</option>          
                                                                                            <option value="0">Not acceptable - 0</option>
                                                                                            <option value="1">Acceptable - 1</option>
                                                                                            <option value="2">Good - 2</option>
                                                                                            <option value="3">Very Good - 3</option>
                                                                                            <option value="4">Excellent - 4</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                                <div class="form-group">
                                                                                    <label>Status</label>
                                                                                    <div class="hr-select-dropdown">
                                                                                        <select class="invoice-fields answer-list" name="multilist_status_value[]">
                                                                                            <option value="Pass">Pass</option>
                                                                                            <option value="Fail">Fail</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div id="answerAdd"></div>
                                                                <div class="form-col-100" id="add_answer"><a href="javascript:;" onclick="addAnswerBlock(); return false;" class="add"> + Add Answer</a></div>
                                                            </div>
                                                            <div id="question_singlelist" style="display:none;">
                                                                <div class="form-col-100 question-row">
                                                                    <div class="container-fluid">
<!--                                                                        <div class="row">
                                                                            <div class="col-lg-11 col-md-11 col-xs-12 col-sm-11">
                                                                                <div class="row">
                                                                                    <div class="form-col-50-left">
                                                                                        <input class="invoice-fields" type="text" name="singlelist_value[]" value="" />
                                                                                    </div>
                                                                                    <div class="form-col-50-right">
                                                                                        <div class="hr-select-dropdown">
                                                                                            <select class="invoice-fields answer-list" name="singlelist_score_value[]">
                                                                                                <option value="">Select Question Score</option>          
                                                                                                <option value="0">Not acceptable - 0</option>
                                                                                                <option value="1">Acceptable - 1</option>
                                                                                                <option value="2">Good - 2</option>
                                                                                                <option value="3">Very Good - 3</option>
                                                                                                <option value="4">Excellent - 4</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>-->
                                                                        <div class="row">
                                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label>Answer Choice</label>
                                                                                    <input class="invoice-fields" type="text" name="singlelist_value[]" value="" />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                                <div class="form-group">
                                                                                    <label>Question Score</label>
                                                                                    <div class="hr-select-dropdown">
                                                                                        <select class="invoice-fields answer-list" name="singlelist_score_value[]">
                                                                                            <option value="">Select Question Score</option>          
                                                                                            <option value="0">Not acceptable - 0</option>
                                                                                            <option value="1">Acceptable - 1</option>
                                                                                            <option value="2">Good - 2</option>
                                                                                            <option value="3">Very Good - 3</option>
                                                                                            <option value="4">Excellent - 4</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                                <div class="form-group">
                                                                                    <label>Status</label>
                                                                                    <div class="hr-select-dropdown">
                                                                                        <select class="invoice-fields answer-list" name="singlelist_status_value[]">
                                                                                            <option value="Pass">Pass</option>
                                                                                            <option value="Fail">Fail</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="answerAddsingle"></div>
                                                                <div class="form-col-100" id="add_answersingle"><a href="javascript:;" onclick="addAnswerBlocksingle(); return false;" class="add"> + Add Answer</a></div>
                                                            </div>
                                                            <input type="hidden" name="questionnaire_sid" id="questionnaire_sid" value="<?= $questionnaire_sid ?>">
                                                            <input type="hidden" name="child_action" id="child_action" value="child_question_add">
                                                            <input type="hidden" name="employers_sid" value="<?= $user_sid ?>">
                                                            <div class="btn-panel">
                                                                <input class="submit-btn" type="submit" name="add_child_submit" value="Submit">
                                                                <input class="submit-btn btn-cancel" onclick="cancel_child_add()" type="button" name="cancel" value="Cancel">
                                                            </div>
                                                        </span>
                                                    </form>
                                                </li>
                                                <!-- {* start *} -->
                                                <div id="show_hide_child_edit" class="show_hide_child_edit" style="display:none;">
                                                    <form name="child_question" action="" method="POST" enctype="multipart/form-data">  
                                                        <div class="notes_area" style="height:auto;">
                                                            <li class="form-col-100 autoheight">
                                                                <label>Question: <samp style="color:red;">*</samp></label>
                                                                <input class="invoice-fields" type="text" name="caption" id="caption_edit" value="" required />
                                                            </li>
                                                            <li class="form-col-100 autoheight send-email">
                                                                <input class="subject-fld check-radio" type="checkbox" name="is_required" id="is_required_edit" value="1" />
                                                                <label for="is_required_edit">Is Required</label>
                                                            </li>
                                                            <label>Answer Type: <samp style="color:red;">*</samp></label>
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input class="subject-fld check-radio" type="radio" name="question_type_edit" id="string_edit" value="string" onclick="string_radio_edit(<?= $questionnaire_sid ?>);">
                                                                    <label for="string_edit">Text</label>
                                                                </div>
                                                            </li>
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input class="subject-fld check-radio" type="radio" name="question_type_edit" id="boolean_edit" value="boolean" onclick="boolean_radio_edit(<?= $questionnaire_sid ?>);">
                                                                    <label for="boolean_edit">Yes/No</label>
                                                                </div>
                                                            </li>
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input class="subject-fld check-radio" type="radio" name="question_type_edit" id="multilist_edit" value="multilist" onclick="multilist_radio_edit(<?= $questionnaire_sid ?>);">
                                                                    <label for="multilist_edit">List of answers with multiple choice</label>
                                                                </div>
                                                            </li>
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input class="subject-fld check-radio" type="radio" name="question_type_edit" id="list_edit" value="list" onclick="list_radio_edit(<?= $questionnaire_sid ?>);">
                                                                    <label for="list_edit">List of answers with single choice</label>
                                                                </div>
                                                            </li>
                                                            <div id="question_yes_no_edit" class="form-col-100" style="display:none;">
<!--                                                                <div class="form-col-100 question-row">
                                                                    <label class="form-col-100">Yes: <samp style="color:red;">*</samp></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" id="answer_boolean_edit_yes" name="answer_boolean_edit[]">
                                                                            <option value="">Select Question Score</option>          
                                                                            <option value="0">Not acceptable - 0</option>
                                                                            <option value="1">Acceptable - 1</option>
                                                                            <option value="2">Good - 2</option>
                                                                            <option value="3">Very Good - 3</option>
                                                                            <option value="4">Excellent - 4</option>
                                                                        </select>
                                                                    </div>
                                                                </div>-->
<!--                                                                <div class="form-col-100 question-row">
                                                                    <label class="form-col-100">No: <samp style="color:red;">*</samp></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" id="answer_boolean_edit_no" name="answer_boolean_edit[]">
                                                                            <option value="">Select Question Score</option>          
                                                                            <option value="0">Not acceptable - 0</option>
                                                                            <option value="1">Acceptable - 1</option>
                                                                            <option value="2">Good - 2</option>
                                                                            <option value="3">Very Good - 3</option>
                                                                            <option value="4">Excellent - 4</option>
                                                                        </select>
                                                                    </div>
                                                                </div>-->
                                                                
                                                                
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <label>Yes: <samp style="color:red;">*</samp></label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select class="invoice-fields" id="answer_boolean_edit_yes" name="answer_boolean_edit[]">
                                                                                        <option value="">Select Question Score</option>          
                                                                                        <option value="0">Not acceptable - 0</option>
                                                                                        <option value="1">Acceptable - 1</option>
                                                                                        <option value="2">Good - 2</option>
                                                                                        <option value="3">Very Good - 3</option>
                                                                                        <option value="4">Excellent - 4</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <label>Status: <samp style="color:red;">*</samp></label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select class="invoice-fields" id="status_boolean_edit_yes" name="status_boolean_edit[]">        
                                                                                        <option value="Pass">Pass</option>
                                                                                        <option value="Fail">Fail</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <label>No: <samp style="color:red;">*</samp></label>
                                                                                <div class="hr-select-dropdown">
                                                                                        <select class="invoice-fields" id="answer_boolean_edit_no" name="answer_boolean_edit[]">
                                                                                        <option value="">Select Question Score</option>          
                                                                                        <option value="0">Not acceptable - 0</option>
                                                                                        <option value="1">Acceptable - 1</option>
                                                                                        <option value="2">Good - 2</option>
                                                                                        <option value="3">Very Good - 3</option>
                                                                                        <option value="4">Excellent - 4</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <label>Status: <samp style="color:red;">*</samp></label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select class="invoice-fields" id="status_boolean_edit_no" name="status_boolean_edit[]">        
                                                                                        <option value="Pass">Pass</option>
                                                                                        <option value="Fail">Fail</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                                
                                                                
                                                            </div>
                                                            <div id="question_multilist_edit" class="form-col-100" style="display:none;"></div>
                                                            <div id="question_singlelist_edit" class="form-col-100" style="display:none;"></div>
                                                            <input type="hidden" name="questionnaire_sid" id="questionnaire_sid" value="<?= $questionnaire_sid ?>">
                                                            <input type="hidden" name="child_action" id="child_action" value="child_question_edit">
                                                            <input type="hidden" name="employers_sid" value="<?= $user_sid ?>">
                                                            <input type="hidden" name="child_question_id" id="child_question_id" value="">
                                                            <div class="btn-panel">
                                                                <input class="submit-btn" type="submit" name="edit_child_submit" value="Submit">
                                                                <input class="submit-btn btn-cancel" onclick="cancel_child_edit()" type="button" name="cancel" value="Cancel">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!--{* ==========================================-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- END *}-->
                                                <div id="hide_child_questions" class="form-col-100">
                                                    <?php if (empty($child_questions)) { ?>
                                                        <li>
                                                            <span class="notes_area">No Question found!</span>
                                                        </li>
                                                    <?php } else { ?>
                                                        <div class="table-responsive table-outer">
                                                            <div class="table-wrp">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="50%">Name</th>
                                                                            <th width="10%">Required</th>
                                                                            <th width="30%">Answer Type</th>
                                                                            <th colspan="2" width="10%">Actions</th>
                                                                        </tr> 
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($child_questions as $question) { ?>
                                                                            <tr id="remove_div<?php echo $question["sid"]; ?>">
                                                                                <td><?php echo $question["caption"]; ?></td>
                                                                                <td><?php if ($question["is_required"] == 1) { ?>Yes <?php } else { ?> No <?php } ?></td>
                                                                                <td><?php if ($question["question_type"] == 'string') { ?>Text <?php } ?>
                                                                                    <?php if ($question["question_type"] == 'boolean') { ?>Yes / No<?php } ?>
                                                                                    <?php if ($question["question_type"] == 'list') { ?> List of answers with single choice <?php } ?>
                                                                                    <?php if ($question["question_type"] == 'multilist') { ?> List of answers with multiple choice <?php } ?></td>
                                                                                <td>
                                                                                    <a href="javascript:;" class="action-btn" onclick="modify_child_question(<?= $question["sid"] ?>)">
                                                                                        <i class="fa fa-pencil"></i>
                                                                                        <span class="btn-tooltip">Edit</span>
                                                                                    </a>
                                                                                </td> 
                                                                                <td>
                                                                                    <a onclick="delete_child_question(<?= $question["sid"] ?>)" href="javascript:;" class="action-btn remove clone-job">
                                                                                        <i class="fa fa-remove"></i>
                                                                                        <span class="btn-tooltip">Delete</span>
                                                                                    </a>
                                                                                </td>      
                                                                            </tr>
                                                                        <input type="hidden" id="caption<?= $question["sid"] ?>" value="<?= $question["caption"] ?>">
                                                                        <input type="hidden" id="is_required<?= $question["sid"] ?>" value="<?= $question["is_required"] ?>">
                                                                        <input type="hidden" id="question_type<?= $question["sid"] ?>" value="<?= $question["question_type"] ?>">
                                                                        <?php $array_answers = $individual_questions[$question["sid"]];
                                                                                $i = 0;
                                                                        if (!empty($array_answers)) { // hassan working area
                                                                            foreach ($array_answers as $array_answer) { ?>
                                                                                <input type="hidden" name="<?= $array_answer["question_type"] ?>_edit<?= $question["sid"] ?>_value[]" value="<?= $array_answer["value"] ?>">
                                                                                <input type="hidden" name="<?= $array_answer["question_type"] ?>_edit<?= $question["sid"] ?>_score[]" value="<?= $array_answer["score"] ?>">
                                                                                <input type="hidden" name="<?= $array_answer["question_type"] ?>_edit<?= $question["sid"] ?>_status[]" value="<?= $array_answer["result_status"] ?>">
                                                                                <?php
                                                                                $i++;
                                                                            }
                                                                        } ?>
                                                                <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                <?php } ?>
                                                </div>
                                                <div class="btn-panel"> 
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <a class="page-heading" href="<?php echo base_url('screening_questionnaires') ?>">Back To Questionnaire</a>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php } ?>
                                        </ul>
                                    </div>
                                </section>
                            </section>
                    <?php } ?>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>
<script type="text/javascript">
    function add_new() {
        document.getElementById("name").value = '';
        document.getElementById("sid").value = '';
        $('#auto_reply_pass').prop('checked', false);
        $('#auto_reply_fail').prop('checked', false);
        CKEDITOR.instances.email_text_pass.setData('');
        CKEDITOR.instances.email_text_fail.setData('');
        $('#show_email_box_pass').hide();
        $('#show_email_box_fail').hide();
        document.getElementById("action").value = 'add_question';
        $('#show_hide').show();
        $('#hide_questions').hide();
        $('#questionnaire-text-block').hide();
    }

    function cancel_add() {
        $('#hide_questions').show();
        $('#show_hide').hide();
        $('#questionnaire-text-block').show();
    }

    function cancel_child_add() {
        $('#show_hide_child').hide();
        $('#hide_child_questions').show();
    }

    function cancel_child_edit() {
        $('#show_hide_child_edit').hide();
        $('#hide_child_questions').show();
    }

    function modify_question(val) {
        var auto_reply_pass = document.getElementById('auto_reply_pass' + val).value;
        var auto_reply_fail = document.getElementById('auto_reply_fail' + val).value;
        var email_text_pass = document.getElementById('email_text_pass' + val).value;
        var email_text_fail = document.getElementById('email_text_fail' + val).value;
        var type = document.getElementById('type' + val).value;
        var name = document.getElementById('name' + val).value;
        var ems_status = <?php echo $session['company_detail']['ems_status']?>;
        document.getElementById("name").value = name;
        document.getElementById("sid").value = val;
        if(ems_status){
            document.getElementById("type").value = type;
        }
        document.getElementById("action").value = 'modify_question';
        CKEDITOR.instances.email_text_pass.setData(email_text_pass);
        CKEDITOR.instances.email_text_fail.setData(email_text_fail);
        if (auto_reply_pass == 1) {
            $('#auto_reply_pass').prop('checked', true);
            $('#show_email_box_pass').show();
            CKEDITOR.instances.email_text_pass.setData(email_text_pass);
        }
        if (auto_reply_fail == 1) {
            $('#auto_reply_fail').prop('checked', true);
            $('#show_email_box_fail').show();
            CKEDITOR.instances.email_text_fail.setData(email_text_fail);
        }

        $('#show_hide').show();
        $('#hide_questions').hide();
        $('#questionnaire-text-block').hide();
    }

    function delete_question(val) {
        alertify.defaults.glossary.title = 'Delete Questionnaire';
        alertify.confirm("Are you sure you want to delete the Questionnaire?",
                function () {
                    $.ajax({
                        url: "<?= base_url() ?>screening_questionnaires?action=remove_questionnaire&sid=" + val,
                        success: function (data) {
                            console.log(data);
                        }
                    });
                    $('#remove_li' + val).hide();
                    alertify.success('Questionnaire deleted successfully.');
                });
    }

    function delete_child_question(val) {
        alertify.defaults.glossary.title = 'Delete Question';
        alertify.confirm("Are you sure you want to delete the Question?",
                function () {
                    $.ajax({
                        url: "<?= base_url() ?>screening_questionnaires?action=remove_child_question&sid=" + val,
                        success: function (data) {
                            console.log(data);
                        }
                    });
                    $('#remove_div' + val).hide();
                    alertify.success('Question deleted successfully.');
                });
    }

    function add_child_question() {
        document.getElementById("caption").value = '';
        $('#is_required').prop('checked', false);
        $('#string').prop('checked', false);
        $('#boolean').prop('checked', false);
        $('#multilist').prop('checked', false);
        $('#list').prop('checked', false);
        $('#show_hide_child').show();
        $('#hide_child_questions').hide();
    }

    function string_radio() {
        $('#question_yes_no').hide();
        $('#question_multilist').hide();
        $('#question_singlelist').hide();
    }

    function boolean_radio() {
        $('#question_yes_no').show();
        $('#question_multilist').hide();
        $('#question_singlelist').hide();
    }

    function multilist_radio() {
        $('#question_yes_no').hide();
        $('#question_multilist').show();
        $('#question_singlelist').hide();
    }

    function list_radio() {
        $('#question_yes_no').hide();
        $('#question_multilist').hide();
        $('#question_singlelist').show();
    }


    $("#auto_reply_pass").bind("click", function () {
        if (this.checked) {
            $('#show_email_box_pass').show();
        } else {
            $('#show_email_box_pass').hide();
        }
    });
    $("#auto_reply_fail").bind("click", function () {
        if (this.checked) {
            $('#show_email_box_fail').show();
        } else {
            $('#show_email_box_fail').hide();
        }
    });
    $(function () {
        $('input[name="add_question_submit"]').click(function () {
            if ($('#name').val() == '') {
                alertify.alert("Please provide Questionnaire Name");
                return false;
            }

            if (document.getElementById('auto_reply_pass').checked) {
                alertify.defaults.glossary.title = 'Screening Questionnaires Module';
                var text_pass = $.trim(CKEDITOR.instances.email_text_pass.getData());
                if (text_pass.length === 0) {
                    alertify.alert("Please provide E-Mail text for Passing Candidate");
                    return false;
                }
            }

            if (document.getElementById('auto_reply_fail').checked) {
                alertify.defaults.glossary.title = 'Screening Questionnaires Module';
                var text_fail = $.trim(CKEDITOR.instances.email_text_fail.getData());
                if (text_fail.length === 0) {
                    alertify.alert("Please provide E-Mail text for Failed Candidate");
                    return false;
                }
            }
        });
        setTimeout(function () {
            $(".success").slideUp();
        }, 5000);
        $('input[name="add_child_submit"]').click(function () {
            alertify.defaults.glossary.title = 'Screening Questionnaires Module';
            if ($('#caption').val() == '') {
                alertify.alert("Please provide Question");
                return false;
            }

            if (!$("input[name='question_type']:checked").val()) {
                alertify.alert("Please select 'Answer Type'");
                return false;
            }

            var question_type = $("input[name='question_type']:checked").val();
            if (question_type == 'boolean') {
                var inps = document.getElementsByName('answer_boolean[]');
                for (var i = 0; i < inps.length; i++) {
                    var inp = inps[i].value;
                    if (inp == '') {
                        if (i == 0) {
                            alertify.alert("Please provide 'Passing score' for choice 'Yes'");
                        } else {
                            alertify.alert("Please provide 'Passing score' for choice 'No'");
                        }
                        return false;
                        break;
                    }
                }
            }

            if (question_type == 'list') {
                var answer_single = document.getElementsByName('singlelist_value[]');
                var score_single = document.getElementsByName('singlelist_score_value[]');
                for (var i = 0; i < answer_single.length; i++) {
                    var singlelist_value = answer_single[i].value;
                    var singlelist_score_value = score_single[i].value;
                    if (singlelist_value == '') {
                        alertify.alert("Missing 'Answer' for 'List of answers with single choice'");
                        return false;
                        break;
                    }
                    if (singlelist_score_value == '') {
                        alertify.alert("Missing 'Score' for 'List of answers with single choice'");
                        return false;
                        break;
                    }
                }
            }

            if (question_type == 'multilist') {
                var answer_multi = document.getElementsByName('multilist_value[]');
                var score_multi = document.getElementsByName('multilist_score_value[]');
                for (var i = 0; i < answer_multi.length; i++) {
                    var multilist_value = answer_multi[i].value;
                    var multilist_score_value = score_multi[i].value;
                    if (multilist_value == '') {
                        alertify.alert("Missing 'Answer' for 'List Of Answers With Multiple Choice'");
                        return false;
                        break;
                    }
                    if (multilist_score_value == '') {
                        alertify.alert("Missing 'Score' for 'List Of Answers With Multiple Choice'");
                        return false;
                        break;
                    }
                }
            }
        });
        setTimeout(function () {
            $(".success").slideUp();
        }, 5000);
    });
    var i = 1;
    function addAnswerBlock() {
        //console.log('hassan work area');
        var id = "answerAdd" + i;
        $("<div id='" + id + "'><\/div>").appendTo("#answerAdd");
        //$('#' + id).html($('#' + id).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' name='multilist_value[]' value='' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select class='invoice-fields answer-list' name='multilist_score_value[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row'><a href='javascript:;' onclick=\"deleteAnswerBlock('" + id + "'); return false;\" class=\"remove\">Delete<\/a></div></div></div></div><\/div>");
        $('#' + id).html($('#' + id).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><div class='form-group'><label>Answer Choice</label><input class='invoice-fields' type='text' name='multilist_value[]' value='' /></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Question Score</label><div class='hr-select-dropdown'><select class='invoice-fields answer-list' name='multilist_score_value[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div><div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'><div class='form-group'><label>Status</label><div class='hr-select-dropdown'><select class='invoice-fields answer-list' name='multilist_status_value[]'><option value='Pass'>Pass</option><option value='Fail'>Fail</option></select></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new'><a href='javascript:;' onclick=\"deleteAnswerBlock('" + id + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div></div></div><\/div>");
        i++;
    }


    function deleteAnswerBlock(id) {
        $('#' + id).remove();
    }

    var j = 1;
    function addAnswerBlocksingle() {
        var idj = "answerAddsingle" + j;
        $("<div id='" + idj + "'><\/div>").appendTo("#answerAddsingle");
        //$('#' + idj).html($('#' + idj).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' name='singlelist_value[]' value='' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select class='invoice-fields' name='singlelist_score_value[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row'><a href='javascript:;' onclick=\"deleteAnswerBlocksingle('" + idj + "'); return false;\" class=\"remove\">Delete<\/a></div></div></div></div><\/div>");
        $('#' + idj).html($('#' + idj).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><div class='form-group'><label>Answer Choice</label><input class='invoice-fields' type='text' name='singlelist_value[]' value='' /></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Question Score</label><div class='hr-select-dropdown'><select class='invoice-fields answer-list' name='singlelist_score_value[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div><div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'><div class='form-group'><label>Status</label><div class='hr-select-dropdown'><select class='invoice-fields answer-list' name='singlelist_status_value[]'><option value='Pass'>Pass</option><option value='Fail'>Fail</option></select></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new'><a href='javascript:;' onclick=\"deleteAnswerBlocksingle('" + idj + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div></div></div><\/div>");
        j++;
    }

    function deleteAnswerBlocksingle(idj) {
        $('#' + idj).remove();
    }

    function modify_child_question(val) {
        var caption = document.getElementById('caption' + val).value;
        document.getElementById("caption_edit").value = caption;
        var is_required = document.getElementById('is_required' + val).value;
        
        if (is_required == 1) {
            $('#is_required_edit').prop('checked', true);
        }
        
        var question_type = document.getElementById('question_type' + val).value;
        document.getElementById("child_question_id").value = val;
        var edit_question_type = question_type + '_edit';
        
        $('#' + edit_question_type).prop('checked', true);
        $('#show_hide_child_edit').show();
        $('#hide_child_questions').hide();
        $('#show_hide_child').hide();
        
        if (edit_question_type == 'boolean_edit') {
            $('#question_yes_no_edit').show();
            $('#question_multilist_edit').hide();
            $('#question_singlelist_edit').hide();
            
            var boolean_edit_value = document.getElementsByName('boolean_edit' + val + '_value[]');
            var boolean_edit_score = document.getElementsByName('boolean_edit' + val + '_score[]');
            var boolean_edit_status = document.getElementsByName('boolean_edit' + val + '_status[]');
            
            for (var i = 0; i < boolean_edit_value.length; i++) {
                var boolen_value = boolean_edit_value[i].value;
                var boolen_score = boolean_edit_score[i].value;
                var boolen_status = boolean_edit_status[i].value;
                
                if (boolen_value == 'Yes') {
                    document.getElementById("answer_boolean_edit_yes").value = boolen_score;
                    document.getElementById("status_boolean_edit_yes").value = boolen_status;
                }
                
                if (boolen_value == 'No') {
                    document.getElementById("answer_boolean_edit_no").value = boolen_score;
                    document.getElementById("status_boolean_edit_no").value = boolen_status;
                }
            }
        }

        if (edit_question_type == 'multilist_edit') {
            $('#question_yes_no_edit').hide();
            $('#question_multilist_edit').empty();
            //$('#question_multilist_edit').html('<div class="form-col-100 question-row"><div class="container-fluid"><div class="row"><div class="col-lg-11 col-md-11 col-xs-12 col-sm-11"><div class="row"><div class="form-col-50-left"><input class="invoice-fields" type="text" id="prefill_multilist_value" name="multilist_value_edit[]" value="" /></div><div class="form-col-50-right"><div class="hr-select-dropdown"><select class="invoice-fields answer-list" id="prefill_multilist_score" name="multilist_score_value_edit[]"><option value="">Select Question Score</option><option value="0">Not acceptable - 0</option><option value="1">Acceptable - 1</option><option value="2">Good - 2</option><option value="3">Very Good - 3</option><option value="4">Excellent - 4</option></select></div></div></div></div></div></div></div><div id="answerAdd_edit" class="form-col-100"></div><div id="add_answer_edit" class="form-col-100 question-row"><a href="javascript:;" onclick="addAnswerBlock_edit(); return false;" class="add"> + Add Answer</a></div>');
            $('#question_multilist_edit').html('<div class="form-col-100 question-row"><div class="container-fluid"><div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><label>Answer Choice</label><input class="invoice-fields" type="text" id="prefill_multilist_value" name="multilist_value_edit[]" value="" /></div><div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><label>Question Score</label><div class="hr-select-dropdown"><select class="invoice-fields answer-list" id="prefill_multilist_score" name="multilist_score_value_edit[]"><option value="">Select Question Score</option><option value="0">Not acceptable - 0</option><option value="1">Acceptable - 1</option><option value="2">Good - 2</option><option value="3">Very Good - 3</option><option value="4">Excellent - 4</option></select></div></div><div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><label>Question Score</label><div class="hr-select-dropdown"><select class="invoice-fields answer-list" id="prefill_multilist_status" name="multilist_status_value_edit[]"><option value="Pass">Pass</option><option value="Fail">Fail</option></select></div></div></div></div></div><div id="answerAdd_edit" class="form-col-100"></div><div id="add_answer_edit" class="form-col-100 question-row"><a href="javascript:;" onclick="addAnswerBlock_edit(); return false;" class="add"> + Add Answer</a></div>');
            //hererere fourth
            $('#question_multilist_edit').show();
            $('#question_singlelist_edit').hide();
            
            var multilist_edit_value = document.getElementsByName('multilist_edit' + val + '_value[]');
            var multilist_edit_score = document.getElementsByName('multilist_edit' + val + '_score[]');
            var multilist_edit_status = document.getElementsByName('multilist_edit' + val + '_status[]');
            
            for (var i = 0; i < multilist_edit_value.length; i++) {
                var multilist_value = multilist_edit_value[i].value;
                var multilist_score = multilist_edit_score[i].value;
                var multilist_status = multilist_edit_status[i].value;
                
                if (i == 0) {
                    document.getElementById("prefill_multilist_value").value = multilist_value;
                    document.getElementById("prefill_multilist_score").value = multilist_score;
                    document.getElementById("prefill_multilist_status").value = multilist_status;
                } else {
                    var id = "answerBlock" + i;
                    var score_id = "edit_prefill_multilist_score" + i;
                    var status_id = "edit_prefill_multilist_status" + i;
                    
                    $('#' + id).remove();
                    $("<div id='" + id + "'><\/div>").appendTo("#answerAdd_edit");
                    //$('#' + id).html($('#' + id).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' name='multilist_value_edit[]' value='" + multilist_value + "' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select id='" + score_id + "' class='invoice-fields' name='multilist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row'><a href='javascript:;' onclick=\"deleteAnswerBlock_edit('" + id + "'); return false;\" class=\"remove\">Delete<\/a></div></div></div></div><\/div>");
                    $('#' + id).html($('#' + id).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><label>Answer Choice</label><input class='invoice-fields' type='text' name='multilist_value_edit[]' value='" + multilist_value + "' /></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><label>Question Score</label><div class='hr-select-dropdown'><select id='" + score_id + "' class='invoice-fields' name='multilist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div><div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'><label>Status</label><div class='hr-select-dropdown'><select id='" + status_id + "' class='invoice-fields' name='multilist_status_value_edit[]'><option value='Pass'>Pass</option><option value='Fail'>Fail</option></select></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new'><a href='javascript:;' onclick=\"deleteAnswerBlock_edit('" + id + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div></div><\/div>");
                    // fifth
                    document.getElementById(score_id).value = multilist_score;
                    document.getElementById(status_id).value = multilist_status;
                }
            }
        }

        if (edit_question_type == 'list_edit') {
            $('#question_yes_no_edit').hide();
            $('#question_multilist_edit').hide();
            $('#question_singlelist_edit').empty();
            //$('#question_singlelist_edit').html('<div class="form-col-100 question-row"><div class="container-fluid"><div class="row"><div class="col-lg-11 col-md-11 col-xs-12 col-sm-11"><div class="row"><div class="form-col-50-left"><input class="invoice-fields" type="text" id="prefill_list_value" name="singlelist_value_edit[]" value="" /></div><div class="form-col-50-right"><div class="hr-select-dropdown"><select class="invoice-fields" id="prefill_list_score" name="singlelist_score_value_edit[]"><option value="">Select Question Score</option><option value="0">Not acceptable - 0</option><option value="1">Acceptable - 1</option><option value="2">Good - 2</option><option value="3">Very Good - 3</option><option value="4">Excellent - 4</option></select></div></div></div></div></div></div></div><div id="answerAddsingle_edit"></div><div id="add_answersingle_edit"><a href="javascript:;" onclick="addAnswerBlocksingle_edit(); return false;" class="add"> + Add Answer</a></div>');
            $('#question_singlelist_edit').html('<div class="form-col-100 question-row"><div class="container-fluid"><div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><div class="form-group"><label>Answer Choice</label><input class="invoice-fields" type="text" id="prefill_list_value" name="singlelist_value_edit[]" value="" /></div></div><div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><div class="form-group"><label>Question Score</label><div class="hr-select-dropdown"><select class="invoice-fields answer-list" id="prefill_list_score" name="singlelist_score_value_edit[]"><option value="">Select Question Score</option><option value="0">Not acceptable - 0</option><option value="1">Acceptable - 1</option><option value="2">Good - 2</option><option value="3">Very Good - 3</option><option value="4">Excellent - 4</option></select></div></div></div><div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><div class="form-group"><label>Status</label><div class="hr-select-dropdown"><select class="invoice-fields answer-list" id="prefill_list_status" name="singlelist_status_value_edit[]"><option value="Pass">Pass</option><option value="Fail">Fail</option></select></div></div></div></div></div></div><div id="answerAddsingle_edit"></div><div id="add_answersingle_edit"><a href="javascript:;" onclick="addAnswerBlocksingle_edit(); return false;" class="add"> + Add Answer</a></div>');
    //herere first
            $('#question_singlelist_edit').show();
            //console.log('edit_question_type: 854');
            var list_edit_value = document.getElementsByName('list_edit' + val + '_value[]');
            var list_edit_score = document.getElementsByName('list_edit' + val + '_score[]');
            var list_edit_status = document.getElementsByName('list_edit' + val + '_status[]');
            //console.log('list_edit_value: '+list_edit_value);
            for (var i = 0; i < list_edit_value.length; i++) {
                var list_value = list_edit_value[i].value;
                var list_score = list_edit_score[i].value;
                var list_status = list_edit_status[i].value;
                
                if (i == 0) {
                    document.getElementById("prefill_list_value").value = list_value;
                    document.getElementById("prefill_list_score").value = list_score;
                    document.getElementById("prefill_list_status").value = list_status;
                } else {
                    var idj = "answerSingleBlock" + i;
                    var score_idj = "edit_prefill_list_score" + i;
                    var status_idj = "edit_prefill_list_status" + i;
                    
                    $('#' + id).remove();
                    $("<div id='" + idj + "'><\/div>").appendTo("#answerAddsingle_edit");
                    //$('#' + idj).html($('#' + idj).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' name='singlelist_value_edit[]' value='" + list_value + "' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select id='" + score_idj + "' class='invoice-fields' name='singlelist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row'><a href='javascript:;' onclick=\"deleteAnswerBlock_edit('" + idj + "'); return false;\" class=\"remove\">Delete<\/a></div></div></div></div><\/div>");
                    $('#' + idj).html($('#' + idj).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><div class='form-group'><label>Answer Choice</label><input class='invoice-fields' type='text' name='singlelist_value_edit[]' value='" + list_value + "' /></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Question Score</label><div class='hr-select-dropdown'><select id='" + score_idj + "' class='invoice-fields' name='singlelist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div><div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'><div class='form-group'><label>Status</label><div class='hr-select-dropdown'><select id='" + status_idj + "' class='invoice-fields answer-list' name='singlelist_status_value_edit[]'><option value='Pass'>Pass</option><option value='Fail'>Fail</option></select></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new'><a href='javascript:;' onclick=\"deleteAnswerBlock_edit('" + idj + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div></div><\/div>");
                    //hererereerere second
                    document.getElementById(score_idj).value = list_score;
                    document.getElementById(status_idj).value = list_status;
                }
            }
        }

        if (edit_question_type == 'string_edit') {
            $('#question_yes_no_edit').hide();
            $('#question_multilist_edit').hide();
            $('#question_singlelist_edit').hide();
        }
    }

    function string_radio_edit(val) {
        $('#question_yes_no_edit').hide();
        $('#question_multilist_edit').hide();
        $('#question_singlelist_edit').hide();
    }

    function boolean_radio_edit(val) {
        $('#question_yes_no_edit').show();
        $('#question_multilist_edit').hide();
        $('#question_singlelist_edit').hide();
    }

    function multilist_radio_edit(val) {
        $('#question_multilist_edit').empty();
        //$('#question_multilist_edit').html('<div class="form-col-100 question-row"><div class="container-fluid"><div class="row"><div class="col-lg-11 col-md-11 col-xs-12 col-sm-11"><div class="row"><div class="form-col-50-left"><input class="invoice-fields" type="text" id="prefill_multilist_value" name="multilist_value_edit[]" value="" /></div><div class="form-col-50-right"><div class="hr-select-dropdown"><select class="invoice-fields answer-list" id="prefill_multilist_score" name="multilist_score_value_edit[]"><option value="">Select Question Score</option><option value="0">Not acceptable - 0</option><option value="1">Acceptable - 1</option><option value="2">Good - 2</option><option value="3">Very Good - 3</option><option value="4">Excellent - 4</option></select></div></div></div></div></div></div></div><div id="answerAdd_edit" class="form-col-100"></div><div id="add_answer_edit" class="form-col-100 question-row"><a href="javascript:;" onclick="addAnswerBlock_edit(); return false;" class="add"> + Add Answer</a></div>');
        $('#question_multilist_edit').html('<div class="form-col-100 question-row"><div class="container-fluid"><div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><div class="form-group"><label>Answer Choice</label><input class="invoice-fields" type="text" id="prefill_multilist_value" name="multilist_value_edit[]" value="" /></div></div><div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><div class="form-group"><label>Passing Score</label><div class="hr-select-dropdown"><select class="invoice-fields answer-list" id="prefill_multilist_score" name="multilist_score_value_edit[]"><option value="">Select Question Score</option><option value="0">Not acceptable - 0</option><option value="1">Acceptable - 1</option><option value="2">Good - 2</option><option value="3">Very Good - 3</option><option value="4">Excellent - 4</option></select></div></div></div><div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"><div class="form-group"><label>Status</label><div class="hr-select-dropdown"><select class="invoice-fields answer-list" id="prefill_multilist_status" name="multilist_status_value_edit[]"><option value="Pass">Pass</option><option value="Fail">Fail</option></select></div></div></div></div></div></div><div id="answerAdd_edit" class="form-col-100"></div><div id="add_answer_edit" class="form-col-100 question-row"><a href="javascript:;" onclick="addAnswerBlock_edit(); return false;" class="add"> + Add Answer</a></div>');
        // seventh
        $('#question_multilist_edit').show();
        $('#question_yes_no_edit').hide();
        $('#question_singlelist_edit').hide();
        document.getElementById('question_multilist_edit').innerHTML = "";
        //$('#question_multilist_edit').html("<div class='form-col-100 question-row' id='answerAdd_edit'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' id='prefill_multilist_value' name='multilist_value_edit[]' value='' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select class='invoice-fields' id='prefill_multilist_score' name='multilist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div></div></div></div><\/div><div class='form-col-100 question-row' id='add_answer_edit'><a href='javascript:;' onclick='addAnswerBlock_edit(); return false;' class='add'> + Add Answer</a></div>");
        $('#question_multilist_edit').html("<div class='form-col-100 question-row' id='answerAdd_edit'><div class='container-fluid'><div class='row'><div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><div class='form-group'><label>Answer Choice</label><input class='invoice-fields' type='text' id='prefill_multilist_value' name='multilist_value_edit[]' value='' /></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Answer Choice</label><div class='hr-select-dropdown'><select class='invoice-fields' id='prefill_multilist_score' name='multilist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Status</label><div class='hr-select-dropdown'><select class='invoice-fields answer-list' id='prefill_multilist_status' name='multilist_status_value_edit[]'><option value='Pass'>Pass</option><option value='Fail'>Fail</option></select></div></div></div></div></div><\/div><div class='form-col-100 question-row' id='add_answer_edit'><a href='javascript:;' onclick='addAnswerBlock_edit(); return false;' class='add'> + Add Answer</a></div>");
        // eigth
        var edit_question_id = document.getElementById('child_question_id').value;
        var multilist_edit_value = document.getElementsByName('multilist_edit' + edit_question_id + '_value[]');
        var multilist_edit_score = document.getElementsByName('multilist_edit' + edit_question_id + '_score[]');
        var multilist_edit_status = document.getElementsByName('multilist_edit' + edit_question_id + '_status[]');
        
        for (var i = 0; i < multilist_edit_value.length; i++) {
            var multilist_value = multilist_edit_value[i].value;
            var multilist_score = multilist_edit_score[i].value;
            var multilist_status = multilist_edit_status[i].value;
            
            if (i == 0) {
                document.getElementById("prefill_multilist_value").value = multilist_value;
                document.getElementById("prefill_multilist_score").value = multilist_score;
                document.getElementById("prefill_multilist_status").value = multilist_status;
            } else {
                var id = "answerBlock" + i;
                var score_id = "edit_prefill_multilist_score" + i;
                var status_id = "edit_prefill_multilist_status" + i;
                $("<div id='" + id + "'><\/div>").appendTo("#answerAdd_edit");
                //$('#' + id).html($('#' + id).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' name='multilist_value_edit[]' value='" + multilist_value + "' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select id='" + score_id + "' class='invoice-fields' name='multilist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row'><a href='javascript:;' onclick=\"deleteAnswerBlock_edit('" + id + "'); return false;\" class=\"remove\">Delete<\/a></div></div></div></div><\/div>");
                //$('#' + id).html($('#' + id).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' name='multilist_value_edit[]' value='" + multilist_value + "' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select id='" + score_id + "' class='invoice-fields' name='multilist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row'><a href='javascript:;' onclick=\"deleteAnswerBlock_edit('" + id + "'); return false;\" class=\"remove\">Delete<\/a></div></div></div></div><\/div>");
                $('#' + id).html($('#' + id).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><div class='form-group'><label>Answer Choice</label><input class='invoice-fields' type='text' name='multilist_value_edit[]' value='" + multilist_value + "' /></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Answer Choice</label><div class='hr-select-dropdown'><select id='" + score_id + "' class='invoice-fields' name='multilist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div><div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'><div class='form-group'><label>Status</label><div class='hr-select-dropdown'><select id='" + status_id + "' class='invoice-fields answer-list' name='multilist_status_value_edit[]'><option value='Pass'>Pass</option><option value='Fail'>Fail</option></select></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new'><a href='javascript:;' onclick=\"deleteAnswerBlock_edit('" + id + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div></div><\/div>");
                //ninth
                document.getElementById(score_id).value = multilist_score;
                document.getElementById(status_id).value = multilist_status;
            }
        }
    }

    function list_radio_edit(val) {
        $('#question_singlelist_edit').empty();
        $('#question_singlelist_edit').html('<div class="form-col-100 question-row"><div class="container-fluid"><div class="row"><div class="col-lg-11 col-md-11 col-xs-12 col-sm-11"><div class="row"><div class="form-col-50-left"><input class="invoice-fields" type="text" id="prefill_list_value" name="singlelist_value_edit[]" value="" /></div><div class="form-col-50-right"><div class="hr-select-dropdown"><select class="invoice-fields" id="prefill_list_score" name="singlelist_score_value_edit[]"><option value="">Select Passing Score</option><option value="0">Not acceptable - 0</option><option value="1">Acceptable - 1</option><option value="2">Good - 2</option><option value="3">Very Good - 3</option><option value="4">Excellent - 4</option></select></div></div></div></div></div></div></div><div id="answerAddsingle_edit"></div><div id="add_answersingle_edit"><a href="javascript:;" onclick="addAnswerBlocksingle_edit(); return false;" class="add"> + Add Answer</a></div>');
        $('#question_singlelist_edit').show();
        //console.log('list_radio_edit: 925');
        $('#question_yes_no_edit').hide();
        $('#question_multilist_edit').hide();
        document.getElementById('question_singlelist_edit').innerHTML = "";
        //$('#question_singlelist_edit').html("<div class='form-col-100 question-row' id='answerAddsingle_edit'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' id='prefill_list_value' name='singlelist_value_edit[]' value='' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select class='invoice-fields' id='prefill_list_score' name='singlelist_score_value_edit[]'><option value=''>Select Passing Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div></div></div></div><\/div><div class='form-col-100 question-row' id='add_answersingle_edit'><a href='javascript:;' onclick='addAnswerBlocksingle_edit(); return false;' class='add'> + Add Answer</a></div>");
        //$('#question_singlelist_edit').html("<div class='form-col-100 question-row' id='answerAddsingle_edit'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' id='prefill_list_value' name='singlelist_value_edit[]' value='' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select class='invoice-fields' id='prefill_list_score' name='singlelist_score_value_edit[]'><option value=''>Select Passing Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div></div></div></div><\/div><div class='form-col-100 question-row' id='add_answersingle_edit'><a href='javascript:;' onclick='addAnswerBlocksingle_edit(); return false;' class='add'> + Add Answer</a></div>");
        $('#question_singlelist_edit').html("<div class='form-col-100 question-row' id='answerAddsingle_edit'><div class='container-fluid'><div class='row'><div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><div class='form-group'><label>Answer Choice</label><input class='invoice-fields' type='text' id='prefill_list_value' name='singlelist_value_edit[]' value='' /></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Answer Choice</label><div class='hr-select-dropdown'><select class='invoice-fields' id='prefill_list_score' name='singlelist_score_value_edit[]'><option value=''>Select Passing Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Status</label><div class='hr-select-dropdown'><select class='invoice-fields answer-list' id='prefill_list_status' name='singlelist_status_value_edit[]'><option value='Pass'>Pass</option><option value='Fail'>Fail</option></select></div></div></div></div></div><\/div><div class='form-col-100 question-row' id='add_answersingle_edit'><a href='javascript:;' onclick='addAnswerBlocksingle_edit(); return false;' class='add'> + Add Answer</a></div>");
        // tenth
        var edit_question_id = document.getElementById('child_question_id').value;
        var list_edit_value = document.getElementsByName('list_edit' + edit_question_id + '_value[]');
        var list_edit_score = document.getElementsByName('list_edit' + edit_question_id + '_score[]');
        var list_edit_status = document.getElementsByName('list_edit' + edit_question_id + '_status[]');
        
        for (var i = 0; i < list_edit_value.length; i++) {
            var list_value = list_edit_value[i].value;
            var list_score = list_edit_score[i].value;
            var list_status = list_edit_status[i].value;
            
            if (i == 0) {
                document.getElementById("prefill_list_value").value = list_value;
                document.getElementById("prefill_list_score").value = list_score;
                document.getElementById("prefill_list_score").value = list_status;
            } else {
                var idj = "answerSingleBlock" + i;
                var score_idj = "edit_prefill_list_score" + i;
                var status_idj = "edit_prefill_list_status" + i;
                
                $("<div id='" + idj + "'><\/div>").appendTo("#answerAddsingle_edit");
                //$('#' + idj).html($('#' + idj).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' name='singlelist_value_edit[]' value='" + list_value + "' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select id='" + score_idj + "' class='invoice-fields' name='singlelist_score_value_edit[]'><option value=''>Select Passing Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row'><a href='javascript:;' onclick=\"deleteAnswerBlock_edit('" + idj + "'); return false;\" class=\"remove\">Delete<\/a></div></div></div></div><\/div>");
                $('#' + idj).html($('#' + idj).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><div class='form-group'><label>Answer Choice</label><input class='invoice-fields' type='text' name='singlelist_value_edit[]' value='" + list_value + "' /></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Answer Choice</label><div class='hr-select-dropdown'><select id='" + score_idj + "' class='invoice-fields' name='singlelist_score_value_edit[]'><option value=''>Select Passing Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div><div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'><div class='form-group'><label>Status</label><div class='hr-select-dropdown'><select id='" + status_idj + "' class='invoice-fields answer-list' name='singlelist_status_value_edit[]'><option value='Pass'>Pass</option><option value='Fail'>Fail</option></select></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new'><a href='javascript:;' onclick=\"deleteAnswerBlock_edit('" + idj + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div></div><\/div>");
                // eleventh
                document.getElementById(score_idj).value = list_score;
                document.getElementById(status_idj).value = list_status;
            }
        }
    }

    var i = 9999999999900;
    function addAnswerBlock_edit() {
        var id = "answerAdd_edit" + i;
        $("<div id='" + id + "'><\/div>").appendTo("#answerAdd_edit");
        //$('#' + id).html($('#' + id).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' name='multilist_value_edit[]' value='' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select class='invoice-fields' name='multilist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row'><a href='#' onclick=\"deleteAnswerBlock_edit('" + id + "'); return false;\" class=\"remove\">Delete<\/a></div></div></div></div><\/div>");
        $('#' + id).html($('#' + id).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><div class='form-group'><label>Answer Choice</label><input class='invoice-fields' type='text' name='multilist_value_edit[]' value='' /></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Passing Score</label><div class='hr-select-dropdown'><select class='invoice-fields answer-list' name='multilist_score_value_edit[]'><option value=''>Select Passing Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div><div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'><div class='form-group'><label>Status</label><div class='hr-select-dropdown'><select class='invoice-fields answer-list' name='multilist_status_value_edit[]'><option value='Pass'>Pass</option><option value='Fail'>Fail</option></select></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new'><a href='javascript:;' onclick=\"deleteAnswerBlock_edit('" + id + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div></div><\/div>");
        // sixth
        i++;
    }

    function deleteAnswerBlock_edit(id) {
        $('#' + id).remove();
    }

    var j = 9999999999900;
    function addAnswerBlocksingle_edit() {
        var idj = "answerAddsingle_edit" + j;
        $("<div id='" + idj + "'><\/div>").appendTo("#answerAddsingle_edit");
        //$('#' + idj).html($('#' + idj).html() + "<div class='form-col-100'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='col-lg-4 col-md-4 col-xs-12 col-sm-4'><input class='invoice-fields' type='text' name='singlelist_value_edit[]' value='' /></div><div class='col-lg-4 col-md-4 col-xs-12 col-sm-4'><div class='hr-select-dropdown'><select class='invoice-fields' name='singlelist_score_value_edit[]'><option value=''>Select Question Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div><div class='col-lg-4 col-md-4 col-xs-12 col-sm-4'><div class='hr-select-dropdown'><select class='invoice-fields' id='' name='pass_fail'><option value=''>Please Select</option><option value='0'>Pass</option><option value='0'>Fail</option></select></div></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row'><a href='javascript:;' onclick=\"deleteAnswerBlocksingle_edit('" + idj + "'); return false;\" class=\"remove\">Delete<\/a></div></div></div><\/div>");
        //$('#' + idj).html($('#' + idj).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='row'><div class='form-col-50-left'><input class='invoice-fields' type='text' name='singlelist_value_edit[]' value='' /></div><div class='form-col-50-right'><div class='hr-select-dropdown'><select class='invoice-fields' name='singlelist_score_value_edit[]'><option value=''>Select Passing Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row'><a href='javascript:;' onclick=\"deleteAnswerBlocksingle_edit('" + idj + "'); return false;\" class=\"remove\">Delete<\/a></div></div></div></div><\/div>");
        $('#' + idj).html($('#' + idj).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><label>Answer Choice</label><input class='invoice-fields' type='text' name='singlelist_value_edit[]' value='' /></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><label>Question Score</label><div class='hr-select-dropdown'><select class='invoice-fields' name='singlelist_score_value_edit[]'><option value=''>Select Passing Score</option><option value='0'>Not acceptable - 0</option><option value='1'>Acceptable - 1</option><option value='2'>Good - 2</option><option value='3'>Very Good - 3</option><option value='4'>Excellent - 4</option></select></div></div><div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'><label>Status</label><div class='hr-select-dropdown'><select class='invoice-fields' name='singlelist_status_value_edit[]'><option value='Pass'>Pass</option><option value='Fail'>Fail</option></select></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new'><a href='javascript:;' onclick=\"deleteAnswerBlocksingle_edit('" + idj + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div></div><\/div>");
    // herererererer third
        j++;
    }

    function deleteAnswerBlocksingle_edit(idj) {
        $('#' + idj).remove();
    }

    $(function () {
        $('input[name="edit_child_submit"]').click(function () {
            alertify.defaults.glossary.title = 'Screening Questionnaires Module';
            if ($('#caption_edit').val() == '') {
                alertify.alert("Please provide Question");
                return false;
            }

            if (!$("input[name='question_type_edit']:checked").val()) {
                alertify.alert("Please select 'Answer Type'");
                return false;
            }

            var question_type = $("input[name='question_type_edit']:checked").val();
            if (question_type == 'boolean') {
                var inps = document.getElementsByName('answer_boolean_edit[]');
                for (var i = 0; i < inps.length; i++) {
                    var inp = inps[i].value;
                    if (inp == '') {
                        if (i == 0) {
                            alertify.alert("Please provide 'Passing score' for choice 'Yes'");
                        } else {
                            alertify.alert("Please provide 'Passing score' for choice 'No'");
                        }
                        return false;
                        break;
                    }
                }
            }

            if (question_type == 'list') {
                var answer_single = document.getElementsByName('singlelist_value_edit[]');
                var score_single = document.getElementsByName('singlelist_score_value_edit[]');
                for (var i = 0; i < answer_single.length; i++) {
                    var singlelist_value = answer_single[i].value;
                    var singlelist_score_value = score_single[i].value;
                    if (singlelist_value == '') {
                        alertify.alert("Missing 'Answer' for 'List of answers with single choice'");
                        return false;
                        break;
                    }
                    if (singlelist_score_value == '') {
                        alertify.alert("Missing 'Score' for 'List of answers with single choice'");
                        return false;
                        break;
                    }
                }
            }

            if (question_type == 'multilist') {
                var answer_multi = document.getElementsByName('multilist_value_edit[]');
                var score_multi = document.getElementsByName('multilist_score_value_edit[]');
                for (var i = 0; i < answer_multi.length; i++) {
                    var multilist_value = answer_multi[i].value;
                    var multilist_score_value = score_multi[i].value;
                    if (multilist_value == '') {
                        alertify.alert("Missing 'Answer' for 'List Of Answers With Multiple Choice'");
                        return false;
                        break;
                    }
                    if (multilist_score_value == '') {
                        alertify.alert("Missing 'Score' for 'List Of Answers With Multiple Choice'");
                        return false;
                        break;
                    }
                }
            }
        });
        setTimeout(function () {
            $(".success").slideUp();
        }, 5000);
    });

</script>