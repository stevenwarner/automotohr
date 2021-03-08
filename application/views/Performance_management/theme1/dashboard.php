<div class="container-fluid">
    <!-- Heading -->
    <div class="csPageHeading">
        <h2>
            <span class="csBTNBox">
                <a href="<?=purl('review/create');?>" class="btn btn-orange"><i class="fa fa-plus-circle"></i> Create
                    Review</a>
            </span>
            <strong>Custom reviews tailored to your needs.</strong><br />
            <p style="font-size: 14px;">Create reviews that fit your team and collect feedback in one location.</p>
        </h2>
    </div>
    <!--  -->
    <div class="row">
        <!-- Sidebar -->
        <div class="col-sm-4 col-xs-12">
            <div class="csPageBox csRadius5">
                <div class="csPageBoxHeader p10">
                    <h4>
                        <strong class="csHeading">My Goals</strong>
                        <span class="csBTNBox">
                            <a href="javascript:void(0)" class="btn btn-orange mt0 jsCreateGoal"><i class="fa fa-plus-circle"></i> Add a Goal</a>
                        </span>
                    </h4>
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
                    <h4>
                        <strong class="csHeading">Assigned Reviews (<?=count($assignedReviews);?>)</strong>
                        <span class="csBTNBox">
                            <a href="<?=purl('reviews');?>" class="btn btn-orange mt0"><i
                                    class="fa fa-eye"></i>
                                View All</a>
                        </span>
                    </h4>
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
                                        <img src="<?=getImageURL($em['img']);?>"
                                            class="csRadius50" />
                                    </figure>
                                    <div class="csEBoxText">
                                        <h4 class="mb0"><strong><?=$em['name'];?></strong></h4>
                                        <p class="mb0"><?=$em['role'];?></p>
                                        <p><?=getDueText($row['end_date']);?></p>
                                        <a href="<?=base_url('performance-management/reviewer_feedback/'.($row['review_sid']).'/'.($row['reviewee_sid']));?>" target="blank" class="btn btn-xs alert-black csRadius100">Start Review</a>
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
                    <h4>
                        <strong class="csHeading">Feedback For You (<?=count($feedbackReviews);?>)</strong>
                        <span class="csBTNBox dn">
                            <a href="<?=purl('reviews');?>" class="btn btn-orange"><i
                                    class="fa fa-eye"></i>
                                View All</a>
                        </span>
                    </h4>
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
                                        <img src="<?=getImageURL($em['img']);?>"
                                            class="csRadius50" />
                                    </figure>
                                    <div class="csEBoxText">
                                        <h4 class="mb0"><strong><?=$em['name'];?></strong></h4>
                                        <p class="mb0"><?=$em['role'];?></p>
                                        <p><?=getDueText($row['end_date']);?></p>
                                        <a href="<?=base_url('performance-management/reviewer_feedback/'.($row['review_sid']).'/'.($row['reviewee_sid']));?>" target="blank" class="btn btn-xs alert-black csRadius100">Start Review</a>
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