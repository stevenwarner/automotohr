$(function () {
    //
    var uploadFile = null;
    var courseURL = baseURI+'lms_courses/handler';
    var courseID = 0;
    var employees = {};
    var departments = {};
    var respondentSids = {};
    var jobTitles = {};
    var includedEmployeesSid = [];
    var departmentInfo = [];
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
                                //
                                courseID = resp.Id;
                                //
                                generateCourePreview(resp.Type);
                                //
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

    generateCourePreview("manual");
    //
    function generateCourePreview (type) {
        //
        $("#show_basicInfo_section").hide();
        //
        $(".step2").addClass("_csactive");
        //
        if (type == "upload") {
            //
            $("#show_upload_section").show();
            //
            $('#jsUploadScormFile').mFileUploader({
                allowedTypes: ['zip'],
                fileLimit: -1,
                onSuccess: function(o) {
                    uploadFile = o;
                },
                onClear: function(e) {
                    uploadFile = null;
                },
            });
        }
        //
        if (type == "manual") {
            $("#show_manual_section").show();
        }
    }

    //
    function uploadZip(zip, courseInfo, type = "insert") {
        var fd = new FormData();
        fd.append('upload_zip', zip);
        fd.append('action', 'upload_zip');
        fd.append('employeeId', eToken);
        fd.append('companyId', cToken);
        fd.append('courseId', courseID);
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
                        resp.Response,
                        function () {
                            $(".step3").addClass("_csactive");
                            $("#show_upload_section").hide();
                            $("#show_employees_section").show();
                            setupEmployeesPreview();
                        }
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

    async function setupEmployeesPreview () {
        $('.jsLMSLoader').show();
        //
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
        employees = await getCompanyEmployees("all", "all", "all", "all", "all");
        assignedSids = await getAssignedEmployees();
        departments = await getCompanyDepartments();
        jobTitles = await getCompanyJobTitles();
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
            createEmployeeList(employees, assignedSids);
            //
            employees.map(function(employee) {
                //
                employeeOptions += '<option value="' + (employee['sid']) + '">' + (remakeEmployeeName(employee)) + '</option>';
            });
        }
        
        //
        if (jobTitles.length) {
            jobTitles.map(function(title) {
                jobTitleOptions += '<option value="' + (title.key) + '">' + (title.value) + '</option>';
            });
        }
        //
        jobTypeOptions+= '<option value="fulltime">Full Time</option>';
        jobTypeOptions+= '<option value="parttime">Part Time</option>';
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
        $('.jsLMSLoader').hide();
        //
    }

    //
    function remakeEmployeeName(o, i) {
        //
        var r = '';
        //
        if (i == undefined) r += o.first_name + ' ' + o.last_name;
        //
        if (o.job_title != '' && o.job_title != null) r += ' (' + (o.job_title) + ')';
        //
        r += ' [';
        //
        if (typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
        //
        if (o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1) r += o['access_level'] + ' Plus / Payroll';
        else if (o['access_level_plus'] == 1) r += o['access_level'] + ' Plus';
        else if (o['pay_plan_flag'] == 1) r += o['access_level'] + ' Payroll';
        else r += o['access_level'];
        //
        r += ']';
        //
        return r;
    }

    async function getFilterCompanyEmployees () {
        var departmentSids = $('#jsDepartments').val() || 'all';
        var includedEmployees = $('#jsEmployees').val() || 'all';
        var excludedEmployees = $('#jsExcludedEmployees').val() || 'no';
        var jobTitles = $('#jsJobTitles').val() || 'all';
        var employeeTypes = $('#jsEemployeeType').val() || 'all';
        //
        employeesList = await getCompanyEmployees(departmentSids, includedEmployees, excludedEmployees, employeeTypes, jobTitles);
        //
        createEmployeeList(employeesList)
    }

    function createEmployeeList (employeesList, respondentSids = '') {
        
        var employeeRow = "";
        var employeeNo = 0;
        //
        if (employeesList.length) {
            employeesList.map(function(employee) {
                
                if (respondentSids.length) {
                    if($.inArray(employee.sid, respondentSids) !== -1) {
                        employeeRow += '<tr class="jsSelectedEmployees" data-employee_sid="'+employee.sid+'">';
                        employeeRow += '<th scope="col">'+remakeEmployeeName(employee)+'</th>';

                        if (departmentInfo.length) {
                            employeeRow += employee.department_sid != 0 ? '<td>'+departmentInfo[employee.department_sid]+'</td>' :  '<td>No Department</td>';
                        } else {
                            employeeRow += '<td>No Department</td>';
                        }
                        employeeRow += '</tr>';
                        //
                        employeeNo++; 
                    }
                } else {
                    employeeRow += '<tr class="jsSelectedEmployees" data-employee_sid="'+employee.sid+'">';
                    employeeRow += '<th scope="col">'+remakeEmployeeName(employee)+'</th>';

                    if (departmentInfo.length) {
                        employeeRow += employee.department_sid != 0 ? '<td>'+departmentInfo[employee.department_sid]+'</td>' :  '<td>No Department</td>';
                    } else {
                        employeeRow += '<td>No Department</td>';
                    }
                    employeeRow += '</tr>';
                    //
                    employeeNo++; 
                }
            });
            //
        } else {
            employeeRow += '<tr><td colspan="2" class="text-center"><b>No Employee Found</b></td></tr>';
        }
        //
        $("#jsCompanyEmployeesList").html(employeeRow);
        $("#jsAssignedEmployeesCount").html('('+employeeNo+')');
    }

    function getCompanyEmployees(departments, included, excluded, type, title) {
        
        return new Promise(resolve => {
            $.ajax({
                type: 'POST',
                url: courseURL,
                data: {
                    action: "get_employees",
                    employeeId: eToken,
                    companyId: cToken,
                    department_sids: departments,
                    included_sids: included,
                    excluded_sids: excluded,
                    employee_types: type,
                    job_titles: title
                }, 
                success: function(resp) {
                    if (resp.Status === true) {
                        resolve(resp.Employees);
                    } 
                   
                    if (resp.Status === false) {
                        resolve(false);
                    }
                },
                error: function() {
                    resolve(false);
                }
            });
        });    
    }

    //
    function getCompanyDepartments () {
        return new Promise(resolve => {
            $.ajax({
                type: 'POST',
                url: courseURL,
                data: {
                    action: "get_departments",
                    employeeId: eToken,
                    companyId: cToken
                },
                success: function(resp) {
                    if (resp.Status === true) {
                        resolve(resp.Departments);
                    } 
                   
                    if (resp.Status === false) {
                        resolve(false);
                    }
                },
                error: function() {
                    resolve(false);
                }
            });
        });
    }

    //
    function getCompanyJobTitles () {
        return new Promise(resolve => {
            $.ajax({
                type: 'POST',
                url: courseURL,
                data: {
                    action: "get_job_titles",
                    employeeId: eToken,
                    companyId: cToken
                },
                success: function(resp) {
                    if (resp.Status === true) {
                        resolve(resp.JobTitles);
                    } 
                   
                    if (resp.Status === false) {
                        resolve(false);
                    }
                },
                error: function() {
                    resolve(false);
                }
            });
        });
    }

    //
    function getAssignedEmployees () {
        return new Promise(resolve => {
            $.ajax({
                type: 'POST',
                url: courseURL,
                data: {
                    action: "get_assigned_employees",
                    employeeId: eToken,
                    companyId: cToken,
                    courseId: courseID
                },
                success: function(resp) {
                    if (resp.Status === true) {
                        resolve(resp.AssignedEmployees);
                    } 
                   
                    if (resp.Status === false) {
                        resolve(false);
                    }
                },
                error: function() {
                    resolve(false);
                }
            });
        });
    }

    //
    function saveAssignedEmployees (selectedEmployees) {
        //
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: {
                action: "save_assigned_employees",
                employeeId: eToken,
                companyId: cToken,
                courseId: courseID,
                employees: selectedEmployees
            },
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
                //
                alertify.alert('SUCCESS!','Employee survey respondents saved sucessfully.',function () {
                    if (resp.Publish == 1) {
                        publishCompanySurvey(resp);
                    }
                    
                });
                //
                $('.jsLMSLoader').hide();
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to save employee survey respondents");
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
        if (uploadFile == null || Object.keys(uploadFile).length === 0 || uploadFile.error) {
            alertify.alert("WARNING!", "Please upload a zip file.");
            return;
        }
        //
        uploadZip(uploadFile);
        
    });

    $(document).on('click', '.jsGetFilterEmployees', function(event) {
        getFilterCompanyEmployees();
    });

    $(document).on('click', '.jsClearFilter', function(event) {
        //
        $("#jsJobTitles").select2("val", "");
        $("#jsDepartments").select2("val", "");
        $("#jsEemployeeType").select2("val", "");
        $("#jsEmployees").select2("val", "");
        $("#jsExcludedEmployees").select2("val", "");
        //
        getFilterCompanyEmployees();
    });

    $(document).on('change', '#jsEmployees', function(event) {
        var selectedEmployees = $("#jsEmployees").val();
        //
        if (selectedEmployees) {
            selectedEmployees.map(function(employeeSid) {
                $("#jsExcludedEmployees option[value='"+employeeSid+"']").remove();
            });
        }    
    });

    $(document).on('change', '#jsExcludedEmployees', function(event) {
        var selectedEmployees = $("#jsExcludedEmployees").val();
        //
        if (selectedEmployees) {
            selectedEmployees.map(function(employeeSid) {
                $("#jsEmployees option[value='"+employeeSid+"']").remove();
                //
                 return parseInt(employeeSid);
            });
        }
        
    });

    $(document).on('click', '.jsSaveAssignedEmployees', function(event) {
        var employees = [];
            //
        $('.jsSelectedEmployees').each(function(index,item){
            employees.push(parseInt($(item).data('employee_sid')));
        });
        //
        if (employees.length) {
            saveAssignedEmployees(employees);
        } else {
            alertify.alert("NOTICE!","Please select employees first",function () {
                return false;
            });
        }
    });
}); 


