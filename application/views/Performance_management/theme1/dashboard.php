<div class="container-fluid">
    <!-- Heading -->
    <div class="csPageHeading">
        <h1 class="csF26 csB9">
            <span class="csBTNBox">
                <a href="<?=purl('review/create');?>" class="btn btn-lg btn-orange csF16"><i class="fa fa-plus-circle csF16" aria-hidden="true"></i> Create a
                    Review</a>
            </span>
            <strong>Custom reviews tailored to your needs.</strong><br />
        </h1>
        <p class="csF16">Create reviews that fit your team and collect feedback in one location.</p>
    </div>
    <!--  -->
    <div class="row">
        <!-- Sidebar -->
        <div class="col-sm-4 col-xs-12">
            <div class="csPageBox csRadius5">
                <div class="csPageBoxHeader p10">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <h4 class="csF18 csB7">
                                My Goals
                            </h4>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <span class="csBTNBox">
                                <a href="javascript:void(0)" class="btn btn-orange jsCreateGoal csF16"><em class="fa fa-plus-circle csF16"></em> Add a Goal</a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="csPageBoxBody p10">
                <?php if(empty($goals)): ?>
                    <div id="jsGoalContainer">
                        <p class="csCaughtBox">
                            You haven't set any goals yet <br />
                        </p>
                    </div>
                <?php else: 
                        foreach($goals as $goal): ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="csF18 csB7"><?=$goal['title'];?></h3>
                                <h4 class="csF16">
                                    <span class="<?=$goal['on_track'] == 1 ? 'text-success' : 'text-danger';?> csB8"><?=$goal['on_track'] == 1 ? "On" : "Off"; ?> Track</span>
                                    <span class="pull-right"><?= $goal['measure_type'] == 1 ? '%' : ($goal['measure_type'] == 2 ? '$' : '');?> <?=$goal['completed_target'];?> / <?=$goal['target'];?></span>
                                </h4>
                            </div>
                        </div>
                        
                <?php endforeach; 
                endif; ?>
                    <!--  -->
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- Content Area -->
        <div class="col-sm-8 col-xs-12">
            <!-- Content Area -->
            <div class="csPageBox csRadius5">
                <div class="csPageBoxHeader p10">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <h4 class="csF18 csB7">
                                Assigned Reviews (<?=count($assignedReviews);?>)
                            </h4>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <span class="csBTNBox">
                                <a href="<?=purl('reviews');?>" class="btn btn-orange csF16"><em
                                        class="fa fa-eye csF16"></em>
                                    View All</a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="csPageBoxBody p10">
                    <div id="jsAssignedReviewContainer">
                    <?php if(count($assignedReviews)):
                            foreach($assignedReviews as $row):
                                $em = $employees[$row['reviewee_sid']];
                            ?>
                            <div class="col-sm-4">
                                <div class="csEBox">
                                    <figure>
                                        <img src="<?=getImageURL($em['image']);?>"
                                            class="csRadius50" />
                                    </figure>
                                    <div class="csEBoxText">
                                        <h4 class="mb0 csF16 csB7"><?=$em['name'];?></h4>
                                        <p class="mb0 csF16"><?=$em['role'];?></p>
                                        <p class="csF16"><?=getDueText($row['end_date']);?></p>
                                        <a href="<?=base_url('performance-management/reviewer_feedback/'.($row['review_sid']).'/'.($row['reviewee_sid']));?>" target="blank" class="btn btn-black csRadius100 csF16"><em class="fa fa-play-circle csF16"></em> &nbsp;Start Review</a>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach; else: ?>
                        <div id="jsFeedbackReviewContainer">
                            <p class="csCaughtBox">
                                <i class="fa fa-check"></i> <br />
                                You are all caught up
                            </p>
                        </div>
                    <?php  endif; ?>
                    </div>
                    <!--  -->
                    <div class="clearfix"></div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="csPageBox csRadius5">
                <div class="csPageBoxHeader p10">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <h4 class="csF18 csB7">
                                Feedback For You (<?=count($feedbackReviews);?>)
                            </h4>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <span class="csBTNBox">
                                <a href="<?=purl('reviews');?>" class="btn btn-orange csF16"><i
                                        class="fa fa-eye csF16"></i>
                                    View All</a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="csPageBoxBody p10">
                <div id="jsAssignedReviewContainer">
                    <?php if(count($feedbackReviews)):
                            foreach($feedbackReviews as $row):
                                $em = $employees[$row['reviewee_sid']];
                            ?>
                            <div class="col-sm-4">
                                <div class="csEBox">
                                    <figure>
                                        <img src="<?=getImageURL($em['image']);?>"
                                            class="csRadius50" />
                                    </figure>
                                    <div class="csEBoxText">
                                        <h4 class="mb0 csF16 csB7"><?=$em['name'];?></h4>
                                        <p class="mb0 csF16"><?=$em['role'];?></p>
                                        <p class="csF16"><?=getDueText($row['end_date']);?></p>
                                        <a href="<?=base_url('performance-management/reviewer_feedback/'.($row['review_sid']).'/'.($row['reviewee_sid']));?>" target="blank" class="btn btn-black csRadius100 csF16"><em class="fa fa-play-circle csF16"></em> &nbsp;Start Review</a>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach; else: ?>
                        <div id="jsFeedbackReviewContainer">
                            <p class="csCaughtBox">
                                <i class="fa fa-check"></i> <br />
                                You are all caught up
                            </p>
                        </div>
                    <?php  endif; ?>
                    </div>
                    <!--  -->
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>