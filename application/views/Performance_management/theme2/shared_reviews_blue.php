
    <div class="panel panel-theme">
        <div class="panel-heading" style="background-color: #3554DC;">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        Reviews Shared With Me
                    </h5>
                </div>
                <div class="col-md-3 col-sm-12 text-right">
                    <h5 class="csF16 csB7 csW">
                        Reviews Found: <?= count($AssignedReviews); ?>
                    </h5>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <br>
            <?php
            //
            if (!empty($AssignedReviews)) {
            ?>
                <div class="panel-body">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col">Review Title</th>
                                <th scope="col">Shared By</th>
                                <th scope="col">Share Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($AssignedReviews as $review) { ?>
                               <tr>
                                    <td>
                                        <p class="csF16 csB7"><b>
                                                <?php echo  $review['review_title']; ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="csF16 csB7"><b>
                                                <?php echo remakeEmployeeName($review); ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="csF16 csB7">
                                            <?php echo  formatDateToDB($review['share_date'], DB_DATE, DATE); ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="csF16 csB7">
                                            <a href="<?php echo base_url('performance-management/feedbackshared/'.$review['review_sid'].'/'.$review['share_from'].'/'.$review['share_from'])?>" class="btn btn-orange"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp; View Feedback</a>
                                        </p>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else {
            ?>
                <div class="panel-body">
                    <div class="row">
                        <p class="csF16 csB7 text-center">
                            No review fond.
                        </p>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>