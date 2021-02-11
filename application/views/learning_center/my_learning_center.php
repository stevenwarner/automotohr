<?php
    if(!$load_view){?>

        <div class="main-content">
            <div class="dashboard-wrp">
                <div class="container-fluid">
                    <div class="applicant-profile-wrp">
                        <div class="row">
    <!--                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">-->
    <!--                            --><?php //$this->load->view('main/employer_column_left_view'); ?>
    <!--                        </div>-->
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                                <?php if($top_view) {
                                    $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top');
                                }
                                else { ?>
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
                                                           type="number" name="rating" class="rating" min=0 max=5
                                                           step=0.2
                                                           data-size="xs">
                                                </div>
                                                <?php if (check_blue_panel_status() && $applicant_info['is_onboarding'] == 1) { ?>
                                                    <span class="badge" style="padding:8px; background-color: red;">Onboarding Request Sent</span>
                                                <?php } else { ?>
                                                    <span class=""
                                                          style="padding:8px;"><?php echo $applicant_info["applicant_type"]; ?></span>
                                                <?php } ?>
                                            </div>
                                        </article>
                                    </div>
                                <?php }?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="page-header-area">
<!--                                            <span class="page-heading down-arrow">-->
<!--                                                <a class="dashboard-link-btn" href="--><?php //echo base_url('my_profile'); ?><!--"><i class="fa fa-chevron-left"></i>Profile</a>-->
<!--                                                --><?php //echo $title; ?>
<!--                                            </span>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-12">
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
                                                                                            <a href="<?php echo base_url('learning_center/watch_video') . '/' . $video['sid'] . $watch_url; ?>" class="btn btn-block btn-success">Watch</a>
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
                                                                            <th rowspan="2" class="col-xs-2 text-center valign-middle">Date</th>
                                                                            <th colspan="2" class="col-xs-1 text-center valign-middle">Time</th>
                                                                            <th rowspan="2" class="col-xs-1 text-center valign-middle">Session Status</th>
<!--                                                                            <th rowspan="2" class="col-xs-1 text-center valign-middle">Attend Status</th>-->
                                                                            <th rowspan="2" class="col-xs-3 text-center valign-middle" colspan="3">Actions</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php if(!empty($assigned_sessions)) { ?>
                                                                            <?php foreach($assigned_sessions as $session) { ?>
                                                                                <tr>
                                                                                    <td><?php echo $session['session_topic']; ?></td>
                                                                                    <td class="text-center"><?=reset_datetime(array( 'datetime' => $session['session_date'], '_this' => $this)); ?></td>
                                                                                    <td class="text-center"><?=reset_datetime(array( 'datetime' => $session['session_date'].' '.$session['session_start_time'], '_this' => $this, 'format' => 'H:i')); ?></td>
                                                                                    <td class="text-center"><?=reset_datetime(array( 'datetime' => $session['session_date'].' '.$session['session_end_time'], '_this' => $this, 'format' => 'H:i')); ?></td>
                                                                                    <td class="text-center"><?php echo ucwords($session['session_status']);?></td>
                                                                                    <!--                                                            <td class="text-center">--><?php //echo ucwords(str_replace('_', ' ', $session['attend_status']));?><!--</td>-->
                                                                                    <td class="text-center"><a class="btn btn-block btn-success" href="<?= base_url('learning_center/view_training_session/'.$session['sid'] . $watch_url);?>">View</a></td>

                                                                                </tr>
<!--                                                                                <tr>-->
<!--                                                                                    <td>--><?php //echo $session['session_topic']; ?><!--</td>-->
<!--                                                                                    <td class="text-center">--><?php //echo date('m-d-Y', strtotime($session['session_date'])); ?><!--</td>-->
<!--                                                                                    <td class="text-center">--><?php //echo date('H:i', strtotime($session['session_start_time'])); ?><!--</td>-->
<!--                                                                                    <td class="text-center">--><?php //echo date('H:i', strtotime($session['session_end_time'])); ?><!--</td>-->
<!--                                                                                    <td class="text-center">--><?php //echo ucwords($session['session_status']);?><!--</td>-->
<!--                                                                                    <td class="text-center">--><?php //echo ucwords(str_replace('_', ' ', isset($session['attend_status']) ? $session['attend_status'] : 'pending'));?><!--</td>-->
<!--                                                                                    <td>-->
<!--                                                                                        <form id="form_unable_to_attend_--><?php //echo $session['sid']; ?><!--" enctype="multipart/form-data" method="post">-->
<!--                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />-->
<!--                                                                                            <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="--><?php //echo $session['sid']; ?><!--" />-->
<!--                                                                                            <input type="hidden" id="user_type" name="user_type" value="--><?php //echo $session['user_type']; ?><!--" />-->
<!--                                                                                            <input type="hidden" id="user_sid" name="user_sid" value="--><?php //echo $session['user_sid']; ?><!--" />-->
<!--                                                                                            <input type="hidden" id="attend_status" name="attend_status" value="unable_to_attend" />-->
<!--                                                                                            <input type="hidden" id="unique_sid" name="unique_sid" value="--><?php //echo isset($unique_sid) ? $unique_sid : ''; ?><!--" />-->
<!--                                                                                        </form>-->
<!--                                                                                        <button --><?php //echo $session['uta_btn_status']; ?><!-- onclick="func_submit_form('form_unable_to_attend_--><?php //echo $session['sid']; ?><!--', 'Are you sure you are unable to attend?');" class="btn btn-block btn-danger btn-sm <?php //echo $session['uta_btn_status']; ?><!--">Unable To Attend</button>-->
<!--                                                                                    </td>-->
<!--                                                                                    <td>-->
<!--                                                                                        <form id="form_will_attend_--><?php //echo $session['sid']; ?><!--" enctype="multipart/form-data" method="post">-->
<!--                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />-->
<!--                                                                                            <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="--><?php //echo $session['sid']; ?><!--" />-->
<!--                                                                                            <input type="hidden" id="user_type" name="user_type" value="--><?php //echo $session['user_type']; ?><!--" />-->
<!--                                                                                            <input type="hidden" id="user_sid" name="user_sid" value="--><?php //echo $session['user_sid']; ?><!--" />-->
<!--                                                                                            <input type="hidden" id="attend_status" name="attend_status" value="will_attend" />-->
<!--                                                                                            <input type="hidden" id="unique_sid" name="unique_sid" value="--><?php //echo isset($unique_sid) ? $unique_sid : ''; ?><!--" />-->
<!--                                                                                        </form>-->
<!--                                                                                        <button --><?php //echo $session['wa_btn_status']; ?><!-- onclick="func_submit_form('form_will_attend_--><?php //echo $session['sid']; ?><!--', 'Are you sure you will attend?');" class="btn btn-block btn-warning btn-sm <?php //echo $session['wa_btn_status']; ?><!--">Will Attend</button>-->
<!--                                                                                    </td>-->
<!--                                                                                    <td>-->
<!--                                                                                        <form id="form_attended_--><?php //echo $session['sid']; ?><!--" enctype="multipart/form-data" method="post">-->
<!--                                                                                            <input type="hidden" id="perform_action" name="perform_action" value="mark_attend_status" />-->
<!--                                                                                            <input type="hidden" id="session_assignment_sid" name="session_assignment_sid" value="--><?php //echo $session['sid']; ?><!--" />-->
<!--                                                                                            <input type="hidden" id="user_type" name="user_type" value="--><?php //echo $session['user_type']; ?><!--" />-->
<!--                                                                                            <input type="hidden" id="user_sid" name="user_sid" value="--><?php //echo $session['user_sid']; ?><!--" />-->
<!--                                                                                            <input type="hidden" id="attend_status" name="attend_status" value="attended" />-->
<!--                                                                                            <input type="hidden" id="unique_sid" name="unique_sid" value="--><?php //echo isset($unique_sid) ? $unique_sid : ''; ?><!--" />-->
<!--                                                                                        </form>-->
<!--                                                                                        <button --><?php //echo $session['a_btn_status']; ?><!-- onclick="func_submit_form('form_attended_--><?php //echo $session['sid']; ?><!--', 'Are you sure you want to mark this session as attended?');" class="btn btn-block btn-success btn-sm <?php //echo $session['a_btn_status']; ?><!--">Attended</button>-->
<!--                                                                                    </td>-->
<!--                                                                                </tr>-->
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
                            <?php if(isset($left_navigation)){
                                $this->load->view($left_navigation);
                            }?>
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
    <?php }else{
        $this->load->view('learning_center/my_learning_center_new');
    }