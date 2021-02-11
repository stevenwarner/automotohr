<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area  margin-top">
                        <span class="page-heading down-arrow">
                            <a class="dashboard-link-btn" href="<?php 
                                if(isset($template_sid)){
                                    echo base_url() . 'video_interview_system/templates/' . $applicant_sid . '/' . $job_list_sid;
                                } else {
                                    echo base_url() . 'applicant_profile/' . $applicant_sid . ((isset($job_list_sid)) ? '/'.$job_list_sid : '');
                                }
                                ?>">
                                <i class="fa fa-chevron-left"></i>
                                Applicant Profile
                            </a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="row" style="margin-top: 10px;">
                            <!-- table -->
                            <div class="table-responsive table-outer">
                                <?php if (!empty($video_questions)) { ?>
                                    <form action="" method="POST" id="send_questions_form" name="send_questions_form">
                                        <div class="table-wrp mylistings-wrp border-none">
                                            <a href="<?php echo base_url() . 'video_interview_system/templates/' . $applicant_sid . '/' . $job_list_sid; ?>" class="btn btn-success">
                                                Video Question Templates
                                            </a>
                                            <a href="<?php echo base_url() . 'video_interview_system/responses/' . $applicant_sid; ?>" class="btn btn-success" style="float:right;margin-bottom:15px;">
                                                View Applicant Responses
                                            </a>
                                            <table class="table table-bordered table-stripped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <input type="checkbox" name="questions[]" value="all" id="all_questions">
                                                        </th>
                                                        <th class="col-xs-5">Question</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Created</th>
                                                        <th>Response Status</th>
<!--                                                        <th class="text-center">Response</th>-->
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($video_questions as $question) { ?>
                                                        <tr 
                                                            id="<?php echo 'q_' . $question['sid']; ?>"
                                                            <?php echo ((in_array($question['sid'], $sent_questions)) || ($question['status'] == 'inactive')) ? 'style="background-color: #f7f7f7 !important;color:#999;"' : ''; ?>>
                                                            <td>
                                                                <input type="checkbox" name="questions[]" value="<?php echo $question['sid']; ?>" 
                                                                <?php echo ((in_array($question['sid'], $sent_questions)) || ($question['status'] == 'inactive')) ? 'checked disabled' : ''; ?>       
                                                                >
                                                            </td>
                                                            <td><?php echo ($question['question_type'] == 'text') ? $question['question_text'] : $question['video_title']; ?></td>
                                                            <td><?php echo ucwords($question['question_type']); ?></td>
                                                            <td 
                                                                id='<?php echo 'r_' . $question['sid']; ?>' 
                                                                class="<?php echo ($question['status'] == 'active') ? 'green' : 'red'; ?>"
                                                                <?php echo ((in_array($question['sid'], $sent_questions)) || ($question['status'] == 'inactive')) ? 'style="color:#999;"' : ''; ?>>
                                                                <?php echo ucwords($question['status']); ?>
                                                            </td>
                                                            <td><?php echo date_with_time($question['created_date']); ?></td>
                                                            <?php $response_status = get_questionnaire_response_status($question['sid'], $applicant_sid); ?>
                                                            <td style="color:<?php echo ($response_status == 'Answered') ? 'green' : 'red'; ?>">
                                                                <?php echo $response_status; ?>
                                                            </td>
<!--                                                            <td class="text-center">
                                                                <?php if ($response_status == 'Answered') { ?>
                                                                <a href="<?php echo base_url() . 'video_interview_system/response/' . $question['sid'] . '/' . $applicant_sid; ?>" class="btn btn-success" target="_blank">
                                                                    View
                                                                </a>
                                                                <?php } ?>
                                                            </td>-->
                                                        </tr>
                                                    <?php } ?>                                            
                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="submit" value="Submit" class="submit-btn" id="send_question_submit" name="send_question_submit" style="float:right;">
                                    </form>
                                <?php } else { ?>
                                    <div class="no-job-found">
                                        <ul>
                                            <li>
                                                <h3 style="text-align: center;">No Video Questions found! </h3>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>                        
                            </div>
                            <!-- table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#all_questions").change(function(){ 
            if(this.checked == true){
                $('input[type=checkbox]').each(function(){
                    if ( !$(this).is(':disabled') ) { 
                        this.checked = true; 
                    }
                });
            } else {
                $('input[type=checkbox]').each(function(){ 
                    if ( !$(this).is(':disabled') ) {
                        this.checked = false; 
                    }
                });
            }
        });
        
        $('#send_question_submit').click(function () {
             if ($('#send_questions_form :checkbox:checked').length > 0){
                var size = '<?php echo count($video_questions); ?>';
                if ($('#send_questions_form :checkbox:disabled').length == size){
                    alertify.error('All Active Video Questions in this list have been sent for to the Applicant.');
                    return false;
                } else if ($('#send_questions_form :checkbox:disabled').length < size) {
                    var selected_length = $('#send_questions_form :checkbox:checked').length;
                    selected_length = selected_length - $('#send_questions_form :checkbox:disabled').length;
                    if($('#all_questions').is(":checked")){
                        selected_length = selected_length - 1;
                    }
                    if(selected_length > 0){
                        // go ahead
                    } else {
                        alertify.error('Please select some Questions to continue.');
                        return false;
                    }
                }
            } else {
                alertify.error('Please select some Questions to continue.');
                return false;
            }
        });   
    });
</script>