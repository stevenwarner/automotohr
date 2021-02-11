<?php
    $company_sid = 0;
    $users_type = '';
    $users_sid = 0;
    $back_url = '';
    $dependants_arr = array();
    $delete_post_url = '';
    $save_post_url = '';
    $watch_video_base_url = '';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/dashboard/' . $unique_sid);

    $watch_video_base_url = base_url('onboarding/watch_video/' . $unique_sid);

    $delete_post_url = current_url();
    $save_post_url = current_url();
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');

    $watch_video_base_url = base_url('my_learning_center/watch_video/');

    $delete_post_url = current_url();
    $save_post_url = current_url();
}

?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a href="<?php echo $back_url; ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Dashboard</a>
                </div>
                <div class="page-header">
                  <h1 class="section-ttile">Learning Center</h1>
                </div>

                <div class="accordion-colored-header">
                    <div class="panel-group" id="accordion">

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#online_videos"><span class="glyphicon glyphicon-minus"></span>Online Videos</a>
                                    </h4>
                                </div>
                                <div id="online_videos" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Video</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php if(!empty($videos)) { ?>
                                                            <?php foreach($videos as $video) { ?>
                                                                <tr>
                                                                    <td class="col-xs-11"><?php echo $video['video_title']; ?></td>
                                                                    <td class="col-xs-1">
                                                                        <a href="<?php echo $watch_video_base_url . '/' . $video['learning_center_online_videos_sid']; ?>" class="btn btn-block btn-info">Watch</a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="text-center" colspan="2">
                                                                    <span class="no-data">No Videos Assigned</span>
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

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#training_sessions"><span class="glyphicon glyphicon-minus"></span>Training Sessions</a>
                                    </h4>
                                </div>
                                <div id="training_sessions" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div class="form-wrp">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th rowspan="2" class="col-xs-4 valign-middle">Topic</th>
                                                        <th rowspan="2" class="col-xs-1 text-center valign-middle">Date</th>
                                                        <th colspan="2" class="col-xs-1 text-center valign-middle">Time</th>
                                                        <th rowspan="2" class="col-xs-1 text-center valign-middle">Session Status</th>
                                                        <th rowspan="2" class="col-xs-1 text-center valign-middle">Attend Status</th>
                                                        <th rowspan="2" class="col-xs-3 text-center valign-middle" colspan="3">Actions</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="col-xs-1 text-center valign-middle">Start</th>
                                                        <th class="col-xs-1 text-center valign-middle">End</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(!empty($assigned_sessions)) { ?>
                                                        <?php foreach($assigned_sessions as $session) { ?>
                                                            <tr>
                                                                <td><?php echo $session['session_topic']; ?></td>
                                                                <td class="text-center"><?php echo date('m-d-Y', strtotime($session['session_date'])); ?></td>
                                                                <td class="text-center"><?php echo date('H:i', strtotime($session['session_start_time'])); ?></td>
                                                                <td class="text-center"><?php echo date('H:i', strtotime($session['session_end_time'])); ?></td>
                                                                <td class="text-center"><?php echo ucwords($session['session_status']);?></td>
                                                                <td class="text-center"><?php echo ucwords(str_replace('_', ' ', $session['attend_status']));?></td>
                                                                <td>
                                                                    <form id="form_unable_to_attend_<?php echo $session['sid']; ?>" enctype="multipart/form-data" method="post">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />
                                                                        <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="<?php echo $session['sid']; ?>" />
                                                                        <input type="hidden" id="user_type" name="user_type" value="<?php echo $session['user_type']; ?>" />
                                                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $session['user_sid']; ?>" />
                                                                        <input type="hidden" id="attend_status" name="attend_status" value="unable_to_attend" />
                                                                        <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                                                    </form>
                                                                    <button <?php echo $session['uta_btn_status']; ?> onclick="func_submit_form('form_unable_to_attend_<?php echo $session['sid']; ?>', 'Are you sure you are unable to attend?');" class="btn btn-block btn-danger btn-sm <?php echo $session['uta_btn_status']; ?>">Unable To Attend</button>
                                                                </td>
                                                                <td>
                                                                    <form id="form_will_attend_<?php echo $session['sid']; ?>" enctype="multipart/form-data" method="post">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />
                                                                        <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="<?php echo $session['sid']; ?>" />
                                                                        <input type="hidden" id="user_type" name="user_type" value="<?php echo $session['user_type']; ?>" />
                                                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $session['user_sid']; ?>" />
                                                                        <input type="hidden" id="attend_status" name="attend_status" value="will_attend" />
                                                                        <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                                                    </form>
                                                                    <button <?php echo $session['wa_btn_status']; ?> onclick="func_submit_form('form_will_attend_<?php echo $session['sid']; ?>', 'Are you sure you will attend?');" class="btn btn-block btn-warning btn-sm <?php echo $session['wa_btn_status']; ?>">Will Attend</button>
                                                                </td>
                                                                <td>
                                                                    <form id="form_attended_<?php echo $session['sid']; ?>" enctype="multipart/form-data" method="post">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />
                                                                        <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="<?php echo $session['sid']; ?>" />
                                                                        <input type="hidden" id="user_type" name="user_type" value="<?php echo $session['user_type']; ?>" />
                                                                        <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $session['user_sid']; ?>" />
                                                                        <input type="hidden" id="attend_status" name="attend_status" value="attended" />
                                                                        <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                                                    </form>
                                                                    <button <?php echo $session['a_btn_status']; ?> onclick="func_submit_form('form_attended_<?php echo $session['sid']; ?>', 'Are you sure you want to mark this session as attended?');" class="btn btn-block btn-success btn-sm <?php echo $session['a_btn_status']; ?>">Attended</button>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="8">
                                                                <span class="no-data">No Sessions</span>
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
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    function func_submit_form(form_id, alert_message){
        alertify.confirm(
            'Are you sure?',
            alert_message,
            function(){
                $('#' + form_id).submit();
            },
            function(){
                alertify.error('Cancelled!');
            });
    }
</script>