<?php
    $company_sid = 0;
    $users_type = '';
    $users_sid = 0;
    $back_url = '';
    $dependants_arr = array();
    $delete_post_url = '';
    $save_post_url = '';
    $watch_video_base_url = '';
    $next_btn = '';
    $center_btn = '';
    $back_btn = 'Dashboard';

	if (isset($applicant)) {
	    $company_sid = $applicant['employer_sid'];
	    $users_type = 'applicant';
	    $users_sid = $applicant['sid'];
            
            if($company_eeoc_form_status == 1) {
                $back_btn_function = 'eeoc_form';
            } else {
                $back_btn_function = 'hr_documents';
            }
    
	    $back_url = base_url('onboarding/'.$back_btn_function.'/' . $unique_sid);
	    $watch_video_base_url = base_url('onboarding/watch_video/' . $unique_sid);
	    $delete_post_url = current_url();
	    $save_post_url = current_url();
            $next_btn = '<a href="'.base_url('onboarding/my_credentials/' . $unique_sid).'"class="btn btn-success btn-block" id="go_next"> Proceed To Next <i class="fa fa-angle-right"></i></a>';
            $center_btn = '<a href="'.base_url('onboarding/my_credentials/' . $unique_sid).'"class="btn btn-warning btn-block" id="go_next"> Bypass This Step <i class="fa fa-angle-right"></i></a>';
            $back_btn = 'Review Previous Step';
	} else if (isset($employee)) {
	    $company_sid = $employee['parent_sid'];
	    $users_type = 'employee';
	    $users_sid = $employee['sid'];
	    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');
	    $watch_video_base_url = base_url('my_learning_center/watch_video/');
	    $delete_post_url = current_url();
	    $save_post_url = current_url();
	} ?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <a href="<?php echo $back_url; ?>" class="btn btn-info btn-block"><i class="fa fa-angle-left"></i>  <?= $back_btn;?></a>
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <?php echo $center_btn;?>
                        </div>
                        <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <?php echo $next_btn;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">Learning Center</h1>
                </div>
                <p class="text-blue">Learning Center helps you succeed in the organization and beyond.</p>
                <p class="text-blue"><b>Take advantage of our resources</b></p>
                
                <div class="panel panel-default lc-tabs-panel">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="<?php echo !empty($videos) ? 'active' : '';?>"><a href="#online_videos" data-toggle="tab">Online Videos</a></li>
                            <li class="<?php echo empty($videos) && !empty($assigned_sessions) ? 'active' : '';?>"><a href="#training_sessions" data-toggle="tab">Training Sessions</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade <?php echo !empty($videos) ? 'in active' : '';?>" id="online_videos">
                                <!--<div class="table-responsive">-->
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="dashboard-conetnt-wrp">
                                                <?php if($videos) { //echo '<pre>'; print_r($videos); echo '</pre>'; exit;?>
                                                        <div class="announcements-listing">
                                                <?php   foreach ($videos as $video) { ?>
                                                                <article class="listing-article">
                                                                    <figure>
                                                                <?php   if($video['video_source'] == 'youtube') { ?>
                                                                            <a href="<?php echo $watch_video_base_url . '/' . $video['learning_center_online_videos_sid']; ?>"><img src="https://img.youtube.com/vi/<?php echo $video['video_id']; ?>/hqdefault.jpg"/></a>
                                                                <?php   } if($video['video_source'] == 'vimeo') { 
                                                                            $thumbnail_image = vimeo_video_data($video['video_id']); ?>
                                                                            <a href="<?php echo $watch_video_base_url . '/' . $video['learning_center_online_videos_sid']; ?>"><img src="<?php echo $thumbnail_image;?>"/></a>
                                                                <?php   } else { ?>
                                                                            <a href="<?php echo $watch_video_base_url . '/' . $video['learning_center_online_videos_sid']; ?>"><img src="<?=base_url('assets/images/no-preview.jpg')?>"/></a>
                                                                <?php   } ?>
                                                                    </figure>
                                                                    <div class="text">
                                                                        <h3><a href="<?php echo $watch_video_base_url . '/' . $video['learning_center_online_videos_sid']; ?>"><?php echo $video['video_title']; ?></a></h3>
                                                                        <div class="post-options">
                                                                            <ul>
                                                                                <li><?php echo date_with_time($video['created_date']); ?></li>
                                                                            </ul>
                                                                            <span class="post-author"><a href="<?php echo $watch_video_base_url . '/' . $video['learning_center_online_videos_sid']; ?>" class="btn btn-block btn-info">Watch</a></span>
                                                                        </div>
                                                                        <div class="full-width announcement-des" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                                            <?php echo strlen($video['video_description']) > 100 ? substr($video['video_description'],0,100)." ..." : $video['video_description']; ?>
                                                                        </div>
                                                                    </div>
                                                                </article>
                                                <?php   } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <!--</div>-->
                            </div>
                            <div class="tab-pane fade <?php echo empty($videos) && !empty($assigned_sessions) ? 'in active' : '';?>" id="training_sessions">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                        <tr>
                                            <th rowspan="2" class="col-xs-3 valign-middle">Topic</th>
                                            <th rowspan="2" class="col-xs-2 text-center valign-middle">Date</th>
                                            <th colspan="2" class="col-xs-1 text-center valign-middle">Time</th>
                                            <th rowspan="2" class="col-xs-1 text-center valign-middle">Session Status</th>
                                            <th rowspan="2" class="col-xs-2 text-center valign-middle">Attend Status</th>
                                            <th rowspan="2" class="col-xs-2 text-center valign-middle" colspan="3">Actions</th>
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
                                                        <?php if($session['is_expired'] == 1) { ?>
                                                        <button class="btn btn-block btn-danger btn-sm disabled" title="Training session is expired">Unable To Attend</button>
                                                        <?php } else{ ?>
                                                        <form id="form_unable_to_attend_<?php echo $session['sid']; ?>" enctype="multipart/form-data" method="post">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />
                                                            <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="<?php echo $session['sid']; ?>" />
                                                            <input type="hidden" id="user_type" name="user_type" value="<?php echo $session['user_type']; ?>" />
                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $session['user_sid']; ?>" />
                                                            <input type="hidden" id="attend_status" name="attend_status" value="unable_to_attend" />
                                                            <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                                        </form>
                                                        <button <?php echo $session['uta_btn_status']; ?> onclick="func_submit_form('form_unable_to_attend_<?php echo $session['sid']; ?>', 'Are you sure you are unable to attend?');" class="btn btn-block btn-danger btn-sm <?php echo $session['uta_btn_status']; ?>">Unable To Attend</button>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if($session['is_expired'] == 1) { ?>
                                                        <button class="btn btn-block btn-warning btn-sm disabled" title="Training session is expired">Will Attend</button>
                                                        <?php } else{ ?>
                                                        <form id="form_will_attend_<?php echo $session['sid']; ?>" enctype="multipart/form-data" method="post">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />
                                                            <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="<?php echo $session['sid']; ?>" />
                                                            <input type="hidden" id="user_type" name="user_type" value="<?php echo $session['user_type']; ?>" />
                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $session['user_sid']; ?>" />
                                                            <input type="hidden" id="attend_status" name="attend_status" value="will_attend" />
                                                            <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                                        </form>
                                                        <button <?php echo $session['wa_btn_status']; ?> onclick="func_submit_form('form_will_attend_<?php echo $session['sid']; ?>', 'Are you sure you will attend?');" class="btn btn-block btn-warning btn-sm <?php echo $session['wa_btn_status']; ?>">Will Attend</button>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if($session['is_expired'] == 1) { ?>
                                                        <button class="btn btn-block btn-success btn-sm disabled" title="Training session is expired">Attended</button>
                                                        <?php } else{ ?>
                                                        <form id="form_attended_<?php echo $session['sid']; ?>" enctype="multipart/form-data" method="post">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />
                                                            <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="<?php echo $session['sid']; ?>" />
                                                            <input type="hidden" id="user_type" name="user_type" value="<?php echo $session['user_type']; ?>" />
                                                            <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $session['user_sid']; ?>" />
                                                            <input type="hidden" id="attend_status" name="attend_status" value="attended" />
                                                            <input type="hidden" id="unique_sid" name="unique_sid" value="<?php echo isset($unique_sid) ? $unique_sid : ''; ?>" />
                                                        </form>
                                                        <button <?php echo $session['a_btn_status']; ?> onclick="func_submit_form('form_attended_<?php echo $session['sid']; ?>', 'Are you sure you want to mark this session as attended?');" class="btn btn-block btn-success btn-sm <?php echo $session['a_btn_status']; ?>">Attended</button>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="text-center" colspan="8">
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

            <?php if($users_type != 'applicant') { ?>
                <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                    <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
                <!-- </div> -->
            <?php } ?>
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