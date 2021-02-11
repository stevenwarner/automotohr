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
                            <a href="javascript:void(0)" class="btn btn-orange mt0"><i class="fa fa-plus-circle"></i> Add Goal</a>
                        </span>
                    </h4>
                </div>
                <div class="csPageBoxBody p10">
                    <div id="jsGoalContainer">
                        <p class="csCaughtBox">
                            You haven't set any goals yet <br />
                        </p>
                    </div>
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
                        <strong class="csHeading">Assigned Reviews (38)</strong>
                        <span class="csBTNBox">
                            <a href="<?=purl('review/view/assigned');?>" class="btn btn-orange mt0"><i
                                    class="fa fa-eye"></i>
                                View All</a>
                        </span>
                    </h4>
                </div>
                <div class="csPageBoxBody p10">
                    <div id="jsAssignedReviewContainer">
                        <?php for($i=0; $i < 10; $i++){ ?>
                        <div class="col-sm-4">
                            <div class="csEBox">
                                <figure>
                                    <img src="<?=randomData('img');?>"
                                        class="csRadius50" />
                                </figure>
                                <div class="csEBoxText">
                                    <h4 class="mb0"><strong><?=randomData('name');?></strong></h4>
                                    <p class="mb0">(QA) [Admin Plus]</p>
                                    <p>Due in 30 days</p>
                                    <a href="" class="btn btn-xs alert-black csRadius100">Start Review</a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <!--  -->
                    <div class="clearfix"></div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="csPageBox csRadius5">
                <div class="csPageBoxHeader p10">
                    <h4>
                        <strong class="csHeading">Feedback For You (0)</strong>
                        <span class="csBTNBox dn">
                            <a href="<?=purl('review/view/feedback');?>" class="btn btn-orange"><i
                                    class="fa fa-eye"></i>
                                View All</a>
                        </span>
                    </h4>
                </div>
                <div class="csPageBoxBody p10">
                    <div id="jsFeedbackReviewContainer">
                        <p class="csCaughtBox">
                            <i class="fa fa-check"></i> <br />
                            You are all caught up
                        </p>
                    </div>
                    <!--  -->
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>