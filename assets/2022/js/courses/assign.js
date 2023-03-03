$(function () {
	//
    var courseType = "pending";
    var courseID = courseToken;
    var chapterID = chapterToken;
	var courseURL = baseURI+'lms_courses/handler';
	//

	function getAssignedCourses () {
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: {
            	'action': "get_assigned_courses",
	            'employeeId': eToken,
	            'companyId': cToken,
                'type': courseType
            },
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    $('.jsLMSLoader').hide();
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    generateOverviewPreview(
                        resp.Courses,
                        resp.pendingCount,
                        resp.completedCount
                    );
                }
            },
            error: function() {
                $("#surveysBoxContainer").html('<p class="_csF14">Unable to load courses</p>');
                $('.jsLMSLoader').hide();
            }
        });
    }

    function generateOverviewPreview (
        courses,
        pendingCount,
        completedCount
    ) {
        var courseBox = '';
        var courseNo = 0;
        //
        //
        $("#jsPendingTabCount").html("("+pendingCount+")");
        $("#jsCompletedTabCount").html("("+completedCount+")");
        //
        if (courses.length) {
            courses.map(function(course) {
                courseBox += '<div class="col-md-4 col-xs-12">';
                courseBox += '    <div class="panel panel-default " data-id="1" data-title="'+course.title+'">';
                courseBox += '        <div class="panel-heading  _csB4 _csF2">';
                courseBox += '            <b>'+course.title+'</b>';
                courseBox += '            <span class="pull-right">';
                courseBox += '                <a class="btn _csB4 _csF2 _csR5  _csF16 " title="Start the course" placement="top" href="'+baseURI+'lms_courses/my_course/'+ course.courseID+'">';
                courseBox += '                    <i class="fa fa-eye csF16" aria-hidden="true"></i>';
                courseBox += '                </a>';
                courseBox += '            </span>';
                courseBox += '            <div class="clearfix"></div>';
                courseBox += '        </div>';
                courseBox += '        <div class="panel-body">';
                courseBox += '            <p class="_csF14"><b>Description</b></p>';
                courseBox += '            <p class="_csF14">'+course.description+'</p>';
                courseBox += '            <hr />';
                courseBox += '            <p class="_csF14"><b>Cycle Period</b></p>';
                courseBox += '            <p class="_csF14">'+course.display_start_date+' <b>to</b> '+course.display_end_date+' </p>';
                courseBox += '            <hr />';
                courseBox += '            <p class="_csF14"><b>Course Progress</b></p>';
                courseBox += '            <p class="_csF14">'+course.status+'</p>';
                courseBox += '            <p class="_csF3"><b>'+course.progress+'% Completed</b></p>';
                courseBox += '        </div>';
                courseBox += '    </div>';
                courseBox += '</div>';
            });
        } else {
            courseBox += '<p class="_csF14 text-center">No course in '+courseType+' yet!</p>';
        }
        //
        $("#jsAssignedCoursesSection").html(courseBox); 
        $('.jsLMSLoader').hide();           
    }

    function markVideoAsCompleted (dataObject) {
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: dataObject,
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    $('.jsLMSLoader').hide();
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    alertify.alert(
                        "SUCCESS!",
                        resp.Response,
                        function () {
                            window.location.reload();
                        }
                    );
                    //
                    $('.jsLMSLoader').hide();
                }
            },
            error: function() {
                $("#surveysBoxContainer").html('<p class="_csF14">Unable to mark video.</p>');
                $('.jsLMSLoader').hide();
            }
        });
    }

    function saveChapterQuiz (dataObject) {
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: dataObject,
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    $('.jsLMSLoader').hide();
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    alertify.alert(
                        "SUCCESS!",
                        resp.Response,
                        function () {
                            window.location.reload();
                        }
                    );
                    //
                    $('.jsLMSLoader').hide();
                }
            },
            error: function() {
                $("#surveysBoxContainer").html('<p class="_csF14">Unable to save chapter quiz</p>');
                $('.jsLMSLoader').hide();
            }
        });
    }

    getAssignedCourses();

    $('#jsChapterVideo').on('ended', function() {
        var dataObject = {
            'action': "video_completed",
            'employeeId': eToken,
            'companyId': cToken,
            'courseId': courseID,
            'chapterId': chapterID
        }
        //
        markVideoAsCompleted(dataObject);
    });

    $(document).on('click', '#jsChapterQuestionSaveBTN', function(event) {
        var quiz_obj = {};
        //
        $('input:radio:checked').each(function() {
            quiz_obj[this.placeholder] = this.value;
        });
        //
        $('input.textbox').map(function() {
            quiz_obj[this.name] = this.value;
        });
        //
        var dataObject = {
            'action': "quiz_completed",
            'employeeId': eToken,
            'companyId': cToken,
            'courseId': courseID,
            'chapterId': chapterID,
            'quiz': JSON.stringify(quiz_obj)
        }
        //
        saveChapterQuiz(dataObject);
    });

    /**
     * 
     */
    $(document).on('click', '.jsCourseTab', function(event) {
        //
        courseType = $(this).data("course_type");
        //
        if (courseType == "pending") {
            $("#jsPendingTab").addClass('active');
            $("#jsCompletedTab").removeClass('active');
        }

        if (courseType == "completed") {
            $("#jsPendingTab").removeClass('active');
            $("#jsCompletedTab").addClass('active');
        }
        //
        getAssignedCourses();
    });
});    