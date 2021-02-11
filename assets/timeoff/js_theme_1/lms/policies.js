$(function() {
    //
    fetchPoliciesLMS();
    //
    $(document).on("click", ".jsScheduledTimeoOfBTN", function(e) {
        //
        e.preventDefault();
        //
        Modal({
                Id: "jsViewTimeOffModal",
                Loader: "jsViewTimeOffModalLoader",
                Title: `Time-offs scheduled for the year ${moment().format("YYYY")}`,
                Body: ` 
                <div class="tabel-responsive">
                    <div id="jsData"></div>
                    <table class="table table-striped csCustomTableHeader">
                        <thead>
                            <tr>
                                <th>Policy</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Reason</th>
                                <th>Requested On</th>
                            </tr>
                        </thead>
                        <tbody id="jsViewTimeOffModalTable"></tbody>
                    </table>
                </div>
            `,
            },
            async() => {
                //
                const requests = await employeeRequests("approved", $(this).closest('.csCard').data('id'));
                //
                if (requests.Data.length == 0) {
                    //
                    ml(false, "jsViewTimeOffModalLoader");
                    //
                    $("#jsViewTimeOffModalTable").html(
                        `<tr><td colspan="5"><p class="alert alert-info text-center">No time-offs found.</p></td></tr>`
                    );
                    return;
                }
                //
                let rows = "";
                //
                requests.Data.map((v) => {
                    rows += `
                    <tr>
                        <td> 
                            <div class="upcoming-time-info">            
                                <div class="icon-image">                   
                                    <img src="${baseURL}assets/images/upcoming-time-off-icon.png" class="emp-image" alt="emp-1">             
                                </div>             
                                <div class="text">                  
                                    <h4>${
                                    v.request_from_date == v.request_to_date
                                        ? moment(v.request_from_date).format(
                                            timeoffDateFormat
                                        )
                                        : moment(v.request_from_date).format(
                                            timeoffDateFormat
                                        ) +
                                        " - " +
                                        moment(v.request_to_date).format(
                                            timeoffDateFormat
                                        )
                                    }</h4>                  
                                    <span>${v.title}</span><br />          
                                    <span>${v.breakdown.text}</span>          
                                </div>       
                            </div>
                        </td>`;

                    rows += `
                        <td>
                          <p class="">${ucwords(v.status)}</p>
                        </td>
                    `;

                    rows += `
                    <td>
                        <div class="progress" style="margin-top: 10px;">
                            <div class="progress-bar progress-bar-success" role="progressbar" style="width: ${
                            v.status == "pending"
                                ? v.level_status != "pending"
                                ? 50
                                : 0
                                : 100
                            }%;">
                                <span class="sr-only"> ${
                                v.status == "pending"
                                    ? v.level_status != "pending"
                                    ? 50
                                    : 0
                                    : 100
                                } % Complete</span>
                            </div>
                            <p>${
                            v.status == "pending"
                                ? v.level_status != "pending"
                                ? 50
                                : 0
                                : 100
                            }%</p>
                        </div>
                        ${getApproverLisiting(v.history)}
                    </td>`;

                    rows += `
                        <td>
                          <p class="">${ucwords(v.reason)}</p>
                        </td>
                    `;

                    rows += `
                        <td>
                          <p class="">${moment(v.created_at).format(timeoffDateFormatWithTime)}</p>
                        </td>
                    `;
                    //
                    rows += "</tr>";
                });
                //
                ml(false, "jsViewTimeOffModalLoader");
                //
                $("#jsViewTimeOffModalTable").html(rows);
                //
                $(".csApproverBox").popover({
                    html: true,
                    trigger: "hover",
                    placement: "left",
                });
            }
        );
    });

    //
    $(document).on("click", ".jsRemainingTimeOffBTN", function(e) {
        //
        e.preventDefault();
        //
        Modal({
                Id: "jsViewTimeOffModal",
                Loader: "jsViewTimeOffModalLoader",
                Title: `Time-offs requested for the year ${moment().format("YYYY")}`,
                Body: ` 
                <div class="tabel-responsive">
                    <div id="jsData"></div>
                    <table class="table table-striped csCustomTableHeader">
                        <thead>
                            <tr>
                                <th>Policy</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Reason</th>
                                <th>Requested On</th>
                            </tr>
                        </thead>
                        <tbody id="jsViewTimeOffModalTable"></tbody>
                    </table>
                </div>
            `,
            },
            async() => {
                //
                const requests = await employeeRequests("pending", $(this).closest('.csCard').data('id'));
                //
                if (requests.Data.length == 0) {
                    //
                    ml(false, "jsViewTimeOffModalLoader");
                    //
                    $("#jsViewTimeOffModalTable").html(
                        `<tr><td colspan="5"><p class="alert alert-info text-center">No time-off requests found.</p></td></tr>`
                    );
                    return;
                }
                //
                let rows = "";
                //
                requests.Data.map((v) => {
                    rows += `
                    <tr>
                        <td> 
                            <div class="upcoming-time-info">            
                                <div class="icon-image">                   
                                    <img src="${baseURL}assets/images/upcoming-time-off-icon.png" class="emp-image" alt="emp-1" />             
                                </div>             
                                <div class="text">                  
                                    <h4>${
                                    v.request_from_date == v.request_to_date
                                        ? moment(v.request_from_date).format(
                                            timeoffDateFormat
                                        )
                                        : moment(v.request_from_date).format(
                                            timeoffDateFormat
                                        ) +
                                        " - " +
                                        moment(v.request_to_date).format(
                                            timeoffDateFormat
                                        )
                                    }</h4>                  
                                    <span>${v.title}</span><br />          
                                    <span>${v.breakdown.text}</span>          
                                </div>       
                            </div>
                        </td>`;

                    rows += `
                        <td>
                          <p class="">${ucwords(v.status)}</p>
                        </td>
                    `;

                    rows += `
                    <td>
                        <div class="progress" style="margin-top: 10px;">
                            <div class="progress-bar progress-bar-success" role="progressbar" style="width: ${
                            v.status == "pending"
                                ? v.level_status != "pending"
                                ? 50
                                : 0
                                : 100
                            }%;">
                                <span class="sr-only"> ${
                                v.status == "pending"
                                    ? v.level_status != "pending"
                                    ? 50
                                    : 0
                                    : 100
                                } % Complete</span>
                            </div>
                            <p>${
                            v.status == "pending"
                                ? v.level_status != "pending"
                                ? 50
                                : 0
                                : 100
                            }%</p>
                        </div>
                        ${getApproverLisiting(v.history)}
                    </td>`;

                    rows += `
                        <td>
                          <p class="">${ucwords(v.reason)}</p>
                        </td>
                    `;

                    rows += `
                        <td>
                          <p class="">${moment(v.created_at).format(timeoffDateFormatWithTime)}</p>
                        </td>
                    `;
                    //
                    rows += "</tr>";
                });
                //
                ml(false, "jsViewTimeOffModalLoader");
                //
                $("#jsViewTimeOffModalTable").html(rows);
                //
                $(".csApproverBox").popover({
                    html: true,
                    trigger: "hover",
                    placement: "left",
                });
            }
        );
    });

    //
    $(document).on("click", ".jsShowTime", function(e) {
        //
        e.preventDefault();
        //
        let policy = getPolicy(
            $(this).closest('.csCard').data('id'),
            window.timeoff.employeePolicies
        );
        //
        Modal({
                Id: "jsViewTimeModal",
                Loader: "jsViewTimeModalLoader",
                Title: `Time for ${policy.Title}`,
                Body: ` 
                <div class="tabel-responsive">
                    <table class="table table-striped csCustomTableHeader">
                        <tbody id="jsViewTimeModalTable"></tbody>
                    </table>
                </div>
            `,
            },
            async() => {

                if (policy.Plans.length === 0) {
                    $("#jsViewTimeModalTable").html(`<tr><td><p class="alert alert-info">No time is available to use</p></td></tr>`);
                    ml(false, "jsViewTimeModalLoader");
                    return;
                }
                //
                let rows = '';
                //
                policy.Plans.map((pl) => {
                    rows += `<tr><td>${pl.time} will be available on ${moment(pl.date, 'YYYY-MM-DD').format(timeoffDateFormat)}</td></tr>`;
                });
                //
                $("#jsViewTimeModalTable").html(rows);
                //
                ml(false, "jsViewTimeModalLoader");
            }
        );
    });

    //
    async function fetchPoliciesLMS() {
        //
        const policies = await fetchEmployeePolicies();
        //
        if (policies.Data.length == 0) {
            console.log("No policies found.");
            return;
        }
        //
        window.timeoff.employeePolicies = policies.Data;
        //
        let rows = "";
        //
        policies.Data.map((policy) => {
            rows += ` 
            <div class = "item active">
                <div class = "col-sm-4 col-xs-12" >
                    <div class = "card csCard" data-id="${policy.PolicyId}">
                        <div class = "card-body" >
                            <h4 class = "card-title" >
                                <strong> ${ policy.Title } </strong> <br />
                                <small> (${ policy.Category }) 
                                ${
                                    policy.Plans.length > 0 ? '<button class="btn btn-success btn-xs jsShowTime pull-right"><i class="fa fa-eye" title="Show time" placement="left"></i> View Time</button>' : ''
                                }
                                
                                </small> 
                            </h4> 
                            <div class="csSeprator"></div>
                            <p class = "card-text" >${ policy.IsUnlimited == 0 ? policy.RemainingTime.text : "Unlimited" } remaining</p> 
                            <p class = "card-text" >${ policy.ConsumedTime.text } scheduled</p> 
                            <p class = "card-text" >${ policy.Approved } time-off${ policy.Approved == 0 || policy.Approved > 1 ? 's' : '' } scheduled 
                                <span class = "pull-right" >
                                    <button class = "btn btn-success btn-xs jsScheduledTimeoOfBTN" title = "Show scheduled time-offs" placement = "top" > 
                                        <i class = "fa fa-eye"></i>
                                    </button >
                                </span> 
                            </p> 
                            <p class = "card-text" > ${ policy.Pending } time-off${ policy.Pending == 0 || policy.Pending > 1 ? 's' : '' } requested
                                <span class = "pull-right" >
                                    <button class = "btn btn-success jsRemainingTimeOffBTN btn-xs" title = "Show requested time-offs" placement = "top" > 
                                        <i class = "fa fa-eye" ></i>
                                    </button>
                                </span> 
                            </p> 
                            <p class = "card-text" > Employment Status: ${ ucwords(policy.EmployementStatus) } </p> 
                            <p class = "card-text" > ${ ucwords(policy.Reason) } </p>`;

            rows += '<div class="csCardFoot">';
            if (policy.Reason != '' || (policy.IsUnlimited == 0 && policy.RemainingTime.M.minutes == 0)) {
                rows += `
                                <button class = "btn btn-success form-control" disabled> 
                                    <i class = "fa fa-plus" > </i> &nbsp;REQUEST A TIME-OFF
                                </button>
                    `;
            } else {

                rows += ` 
                                <button class = "btn btn-success jsCreateRequest form-control" data-id = "${employeeId}" data-policyId = "${policy.PolicyId}" > 
                                    <i class = "fa fa-plus" > </i> &nbsp;REQUEST A TIME-OFF
                                </button>`;
            }
            rows += `
                        </div> 
                        </div> 
                    </div> 
                </div> 
            </div>`;
        });
        //
        $("#jsEMSCraousal div").html(rows);
        //
        loadTitles();
    }

    // Fetch employee policies
    function fetchEmployeePolicies() {
        return new Promise((res, rej) => {
            //
            $.post(
                handlerURL, {
                    action: "get_employee_policies_with_timeoffs",
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                },
                function(resp) {
                    res(resp);
                }
            );
        });
    }

    // Fetch employee policies
    function employeeRequests(status, policyId) {
        return new Promise((res, rej) => {
            //
            $.post(
                handlerURL, {
                    action: "get_employee_requests_by_status",
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                    status: status,
                    policyId: policyId
                },
                function(resp) {
                    res(resp);
                }
            );
        });
    }
});