$(function() {
    //
    var selectedReviewId;
    var selectedRevieweeId;
    //
    $('.jsAddReviewees').click(function(event) {
        //
        event.preventDefault();
        //
        selectedReviewId = review.sid;
        selectedRevieweeId = null;
        //
        Modal({
            Id: 'jsAddReviewersModal',
            Title: 'Add Reviewers to ' + review.review_title,
            Body: getReviewerBody2(),
            Loader: 'jsAddReviewersModalLoader'
        }, function() {
            //
            var options = '';
            options += '<option value="0">[Select a Reviewee]</option>';
            //
            employees.map(function(emp) {
                options += '<option value="' + (emp.Id) + '">' + (emp.Name + ' ' + emp.Role) + '</option>';
            });
            //
            $('#jsReviewRevieweesSelect').html(options).select2();
            //
            ml(false, 'jsAddReviewersModalLoader');
        });
    });
    //
    $(document).on('change', '#jsReviewRevieweesSelect', function() {
        //
        $('.jsReviewReviewersBox').addClass('dn');
        //
        selectedRevieweeId = $(this).val();
        //
        getRevieweeReviewers();
    });
    //
    $(document).on('click', '.jsReviewSaveReviewers2', function(event) {
        //
        event.preventDefault();
        //
        var reviewersList = $('#jsReviewReviewersSelect').val() || [];
        //
        if (reviewersList.length == 0) {
            handleError("Please select at least one reviewer.");
            return;
        }
        //
        saveReviewersList2(selectedReviewId, selectedRevieweeId, reviewersList);
    });
    //
    $('.jsManageReview').click(function(event) {
        //
        event.preventDefault();
        //
        selectedReviewId = $(this).closest('tr').data('review_id');
        selectedRevieweeId = $(this).closest('tr').data('id');
        //
        Modal({
            Id: 'jsAddReviewersModal',
            Title: 'Manage settings for ' + ne[selectedRevieweeId]['Name'],
            Body: getReviewerBody(),
            Loader: 'jsAddReviewersModalLoader'
        }, function() {
            //
            var options = '';
            //
            options += '<option value="' + (ne[selectedRevieweeId]['Id']) + '">' + (ne[selectedRevieweeId]['Name']) + ' ' + (ne[selectedRevieweeId]['Role']) + '</option>';
            //
            $('#jsReviewRevieweesSelect').html(options).select2();
            //
            $('.jsReviewReviewersBox').addClass('dn');
            //
            getRevieweeReviewers();
            $('#jsStartDate').datepicker();
            $('#jsEndDate').datepicker();
            //
            ml(false, 'jsAddReviewersModalLoader');
        });
    });

    //
    $('.jsReviewViewReviewers').click(function(event) {
        //
        event.preventDefault();
        //
        var revieweeId = $(this).closest('tr').data('id');
        //
        Modal({
            Id: 'jsReviewers',
            Title: review.review_title,
            Body: getReviewersBody(),
            Loader: 'jsReviewersLoader'
        }, function() {
            //
            var trs = '';
            //
            $.each(review.Reviewees[revieweeId].reviewers, function(index, reviewer) {
                //
                trs += '<tr>';
                trs += '    <td style="vertical-align: middle;">';
                trs += '        <p class="csF16">';
                trs += ne[reviewer['reviewer_sid']]['Name'];
                trs += '        </p>';
                trs += '        <p class="csF16">';
                trs += ne[reviewer['reviewer_sid']]['Role'];
                trs += '        </p>';
                trs += '    </td>';
                trs += '    <td style="vertical-align: middle;">';
                trs += '        <p class="csF16 csB7 text-' + (reviewer['is_manager'] ? 'success' : 'warning') + '">' + (reviewer['is_manager'] ? "Reporting Manager" : "Reviewer") + '</p>';
                trs += '    </td>';
                trs += '    <td style="vertical-align: middle;">';
                trs += '        <p class="csF16 csB7 text-' + (reviewer['is_completed'] == 1 ? 'success' : 'warning') + '">' + (reviewer['is_completed'] == 1 ? "COMPLETED" : "PENDING") + '</p>';
                trs += '    </td>';
                trs += '</tr>';
            });
            //
            $('#jsReviewersBox').html(trs);
            //
            ml(false, 'jsReviewersLoader');
        });
    });

    //
    $(document).on('click', '.jsReviewSaveReviewers', function(event) {
        //
        event.preventDefault();
        //
        var obj = {};
        obj.reviwers = $('#jsReviewReviewersSelect').val() || [];
        obj.start_date = $('#jsStartDate').val();
        obj.end_date = $('#jsEndDate').val();
        //
        saveReviewersList(obj);
    });

    //
    $('.jsStopReview').click(function(event) {
        //
        event.preventDefault();
        //
        var id = $(this).closest('tr').data('id');
        var review_id = $(this).closest('tr').data('review_id');
        //
        alertify.confirm(
            "Do you really want to stop this review?",
            function() {
                stopReview(review_id, id);
            }
        );
    });

    //
    $('.jsStartReview').click(function(event) {
        //
        event.preventDefault();
        //
        var id = $(this).closest('tr').data('id');
        var review_id = $(this).closest('tr').data('review_id');
        //
        alertify.confirm(
            "Do you really want to start this review?",
            function() {
                startReview(review_id, id);
            }
        );
    });

    //
    function saveReviewersList(data) {
        //
        data.reviewId = selectedReviewId;
        data.revieweeId = selectedRevieweeId;
        //
        ml(true, 'jsAddReviewersModalLoader');
        //
        $.post(pm.urls.pbase + 'update_reviewee', data).done(function(resp) {
            //
            handleSuccess('You have successfully updated reviewers.');
            //
            ml(false, 'jsAddReviewersModalLoader');
        });

    }

    //
    async function getRevieweeReviewers() {
        //
        ml(true, 'jsAddReviewersModalLoader');
        //
        var selectedReviewers = await GetReviewDetails(selectedReviewId, selectedRevieweeId);
        //
        var options = '';
        //
        employees.map(function(emp) {
            options += '<option ' + ($.inArray(emp.Id, selectedReviewers.Data) !== -1 ? 'selected' : '') + ' value="' + (emp.Id) + '">' + (emp.Name + ' ' + emp.Role) + '</option>';
        });
        //
        $('#jsReviewReviewersSelect').html(options).select2({ closeOnSelect: false });
        $('.jsReviewReviewersBox').removeClass('dn');
        //
        ml(false, 'jsAddReviewersModalLoader');
    }

    //
    function GetReviewDetails(reviewId, revieweeId) {
        return new Promise(function(res, rej) {
            //
            $.get(
                pm.urls.pbase + 'get_reviewee_reviewes/' + reviewId + '/' + revieweeId
            ).done(function(resp) {
                res(resp);
            });
        });
    }

    //
    function getReviewerBody() {
        //
        var html = '';
        //
        html += '<div class="container">';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Select a Reviewee <span class="csRequired"></span></label>';
        html += '        </div>';
        html += '        <div class="col-sm-12">';
        html += '            <select id="jsReviewRevieweesSelect" disabled></select>';
        html += '        </div>';
        html += '    </div>';
        html += '    <div class="row jsReviewReviewersBox dn"><br>';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Select Reviewers <span class="csRequired"></span></label>';
        html += '        </div>';
        html += '        <div class="col-sm-12">';
        html += '            <select id="jsReviewReviewersSelect" multiple></select>';
        html += '        </div>';
        html += '    </div>';
        html += '    <div class="row"><br>';
        html += '    <div class="col-sm-12"><label class="csF16 csB7">Cycle Period</label></div>';
        html += '<div class="col-sm-6 col-xs-12">';
        html += '    <input type="text" readonly class="form-control" id="jsStartDate" value="' + (moment(review.Reviewees[selectedRevieweeId].start_date).format(pm.dateTimeFormats.ymdf)) + '"/>';
        html += '</div>';
        html += '<div class="col-sm-6 col-xs-12">';
        html += '    <input type="text" readonly class="form-control" id="jsEndDate"value="' + (moment(review.Reviewees[selectedRevieweeId].end_date).format(pm.dateTimeFormats.ymdf)) + '"/>';
        html += '</div>';
        html += '    </div>';
        html += '    <div class="row"><br>';
        html += '        <div class="col-sm-12">';
        html += '           <button class="btn btn-orange csF16 jsReviewSaveReviewers"><i class="fa fa-save" aria-hidden="true"></i>&nbsp; Save</button>';
        html += '        </div>';
        html += '    </div>';
        html += '</div>';
        //
        return html;
    }

    //
    function getReviewerBody2() {
        //
        var html = '';
        //
        html += '<div class="container">';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Select a Reviewee <span class="csRequired"></span></label>';
        html += '        </div>';
        html += '        <div class="col-sm-12">';
        html += '            <select id="jsReviewRevieweesSelect"></select>';
        html += '        </div>';
        html += '    </div>';
        html += '    <div class="row jsReviewReviewersBox dn"><br>';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Select Reviewers <span class="csRequired"></span></label>';
        html += '        </div>';
        html += '        <div class="col-sm-12">';
        html += '            <select id="jsReviewReviewersSelect" multiple></select>';
        html += '        </div>';
        html += '    </div>';

        html += '    <div class="row"><br>';
        html += '        <div class="col-sm-12">';
        html += '           <button class="btn btn-orange csF16 jsReviewSaveReviewers2"><i class="fa fa-save" aria-hidden="true"></i>&nbsp; Save</button>';
        html += '        </div>';
        html += '    </div>';
        html += '</div>';
        //
        return html;
    }

    //
    function stopReview(reviewId, revieweeId) {
        //
        $.post(pm.urls.pbase + 'stop_reviewee_review', { reviewId: reviewId, revieweeId: revieweeId }).done(function(resp) {
            handleSuccess("You have successfully stoped the review.", function() {
                window.location.reload();
            });
        });
    }

    //
    function startReview(reviewId, revieweeId) {
        //
        $.post(pm.urls.pbase + 'start_reviewee_review', { reviewId: reviewId, revieweeId: revieweeId }).done(function(resp) {
            handleSuccess("You have successfully started the review.", function() {
                window.location.reload();
            });
        });
    }

    //
    function getReviewersBody() {
        var html = '';
        html += '<div class="container">';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <table class="table table-striped table-condensed">';
        html += '                <caption></caption>';
        html += '                <thead>';
        html += '                    <tr>';
        html += '                        <th scope="col">Reviewer</th>';
        html += '                        <th scope="col">Reviewer Type</th>';
        html += '                        <th scope="col">Status</th>';
        html += '                    </tr>';
        html += '                </thead>';
        html += '                <tbody id="jsReviewersBox">';
        html += '                </tbody>';
        html += '            </table>';
        html += '        </div>';
        html += '    </div>';

        html += '</div>';

        return html;
    }
    //
    function saveReviewersList2(reviewId, revieweeId, reviewerIds) {
        //
        ml(true, 'jsAddReviewersModalLoader');
        //
        $.post(pm.urls.pbase + 'save_review_reviewers', {
            reviewId: reviewId,
            revieweeId: revieweeId,
            reviewerIds: reviewerIds
        }).done(function(resp) {
            //
            if (resp.Status === false) {
                handleError('Something went wrong while adding new reviewers.');
                return;
            }
            //
            handleSuccess('You have successfully added new reviewers.');
            //
            ml(false, 'jsAddReviewersModalLoader');
        });

    }
});