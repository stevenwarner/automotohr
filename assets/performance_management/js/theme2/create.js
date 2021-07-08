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
            custom_runs: getCustomRuns()
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
            if (!resp.Status) {
                handleError(resp.Msg);
                return;
            }
            //
            // loadReviewers();
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
        });

    });


    /**
     * 
     * @param {*} targetId 
     * @param {*} type 
     * @param {*} id 
     */
    $('.jsReviewRevieweeSearchBtn').click(function(event) {
        //
        event.preventDefault();
        //
        loadReviewees();
    });

    /**
     * 
     * @param {*} targetId 
     * @param {*} type 
     * @param {*} id 
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
            if (filter.employees.length > 0 && $.inArray(data['employees'], filter.employees) !== -1) {
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
        //
        stepMover("reviewers");
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

        //
        if (pm.review.reviewers) {
            //
            var tmp = JSON.parse(pm.review.reviewers);
            //
            obj.Reviewers = JSON.parse(pm.review.reviewers);

            return;
        }

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
            step = 'reviewers';
            loadReviewers();
            return;
        }
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
        step = "reviewers";
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
});