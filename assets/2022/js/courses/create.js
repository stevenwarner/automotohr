$(function () {
    
    //
    function saveCourseDetails (courseDetails) {
        $.ajax({
            type: 'POST',
            url: baseURI+'lms_courses/handler',
            data: courseDetails,
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
                            if (resp.Id > 0) {
                                $("#show_basicInfo_section").hide();
                                //
                                if (resp.Type == "upload") {
                                    $("#show_upload_section").show();
                                    $(".step2").addClass("_csactive");

                                    $('#jsUploadScormFile').mFileUploader({
                                        fileLimit: -1, // Default is '2MB', Use -1 for no limit (Optional)
                                        allowedTypes: ['zip'], //(Optional)
                                        placeholderImage: s3Name // Default is empty ('') but can be set any image  (Optional)
                                    });

                                    $('#jsUploadScormFile').mFileUploader({
                                        allowedTypes: ['mp4', 'webm'],
                                        fileLimit: -1,
                                        onSuccess: function(o) {
                                            questionFile = o;
                                            updatePreview();
                                        },
                                        onClear: function(e) {
                                            questionFile = null;
                                            updatePreview();
                                        },
                                    });
                                }
                                //
                                if (resp.Type == "manual") {
                                    $("#show_manual_section").show();
                                }
                            }
                        }
                    );
                    //
                    $('.jsLMSLoader').hide();
                    //
                    return;
                }
            },
            error: function() {
                alertify.alert("Notice", "Unable to save course detail</b>");
                $('.jsLMSLoader').hide();
            }
        });
    }

    $('#jsStartDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '-0:+3',
        onSelect: function (value) {
            $('#jsEndDate').datepicker('option', 'minDate', value);
        }
    }).datepicker('option', 'maxDate', $('#jsEndDate').val());

    $('#jsEndDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: '-0:+3',
        onSelect: function (value) {
            $('#jsStartDate').datepicker('option', 'maxDate', value);
        }
    }).datepicker('option', 'minDate', $('#jsStartDate').val());

    $('#jsUploadScormFile').mFileUploader({
        allowedTypes: ['zip'],
        fileLimit: -1,
        onSuccess: function(o) {
            questionFile = o;
        },
        onClear: function(e) {
            questionFile = null;
        },
    });

    $(document).on('click', '.jsSaveCourseBasicDetails', function(event) {
        //
        var courseTitle = $("#jsCourseTitle").val();
        var courseDescriptio = $("#jsCourseDescription").val();
        var courseStartDate = $("#jsStartDate").val();
        var courseEndDate = $("#jsEndDate").val();
        //
        if (courseTitle == '') {
            alertify.alert("Notice", "Please Enter course Title");
            return false;
        }
        if (courseStartDate == '') {
            alertify.alert("Notice", "Please Enter course Start Date");
            return false;

        }
        if (courseEndDate == '') {
            alertify.alert("Notice", "Please Enter Survey End Date");
            return false;
        }

        var courseDetails = {
            'action': "add_course",
            'title': courseTitle,
            'start_date': moment(courseStartDate).format('YYYY-MM-DD'),
            'end_date': moment(courseEndDate).format('YYYY-MM-DD'),
            'description': courseDescriptio,
            'course_type': $('input[name="jsCourseChoice"]:checked').val(),
            'employeeId': eToken,
            'companyId': cToken
        };

        saveCourseDetails(courseDetails);
    });
}); 


