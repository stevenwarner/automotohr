<?php $review = $review[0];

//
$statusClass = 'warning';
//
if($review['status'] == 'started'){
    $statusClass = 'success';
} else if($review['status'] == 'ended'){
    $statusClass = 'danger';
}

//
$ne = [];
//
foreach($company_employees as $emp){
    $ne[$emp['Id']] = $emp;
}
//
$RT = getCompletedPercentage($review['Reviewees'], 'reviewer', true);
$MT = getCompletedPercentage($review['Reviewees'], 'manager', true);

?>
<div class="col-md-9 col-sm-12">
    <!--  -->
    <div class="row">
        <div class="col-sm-12">
            <span class="pull-right">
                <a class="btn btn-black" href="<?=purl("reviews");?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp; Back To Reviews</a>
                <button class="btn btn-orange jsAddReviewees"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp; Add Reviewee(s)</button>
            </span>    
        </div>
    </div>
    <br>

    <!--  -->
    <div class="panel panel-theme">
        <!--  -->
        <div class="panel-body">
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="csF16 csB7">
                        <?=$review['review_title'];?>&nbsp;
                        <button class="btn btn-<?=$statusClass;?> btn-xs csF16 csRadius5"><?=strtoupper($review['status']);?></button>
                    </h5>
                </div>
            </div>
            <hr>
            <!--  -->
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h5 class="csF16 csB7">
                        <?=$RT['percentage'];?>% Completed
                    </h5>
                    <h5 class="csF14">
                    <?=$RT['completed'];?> out of <?=$RT['total'];?> reviewer(s) submitted their feedback.
                    </h5>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <h5 class="csF16 csB7">
                    <?=$MT['percentage'];?>% Completed
                    </h5>
                    <h5 class="csF14">
                    <?=$MT['completed'];?> out of <?=$MT['total'];?> reporting manager(s) submitted their feedback.
                    </h5>
                </div>
            </div>  
        </div>
    </div>

    <!--  -->
    <div class="panel panel-theme">
        <div class="panel-body">
            <table class="table table-striped table-condensed">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col" class="csF16 csB7">Reviewee</th>
                        <th scope="col" class="csF16 csB7">Reviewer(s)</th>
                        <th scope="col" class="csF16 csB7">Period Cycle</th>
                        <th scope="col" class="csF16 csB7">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($review['Reviewees'] as $reviewee){
                            ?>
                            <tr data-review_id="<?=$review['sid'];?>" data-id="<?=$reviewee['reviewee_sid'];?>">
                                <td style="vertical-align: middle">
                                    <p class="csF14">
                                        <b><?=$ne[$reviewee['reviewee_sid']]['Name'];?></b>
                                    </p>
                                    <p class="csF14">
                                        <?=$ne[$reviewee['reviewee_sid']]['Role'];?>
                                    </p>
                                </td>
                                <td style="vertical-align: middle">
                                    <a class="csF14 csB7 csCP jsReviewViewReviewers" title="Click to view employees" placement="top">
                                        <?=count($reviewee['reviewers']);?> Reviewer(s) Added
                                    </a>
                                </td>
                                <td style="vertical-align: middle">
                                    <p class="csF14">
                                        <?php
                                            if(!empty($reviewee['start_date'])){
                                                ?>
                                                <?=formatDateToDB($reviewee['start_date'], DB_DATE, DATE);?> - <?=formatDateToDB($reviewee['end_date'], DB_DATE, DATE);?>
                                                <?php
                                            } else{
                                                ?>
                                                N/A
                                                <?php
                                            }
                                            ?>
                                    </p>
                                </td>
                                <td style="vertical-align: middle">
                                    <button class="btn btn-orange csF16 jsReviewViewReviewers" title="View Reviewers" placement="top">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                    <?php
                                        if($reviewee['is_started']){
                                            ?>
                                            <button class="btn btn-orange csF16 jsStopReview" title="Stop Review" placement="top">
                                                <i class="fa fa-stop" aria-hidden="true"></i>
                                            </button>
                                            <?php
                                        } else{
                                            ?>
                                            <button class="btn btn-orange csF16 jsStartReview" title="Start Review" placement="top">
                                                <i class="fa fa-play" aria-hidden="true"></i>
                                            </button>
                                            <?php
                                        }
                                    ?>
                                    <button class="btn btn-orange csF16 jsManageReview" title="Manage" placement="top">
                                        <i class="fa fa-cogs" aria-hidden="true"></i>
                                    </button>
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

<script>
    var employees = <?=json_encode($company_employees);?>;
    var ne = <?=json_encode($ne);?>;
    var review = <?=json_encode($review);?>;
</script>