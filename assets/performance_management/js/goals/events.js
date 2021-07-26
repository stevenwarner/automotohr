$(function() {
    //
    var goalId = null;

    //
    $(document).on('click', '.jsGoalStatusClose', function(event) {
        //
        event.preventDefault();
        //
        goalId = $(this).closest('.jsGoalBox').data('id');
        //
        alertify.confirm(
            "Do you really want to close this goal?",
            function() {
                ml(true, 'goal_box_' + goalId);
                //
                closeGoal();
            });
    });
    //
    function closeGoal() {
        $.post(
            pm.urls.pbase + "close_goal", {
                goalId: goalId
            }
        ).done(function(resp) {
            ml(false, 'goal_box_' + goalId);
            //
            if (resp.Status === false) {
                handleError('Failed to close goal');
                return;
            }
            //
            handleSuccess("You have successfully closed the goal", function() {
                window.location.reload();
            });
        });
    }


    //
    $(document).on('click', '.jsGoalStatusOpen', function(event) {
        //
        event.preventDefault();
        //
        goalId = $(this).closest('.jsGoalBox').data('id');
        //
        alertify.confirm(
            "Do you really want to open this goal?",
            function() {
                ml(true, 'goal_box_' + goalId);
                //
                openGoal();
            });
    });
    //
    function openGoal() {
        $.post(
            pm.urls.pbase + "open_goal", {
                goalId: goalId
            }
        ).done(function(resp) {
            ml(false, 'goal_box_' + goalId);
            //
            if (resp.Status === false) {
                handleError('Failed to open goal');
                return;
            }
            //
            handleSuccess("You have successfully opened the goal", function() {
                window.location.reload();
            });
        });
    }

    //
    $(document).on('click', '.jsExpandGoal', function(event) {
        //
        event.preventDefault();
        var ref = $(this).closest('.jsGoalBox').parent().parent();
        if (!ref.hasClass('col-md-12')) {
            ref.removeClass('col-md-4');
            ref.addClass('col-md-12');
        } else {
            ref.addClass('col-md-4');
            ref.removeClass('col-md-12');
        }
    });

    //
    $(document).on('click', '.jsGoalUpdateBTN', function(event) {
        //
        event.preventDefault();
        //
        goalId = $(this).closest('.jsGoalBox').data('id');
        //
        BoxMover('update');
        //
        $('.jsGoalTrack' + goalId).select2({
            minimumResultsForSearch: -1
        });
    });

    //
    $(document).on('click', '.jsGoalUpdateBtn', function(event) {
        //
        event.preventDefault();
        //
        var obj = {};
        obj.on_track = $('.jsGoalTrack' + goalId).val();
        obj.completed = $('.jsGoalCompletedTarget' + goalId).val();
        obj.target = $('.jsGoalTarget' + goalId).val();
        obj.goalId = goalId;
        //
        if (obj.completed == '') {
            handleError("Target completed is required.");
            return;
        }
        //
        if (obj.target == '') {
            handleError("Target is required.");
            return;
        }
        //
        ml(true, 'goal_box_' + goalId);
        //
        $.post(
            pm.urls.pbase + 'update_goal',
            obj
        ).done(function(resp) {
            //
            handleSuccess('You have successfully updated the goal.', function() {
                window.location.reload();
            });
        });
    });


    //
    $(document).on('click', '.jsBoxSectionBackBtn', function(event) {
        //
        event.preventDefault();
        //
        BoxMover('main');
    });


    //
    $(document).on('click', '.jsGoalCommentBtn', function(event) {
        //
        event.preventDefault();
        //
        goalId = $(this).closest('.jsGoalBox').data('id');
        //
        BoxMover('comment');
        //
        ml(true, 'goal_box_' + goalId);
        //
        fetchComments();
    });

    //
    $(document).on('click', '.jsGoalCommentSaveBtn', function(event) {
        //
        event.preventDefault();
        //
        goalId = $(this).closest('.jsGoalBox').data('id');
        //
        var obj = {};
        obj.msg = $('.jsGoalComment' + goalId).val().trim();
        obj.goalId = goalId;
        //
        if (obj.msg == '') {
            handleError("Please type a comment");
            return;
        }
        //
        $.post(
            pm.urls.pbase + 'add_comment',
            obj
        ).done(function() {
            //
            handleSuccess("You have successfully commented on this goal", function() {
                //
                $('.jsGoalComment' + goalId).val('');
                //
                fetchComments();
            });
        });
    });


    //
    function fetchComments() {
        $.get(
            pm.urls.pbase + 'goal_comments/' + goalId
        ).done(function(resp) {
            //
            if (resp.Data.length == 0) {
                //
                ml(false, 'goal_box_' + goalId);
                //
                return;
            }
            //
            var html = '';
            //
            resp.Data.map(function(comment) {
                html += '<li>';
                html += '   <p class="csF16 ' + (comment.sender_sid == pm.employerId ? 'text-right' : '') + '">';
                html += comment.message;
                html += '       <br>';
                html += '       <br>';
                html += '       <span class="csF12 csB7">' + (moment(comment.created_at).format(pm.dateTimeFormats.mdyt)) + '</span>';
                html += '       <span class="csF12 csB7">' + (comment.first_name) + ' ' + (comment.last_name) + '</span>';
                html += '   </p>';
                html += '</li>';
            });
            //
            $('.jsGoalCommentWrap' + goalId).html(html);
            ml(false, 'goal_box_' + goalId);
            $('.jsGoalCommentWrap' + goalId).scrollTop($('.jsGoalCommentWrap' + goalId)[0].scrollHeight);
        });
    }


    //
    function BoxMover(step) {
        $('.jsGoalBox[data-id=' + (goalId) + '] .jsBoxSection').addClass('dn');
        $('.jsGoalBox[data-id=' + (goalId) + '] .jsBoxSection[data-key="' + (step) + '"]').removeClass('dn');
    }
});