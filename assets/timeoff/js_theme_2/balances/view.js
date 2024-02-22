$(function () {
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
						employees: getParams("id"),
						policies: getParams("pid"),
						type: $(".jsEditResetCheckbox:checked").val(),
                        all : 0,
					},
					public: 0,
					inset: 0,
					offset: 10,
				},
			},
		},
		xhr = null,
		balancePolicyOBJ = {};
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
		onSelect: function (v) {
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
	$(document).on("click", ".jsViewPolicies", function (e) {
		//
		e.preventDefault();
		//
		startPolicyProcess(
			$(this).closest(".jsBox").data("id"),
			$(this).closest(".jsBox").data("name"),
			$(this).closest(".jsBox").data("anniversary")
		);
	});

	//
	$(document).on("click", ".jsViewBalance", function (e) {
		//
		e.preventDefault();
		//
		startBalanceProcess(
			$(this).closest(".jsBox").data("id"),
			$(this).closest(".jsBox").data("name"),
			$(this).closest(".jsBox").data("anniversary")
		);
	});

	//
	$(document).on("click", ".jsViewApprovers", function (e) {
		//
		e.preventDefault();
		//
		Modal(
			{
				Id: "employeeApproverModal",
				Title: `Approvers for ${$(this)
					.closest(".jsBox")
					.data("name")}`,
				Body: "",
				Loader: "employeeApproverModalLoader",
			},
			async () => {
				//
				const approvers = await fetchEmployeeApprovers(
					$(this).closest(".jsBox").data("id")
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
						approver.approver_percentage == 1
							? "text-success"
							: "text-danger"
					}">${
						approver.approver_percentage == 1 ? "Yes" : "No"
					}</td>`;
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
	function resetFilter(e) {
		//
		e.preventDefault();
		//
		$("#js-filter-employee").select2("val", "all");
		// $("#js-filter-policies").select2("val", "all");
		//
		callOBJ.Balances.Main.inset = 0;
		callOBJ.Balances.Main.offset = 10;
		callOBJ.Balances.Main.filter.employees = "all";
		callOBJ.Balances.Main.filter.policies = "all";
        callOBJ.Balances.Main.filter.employeeStatus= 0;
		callOBJ.Balances.Main.filter.type = $(
			".jsEditResetCheckbox:checked"
		).val();
		//
		// fetchBalances();
		window.location = "?id=all&pid=all";
	}

	//
	function applyFilter(e) {
		//
		e.preventDefault();
		//
		callOBJ.Balances.Main.inset = 0;
		callOBJ.Balances.Main.offset = 10;
		callOBJ.Balances.Main.filter.employees = $("#js-filter-employee").val();
		callOBJ.Balances.Main.filter.employeeStatus = $(
			".jsFilterEmployeeStatus"
		).val();
		callOBJ.Balances.Main.filter.policies =
			$("#js-filter-policies").val() == null
				? "all"
				: $("#js-filter-policies").val();
		callOBJ.Balances.Main.filter.type = $(
			".jsEditResetCheckbox:checked"
		).val();
		//
		window.location =
			"?id=" +
			callOBJ.Balances.Main.filter.employees +
			"&employee_status=" +
			callOBJ.Balances.Main.filter.employeeStatus +
			"";
		//
		// fetchBalances();
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
		if ($("#js-filter-employee > option").length) {
			$("#js-filter-employee").select2("val", getParams("id"));
		}
		//
		if ($("#js-filter-policies > option").length) {
			$("#js-filter-policies").select2("val", getParams("pid"));
		}
		// $('#js-filter-employee').select2('val', getParams('id'));
		// $('#js-filter-policies').select2('val', getParams('pid'));
		//
		ml(true, "balance");
		//
		$(".js-error-row").remove();

        callOBJ.Balances.Main.filter.all = getSearchParam("employee_status") || 0;

		//
		xhr = $.post(handlerURL, callOBJ.Balances.Main, function (resp) {
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
			if (
				resp.Data.Balances == undefined ||
				resp.Data.Balances.length == 0
			) {
				console.log("pop");
				ml(false, "balance");
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
		$.each(resp.Data.Balances, function (i, v) {
			//
			if (v.total !== undefined) {
				//
				let userRow = getUserById(
					v.total.UserId,
					resp.Data.Employees,
					"userId"
				);
				//
				if (Object.keys(userRow).length == 0) return;
				//
				rows += getBalanceBox(v, userRow);
				//
				balancePolicyOBJ[v.total.UserId] = { allowed: [], pending: [] };
				//
				$.each(v, (index, poli) => {
					if (index == "total") return "";
					balancePolicyOBJ[v.total.UserId]["allowed"].push({
						policy:
							index +
							' <strong class="text-' +
							(poli.policy_type == 1 ? "success" : "danger") +
							'">(' +
							(poli.policy_type == 1 ? "Paid" : "Unpaid") +
							"</strong>)",
						time: poli.AllowedTime.text,
					});
					balancePolicyOBJ[v.total.UserId]["pending"].push({
						policy:
							index +
							' <strong class="text-' +
							(poli.policy_type == 1 ? "success" : "danger") +
							'">(' +
							(poli.policy_type == 1 ? "Paid" : "Unpaid") +
							"</strong>)",
						time: poli.RemainingTime.text,
					});
				});
			}
		});
		//
		if (typo === undefined) $(".csBalanceBoxInner").html(rows);
		else $(".csBalanceBoxInner").append(rows);
		//
		$(".jsCustomPopover")
			.popover({
				html: true,
				trigger: "hover click",
				placement: "auto right",
				template:
					'<div class="popover"><div class="arrow"></div><div class="popover-content"></div></div>',
			})
			.on("inserted.bs.popover", function (e) {
				//
				let rows = "<ul>";
				//
				balancePolicyOBJ[$(this).closest(".jsBox").data("id")][
					$(this).data("type")
				].map(function (li) {
					rows += `<li>${li.time} of <strong>${li.policy}</strong></li>`;
				});
				//
				rows += "</ul>";
				$(this).next(".popover").find(".popover-content").html(rows);
			});
		//
		$(".jsTooltip").tooltip();
		//
		ml(false, "balance");
	}

	//
	function fetchEmployeeApprovers(employeeId) {
		return new Promise((res) => {
			$.post(
				handlerURL,
				{
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
		$.post(handlerURL, callOBJ.Balances.Main, function (resp) {
			//
			if (resp.Data.Balances.length != 0) loadMoreBalance();
			//
			setTable(resp, "append");
		});
	}

	//
	function getBalanceBox(v, userRow, popo) {
		return `
        <!--  -->
        <div class="col-sm-3">
            <div class="csBox jsBox csShadow csRadius5"  data-id="${
				v.total.UserId
			}" data-name="${userRow.first_name} ${userRow.last_name}" data-anniversary="${userRow.anniversary_text}">
                <!-- Box Header -->
                <div class="csBoxHeader csRadius5 csRadiusBL0 csRadiusBR0">
                    <span class="pull-right">
                        <span target="_blank" class="csCircleBtn csRadius50 jsViewPolicies jsTooltip" title="View Policies" placement="top"><i class="fa fa-eye"></i></span>
                        <span target="_blank" class="csCircleBtn csRadius50 jsViewBalance jsTooltip" title="Manage Balance" placement="top"><i class="fa fa-balance-scale"></i></span>
                        <span target="_blank" class="csCircleBtn csRadius50 jsViewApprovers jsTooltip" title="View Approvers" placement="top"><i class="fa fa-users"></i></span>
                    </span>
                    <div class="clearfix"></div>
                </div>
                <!-- Box Content -->
                <div class="csBoxContent">
                    <!-- Section 1 -->
                    <div class="csBoxContentEmpployeeSection">
                        <a href="${baseURL}employee_profile/${userRow.userId}" target="_blank">
                        <div class="col-sm-3">
                            <img src="${getImageURL(
								userRow.image
							)}" class="csRoundImg" />
                        </div>
                        <div class="col-sm-9 pr0">
                            <p><strong>${
								userRow.first_name
							} ${userRow.last_name}</strong>${userRow.terminated_status === "1" ? ' (<span class="text-danger">Terminated</span>)' : ""}
        ${
			userRow.terminated_status === "0" && userRow.active === "0"
				? ' (<span class="text-danger">Deactivated</span>)'
				: ""
		}
                            <br /> ${remakeEmployeeName(userRow, false)}</p>
                            ${userRow.anniversary_text}
                            </div>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Section 2 -->
                    <div class="csBoxBalanceSection">
                        <div class="col-sm-12">
                            <p><strong>${
								v.total.AllowedTime.text == ""
									? "0 hours"
									: v.total.AllowedTime.text
							}</strong></p>
                            <p>Allowed Time</p>
                            <!--  -->
                            <div class="csFixedToRight jsCustomPopover" data-type="allowed" title="p" placement="right">
                                <i class="fa fa-plus-circle"></i>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Section 3 -->
                    <div class="csBoxBalanceSection">
                        <div class="col-sm-12">
                            <p><strong>${
								v.total.ConsumedTime.text == ""
									? "0 hours"
									: v.total.ConsumedTime.text
							}</strong></p>
                            <p>Consumed Paid Time</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Section 4 -->
                    <div class="csBoxBalanceSection">
                        <div class="col-sm-12">
                            <p><strong>${
								v.total.UnpaidConsumedTime.text == ""
									? "0 hours"
									: v.total.UnpaidConsumedTime.text
							}</strong></p>
                            <p>Consume Unpaid Time</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Section 5 -->
                    <div class="csBoxBalanceSection">
                        <div class="col-sm-12">
                            <p><strong>${
								v.total.RemainingTime.text == ""
									? "0 hours"
									: v.total.RemainingTime.text
							}</strong></p>
                            <p>Remaining Time</p>
                            <!--  -->
                            <div class="csFixedToRight jsCustomPopover" data-type="pending" title="p">
                                <i class="fa fa-plus-circle"></i>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <!-- Box Footer -->
                <div class="csBoxFooter">
                    <div class="col-sm-12 pl0 pr0">
                        <button class="btn btn-orange btn-lg form-control jsCreateRequest"><i class="fa fa-plus-circle"></i> Create time-off</button>
                    </div>
                </div>
            </div>
        </div>
        `;
	}

	$(".jsFilterEmployeeStatus").select2({
		minimumResultsForSearch: -1,
	});
});
