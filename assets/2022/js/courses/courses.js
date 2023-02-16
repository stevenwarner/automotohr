$(function () {
	//
    var courseType = "running";
	var courseURL = baseURI+'lms_courses/handler';
	//

	function getCompanyCourses () {
         $.ajax({
            type: 'POST',
            url: courseURL,
            data: {
            	'action': "get_all_courses",
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
                        resp.assignedCount,
                        resp.draftCount,
                        resp.runningCount,
                        resp.completedCount
                    );
                }
            },
            error: function() {
                $("#surveysBoxContainer").html('<p class="_csF14">Unable to load courses</p>');
                $('.jsESLoader').hide();
            }
        });
    }

    function generateOverviewPreview (
        courses,
        assignedCount,
        draftCount,
        runningCount,
        completedCount
    ) {
    	var courseBox = '';
        var courseNo = 0;
        //
        //
        $("#jsAssignedTabCount").html("("+assignedCount+")");
        $("#jsDraftTabCount").html("("+draftCount+")");
        $("#jsRunningTabCount").html("("+runningCount+")");
        $("#jsFinishedTabCount").html("("+completedCount+")");
        //

        if (courses.length) {
            courses.map(function(course) {
                courseBox += '<div class="col-md-4 col-xs-12">';
                courseBox += '    <div class="panel panel-default " data-id="1" data-title="'+course.title+'">';
                courseBox += '        <div class="panel-heading  _csB4 _csF2">';
                courseBox += '            <b class="">'+course.title+'</b>';
                courseBox += '            <span class="pull-right">';
                //
                if (courseType == "assigned" || courseType == "draft") {
                    courseBox += '                <a class="btn _csB4 _csF2 _csR5 _csF16 jsManageCoursePeriod" data-toggle="tooltip" data-placement="top" data-sid="'+course.courseID+'" data-start_date="'+course.start_date+'" data-end_date="'+course.end_date+'" title="Manage Course Dates" href="javascript:;">';
                    courseBox += '                    <i class="fa fa-cogs csF16" aria-hidden="true"></i>';
                    courseBox += '                </a>';
                }
                //
                if (courseType == "draft") {
                    courseBox += '                <a class="btn _csB4 _csF2 _csR5 _csF16" data-toggle="tooltip" data-placement="top" title="Edit Course" href="'+baseURI+'lms_courses/create/'+ course.courseID+'">';
                    courseBox += '                    <i class="fa fa-pencil csF16" aria-hidden="true"></i>';
                    courseBox += '                </a>';
                }
                //
                courseBox += '            </span>';
                courseBox += '            <div class="clearfix"></div>';
                courseBox += '        </div>';
                courseBox += '        <div class="panel-body">';
                courseBox += '            <p class="_csF14"><b>Title</b></p>';
                courseBox += '            <p class="_csF18 _csFb6">'+course.title+'</p>';
                courseBox += '            <hr />';
                courseBox += '            <p class="_csF14"><b>Cycle Period</b></p>';
                courseBox += '            <p class="_csF14">'+course.display_start_date+' <b>to</b> '+course.display_end_date+' </p>';
                courseBox += '            <hr />';
                courseBox += '            <p class="_csF14"><b>Employee(s): </b>('+course.employees+')</p>';
                courseBox += '        </div>';
                courseBox += '    </div>';
                courseBox += '</div>';
            });
        } else {
            courseBox += '<p class="_csF14 text-center">No course in '+courseType+' yet!</p>';
        }

        $("#jsCompanyCoursesSection").html(courseBox); 
        //
         $('.jsLMSLoader').hide();           

    }

    getCompanyCourses();

    /**
     * 
     */
    $(document).on('click', '.jsCoursesTab', function(event) {
        //
        courseType = $(this).data("survey_type");
        //
        if (courseType == "assigned") {
            $(".jsCoursesTab").removeClass('active');
            $("#jsAssignedTab").addClass('active');
        }

        if (courseType == "draft") {
            $(".jsCoursesTab").removeClass('active');
            $("#jsDraftTab").addClass('active');
        }

        if (courseType == "finished") {
            $(".jsCoursesTab").removeClass('active');
            $("#jsFinishedTab").addClass('active');
        }
        //
        if (courseType == "running") {
            $(".jsCoursesTab").removeClass('active');
            $("#jsRunningTab").addClass('active');
        }
        //
        getCompanyCourses();
    });

    $(document).on('click', '.jsManageCoursePeriod', function(event) {
        var courseID = $(this).data("sid");
        var startDate = $(this).data("start_date");
        var endDate = $(this).data("end_date");
        //
        $('#jsCourseManageModal').show();
        $('#jsStartDate').val(startDate);
        $('#jsEndDate').val(endDate);
        $('#jsCourseId').val(courseID);
    });

    $(document).on('click', '.jsCancelManageDate', function(event) {
        $("#jsCourseManageModal").hide();
    });

    $(document).on('click', '.jsSaveCourseDates', function(event) {
        var courseID = $('#jsCourseId').val();
        var startDate = $('#jsStartDate').val();
        var endDate = $('#jsEndDate').val();

       

        if (moment(startDate).isSameOrAfter(endDate)) {
            alertify.alert("WARNING!", "Please select end date greater then start date");
            return false;
        }

        $.ajax({
            type: 'POST',
            url: courseURL,
            data: {
                'action': "update_course",
                'employeeId': eToken,
                'companyId': cToken,
                'courseId': courseID,
                'startDate': moment(startDate).format('YYYY-MM-DD'),
                'endDate': moment(endDate).format('YYYY-MM-DD')
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
                    alertify.alert(
                        "SUCCESS!",
                        resp.Response,
                        function () {
                            $("#jsCourseManageModal").hide();
                            getCompanyCourses();
                        }
                    );
                    //
                    $('.jsLMSLoader').hide();
                    //
                    return;
                }
                //
                $('.jsESLoader').hide();
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to update course period");
                $('.jsLMSLoader').hide();
            }
        });
    });

    $('#jsStartDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '-0:+3',
        minDate: 0,
        onSelect: function (value) {
            $('#jsEndDate').datepicker('option', 'minDate', value);
        }
    }).datepicker('option', 'maxDate', $('#jsEndDate').val());

    $('#jsEndDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '-0:+3',
        minDate: 0,
        onSelect: function (value) {
            $('#jsStartDate').datepicker('option', 'maxDate', value);
        }
    }).datepicker('option', 'minDate', $('#jsStartDate').val());
})