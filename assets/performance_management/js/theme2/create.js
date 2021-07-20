// 
$(function() {

    var obj = {
        Id: 0,
        Title: '',
        Description: '',
        Visibility: {
            roles: [],
            departments: [],
            teams: [],
            employees: []
        },
        Schedule: {
            frequency_type: 'onetime',
            start_date: '',
            end_date: '',
            recur_value: 0,
            recur_type: 'days',
            review_due: 0,
            custom_runs: []
        },
        Reviewees: {
            included: [],
            excluded: []
        },
        Reviewers: {
            reviewer_type: null,
            reviewees: {}
        },
        Questions: {},
        Share_feedback: true
    };

    //
    var questionFile = null;

    window.questionFile = questionFile;

    window.REVIEW = obj;
    //
    $('#jsReviewCustomRunDueType').select2({ minimumResultsForSearch: -1 });
    $('#jsReviewCustomRunType').select2({ minimumResultsForSearch: -1 });
    $('#jsReviewRecurType').select2({ minimumResultsForSearch: -1 });
    //
    $('#jsReviewRolesInp').select2({ closeOnSelect: false, minimumResultsForSearch: -1 });
    $('#jsReviewDepartmentsInp').select2({ closeOnSelect: false });
    $('#jsReviewTeamsInp').select2({ closeOnSelect: false });
    $('#jsReviewEmployeesInp').select2({ closeOnSelect: false });
    //
    $('#jsReviewRevieweeFilterRoles').select2({ minimumResultsForSearch: -1 });
    $('#jsReviewRevieweeFilterDepartments').select2({ closeOnSelect: false });
    $('#jsReviewRevieweeFilterTeams').select2({ closeOnSelect: false });
    $('#jsReviewRevieweeFilterEmployees').select2({ closeOnSelect: false });
    $('#jsReviewRevieweeFilterJob').select2({ closeOnSelect: false });
    $('#jsReviewRevieweeFilterType').select2({ minimumResultsForSearch: -1 });
    $('#jsReviewRevieweeFilterExcludeEmployees').select2({ closeOnSelect: false });
    $('#jsReviewRevieweeFilterExcludeFrame').select2({ minimumResultsForSearch: -1 });
    //
    $('.jsReviewRevieweesRow').map(function() {
        obj.Reviewees.included.push($(this).data().id);
    });
    //
    $('#jsReviewReviewerFilterEmployees').select2({ closeOnSelect: false });

    //
    $('#jsReviewStartDateInp').datepicker({
        changeYear: true,
        changeMonth: true,
        onselect: function(d) {
            $('#jsReviewEndDateInp').datepicker("set", "minDate", d)
        }
    });
    //
    $('#jsReviewEndDateInp').datepicker({
        changeYear: true,
        changeMonth: true,
    });
    //
    $('#jsReviewQuestionAddQuestionType').select2({ minimumResultsForSearch: -1 });

    //
    var cp = new mVideoRecorder({
        recorderPlayer: 'jsVideoRecorder',
        previewPlayer: 'jsVideoPreview',
        recordButton: 'jsVideoRecordButton',
        playRecordedVideoBTN: 'jsVideoPlayVideo',
        removeRecordedVideoBTN: 'jsVideoRemoveButton',
        pauseRecordedVideoBTN: 'jsVideoPauseButton',
        resumeRecordedVideoBTN: 'jsVideoResumeButton',
    });

    //
    checkStep();

    // Events
    //
    $('.jsTemplateQuestionsView').click(function(event) {
        //
        event.preventDefault();
        //
        var data = $(this).closest('.csTemplateWrap').data();
        //
        Modal({
            Id: 'jsTemplateQuestionView',
            Title: data.name,
            Loader: 'jsTemplateQuestionViewLoader',
            Body: '<div id="jsTemplateQuestionViewBody"></div>',
            Cancel: 'Close'
        }, loadTemplateQuestions.bind(this, 'jsTemplateQuestionView', data.type, data.id));
    });

    //
    $('.jsTemplateQuestionsSelect').click(function(event) {
        //
        event.preventDefault();
        //
        ml(true, 'review', 'Please wait we are setting the review.');
        //
        $('.csTemplateWrap').removeClass('active');
        //
        $(this).closest('.csTemplateWrap').addClass('active');
        //
        //
        var data = $(this).closest('.csTemplateWrap').data();
        //
        $.get(pm.urls.pbase + 'get-single-template/' + (data.type) + '/' + (data.id) + '?format=json')
            .done(function(resp) {
                //
                obj.Questions = resp.data.questions;
                //
                if (obj.Questions.length > 0) {
                    obj.Questions.map(function(question, index) {
                        obj.Questions[index]['sort_order'] = index;
                    });
                }
                obj.Title = resp.data.name;
                //
                $('#jsReviewTitleTxt').text(': ' + obj.Title);
                $('#jsReviewTitleInp').val(obj.Title);
                //
                stepMover('schedule');
            });
    });

    //
    $('#jsReviewCreateNewBtn').click(function(event) {
        //
        event.preventDefault();
        //
        obj.Title = '';
        obj.questions = [];
        //
        $('#jsReviewTitleTxt').text('');
        $('#jsReviewTitleInp').val('');
        //
        $('.csTemplateWrap').removeClass('active');
        //
        stepMover('schedule');
    });

    //
    $('.jsPageSectionBtn').click(function(event) {
        //
        event.preventDefault();
        //
        stepMover($(this).data('to'));
    });

    /**
     * 
     */
    $('.jsReviewFrequencyInp').click(function() {
        //
        $('.jsReviewFrequencyRowOne').addClass('dn');
        $('.jsReviewFrequencyRowRecur').addClass('dn');
        $('.jsReviewFrequencyRowCustom').addClass('dn');
        //
        switch ($(this).val()) {
            case "recurring":
                $('.jsReviewFrequencyRowRecur').removeClass('dn');
                obj.Schedule.frequency_type = 'recur';
                break;
            case "custom":
                $('.jsReviewFrequencyRowCustom').removeClass('dn');
                obj.Schedule.frequency_type = 'custom';
                break;
            default:
                $('.jsReviewFrequencyRowOne').removeClass('dn');
                obj.Schedule.frequency_type = 'onetime';
        }
    });

    /**
     * 
     */
    $('.jsReviewAddCustomRun').click(function(event) {
        //
        event.preventDefault();
        //
        var randomId = getRandomId();
        //
        var html = '';
        html += '<!-- Row 2 -->';
        html += '<div class="row jsReviewCustomRunRow" data-id="' + (randomId) + '">';
        html += '    <div class="col-md-2 col-xs-3">';
        html += '        <input type="text" class="form-control jsReviewCustomRunValue" placeholder="5" id="jsReviewCustomRunValue' + (randomId) + '"/>';
        html += '    </div>';
        html += '    <div class="col-md-4 col-xs-4">';
        html += '        <select class="jsReviewCustomRunType" id="jsReviewCustomRunType' + (randomId) + '">';
        html += '            <option value="days">Day(s)</option>';
        html += '            <option value="weeks">Week(s)</option>';
        html += '            <option value="months">Month(s)</option>';
        html += '        </select>';
        html += '    </div>';
        html += '    <div class="col-md-4 col-xs-4">';
        html += '        <p class="csF16">After Employee\'s (Reviewee\'s) Hire Date</p>';
        html += '    </div>';
        html += '    <div class="col-md-1 col-xs-1">';
        html += '        <i class="fa fa-trash-o csF18 csB7 csCP csInfoTxt jsReviewCustomRunDelete"  aria-hidden="true" title="Delete this custom run" placement="top"> </i>';
        html += '    </div>';
        html += '</div>';
        //
        $('#jsReviewCustomRunContainer').append(html);
        //
        $('#jsReviewCustomRunType' + (randomId) + '').select2({ minimumResultsForSearch: -1 });
    });

    /**
     * 
     */
    $(document).on('click', '.jsReviewCustomRunDelete', function(event) {
        //
        event.preventDefault();
        //
        var _this = $(this);
        //
        alertify.confirm("Do you really want to delete this custom run?", function() {
            _this.closest(".jsReviewCustomRunRow").remove();
        });
    });

    /**
     * 
     */
    $('#jsReviewScheduleSaveBtn').click(function(event) {
        //
        event.preventDefault();
        //
        var o = {
            title: $('#jsReviewTitleInp').val().trim(),
            description: $('#jsReviewDescriptionInp').val().trim(),
            roles: $('#jsReviewRolesInp').val() || [],
            departments: $('#jsReviewDepartmentsInp').val() || [],
            teams: $('#jsReviewTeamsInp').val() || [],
            employees: $('#jsReviewEmployeesInp').val() || [],
            frequency_type: $('.jsReviewFrequencyInp:checked').val(),
            start_date: $('#jsReviewStartDateInp').val() || '',
            end_date: $('#jsReviewEndDateInp').val() || '',
            recur_type: $('#jsReviewRecurType').val() || 'days',
            recur_value: $('#jsReviewRecurValue').val() || 0,
            review_due_type: $('#jsReviewCustomRunDueType').val() || 'days',
            review_due_value: $('#jsReviewCustomRunDueValue').val() || 0,
            repeat_review: $('#jsReviewCustomRunEveryYear').val() || false,
            custom_runs: getCustomRuns(),
            questions: obj.Questions
        };
        //
        if (o.title == '') {
            handleError("Review title is missing.");
            return;
        }
        //
        if (o.frequency_type == '') {
            handleError("Please select frequency.");
            return;
        }
        //
        if ((o.frequency_type == 'onetime' || o.frequency_type == 'recurring') && (o.start_date == '' || o.end_date == '')) {
            handleError("Please select review start and end date.");
            return;
        }
        //
        if (o.frequency_type == 'recurring' && (o.recur_value == 0 || o.recur_value == '')) {
            handleError("Recur value is missing.");
            return;
        }
        //
        if (o.frequency_type == 'custom' && Object.keys(o.custom_runs).length === 0) {
            handleError("Please add at least one custom run.");
            return;
        }
        //
        if (o.frequency_type == 'custom' && (o.review_due_value == 0 || o.review_due_value == '')) {
            handleError("Review due value is missing.");
            return;
        }

        //
        ml(true, 'review');
        //
        $.post(pm.urls.pbase + 'save_review_step', {
            step: 'ReviewStep1',
            data: o,
            id: obj.Id
        }, function(resp) {
            //
            obj.Id = resp.Id;
            //
            if (!resp.Status) {
                handleError(resp.Msg);
                return;
            }
            //
            stepMover("reviewees");
        });

    });

    /**
     * 
     */
    $('#jsReviewReviewersSaveBtn').click(function(event) {
        //
        event.preventDefault();
        //
        if (obj.Reviewers.reviewer_type === null) {
            handleError("Please select a reviewer type.");
            return;
        }
        //
        if (Object.keys(obj.Reviewers.reviewees).length === 0) {
            handleError("Please add reviewer against all reviewees.");
            return;
        }
        //
        var isError = false;
        //
        $.each(obj.Reviewers.reviewees, function(index, employee) {
            if (Object.keys(employee.included).length === 0) {
                isError = true;
                handleError("Please add reviewer against all reviewees.");
                return;
            }
        });
        //
        if (isError) {
            return false;
        }
        //
        ml(true, 'review');
        //
        $.post(pm.urls.pbase + 'save_review_step', {
            step: 'ReviewStep3',
            data: obj.Reviewers,
            id: obj.Id
        }, function(resp) {
            //
            if (resp.Status == false) {
                handleError(resp.Msg);
                return;
            }
            //
            loadQuestions(true);
        });

    });


    /**
     * 
     */
    $('#jsReviewRevieweesSaveBtn').click(function(event) {
        //
        event.preventDefault();
        //
        if (obj.Reviewees.included.length === 0) {
            handleError("Please add at least one reviewee..");
            return;
        }
        //
        ml(true, 'review');
        //
        $.post(pm.urls.pbase + 'save_review_step', {
            step: 'ReviewStep2',
            data: {
                included: obj.Reviewees.included || [],
                excluded: obj.Reviewees.excluded || []
            },
            id: obj.Id
        }, function(resp) {
            //
            if (!resp.Status) {
                handleError(resp.Msg);
                return;
            }
            //
            loadReviewers();
            //
            stepMover("reviewers");
        });

    });


    /**
     * 
     */
    $('.jsReviewRevieweeSearchBtn').click(function(event) {
        //
        event.preventDefault();
        //
        loadReviewees();
    });

    /**
     * 
     */
    $('.jsReviewRevieweeResetBtn').click(function(event) {
        //
        event.preventDefault();
        //
        $('#jsReviewRevieweeFilterRoles').select2('val', null);
        //
        $('#jsReviewRevieweeFilterDepartments').select2('val', null);
        //
        $('#jsReviewRevieweeFilterTeams').select2('val', null);
        //
        $('#jsReviewRevieweeFilterEmployees').select2('val', null);
        //
        $('#jsReviewRevieweeFilterJob').select2('val', null);
        //
        $('#jsReviewRevieweeFilterType').select2('val', null);
        //
        $('#jsReviewRevieweeFilterExcludeEmployees').select2('val', null);
        //
        $('#jsReviewRevieweeFilterExcludeFrame').select2('val', 0);
        //
        $('.jsReviewRevieweesRow').show(0);
        //
        obj.Reviewees.included = [];
        obj.Reviewees.excluded = [];
        //
        $('.jsReviewRevieweesRow').map(function() {
            obj.Reviewees.included.push($(this).data('id'));
        });
        //
        $('#jsReviewRevieweesCount').text($('.jsReviewRevieweesRow').length);
    });

    /**
     * 
     */
    $('.jsReviewReviewerCountBtn').click(function(event) {
        // 
        event.preventDefault();
        //
        $('.jsReviewReviewerCountBtn').removeClass('dn');
        $('.jsReviewReviewerCountBtn').parent().find('.jsReviewReviewerSelectBox').addClass('dn');
        //
        $(this).addClass('dn');
        $(this).parent().find('.jsReviewReviewerSelectBox').removeClass('dn');
    });

    /**
     * 
     */
    $('.jsReviewReviewerBackCountBtn').click(function(event) {
        // 
        event.preventDefault();
        //
        $(this).closest('.jsReviewReviewerCountBox').find('.jsReviewReviewerCountBtn').removeClass('dn');
        $(this).closest('.jsReviewReviewerCountBox').find('.jsReviewReviewerSelectBox').addClass('dn');
    });


    /**
     * 
     * @param {*} targetId 
     * @param {*} type 
     * @param {*} id 
     */
    $('.jsReviewReviewerType').change(function() {
        //
        obj.Reviewers.reviewer_type = $(this).val();
        //
        obj.Reviewees.reviewees = {};
        //
        $('.jsReviewReviewerSpecificReviewers').addClass('dn');
        $('#jsReviewReviewerFilterEmployees').select2('val', null);
        //
        switch (obj.Reviewers.reviewer_type) {
            case "reporting_manager":
                //
                obj.Reviewees.included.map(function(employeeId) {
                    //
                    employeeId = employeeId.toString();
                    //
                    var employee = getEmployee(employeeId);
                    //
                    obj.Reviewers.reviewees[employee.Id] = {
                        included: employee.ReportingManagers,
                        excluded: []
                    };
                    //
                    $('.jsReviewReviewersRow[data-id="' + (employee.Id) + '"] .select2').select2('val', null);
                    $('.jsReviewReviewersRow[data-id="' + (employee.Id) + '"] .select2:nth-child(1)').select2('val', obj.Reviewers.reviewees[employee.Id].included);
                    $('.jsReviewReviewersRow[data-id="' + (employee.Id) + '"] .jsReviewReviewerCount').text(obj.Reviewers.reviewees[employee.Id].included.length);
                });
                break;
            case "self_review":
                //
                obj.Reviewees.included.map(function(employeeId) {
                    //
                    obj.Reviewers.reviewees[employeeId] = {
                        included: [employeeId],
                        excluded: []
                    };
                    //
                    $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"] .select2').select2('val', null);
                    $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.select2:nth-child(1)').select2('val', obj.Reviewers.reviewees[employeeId].included);
                    $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.jsReviewReviewerCount').text(obj.Reviewers.reviewees[employeeId].included.length);
                });
                break;
            case "peers":
                //
                obj.Reviewees.included.map(function(employeeId) {
                    //
                    var employee = getEmployee(employeeId);
                    //
                    obj.Reviewers.reviewees[employeeId] = {
                        included: getMyPeers(employeeId, employee.Teams),
                        excluded: []
                    };
                    //
                    $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"] .select2').select2('val', null);
                    $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.select2:nth-child(1)').select2('val', obj.Reviewers.reviewees[employeeId].included);
                    $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.jsReviewReviewerCount').text(obj.Reviewers.reviewees[employeeId].included.length);
                });
                break;
            case "specific_reviewers":
                $('.jsReviewReviewerSpecificReviewers').removeClass('dn');
                break;
        }
    });

    /**
     * 
     * @param {*} targetId 
     * @param {*} type 
     * @param {*} id 
     */
    $('#jsReviewReviewerFilterEmployees').change(function() {
        //
        var reviewers = $(this).val() || [];
        //
        obj.Reviewees.included.map(function(employeeId) {
            //
            if (obj.Reviewers.reviewees[employeeId] === undefined) {
                obj.Reviewers.reviewees[employeeId] = {
                    included: [],
                    excluded: []
                }
            }
            //
            obj.Reviewers.reviewees[employeeId].included = _.uniq(_.concat(reviewers, obj.Reviewers.reviewees[employeeId].included));
            //
            var newInc = _.filter(obj.Reviewers.reviewees[employeeId].included, function(i) {
                if ($.inArray(i, obj.Reviewers.reviewees[employeeId].excluded) !== -1) {
                    return false;
                }
                return true;
            });
            //
            $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"] .select2').select2('val', null);
            $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.select2:nth-child(1)').select2('val', newInc);
            $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.jsReviewReviewerCount').text(newInc.length);
        });
    });

    /**
     * 
     * @param {*} targetId 
     * @param {*} type 
     * @param {*} id 
     */
    $('.jsReviewReviewerSelectBoxIncluded').on('select2:select', function() {
        //
        var reviewers = $(this).val() || [];
        //
        var employeeId = $(this).closest('.jsReviewReviewersRow').data('id');
        //
        //
        if (obj.Reviewers.reviewees[employeeId] === undefined) {
            obj.Reviewers.reviewees[employeeId] = {
                included: [],
                excluded: []
            }
        }
        //
        obj.Reviewers.reviewees[employeeId].included = _.uniq(_.concat(reviewers, obj.Reviewers.reviewees[employeeId].included));
        //
        var newInc = _.filter(obj.Reviewers.reviewees[employeeId].included, function(i) {
            if ($.inArray(i, obj.Reviewers.reviewees[employeeId].excluded) !== -1) {
                return false;
            }
            return true;
        });
        //
        $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"] .select2').select2('val', null);
        $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.select2:nth-child(1)').select2('val', newInc);
        $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.jsReviewReviewerCount').text(newInc.length);
    });

    /**
     * 
     * @param {*} targetId 
     * @param {*} type 
     * @param {*} id 
     */
    $('.jsReviewReviewerSelectBoxExcluded').on('select2:select', function() {
        //
        var reviewers = $(this).val() || [];
        //
        var employeeId = $(this).closest('.jsReviewReviewersRow').data('id');
        //
        //
        if (obj.Reviewers.reviewees[employeeId] === undefined) {
            obj.Reviewers.reviewees[employeeId] = {
                included: [],
                excluded: []
            }
        }
        //
        obj.Reviewers.reviewees[employeeId]['excluded'] = _.uniq(_.concat(reviewers, obj.Reviewers.reviewees[employeeId].excluded));
        //
        var newInc = _.filter(obj.Reviewers.reviewees[employeeId].included, function(i) {
            if ($.inArray(i, obj.Reviewers.reviewees[employeeId].excluded) !== -1) {
                return false;
            }
            return true;
        });
        //
        $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"] .select2').select2('val', null);
        $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.select2:nth-child(1)').select2('val', newInc);
        $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.jsReviewReviewerCount').text(newInc.length);
    });

    /**
     * 
     */
    $('#jsReviewQuestionAddBtn').click(function(event) {
        //
        event.preventDefault();
        //
        $('#jsReviewQuestionSaveBtn').removeClass('dn');
        $('#jsReviewQuestionEditBtn').addClass('dn');
        //
        $('#jsReviewQuestionListBox').addClass('dn');
        $('#jsReviewQuestionAddBox').removeClass('dn');
        //
        $('#jsReviewQuestionAddPreviewTextBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewMultipleChoiceBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewRatingBox').addClass('dn');
        //
        $('#jsReviewQuestionAddPreviewVideo').addClass('dn');
        $('#jsVideoPreviewBox').next('video').remove();
        //
        $('#jsReviewQuestionAddPreviewTitle').text('');
        $('#jsReviewQuestionAddPreviewDescription').text('');
        //
        $('#jsReviewQuestionAddTitle, #jsReviewQuestionAddDescription').val('');
        $('#jsReviewQuestionAddQuestionType').select2('val', 'text');
        //
        $('.jsReviewQuestionAddVideoType[value="none"]').prop('checked', 'true').trigger('click');
        //
        questionFile = null;
        //
        $('#jsReviewQuestionAddVideoUploadInp').mFileUploader({
            allowedTypes: ['mp4', 'webm'],
            fileLimit: '2mb',
            onSuccess: function(o) {
                questionFile = o;
                updatePreview();
            },
            onClear: function(e) {
                questionFile = null;
                updatePreview();
            },
        });
        //
        cp.close();
    });

    /**
     * 
     */
    $(document).on('click', '.csReviewQuestionEdit', function(event) {
        //
        event.preventDefault();
        //
        var question = obj.Questions[$(this).closest('.jsReviewQuestionRow').data('index')];
        //
        $('#jsReviewQuestionSaveBtn').addClass('dn');
        $('#jsReviewQuestionEditBtn').removeClass('dn');
        //
        $('#jsReviewQuestionListBox').addClass('dn');
        $('#jsReviewQuestionAddBox').removeClass('dn');
        //
        $('#jsReviewQuestionAddPreviewTextBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewMultipleChoiceBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewRatingBox').addClass('dn');
        //
        $('#jsReviewQuestionAddPreviewVideo').addClass('dn');
        $('#jsVideoPreviewBox').next('video').remove();
        //
        $('#jsReviewQuestionAddPreviewTitle').text('');
        $('#jsReviewQuestionAddPreviewDescription').text('');
        //
        $('#jsReviewQuestionAddTitle, #jsReviewQuestionAddDescription').val('');
        $('#jsReviewQuestionAddQuestionType').select2('val', 'text');
        //
        $('.jsReviewQuestionAddVideoType[value="none"]').prop('checked', 'true').trigger('click');
        //
        questionFile = null;
        //
        $('#jsReviewQuestionAddVideoUploadInp').mFileUploader('clear');
        //
        cp.close();
        //
        $('#jsReviewQuestionAddTitle').val(question.title);
        $('#jsReviewQuestionAddDescription').val(question.description);
        $('.jsReviewQuestionAddVideoType[value="' + (question.video_help) + '"]').prop('checked', 'true').trigger('click');
        $('#jsReviewQuestionAddQuestionType').select2('val', question.question_type);
        //
        if (question.video_help == 'upload') {
            questionFile = question.video;
            $('#jsReviewQuestionAddVideoUploadInp').mFileUploader({
                allowedTypes: ['mp4', 'webm'],
                fileLimit: '2mb',
                onSuccess: function(o) {
                    questionFile = o;
                    updatePreview();
                },
                onClear: function(e) {
                    questionFile = null;
                    updatePreview();
                },
                path: false,
                placeholderImage: pm.urls.base + 'assets/performance_management/videos/' + obj.id + '/' + questionFile
            });
        }
        $('#jsReviewQuestionEditBtn').data('index', $(this).closest('.jsReviewQuestionRow').data('index'));
    });

    /**
     * 
     */
    $('#jsReviewQuestionToList').click(function(event) {
        //
        event.preventDefault();
        //
        $('#jsReviewQuestionListBox').removeClass('dn');
        $('#jsReviewQuestionAddBox').addClass('dn');
    });

    /**
     * 
     */
    $('.jsReviewQuestionAddVideoType').click(function() {
        //
        $('#jsReviewQuestionAddVideoRecord').addClass('dn');
        $('#jsReviewQuestionAddVideoUpload').addClass('dn');
        //
        $('.jsVideoPreviewBox').addClass('dn');
        //
        cp.close();
        //
        switch ($(this).val()) {
            case "record":
                $('#jsReviewQuestionAddVideoRecord').removeClass('dn');
                cp.init();
                break;
            case "upload":
                $('#jsReviewQuestionAddVideoUpload').removeClass('dn');
                break;
        }
        //
        updatePreview();
    });

    /**
     * 
     */
    $('#jsReviewQuestionAddTitle, #jsReviewQuestionAddDescription').keyup(function() {
        updatePreview();
    });

    /**
     * 
     */
    $('#jsReviewQuestionAddQuestionType').change(function() {
        updatePreview();
    });

    /**
     * 
     */
    $('#jsReviewQuestionSaveBtn').click(function(event) {
        //
        event.preventDefault();
        //
        var question = {
            id: getRandomId(),
            title: $('#jsReviewQuestionAddTitle').val().trim(),
            description: $('#jsReviewQuestionAddDescription').val().trim(),
            video_help: $('.jsReviewQuestionAddVideoType:checked').val(),
            video: "",
            sort_order: "1",
            not_applicable: "0",
            question_type: $('#jsReviewQuestionAddQuestionType').val()
        };
        //
        if (question.title == '') {
            handleError("Please add the question title");
            return;
        }

        // 
        if (question.video_help == 'record') { // Upload Recorded Video
            //
            uploadRecordedVideo(question);
        } else if (question.video_help == 'upload') { // Upload video
            //
            if (Object.keys(questionFile).length === 0 || questionFile.error) {
                handleError("Please upload a video.");
                return;
            }
            //
            ml(true, 'review', 'Please wait, while we are uploading the video.');
            //
            uploadVideo(question, questionFile);
        } else {
            saveQuestion(question);
        }
        //

    });

    /**
     * 
     */
    $('#jsReviewQuestionEditBtn').click(function(event) {
        //
        event.preventDefault();
        //
        var question = obj.Questions[$(this).data('index')];
        //
        question.title = $('#jsReviewQuestionAddTitle').val().trim();
        question.description = $('#jsReviewQuestionAddDescription').val().trim();
        question.video_help = $('.jsReviewQuestionAddVideoType:checked').val();
        question.question_type = $('#jsReviewQuestionAddQuestionType').val();
        //
        if (question.title == '') {
            handleError("Please add the question title");
            return;
        }

        // 
        if (question.video_help == 'record' && questionFile != null) { // Upload Recorded Video
            //
            uploadRecordedVideo(question);
            return;
        } else if (question.video_help == 'upload' && questionFile != null) { // Upload video
            //
            if (Object.keys(questionFile).length === 0 || questionFile.error) {
                handleError("Please upload a video.");
                return;
            }
            //
            ml(true, 'review', 'Please wait, while we are uploading the video.');
            //
            uploadVideo(question, questionFile);
            return;
        } else if (question.video_help == 'record' || question.video_help == 'upload') {
            question.video = questionFile;
        }
        //
        updateQuestion(question);
    });

    /**
     * 
     */
    $(document).on('click', '.jsReviewRemoveQuestion', function(event) {
        //
        event.preventDefault();
        //
        var id = $(this).closest('.jsReviewQuestionRow').data('id');
        //
        alertify.confirm(
            "Are you sure you want to remove this question?",
            function() {
                //
                ml(true, 'review', 'Please wait, while we are removing the question.');
                //
                removeQuestion(id);
            }
        );
    });

    /**
     * 
     */
    $(document).on('click', '.jsReviewQuestionUp', function(event) {
        //
        event.preventDefault();
        //
        shiftQuestion($(this).closest('.jsReviewQuestionRow').data('id'), 'up');
    });

    /**
     * 
     */
    $(document).on('click', '.jsReviewQuestionDown', function(event) {
        //
        event.preventDefault();
        //
        shiftQuestion($(this).closest('.jsReviewQuestionRow').data('id'), 'down');
    });

    /**
     * 
     */
    $('#jsReviewQuestionsSaveBtn').click(function(event) {
        //
        event.preventDefault();
        //
        if (obj.Questions.length === 0) {
            handleError("Please add at least one question.");
            return;
        }
        //
        saveQuestions();
    });

    /**
     * 
     */
    $('#jsReviewSaveBtn').click(function(event) {
        //
        event.preventDefault();
        //
        if ($('.jsReviewShareFeedback:checked').val() === undefined) {
            handleError("Please slect how the reporting managers will share their report.");
            return;
        }
        //
        saveReview();
    });

    //
    function uploadRecordedVideo(question) {

        cp.getVideo()
            .then(
                function(video) {
                    //
                    if (video == 'data:') {
                        handleError("Please record a video.");
                        return;
                    }
                    //
                    ml(true, 'review', 'Please wait, while we are uploading the video.');
                    //
                    var fd = new FormData();
                    fd.append('file', video);
                    fd.append('reviewId', obj.Id);
                    fd.append('type', 'record');
                    fd.append('step', 'SaveVideo');
                    //
                    $.ajax({
                        url: pm.urls.pbase + 'save_review_step',
                        type: 'post',
                        data: fd,
                        contentType: false,
                        processData: false,
                    }).done(function(resp) {
                        //
                        if (resp.Status === false) {
                            ml(false, 'review');
                            handleError('Failed to save video.');
                            return false;
                        }
                        //
                        cp.close();
                        //
                        question.video = resp.Id;
                        //
                        saveQuestion(question);
                    });
                },
                function(error) {
                    handleError("Please record the video first.");
                }
            );
    }

    //
    function uploadVideo(question, video) {
        //
        var fd = new FormData();
        fd.append('file', video);
        fd.append('reviewId', obj.Id);
        fd.append('type', 'upload');
        fd.append('step', 'SaveVideo');
        //
        $.ajax({
            url: pm.urls.pbase + 'save_review_step',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
        }).done(function(resp) {
            //
            if (resp.Status === false) {
                handleError('Failed to save video.');
                return false;
            }
            //
            question.video = resp.Id;
            //
            saveQuestion(question);
        });
    }

    //
    function saveQuestion(question) {
        //
        ml(true, 'review', 'Please wait, while we are saving the question.');
        //
        $.post(pm.urls.pbase + 'save_review_step', {
            step: 'SaveQuestion',
            data: question,
            id: obj.Id
        }).done(function(resp) {
            ml(false, 'review');
            //
            if (resp.Status === false) {
                handleError('Failed to save question.');
                return false;
            }
            //
            handleSuccess('You have successfully added a question.', function() {
                //
                cp.close();
                //
                obj.Questions[obj.Questions.length] = question;
                //
                loadQuestions();
                //
                $('#jsReviewQuestionListBox').removeClass('dn');
                $('#jsReviewQuestionAddBox').addClass('dn');
            });
        });
    }

    //
    function updateQuestion(question) {
        //
        ml(true, 'review', 'Please wait, while we are updating the question.');
        //
        $.post(pm.urls.pbase + 'save_review_step', {
            step: 'UpdateQuestion',
            data: question,
            id: obj.Id
        }).done(function(resp) {
            ml(false, 'review');
            //
            if (resp.Status === false) {
                handleError('Failed to update question.');
                return false;
            }
            //
            handleSuccess('You have successfully updated a question.', function() {
                //
                cp.close();
                //
                obj.Questions[question.sort_order] = question;
                //
                loadQuestions();
                //
                $('#jsReviewQuestionListBox').removeClass('dn');
                $('#jsReviewQuestionAddBox').addClass('dn');
            });
        });
    }

    //
    function removeQuestion(questionId) {
        //
        $.post(pm.urls.pbase + 'save_review_step', {
            step: 'RemoveQuestion',
            question_id: questionId,
            id: obj.Id
        }).done(function(resp) {
            //
            ml(false, 'review');
            //
            if (resp.Status === false) {
                handleError('Failed to remove question.');
                return false;
            }
            //
            handleSuccess('You have successfully removed a question.', function() {
                //
                delete obj.Questions[resp.Index];
                //
                loadQuestions();
            });
        });
    }

    //
    function shiftQuestion(questionId, direction) {
        //
        ml(true, 'review');
        //
        var currentIndex, currentQuestion, tmp;
        //
        obj.Questions.map(function(question, index) {
            if (question.id == questionId) {
                currentIndex = index;
                currentQuestion = question;
            }
        });
        //
        if (direction == 'down') {
            //
            tmp = obj.Questions[currentIndex + 1]; // Get the next index
            tmp['sort_order']--; // Decrease it by 1
            currentQuestion['sort_order']++; // Increase it by 1
            obj.Questions[currentIndex + 1] = currentQuestion; // Move the question to the next index
            obj.Questions[currentIndex] = tmp; // Move the next index question to current index
        } else if (direction == 'up') {
            //
            tmp = obj.Questions[currentIndex - 1]; // Get the next index
            tmp['sort_order']++; // Decrease it by 1
            currentQuestion['sort_order']--; // Increase it by 1
            obj.Questions[currentIndex - 1] = currentQuestion; // Move the question to the next index
            obj.Questions[currentIndex] = tmp; // Move the next index question to current index
        }
        //
        $.post(pm.urls.pbase + 'save_review_step', {
            step: 'ReviewStep4',
            questions: obj.Questions,
            id: obj.Id
        }).done(function(resp) {
            //
            ml(false, 'review');
            //
            loadQuestions();
        });
    }

    //
    function saveQuestions() {
        //
        ml(true, 'review');
        //
        $.post(pm.urls.pbase + 'save_review_step', {
            step: 'ReviewStep4',
            questions: obj.Questions,
            id: obj.Id
        }).done(function(resp) {
            //
            ml(false, 'review');
            //
            stepMover('feedback');
        });
    }

    //
    function saveReview() {
        //
        ml(true, 'review', "Please wait, while we are saving the review. This might take a few minutes.");
        //
        $.post(pm.urls.pbase + 'save_review_step', {
            step: 'ReviewStep5',
            feedback: $('.jsReviewShareFeedback:checked').val(),
            id: obj.Id
        }).done(function() {
            window.location.reload();
        });
    }


    function updatePreview() {
        //
        var question = {
            title: $('#jsReviewQuestionAddTitle').val().trim(),
            description: $('#jsReviewQuestionAddDescription').val().trim(),
            video_help: $('.jsReviewQuestionAddVideoType:checked').val(),
            type: $('#jsReviewQuestionAddQuestionType').val(),
            file: questionFile
        };
        //
        $('#jsReviewQuestionAddPreviewTextBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewMultipleChoiceBox').addClass('dn');
        $('#jsReviewQuestionAddPreviewRatingBox').addClass('dn');
        //
        $('#jsReviewQuestionAddPreviewVideo').addClass('dn');
        //
        $('#jsReviewQuestionAddPreviewTitle').text(question.title);
        $('#jsReviewQuestionAddPreviewDescription').text(question.description);
        //
        if (question.file != null) {
            $('#jsReviewQuestionAddPreviewVideo').removeClass('dn');
            //
            var
                videoURL,
                videoType;
            //
            if (typeof(question.file) === 'object') {
                videoURL = URL.createObjectURL(question.file);
                videoType = question.type;
            } else {
                videoURL = pm.urls.base + 'assets/performance_management/videos/' + (obj.Id) + '/' + question.file;
                videoType = getVideoType(question.file);
            }
            //
            var video = '';
            video += '<video controls style="width: 100%">';
            video += '  <source src="' + (videoURL) + '" type="' + (videoType) + '"></source>';
            video += '</video>';
            $('#jsReviewQuestionAddPreviewVideo').append(video);
        }

        //
        if (question.type.match(/multiple/ig) !== null) {
            $('#jsReviewQuestionAddPreviewMultipleChoiceBox').removeClass('dn');
        }
        //
        if (question.type.match(/rating/ig) !== null) {
            $('#jsReviewQuestionAddPreviewRatingBox').removeClass('dn');
        }
        //
        if (question.type.match(/text/ig) !== null) {
            $('#jsReviewQuestionAddPreviewTextBox').removeClass('dn');
        }
    }

    // Functions
    //
    function loadTemplateQuestions(targetId, type, id) {
        //
        $.get(pm.urls.pbase + 'get-template-questions/' + type + '/' + id).done(function(resp) {
            $('#' + targetId + 'Body').html(resp);
            ml(false, 'jsTemplateQuestionViewLoader');
        });
    }

    //
    function stepMover(to) {
        $('.jsPageSection').fadeOut(0);
        $('.jsPageSection[data-page="' + (to) + '"]').show(0);
        ml(false, 'review');
    }

    //
    function getCustomRuns() {
        //
        var runs = {};
        //
        $('.jsReviewCustomRunRow').map(function() {
            //
            if ($(this).find('.jsReviewCustomRunValue').val().trim() != '' && $(this).find('.jsReviewCustomRunValue').val().trim() != 0) {
                runs[$(this).data('id')] = {
                    value: $(this).find('.jsReviewCustomRunValue').val().trim(),
                    type: $(this).find('.jsReviewCustomRunType:selected').val() || 'days'
                };
            }
        });
        //
        return runs;
    }

    /**
     * 
     */
    function loadReviewees() {
        //
        var filter = {};
        //
        filter.roles = $('#jsReviewRevieweeFilterRoles').val() || [];
        //
        filter.departments = $('#jsReviewRevieweeFilterDepartments').val() || [];
        //
        filter.teams = $('#jsReviewRevieweeFilterTeams').val() || [];
        //
        filter.employees = $('#jsReviewRevieweeFilterEmployees').val() || [];
        //
        filter.jobs = $('#jsReviewRevieweeFilterJob').val() || [];
        //
        filter.type = $('#jsReviewRevieweeFilterType').val() || [];
        //
        filter.excluded_employees = $('#jsReviewRevieweeFilterExcludeEmployees').val() || [];
        //
        filter.frame = $('#jsReviewRevieweeFilterExcludeFrame').val() || [];
        //
        if (
            filter.roles.length == 0 &&
            filter.departments.length == 0 &&
            filter.teams.length == 0 &&
            filter.employees.length == 0 &&
            filter.jobs.length == 0 &&
            filter.type.length == 0 &&
            filter.excluded_employees.length == 0 &&
            filter.frame == 0
        ) {
            //
            obj.Reviewees.included = [];
            obj.Reviewees.excluded = [];
            //
            $('.jsReviewRevieweesRow').map(function() {
                obj.Reviewees.included.push($(this).data().id);
            });
            $('#jsReviewRevieweesCount').text(obj.Reviewees.included.length);
            $('.jsReviewRevieweesRow').show(0);
            return;
        }

        var tmpIncluded = [];

        // For exluded
        $('.jsReviewRevieweesRow').map(function() {
            //
            var data = $(this).data();
            data.id = data.id.toString();
            //
            // Check for exclude
            if (filter.excluded_employees.length > 0 && $.inArray(data.id, filter.excluded_employees) !== -1) {
                //
                $(this).hide(0);
                obj.Reviewees.excluded.push(data.id);
                return;
            }
            // Join Date
            if (filter.frame != 0 && moment(data.join, "YYYY-MM-DD").add(filter.frame, 'days').format('YYYY-MM-DD') <= moment().format("YYYY-MM-DD")) {
                $(this).hide(0);
                obj.Reviewees.excluded.push(data.id);
                return;
            }
            //
            tmpIncluded.push(data.id);
        });
        //
        if (
            filter.roles.length == 0 &&
            filter.departments.length == 0 &&
            filter.teams.length == 0 &&
            filter.employees.length == 0 &&
            filter.jobs.length == 0 &&
            filter.type.length == 0
        ) {
            //
            obj.Reviewees.included = tmpIncluded;
            //
            $('#jsReviewRevieweesCount').text(obj.Reviewees.included.length);
            return;
        }
        //
        $('.jsReviewRevieweesRow').hide(0);
        // For Included
        $('.jsReviewRevieweesRow').map(function() {
            //
            var data = $(this).data();
            //
            if ($.inArray(data.id, obj.Reviewees.excluded) !== -1) {
                return;
            }
            data.departments = data.departments.toString();
            data.teams = data.teams.toString();
            data.id = data.id.toString();
            // Role check 
            if (filter.roles.length > 0 && $.inArray(data['role'], filter.roles) !== -1) {
                obj.Reviewees.included.push(data.id);
                $(this).show();
                return;
            }
            // Departments check 
            if (filter.departments.length > 0 && _.intersection(data['departments'].split(','), filter.departments).length > 0) {
                obj.Reviewees.included.push(data.id);
                $(this).show();
                return;
            }
            // Teams check 
            if (filter.teams.length > 0 && _.intersection(data['teams'].split(','), filter.teams).length > 0) {
                obj.Reviewees.included.push(data.id);
                $(this).show();
                return;
            }
            // Employee check 
            if (filter.employees.length > 0 && $.inArray(data['id'], filter.employees) !== -1) {
                obj.Reviewees.included.push(data.id);
                $(this).show();
                return;
            }
            // Job title check 
            if (filter.jobs.length > 0 && $.inArray(data['job'], filter.jobs) !== -1) {
                obj.Reviewees.included.push(data.id);
                $(this).show();
                return;
            }
            // Employment type check 
            if (filter.type.length > 0 && $.inArray(data['type'], filter.type) !== -1) {
                obj.Reviewees.included.push(data.id);
                $(this).show();
                return;
            }
            //
            obj.Reviewees.excluded.push(data.id);
        });

        //
        $('#jsReviewRevieweesCount').text(obj.Reviewees.included.length);

    }

    /**
     * 
     * @returns 
     */
    function loadReviewers() {
        //
        $('.jsReviewReviewersRow').hide(0);
        $('.jsReviewReviewerCount').text(0);
        $('.jsReviewReviewersRow .select2').select2('val', null);
        $('.jsReviewReviewerType').prop('checked', false);
        //
        obj.Reviewers.reviewer_type = null;
        //
        obj.Reviewees.included.map(function(id) {
            $('.jsReviewReviewersRow[data-id="' + (id) + '"]').show(0);
        });
    }

    //
    function checkStep() {
        //
        if (pm.review.length === 0) {
            stepMover('template');
            return;
        }
        //
        var step = 'schedule';

        // STEP 1
        // 
        if (pm.review.reviewId) {
            obj.Id = pm.review.reviewId;
        }
        //
        if (pm.review.title) {
            obj.Title = pm.review.title;
            $('#jsReviewTitleInp').val(obj.Title);
        }
        //
        if (pm.review.description) {
            obj.Description = pm.review.description;
            $('#jsReviewDescriptionInp').val(obj.Description);
        }
        //
        if (pm.review.frequency_type) {
            //
            $('.jsReviewFrequencyRowOne').addClass('dn');
            $('.jsReviewFrequencyRowRecur').addClass('dn');
            $('.jsReviewFrequencyRowCustom').addClass('dn');
            //
            obj.Schedule.frequency_type = pm.review.frequency_type;
            $('.jsReviewFrequencyInp[value="' + (obj.Schedule.frequency_type) + '"]').click();
            //
            if (obj.Schedule.frequency_type === 'recurring') {
                $('.jsReviewFrequencyRowRecur').removeClass('dn');
            } else if (obj.Schedule.frequency_type === 'custom') {
                $('.jsReviewFrequencyRowCustom').removeClass('dn');
            } else {
                $('.jsReviewFrequencyRowOne').removeClass('dn');
            }
        }
        //
        if (pm.review.start_date) {
            obj.Schedule.start_date = moment(pm.review.start_date, "YYYY-MM-DD").format("MM/DD/YYYY");
            $('#jsReviewStartDateInp').val(obj.Schedule.start_date);
        }
        //
        if (pm.review.end_date) {
            obj.Schedule.end_date = moment(pm.review.end_date, "YYYY-MM-DD").format("MM/DD/YYYY");
            $('#jsReviewEndDateInp').val(obj.Schedule.end_date);
        }
        //
        if (pm.review.recur_type) {
            obj.Schedule.recur_type = pm.review.recur_type;
            $('#jsReviewRecurType[value="' + (obj.Schedule.recur_type) + '"]').prop('checked', true);
        }
        //
        if (pm.review.recur_value) {
            obj.Schedule.recur_value = pm.review.recur_value;
            $('#jsReviewRecurValue').val(obj.Schedule.recur_value);
        }
        //
        if (pm.review.review_due_type) {
            obj.Schedule.review_due_type = pm.review.review_due_type;
            $('#jsReviewCustomRunDueType[value="' + (obj.Schedule.review_due_type) + '"]').prop('checked', true);
        }
        //
        if (pm.review.review_due_value) {
            obj.Schedule.review_due_value = pm.review.review_due_value;
            $('#jsReviewCustomRunDueValue').val(obj.Schedule.review_due_value);
        }
        //
        if (pm.review.repeat_review) {
            obj.Schedule.repeat_review = pm.review.repeat_review;
            $('#jsReviewCustomRunEveryYear[value="' + (obj.Schedule.repeat_review) + '"]').prop('checked', true);
        }
        //
        if (pm.review.custom_runs) {
            obj.Schedule.custom_runs = JSON.parse(pm.review.custom_runs);
            $.each(obj.Schedule.custom_runs, function(randomId, v) {
                //
                var html = '';
                html += '<!-- Row 2 -->';
                html += '<div class="row jsReviewCustomRunRow" data-id="' + (randomId) + '">';
                html += '    <div class="col-md-2 col-xs-3">';
                html += '        <input type="text" class="form-control jsReviewCustomRunValue" placeholder="5" id="jsReviewCustomRunValue' + (randomId) + '" value="' + (v.value) + '"/>';
                html += '    </div>';
                html += '    <div class="col-md-4 col-xs-4">';
                html += '        <select class="jsReviewCustomRunType" id="jsReviewCustomRunType' + (randomId) + '">';
                html += '            <option value="days">Day(s)</option>';
                html += '            <option value="weeks">Week(s)</option>';
                html += '            <option value="months">Month(s)</option>';
                html += '        </select>';
                html += '    </div>';
                html += '    <div class="col-md-4 col-xs-4">';
                html += '        <p class="csF16">After Employee\'s (Reviewee\'s) Hire Date</p>';
                html += '    </div>';
                html += '    <div class="col-md-1 col-xs-1">';
                html += '        <i class="fa fa-trash-o csF18 csB7 csCP csInfoTxt jsReviewCustomRunDelete"  aria-hidden="true" title="Delete this custom run" placement="top"> </i>';
                html += '    </div>';
                html += '</div>';
                //
                $('#jsReviewCustomRunContainer').append(html);
                //
                $('#jsReviewCustomRunType' + (randomId) + '').select2({ minimumResultsForSearch: -1 });
                $('#jsReviewCustomRunType' + (randomId) + '').select2('val', v.type);
            });
        }
        //
        if (pm.review.roles) {
            obj.Visibility.roles = pm.review.roles.split(',');
            $('#jsReviewRolesInp').select2('val', obj.Visibility.roles);
        }
        //
        if (pm.review.departments) {
            obj.Visibility.departments = pm.review.departments.split(',');
            $('#jsReviewDepartmentsInp').select2('val', obj.Visibility.departments);
        }
        //
        if (pm.review.teams) {
            obj.Visibility.teams = pm.review.teams.split(',');
            $('#jsReviewTeamsInp').select2('val', obj.Visibility.teams);
        }
        //
        if (pm.review.employees) {
            obj.Visibility.employees = pm.review.employees.split(',');
            $('#jsReviewEmployeesInp').select2('val', obj.Visibility.employees);
        }
        //
        if (pm.review.questions) {
            //
            obj.Questions = JSON.parse(pm.review.questions);
        }


        // Reviewees
        //
        if (pm.review.excluded) {
            obj.Reviewees.excluded = pm.review.excluded.split(',');
            $('#jsReviewRevieweeFilterExcludeEmployees').select2('val', obj.Reviewees.excluded);
        }

        //
        if (pm.review.included) {
            obj.Reviewees.included = pm.review.included.split(',');
            loadReviewees();
            //
            loadReviewers();
            //
            step = "reviewers";
        }

        // Reviewers
        //
        if (pm.review.reviewers) {
            //
            var tmp = JSON.parse(pm.review.reviewers);
            //
            obj.Reviewers = tmp;
            //
            $('.jsReviewReviewerType[value="' + (obj.Reviewers.reviewer_type) + '"]').prop('checked', true);
            //
            $('.jsReviewReviewersRow').hide(0);
            //
            $.each(obj.Reviewers.reviewees, function(employeeId) {
                //
                var newInc = _.filter(obj.Reviewers.reviewees[employeeId].included, function(i) {
                    if ($.inArray(i, obj.Reviewers.reviewees[employeeId].excluded) !== -1) {
                        return false;
                    }
                    return true;
                });
                //
                $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').show(0);
                $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"] .select2').select2('val', null);
                $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.select2:nth-child(1)').select2('val', newInc);
                $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.jsReviewReviewerCount').text(newInc.length);
                //
                if (obj.Reviewers.reviewees[employeeId].excluded) {
                    $('.jsReviewReviewersRow[data-id="' + (employeeId) + '"]').find('.jsReviewReviewerSelectBoxExcluded').select2('val', obj.Reviewers.reviewees[employeeId].excluded);
                }
            });
            //
            step = 'questions';
        }

        //
        if (step == 'questions') {
            //
            if (obj.Questions.length > 0) {
                step = 'feedback';
            }
            loadQuestions();
        }

        //
        stepMover(step);

    }

    //
    function getEmployee(employeeId) {
        //
        var i = 0,
            il = pm.employees.length;
        //
        for (i; i < il; i++) {
            if (employeeId == pm.employees[i]['Id']) {
                return pm.employees[i];
            }
        }
    }

    //
    function getMyPeers(employeeId, teamIds) {
        //
        var i = 0,
            il = pm.employees.length,
            eIds = [];
        //
        for (i; i < il; i++) {
            if (employeeId != pm.employees[i]['Id'] && _.intersection(teamIds, pm.employees[i]['Teams']).length > 0) {
                eIds.push(pm.employees[i]['Id']);
            }
        }
        //
        return eIds;
    }

    //
    function loadQuestions(shift) {
        //
        if (!obj.Questions || obj.Questions.length === 0) {
            return;
        }
        //
        var html = '',
            il = obj.Questions.length;
        //
        obj.Questions.map(function(question, index) {
            //
            obj.Questions[index]['sort_order'] = index;
            //
            html += '<!-- Question Row -->';
            html += '<div class="' + (index % 2 === 0 ? 'csGB' : '') + ' jsReviewQuestionRow" data-id="' + (question.id) + '" data-index="' + (index) + '">';
            html += '    <div class="row">';
            html += '        <div class="col-xs-12">';
            html += '            <div class="p10">';
            html += '                <h5 class="csF14 csB7">';
            html += '                    Q' + (++index) + ': ' + question.title;
            html += '                    <span class="pull-right">';
            html += '                        <i class="fa fa-edit csF18 csB7 csCP csReviewQuestionEdit" title="Edit the question" placemment="top" aria-hidden="true"></i>&nbsp;&nbsp;';
            if (index !== 0) {
                html += '                        <i class="fa fa-arrow-circle-up csF18 csB7 csCP jsReviewQuestionUp" title="Move question one level up" placemment="top" aria-hidden="true"></i>';
            }
            if (index !== il) {
                html += '                        <i class="fa fa-arrow-circle-down csF18 csB7 csCP jsReviewQuestionDown" title="Move question one level down" placemment="top" aria-hidden="true"></i>';
            }
            html += '                        <i class="fa fa-times-circle csF18 csB7 csCP csInfo jsReviewRemoveQuestion" title="Remove this question from the list" placemment="top" aria-hidden="true"></i>';
            html += '                    </span>';
            html += '                </h5>';
            html += '                <!-- Description -->';
            html += '                <div class="row">';
            html += '                    <div class="col-md-8 col-xs-12">';
            html += '                        <p class="csF14">' + (question.description) + '</p>';
            html += '                    </div>';
            html += '                    <div class="col-md-4 col-xs-12">';
            if (question.video_help) {
                html += '                        <video controls style="width: 100%;">';
                html += '                           <source src="' + (pm.urls.base + 'assets/performance_management/videos/' + (obj.Id) + '/' + question.video) + '"  type="' + (getVideoType(question.video)) + '"></source>';
                html += '                        </video>';
            }
            html += '                    </div>';
            html += '                </div>';
            //
            if (question.question_type.match(/multiple/ig) !== null) {
                html += '                <!-- Multiple Choice -->';
                html += '                <div class="row">';
                html += '                    <br />';
                html += '                    <div class="col-xs-12">';
                html += '                        <label class="control control--radio csF14">';
                html += '                            <input type="radio" name="1" /> Yes';
                html += '                            <span class="control__indicator"></span>';
                html += '                        </label> <br />';
                html += '                        <label class="control control--radio csF14">';
                html += '                            <input type="radio" name="1" /> No';
                html += '                            <span class="control__indicator"></span>';
                html += '                        </label>';
                html += '                    </div>';
                html += '                </div>';
            }

            //
            if (question.question_type.match(/rating/ig) !== null) {
                html += '                <!-- Rating -->';
                html += '                <div class="row">';
                html += '                    <br />';
                html += '                    <ul class="csRatingBar pl10 pr10">';
                html += '                        <li>';
                html += '                            <p class="csF20 csB9">1</p>';
                html += '                            <p class="csF14 csB6">Strongly Agree</p>';
                html += '                        </li>';
                html += '                        <li>';
                html += '                            <p class="csF20 csB9">2</p>';
                html += '                            <p class="csF14 csB6">Agree</p>';
                html += '                        </li>';
                html += '                        <li>';
                html += '                            <p class="csF20 csB9">3</p>';
                html += '                            <p class="csF14 csB6">Neutral</p>';
                html += '                        </li>';
                html += '                        <li>';
                html += '                            <p class="csF20 csB9">4</p>';
                html += '                            <p class="csF14 csB6">Disagree</p>';
                html += '                        </li>';
                html += '                        <li>';
                html += '                            <p class="csF20 csB9">5</p>';
                html += '                            <p class="csF14 csB6">Strongly Disagree</p>';
                html += '                        </li>';
                html += '                    </ul>';
                html += '                </div>';
            }
            //
            if (question.question_type.match(/text/ig) !== null) {
                html += '                <!-- Text -->';
                html += '                <div class="row">';
                html += '                    <br />';
                html += '                    <div class="col-xs-12">';
                html += '                        <p class="csF14 csB7">Feedback (Elaborate)</p>';
                html += '                        <textarea rows="5" class="form-control"></textarea>';
                html += '                    </div>';
                html += '                </div>';
            }
            html += '            </div>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';
        });
        //
        $('#jsReviewQuestionListArea').html(html);
        //
        if (shift) {
            stepMover('questions');
        }

    }


    //
    function getVideoType(video) {
        //
        if (!video) {
            return '';
        }
        //
        var extension = video.split('.');
        //
        return 'type/' + extension[extension.length - 1].toLowerCase();
    }
});