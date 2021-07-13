<div class="col-md-9 col-sm-12">
    <!--  -->
    <div class="panel panel-theme">
        <!--  -->
        <div class="panel-heading mt0 mb0 pb0">
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <h5 class="csF16 csW csB7">
                        Review(s)
                    </h5>
                </div>
                <div class="col-xs-12 col-md-10">
                    <ul class="text-right csTabMenu">
                        <li class="csF16 csCP csB7 active">Active</li>
                        <li class="csF16 csCP ">Scheduled</li>
                        <li class="csF16 csCP ">Archived</li>
                        <li class="csF16 csCP ">Draft</li>
                    </ul>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="panel-body">
            <?php
                //
                if(!empty($reviews)):
                    //
                    foreach($reviews as $review):
                        _e($review, true);
                        //
                        $statusClass = 'warning';
                        //
                        if($review['status'] == 'started'){
                            $statusClass = 'success';
                        } else if($review['status'] == 'ended'){
                            $statusClass = 'danger';
                        }
                        ?>
                        <!--  -->
                        <div class="row">
                            <div class="col-md-4 col-xs-12">
                                <div class="panel panel-theme">
                                    <div class="panel-heading pl5 pr5">
                                        <button class="btn btn-<?=$statusClass;?> btn-xs csF14 csRadius5"><?=strtoupper($review['status']);?></button>
                                        <span class="pull-right">
                                            <button class="btn btn-black csF16 btn-xs" title="View Review Details" placement="top">
                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-black csF16 btn-xs"  title="Edit Review" placement="top">
                                                <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-black csF16 btn-xs"  title="Add Reviewers" placement="top">
                                                <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-black csF16 btn-xs"  title="Archive Review" placement="top">
                                                <i class="fa fa-archive csF16" aria-hidden="true"></i>
                                            </button>
                                        </span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="panel-body">
                                        <p class="csF14 csB7 mb0">Title</p>
                                        <p class="csF14"><?=$review['review_title'];?></p>
                                        <hr />
                                        <p class="csF14 csB7 mb0">Cycle Period</p>
                                        <p class="csF14">
                                            <?=$review['start_date'];?> - <?=$review['end_date'];?> <br>
                                        </p>
                                        <hr />
                                        <p class="csF14 csB7 mb0">Reviewer(s) Progress <i class="fa fa-question-circle-o csF14 csB7 csCP jsHintBtn" aria-hidden="true" data-target="jsReviewerProgress<?=$review['sid'];?>"></i></p>
                                        <p class="jsHintBody" data-hint="jsReviewerProgress<?=$review['sid'];?>">The percentage of reviewers who have submitted the review.</p>
                                        <p class="csF14">50% Completed</p>
                                        <hr />
                                        <p class="csF14 csB7 mb0">Manager(s) Progress <i class="fa fa-question-circle-o csF14 csB7 csCP jsHintBtn" aria-hidden="true" data-target="jsManagerProgress<?=$review['sid'];?>"></i></p>
                                        <p class="jsHintBody" data-hint="jsManagerProgress<?=$review['sid'];?>">The percentage of reporting managers who have submitted the review.</p>
                                        <p class="csF14">30% Completed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif;
            ?>
        </div>
    </div>
</div>