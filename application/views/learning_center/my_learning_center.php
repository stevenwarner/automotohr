<?php
    if(!$load_view){
        $watch_video_base_url = '';

        if (isset($applicant)) {
            $watch_video_base_url = base_url('onboarding/watch_video/' . $unique_sid);
        } else if (isset($employee)) {
            $watch_video_base_url = base_url('learning_center/watch_video/');
        }
        //
        $alreadyAssignedVideos = [];
?>

        <div class="main-content">
            <div class="dashboard-wrp">
                <div class="container-fluid">
                    <div class="applicant-profile-wrp">
                        <div class="row">
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
                                                                <div class="col-sm-12">
                                                                    <span class="pull-right">
                                                                        <button class="btn btn-success" id="jsAssignVideo"><i class="fa fa-plus-circle" aria-hidden="true" style="font-size: 14px;"></i>&nbsp;Assign a Video</button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                                    <br />
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-hover table-striped">
                                                                            <thead>
                                                                            <tr>
                                                                                <th scope="col">Video</th>
                                                                                <th scope="col">Watched</th>
                                                                                <th scope="col">Questionnaire</th>
                                                                                <th scope="col">Status</th>
                                                                                <th scope="col">Assigned On</th>
                                                                                <th scope="col">Start Date</th>
                                                                                <th class="text-center">Action</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php if(!empty($videos)) { ?>
                                                                                <?php foreach($videos as $video) {
                                                                                    $alreadyAssignedVideos[] = $video['sid'];
                                                                                    //
                                                                                    $status = ['success', 'Not Started'];
                                                                                    // Check for start date
                                                                                    if(
                                                                                        !empty($video['expired_start_date']) && $video['expired_start_date'] <= date('Y-m-d', strtotime('now'))
                                                                                    ){
                                                                                        $status = ['danger', 'Expired'];
                                                                                    } else if(
                                                                                        $video['video_start_date']
                                                                                        <= date('Y-m-d', strtotime('now'))
                                                                                    ){
                                                                                        $status = ['success', 'Started'];
                                                                                    }
                                                                                ?>
                                                                                    <tr data-id="<?=$video['sid'];?>">
                                                                                        <td><?php echo $video['video_title']; ?></td>
                                                                                        <td>
                                                                                            <?php if ($video['video_watched_status'] == "pending") { ?>
                                                                                                <span class="text-danger">
                                                                                                    Pending
                                                                                                </span>
                                                                                            <?php } else { ?>
                                                                                                <span class="text-success">
                                                                                                    Watched
                                                                                                </span>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($video['video_have_question'] == "yes") { ?>
                                                                                                <?php if ($video['video_question_completed'] == "pending") { ?>
                                                                                                    <span class="text-danger">Pending</span>
                                                                                                <?php } else { ?>
                                                                                                        
                                                                                                    <span class="text-success">Completed</span>
                                                                                                <?php } ?>
                                                                                            <?php } ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <strong class="text-<?=$status[0];?>"><?=$status[1];?></strong>
                                                                                        </td>
                                                                                        <td>
                                                                                        <?=reset_datetime(array( 'datetime' => $video['created_date'], '_this' => $this)); ?>
                                                                                        </td>
                                                                                        <td>
                                                                                        <?=reset_datetime(array( 'datetime' => $video['video_start_date'], '_this' => $this)); ?>
                                                                                        </td>
                                                                                        <td  align="center">
                                                                                            <button class="btn btn-danger jsRevokeVideo"><i class="fa fa-times-circle" style="font-size: 14px;" aria-hidden="true"></i>&nbsp;Revoke Video</button>
                                                                                            <a href="<?php echo base_url('learning_center/watch_video') . '/' . $video['sid'] . $watch_url; ?>" class="btn btn-success"><i class="fa fa-eye" style="font-size: 14px;" aria-hidden="true"></i>&nbsp;View Details</a>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <tr>
                                                                                    <td class="text-center" colspan="7">
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

                                                    <!-- History Panel -->
                                                    <div class="col-sm-12">
                                                        <div class="panel panel-success">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    Online Video History (<?=count($history);?>)
                                                                </h4>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-striped table-bordered">
                                                                        <caption></caption>
                                                                        <thead>
                                                                            <th scope="col">Video</th>
                                                                            <th scope="col">Watched</th>
                                                                            <th scope="col">Questionnaire</th>
                                                                            <th scope="col">Assigned On</th>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php 
                                                                                if(!empty($history)) { 
                                                                                    foreach($history as $video){
                                                                                        //
                                                                                        $videoWatched = '<span class="text-danger">Pending</span>';
                                                                                        //
                                                                                        if($video['watched']){
                                                                                            $videoWatched = '<span class="text-success">Watched <br> '.(reset_datetime(array( 'datetime' => $video['date_watched'], '_this' => $this))).'</span>';
                                                                                        }
                                                                                        //
                                                                                        $questionnaire = '<span class="text-danger">N/A</span>';
                                                                                        //
                                                                                        if(!empty($video['questionnaire_result'])){
                                                                                            //
                                                                                            $questionnaire = '<span class="text-danger">Pending</span>';
                                                                                            //
                                                                                            if($video['questionnaire_result'] == 'Pass'){
                                                                                                $questionnaire = '<span class="text-success">Pass <br>'.(reset_datetime(array( 'datetime' => $video['questionnaire_attend_timestamp'], '_this' => $this))).'</span>';
                                                                                            } else{
                                                                                                $questionnaire = '<span class="text-danger">Fail <br>'.(reset_datetime(array( 'datetime' => $video['questionnaire_attend_timestamp'], '_this' => $this))).'</span>';
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><?=$video['video_title'];?></td>
                                                                                            <td><?=$videoWatched;?></td>
                                                                                            <td><?=$questionnaire;?></td>
                                                                                            <td><?=reset_datetime(array( 'datetime' => $video['date_assigned'], '_this' => $this)); ?></td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                } else{
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <p class="alert alert-info text-center">No history found.</p>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
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
                                                                                    <td class="text-center"><a class="btn btn-block btn-success" href="<?= base_url('learning_center/view_training_session/'.$session['sid'] . $watch_url);?>">View</a></td>

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
                            <?php if(isset($left_navigation)){
                                $this->load->view($left_navigation);
                            }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--  -->
        
        <div class="modal fade" id="jsVideoModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><strong>Learning Management - Videos</strong></h4>
                    </div>
                    <div class="modal-body">
                    <?php 
                    if(!empty($video_list)){
                        foreach($video_list as $list){
                            ?>
                            <div class="csAssigneVideoBox jsAssigneVideoBox" style="border: 1px solid #ddd; padding: 10px auto;" data-id="<?=$list['sid'];?>">
                                <div class="rows">
                                    <div class="col-sm-4 col-xs-12">
                                    <?php if($list['video_source'] == 'youtube') { ?>
                                                <a href="<?php echo $watch_video_base_url . '/' . $list['sid']; ?>">
                                                    <img src="https://img.youtube.com/vi/<?php echo $list['video_id']; ?>/hqdefault.jpg" alt="" width="100%"/>
                                                </a>
                                    <?php } else if($list['video_source'] == 'vimeo') { 
                                                $thumbnail_image = vimeo_video_data($list['video_id']); ?> 
                                                <a href="<?php echo $watch_video_base_url . '/' . $list['sid']; ?>"><img src="<?php echo $thumbnail_image;?>" alt=""  width="100%"/></a>
                                    <?php   } else { ?>
                                                <a href="<?php echo $watch_video_base_url . '/' . $list['sid']; ?>">
                                                    <video id="video" width="100%" controls preload="metadata">
                                                        <source src="<?php echo base_url('assets/uploaded_videos/'.$list['video_id']); ?>" type="video/mp4">
                                                    </video>
                                                </a>
                                    <?php   } ?>
                                    </div>
                                    <div class="col-sm-8 col-xs-12">
                                        <h3>
                                            <span class="pull-right">
                                            <label class="control control--checkbox">
                                                <input type="checkbox" name="jsAssignVideoIds[]" class="jsAssignVideoIds" value="<?=$list['sid'];?>">
                                                <div class="control__indicator"></div>
                                            </label>
                                            </span>
                                            <?=$list['video_title'];?>
                                        </h3>
                                        <p><?=$list['video_description'];?></p>
                                        <p><strong>Visible on:</strong> <?=formatDate($list['video_start_date']);?></p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <?php
                        }
                    } else{
                        echo '<br /><h3 class="alert alert-info text-center">You haven\'t added any videos yet. <br /> To add new videos click the below button.<br /><br /><a href="'.(base_url('learning_center/add_online_video')).'" class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true" style="font-size: 14px;"></i>&nbsp;Create a Video</a></h3>';
                    } ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <?php if(!empty($video_list)){ ?>
                        <button type="button" class="btn btn-success jsAssignVideBTN">Assign Selected Videos</button>
                        <?php } ?>
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
            //
            $(function(){
                //
                let alreadyAssignedVideos = <?=json_encode($alreadyAssignedVideos);?>;

                //
                $('.jsRevokeVideo').click(function(event){
                    //
                    event.preventDefault();
                    //
                    var videoId = $(this).closest('tr').data('id');
                    //
                    alertify.confirm(
                        "This action will remove the <?=$user_type;?> from this video and remove the saved data.",
                        function(){
                            revokeVideoAccess(videoId);
                        }
                    ).setHeader('Confirm!').set('label', {
                        ok: "Yes",
                        cancel: "No"
                    });
                });
               
                //
                $('#jsAssignVideo').click(function(event){
                    //
                    event.preventDefault();
                    //
                    $('.jsAssignVideBTN').prop('disabled', false);
                    //
                    if(alreadyAssignedVideos.length){
                        alreadyAssignedVideos.map(function(_id){
                            $('.jsAssigneVideoBox[data-id="'+(_id)+'"]').find('.jsAssignVideoIds').prop('disabled', true);
                        });
                    }
                    //
                    $('#jsVideoModal').modal('show');
                });
                
                
                //
                $('.jsAssignVideBTN').click(function(event){
                    //
                    event.preventDefault();
                    //
                    var videoList = [] ;
                    //
                    $('.jsAssignVideoIds:checked').map(function(){
                        videoList.push($(this).val());
                    });
                    //
                    if(videoList.length === 0){
                        alertify.alert("Error!", "Please, select atleast one video.", function(){});
                        return;
                    }
                    //
                    $.post("<?=base_url('learning_center/video_access');?>", {
                        action: "assign",
                        ids: videoList,
                        userType: "<?=$user_type;?>",
                        userId: "<?=$employer_sid;?>",
                        sendEmail: $(this).data('send') !== undefined ? 'yes' : 'no'
                    }).done(function(resp){
                        if(resp == 'success'){
                            alertify.alert('Success!', 'You have successfully assigned the videos.', function(){
                                window.location.reload();
                            });
                        } else{
                            alertify.alert('Error!','Something went wrong while assigning videos.')
                        }
                    });
                });

                //
                function revokeVideoAccess(videoId){
                    $.post("<?=base_url("learning_center/video_access");?>", {
                        action: 'revoke',
                        userId: <?=$employer_sid;?>,
                        userType: "<?=$user_type;?>",
                        videoId: videoId
                    }).done(function(resp){
                        //
                        if(resp == 'success'){
                            alertify.alert('Success!', "You have successfully removed this <?=$user_type;?> from video.", function(){
                                window.location.reload();
                            });
                        } else{
                            alertify.alert('Warning!', "Something went wrong. Please, try again in a few moments.");
                        }
                    });
                }
            });
        </script>
    <?php }else{
        $this->load->view('learning_center/my_learning_center_new');
    }