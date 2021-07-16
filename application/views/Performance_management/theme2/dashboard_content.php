<div class="col-md-9 col-sm-12">
    <!-- Assigned -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="background-color: #3554DC;">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        Assigned Reviews <i class="fa fa-question-circle-o" aria-hidden="true"
                            title="Click to see help." placement="top"></i>
                    </h5>
                </div>
                <div class="col-md-3 col-sm-12">
                    <span class="pull-right">
                        <a href="<?=purl("reviews");?>" class="btn btn-orange"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View
                            Review(s)</a>
                    </span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF14 csW dn jsToggleHelpArea" data-help="assigned_reviews">All the assigned reviews, on
                        which your feedback is required. The submitted feedback will be shared with the reporting
                        manager(s).</p>
                </div>
            </div>
        </div>

        <div class="panel-body">
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
                                        <img src="<?=getImageURL($employee['profile_picture']);?>" class="csRadius50" alt="" />
                                        <div class="csTextBox">
                                            <p class="csF14 csB7 mb0"><?=ucwords($employee['first_name'].' '.$employee['last_name']);?></p>
                                            <p class="csTextSmall mb0 csF14"> <?=remakeEmployeeName($employee, false);?></p>
                                            <p class="csTextSmall csF14">Due in <?=dateDifferenceInDays($now, $review['start_date'], '%a');?> day(s)</p>
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
                            <p class="csF26 csB7 text-center">
                                <i class="fa fa-check csF40" aria-hidden="true"></i><br />
                                You are all caught up
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
        <div class="panel-heading" style="background-color: #3554DC;">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        Feedback Reviews <i class="fa fa-question-circle-o" aria-hidden="true"
                            title="Click to see help." placement="top"></i>
                    </h5>
                </div>
                <div class="col-md-3 col-sm-12">
                    <span class="pull-right">
                        <a href="<?=purl("reviews");?>" class="btn btn-orange csF16"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View
                            Review(s)</a>
                    </span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF14 csW dn jsToggleHelpArea" data-help="assigned_reviews">All the assigned reviews, on
                        which your feedback is required. The submitted feedback will be shared with the employee.</p>
                </div>
            </div>
        </div>
    <?php
            //
            if(!empty($FeedbackReviews)){
                ?>
                <div class="panel-body">
                <div class="row">
                    <?php
                    $now = date('Y-m-d', strtotime('now'));
                foreach($FeedbackReviews as $review){
                    ?>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="csEmployeeBox">
                                <figure>
                                    <img src="<?=getImageURL($employee['profile_picture']);?>" class="csRadius50" alt="" />
                                    <div class="csTextBox">
                                        <p class="csF14 csB7 mb0"><?=ucwords($employee['first_name'].' '.$employee['last_name']);?></p>
                                        <p class="csTextSmall mb0 csF14"> <?=remakeEmployeeName($employee, false);?></p>
                                        <p class="csTextSmall csF14">Due in <?=dateDifferenceInDays($now, $review['start_date'], '%a');?> day(s)</p>
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
                </div></div>
                <?php
            } else{
                ?>
                <div class="panel-body">
                    <div class="row">
                        <p class="csF26 csB7 text-center">
                            <i class="fa fa-check csF40" aria-hidden="true"></i><br />
                            You are all caught up
                        </p>
                    </div>
                </div>
                <?php
            }
        ?>
    </div>

    <!-- Goals -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="background-color: #3554DC;">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        My Goal(s) <i class="fa fa-question-circle-o" aria-hidden="true" title="Click to see help."
                            placement="top"></i>
                    </h5>
                </div>
                <div class="col-md-3 col-sm-12">
                    <span class="pull-right">
                        <a href="" class="btn btn-orange"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View My
                            Goal(s)</a>
                    </span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF14 csW dn jsToggleHelpArea" data-help="assigned_reviews">All the assigned reviews, on
                        which your feedback is required. The submitted feedback will be shared with the reporting
                        manager(s).</p>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <!-- Box 1 -->
                    <div class="csGoalBox">
                        <div class="csPageBox csRadius5 jsGoalBox" data-id="7">
                            <!-- Loader -->
                            <div class="csIPLoader jsIPLoader dn" data-page="goal_box">
                                <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
                            </div> 
                            <!-- HEADER -->
                            <div class="csPageHeader bbb p10"> 
                                <span class="pull-right"> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalStatusClose jsPopover" title="" data-original-title="Close this goal">
                                        <i class="fa csF16 csB7 fa-times-circle mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalUpdateBTN jsPopover" title="" data-original-title="Update Goal">
                                        <i class="fa csF16 csB7 fa-pencil mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalHistory jsPopover" title="" data-original-title="Show history">
                                        <i class="fa csF16 csB7 fa-history mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalCommentBtn jsPopover" title="" data-original-title="Comments">
                                        <i class="fa csF16 csB7 fa-comment mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsEditVisibility jsPopover" title="" data-original-title="Edit Visibility">
                                        <i class="fa csF16 csB7 fa-users mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-xs btn-black mt0 jsExpandGoal jsPopover" title="" placement="auto" data-original-title="Expand Goal">
                                        <i class="fa fa-expand csF16 csB7" area-hidden="true"  aria-hidden="true"></i>
                                    </button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                            <div class="csPageHeader bbb pl10 pr10">
                                <h3 class="csF16 csB7"> Goal for admin 1 </h3>
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
                                                    <img src="https://automotohrattachments.s3.amazonaws.com/8578-logo--nZ1.jpg" alt="">
                                                </figure>
                                                <div class="csEBoxText">
                                                    <p class="mb0 ma10 csF14 csB7">John Doe</p>
                                                    <p class="mb0 csF14">[Admin Plus]</p>
                                                </div>
                                            </div>
                                        </div> <!-- Track Row -->
                                        <div class="col-sm-12 col-xs-12">
                                            <div>
                                                <h4 class="mb0 csF14 csB7 csYes">OnTrack</h4>
                                                <p class="ma0 csF14">As Of Jun 14 2021, Mon</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="csF14 csB7 pull-right"> $0 / $33 </p>
                                        </div>
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar" style="width:  0%;"></div>
                                                <p class="text-center">0% completed</p>
                                            </div>
                                            <div class="row ma10">
                                                <div class="col-sm-6">
                                                    <p class="csF14">Apr 30 2021, Fri<br> Start Date </p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="text-right csF14">Apr 30 2021, Fri<br> Due Date</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <h5 class="csF14">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos libero odio laborum voluptates corrupti. Ut ipsam, quam quod ex voluptate repellat possimus voluptatibus eligendi illum ad dolor beatae fuga saepe.</h5>
                                        </div>
                                    </div>
                                </div> <!-- FOOTER -->
                            </div> <!-- Comment screen -->
                            <div class="csPageSection jsBoxSection dn" data-key="comment">
                                <!-- BODY -->
                                <div class="csPageBody csGoalBoxH">
                                    <ul class="csChatMenu jsGoalCommentWrap"></ul>
                                </div> <!-- FOOTER -->
                                <div class="csPageFooter bbt p10">
                                    <div class="row">
                                        <div class="col-sm-8 col-xs-12"> <textarea class="form-control jsGoalComment"
                                                placeholder="John Doe has completed his tasks."></textarea> </div>
                                        <div class="col-sm-4 col-xs-12"> <button
                                                class="btn btn-orange form-control jsGoalCommentSaveBtn"><i
                                                    class="fa fa-save"></i> </button> <button
                                                class="btn btn-black form-control jsBoxSectionBackBtn" data-to="main"><i
                                                    class="fa fa-times"></i> </button> </div>
                                    </div>
                                </div>
                            </div> <!-- Update -->
                            <div class="csPageSection jsBoxSection dn" data-key="update">
                                <!-- BODY -->
                                <div class="csPageBody p10 csGoalBoxH">
                                    <div class="row">
                                        <div class="col-sm-5 col-xs-12">
                                            <h4><strong>Status</strong></h4>
                                        </div>
                                        <div class="col-sm-7 col-xs-12"> <select class="jsGoalTrack">
                                                <option value="1">On Track</option>
                                                <option value="0">Off Track</option>
                                            </select> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 col-xs-12">
                                            <h4><strong>Completed Target</strong></h4>
                                        </div>
                                        <div class="col-sm-7 col-xs-12"> <input type="text"
                                                class="form-control jsGoalCompletedTarget"> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 col-xs-12">
                                            <h4><strong>Target</strong></h4>
                                        </div>
                                        <div class="col-sm-7 col-xs-12"> <input type="text"
                                                class="form-control jsGoalTarget" value=""> </div>
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
                            </div> <!-- Visibility -->
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

                <div class="col-md-4 col-sm-4 col-xs-12">
                    <!-- Box 1 -->
                    <div class="csGoalBox">
                        <div class="csPageBox csRadius5 jsGoalBox" data-id="7">
                            <!-- Loader -->
                            <div class="csIPLoader jsIPLoader dn" data-page="goal_box">
                                <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
                            </div> 
                            <!-- HEADER -->
                            <div class="csPageHeader bbb p10"> 
                                <span class="pull-right"> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalStatusClose jsPopover" title="" data-original-title="Close this goal">
                                        <i class="fa csF16 csB7 fa-times-circle mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalUpdateBTN jsPopover" title="" data-original-title="Update Goal">
                                        <i class="fa csF16 csB7 fa-pencil mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalHistory jsPopover" title="" data-original-title="Show history">
                                        <i class="fa csF16 csB7 fa-history mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalCommentBtn jsPopover" title="" data-original-title="Comments">
                                        <i class="fa csF16 csB7 fa-comment mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsEditVisibility jsPopover" title="" data-original-title="Edit Visibility">
                                        <i class="fa csF16 csB7 fa-users mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-xs btn-black mt0 jsExpandGoal jsPopover" title="" placement="auto" data-original-title="Expand Goal">
                                        <i class="fa fa-expand csF16 csB7" area-hidden="true"  aria-hidden="true"></i>
                                    </button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                            <div class="csPageHeader bbb pl10 pr10">
                                <h3 class="csF16 csB7"> Goal for admin 1 </h3>
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
                                                    <img src="https://automotohrattachments.s3.amazonaws.com/8578-logo--nZ1.jpg" alt="">
                                                </figure>
                                                <div class="csEBoxText">
                                                    <p class="mb0 ma10 csF14 csB7">John Doe</p>
                                                    <p class="mb0 csF14">[Admin Plus]</p>
                                                </div>
                                            </div>
                                        </div> <!-- Track Row -->
                                        <div class="col-sm-12 col-xs-12">
                                            <div>
                                                <h4 class="mb0 csF14 csB7 csYes">OnTrack</h4>
                                                <p class="ma0 csF14">As Of Jun 14 2021, Mon</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="csF14 csB7 pull-right"> $0 / $33 </p>
                                        </div>
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar" style="width:  0%;"></div>
                                                <p class="text-center">0% completed</p>
                                            </div>
                                            <div class="row ma10">
                                                <div class="col-sm-6">
                                                    <p class="csF14">Apr 30 2021, Fri<br> Start Date </p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="text-right csF14">Apr 30 2021, Fri<br> Due Date</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <h5 class="csF14">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos libero odio laborum voluptates corrupti. Ut ipsam, quam quod ex voluptate repellat possimus voluptatibus eligendi illum ad dolor beatae fuga saepe.</h5>
                                        </div>
                                    </div>
                                </div> <!-- FOOTER -->
                            </div> <!-- Comment screen -->
                            <div class="csPageSection jsBoxSection dn" data-key="comment">
                                <!-- BODY -->
                                <div class="csPageBody csGoalBoxH">
                                    <ul class="csChatMenu jsGoalCommentWrap"></ul>
                                </div> <!-- FOOTER -->
                                <div class="csPageFooter bbt p10">
                                    <div class="row">
                                        <div class="col-sm-8 col-xs-12"> <textarea class="form-control jsGoalComment"
                                                placeholder="John Doe has completed his tasks."></textarea> </div>
                                        <div class="col-sm-4 col-xs-12"> <button
                                                class="btn btn-orange form-control jsGoalCommentSaveBtn"><i
                                                    class="fa fa-save"></i> </button> <button
                                                class="btn btn-black form-control jsBoxSectionBackBtn" data-to="main"><i
                                                    class="fa fa-times"></i> </button> </div>
                                    </div>
                                </div>
                            </div> <!-- Update -->
                            <div class="csPageSection jsBoxSection dn" data-key="update">
                                <!-- BODY -->
                                <div class="csPageBody p10 csGoalBoxH">
                                    <div class="row">
                                        <div class="col-sm-5 col-xs-12">
                                            <h4><strong>Status</strong></h4>
                                        </div>
                                        <div class="col-sm-7 col-xs-12"> <select class="jsGoalTrack">
                                                <option value="1">On Track</option>
                                                <option value="0">Off Track</option>
                                            </select> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 col-xs-12">
                                            <h4><strong>Completed Target</strong></h4>
                                        </div>
                                        <div class="col-sm-7 col-xs-12"> <input type="text"
                                                class="form-control jsGoalCompletedTarget"> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 col-xs-12">
                                            <h4><strong>Target</strong></h4>
                                        </div>
                                        <div class="col-sm-7 col-xs-12"> <input type="text"
                                                class="form-control jsGoalTarget" value=""> </div>
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
                            </div> <!-- Visibility -->
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

                <div class="col-md-4 col-sm-4 col-xs-12">
                    <!-- Box 1 -->
                    <div class="csGoalBox">
                        <div class="csPageBox csRadius5 jsGoalBox" data-id="7">
                            <!-- Loader -->
                            <div class="csIPLoader jsIPLoader dn" data-page="goal_box">
                                <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
                            </div> 
                            <!-- HEADER -->
                            <div class="csPageHeader bbb p10"> 
                                <span class="pull-right"> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalStatusClose jsPopover" title="" data-original-title="Close this goal">
                                        <i class="fa csF16 csB7 fa-times-circle mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalUpdateBTN jsPopover" title="" data-original-title="Update Goal">
                                        <i class="fa csF16 csB7 fa-pencil mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalHistory jsPopover" title="" data-original-title="Show history">
                                        <i class="fa csF16 csB7 fa-history mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsGoalCommentBtn jsPopover" title="" data-original-title="Comments">
                                        <i class="fa csF16 csB7 fa-comment mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-black btn-xs mt0 jsEditVisibility jsPopover" title="" data-original-title="Edit Visibility">
                                        <i class="fa csF16 csB7 fa-users mr0" aria-hidden="true"></i>
                                    </button> 
                                    <button class="btn btn-xs btn-black mt0 jsExpandGoal jsPopover" title="" placement="auto" data-original-title="Expand Goal">
                                        <i class="fa fa-expand csF16 csB7" area-hidden="true"  aria-hidden="true"></i>
                                    </button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                            <div class="csPageHeader bbb pl10 pr10">
                                <h3 class="csF16 csB7"> Goal for admin 1 </h3>
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
                                                    <img src="https://automotohrattachments.s3.amazonaws.com/8578-logo--nZ1.jpg" alt="">
                                                </figure>
                                                <div class="csEBoxText">
                                                    <p class="mb0 ma10 csF14 csB7">John Doe</p>
                                                    <p class="mb0 csF14">[Admin Plus]</p>
                                                </div>
                                            </div>
                                        </div> <!-- Track Row -->
                                        <div class="col-sm-12 col-xs-12">
                                            <div>
                                                <h4 class="mb0 csF14 csB7 csYes">OnTrack</h4>
                                                <p class="ma0 csF14">As Of Jun 14 2021, Mon</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="csF14 csB7 pull-right"> $0 / $33 </p>
                                        </div>
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="progress">
                                                <div class="progress-bar" style="width:  0%;"></div>
                                                <p class="text-center">0% completed</p>
                                            </div>
                                            <div class="row ma10">
                                                <div class="col-sm-6">
                                                    <p class="csF14">Apr 30 2021, Fri<br> Start Date </p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="text-right csF14">Apr 30 2021, Fri<br> Due Date</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <h5 class="csF14">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos libero odio laborum voluptates corrupti. Ut ipsam, quam quod ex voluptate repellat possimus voluptatibus eligendi illum ad dolor beatae fuga saepe.</h5>
                                        </div>
                                    </div>
                                </div> <!-- FOOTER -->
                            </div> <!-- Comment screen -->
                            <div class="csPageSection jsBoxSection dn" data-key="comment">
                                <!-- BODY -->
                                <div class="csPageBody csGoalBoxH">
                                    <ul class="csChatMenu jsGoalCommentWrap"></ul>
                                </div> <!-- FOOTER -->
                                <div class="csPageFooter bbt p10">
                                    <div class="row">
                                        <div class="col-sm-8 col-xs-12"> <textarea class="form-control jsGoalComment"
                                                placeholder="John Doe has completed his tasks."></textarea> </div>
                                        <div class="col-sm-4 col-xs-12"> <button
                                                class="btn btn-orange form-control jsGoalCommentSaveBtn"><i
                                                    class="fa fa-save"></i> </button> <button
                                                class="btn btn-black form-control jsBoxSectionBackBtn" data-to="main"><i
                                                    class="fa fa-times"></i> </button> </div>
                                    </div>
                                </div>
                            </div> <!-- Update -->
                            <div class="csPageSection jsBoxSection dn" data-key="update">
                                <!-- BODY -->
                                <div class="csPageBody p10 csGoalBoxH">
                                    <div class="row">
                                        <div class="col-sm-5 col-xs-12">
                                            <h4><strong>Status</strong></h4>
                                        </div>
                                        <div class="col-sm-7 col-xs-12"> <select class="jsGoalTrack">
                                                <option value="1">On Track</option>
                                                <option value="0">Off Track</option>
                                            </select> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 col-xs-12">
                                            <h4><strong>Completed Target</strong></h4>
                                        </div>
                                        <div class="col-sm-7 col-xs-12"> <input type="text"
                                                class="form-control jsGoalCompletedTarget"> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5 col-xs-12">
                                            <h4><strong>Target</strong></h4>
                                        </div>
                                        <div class="col-sm-7 col-xs-12"> <input type="text"
                                                class="form-control jsGoalTarget" value=""> </div>
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
                            </div> <!-- Visibility -->
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
                                        <div class="col-sm-6 col-xs-12"> <button class="btn btn-orange form-control"><i class="fa fa-save" aria-hidden="true"></i> Save</button> </div>
                                        <div class="col-sm-6 col-xs-12"> <button class="btn btn-black form-control"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button> </div>
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