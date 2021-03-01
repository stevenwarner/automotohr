$(function() {
    const
        goal = {
            title: '',
            description: '',
            startDate: '',
            endDate: '',
            goalType: '',
            employeeId: '',
            measure: '',
            target: '',
            alignedGoal: ''
        };

    /**
     * 
     */
    $(document).on('click', '.jsCreateGoal', function(event) {
        //
        event.preventDefault();
        //
        Modal({
            Id: 'jsCreateGoalModal',
            Title: 'Create a Goal',
            Body: '',
            Loader: 'jsCreateGoalModalLoader'
        }, async() => {
            //
            const resp = await getCreateGoalBody();
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
            if (pm.employees === undefined) {
                const employees = await getEmployeeListWithDnT();
                //
                pm.employees = employees.Data;
            }
            //
            const companyGoals = await getCompanyGoals();
            //
            pm.companyGoals = companyGoals.Data;
            //
            $('#jsCreateGoalModal .csModalBody').append(resp.Data);
            //
            $('#jsCGStartDate').datepicker({
                changeYear: true,
                changeMonth: true,
                minDate: 0,
                formatDate: pm.dateTimeFormats.ymdf,
                onSelect: function(d) {
                    $('#jsCGEndDate').datepicker('option', 'minDate', d);
                    goal.startDate = d;
                }
            });
            //
            $('#jsCGEndDate').datepicker({
                changeYear: true,
                changeMonth: true,
                minDate: 0,
                formatDate: pm.dateTimeFormats.ymdf,
                onSelect: function(d) {
                    goal.endDate = d;
                }
            });
            //
            let options = '<option value="0">[Select an employee]</option>';
            //
            pm.employees.map((em) => {
                options += `<option value="${em.userId}">${remakeEmployeeName(em)}</option>`;
            });
            //
            $('#jsCGType').select2({ minimumResultsForSearch: -1 });
            $('#jsCGEmployee').html(options).select2();
            $('#jsCGMeasure').select2({ minimumResultsForSearch: -1 });
            //
            ml(false, 'jsCreateGoalModalLoader');
            //
            if (pm.companyGoals.length == 0) {
                $('#jsCreateGoalModal .jsAlignBox').html('<p class="alert alert-info text-center">No goals found.</p>');
                return;
            }
            //
            let rows = '';
            //
            rows += `<div class="row">`;
            pm.companyGoals.map((go) => {
                rows += `    <div class="col-sm-4 col-xs-12">`;
                rows += `            <div class="csPageBox csRadius5 csGoalCard jsGoalCard csCursorSelect" data-id="${go.sid}">`;
                rows += `                <div class="csPageBoxHeader p10">`;
                rows += `                    <h4>`;
                rows += `                        <strong>${go.title}</strong>`;
                rows += `                    </h4>`;
                rows += `                </div>`;
                rows += `                <div class="csPageBoxBody">`;
                rows += `                    <div class="csGoalCardProgress p10">`;
                rows += `                        <h4>`;
                rows += `                            <span class="csBTNBox">`;
                rows += `                                ${getMeasureSymbol(go.measure_type)} ${go.target}`;
                rows += `                            </span>`;
                rows += `                        </h4>`;
                rows += `                        <div class="clearfix"></div>`;
                rows += `                    </div>`;
                rows += `                </div>`;
                rows += `            </div>`;
                rows += `        </div>`;
            });
            rows += `</div>`;
            //
            $('#jsCreateGoalModal .jsAlignBox').html(rows);
        });
    });

    /**
     * 
     */
    $(document).on('keyup', '#jsCGTitle', function() {
        goal.title = $(this).val().trim();
    });

    /**
     * 
     */
    $(document).on('keyup', '#jsCGDescription', function() {
        goal.description = $(this).val().trim();
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGType', function() {
        goal.goalType = $(this).val();
        //
        if (goal.goalType == 1) {
            $('.jsCGBoxIndividual').removeClass('dn');
        } else {
            $('.jsCGBoxIndividual').addClass('dn');
        }
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGEmployee', function() {
        goal.employeeId = $(this).val();
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGMeasure', function() {
        goal.measure = $(this).val();
    });

    /**
     * 
     */
    $(document).on('keyup', '#jsCGTarget', function() {
        goal.target = $(this).val().trim();
    });

    /**
     * 
     */
    $(document).on('click', '.jsGoalCard', function() {
        goal.alignedGoal = $(this).data().id;
        $('.jsGoalCard').removeClass('active');
        $(this).addClass('active');
    });

    /**
     * 
     */
    $(document).on('click', '.jsCGSave', function(event) {
        //
        event.preventDefault();
        //
        if (goal.title == '') {
            handleError(getError('goal_title'));
            return;
        }
        if (goal.startDate == '') {
            handleError(getError('goal_start_date'));
            return;
        }
        if (goal.endDate == '') {
            handleError(getError('goal_end_date'));
            return;
        }
        if (goal.goalType == 0) {
            handleError(getError('goal_type'));
            return;
        }
        if (goal.goalType == 1 && (goal.employeeId == null || goal.employeeId == '')) {
            handleError(getError('goal_employee'));
            return;
        }
        if (goal.target == '') {
            handleError(getError('goal_target'));
            return;
        }
        //
        ml(true, 'jsCreateGoalModalLoader');
        //
        $.post(
            pm.urls.handler, {
                action: 'save_goal',
                goal: goal
            },
            (resp) => {
                if (resp.Status === false) {
                    handleError(getError('save_goal_error'));
                    ml(false, 'jsCreateGoalModalLoader');
                    return;
                }
                handleSuccess(getError('save_goal_success'), function() {
                    goal.title = '';
                    goal.description = '';
                    goal.startDate = '';
                    goal.endDate = '';
                    goal.goalType = '';
                    goal.employeeId = '';
                    goal.measure = '';
                    goal.target = '';
                    goal.alignedGoal = '';
                    //
                    $('.jsModalCancel').trigger('click');
                });
            }
        );
    });

    /**
     * 
     */
    function getCreateGoalBody() {
        return new Promise((res) => {
            $.get(`${pm.urls.handler}get/goal_body`)
                .done(function(resp) { res(resp); })
                .fail(function(resp) {
                    res(getMessage(resp.status, true));
                });;
        });
    }

    /**
     * Get employees list with dnt
     * 
     * @return {Promise}
     */
    function getEmployeeListWithDnT() {
        return new Promise(function(res) {
            $.get(`${pm.urls.handler}get/employeeListWithDnT`)
                .done(function(resp) { res(resp); })
                .fail(function(resp) {
                    res(getMessage(resp.status, true));
                });
        });
    }

    /**
     * Get company goals
     * 
     * @return {Promise}
     */
    function getCompanyGoals() {
        return new Promise(function(res) {
            $.get(`${pm.urls.handler}get/company_goals`)
                .done(function(resp) { res(resp); })
                .fail(function(resp) {
                    res(getMessage(resp.status, true));
                });
        });
    }

    

});