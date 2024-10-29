<?php
$employeeSelect = $efj['EmployeeRows'];
if ($load_view) {

    $panelHeading = 'background-color: #3554DC';
} else {
    $panelHeading = 'background-color: #81b431';
}
?>
<div class="col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
    <!--  -->
    <div class="panel panel-theme">
        <!--  -->
        <div class="panel-heading mt0 mb0 pb0" style="<?= $panelHeading ?>">
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <h5 class="csF16 csW csB7">
                        Review(s)
                    </h5>
                </div>
                <div class="col-xs-12 col-md-10">
                    <span class="pull-right">
                        <a href="javascript:void(0)" class="btn btn-orange" id="jsReminderReviewer"><i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp; Send Reminder Email</a>
                        <a title="Show me active reviews" placement="top" href="<?= current_url(); ?>?type=active" class="btn btn-orange <?= $type == 'active' ? 'active' : '' ?>">Active Reviews (<?= $ReviewCount['Active']; ?>)</a>
                        <a title="Show me archived reviews" placement="top" href="<?= current_url(); ?>?type=archived" class="btn btn-orange <?= $type == 'archived' ? 'active' : '' ?>">Archived Reviews (<?= $ReviewCount['Archived']; ?>)</a>
                        <a title="Show me reviews that are in draft" placement="top" href="<?= current_url(); ?>?type=draft" class="btn btn-orange <?= $type == 'draft' ? 'active' : '' ?>">Reviews In Draft (<?= $ReviewCount['Draft']; ?>)</a>
                    </span>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="panel-body">
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF16">
                        <button class="btn btn-warning btn-xs csRadius5">PENDING</button>&nbsp; The review is active but hasn't started.
                    </p>
                    <p class="csF16">
                        <button class="btn btn-success btn-xs csRadius5">STARTED</button>&nbsp; The review is active and started.
                    </p>
                    <p class="csF16">
                        <button class="btn btn-danger btn-xs csRadius5">ENDED</button>&nbsp; The review is active and ended.
                    </p>
                </div>
            </div>
            <br />
            <div class="row">
                <?php
                //   
                if (!empty($reviews)):
                    //
                    foreach ($reviews as $review):
                        //
                        $statusClass = 'warning';
                        //
                        if ($review['status'] == 'started') {
                            $statusClass = 'success';
                        } else if ($review['status'] == 'ended') {
                            $statusClass = 'danger';
                        }
                ?>
                        <div class="col-md-4 col-xs-12">
                            <div class="panel panel-theme jsReviewBox" data-id="<?= $review['sid']; ?>" data-title="<?= $review['review_title']; ?>">
                                <div class="panel-heading pl5 pr5" style="<?= $panelHeading ?>">
                                    <?php
                                    if (!$review['is_draft']) { ?>
                                        <button class="btn btn-<?= $statusClass; ?> btn-xs csF14 csRadius5"><?= strtoupper($review['status']); ?></button>
                                    <?php } ?>
                                    <span class="pull-right">
                                        <?php
                                        if (!$review['is_draft']) {
                                            if ($review['status'] != 'started') {                                        ?>
                                                <button class="btn btn-black csF16 btn-xs jsStartReview" title="Start the review" placement="top">
                                                    <i class="fa fa-play csF16" aria-hidden="true"></i>
                                                </button>
                                            <?php
                                            } else {
                                            ?>
                                                <button class="btn btn-black csF16 btn-xs jsEndReview" title="End the review" placement="top">
                                                    <i class="fa fa-stop csF16" aria-hidden="true"></i>
                                                </button>
                                            <?php
                                            }
                                            ?>
                                            <a href="<?= purl('review/' . $review['sid']); ?>" class="btn btn-black csF16 btn-xs" title="View Reviewee(s)" placement="top">
                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                            </a>
                                            <button class="btn btn-black csF16 btn-xs jsAddReviewers" title="Add Reviewees" placement="top">
                                                <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-black csF16 btn-xs jsReviewVisibility" title="Manage Visibility" placement="top">
                                                <i class="fa fa-users csF16" aria-hidden="true"></i>
                                            </button>
                                        <?php
                                        } else {
                                        ?>
                                            <a href="<?= purl('review/create/' . $review['sid']); ?>" class="btn btn-black csF16 btn-xs " title="Edit Review" placement="top">
                                                <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if (!$review['is_draft']) {
                                            if ($review['is_archived']) {
                                        ?>
                                                <button class="btn btn-black csF16 btn-xs jsActivateReview" title="Activate Review" placement="top">
                                                    <i class="fa fa-check csF16" aria-hidden="true"></i>
                                                </button>
                                            <?php
                                            } else {
                                            ?>
                                                <button class="btn btn-black csF16 btn-xs jsArchiveReview" title="Archive Review" placement="top">
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
                                    <p class="csF14"><?= $review['review_title']; ?></p>
                                    <hr />
                                    <p class="csF14 csB7 mb0">Cycle Period</p>
                                    <p class="csF14">
                                        <?= formatDateToDB($review['review_start_date'], DB_DATE, DATE); ?> - <?= formatDateToDB($review['review_end_date'], DB_DATE, DATE); ?> <br>
                                    </p>
                                    <hr />
                                    <p class="csF14 csB7 mb0">Reviewer(s) Progress <i class="fa fa-question-circle-o csF14 csB7 csCP jsHintBtn" aria-hidden="true" data-target="jsReviewerProgress<?= $review['sid']; ?>"></i></p>
                                    <p class="jsHintBody" data-hint="jsReviewerProgress<?= $review['sid']; ?>">The percentage of reviewers who have submitted the review. Click to view details.</p>
                                    <p class="csF14 csB7 csFC2 csCP jsShowReviews" title="Click to see Reviewees/Reviewers" placement="top"><?= getCompletedPercentage($review['Reviewees'], 'reviewers'); ?>% Completed</p>
                                    <hr />
                                    <p class="csF14 csB7 mb0">Manager(s) Progress <i class="fa fa-question-circle-o csF14 csB7 csCP jsHintBtn" aria-hidden="true" data-target="jsManagerProgress<?= $review['sid']; ?>"></i></p>
                                    <p class="jsHintBody" data-hint="jsManagerProgress<?= $review['sid']; ?>">The percentage of reporting managers who have submitted the review. Click to view details.</p>
                                    <p class="csF14 csB7 csFC2 csCP jsShowReviews" title="Click to see Reviewees/Reviewers" placement="top">
                                        <?= getCompletedPercentage($review['Reviewees'], 'manager'); ?>% Completed
                                    </p>
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
    var employees = <?= json_encode($company_employees); ?>;
</script>

<script>
    //
    $('#jsReminderReviewer').click(getReviewers);

    function getReviewers(e) {
        Model({
            Id: 'jsReminderReviewerModel',
            Loader: 'jsReminderReviewerLoader',
            Body: '<div class="container"><div id="jsReminderReviewerBody"></div></div>',
            Title: 'Reminder Email To Reviewers'
        }, );

        <?php
        $reviewersList = '<br><label>Reviewers</label><select id="js-reviewers" multiple="true"><option value"all">All</option>';
        foreach ($reviewersPendingReviews as $review) {
            $options .= '<option value="' . $review["reviewer_sid"] . '">' . remakeEmployeeName($review) . '</option>';
        }

        $reviewersList = $reviewersList . $options . '</select>';
        ?>

        <?php
        $sendbtn = '<span class = "pull-right" > <a href = "javascript:void(0)"   class = "btn btn-orange"  id = "js-sendMail" > <i class = "fa fa-paper-plane"  aria - hidden = "true" > </i>&nbsp; Send Email</a></span>'; ?>

        $('#jsReminderReviewerBody').html('<?php echo $reviewersList . $sendbtn; ?>');

        ml(false, 'jsReminderReviewerLoader');
        $('#js-reviewers').select2({
            closeOnSelect: false
        });
    }

    //
    $(document).on("click", "#js-sendMail", function() {
        let selectedReviewers = $("#js-reviewers").val();
        if (selectedReviewers == null || selectedReviewers == '') {
            alertify.alert('Please select a reviewer');
            return;
        }
        rurl = "<?php echo base_url('performance-management/reviwers_reminder') ?>";
        $.ajax({
            type: "POST",
            url: rurl,
            data: {
                reviewers: selectedReviewers
            },
            success: function(data) {
                console.log(data);
                alertify.success('Email sent successfully');
                $('#jsReminderReviewerModel .jsModalCancel').trigger('click');

            }
        });

    });
</script>