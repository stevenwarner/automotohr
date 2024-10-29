$(function () {
    const
        goal = {
            title: '',
            description: '',
            startDate: '',
            endDate: '',
            type: '',
            departmentIds: [],
            teamIds: [],
            employeeIds: [],
            measureUnit: '',
            customUnit: '',
            target: '',
            roles: [],
            teams: [],
            departments: [],
            employees: []
        };
    /**
     * 
     */
    $(document).on('click', '.jsCreateGoal', function (event) {
        //
        event.preventDefault();
        //
        Modal({
            Id: 'jsCreateGoalModal',
            Title: 'Create a Goal',
            Body: '',
            Loader: 'jsCreateGoalModalLoader'
        }, async () => {
            //
            const resp = await getCreateGoalBody();
            //
            $('#jsCreateGoalModal .csModalBody').append(resp);
            //
            ml(false, 'jsCreateGoalModalLoader');
            //
            $('#jsCGStartDate').datepicker({
                changeYear: true,
                changeMonth: true,
                formatDate: pm.dateTimeFormats.ymdf,
                onSelect: function (d) {
                    $('#jsCGEndDate').datepicker('option', 'minDate', d);
                    goal.startDate = d;
                }
            });
            //
            $('#jsCGEndDate').datepicker({
                changeYear: true,
                changeMonth: true,
                formatDate: pm.dateTimeFormats.ymdf,
                onSelect: function (d) {
                    goal.endDate = d;
                }
            });

            //
            $('#jsCGType').select2({ minimumResultsForSearch: -1 });
            $('#jsCGGoalType').select2({ minimumResultsForSearch: -1 });
            $('#jsCGDepartments').select2({ closeOnSelect: false });
            $('#jsCGTeams').select2({ closeOnSelect: false });
            $('#jsCGEmployees').select2({ closeOnSelect: false });
            $('#jsCGVDepartments').select2({ closeOnSelect: false });
            $('#jsCGVTeams').select2({ closeOnSelect: false });
            $('#jsCGVEmployees').select2({ closeOnSelect: false });
            $('#jsCGVRoles').select2({ closeOnSelect: false, minimumResultsForSearch: -1 });


            //
            loadFonts();
        });
    });

    /**
     * 
     */
    $(document).on('keyup', '#jsCGTitle', function () {
        goal.title = $(this).val().trim();
    });

    /**
     * 
     */
    $(document).on('keyup', '#jsCGDescription', function () {
        goal.description = $(this).val().trim();
    });

    /**
     *  
     */
    $(document).on('change', '#jsCGType', function () {
        //
        goal.type = $(this).val();
        //
        goal.departmentIds = [];
        goal.teamIds = [];
        goal.employeeIds = [];
        //
        $('#jsCGDR').addClass('dn');
        $('#jsCGTR').addClass('dn');
        $('#jsCGER').addClass('dn');
        //
        if (goal.type == '2') {
            $('#jsCGDR').removeClass('dn');
        } else if (goal.type == '3') {
            $('#jsCGTR').removeClass('dn');
        } else if (goal.type == '4') {
            $('#jsCGER').removeClass('dn');
        }
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGDepartments', function () {
        goal.departmentIds = $(this).val() || [];
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGTeams', function () {
        goal.teamIds = $(this).val() || [];
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGEmployees', function () {
        goal.employeeIds = $(this).val() || [];
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGGoalType', function () {
        //
        goal.measureUnit = $(this).val();
        //
        $('#jsCGCustomGoalType').addClass('dn');
        //
        if (goal.measureUnit == 4) {
            $('#jsCGCustomGoalType').removeClass('dn');
        }
    });

    /**
     * 
     */
    $(document).on('keyup', '#jsCGCustomGoalType', function () {
        goal.customUnit = $(this).val().trim();
    });

    /**
     * 
     */
    $(document).on('keyup', '#jsCGTarget', function () {
        goal.target = $(this).val().trim();
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGVRoles', function () {
        goal.roles = $(this).val() || [];
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGVDepartments', function () {
        goal.departments = $(this).val() || [];
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGVTeams', function () {
        goal.teams = $(this).val() || [];
    });

    /**
     * 
     */
    $(document).on('change', '#jsCGVEmployees', function () {
        goal.employees = $(this).val() || [];
    });

    /**
     * 
     * @returns 
     */
    $(document).on('click', '.jsCGCloseModal', function (event) {
        //
        event.preventDefault();
        //
        alertify.confirm('All the unsaved changes will be lost. <br> Would you like to continue?',
            function () {
                //
                resetGoal();
                $('.jsModalCancel').trigger('click');
            });
    });

    /**
     * 
     * @returns 
     */
    $(document).on('click', '.jsCGSaveGoal', function (event) {
        //
        event.preventDefault();
        //
        console.log(goal);
        //
        if (goal.title == '') {
            handleError("Goal title is required.");
            return;
        }
        //
        if (goal.startDate == '') {
            handleError("Goal start date is required.");
            return;
        }
        //
        if (goal.endDate == '') {
            handleError("Goal end date is required.");
            return;
        }
        //
        if (goal.type == 0) {
            handleError("Goal type is required.");
            return;
        }
        //
        if (goal.type == 2 && goal.departmentIds.length === 0) {
            handleError("Please select at least one department.");
            return;
        }
        //
        if (goal.type == 3 && goal.teamIds.length === 0) {
            handleError("Please select at least one team.");
            return;
        }
        //
        if (goal.type == 4 && goal.employeeIds.length === 0) {
            handleError("Please select at least one employee.");
            return;
        }
        //
        if (goal.measureUnit == 0) {
            handleError("Measure unit is required.");
            return;
        }
        //
        if (goal.measureUnit == 4 && goal.customUnit == '') {
            handleError("Measure unit is required.");
            return;
        }
        //
        if (goal.target == '') {
            handleError("Please set a proper target.");
            return;
        }
        //
        ml(true, 'jsCreateGoalModalLoader');
        //
        $.post(pm.urls.pbase + "save_goal", goal)
            .done(function (resp) {
                ml(false, 'jsCreateGoalModalLoader');
                //
                if (resp.Status === false) {
                    handleError("Something went wrong while creating goal.");
                    return;
                }
                //
                handleSuccess("You have successfully created a goal.", function () {
                    resetGoal();
                    $('.jsModalCancel').trigger('click');
                });
            });
    });

    function resetGoal() {
        goal['title'] = '';
        goal['description'] = '';
        goal['startDate'] = '';
        goal['endDate'] = '';
        goal['type'] = '';
        goal['departmentIds'] = [];
        goal['teamIds'] = [];
        goal['employeeIds'] = [];
        goal['measureUnit'] = '';
        goal['target'] = '';
        goal['roles'] = [];
        goal['teams'] = [];
        goal['departments'] = [];
        goal['employees'] = [];
    }

    /**
     * 
     */
    function getCreateGoalBody() {
        return new Promise((res) => {
            $.get(`${pm.urls.pbase}get_goal_body`)
                .done(function (resp) { res(resp); });
        });
    }

    //
    $(document).on('click', '.jsCGSaveEmployeeGoal', function (event) {
        //
        event.preventDefault();
        //
        console.log(goal);
        //
        if (goal.title == '') {
            handleError("Goal title is required.");
            return;
        }
        //
        if (goal.startDate == '') {
            handleError("Goal start date is required.");
            return;
        }
        //
        if (goal.endDate == '') {
            handleError("Goal end date is required.");
            return;
        }


        goal.type = 4;
        goal.employeeIds = $("#jsemployeeId").val();

        //
        if (goal.measureUnit == 0) {
            handleError("Measure unit is required.");
            return;
        }
        //
        if (goal.measureUnit == 4 && goal.customUnit == '') {
            handleError("Measure unit is required.");
            return;
        }
        //
        if (goal.target == '') {
            handleError("Please set a proper target.");
            return;
        }
        //
        ml(true, 'jsCreateGoalModalLoader');
        //
        $.post(pm.urls.pbase + "save_goal", goal)
            .done(function (resp) {
                ml(false, 'jsCreateGoalModalLoader');
                //
                if (resp.Status === false) {
                    handleError("Something went wrong while creating goal.");
                    return;
                }
                //
                handleSuccess("You have successfully created a goal.", function () {
                    resetGoal();
                    $('.jsModalCancel').trigger('click');
                    window.location.reload();

                });
            });
    });




    function getCreateEmployeeGoalBody() {
        return new Promise((res) => {
            $.get(`${pm.urls.pbase}get_employee_goal_body`)
                .done(function (resp) { res(resp); });
        });
    }


    $(document).on('click', '.jsCreateGoalEmployee', function (event) {
        //
        event.preventDefault();

        //
        Modal({
            Id: 'jsCreateGoalModal',
            Title: 'Create a Goal',
            Body: '',
            Loader: 'jsCreateGoalModalLoader'
        }, async () => {
            //
            const resp = await getCreateEmployeeGoalBody();
            //
            $('#jsCreateGoalModal .csModalBody').append(resp);
            //
            ml(false, 'jsCreateGoalModalLoader');
            //
            $('#jsCGStartDate').datepicker({
                changeYear: true,
                changeMonth: true,
                formatDate: pm.dateTimeFormats.ymdf,
                onSelect: function (d) {
                    $('#jsCGEndDate').datepicker('option', 'minDate', d);
                    goal.startDate = d;
                }
            });
            //
            $('#jsCGEndDate').datepicker({
                changeYear: true,
                changeMonth: true,
                formatDate: pm.dateTimeFormats.ymdf,
                onSelect: function (d) {
                    goal.endDate = d;
                }
            });

            //
            $('#jsCGType').select2({ minimumResultsForSearch: -1 });
            $('#jsCGGoalType').select2({ minimumResultsForSearch: -1 });
            $('#jsCGDepartments').select2({ closeOnSelect: false });
            $('#jsCGTeams').select2({ closeOnSelect: false });
            $('#jsCGEmployees').select2({ closeOnSelect: false });

            //
            loadFonts();
        });
    });

});