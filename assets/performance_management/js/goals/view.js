$(function() {

    const filter = {
        status: -1,
        type: 1,
        employeeId: 0
    };
    let XHR = null;
    //
    let goalsOBJ = {};

    /**
     * 
     */
    $('#jsVGStatus').select2({ minimumResultsForSearch: -1 });
    $('#jsVGEmployee').select2({ minimumResultsForSearch: -1 });

    /**
     * 
     */
    $('.jsVGType').click(function(event) {
        //
        event.preventDefault();
        //
        $('.jsVGType').removeClass('active');
        $(this).addClass('active');
        //
        filter.type = $(this).data().id;
        applyFilter();
    });

    /**
     * 
     */
    $('#jsVGEmployee').change(function() {
        //
        filter.employeeId = $(this).val();
        applyFilter();
    });

    /**
     * 
     */
    $('#jsVGStatus').change(function() {
        //
        filter.status = $(this).val();
        applyFilter();
    });

    /**
     * 
     */
    $(document).on('click', '.jsGoalUpdateBTN', function(event) {
        //
        event.preventDefault();
        //
        const goalBox = $(this).closest('.jsGoalBox');
        //
        const goalId = goalBox.data('id');
        //
        const goal = goalsOBJ[goalId];
        //
        goalBox.find('.jsGoalTrack').select2({
            minimumResultsForSearch: -1
        }).select2('val', goal.on_track);
        goalBox.find('.jsGoalCompletedTarget').val(goal.completed_target);
        goalBox.find('.jsGoalTarget').val(goal.target);
        //
        goalBox.find('.jsBoxSection').fadeOut(0);
        goalBox.find(`.jsBoxSection[data-key="update"]`).fadeIn(0);
    });

    /**
     * 
     */
    $(document).on('click', '.jsGoalUpdateBtn', function(event) {
        //
        event.preventDefault();
        //
        const goalBox = $(this).closest('.jsGoalBox');
        //
        goalBox.find('.jsIPLoader').fadeIn(0);
        //
        let obj = {
            completedTarget: goalBox.find('.jsGoalCompletedTarget').val().trim(),
            target: goalBox.find('.jsGoalTarget').val().trim(),
            onTrack: goalBox.find('.jsGoalTrack').val()
        };
        //
        if (obj.completedTarget == '') {
            handleError(getError('goal_update_error'));
            return;
        }
        obj.action = 'update_goal';
        obj.sid = goalBox.data('id');
        //
        $.post(pm.urls.handler, obj, (resp) => {
            //
            if (resp.Redirect === true) {
                handleedirect();
                return;
            }
            //
            if (resp.Status === false) {
                handleError(getError());
                return;
            }
            //
            handleSuccess(getError('goal_update_success'), loadGoals);
        });
    });

    /**
     * 
     */
    $(document).on('click', '.jsGoalCommentBtn', function(event) {
        //
        event.preventDefault();
        //
        const goalBox = $(this).closest('.jsGoalBox');
        //
        const goalId = goalBox.data('id');
        //
        const goal = goalsOBJ[goalId];
        //
        goalBox.find('.jsBoxSection').fadeOut(0);
        goalBox.find(`.jsBoxSection[data-key="comment"]`).fadeIn(0);
        //
        goalBox.find('.jsIPLoader').fadeIn(0);
        //
        $.get(`${pm.urls.handler}get/comments/${goalId}`, (resp) => {
            //
            if (resp.Redirect === true) {
                handleRedirect();
                return;
            }
            //
            if (resp.Status === false) {
                handleError();
                return;
            }
            //
            setCommentView(resp.Data, goalBox);
        });
    });

    /**
     * 
     */
    $(document).on('click', '.jsGoalCommentSaveBtn', function(event) {
        //
        event.preventDefault();
        //
        const goalBox = $(this).closest('.jsGoalBox');
        //
        if (goalBox.find('.jsGoalComment').val() == '') {
            handleError(getError('comment_missing'));
            return;
        }
        //
        const goalId = goalBox.data('id');
        //
        goalBox.find('.jsIPLoader').fadeIn(0);
        //
        $.post(
            pm.urls.handler, {
                action: "save_comment",
                message: goalBox.find('.jsGoalComment').val().trim(),
                goalId: goalId,
                employeeId: pm.employerId
            }, (resp) => {
                //
                if (resp.Redirect === true) {
                    handleRedirect();
                    return;
                }
                //
                if (resp.Status === false) {
                    handleError();
                    return;
                }
                //
                goalBox.find('.jsGoalComment').val('');
                //
                goalBox.find('.jsGoalCommentBtn').trigger('click');
            }
        );
    });

    /**
     * 
     */
    $(document).on('click', '.jsBoxSectionBackBtn', function(event) {
        //
        event.preventDefault();
        //
        const goalBox = $(this).closest('.jsGoalBox');
        //
        goalBox.find('.jsBoxSection').fadeOut(0);
        goalBox.find(`.jsBoxSection[data-key="${$(this).data('to')}"]`).fadeIn(0);
    });

    /**
     * 
     */
    $(document).on('click', '.jsGoalStatusClose', function(event) {
        //
        event.preventDefault();
        //
        const goalBox = $(this).closest('.jsGoalBox');
        //
        const goalId = goalBox.data('id');
        //
        $.post(
            pm.urls.handler, {
                action: 'change_goal_status',
                goalId: goalId,
                status: 0
            },
            (resp) => {
                //
                if (resp.Redirect === true) {
                    handleRedirect();
                    return;
                }
                //
                if (resp.Status === false) {
                    handleError();
                    return;
                }
                //
                handleSuccess(getError('goal_closed_success'), applyFilter);
            }
        );
    });

    /**
     * 
     */
    $(document).on('click', '.jsGoalStatusOpen', function(event) {
        //
        event.preventDefault();
        //
        const goalBox = $(this).closest('.jsGoalBox');
        //
        const goalId = goalBox.data('id');
        //
        $.post(
            pm.urls.handler, {
                action: 'change_goal_status',
                goalId: goalId,
                status: 1
            },
            (resp) => {
                //
                if (resp.Redirect === true) {
                    handleRedirect();
                    return;
                }
                //
                if (resp.Status === false) {
                    handleError();
                    return;
                }
                //
                handleSuccess(getError('goal_open_success'), applyFilter);
            }
        );
    });

    /**
     * 
     */
    $(document).on('click', '.jsGoalHistory', function(event) {
        //
        event.preventDefault();
        //
        const goalBox = $(this).closest('.jsGoalBox');
        //
        const goalId = goalBox.data('id');
        //
        const goal = goalsOBJ[goalId];
        //
        Modal({
            Id: 'jsGoalHistoryModal',
            Title: `${goal.title} history`,
            Body: '',
            Loader: 'jsGoalHistoryModalLoader',
        }, () => {
            //
            $.get(`${pm.urls.handler}get/history/${goalId}`, (resp) => {
                //
                if (resp.Redirect === true) {
                    handleRedirect();
                    return;
                }
                //
                if (resp.Status === false) {
                    handleError();
                    return;
                }
                //
                setHistoryView(resp.Data, 'jsGoalHistoryModal', 'jsGoalHistoryModalLoader');
            });
        });
    });

    /**
     * 
     */
    $(document).on('click', '.jsEditVisibility', function(event) {
        //
        event.preventDefault();
        //
        const goalBox = $(this).closest('.jsGoalBox');
        //
        const goalId = goalBox.data('id');
        //
        const goal = goalsOBJ[goalId];
        //
        Modal({
            Id: 'jsGoalVisibiltyModal',
            Title: `${goal.title} - Visibility`,
            Body: '',
            Loader: 'jsGoalVisibiltyModalLoader',
        }, () => {
            let rows = `
            <div class="container">
            <div class="row mb10 jsCGBoxVisibilty">
                <div class="col-sm-3 col-xs-12">
                    <label class="pa10">Who has access</label>
                </div>
                <div class="col-sm-9 col-xs-12">
                    <div class="row">
                        <div class="col-sm-12 ma10">
                            <label>Roles</label>
                            <select multiple id="jsEGVisibilityRoles"></select> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 ma10">
                            <label>Teams</label>
                            <select multiple id="jsEGVisibilityTeams"></select> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 ma10">
                            <label>Departments</label>
                            <select multiple id="jsEGVisibilityDepartments"></select> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 ma10">
                            <label>Employees</label>
                            <select multiple id="jsEGVisibilityEmployees"></select> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 ma10">
                            <button id="jsEGSaveBtn" data-id="${goalId}" class="btn btn-orange btn-lg">Update Visibility</button>
                        </div>
                    </div>
                </div>
            </div></div>`;
            $('#jsGoalVisibiltyModal .csModalBody').html(rows);
            let options = '';
            //
            options = '<option value="0">[Select a role]</option>';
            options += '<option value="admin">Admin</option>';
            options += '<option value="hiring_manager">Hiring Manager</option>';
            options += '<option value="manager">Manager</option>';
            options += '<option value="employee">Employee</option>';
            //
            $('#jsEGVisibilityRoles').html(options).select2({ minimumResultsForSearch: -1 });
            //
            options = '<option value="0">[Select a team]</option>';
            //
            dnt['teams'].map((team) => {
                options += `<option value="${team.sid}">${team.name}</option>`;
            });
            $('#jsEGVisibilityTeams').html(options).select2();
            //
            options = '<option value="0">[Select a department]</option>';
            dnt['departments'].map((team) => {
                options += `<option value="${team.sid}">${team.name}</option>`;
            });
            $('#jsEGVisibilityDepartments').html(options).select2();
            //
            options = '<option value="0">[Select an employee]</option>';
            //
            pm.cemployees.map((em) => {
                options += `<option value="${em.userId}">${remakeEmployeeName(em)}</option>`;
            });
            $('#jsEGVisibilityEmployees').html(options).select2();
            //
            let roles = goal.roles != '' ? JSON.parse(goal.roles) : [];
            let teams = goal.teams != '' ? JSON.parse(goal.teams) : [];
            let departments = goal.departments != '' ? JSON.parse(goal.departments) : [];
            let employees = goal.employees != '' ? JSON.parse(goal.employees) : [];
            //
            console.log(goal, roles)
            $('#jsEGVisibilityRoles').select2('val', roles);
            $('#jsEGVisibilityTeams').select2('val', teams);
            $('#jsEGVisibilityDepartments').select2('val', departments);
            $('#jsEGVisibilityEmployees').select2('val', employees);
        });
    });


    $(document).on('click', '#jsEGSaveBtn', function(event) {
        event.preventDefault();
        //
        ml(true, 'jsGoalVisibiltyModalLoader');
        $.post(pm.urls.handler, {
            action: 'update_visibility',
            goalId: $(this).data('id'),
            roles: $('#jsEGVisibilityRoles').val() || [],
            teams: $('#jsEGVisibilityTeams').val() || [],
            departments: $('#jsEGVisibilityDepartments').val() || [],
            employees: $('#jsEGVisibilityEmployees').val() || []
        }, () => {
            //
            if (resp.Redirect === true) {
                handleRedirect();
                return;
            }
            //
            if (resp.Status === false) {
                handleError(getError());
                return;
            }
            //
            handleSuccess(getError('visibility_updated'), () => {
                $('.jsModalCancel').trigger('click');
                window.location.reload();
            })
        });
    });



    /**
     * 
     */
    function applyFilter() {
        //
        loadGoals();
    }

    /**
     * 
     */
    function loadGoals() {
        //
        if (XHR !== null) XHR.abort();
        //
        XHR = $.post(
            pm.urls.handler, {
                action: "get_goals",
                filter: filter
            },
            (resp) => {
                XHR = null;
                //
                if (resp.Redirect === true) {
                    handleRedirect();
                    return;
                }
                //
                if (resp.Status === false) {
                    handleError(getError('view_goals_error'));
                    return;
                }
                //
                setView(resp.Data);
            }
        );
    }

    /**
     * 
     */
    function setView(goals) {
        goalsOBJ = {};
        if (goals.length === 0) {
            $('.jsGoalWrap').html('<p class="alert alert-info text-center">No records found.</p>');
            return;
        }
        //
        let rows = '<div class="row">';
        //
        goals.map((goal) => {
            if (filter.type == 1) {
                //
                if (pm.permission.employeeIds !== undefined) {
                    if ($.inArray(goal.employee_sid, pm.permission.employeeIds) === -1) { return; }
                }
            }
            if (filter.type == 2) {
                //
                if (pm.permission.teamIds !== undefined) {
                    if ($.inArray(goal.employee_sid, pm.permission.teamIds) === -1) { return; }
                }
            }
            if (filter.type == 3) {
                //
                if (pm.permission.departmentIds !== undefined) {
                    if ($.inArray(goal.employee_sid, pm.permission.departmentIds) === -1) { return; }
                }
            }
            goalsOBJ[goal.sid] = goal;
            //
            let startDate = moment(goal.start_date, 'YYYY-MM-DD');
            let endDate = moment(goal.end_date, 'YYYY-MM-DD');
            let todayDate = moment();
            let totalDays = endDate.diff(startDate, 'days');
            let totalDays2 = todayDate.diff(startDate, 'days');
            let completed = goal.completed_target * 100 / goal.target;
            let pp = totalDays2 * 100 / totalDays;
            pp = pp >= 99 ? 99 : pp
                // 
            rows += ` <!-- Box -->`;
            rows += `<div class="col-sm-3 col-xs-12">`;
            rows += `    <div class="csPageBox csRadius5 jsGoalBox" data-id="${goal.sid}">`;
            rows += `       <div class="csIPLoader jsIPLoader" data-page="goal_box"><i class="fa fa-circle-o-notch fa-spin"></i></div>`;
            rows += `        <!-- HEADER -->`;
            rows += `        <div class="csPageHeader bbb pl10 pr10">`;
            rows += `            <h4>`;
            rows += `                <strong>${goal.title}</strong>`;
            rows += `                <span class="pull-right">`;
            if (goal.status == 1)
                rows += `                    <button class="btn btn-black btn-xs mt0 jsGoalStatusClose jsPopover" title="Close this goal"><i class="fa fa-times-circle mr0"></i></button>`;
            else
                rows += `                    <button class="btn btn-black btn-xs mt0 jsGoalStatusOpen jsPopover" title="Open this goal"><i class="fa fa-check-circle  mr0"></i></button>`;
            rows += `                    <button class="btn btn-black btn-xs mt0 jsGoalHistory jsPopover" title="Show history"><i class="fa fa-history mr0"></i></button>`;
            rows += `                    <button class="btn btn-black btn-xs mt0 jsEditVisibility jsPopover" title="Edit Visibility"><i class="fa fa-users mr0"></i></button>`;
            rows += `                </span>`;
            rows += `            </h4>`;
            rows += `        </div>`;
            rows += `        <!-- Main screen -->`;
            rows += `        <div class="csPageSection jsBoxSection" data-key="main">`;
            rows += `            <!-- BODY -->`;
            rows += `            <div class="csPageBody csGoalBoxH p10">`;
            rows += `                <!--  -->`;
            rows += `                <div class="row">`;
            rows += `                    <!-- Employee -->`;
            rows += `                    <div class="col-sm-8 col-xs-12">`;
            if (filter.type == 1) {
                let em = getEmployee(goal.employee_sid, 'userId');
                rows += `                        <div class="csEBox">`;
                rows += `                            <figure>`;
                rows += `                                <img src="${getImageURL(em.image)}" />`;
                rows += `                            </figure>`;
                rows += `                            <div class="csEBoxText">`;
                rows += `                                <h4 class="mb0 ma10"><strong>${em.first_name} ${em.last_name}</strong></h4>`;
                rows += `                                <p class="mb0"><strong>${remakeEmployeeName(em, false)}</strong></p>`;
                rows += `                                <p><strong>#${getEmployeeId(em.userId, em.employee_number)}</strong></p>`;
                rows += `                            </div>`;
                rows += `                        </div>`;
            } else {
                rows += `                        <div class="csEBox">`;
                rows += `                            <figure>`;
                rows += `                                <img src="${getImageURL(pm.companyLogo)}" />`;
                rows += `                            </figure>`;
                rows += `                            <div class="csEBoxText">`;
                if (filter.type == 2) {
                    rows += `                                <h4 class="mb0 ma10"><strong>${getTeamName(goal.employee_sid)}</strong></h4>`;
                } else if (filter.type == 3) {
                    rows += `                                <h4 class="mb0 ma10"><strong>${getDepartmentName(goal.employee_sid)}</strong></h4>`;
                } else {
                    rows += `                                <h4 class="mb0 ma10"><strong>${pm.companyName}</strong></h4>`;
                }
                rows += `                            </div>`;
                rows += `                        </div>`;
            }
            rows += `                    </div>`;
            rows += `                    <!-- Track Row -->`;
            rows += `                    <div class="col-sm-4 col-xs-12">`;
            rows += `                        <div class="text-right">`;
            rows += `                            <h4 class="mb0"><strong>${goal.on_track == 1 ? "On" : "Off"} Track</strong></h4>`;
            rows += `                            <p class="ma0">As Of ${todayDate.format(pm.dateTimeFormats.mdy)}</p>`;
            rows += `                        </div>`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `                <div class="row">`;
            rows += `                    <div class="col-sm-12">`;
            rows += `                        <p class="text-right">`;
            rows += `                            <strong>${getMeasureSymbol(goal.measure_type)} ${goal.completed_target} / ${goal.target}</strong>`;
            rows += `                        </p>`;
            rows += `                    </div>`;
            rows += `                    <div class="col-sm-12 col-xs-12">`;
            rows += `                        <div class="progress">`;
            rows += `                            <div class="progress-bar" style="width:  ${completed}%;"></div>`;
            rows += `                        </div>`;
            rows += `                        <div class="row ma10">`;
            rows += `                            <div class="col-sm-6">`;
            rows += `                                <p><strong>${startDate.format(pm.dateTimeFormats.mdy)}</strong></p>`;
            rows += `                            </div>`;
            rows += `                            <div class="col-sm-6">`;
            rows += `                                <p class="text-right"><strong>${endDate.format(pm.dateTimeFormats.mdy)}</strong></p>`;
            rows += `                            </div>`;
            rows += `                        </div>`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `                <div class="row">`;
            rows += `                    <div class="col-sm-12 col-xs-12">`;
            rows += `                        <h5${goal.description}</h5>`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `                `;
            rows += `            </div>`;
            rows += `            <!-- FOOTER -->`;
            rows += `            <div class="csPageFooter bbt p10">`;
            rows += `                <div class="row">`;
            rows += `                    <div class="col-sm-6 col-xs-12">`;
            rows += `                        <button class="btn btn-orange form-control jsGoalUpdateBTN"><i class="fa fa-pencil"></i> Update</button>`;
            rows += `                    </div>`;
            rows += `                    <div class="col-sm-6 col-xs-12">`;
            rows += `                        <button class="btn btn-black form-control jsGoalCommentBtn"><i class="fa fa-comment"></i> Comment</button>`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `            </div>`;
            rows += `        </div>`;
            rows += `        <!-- Comment screen -->`;
            rows += `        <div class="csPageSection jsBoxSection dn" data-key="comment">`;
            rows += `            <!-- BODY -->`;
            rows += `            <div class="csPageBody csGoalBoxH">`;
            rows += `                <ul class="csChatMenu jsGoalCommentWrap"></ul>`;
            rows += `            </div>`;
            rows += `            <!-- FOOTER -->`;
            rows += `            <div class="csPageFooter bbt p10">`;
            rows += `                <div class="row">`;
            rows += `                    <div class="col-sm-8 col-xs-12">`;
            rows += `                        <textarea class="form-control jsGoalComment" placeholder="John Doe has completed his tasks."></textarea>`;
            rows += `                    </div>`;
            rows += `                    <div class="col-sm-4 col-xs-12">`;
            rows += `                        <button class="btn btn-orange form-control jsGoalCommentSaveBtn"><i class="fa fa-save"></i> Save</button>`;
            rows += `                        <button class="btn btn-black form-control jsBoxSectionBackBtn" data-to="main"><i class="fa fa-times"></i> Cancel</button>`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `            </div>`;
            rows += `        </div>`;
            rows += `        `;
            rows += `        <!-- Update -->`;
            rows += `        <div class="csPageSection jsBoxSection dn" data-key="update">`;
            rows += `            <!-- BODY -->`;
            rows += `            <div class="csPageBody p10 csGoalBoxH">`;
            rows += `                <div class="row">`;
            rows += `                    <div class="col-sm-5 col-xs-12">`;
            rows += `                        <h4><strong>Status</strong></h4>`;
            rows += `                    </div>`;
            rows += `                    <div class="col-sm-7 col-xs-12">`;
            rows += `                        <select class="jsGoalTrack">`;
            rows += `                            <option value="1">On Track</option>`;
            rows += `                            <option value="0">Off Track</option>`;
            rows += `                        </select>`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `                <div class="row">`;
            rows += `                    <div class="col-sm-5 col-xs-12">`;
            rows += `                        <h4><strong>Completed Target</strong></h4>`;
            rows += `                    </div>`;
            rows += `                    <div class="col-sm-7 col-xs-12">`;
            rows += `                        <input type="text" class="form-control jsGoalCompletedTarget" />`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `                <div class="row">`;
            rows += `                    <div class="col-sm-5 col-xs-12">`;
            rows += `                        <h4><strong>Target</strong></h4>`;
            rows += `                    </div>`;
            rows += `                    <div class="col-sm-7 col-xs-12">`;
            rows += `                        <input type="text" class="form-control jsGoalTarget" value="" />`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `            </div>`;
            rows += `            <!-- FOOTER -->`;
            rows += `            <div class="csPageFooter bbt p10">`;
            rows += `                <div class="row">`;
            rows += `                    <div class="col-sm-6 col-xs-12">`;
            rows += `                        <button class="btn btn-orange form-control jsGoalUpdateBtn"><i class="fa fa-save"></i> Save</button>`;
            rows += `                    </div>`;
            rows += `                    <div class="col-sm-6 col-xs-12">`;
            rows += `                        <button class="btn btn-black form-control jsBoxSectionBackBtn" data-to="main"><i class="fa fa-times"></i> Cancel</button>`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `            </div>`;
            rows += `        </div>`;
            rows += `        <!-- Visibility -->`;
            rows += `        <div class="csPageSection jsBoxSection dn" data-key="visibility">`;
            rows += `            <!-- BODY -->`;
            rows += `            <div class="csPageBody p10 csGoalBoxH">`;
            rows += `                <div class="row">`;
            rows += `                    <div class="col-sm-12 col-xs-12">`;
            rows += `                        <h4><strong>Employees</strong></h4>`;
            rows += `                    </div>`;
            rows += `                    <div class="col-sm-12 col-xs-12">`;
            rows += `                        <select></select>`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `            </div>`;
            rows += `            <!-- FOOTER -->`;
            rows += `            <div class="csPageFooter bbt p10">`;
            rows += `                <div class="row">`;
            rows += `                    <div class="col-sm-6 col-xs-12">`;
            rows += `                        <button class="btn btn-orange form-control"><i class="fa fa-save"></i> Save</button>`;
            rows += `                    </div>`;
            rows += `                    <div class="col-sm-6 col-xs-12">`;
            rows += `                        <button class="btn btn-black form-control"><i class="fa fa-times"></i> Cancel</button>`;
            rows += `                    </div>`;
            rows += `                </div>`;
            rows += `            </div>`;
            rows += `        </div>`;
            rows += `    </div>`;
            rows += `</div>`;
        });
        //
        rows += '</div>';

        $('.jsGoalWrap').html(rows);
        $('.jsIPLoader').hide(0);
        //
        $('.jsPopover').tooltip({
            placement: 'top auto'
        });
    }

    /**
     * 
     */
    function setCommentView(comments, goalBox) {
        if (comments.length === 0) {
            $('.jsGoalCommentWrap').html('<li><p class="alert alert-info text-center">No comments found.</p></li>');
            goalBox.find('.jsIPLoader').fadeOut(0);
            return;
        }
        //
        let rows = '';
        //
        comments.map((comment) => {
            //
            let em = getEmployee(comment.sender_sid, 'userId');
            //
            let imgRow = '';
            imgRow += `        <div class="col-sm-2 col-xs-2">`;
            imgRow += `            <img src="${getImageURL(em.image)}" />`;
            imgRow += `        </div>`;
            //
            let dataRow = '';
            dataRow += `        <div class="col-sm-10 col-xs-10 ${pm.employerId === parseInt(em.userId) ? 'pr0' : 'pl0'}">`;
            dataRow += `            <span><strong>${em.first_name} ${em.last_name}</strong> ${remakeEmployeeName(em, false)}</span>`;
            dataRow += `            <p>${comment.message}`;
            dataRow += `                <span>${moment(comment.created_at, pm.dateTimeFormats.ymdt).format(pm.dateTimeFormats.mdyt)}</span>`;
            dataRow += `            </p>`;
            dataRow += `        </div>`;
            //
            rows += `<li>`;
            rows += `    <div class="row">`;
            rows += pm.employerId === parseInt(em.userId) ? dataRow + imgRow : imgRow + dataRow;
            rows += `    </div>`;
            rows += `</li>`;
        });
        //
        goalBox.find('.jsGoalCommentWrap').html(rows);
        goalBox.find('.jsIPLoader').fadeOut(0);
        //
        goalBox.find('.jsGoalCommentWrap').scrollTop(goalBox.find('.jsGoalCommentWrap')[0].scrollHeight);
    }

    /**
     * 
     */
    function loadEmployeesInFilter() {
        //
        if (pm.cemployees === undefined) {
            setTimeout(loadEmployeesInFilter, 1000);
            return;
        }
        //
        if (pm.cemployees.length === 0) return;
        //
        let options = '<option value="-1">All</option>';
        //
        pm.cemployees.map((em) => {
            if (pm.permission.employeeIds !== undefined) {
                if ($.inArray(em.userId, pm.permission.employeeIds) === -1) { return; }
            }
            options += `<option value="${em.userId}">${remakeEmployeeName(em)}</option>`;
        });
        //
        $('#jsVGEmployee').html(options).select2();;
    }

    /**
     * 
     */
    function setHistoryView(data, modalId, loader) {
        //
        if (data.length === 0) {
            $(`#${modalId} .csModalBody`).html('<p class="alert alert-info text">No history found.</p>');
            ml(false, loader);
            return;
        }
        //
        let rows = '';
        rows += '<div class="container">';
        rows += '<div class="csPageWrap">';
        rows += '<div class="csPageBox">';
        rows += '<table class="table table-striped">';
        rows += '   <thead>';
        rows += '       <tr>';
        rows += '           <th>Action</th>';
        rows += '           <th>Action Taken</th>';
        rows += '       </tr>';
        rows += '   </thead>';
        //
        data.map((history) => {
            let em = getEmployee(history.employee_sid, 'userId');
            rows += `<tr>`;
            rows += `   <td>${getAction(JSON.parse(history.note), history.action, em)}</td>`;
            rows += `   <td>${moment(history.created_at, pm.dateTimeFormats.ymdt).format(pm.dateTimeFormats.mdyt)}</td>`;
            rows += `</tr>`;
        });
        rows += `</table>`;
        rows += `</div>`;
        rows += `</div>`;
        rows += `</div>`;
        //
        $(`#${modalId} .csModalBody`).html(rows);
    }

    /**
     * 
     */
    function getAction(o, action, em) {
        //
        let row = '-';
        if (action == 'created') {
            row = `<strong>${remakeEmployeeName(em)}</strong> created the goal.`;
        } else if (action == 'updated') {
            row = `<strong>${remakeEmployeeName(em)}</strong> updated the goal.`;
        } else if (action == 'commented') {
            row = `<strong>${remakeEmployeeName(em)}</strong> commented on the goal.`;
        }
        //
        return row;
    }
    //
    function getTeamName(teamId) {
        let i = 0,
            il = dnt['teams'].length;
        //
        for (i; i < il; i++) {
            if (dnt['teams'][i]['sid'] == teamId) return dnt['teams'][i]['name'];
        }
        //
        return '';
    }
    //
    function getDepartmentName(departmentId) {
        let i = 0,
            il = dnt['departments'].length;
        //
        for (i; i < il; i++) {
            if (dnt['departments'][i]['sid'] == departmentId) return dnt['departments'][i]['name'];
        }
        //
        return '';
    }
    //
    loadGoals();
    //
    loadEmployeesInFilter();
});