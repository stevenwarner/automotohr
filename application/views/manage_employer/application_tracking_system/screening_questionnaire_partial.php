<script type="text/javascript">
    $(document).ready(function () {
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });
</script>

<div class="event_detail">
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-tabs nav-justified">
                <li class="active"><a data-toggle="tab" href="#screening_questionnaire_tabpage">Screening Questionnaire</a></li>
                <li><a data-toggle="tab" href="#talant_job_fair_tabpage">Talent or Job Fair Data</a></li>
            </ul>
            <div class="tab-content">
                <div id="screening_questionnaire_tabpage" class="tab-pane fade in active hr-innerpadding">
                    <div class="row">
                        <div class="col-xs-12">
                        <!-- Start -->
            <?php       if (sizeof($applicant_jobs) > 0) {
                            $item = 0; 
                            $counter = 0;?>
                            <div class="tab-header-sec">
                                <h2 class="tab-title">Screening Questionnaire <button class="btn btn-success pull-right hidden-print" onclick="window.print()">Print</button></h2><!-- next working area-->
                            </div>
                            <?php foreach ($applicant_jobs as $job) { ?>
                            <?php if($job['job_title'] != NULL && $job['job_title'] != '') { ?>
                                    <p class="questionnaire-heading margin-top">Job: <?php echo $job['job_title']; ?></p>
                            <?php } ?>
                            <?php   if ($job['questionnaire'] != NULL && $job['questionnaire'] != '') { 
                                    $my_questionnaire = unserialize($job['questionnaire']);
                                
                                    if(isset($my_questionnaire['applicant_sid'])) {
                                        $questionnaire_type = 'new';
                                        $questionnaire_name = $my_questionnaire['questionnaire_name']; ?>
                                <p class="questionnaire-heading margin-top" style="background-color: #466b1d;"><?php echo $questionnaire_name; ?></p>
                                    <div class="tab-btn-panel">
                                        <span>Score: <?php echo $job['score'] ?></span><a <?php if($job['questionnaire_result'] == 'Fail'){ echo 'style="background-color:#FF0000;"';}?> href="javascript:;"><?php echo $job['questionnaire_result']; ?></a>                                                       
                                    </div>
                                    <div class="questionnaire-body">
                                    <?php   $questionnaire = $my_questionnaire['questionnaire'];
                                        foreach ($questionnaire as $key => $questionnaire_answers) { 
                                                $answer = $questionnaire_answers['answer'];
                                                $passing_score = $questionnaire_answers['passing_score'];
                                                $score = $questionnaire_answers['score'];
                                                $status = $questionnaire_answers['status']; 
                           
                                                $item ++; ?>

                                                <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="accordion-toggle" data-toggle="collapse"
                                                                   data-parent="#accordion"
                                                                   href="#collapse_<?php echo $item; ?>">
                                                                    <span class="glyphicon glyphicon-minus"></span>
                                                                    <?php echo $key; ?> 
                                                                </a>
                                                                <span style="float: right;"><?php echo  DateTime::createFromFormat('Y-m-d H:i:s', $my_questionnaire['attend_timestamp'])->format('m-d-Y H:i:s'); ?>
                                                            </span>
                                                            </h4>
                                                        </div>
                                                        <div id="collapse_<?php echo $item; ?>" class="panel-collapse collapse in">
                                                        <?php   if (is_array($answer)) {
                                                                    foreach ($answer as $multiple_answer) { ?>
                                                                        <div class="panel-body">
                                                                            <?php echo $multiple_answer; ?>
                                                                        </div>
                                                        <?php       }
                                                                } else { ?>
                                                                <div class="panel-body">
                                                                    <?php echo $answer; ?>
                                                                </div>
                                                        <?php   } ?>

                                                            <div class="panel-body">
                                                                <b>Score: <?php echo $score; ?> points out of possible <?php echo $passing_score; ?></b>
                                                                <span class="<?php echo strtolower($status); ?> pull-right" style="font-size: 22px;">(<?php echo $status; ?>)</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                    <?php } ?>                                                       
                                                </div>
                                    <?php       } else {
                                                $questionnaire_type = 'old'; ?>
                                                  <div class="tab-btn-panel">
                                                        <span>Score : <?php echo $job['score'] ?></span>
                                                        <?php if ($job['passing_score'] <= $job['score']) { ?>
                                                            <a href="javascript:;">Pass</a> 
                                                        <?php } else { ?>
                                                            <a href="javascript:;">Fail</a>
                                                        <?php } ?>
                                                    </div>
                                                    <p class="questionnaire-heading margin-top" style="background-color: #466b1d;">Questions / Answers</p>
                                                    <div class="panel-group-wrp">
                                                        <div class="panel-group" id="accordion">
                                                    <?php   
                                                            $questionnaire = unserialize($job['questionnaire']);
                                                            foreach ($questionnaire as $key => $value) {
                                                                $item ++; ?>
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <a class="accordion-toggle" data-toggle="collapse"
                                                                               data-parent="#accordion"
                                                                               href="#collapse_<?php echo $item; ?>">
                                                                                <span class="glyphicon glyphicon-minus"></span>
                                                                                <?php echo $key; ?> 
                                                                            </a>
                                                                        </h4>
                                                                    </div>
                                                                    <div id="collapse_<?php echo $item; ?>"
                                                                         class="panel-collapse collapse in">
                                                                             <?php
                                                                             if (is_array($value)) {
                                                                                 foreach ($value as $answer) {
                                                                                     ?>
                                                                                <div class="panel-body">
                                                                                    <?php echo $answer; ?>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                        } else { ?>
                                                                            <div class="panel-body">
                                                                                <?php echo $value; ?>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                $counter++;
                                                            } ?>
                                                        </div>
                                                    </div>
                                         <?php     } ?>
                                        <?php } else { ?>
                                            <div class="applicant-notes">
                                                <div class="notes-not-found">No questionnaire found!</div>
                                            </div>
                                        <?php }
                                        $job_manual_questionnaire_history = $job['manual_questionnaire_history'];
                                                                            
                                        if(!empty($job_manual_questionnaire_history)) {
                                            $job_manual_questionnaire_history_count = count($job_manual_questionnaire_history);

                                            foreach($job_manual_questionnaire_history as $job_man_key => $job_man_value) {
                                                $job_manual_questionnaire       = $job_man_value['questionnaire'];
                                                $job_questionnaire_sent_date    = $job_man_value['questionnaire_sent_date'];
                                                $job_man_questionnaire_result   = $job_man_value['questionnaire_result'];
                                                $job_man_score                  = $job_man_value['score'];
                                                $job_man_passing_score          = $job_man_value['passing_score'];
                                                
                                                echo '<br>Resent on: '.date_with_time($job_questionnaire_sent_date).'<hr style="margin-top: 5px; margin-bottom: 5px;">';

                                                if($job_manual_questionnaire != '' || $job_manual_questionnaire != NULL) {
                                                   $job_manual_questionnaire_array = unserialize($job_manual_questionnaire);

                                                   if(empty($job_manual_questionnaire_array)) {
                                                       echo '<div class="applicant-notes">
                                                                <div class="notes-not-found">No questionnaire found!</div>
                                                            </div>';
                                                   } else {
                                                        /************************************************************/
                                                       if(isset($job_manual_questionnaire_array['applicant_sid'])) {
                                                        $questionnaire_name = $job_manual_questionnaire_array['questionnaire_name']; ?>
                                                        <p class="questionnaire-heading margin-top" style="background-color: #466b1d;"><?php echo $questionnaire_name; ?></p>
                                                        <div class="tab-btn-panel">
                                                            <span>Score: <?php echo $job_man_score; ?></span><a <?php if($job_man_questionnaire_result == 'Fail'){ echo 'style="background-color:#FF0000;"';}?> href="javascript:;"><?php echo $job_man_questionnaire_result; ?></a>                                                       
                                                        </div>
                                                    <div class="questionnaire-body">
                                                <?php  
                                               

                                                $questionnaire = $job_manual_questionnaire_array['questionnaire'];

                                                        foreach ($questionnaire as $key => $questionnaire_answers) { 
                                                                    $answer = $questionnaire_answers['answer'];
                                                                    $passing_score = $questionnaire_answers['passing_score'];
                                                                    $score = $questionnaire_answers['score'];
                                                                    $status = $questionnaire_answers['status']; 
                                                                    $item ++; ?>

                                                                    <div class="panel panel-default">
                                                                            <div class="panel-heading">
                                                                                <h4 class="panel-title">
                                                                                    <a class="accordion-toggle" data-toggle="collapse"
                                                                                       data-parent="#accordion"
                                                                                       href="#collapse_<?php echo $item; ?>">
                                                                                        <span class="glyphicon glyphicon-minus"></span>
                                                                                        <?php echo $key; ?>
                                                                                        <span style="float: right;"><?php echo  DateTime::createFromFormat('Y-m-d H:i:s', $job_manual_questionnaire_array['attend_timestamp'])->format('m-d-Y H:i:s'); ?>

                                                                                    </a>
                                                                                </h4>
                                                                            </div>
                                                                            <div id="collapse_<?php echo $item; ?>" class="panel-collapse collapse in">
                                                                            <?php   if (is_array($answer)) {
                                                                                        foreach ($answer as $multiple_answer) { ?>
                                                                                            <div class="panel-body">
                                                                                                <?php echo $multiple_answer; ?>
                                                                                            </div>
                                                                            <?php       }
                                                                                    } else { ?>
                                                                                    <div class="panel-body">
                                                                                        <?php echo $answer; ?>
                                                                                    </div>
                                                                            <?php   } ?>

                                                                                <div class="panel-body">
                                                                                    <b>Score: <?php echo $score; ?> points out of possible <?php echo $passing_score; ?></b>
                                                                                    <span class="<?php echo strtolower($status); ?> pull-right" style="font-size: 22px;">(<?php echo $status; ?>)</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>                                                        
                                                            <?php } ?>                                                       
                                                        </div>
                                            <?php       } else { ?>
                                                              <div class="tab-btn-panel">
                                                                    <span>Score : <?php echo $job_man_score; ?></span>
                                                                    <?php if ($job_man_passing_score <= $job_man_score) { ?>
                                                                        <a href="javascript:;">Pass</a>
                                                                    <?php } else { ?>
                                                                        <a href="javascript:;">Fail</a>
                                                                    <?php } ?>
                                                                </div>
                                                                <p class="questionnaire-heading margin-top" style="background-color: #466b1d;">Questions / Answers</p>
                                                                <div class="panel-group-wrp">
                                                                    <div class="panel-group" id="accordion">
                                                                <?php   $questionnaire = $job_manual_questionnaire_array;

                                                                        foreach ($questionnaire as $key => $value) {
                                                                            $item ++; ?>
                                                                            <div class="panel panel-default">
                                                                                <div class="panel-heading">
                                                                                    <h4 class="panel-title">
                                                                                        <a class="accordion-toggle" data-toggle="collapse"
                                                                                           data-parent="#accordion"
                                                                                           href="#collapse_<?php echo $item; ?>">
                                                                                            <span class="glyphicon glyphicon-minus"></span>
                                                                                            <?php echo $key; ?>
                                                                                        </a>
                                                                                    </h4>
                                                                                </div>
                                                                                <div id="collapse_<?php echo $item; ?>"
                                                                                     class="panel-collapse collapse in">
                                                                                         <?php
                                                                                         if (is_array($value)) {
                                                                                             foreach ($value as $answer) {
                                                                                                 ?>
                                                                                            <div class="panel-body">
                                                                                                <?php echo $answer; ?>
                                                                                            </div>
                                                                                            <?php
                                                                                        }
                                                                                    } else { ?>
                                                                                        <div class="panel-body">
                                                                                            <?php echo $value; ?>
                                                                                        </div>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                            $counter++;
                                                                        } ?>
                                                                    </div>
                                                                </div>
                                                     <?php     } 
                                                   }
                                                } else {
                                                    echo '<div class="applicant-notes">
                                                            <div class="notes-not-found">No questionnaire found!</div>
                                                        </div>';
                                                }
                                            }
                                        } ?>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="tab-header-sec">
                                        <h2 class="tab-title">Screening Questionnaire</h2>
                                        <div class="applicant-notes">
                                            <div class="notes-not-found">No questionnaire found!</div>
                                        </div>
                                    </div>
                                <?php } ?>
                        <!-- End -->
                        </div>
                    </div>
                </div>
                <div id="talant_job_fair_tabpage" class="tab-pane fade">
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- Start Talent-->
            <?php       if (sizeof($applicant_jobs) > 0) {
                            $item = 0; 
                            $counter = 0;
                            $found = 0; ?>
                            <div class="tab-header-sec">
                                <h2 class="tab-title margin-top">Talent Network or Job Fair Data<button class="btn btn-success pull-right hidden-print" onclick="window.print()">Print</button></h2><!-- next working area-->
                            </div>
            <?php           foreach ($applicant_jobs as $job) { 
                            if($job['talent_and_fair_data'] != NULL && $job['talent_and_fair_data'] != '') { 
                                $found = 1;
                                $talent_and_fair_data = unserialize($job['talent_and_fair_data']);
                                $questionnaire_name = $talent_and_fair_data['title']; ?>
                                <p class="questionnaire-heading margin-top" style="background-color: #466b1d;"><?php echo $questionnaire_name; ?></p>
                                <div class="questionnaire-body">
            <?php               $questionnaire = $talent_and_fair_data['questions'];
                                foreach ($questionnaire as $key => $questionnaire_answers) { 
                                    $answer = $questionnaire_answers;
                                    $item ++; ?>
                                    <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse"
                                                       data-parent="#accordion"
                                                       href="#collapse_<?php echo $item; ?>">
                                                        <span class="glyphicon glyphicon-minus"></span>
                                                        <?php echo $key; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse_<?php echo $item; ?>" class="panel-collapse collapse">
                                            <?php   if (is_array($answer)) {
                                                        foreach ($answer as $multiple_answer) { ?>
                                                            <div class="panel-body">
                                                                <?php echo $multiple_answer; ?>
                                                            </div>
                                            <?php       }
                                                    } else { ?>
                                                    <div class="panel-body">
                                            <?php       if($answer != NULL || $answer != '') {
                                                            echo $answer;
                                                        } else {
                                                            echo '<span class="candidate did not answer the question pull-right" style="font-size: 22px;">(Candidate did not answer the question)</span>';
                                                        } ?>
                                                    </div>
                                            <?php   } ?>
                                            </div>
                                        </div>                                                        
                                    <?php } ?>                                                       
                                </div>
            <?php           } ?>
                            
            <?php           } ?>
            <?php       } 
                        if($found == 0) { ?>
                            <div class="tab-header-sec">
                                <h2 class="tab-title">Talent Network or Job Fair Data</h2>
                                <div class="applicant-notes">
                                    <div class="notes-not-found">No data found!</div>
                                </div>
                            </div>
            <?php       } ?>
                        <!-- End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.loader').hide();
        $('#tab_loader').hide();

        $('body').on('shown.bs.modal', '#popupmodal', function(){
            $('.show_email_main_container').hide();
            $('.show_email_col').hide();

            $('#interviewer').select2();
            $('#interviewer').trigger('change');

            func_make_time_pickers();
            func_make_date_picker();
        });


        func_make_time_pickers();
        func_make_date_picker();

        $('.show_email_main_container').hide();
        $('.show_email_col').hide();

        $('#interviewer').select2();
        $('#interviewer').trigger('change');

        $('body').on('change', '#interviewer', function(){
            var event_sid = $(this).attr('data-event_sid');
            event_sid = event_sid == null || event_sid == undefined || event_sid == '' || event_sid == 0 ? 0 : event_sid;

            var selected = $(this).val();
            if(selected !== null && selected.length > 0) {
                $('#show_email_main_container_' + event_sid).show();
                $('#show_email_main_container_' + event_sid + ' input[type=checkbox]').prop('disabled', false);

                $('.show_email_col').hide();

                for (i = 0; i < selected.length; i++) {
                    emp_sid = selected[i];

                    $('#show_email_container_' + event_sid + '_' + emp_sid).show();
                    $('#show_email_' + event_sid + '_' + emp_sid).prop('disabled', false);
                }
            } else {
                $('#show_email_main_container_' + event_sid).hide();
                $('#show_email_main_container_' + event_sid + ' input[type=checkbox]').prop('disabled', true);
            }
        });
    });

    function func_make_time_pickers(){
        $('.start_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('.end_time').datetimepicker({
                    minTime: $input.val()
                });
            }
        });

        $('.end_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onChangeDateTime: function (dp, $input) {
                $('.start_time').datetimepicker({
                    maxTime: $input.val()
                });
            }
        });
    }

    function func_make_date_picker(){
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
        });
    }

    function func_get_past_events(user_type, user_sid) {
        var my_request;

        $('#tab_content').hide();
        $('#tab_loader').show();

        my_request = $.ajax({
            url: '<?php echo base_url('calendar/ajax_responder'); ?>',
            type: 'POST',
            data: {
                'perform_action': 'get_past_events',
                'user_type': user_type,
                'user_sid': user_sid
            }
        });

        my_request.done(function(response){
            $('#tab_content').show();
            $('#tab_loader').hide();

            $('#tab_content').html(response);
            $('.loader').hide();
        });

        my_request.always(function(){
            $('#tab_content').show();
            $('#tab_loader').hide();

        });
    }

    function func_edit_event(event_sid, user_sid, user_job_list_sid) {
        var my_request;

        $('.btn').prop('disabled', true);
        $('#spinner_' + event_sid).show();

        my_request = $.ajax({
            url: '<?php echo base_url('calendar/ajax_responder'); ?>',
            type: 'POST',
            data: {
                'perform_action': 'get_event_edit_form',
                'event_sid': event_sid,
                'user_sid': user_sid,
                'redirect_to': 'applicant_profile',
                'redirect_to_user_sid': user_sid,
                'redirect_to_job_list_sid': user_job_list_sid
            }
        });

        my_request.done(function (response) {

            $('.btn').prop('disabled', false);
            $('#spinner_' + event_sid).hide();

            $('#popupmodallabel').html('Edit Event');
            $('#popupmodalbody').html(response);
            $('.modal-dialog').addClass('modal-lg');
            $('#popupmodal').modal('show');

            console.log(response);
        });

        my_request.always(function(){
            $('.btn').prop('disabled', false);
            $('#spinner_' + event_sid).hide();
        });
    }
</script>