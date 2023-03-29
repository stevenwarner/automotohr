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
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <div class="row">
                                <div class="col-xs-4">

                                </div>
                                <div class="col-xs-4">
                                    <?php echo $subtitle; ?>
                                </div>
                                <div class="col-xs-4"></div>
                            </div>
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">                        	
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('.collapse').on('shown.bs.collapse', function () {
                                        $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
                                    }).on('hidden.bs.collapse', function () {
                                        $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
                                    });
                                });
                            </script>
                            <form id="form_send_questions" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid?>"  />
                                <input type="hidden" id="employer_sid" name="employer_sid" value="<?php echo $employer_sid?>"  />
                                <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $applicant_sid?>"  />
                                <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $applicant_sid?>"  />
                                <input type="hidden" id="job_list_sid" name="job_list_sid" value="<?php echo $job_list_sid?>"  />
                                <div class="panel-group-wrp panel-head-bg">
	                                <div class="panel-group" id="accordion">
	                                    <div class="panel panel-default">
	                                        <div class="panel-heading">
	                                            <h4 class="panel-title">
	                                                <label class="control control--checkbox">
	                                                	<h4 class="panel-title">
		                                                    <a data-toggle="collapse" data-parent="#accordion" href="#default_questions">
		                                                        <span class="glyphicon glyphicon-plus"></span>
		                                                        Default Questions
		                                                    </a>
	                                                    </h4>
	                                                    <input class="check_select_all" type="checkbox" id="select_all_def_questions" name="select_all_def_questions" value="0" />
	                                                    <div class="control__indicator"></div>
	                                                </label>
	                                            </h4>
	                                        </div>
	                                        <div id="default_questions" class="panel-collapse collapse">
	                                            <div class="panel-body">
	                                                <div class="row">
	                                                    <div class="col-xs-12">
	                                                        <div class="table-responsive">
	                                                            <table class="table table-bordered">
	                                                                <thead>
	                                                                    <tr>
	                                                                        <th class="col-xs-9">Question</th>
	                                                                        <th class="col-xs-1">Type</th>
	                                                                        <th class="col-xs-1">Status</th>
	                                                                        <th class="col-xs-1">Sort Order</th>
	                                                                    </tr>
	                                                                </thead>
	                                                                <tbody>
	                                                                    <?php if(!empty($company_default_questions)) { ?>
	                                                                        <?php foreach ($company_default_questions as $key => $question) { ?>
	                                                                            <?php $response_status = get_questionnaire_response_status($question['sid'], $applicant_sid); ?>
	                                                                            <tr id="<?php echo 'q_' . $question['sid']; ?>">
	                                                                                <td>
	                                                                                    <div class="row">
	                                                                                        <div class="col-xs-12">
	                                                                                            <label class="control control--checkbox">
	                                                                                                <?php echo ($question['question_type'] == 'text') ? $question['question_text'] : $question['video_title']; ?>
	                                                                                                <input <?php echo $response_status != 'Not Sent' ? 'disabled="disabled"' : '' ; ?> class="default_question question" type="checkbox" name="questions[<?php echo $question['sid']; ?>][sid]" value="<?php echo $question['sid']; ?>" />
	                                                                                                <div class="control__indicator"></div>
	                                                                                            </label>
	                                                                                        </div>
	                                                                                    </div>
	                                                                                    <?php if($response_status != 'Not Sent') { ?>
	                                                                                        <div class="row">
	                                                                                            <div class="col-xs-1"></div>
	                                                                                            <div class="col-xs-10">
	                                                                                                <label class="control control--checkbox">
	                                                                                                    <small>Resend This Question</small>
	                                                                                                    <input class="default_question resend_question question" type="checkbox" name="questions[<?php echo $question['sid']; ?>][sid]" value="<?php echo $question['sid']; ?>" />
	                                                                                                    <div class="control__indicator"></div>
	                                                                                                </label>
	                                                                                                <input type="hidden" id="is_resent_<?php echo $question['sid']; ?>" name="questions[<?php echo $question['sid']; ?>][is_resent]" value="0" />
	                                                                                                <div id="resend_note_container_<?php echo $question['sid']; ?>" class="form-group">
	                                                                                                    
	                                                                                                    <div class="floating-label-field resend_note" id="resent_note_<?php echo $question['sid']; ?>">
																										  <input data-question_sid="<?php echo $question['sid']; ?>" class="form-control" name="questions[<?php echo $question['sid']; ?>][resent_note]" required/>
																										  <span class="floating-label">resent note</span>
																										</div>

	                                                                                                </div>
	                                                                                            </div>
	                                                                                        </div>
	                                                                                    <?php } else { ?>
	                                                                                        <input type="hidden" id="is_resent_<?php echo $question['sid']; ?>" name="questions[<?php echo $question['sid']; ?>][is_resent]" value="0" />
	                                                                                        <input type="hidden" id="resent_note_<?php echo $question['sid']; ?>" name="questions[<?php echo $question['sid']; ?>][resent_note]" value="0" />
	                                                                                    <?php } ?>
	                                                                                </td>

	                                                                                <td>
	                                                                                    <?php echo ucwords($question['question_type']); ?>
	                                                                                    <input type="hidden" id="question_type" name="questions[<?php echo $question['sid']; ?>][question_type]" value="<?php echo $question['question_type']; ?>" />
	                                                                                </td>

	                                                                                <td style="color:<?php echo ($response_status == 'Answered') ? 'green' : 'red'; ?>">
	                                                                                    <?php echo $response_status; ?>
	                                                                                </td>
	                                                                                <td>
	                                                                                    <div class="form-group">
	                                                                                        <input class="form-control" type="number" min="0" value="<?php echo $key?>" step="1" id="sort_order_<?php echo $question['sid']; ?>" name="questions[<?php echo $question['sid']; ?>][sort_order]" />
	                                                                                    </div>
	                                                                                </td>
	                                                                            </tr>

	                                                                        <?php } ?>
	                                                                    <?php } else { ?>
	                                                                        <tr>
	                                                                            <td class="text-center" colspan="3">
	                                                                                <span class="no-data">No Questions</span>
	                                                                            </td>
	                                                                        </tr>
	                                                                    <?php } ?>
	                                                                </tbody>
	                                                            </table>
	                                                        </div>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>

	                                    <?php if(!empty($company_question_templates)) {?>
	                                        <?php foreach($company_question_templates as $template) { ?>
	                                            <div class="panel panel-default">
	                                                <div class="panel-heading">
	                                                    <h4 class="panel-title">
	                                                        <label class="control control--checkbox">
	                                                        	<h4 class="panel-title">
		                                                            <a data-toggle="collapse" data-parent="#accordion" href="#template_<?php echo $template['sid']; ?>">
		                                                                <span class="glyphicon glyphicon-plus"></span>
		                                                                <?php echo $template['title']; ?>
		                                                            </a>
	                                                            </h4>
	                                                            <input class="check_select_all resend_question" type="checkbox" id="select_all_def_questions" name="select_all_def_questions" value="<?php echo $template['sid']; ?>" />
	                                                            <div class="control__indicator"></div>
	                                                        </label>
	                                                    </h4>
	                                                </div>
	                                                <div id="template_<?php echo $template['sid']; ?>" class="panel-collapse collapse">
	                                                    <div class="panel-body">
	                                                        <div class="row">
	                                                            <div class="col-xs-12">
	                                                                <div class="table-responsive">

	                                                                    <table class="table table-bordered">
	                                                                        <thead>
	                                                                        <tr>
	                                                                            <th class="col-xs-9">Question</th>
	                                                                            <th class="col-xs-1">Type</th>
	                                                                            <th class="col-xs-1">Status</th>
	                                                                            <th class="col-xs-1">Sort Order</th>
	                                                                        </tr>
	                                                                        </thead>
	                                                                        <tbody>
	                                                                        <?php if(!empty($template['questions'])) { ?>
	                                                                            <?php foreach ($template['questions'] as $key => $question) { ?>
	                                                                                <?php $response_status = get_questionnaire_response_status($question['sid'], $applicant_sid); ?>
	                                                                                <tr id="<?php echo 'q_' . $question['sid']; ?>">
	                                                                                    <td>
	                                                                                        <div class="row">
	                                                                                            <div class="col-xs-12">
	                                                                                                <label class="control control--checkbox">
	                                                                                                    <?php echo ($question['question_type'] == 'text') ? $question['question_text'] : $question['video_title']; ?>
	                                                                                                    <input <?php echo $response_status != 'Not Sent' ? 'disabled="disabled"' : '' ; ?> class="template_<?php echo $template['sid']?> question" type="checkbox" name="questions[<?php echo $question['sid']; ?>][sid]" value="<?php echo $question['sid']; ?>" />
	                                                                                                    <div class="control__indicator"></div>
	                                                                                                </label>
	                                                                                            </div>
	                                                                                        </div>
	                                                                                        <?php if($response_status != 'Not Sent') { ?>
	                                                                                            <div class="row">
	                                                                                                <div class="col-xs-1"></div>
	                                                                                                <div class="col-xs-10">
	                                                                                                    <label class="control control--checkbox">
	                                                                                                        <small>Resend This Question</small>
	                                                                                                        <input class="template_<?php echo $template['sid']?> resend_question question" type="checkbox" name="questions[<?php echo $question['sid']; ?>][sid]" value="<?php echo $question['sid']; ?>" />
	                                                                                                        <div class="control__indicator"></div>
	                                                                                                    </label>
	                                                                                                    <input type="hidden" id="is_resent_<?php echo $question['sid']; ?>" name="questions[<?php echo $question['sid']; ?>][is_resent]; ?>" value="0" />
	                                                                                                    <div id="resend_note_container_<?php echo $question['sid']; ?>" class="form-group">
                                                                                                            <div class="floating-label-field resend_note" id="resent_note_<?php echo $question['sid']; ?>">
																											    <input data-question_sid="<?php echo $question['sid']; ?>" class="form-control" name="questions[<?php echo $question['sid']; ?>][resent_note]" required />
                                                                                                                <span class="floating-label">resent note</span>
                                                                                                            </div>
	                                                                                                    </div>
	                                                                                                </div>
	                                                                                            </div>
	                                                                                        <?php } else { ?>
	                                                                                            <input type="hidden" id="is_resent_<?php echo $question['sid']; ?>" name="questions[<?php echo $question['sid']; ?>][is_resent]" value="0" />
	                                                                                            <input type="hidden" id="resent_note_<?php echo $question['sid']; ?>" name="questions[<?php echo $question['sid']; ?>][resent_note]" value="0" />
	                                                                                        <?php } ?>
	                                                                                    </td>

	                                                                                    <td>
	                                                                                        <?php echo ucwords($question['question_type']); ?>
	                                                                                        <input type="hidden" id="question_type" name="questions[<?php echo $question['sid']; ?>][question_type]" value="<?php echo $question['question_type']; ?>" />
	                                                                                    </td>

	                                                                                    <td style="color:<?php echo ($response_status == 'Answered') ? 'green' : 'red'; ?>">
	                                                                                        <?php echo $response_status; ?>
	                                                                                    </td>
	                                                                                    <td>
	                                                                                        <div class="form-group">
	                                                                                            <input class="form-control" type="number" min="0" value="<?php echo $key?>" step="1" id="sort_order_<?php echo $question['sid']; ?>" name="questions[<?php echo $question['sid']; ?>][sort_order]" />
	                                                                                        </div>
	                                                                                    </td>
	                                                                                </tr>

	                                                                            <?php } ?>
	                                                                        <?php } else { ?>
	                                                                            <tr>
	                                                                                <td class="text-center" colspan="3">
	                                                                                    <span class="no-data">No Questions</span>
	                                                                                </td>
	                                                                            </tr>
	                                                                        <?php } ?>
	                                                                        </tbody>
	                                                                    </table>
	                                                                </div>
	                                                            </div>
	                                                        </div>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        <?php } ?>
	                                    <?php } ?>
	                                </div> 
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <label class="control control--radio">
                                            <input type="radio" id="notification_type_group" name="notification_type" value="group" checked="checked" />
                                            Notify me when the group of Questions have been Answered
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <label class="control control--radio">
                                            <input type="radio" id="notification_type_individual" name="notification_type" value="individual" />
                                            Notify me when each question is Answered
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-8"></div>
                                    <div class="col-xs-4">
                                        <button onclick="func_send_questions();" type="button" class="btn btn-success btn-block">Send Selected Questions</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function func_send_questions() {
        var selected_questions = $('.question:checked');

        if(selected_questions.length > 0) {

            alertify.confirm(
                'Are You Sure?',
                'Are you sure you want to send / resend the selected questions?',
                function () {
                    $('#form_send_questions').submit();
                }, function () {
                    alertify.error('Cancelled!');
                });
        } else {
            alertify.error('Please Select Questions First!');
        }
    }

    $(document).ready(function () {
        $('.resend_note, .floating-label').hide();

        $('.resend_question').each(function () {
            $(this).on('click', function () {
                var question_sid = $(this).val();
                var status = $(this).prop('checked');

                if (status == true) {
                    $('#is_resent_' + question_sid).val(1);
                    $('#resent_note_' + question_sid).show();
                    $('.floating-label').show();
                } else {
                    $('#is_resent_' + question_sid).val(0);
                    $('#resent_note_' + question_sid).hide();
                }
            });
        });

        $('.check_select_all').each(function () {
            $(this).on('click', function () {
                var value = $(this).val();
                var status = $(this).prop('checked');

                if (status == true) {
                    if (value > 0) {
                        $('.template_' + value + ':not(input[disabled], .resend_question)').prop('checked', true);
                    } else {
                        $('.default_question:not(input[disabled], .resend_question)').prop('checked', true);
                    }
                } else {
                    if (value > 0) {
                        $('.template_' + value + ':not(input[disabled], .resend_question)').prop('checked', false);
                    } else {
                        $('.default_question').prop('checked', false);
                    }
                }
            });
        });
    });
</script>

