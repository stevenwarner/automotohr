$(function () {
    //
    var uploadFile = null;
    var courseURL = baseURI+'lms_courses/handler';
    var courseID = 0;
    var chapterID = 0;
    var employees = {};
    var departments = {};
    var respondentSids = {};
    var jobTitles = {};
    var includedEmployeesSid = [];
    var departmentInfo = [];
    var questionCount = 1;
    var intRegex = /^\d+$/;
    var updateQuestionID= 0;
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

    // generateCourePreview("manual");
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
            $("#jsAddNewChapterSection").hide();
            $('#jsAddQuestionType').select2({
                closeOnSelect: false
            });
            //
            $('#jsUploadChapterVideoUpload').mFileUploader({
                allowedTypes: ['mp4', 'webm'],
                fileLimit: -1,
                onSuccess: function(o) {
                    uploadFile = o;
                },
                onClear: function(e) {
                    uploadFile = null;
                },
            });
            //
            getManualCourseDetail();
        }
    }

    function getManualCourseDetail () {
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: {
                action: "get_manual_course_detail",
                employeeId: eToken,
                companyId: cToken,
                courseId: courseID,
            },
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
                //
                $('.jsLMSLoader').hide();
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    generateManualCourseDetail(resp.CourseInfo);
                }
                //
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to save question");
                $('.jsLMSLoader').hide();
            }
        });
    }

    function generateManualCourseDetail (CourseInfo) {
        var chapterBox = '';
        var chapterNo = 0;
        //
        if (CourseInfo.length) {
            CourseInfo.map(function(CC) {
                chapterBox += '<article class="listing-article">';
                chapterBox += ' <figure>';
                chapterBox += '        <video id="video" width="214" height="145">';
                chapterBox += '            <source src="'+CC.videoURL+'" type="video/mp4">';
                chapterBox += '        </video>';
                chapterBox += '    </figure>';
                chapterBox += '    <div class="text">';
                chapterBox += '        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4" style="padding-left: 0px !important;">';
                chapterBox += '            <h3>'+CC.title+'</h3>';
                chapterBox += '        </div>';
                chapterBox += '        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">';      
                chapterBox += '            <span class="pull-right badge badge-warning pending">';
                chapterBox += '                <i class="fa fa-ban" aria-hidden="true"></i>';
                chapterBox += '                 Question(s): '+CC.question_count;
                chapterBox += '            </span>';
                chapterBox += '        </div>';
                chapterBox += '        <div class="post-options">';
                chapterBox += '            <ul>';
                chapterBox += '                <li class="video_time_log">';
                chapterBox += '                    <b>Created By : </b>';
                chapterBox +=                       CC.created_by;                   
                chapterBox += '                </li>';
                chapterBox += '                <li class="video_time_log">';
                chapterBox += '                    <b>Created On : </b>';
                chapterBox +=                       CC.created_on;
                chapterBox += '            </ul>';
                chapterBox += '             <span class="post-author">';
                chapterBox += '                 <a href="javascript:;"" data-chapter_sid="'+CC.chapterID+'" class="btn btn-block btn-info jsUpdateChapterQuestion">';
                chapterBox += '                     Add Question';
                chapterBox += '                 </a>';
                chapterBox += '             </span>';
                chapterBox += '        </div>';
                chapterBox += '        <div class="full-width announcement-des" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">';
                chapterBox +=               CC.description;          
                chapterBox += '        </div>';
                chapterBox += '    </div>';
                chapterBox += '</article>';
                //
                chapterNo++;
            });
        } else {
            
            chapterBox += '<div class="row">';
            chapterBox += ' <div class="col-md-12 col-xs-12">';
            chapterBox += '     <p colspan="2" class="text-center"><b>No chapter added yet.</b></p>';
            chapterBox += ' </div>';
            chapterBox += '</div>';
        }

        if (chapterNo == 0) {
            $(".jsAssignEmployeesToCourseBTN").hide();
        } else if (chapterNo > 0) {
            $(".jsAssignEmployeesToCourseBTN").show();
        }

        $("#jsCourseChaptersList").html(chapterBox)    
        $("#jsSurveyQuestionCount").html(chapterNo);    
    }

    //
    function uploadZip(zip, type = "insert") {
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
                        "SUCCESS!",
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

    //
    function uploadCourseVideo(video, title, description) {
        var fd = new FormData();
        fd.append('video', video);
        fd.append('action', 'upload_video');
        fd.append('employeeId', eToken);
        fd.append('companyId', cToken);
        fd.append('courseId', courseID);
        fd.append('title', title);
        fd.append('description', description);
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
                        "SUCCESS!",
                        resp.Response,
                        function () {
                            chapterID = resp.chapterId;
                            console.log(chapterID )
                            createChapterPreview();
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

    function saveChapterQuestion (questionInfo) {
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: questionInfo,
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
                //
                $('.jsLMSLoader').hide();
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    alertify.alert(
                        "SUCCESS!",
                        resp.Response,
                        function () {
                            $('#jsQuestionTitle').val("");
                            $('#jsQuestionAnswer').val("");
                            $('#jsQuestionType').select2('val', "text");
                            if (resp.QuestionCount) {
                                questionCount = resp.QuestionCount;
                            }
                            createChapterPreview();
                        }
                    );
                }
                //
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to save question");
                $('.jsLMSLoader').hide();
            }
        });
    }

    function createTextQuestion () {
        textQuestion = '';
        //
        textQuestion += '<div class="row"><br>';
        textQuestion += '   <div class="col-xs-12">';
        textQuestion += '       <input type="text" class="form-control _csTAD" readonly />';
        textQuestion += '   </div>';
        textQuestion += '</div>';
        //
        return textQuestion;
    }

    function createBooleanQuestion (no) {
        booleaQuestion = '';
        //
        booleaQuestion += '<div class="row"><br>';
        booleaQuestion += '<div class="col-xs-12">';
        booleaQuestion += '  <label class="control control--radio _csF14">';
        booleaQuestion += '      <input type="radio" name="jsQuestionChoice'+no+'" value="yes" />';
        booleaQuestion += '        Yes';
        booleaQuestion += '      <span class="control__indicator"></span>';
        booleaQuestion += '  </label>';
        booleaQuestion += '  <br />';
        booleaQuestion += '  <label class="control control--radio _csF14">';
        booleaQuestion += '      <input type="radio" name="jsQuestionChoice'+no+'" value="no" />';
        booleaQuestion += '          No';
        booleaQuestion += '      <span class="control__indicator"></span>';
        booleaQuestion += '  </label>';
        booleaQuestion += '</div>';
        booleaQuestion += '</div>';
        //
        return booleaQuestion;
    }

    async function createChapterPreview () {
        //
        $("#jsAddCourseVideo").hide();
        $("#jsAddCourseQueston").hide();
        $("#jsChepterListSection").hide();
        //
        $("#jsAddNewChapterSection").show();
        $("#jsChapterPreviewSection").show();
        //
        var chapter = await getChapterInfo();
        //
        $("#jsVideoPreview").attr('src', chapter.videoUrl);
        $("#jsVideoPreviewSection video")[0].load();
        //
        var questions_html = '';
        //
        if (chapter.questions.length) {
            var questionNo = 1;
            var questionBox = '';
            //
            chapter.questions.map(function(CQ) {
                questionBox += '<div class="jsBox _csBox _csP10 jsChapterQuestionSort" id="div_'+CQ.sid+'" data-question_sid="'+CQ.sid+'">';
                questionBox += '    <div class="row">';
                questionBox += '        <div class="col-md-6">';
                questionBox += '            <label>Question '+questionNo+' </label>';
                questionBox += '        </div>';
                questionBox += '        <div class="col-md-6 text-right">';
                questionBox += '            <button class="btn _csR5 jsRearrangeUpQuestion" data-question_sid="'+CQ.sid+'" data-sort_order="'+CQ.sort_order+'"> <i class="fa fa-long-arrow-up" aria-hidden="true"></i></button>';
                questionBox += '            <button class="btn _csR5 jsRearrangeDownQuestion" data-question_sid="'+CQ.sid+'" data-sort_order="'+CQ.sort_order+'"> <i class="fa fa-long-arrow-down" aria-hidden="true"></i></button>';
                questionBox += '            <button class="btn btn-warning _csR5 jsEditQuestion" data-question_sid="'+CQ.sid+'"> <i class="fa fa-pencil" aria-hidden="true"></i></button>';
                questionBox += '            <button class="btn btn-danger _csR5 jsDeleteQuestion" data-question_sid="'+CQ.sid+'"> <i class="fa fa-trash" aria-hidden="true"></i></button>';
                questionBox += '        </div>';
                questionBox += '    </div>';
                questionBox += '    <div class="row">';
                questionBox += '        <div class="col-md-12">';
                questionBox += '            <h4 class="_csF14">'+CQ.question+'</h4>';
                questionBox += '        </div>';
                questionBox += '    </div>';
                // questionBox += '</div>';
                questionBox +=      CQ.type == 'boolean' ? createBooleanQuestion(questionNo) : createTextQuestion();
                questionBox += '</div>';
                //
                questionNo++;
            });
            //
            questions_html += '<div class="panel panel-default _csMt10">';
            questions_html += '    <div class="panel-body">';
            questions_html += '        <div class="row">';
            questions_html += '            <div class="col-md-12 col-xs-12">';
            questions_html +=                   questionBox;
            questions_html += '            </div>';
            questions_html += '        </div>';
            questions_html += '    </div>';
            questions_html += '</div>';
            //
        } else {
            questions_html += '<div class="panel panel-default _csMt10">';
            questions_html += '    <div class="panel-body">';
            questions_html += '        <div class="row">';
            questions_html += '            <div class="col-md-12 col-xs-12">';
            questions_html += '                 No question added yet.';
            questions_html += '            </div>';
            questions_html += '        </div>';
            questions_html += '    </div>';
            questions_html += '</div>';
        }
        //
        $("#jsCourseQuestionSection").html(questions_html);
    }

    //
    function getChapterInfo () {
        return new Promise(resolve => {
            $.ajax({
                type: 'POST',
                url: courseURL,
                data: {
                    action: "get_chapter_detail",
                    employeeId: eToken,
                    companyId: cToken,
                    courseId: courseID,
                    chapterId: chapterID,
                },
                success: function(resp) {
                    if (resp.Status === true) {
                        var returnResult = {
                            'videoUrl': resp.VideoURL,
                            'questions': resp.Questions,
                        }
                        resolve(returnResult);
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

    function resetSortQuestions (sortOrder) {
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: {
                'action': "update_sort_order",
                'employeeId': eToken,
                'companyId': cToken,
                'courseId': courseID,
                'chapterId': chapterID,
                'sortOrder' : sortOrder
            },
            success: function(resp) {
                $('.jsLMSLoader').hide();
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    alertify.alert(
                        "SUCCESS!",
                        resp.Responses
                    );
                }
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to sort question");
                $('.jsLMSLoader').hide();
            }
        });
    }

    async function setupEmployeesPreview () {
        $('.jsLMSLoader').show();
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
        if (employees.length) {
            createEmployeeList(employees, assignedSids);
        }    
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
        $('.jsLMSLoader').show();
        //
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
        //
        $('.jsLMSLoader').hide();
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
                $('.jsLMSLoader').hide();
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    alertify.confirm(
                        resp.Response,
                        function () {
                            markCourseAsComplete();
                        }
                    ).set('labels', {
                        ok: 'Yes',
                        cancel: 'No'
                    });
                }
                //
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to save employee");
                $('.jsLMSLoader').hide();
            }
        });
    }

    //
    function markCourseAsComplete () {
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: {
                action: "finish_course",
                employeeId: eToken,
                companyId: cToken,
                courseId: courseID
            },
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
                //
                $('.jsLMSLoader').hide();
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    alertify.alert(
                        "SUCCESS!",
                        resp.Response,
                        function () {
                            if (resp.Redirect === true) {
                                window.location.href = resp.RedirectURL;
                            }
                        }
                    );
                }
                //
            },
            error: function() {
                alertify.alert("NOTICE!", "Unable to finish the course");
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

    $(document).on('click', '#jsAddNewChapterBTN', function(event) {
        $("#jsChepterListSection").hide();
        $("#jsAddNewChapterSection").show();
        $("#jsAddCourseVideo").show();
        $("#jsAddCourseQueston").hide();
        $("#jsChapterPreviewSection").hide();
        //
        $("#jsChapterTitle").val("");
        $("#jsChapterDescription").val("");
        $('#jsUploadChapterVideoUpload').mFileUploader('clear');
    });

    /**
     * 
     */
    $(document).on('click', '#jsbackToChapterListBTN', function(event) {
        chapterID = 0;
        $("#jsChepterListSection").show();
        $("#jsAddNewChapterSection").hide();
        $("#jsChapterPreviewSection").hide();
        $("#jsAddCourseQueston").hide();
        $("#jsAddCourseVideo").hide();
        //
        getManualCourseDetail();
    });

    /**
     * 
     */
    $(document).on('click', '#jsUploadCourseVideo', function(event) {
        //
        event.preventDefault();
        //
        var title = $("#jsChapterTitle").val();
        var description = $("#jsChapterDescription").val();
        //
        if (title == '') {
            alertify.alert("WARNING!", "Please add the chapter title.");
            return;
        }
        //
        if (uploadFile == null || Object.keys(uploadFile).length === 0 || uploadFile.error) {
            alertify.alert("WARNING!", "Please upload a course video.");
            return;
        }
        //
        uploadCourseVideo(uploadFile, title, description);
    });

    /**
     * 
     */
    $(document).on('click', '#jsAddQuestionBTN', function(event) {
        //
        $("#jsChapterPreviewSection").hide();
        $("#jsAddCourseQueston").show();
        //
        $("#jsChapterQuestionUpdateBTN").hide();
        $("#jsChapterQuestionSaveBTN").show();
        //
        $('#jsQuestionType').select2({
            closeOnSelect: false
        });
        //
        $('#jsQuestionTitle').val('');
        $('#jsQuestionAnswer').val('');
        $('#jsQuestionType').select2('val', "text");
    });

    /**
     * 
     */
    $(document).on('click', '#jsChapterQuestionSaveBTN', function(event) {
        //
        event.preventDefault();
        //
        var question = {
            'action': "save_question",
            'employeeId': eToken,
            'companyId': cToken,
            'courseId': courseID,
            'chapterId': chapterID,
            'question': $('#jsQuestionTitle').val().trim(),
            'answer': $('#jsQuestionAnswer').val().trim().toLowerCase(),
            'sort_order': questionCount,
            'type': $('#jsQuestionType').val()
        };
        //
        if (question.title == '') {
            alertify.alert("WARNING!", "Please add the question title.");
            return;
        }
        //
        if (question.answer == '') {
            alertify.alert("WARNING!", "Please add the question answer.");
            return;
        }
        //
        if (question.type == 'boolean') {
            if (question.answer != 'yes' && question.answer != 'no') {
                alertify.alert("WARNING!", "Please add the correct answer.");
                return;
            }
        } else if (question.type == 'text') {
            if (!intRegex.test(question.answer)) {
                alertify.alert("WARNING!", "Please add the valid number.");
                return;
            }
            
        }
        //
        saveChapterQuestion(question);
    });

    /**
     * 
     */
    $(document).on('click', '#jsResetQuestionSectionBTN', function(event) {
        //
        createChapterPreview();
    });

    /**
     * move up:
     */
    $(document).on('click', '.jsRearrangeUpQuestion', function(event) {
        //
        var sortorder = {};
        var sid = $(this).data("question_sid");
        var div = $("#div_"+sid);
        // 
        div.prev().insertAfter(div);
        //
        $('.jsChapterQuestionSort').each(function(index,item){
            sortorder[++index] = parseInt($(item).data('question_sid'));
        });
        //
        resetSortQuestions(sortorder)
    });

    /**
     * move down:
     */
    $(document).on('click', '.jsRearrangeDownQuestion', function(event) {
        //
        var sortorder = {};
        var sid = $(this).data("question_sid");
        var div = $("#div_"+sid);
        //
        div.next().insertBefore(div);
        //
        $('.jsChapterQuestionSort').each(function(index,item){
            sortorder[++index] = parseInt($(item).data('question_sid'));
        });
        //
        resetSortQuestions(sortorder)
    });

    /**
     * 
     */
    $(document).on('click', '.jsEditQuestion', function(event) {
        //
        updateQuestionID = $(this).data("question_sid");
        //
        var obj = {
            'action': "get_question",
            'employeeId': eToken,
            'companyId': cToken,
            'questionId': updateQuestionID
        };
        //
        $.ajax({
            type: 'POST',
            url: courseURL,
            data: obj,
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
                //
                $('.jsLMSLoader').hide();
                //
                if (resp.Status === false) {
                    alertify.alert(
                        "WARNING!",
                        resp.Response
                    );
                    //
                    return;
                }
                //
                if (resp.Status === true) {
                    //
                    $("#jsChapterPreviewSection").hide();
                    $("#jsAddCourseQueston").show();
                    //
                    $("#jsChapterQuestionUpdateBTN").show();
                    $("#jsChapterQuestionSaveBTN").hide();
                    //
                    $('#jsQuestionType').select2({
                        closeOnSelect: false,
                        allowClear: true,
                    });
                    //
                    $('#jsQuestionTitle').val(resp.QuestionInfo.question);
                    $('#jsQuestionAnswer').val(resp.QuestionInfo.answer);
                    //
                    if (resp.QuestionInfo.type == "text") {
                        $('#jsQuestionType').select2('val', "text");
                    } else if (resp.QuestionInfo.type == "boolean") {
                        $('#jsQuestionType').select2('val', "boolean");
                    }
                }
                //
            },
            error: function(resp){
                alertify.alert("NOTICE!", "Unable to get question");
                $('.jsLMSLoader').hide();
            }
        });
    });

    /**
     * 
     */
    $(document).on('click', '#jsChapterQuestionUpdateBTN', function(event) {
        event.preventDefault();
        //
        var question = {
            'action': "update_question",
            'employeeId': eToken,
            'companyId': cToken,
            'questionId': updateQuestionID,
            'question': $('#jsQuestionTitle').val().trim(),
            'answer': $('#jsQuestionAnswer').val().trim().toLowerCase(),
            'type': $('#jsQuestionType').val()
        };
        //
        if (question.title == '') {
            alertify.alert("WARNING!", "Please add the question title.");
            return;
        }
        //
        if (question.answer == '') {
            alertify.alert("WARNING!", "Please add the question answer.");
            return;
        }
        //
        if (question.type == 'boolean') {
            if (question.answer != 'yes' && question.answer != 'no') {
                alertify.alert("WARNING!", "Please add the correct answer.");
                return;
            }
        } else if (question.type == 'text') {
            if (!intRegex.test(question.answer)) {
                alertify.alert("WARNING!", "Please add the valid number.");
                return;
            }
            
        }
        //
        saveChapterQuestion(question);
    });

    /**
     * 
     */
    $(document).on('click', '.jsDeleteQuestion', function(event) {
        alertify.confirm(
            'Are you sure you want to delete this question?',
            () => {
                var questionSid = $(this).data("question_sid");
                //
                var obj = {
                    'action': "delete_question",
                    'employeeId': eToken,
                    'companyId': cToken,
                    'courseId': courseID,
                    'chapterId': chapterID,
                    'questionId': questionSid
                };
                //
                $.ajax({
                    type: 'POST',
                    url: courseURL,
                    data: obj,
                    beforeSend: function() {
                        $('.jsLMSLoader').show();
                    },
                    success: function(resp) {
                        $('.jsLMSLoader').hide();
                        //
                        if (resp.Status === false) {
                            alertify.alert(
                                "WARNING!",
                                resp.Response
                            );
                            //
                            return;
                        }
                        //
                        if (resp.Status === true) {
                            //
                            alertify.alert(
                                "SUCCESS!",
                                resp.Response,
                                function () {
                                    questionCount = resp.QuestionCount;
                                    createChapterPreview();
                                }
                            );
                        }
                    },
                    error: function(result){
                        alertify.alert("NOTICE!", "Unable to delete this question");
                        $('.jsLMSLoader').hide();
                    }
                });
            }
        ).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    });

    /**
     * 
     */
    $(document).on('click', '.jsUpdateChapterQuestion', function(event) {
        chapterID = $(this).data("chapter_sid");
        createChapterPreview();
    });

    $(document).on('click', '.jsAssignEmployeesToCourseBTN', function(event) {
        $(".step3").addClass("_csactive");
        $("#show_manual_section").hide();
        $("#show_employees_section").show();
        setupEmployeesPreview();
    })

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


