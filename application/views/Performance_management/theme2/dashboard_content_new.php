<?php if($load_view){

    $panelHeading='background-color: #3554DC';

}else{
    $panelHeading='background-color: #81b431';
}
?>
<div class="col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">

    <!-- Assigned -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="<?=$panelHeading?>">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        Reviews Assigned To Me - Reviewer
                    </h5>
                </div>
                <div class="col-md-3 col-sm-12">
                    <?php
                     if(!empty($AssignedReviews)){
                    ?>
                    <span class="pull-right">
                        <a href="<?=purl("reviews/all");?>" class="btn btn-orange"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View
                            Review(s)</a>
                    </span>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF14 csInfo csB7"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Assigned reviews on which your feedback is required. The submitted feedback will be shared with the reporting manager(s).</p>
                </div>
            </div>
            <br>
            <?php
                //
                if(!empty($AssignedReviews)){
                    ?>
                    <div class="row">
                        <?php
                        $now = date('Y-m-d', strtotime('now'));
                    foreach($AssignedReviews as $review){
                        ?>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="csEmployeeBox">
                                    <figure>
                                        <img src="<?=($company_employees_index[$review['reviewee_sid']]['Image']);?>" class="csRadius50" alt="" />
                                        <div class="csTextBox">
                                            <p class="csF14 csB7 mb0"><?=$company_employees_index[$review['reviewee_sid']]['Name'];?></p>
                                            <p class="csTextSmall mb0 csF14"> <?=$company_employees_index[$review['reviewee_sid']]['Role'];?></p>
                                            <p class="csTextSmall csF14">Due in <?=dateDifferenceInDays($now, $review['end_date'], '%a');?> day(s)</p>
                                            <p class="csTextSmall csF14">
                                                <a href="<?=purl("review/{$review['sid']}/{$review['reviewee_sid']}/{$review['reviewer_sid']}");?>" class="btn btn-orange csF14">Start Review</a>
                                            </p>
                                        </div>
                                    </figure>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        <?php
                    }
                    ?>
                    </div>
                    <?php
                } else{
                    ?>
                    <div class="panel-body">
                        <div class="row">
                            <p class="csF16 csB7 text-center">
                                No reviews are assigned to you for feedback.
                            </p>
                        </div>
                    </div>
                    <?php
                }
            ?>
           
        </div>
    </div>

    <!-- Feedback -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="<?=$panelHeading?>">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="feedback_reviews">
                        Reviews Assigned To Me - Reporting Manager
                    </h5>
                </div>
                <div class="col-md-3 col-sm-12">
                <?php
                     if(!empty($FeedbackReviews)){
                    ?>
                    <span class="pull-right">
                        <a href="<?=purl("feedbacks/all");?>" class="btn btn-orange csF16"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View
                            Review(s)</a>
                    </span>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="panel-body">
            <?php
            //
            if(!empty($FeedbackReviews)){
                ?>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF14 csInfo csB7"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Assigned reviews on which your feedback is required. The submitted feedback will be shared with the employee.</p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <?php
                    $now = date('Y-m-d', strtotime('now'));
                foreach($FeedbackReviews as $review){
                    ?>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="csEmployeeBox">
                                <figure>
                                    <img src="<?=($company_employees_index[$review['reviewee_sid']]['Image']);?>" class="csRadius50" alt="" />
                                    <div class="csTextBox">
                                        <p class="csF14 csB7 mb0"><?=$company_employees_index[$review['reviewee_sid']]['Name'];?></p>
                                        <p class="csTextSmall mb0 csF14"> <?=$company_employees_index[$review['reviewee_sid']]['Role'];?></p>
                                        <p class="csTextSmall csF14">Due in <?=dateDifferenceInDays($now, $review['end_date'], '%a');?> day(s)</p>
                                        <p class="csTextSmall csF14">
                                            <a href="<?=purl("feedback/{$review['sid']}/{$review['reviewee_sid']}/{$review['reviewer_sid']}");?>" class="btn btn-orange csF14">Start Review</a>
                                        </p>
                                    </div>
                                </figure>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    <?php
                }
                ?>
                </div>
                <?php
            } else{
                ?>
                
                    <div class="row">
                        <p class="csF16 csB7 text-center">
                            No reviews are assigned to you for feedback.
                        </p>
                    </div>
                <?php
            }
        ?>
        </div>
    </div>

    <!-- Goals -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="<?=$panelHeading?>">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        My Goal(s)
                    </h5>
                </div>
                <div class="col-md-3 col-sm-12">
                    <span class="pull-right">
                        <a href="<?=purl('goals');?>" class="btn btn-orange"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View My
                            Goal(s)</a>
                    </span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="panel-body">
            <div class="row">
                <?php
                    if(!empty($MyGoals)){
                        foreach($MyGoals as $goal){
                            //
                            $g_target = $goal['target'];
                            $g_target_completed = $goal['completed_target'];
                            $g_target_completed_percentage = ceil($g_target_completed * $g_target / 100);
                            $g_target_sign = 'V';
                            //
                            if($goal['goal_type'] == '1'){
                                $g_target_sign= '%';
                            } else if($goal['goal_type'] == '3'){
                                $g_target_sign= '$';
                            }else if($goal['goal_type'] == '4'){
                                $g_target_sign= $goal['custom_measure_type'];
                            }
                            ?>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <!-- Box 1 -->
                                <div class="csGoalBox">
                                    <div class="csPageBox csRadius5 jsGoalBox" data-id="<?=$goal['sid'];?>">
                                        <!-- Loader -->
                                        <div class="csIPLoader jsIPLoader dn" data-page="goal_box_<?=$goal['sid'];?>">
                                            <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
                                        </div> 
                                        <!-- HEADER -->
                                        <div class="csPageHeader bbb p10"> 
                                            <span class="pull-right"> 
                                                <?php 
                                                    if($goal['status'] == 0){
                                                        ?>
                                                        <button class="btn btn-black btn-xs mt0 jsGoalStatusOpen jsPopover" title="" data-original-title="Open this goal">
                                                            <i class="fa csF16 csB7 fa-check mr0" aria-hidden="true"></i>
                                                        </button>
                                                        <?php
                                                    } else{
                                                        ?>
                                                        <button class="btn btn-black btn-xs mt0 jsGoalStatusClose jsPopover" title="" data-original-title="Close this goal">
                                                            <i class="fa csF16 csB7 fa-times-circle mr0" aria-hidden="true"></i>
                                                        </button> 
                                                        <?php
                                                    }
                                                ?>
                                                <button class="btn btn-black btn-xs mt0 jsGoalUpdateBTN jsPopover" title="" data-original-title="Update Goal">
                                                    <i class="fa csF16 csB7 fa-pencil mr0" aria-hidden="true"></i>
                                                </button> 
                                                <button class="btn btn-black btn-xs mt0 jsGoalHistory dn jsPopover" title="" data-original-title="Show history">
                                                    <i class="fa csF16 csB7 fa-history mr0" aria-hidden="true"></i>
                                                </button> 
                                                <button class="btn btn-black btn-xs mt0 jsGoalCommentBtn jsPopover" title="" data-original-title="Comments">
                                                    <i class="fa csF16 csB7 fa-comment mr0" aria-hidden="true"></i>
                                                </button> 
                                                <button class="btn btn-black btn-xs mt0 jsEditVisibility dn jsPopover" title="" data-original-title="Edit Visibility">
                                                    <i class="fa csF16 csB7 fa-users mr0" aria-hidden="true"></i>
                                                </button> 
                                                <button class="btn btn-xs btn-black mt0 jsExpandGoal jsPopover" title="" placement="auto" data-original-title="Expand Goal">
                                                    <i class="fa fa-expand csF16 csB7" area-hidden="true"  aria-hidden="true"></i>
                                                </button>
                                            </span>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="csPageHeader bbb pl10 pr10">
                                            <h3 class="csF16 csB7"><?=$goal['title'];?></h3>
                                        </div> <!-- Main screen -->
                                        <div class="csPageSection jsBoxSection" data-key="main">
                                            <!-- BODY -->
                                            <div class="csPageBody csGoalBoxH p10">
                                                <!--  -->
                                                <div class="row">
                                                    <!-- Employee -->
                                                    <div class="col-sm-12 col-xs-12">
                                                        <div class="csEBox">
                                                            <figure> 
                                                                <img src="<?=AWS_S3_BUCKET_URL.$employee['profile_picture'];?>" alt="">
                                                            </figure>
                                                            <div class="csEBoxText">
                                                                <p class="mb0 ma10 csF14 csB7"><?=ucwords($employee['first_name'].' '.$employee['last_name']);?></p>
                                                            </div>
                                                        </div>
                                                    </div> <!-- Track Row -->
                                                    <div class="col-sm-12 col-xs-12">
                                                        <div>
                                                            <h4 class="mb0 csF14 csB7 <?=$goal['on_track'] == 1 ? "csYes" : "csNo";?>"><?=$goal['on_track'] == 1 ? "On Track" : "Off Track";?></h4>
                                                            <p class="ma0 csF14">As Of <?=date(DATE);?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <p class="csF14 csB7 pull-right"> <?=$g_target_sign;?> <?=$g_target_completed;?> / <?=$g_target_sign;?> <?=$g_target;?> </p>
                                                    </div>
                                                    <div class="col-sm-12 col-xs-12">
                                                        <div class="progress">
                                                            <div class="progress-bar" style="width:  <?=$g_target_completed;?>%;"></div>
                                                            <p class="text-center"><?=$g_target_completed;?>% completed</p>
                                                        </div>
                                                        <div class="row ma10">
                                                            <div class="col-sm-6">
                                                                <p class="csF14"><?=formatDateToDB($goal['start_date'], 'Y-m-d', DATE);?><br> Start Date </p>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <p class="text-right csF14"><?=formatDateToDB($goal['end_date'], 'Y-m-d', DATE);?><br> Due Date</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-xs-12">
                                                        <h5 class="csF14"><?=$goal['description'];?></h5>
                                                    </div>
                                                </div>
                                            </div> <!-- FOOTER -->
                                        </div> 
                                        <!-- Comment screen -->
                                        <div class="csPageSection jsBoxSection dn" data-key="comment">
                                            <!-- BODY -->
                                            <div class="csPageBody csGoalBoxH">
                                                <ul class="csChatMenu jsGoalCommentWrap<?=$goal['sid'];?>">
                                                </ul>
                                            </div> <!-- FOOTER -->
                                            <div class="csPageFooter bbt p10">
                                                <div class="row">
                                                    <div class="col-sm-8 col-xs-12"> <textarea class="form-control jsGoalComment<?=$goal['sid'];?>"
                                                            placeholder="John Doe has completed his tasks."></textarea> </div>
                                                    <div class="col-sm-4 col-xs-12"> <button
                                                            class="btn btn-orange form-control jsGoalCommentSaveBtn"><i
                                                                class="fa fa-save"></i> </button> <button
                                                            class="btn btn-black form-control jsBoxSectionBackBtn" data-to="main"><i
                                                                class="fa fa-times"></i> </button> </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <!-- Update -->
                                        <div class="csPageSection jsBoxSection dn" data-key="update">
                                            <!-- BODY -->
                                            <div class="csPageBody p10 csGoalBoxH">
                                                <div class="row">
                                                    <div class="col-sm-5 col-xs-12">
                                                        <h4><strong>Status</strong></h4>
                                                    </div>
                                                    <div class="col-sm-7 col-xs-12"> <select class="jsGoalTrack<?=$goal['sid'];?>">
                                                            <option value="1" <?=$goal['on_track'] == 1 ? "selected" : "";?>>On Track</option>
                                                            <option value="0" <?=$goal['on_track'] == 0 ? "selected" : "";?>>Off Track</option>
                                                        </select> </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-5 col-xs-12">
                                                        <h4><strong>Completed Target</strong></h4>
                                                    </div>
                                                    <div class="col-sm-7 col-xs-12"> <input type="text"
                                                            class="form-control jsGoalCompletedTarget<?=$goal['sid'];?>" value=" <?=$g_target_completed;?>"> </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-5 col-xs-12">
                                                        <h4><strong>Target</strong></h4>
                                                    </div>
                                                    <div class="col-sm-7 col-xs-12"> <input type="text"
                                                            class="form-control jsGoalTarget<?=$goal['sid'];?>" value=" <?=$g_target;?>"> </div>
                                                </div>
                                            </div> <!-- FOOTER -->
                                            <div class="csPageFooter bbt p10">
                                                <div class="row">
                                                    <div class="col-sm-6 col-xs-12"> <button
                                                            class="btn btn-orange form-control jsGoalUpdateBtn"><i
                                                                class="fa fa-save"></i> Save</button> </div>
                                                    <div class="col-sm-6 col-xs-12"> <button
                                                            class="btn btn-black form-control jsBoxSectionBackBtn" data-to="main"><i
                                                                class="fa fa-times"></i> Cancel</button> </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <!-- Visibility -->
                                        <div class="csPageSection jsBoxSection dn" data-key="visibility">
                                            <!-- BODY -->
                                            <div class="csPageBody p10 csGoalBoxH">
                                                <div class="row">
                                                    <div class="col-sm-12 col-xs-12">
                                                        <h4><strong>Employees</strong></h4>
                                                    </div>
                                                    <div class="col-sm-12 col-xs-12"> <select></select> </div>
                                                </div>
                                            </div> <!-- FOOTER -->
                                            <div class="csPageFooter bbt p10">
                                                <div class="row">
                                                    <div class="col-sm-6 col-xs-12"> <button class="btn btn-orange form-control"><i
                                                                class="fa fa-save"></i> Save</button> </div>
                                                    <div class="col-sm-6 col-xs-12"> <button class="btn btn-black form-control"><i
                                                                class="fa fa-times"></i> Cancel</button> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else{
                        ?>
                        <div class="col-sm-12">
                        <p class="csF16 csB7 text-center">
                            <i class="fa fa-check csF40" aria-hidden="true"></i><br />
                            You haven't created any goals yet
                        </p>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>