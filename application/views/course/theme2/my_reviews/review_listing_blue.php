<!--  -->
<div class="col-sm-9 col-xs-12">
    <!--  -->
    <div class="panel panel-theme dn">
        <div class="panel-heading pb0">
            <p class="csF16 csB7 csW">Filter Results</p>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <label class="csF16 csB7">Review</label>
                    <select id="jsReviewTitles" multiple></select>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <label class="csF16 csB7">Start Date</label>
                    <input readonly class="form-control" id="jsReviewStartDateInp" />
                </div>
                <div class="col-sm-4 col-xs-12">
                    <label class="csF16 csB7">End Date</label>
                    <input readonly class="form-control" id="jsReviewEndDateInp" />
                </div>
            </div>
            <!--  -->
            <div class="row">
                <br>
                <div class="col-sm-12">
                    <span class="pull-right">
                        <button class="btn btn-orange csF16 csB7">Search</button>
                        <button class="btn btn-black csF16 csB7">Clear</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="panel panel-theme">
        <div class="panel-body">
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col" class="csF16 csB7">Review</th>
                                <th scope="col" class="csF16 csB7">Reviewer</th>
                                <th scope="col" class="csF16 csB7">Period</th>
                                <th scope="col" class="csF16 csB7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(!empty($reviews)){
                                    foreach($reviews as $review){
                                        ?>
                            <tr data-id="<?=$review['review_sid'];?>" data-reviwee="<?=$review['reviewee_sid'];?>"
                                data-reviwer="<?=$review['reviewer_sid'];?>">
                                <td style="vertical-align: middle;"><?=ucwords($review['review_title']);?></td>
                                <td style="vertical-align: middle;"><?=ucwords($review['first_name'].' '.$review['last_name']);?></td>
                                <td style="vertical-align: middle;"><?=formatDate($review['review_start_date'], 'Y-m-d', DATE);?> - <?=formatDate($review['review_end_date'], 'Y-m-d', DATE);?></td>
                                <td style="vertical-align: middle;">
                                    <button class="btn btn-orange csF16 csB7 jsViewFeedback"
                                        title="View Manager's Feedback" placement="top">
                                        <i class="fa fa-eye" aria-hidden="true"></i>&nbsp; View Feedback
                                    </button>
                                </td>
                            </tr>
                            <?php
                                    }
                                } else{
                                    ?>
                            <tr>
                                <td colspan="4">
                                    <p class="csF16 csB7 text-center">No feedback found</p>
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

<script>
    var reviews = <?=json_encode($reviews);?>;
</script>
