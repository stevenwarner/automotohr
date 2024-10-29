<?php

if (!empty($AssignedReviews)) {
    //
    $newArray = [];
    //
    $now = date('Y-m-d', strtotime('now'));
    //
    foreach ($AssignedReviews as $review) {
        //
        if (!isset($newArray[$review['review_title']])) {
            $newArray[$review['review_title']] = [];
        }
        //
        $tr = '<tr>';
        $tr .= '    <td>';
        $tr .= '        <p class="csF16 csB7"><b>';
        $tr .=             $company_employees_index[$review['reviewee_sid']]['Name'];
        $tr .= '           </br>';
        $tr .=             $company_employees_index[$review['reviewee_sid']]['Role'];
        $tr .= '        </b></p>';
        $tr .= '    </td>';
        $tr .= '    <td>';
        $tr .= '        <p class="csF16 csB7">';
        $tr .=              formatDateToDB($review['start_date'], DB_DATE, DATE) . ' - ' . formatDateToDB($review['end_date'], DB_DATE, DATE);
        $tr .= '            </br> ';
        $tr .= '            Due In: ' . ($review['end_date'] < $now ? 'Due date passed' : dateDifferenceInDays($now, $review['end_date'], '%a') . ' Day(s)') . ' ';
        $tr .= '        </p>';
        $tr .= '    </td>';
        $tr .= '    <td>';
        $tr .= '        <p class="csF16 csB7">';
        $tr .= '        <a href="javascript:void(0)" class="btn btn-orange jsShareReview"  data-id="' . $review['sid'] . '"><i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp; Share Review</a>
';
        $tr .= '        </p>';

        $tr .= '</tr>';
        //
        $newArray[$review['review_title']][] = $tr;
    }
}

?>

<div class="col-md-12 col-sm-12">
    <?php if ($this->session->flashdata('message')) { ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="flash_error_message">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $this->session->flashdata('message'); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- Assigned -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="background-color: #3554DC;">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        Reviews Assigned To Me - As Reviewer
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
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF14 csInfo csB7"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Assigned reviews on which your feedback is required. The submitted feedback will be shared with the reporting manager(s).</p>
                </div>
            </div>
            <br>
            <?php
            //
            if (!empty($AssignedReviews)) {
            ?>

                <?php foreach ($newArray as $review_title => $row) { ?>
                    <div class="panel panel-theme">
                        <div class="panel-heading" style="background-color: #3554DC;">
                            <h5 class="csF16 csB7 csW mb0 mt0">
                                <?= $review_title; ?>
                            </h5>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <caption></caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Employee</th>
                                        <th scope="col">Cycle Period</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?= implode('', $row); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            <?php
            } else {
            ?>
                <div class="panel-body">
                    <div class="row">
                        <p class="csF16 csB7 text-center">
                            No reviews are assigned to you for feedback.
                        </p>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
    </div>
</div>


<script>
    //

    $('.jsShareReview').click(function() {
        getReviewers();
        letreviewId = $(this).data("id");
        $("#jsreviewid").val(letreviewId);
    });


    function getReviewers(e) {

        Model({
            Id: 'jsjsShareReviewModel',
            Loader: 'jsShareReviewLoader',
            Body: '<div class="container"><div id="jsShareReviewBody"></div></div>',
            Title: 'Share Review'
        }, );

        <?php
        $employeesList = '<form id="formsharereview" action="' . base_url('performance-management/review_share') . '" method="POST"><br><label>Employees</label><select id="js-reviewers" name="employees[]" multiple="true"><option value"all">All</option>';
        foreach ($employees as $employee) {
            $options .= '<option value="' . $employee["sid"] . '">' . remakeEmployeeName($employee) . '</option>';
        }

        $employeesList = $employeesList . $options . '</select>';
        ?>

        <?php
        $sendbtn = '<input type="hidden" class="form-control" id="jsreviewid" name="reviewid" placeholder="Quarterly Review"><span class = "pull-right" ><br><a href = "javascript:void(0)"   class = "btn btn-orange"  id = "js-shareReview" > <i class = "fa fa-paper-plane"  aria - hidden = "true" > </i>&nbsp; Share </a></span></form>'; ?>

        $('#jsShareReviewBody').html('<?php echo $employeesList . $sendbtn; ?>');

        ml(false, 'jsShareReviewLoader');
        $('#js-reviewers').select2({
            closeOnSelect: false
        });
    }


    //
    $(document).on("click", "#js-shareReview", function() {
        let selectedReviewers = $("#js-reviewers").val();
        if (selectedReviewers == null || selectedReviewers == '') {
            alertify.alert('Please select an employee');
            return;
        }

        $("#formsharereview").submit();

    });
</script>