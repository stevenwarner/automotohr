$(function() {
    //
    var selectedReviewId;
    var selectedRevieweeId;
    //
    $('.jsAddReviewers').click(function(event) {
        //
        event.preventDefault();
        //
        selectedReviewId = $(this).closest('.jsReviewBox').data('id');
        //
        Modal({
            Id: 'jsAddReviewersModal',
            Title: 'Add Reviewers to ' + $(this).closest('.jsReviewBox').data('title'),
            Body: getReviewerBody(),
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
    $('.jsReviewVisibility').click(function(event) {
        //
        event.preventDefault();
        //
        var reviewId = $(this).closest('.jsReviewBox').data('id');
        //
        Modal({
            Id: 'jsManageVisibilityModal',
            Title: 'Manage Visibility for ' + $(this).closest('.jsReviewBox').data('title'),
            Body: '<div id="jsManageVisibilityModalBody"></div><input type="hidden" id="jsManageVisibilityModalINP" value="' + (reviewId) + '" />',
            Loader: 'jsManageVisibilityModalLoader'
        }, async function() {
            //
            var reviewVisibility = await GetReviewVisibility(reviewId);
            //
            $('#jsManageVisibilityModalBody').html(reviewVisibility);
            //
            $('#jsVRoles').select2({ closeOnSelect: false });
            $('#jsVDepartments').select2({ closeOnSelect: false });
            $('#jsVTeams').select2({ closeOnSelect: false });
            $('#jsVEmployees').select2({ closeOnSelect: false });
            //
            ml(false, 'jsManageVisibilityModalLoader');
        });
    });

    //
    $(document).on('click', '.jsUpdateVisibility', function(event) {
        //
        event.preventDefault();
        //
        ml(true, 'jsManageVisibilityModalLoader');
        //
        var obj = {
            reviewId: $('#jsManageVisibilityModalINP').val(),
            roles: $('#jsVRoles').val() || [],
            departments: $('#jsVDepartments').val() || [],
            teams: $('#jsVTeams').val() || [],
            employees: $('#jsVEmployees').val() || []
        };
        //
        $.post(
            pm.urls.pbase + 'update_visibility_post',
            obj
        ).done(function(resp) {
            ml(false, 'jsManageVisibilityModalLoader');
            handleSuccess("You have successfully updated the visibility.");
        });
    });

    //
    $('.jsArchiveReview').click(function(event) {
        //
        event.preventDefault();
        //
        var id = $(this).closest('.jsReviewBox').data('id');
        //
        alertify.confirm(
            "Do you really want to archive this review?",
            function() {
                archiveReview(id);
            }
        );
    });

    //
    $('.jsActivateReview').click(function(event) {
        //
        event.preventDefault();
        //
        var id = $(this).closest('.jsReviewBox').data('id');
        //
        alertify.confirm(
            "Do you really want to activate this review?",
            function() {
                activateReview(id);
            }
        );
    });

    //
    $('.jsEndReview').click(function(event) {
        //
        event.preventDefault();
        //
        var id = $(this).closest('.jsReviewBox').data('id');
        //
        alertify.confirm(
            "Do you really want to stop this review?",
            function() {
                stopReview(id);
            }
        );
    });

    //
    $('.jsStartReview').click(function(event) {
        //
        event.preventDefault();
        //
        var id = $(this).closest('.jsReviewBox').data('id');
        //
        alertify.confirm(
            "Do you really want to start this review?",
            function() {
                startReview(id);
            }
        );
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
    $(document).on('click', '.jsReviewSaveReviewers', function(event) {
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
        saveReviewersList(selectedReviewId, selectedRevieweeId, reviewersList);
    });

    //
    function saveReviewersList(reviewId, revieweeId, reviewerIds) {
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
            handleSuccess(resp.Message, function() {
                $('#jsAddReviewersModal .jsModalCancel').click();
            });
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
            options += '<option ' + ($.inArray(emp.Id, selectedReviewers.Data) !== -1 ? 'disabled' : '') + ' value="' + (emp.Id) + '">' + (emp.Name + ' ' + emp.Role) + '</option>';
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
        html += '           <button class="btn btn-orange csF16 jsReviewSaveReviewers"><i class="fa fa-save" aria-hidden="true"></i>&nbsp; Save</button>';
        html += '        </div>';
        html += '    </div>';
        html += '</div>';
        //
        return html;
    }

    //
    function archiveReview(reviewId) {
        //
        $.post(pm.urls.pbase + 'archive_review', { reviewId: reviewId }).done(function(resp) {
            handleSuccess("You have successfully archived the review.", function() {
                window.location.reload();
            });
        });
    }

    //
    function activateReview(reviewId) {
        //
        $.post(pm.urls.pbase + 'activate_review', { reviewId: reviewId }).done(function(resp) {
            handleSuccess("You have successfully activate the review.", function() {
                window.location.reload();
            });
        });
    }

    //
    function stopReview(reviewId) {
        //
        $.post(pm.urls.pbase + 'stop_review', { reviewId: reviewId }).done(function(resp) {
            handleSuccess("You have successfully stopped the review.", function() {
                window.location.reload();
            });
        });
    }

    //
    function startReview(reviewId) {
        //
        $.post(pm.urls.pbase + 'start_review', { reviewId: reviewId }).done(function(resp) {
            handleSuccess("You have successfully started the review.", function() {
                window.location.reload();
            });
        });
    }

    //
    function GetReviewVisibility(reviewId) {
        //
        return new Promise(function(res) {
            //
            $.get(pm.urls.pbase + 'get_visibility/' + reviewId)
                .done(function(resp) {
                    res(resp);
                });
        });
    }
});