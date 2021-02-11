$(function() {
    //
    let callOBJ = {
            Balances: {
                Main: {
                    action: "get_balances",
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                    level: level,
                    filter: {
                        employees: "all",
                        policies: "all",
                        type: $(".jsEditResetCheckbox:checked").val(),
                    },
                    public: 0,
                    inset: 0,
                    offset: 10,
                },
            },
        },
        xhr = null;
    //
    window.timeoff.fetchBalances = fetchBalances;
    //
    fetchBalances();

    // Set Filter
    //
    $("#js-filter-from-date").datepicker({
        dateFormat: "mm-dd-yy",
        changeYear: true,
        changeMonth: true,
        onSelect: function(v) {
            $("#js-filter-to-date").datepicker("option", "minDate", v);
        },
    });

    //
    $("#js-filter-to-date")
        .datepicker({
            dateFormat: "mm-dd-yy",
            changeYear: true,
            changeMonth: true,
        })
        .datepicker("option", "minDate", $("#js-filter-from-date").val());

    // Filter buttons
    $(document).on("click", ".js-apply-filter-btn", applyFilter);
    $(document).on("click", ".js-reset-filter-btn", resetFilter);
    $(document).on("change", ".jsEditResetCheckbox", applyFilter);

    //
    function resetFilter(e) {
        //
        e.preventDefault();
        //
        $("#js-filter-employee").select2("val", "all");
        $("#js-filter-policies").select2("val", "all");
        //
        callOBJ.Balances.Main.inset = 0;
        callOBJ.Balances.Main.offset = 10;
        callOBJ.Balances.Main.filter.employees = "all";
        callOBJ.Balances.Main.filter.policies = "all";
        callOBJ.Balances.Main.filter.type = $(".jsEditResetCheckbox:checked").val();
        //
        fetchBalances();
    }

    //
    function applyFilter(e) {
        //
        e.preventDefault();
        //
        callOBJ.Balances.Main.inset = 0;
        callOBJ.Balances.Main.offset = 10;
        callOBJ.Balances.Main.filter.employees = $("#js-filter-employee").val();
        callOBJ.Balances.Main.filter.policies =
            $("#js-filter-policies").val() == null ?
            "all" :
            $("#js-filter-policies").val();
        callOBJ.Balances.Main.filter.type = $(".jsEditResetCheckbox:checked").val();
        //
        fetchBalances();
    }

    // Fetch plans
    function fetchBalances() {
        //
        if (window.timeoff.employees === undefined) {
            setTimeout(() => {
                fetchBalances();
            }, 1000);
            return;
        }
        //
        if (xhr != null) return;
        //
        ml(true, "balance");
        //
        $(".js-error-row").remove();
        //
        xhr = $.post(handlerURL, callOBJ.Balances.Main, function(resp) {
            //
            xhr = null;
            //
            if (resp.Redirect === true) {
                alertify.alert(
                    "WARNING!",
                    "Your session expired. Please, re-login to continue.",
                    () => {
                        window.location.reload();
                    }
                );
                return;
            }
            //
            if (resp.Status === false && callOBJ.Balances.Main.page == 1) {
                $(".js-ip-pagination").html("");
                $("#js-data-area").html(
                    `<tr class="js-error-row"><td colspan="${
            $(".js-table-head").find("th").length
          }"><p class="alert alert-info text-center">${
            resp.Response
          }</p></td></tr>`
                );
                //
                ml(false, "balance");
                //
                return;
            }
            //
            if (resp.Status === false) {
                //
                $(".js-ip-pagination").html("");
                //
                ml(false, "balance");
                //
                return;
            }
            //
            if (callOBJ.Balances.Main.inset == 0) {
                loadMoreBalance();
            }
            //
            setTable(resp);
        });
    }

    //
    function setTable(resp, typo) {
        //
        let rows = "";
        //
        if (resp.Data.Balances.length == 0) return;
        //
        $.each(resp.Data.Balances, function(i, v) {
            //
            let userRow = getUserById(v.total.UserId, resp.Data.Employees, "userId");
            //
            if (Object.keys(userRow).length == 0) return;
            //
            rows += `<tr data-id="${v.total.UserId}" data-name="${userRow.first_name} ${userRow.last_name}">`;
            rows += '    <td scope="row">';
            rows += '        <div class="employee-info">';
            rows += "            <figure>";
            rows += `                <img src="${getImageURL(
        userRow.image
      )}" class="img-circle emp-image" />`;
            rows += "            </figure>";
            rows += '            <div class="text">';
            rows += `                <h4>${userRow.first_name} ${userRow.last_name}</h4>`;
            rows += `                <p>${remakeEmployeeName(userRow, false)}</p>`;
            rows += `                <p><a href="${baseURL}employee_profile/${
        userRow.userId
      }" target="_blank">Id: ${getEmployeeId(
        userRow.userId,
        userRow.employee_number
      )}</a></p>`;
            rows += "            </div>";
            rows += "        </div>";
            rows += "    </td>";
            rows += "    <td>";
            rows += '        <div class="text">';
            //
            rows += `            <p>${
        userRow.joined_at == "" || userRow.joined_at == null
          ? "-"
          : moment(userRow.joined_at).format(timeoffDateFormat)
      }</p>`;
            rows += "        </div>";
            rows += "    </td>";
            rows += "    <td>";
            rows += '        <div class="text">';
            rows += `            <p>${
        v.total.AllowedTime.text == "" ? "0 hours" : v.total.AllowedTime.text
      } <a href="" data-target="jsBreakdownDivAllowed${
        v.total.UserId
      }" class="csExpandBalance jsExpandBalance" title="See breakdown" placement="top" class="btn btn-xs"><i class="fa fa-plus-circle"></i></a></p>`;
            rows += `        <div class="dn" id="jsBreakdownDivAllowed${v.total.UserId}">`;
            $.each(v, (index, poli) => {
                if (index == "total") return "";
                rows += `<p><strong>${index}</strong><br /> (${poli.AllowedTime.text})</p>`;
            });
            rows += "        </div>";
            rows += "        </div>";
            rows += "    </td>";
            rows += '    <td style="vertical-align: middle;">';
            rows += '        <div class="text">';
            rows += `            <span><strong>Paid:</strong> ${
        v.total.ConsumedTime.text == "" ? "0 hours" : v.total.ConsumedTime.text
      }</span><br />`;
            rows += `            <span><strong>Unpaid:</strong> ${
        v.total.UnpaidConsumedTime.text == ""
          ? "0 hours"
          : v.total.UnpaidConsumedTime.text
      }</span>`;
            rows += "        </div>";
            rows += "    </td>";
            rows += "    <td>";
            rows += '        <div class="text">';
            rows += `            <p>${
        v.total.RemainingTime.text == ""
          ? "0 hours"
          : v.total.RemainingTime.text
      }<a href="" data-target="jsBreakdownDivRemaining${
        v.total.UserId
      }" class="csExpandBalance jsExpandBalance" title="See breakdown" placement="top" class="btn btn-xs"><i class="fa fa-plus-circle"></i></a></p>`;
            rows += `        <div class="dn" id="jsBreakdownDivRemaining${v.total.UserId}">`;
            $.each(v, (index, poli) => {
                if (index == "total") return "";
                rows += `<p><strong>${index}</strong><br /> (${poli.RemainingTime.text})</p>`;
            });
            rows += "        </div>";
            rows += "        </div>";
            rows += "    </td>";

            rows += `    <td>`;
            rows += `    <div class="dropdown" style="margin-top: 10px;">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Action
                        <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="right:0; left: auto;">
                        <li><a href="#" class="jsViewPolicies">View Policies</a></li>
                        <li><a href="#" class="jsViewBalance">Manage Balance</a></li>
                        <li><a href="#" class="jsViewApprovers">View Approvers</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#" class="jsCreateRequest">Create Time-off</a></li>
                        </ul>
                    </div>`;
            rows += `    </td>`;
            rows += `</tr>`;
        });

        //
        if (typo === undefined) $("#js-data-area").html(rows);
        else $("#js-data-area").append(rows);

        //
        $(".js-type-popover").popover({
            html: true,
            trigger: "hover",
        });
        //
        $('[data-toggle="tooltip"]').tooltip();
        //
        ml(false, "balance");
        //
        loadTitles();
    }

    //
    $(document).on("click", ".jsViewPolicies", function(e) {
        //
        e.preventDefault();
        //
        startPolicyProcess(
            $(this).closest("tr").data("id"),
            $(this).closest("tr").data("name")
        );
    });

    //
    $(document).on("click", ".jsViewBalance", function(e) {
        //
        e.preventDefault();
        //
        startBalanceProcess(
            $(this).closest("tr").data("id"),
            $(this).closest("tr").data("name")
        );
    });

    //
    $(document).on("click", ".jsViewApprovers", function(e) {
        //
        e.preventDefault();
        //
        Modal({
                Id: "employeeApproverModal",
                Title: `Approvers for ${$(this).closest("tr").data("name")}`,
                Body: "",
                Loader: "employeeApproverModalLoader",
            },
            async() => {
                //
                const approvers = await fetchEmployeeApprovers(
                    $(this).closest("tr").data("id")
                );
                //
                //
                if (approvers.Redirect === true) {
                    alertify.alert(
                        "WARNING!",
                        "Your session expired. Please, re-login to continue.",
                        () => {
                            window.location.reload();
                        }
                    );
                    return;
                }
                //
                if (approvers.Status === false || approvers.Data.length == 0) {
                    $("#employeeApproverModal .csModalBody").html(
                        `<p class="alert alert-success text-center">No approvers are assigned to this employee.</p>`
                    );
                    //
                    ml(false, "employeeApproverModalLoader");
                    //
                    return;
                }
                //
                let rows = `
            <div class="tabel-responsive">
                <table class="table table-striped csCustomTableHeader">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Can Approve</th>
                        </tr>
                    </thead>
                    <tbody id="employeeApproverModalTable"></tbody>
                </table>
            </div>`;
                $("#employeeApproverModal .csModalBody").html(rows);
                rows = "";
                //
                approvers.Data.map((approver) => {
                    rows += `<tr>`;
                    rows += `   <td>${remakeEmployeeName(approver)}</td>`;
                    rows += `   <td style="font-weight: 900"; class="${
            approver.approver_percentage == 1 ? "text-success" : "text-danger"
          }">${approver.approver_percentage == 1 ? "Yes" : "No"}</td>`;
                    rows += `</tr>`;
                });
                //
                $("#employeeApproverModalTable").html(rows);
                //
                ml(false, "employeeApproverModalLoader");
            }
        );
    });

    //
    function fetchEmployeeApprovers(employeeId) {
        return new Promise((res) => {
            $.post(
                handlerURL, {
                    action: "get_employee_approvers",
                    companyId: companyId,
                    employerId: employerId,
                    employeeId: employeeId,
                    public: 0,
                },
                (resp) => {
                    res(resp);
                }
            );
        });
    }

    //
    function loadMoreBalance() {
        //
        callOBJ.Balances.Main.inset += 10;
        $.post(handlerURL, callOBJ.Balances.Main, function(resp) {
            //
            if (resp.Data.Balances.length != 0) loadMoreBalance();
            //
            setTable(resp, "append");
        });
    }
});