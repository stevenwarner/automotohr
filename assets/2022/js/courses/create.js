$(function () {
    //
    var uploadFile = null;
    var courseURL = baseURI+'lms_courses/handler';
    //
    function saveCourseDetails (courseDetails) {
        $.ajax({
            type: 'POST',
            url: courseURL,
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
    //
    function uploadZip(zip, courseInfo, type = "insert") {
        var fd = new FormData();
        fd.append('upload_zip', zip);
        fd.append('action', 'upload_zip');
        fd.append('employeeId', eToken);
        fd.append('companyId', cToken);
        fd.append('courseId', courseInfo.courseID);
        //
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: fd,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
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
                        "WARNING!",
                        resp.Response
                    );
                    //
                    $('.jsLMSLoader').hide();
                    //
                    return;
                }
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to upload zip");
                $('.jsLMSLoader').hide();
            }
        });
    }

    function CreateEmployeeList () {
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: {
                action: "get_employees_list",
                employeeId: eToken,
                companyId: cToken
            },
            success: function(resp) {
                if ($('#jsRoles').data('select2')) {
                    $('#jsRoles').data('select2').destroy()
                    $('#jsRoles').remove()
                }
                //
                if ($('#jsDepartments').data('select2')) {
                    $('#jsDepartments').data('select2').destroy()
                    $('#jsDepartments').remove()
                }
                //
                if ($('#jsEmployees').data('select2')) {
                    $('#jsEmployees').data('select2').destroy()
                    $('#jsEmployees').remove()
                }
                //
                if ($('#jsExcludedEmployees').data('select2')) {
                    $('#jsExcludedEmployees').data('select2').destroy()
                    $('#jsExcludedEmployees').remove()
                }
                //
                //
                var employeeOptions = "";
                var departmentOptions = "";
                var jobTitleOptions = "";
                var jobTypeOptions = "";
                var employeeNo = 0;
                //
                //
                if (departments.length) {
                    departments.map(function(department) {
                        departmentOptions += '<option value="' + (department['sid']) + '">' + (department['name']) + '</option>';
                        departmentInfo[department['sid']] = department['name'];
                    });
                }
                //
                if (employees.length) {
                    createEmployeeList(employees, respondentSids, );
                    //
                    employees.map(function(employee) {
                        //
                        employeeOptions += '<option value="' + (employee['sid']) + '">' + (remakeEmployeeName(employee)) + '</option>';
                    });
                }
                
                //
                if (jobTitles.length) {
                    jobTitles.map(function(title) {
                        jobTitleOptions += '<option value="' + (title) + '">' + (title) + '</option>';
                    });
                }
                //
                jobTypeOptions+= '<option value="fulltime">Full Time</option>';
                jobTypeOptions+= '<option value="parttime">Part Time</option>';
                //
                
                //
                $('#jsEmployees')
                    .html(employeeOptions)
                    .select2({
                        closeOnSelect: false
                    });
                //    
                $('#jsExcludedEmployees')
                    .html(employeeOptions)
                    .select2({
                        closeOnSelect: false
                    });
                //
                $('#jsDepartments')
                    .html(departmentOptions)
                    .select2({
                        closeOnSelect: false
                    });
                //
                $('#jsJobTitles')
                    .html(jobTitleOptions)
                    .select2({
                        closeOnSelect: false
                    });   
                //
                $('#jsEemployeeType')
                    .html(jobTypeOptions)
                    .select2({
                        closeOnSelect: false
                    });         
                //
                $('.jsESLoader').hide();
        //
                
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to load company employees data");
                $('.jsESLoader').hide();
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
        allowedTypes: ['zip','pdf'],
        fileLimit: -1,
        onSuccess: function(o) {
            uploadFile = o;
        },
        onClear: function(e) {
            uploadFile = null;
        },
    });

    CreateEmployeeList();

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

    /**
     * 
     */
    $(document).on('click', '#jsUploadZip', function(event) {
        //
        event.preventDefault();
        //
        var courseinfo = {
            action: "upload_zip_info",
            courseID: 3,
            employeeId: eToken,
            companyId: cToken
        };
        //
        if (uploadFile == null || Object.keys(uploadFile).length === 0 || uploadFile.error) {
            alertify.alert("WARNING!", "Please upload a zip file.");
            return;
        }
        //
        uploadZip(uploadFile, courseinfo);
        
    });
}); 


