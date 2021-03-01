$(function() {

    const filter = {
        status: 'active',
        type: 1,
        employeeId: 0
    };
    let XHR = null;

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
    $('.jsVGEmployee').change(() => {
        //
        filter.employeeId = $(this).val();
        applyFilter();
    });

    /**
     * 
     */
    $('.jsVGStatus').change(() => {
        //
        filter.status = $(this).val();
        applyFilter();
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
    function setView(goals){
        if(goals.length === 0){
            $('.jsGoalWrap').html('<p class="alert alert-info text-center">No records found.</p>');
            return;
        } 
        //
        let rows = '<div class="row">';
        //
        goals.map((goal) => {
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
            rows += `<div class="col-sm-4 col-xs-12">`;
            rows +=`    <div class="csPageBox csRadius5 csGoalCard">`;
            rows +=`        <div class="csPageBoxHeader p10">`;
            rows +=`            <h4>`;
            rows +=`                <strong>${goal.title}</strong>`;
            rows +=`                <span class="csBTNBox">`;
            rows +=`                    <button class="btn mt0">`;
            rows +=`                        <i class="fa fa-ellipsis-v"></i>`;
            rows +=`                    </button>`;
            rows +=`                </span>`;
            rows +=`            </h4>`;
            rows +=`        </div>`;
            rows +=`        <div class="csPageBoxBody">`;
            rows +=`            <div class="csGoalCardDesc p10">`;
            rows +=`                <h5>${goal.description}</h5>`;
            rows +=`            </div>`;
            rows +=`            <div class="csGoalCardProgress p10">`;
            rows +=`                <h4>`;
            rows +=`                    <span class="csBTNBox">`;
            rows +=`                        ${getMeasureSymbol(goal.measure_type)} ${goal.completed_target} / ${goal.target}`;
            rows +=`                    </span>`;
            rows +=`                </h4>`;
            rows +=`                <div class="clearfix"></div>`;
            rows +=`                <div class="progress">`;
            rows +=`                    <div class="progress-bar" role="progressbar"`;
            rows +=`                        aria-valuenow="${completed}" aria-valuemin="0" aria-valuemax="100"`;
            rows +=`                        style="width: ${completed}%;"></div>`;
            rows +=`                    <div class="csProgressPointer" style="left: ${pp}%;" title="sdad"></div>`;
            rows +=`                </div>`;
            rows +=`                <span class="csBTNBoxLeft">`;
            rows +=`                    <p>${startDate.format(pm.dateTimeFormats.mdy)}</p>`;
            rows +=`                </span>`;
            rows +=`                <span class="csBTNBox">`;
            rows +=`                    <p>${endDate.format(pm.dateTimeFormats.mdy)}</p>`;
            rows +=`                </span>`;
            rows +=`                <div class="clearfix"></div>`;
            rows +=`            </div>`;
            rows +=`            <!--  -->`;
            rows +=`            <div class="csGoalCardEmp p10">`;
            rows +=`                <div class="row">`;
            rows +=`                    <div class="col-sm-8 col-sm-12">`;
            if(filter.type == 1){
                //
                let em = getEmployee(goal.employee_sid, 'userId');
                //
                rows +=`                        <div class="csEBox">`;
                rows +=`                            <figure>`;
                rows +=`                                <img src="${getImageURL(em.image)}" />`;
                rows +=`                            </figure>`;
                rows +=`                            <div class="csEBoxText">`;
                rows +=`                                <h4 class="mb0">`;
                rows +=`                                    <strong>${em.first_name} ${em.last_name}</strong>`;
                rows +=`                                </h4>`;
                rows +=`                                <p>${remakeEmployeeName(em)}</p>`;
                rows +=`                            </div>`;
                rows +=`                        </div>`;
            }
            rows +=`                    </div>`;
            rows +=`                    <div class="col-sm-4 col-sm-12">`;
            rows +=`                        <h4 class="mb0 text-success text-right">On Track</h4>`;
            rows +=`                        <p class="text-right">As of ${todayDate.format(pm.dateTimeFormats.md)}</p>`;
            rows +=`                    </div>`;
            rows +=`                </div>`;
            rows +=`            </div>`;
            rows +=`            <div class="csGoalCardFooter">`;
            rows +=`                <div class="csPageBoxFooter p10">`;
            rows +=`                    <div class="row">`;
            rows +=`                        <div class="col-sm-6 col-xs-12">`;
            rows +=`                            <button`;
            rows +=`                                class="btn btn-orange btn-lg form-control"><i`;
            rows +=`                                    class="fa fa-pencil"></i> Update</button>`;
            rows +=`                        </div>`;
            rows +=`                        <div class="col-sm-6 col-xs-12">`;
            rows +=`                            <button class="btn btn-black btn-lg form-control"><i class="fa fa-comment"></i> Comment</button>`;
            rows +=`                        </div>`;
            rows +=`                    </div>`;
            rows +=`                </div>`;
            rows +=`            </div>`;
            rows +=`            <div class="clearfix"></div>`;
            rows +=`        </div>`;
            rows +=`    </div>`;
            rows +=`</div>`;
        });
        //
        rows += '</div>';

        $('.jsGoalWrap').html(rows);
    }

    //
    loadGoals();
});