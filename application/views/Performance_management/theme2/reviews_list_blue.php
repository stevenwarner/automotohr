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
                    <span class="pull-right">
                        <a href="<?=current_url();?>?type=active" class="btn btn-orange <?=$type == 'active' ? 'active' : ''?>">Active</a>
                        <a href="<?=current_url();?>?type=archived" class="btn btn-orange <?=$type == 'archived' ? 'active' : ''?>">Archived</a>
                        <a href="<?=current_url();?>?type=draft" class="btn btn-orange <?=$type == 'draft' ? 'active' : ''?>">Draft</a>
                    </span>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="panel-body">
            <!--  -->
            <div class="row">
            <?php
                //
                if(!empty($reviews)):
                    //
                    foreach($reviews as $review):
                        //
                        $statusClass = 'warning';
                        //
                        if($review['status'] == 'started'){
                            $statusClass = 'success';
                        } else if($review['status'] == 'ended'){
                            $statusClass = 'danger';
                        }
                        ?>
                        
                            <div class="col-md-4 col-xs-12">
                                <div class="panel panel-theme jsReviewBox" data-id="<?=$review['sid'];?>" data-title="<?=$review['review_title'];?>">
                                    <div class="panel-heading pl5 pr5">
                                        <button class="btn btn-<?=$statusClass;?> btn-xs csF14 csRadius5"><?=strtoupper($review['status']);?></button>
                                        <span class="pull-right">
                                            <?php
                                            if(!$review['is_draft']){
                                                if($review['status'] != 'started'){
                                                    ?>
                                                    <button class="btn btn-black csF16 btn-xs jsStartReview"  title="Start the review" placement="top">
                                                        <i class="fa fa-play csF16" aria-hidden="true"></i>
                                                    </button>
                                                    <?php
                                                } else{
                                                    ?>
                                                    <button class="btn btn-black csF16 btn-xs jsEndReview"  title="End the review" placement="top">
                                                        <i class="fa fa-stop csF16" aria-hidden="true"></i>
                                                    </button>
                                                    <?php
                                                }
                                            }else{
                                                ?>
                                                 <a href="<?=purl('review/create/'.$review['sid']);?>" class="btn btn-black csF16 btn-xs "  title="Edit Review" placement="top">
                                                        <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                                    </a>
                                                <?php
                                            }
                                            ?>
                                            <a href="<?=purl('review/'.$review['sid']);?>" class="btn btn-black csF16 btn-xs" title="View Review Details" placement="top">
                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                            </a>
                                            <button class="btn btn-black csF16 btn-xs jsAddReviewers"  title="Add Reviewers" placement="top">
                                                <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                                            </button>
                                            <?php 
                                            if(!$review['is_draft']){
                                                if($review['is_archived']){
                                                    ?>
                                                    <button class="btn btn-black csF16 btn-xs jsActivateReview"  title="Activate Review" placement="top">
                                                        <i class="fa fa-check csF16" aria-hidden="true"></i>
                                                    </button>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button class="btn btn-black csF16 btn-xs jsArchiveReview"  title="Archive Review" placement="top">
                                                        <i class="fa fa-archive csF16" aria-hidden="true"></i>
                                                    </button>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="panel-body">
                                        <p class="csF14 csB7 mb0">Title</p>
                                        <p class="csF14"><?=$review['review_title'];?></p>
                                        <hr />
                                        <p class="csF14 csB7 mb0">Cycle Period</p>
                                        <p class="csF14">
                                            <?=formatDateToDB($review['review_start_date'], DB_DATE, DATE);?> - <?=formatDateToDB($review['review_end_date'], DB_DATE, DATE);?> <br>
                                        </p>
                                        <hr />
                                        <p class="csF14 csB7 mb0">Reviewer(s) Progress <i class="fa fa-question-circle-o csF14 csB7 csCP jsHintBtn" aria-hidden="true" data-target="jsReviewerProgress<?=$review['sid'];?>"></i></p>
                                        <p class="jsHintBody" data-hint="jsReviewerProgress<?=$review['sid'];?>">The percentage of reviewers who have submitted the review.</p>
                                        <p class="csF14"><?=getCompletedPercentage($review['Reviewees'], 'reviewers');?>% Completed</p>
                                        <hr />
                                        <p class="csF14 csB7 mb0">Manager(s) Progress <i class="fa fa-question-circle-o csF14 csB7 csCP jsHintBtn" aria-hidden="true" data-target="jsManagerProgress<?=$review['sid'];?>"></i></p>
                                        <p class="jsHintBody" data-hint="jsManagerProgress<?=$review['sid'];?>">The percentage of reporting managers who have submitted the review.</p>
                                        <p class="csF14"><?=getCompletedPercentage($review['Reviewees'], 'manager');?>% Completed</p>
                                    </div>
                                </div>
                            </div>
                            <?php
                    endforeach;
                else:
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="csF16 text-center">
                                No review(s) found.
                            </p>
                        </div>
                    </div>
                    <?php
                endif;
                ?>
            </div>  
        </div>
    </div>
</div>

<script>
    var employees = <?=json_encode($company_employees);?>;
</script>