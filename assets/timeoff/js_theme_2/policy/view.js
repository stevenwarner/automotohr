// TODO
// Timeline of edit and create policy
$(function () {
	//
	let callOBJ = {
		CompanyPolicies: {
			Main: {
				action: "get_policies_by_company",
				companyId: companyId,
				employerId: employerId,
				employeeId: employeeId,
				filter: {
					archived: 0,
					policy: "",
					startDate: "",
					endDate: "",
					status: "",
				},
				public: 0,
				page: 1,
			},
			cb: fetchCompanyPolicies,
			limit: 0,
			count: 0,
			pages: 0,
		},
		PolicySort: {
			Main: {
				action: "update_sort_order",
				companyId: companyId,
				employerId: employerId,
				employeeId: employeeId,
				public: 0,
			},
		},
		PolicyHistory: {
			Main: {
				action: "get_policy_history",
				companyId: companyId,
				employerId: employerId,
				employeeId: employeeId,
				public: 0,
			},
		},
		ManagePolicy: {
			Main: {
				action: "get_policy_requests_with_employees",
				companyId: companyId,
				employerId: employerId,
				employeeId: employeeId,
				public: 0,
			},
		},
		MigratePolicy: {
			Main: {
				action: "migrate_employee_requests_policy",
				companyId: companyId,
				employerId: employerId,
				employeeId: employeeId,
				public: 0,
			},
		},
		AllowedEmployees: {
			Main: {
				action: "get_allowed_employees",
				companyId: companyId,
				employerId: employerId,
				employeeId: employeeId,
				public: 0,
			},
		},
	},
		oldState = {},
		xhr = null,
		PolicyID = 0;

	//
	window.timeoff.PaginationOBJ.CompanyPolicies = callOBJ.CompanyPolicies;
	window.timeoff.fetchCompanyPolicies = fetchCompanyPolicies;

	//
	if (page == "" || page == "view") fetchCompanyPolicies();

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

	//
	$(".js-tab").click(function () {
		callOBJ.CompanyPolicies.Main.filter.archived =
			$(this).data("type") === "archived" ? 1 : 0;
		fetchCompanyPolicies();
	});

	// Trigger for policy history
	$(document).on("click", ".jsPolicyHistory", function () {
		Modal(
			{
				Id: 1,
				Title: `Policy History for ${$(this)
					.closest(".jsBox")
					.data("name")}`,
				Body: getHistoryTemplate(),
				Loader: "jsPolicyHistoryLoader",
			},
			() => {
				// Fetch history
				fetchPolicyHistory($(this).closest(".jsBox").data("id"));
			}
		);
	});
	//
	$(document).on("click", ".jsManagePolicy", function () {
		Modal(
			{
				Id: "jsManagePolicy",
				Title: `Policy History for ${$(this)
					.closest(".jsBox")
					.data("name")}`,
				Body: getManagePolicyTemplate(),
				Loader: "jsManagePolicyLoader",
			},
			() => {
				// Fetch Employee
				fetchPolicyRequests($(this).closest(".jsBox").data("id"));
			}
		);
	});
	//
	$(document).on("click", ".jsAllowedPolicyEmployees", function () {
		Modal(
			{
				Id: "jsAllowedPolicyEmployees",
				Title: `Employees entitled for ${$(this)
					.closest(".jsBox")
					.data("name")}`,
				Body: getAllowedPolicyEmployee(),
				Loader: "jsAllowedPolicyEmployeesLoader",
			},
			() => {
				// Fetch Employee
				fetchAllowedEmployees($(this).closest(".jsBox").data("id"));
			}
		);
	});
	// Filter buttons
	$(document).on("click", ".js-apply-filter-btn", applyFilter);
	$(document).on("click", ".js-reset-filter-btn", resetFilter);

	//
	$(".csLisitingArea").on("sortstop", callSort);

	//
	$(document).on("click", ".js-archive-btn", function (e) {
		//
		e.preventDefault();
		//
		var _this = $(this);
		//
		alertify
			.confirm(
				"Do you really want to deactivate this policy?",
				function () {
					//
					var post = {};
					post.action = "archive_company_policy";
					post.companyId = companyId;
					post.employeeId = employeeId;
					post.employerId = employerId;
					post.public = 0;
					post.policySid = _this.closest(".jsBox").data("id");
					//
					ml(true, "policy");
					//
					$.post(handlerURL, post, function (resp) {
						//
						ml(false, "policy");
						//
						if (resp.Redirect === true) {
							//
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
						if (resp.Status === false) {
							alertify.alert(
								"WARNING!",
								resp.Response,
								function () {
									return;
								}
							);
							return;
						}
						//
						alertify.alert("SUCCESS!", resp.Response, function () {
							loadViewPage();
						});
						return;
					});
				}
			)
			.set("labels", {
				ok: "YES",
				cancel: "NO",
			})
			.setHeader("CONFIRM!");
	});

	//
	$(document).on("click", ".js-activate-btn", function (e) {
		//
		e.preventDefault();
		//
		var _this = $(this);
		//
		alertify
			.confirm(
				"Do you really want to activate this policy?",
				function () {
					var post = {};
					post.action = "activate_company_policy";
					post.companyId = companyId;
					post.employeeId = employeeId;
					post.employerId = employerId;
					post.public = 0;
					post.policySid = _this.closest(".jsBox").data("id");
					//
					ml(true, "policy");
					//
					$.post(handlerURL, post, function (resp) {
						//
						ml(false, "policy");
						//
						if (resp.Redirect === true) {
							//
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
						if (resp.Status === false) {
							alertify.alert(
								"WARNING!",
								resp.Response,
								function () {
									return;
								}
							);
							return;
						}
						//
						alertify.alert("SUCCESS!", resp.Response, function () {
							loadViewPage();
						});
					});
				}
			)
			.set("labels", {
				ok: "YES",
				cancel: "NO",
			})
			.setHeader("CONFIRM!");
	});

	//
	$(document).on("change", "#jsAssigPolicyToAll", function () {
		//
		let newPolicy = $(this).val();
		//
		$(".jsAssignNewPolicy").val(newPolicy).change();
	});

	//
	$(document).on("click", ".jsTransferPolicyBTN", function () {
		//
		var employeesList = [];
		//
		$(".jsAssignNewPolicy").each(function (index, select) {
			var newPolicy = $(this).val();
			//
			if (newPolicy != PolicyID) {
				employeesList.push({
					employeeId: $(this).closest(".jsEmployeeRow").data("sid"),
					oldPolicyId: PolicyID,
					newPolicyId: newPolicy,
				});
			}
		});
		//
		if (employeesList.length) {
			migrateRequestPolicy(employeesList);
		}
	});

	//
	function resetFilter(e) {
		//
		e.preventDefault();
		//
		$("#js-filter-policies").select2("val", "all");
		$("#js-filter-from-date").val("");
		$("#js-filter-to-date").val("");
		$('#js-filter-status option[value="-1"]').prop("selected", true);
		//
		callOBJ.CompanyPolicies.Main.filter.policy = "all";
		callOBJ.CompanyPolicies.Main.filter.startDate = "";
		callOBJ.CompanyPolicies.Main.filter.endDate = "";
		callOBJ.CompanyPolicies.Main.filter.status = "-1";
		callOBJ.CompanyPolicies.Main.filter.archived = 0;
		callOBJ.CompanyPolicies.Main.page = 1;
		callOBJ.CompanyPolicies.Main.limit = 0;
		callOBJ.CompanyPolicies.Main.count = 0;
		//
		fetchCompanyPolicies();
	}

	//
	function applyFilter(e) {
		//
		e.preventDefault();
		//
		callOBJ.CompanyPolicies.Main.filter.policy = $(
			"#js-filter-policies"
		).val();
		callOBJ.CompanyPolicies.Main.filter.startDate = $(
			"#js-filter-from-date"
		).val();
		callOBJ.CompanyPolicies.Main.filter.endDate =
			$("#js-filter-to-date").val();
		callOBJ.CompanyPolicies.Main.filter.status =
			$("#js-filter-status").val();
		callOBJ.CompanyPolicies.Main.page = 1;
		callOBJ.CompanyPolicies.Main.limit = 0;
		callOBJ.CompanyPolicies.Main.count = 0;
		//
		fetchCompanyPolicies();
	}

	// Fetch plans
	function fetchCompanyPolicies() {
		//
		if (window.timeoff.categories === undefined) {
			setTimeout(() => {
				fetchCompanyPolicies();
			}, 1000);
			return;
		}
		//
		if (xhr != null) return;
		//
		ml(true, "policy");
		//
		xhr = $.post(handlerURL, callOBJ.CompanyPolicies.Main, function (resp) {
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
				resp.Status === false &&
				callOBJ.CompanyPolicies.Main.page == 1
			) {
				$(".js-ip-pagination").html("");
				$(".csLisitingArea").html(
					`<p class="alert alert-info text-center">${resp.Response}</p>`
				);
				//
				ml(false, "policy");
				//
				return;
			}
			//
			if (resp.Status === false) {
				//
				$(".js-ip-pagination").html("");
				//
				ml(false, "policy");
				//
				return;
			}
			//
			if (callOBJ.CompanyPolicies.Main.page == 1) {
				callOBJ.CompanyPolicies.limit = resp.Limit;
				callOBJ.CompanyPolicies.count = resp.TotalRecords;
				callOBJ.CompanyPolicies.pages = resp.TotalPages;
			}
			//
			setTable(resp);
		});
	}

	//
	function setTable(resp) {
		//
		oldState = {};
		//
		let rows = "";
		//
		if (resp.Data.length == 0) return;
		//
		$.each(resp.Data, function (i, v) {
			oldState[v.policy_id] = i;
			rows += getPolicyBox(v);
		});

		//
		load_pagination(
			callOBJ.CompanyPolicies.limit,
			5,
			$(".js-ip-pagination"),
			"CompanyPolicies"
		);
		//
		$(".csLisitingArea").html(rows);

		//
		// $('.js-type-popover').popover({
		//     html: true,
		//     trigger: 'hover'
		// });
		// //
		callDrager(".csLisitingArea");
		// //
		$(".jsTooltip").tooltip({ placement: "top" });
		//
		ml(false, "policy");
	}

	// D&D
	function callDrager(target) {
		$(target).sortable({
			placeholder: "ui-state-highlight",
		});
		$(target).disableSelection();
	}

	//
	function callSort() {
		var i = 0,
			s = {},
			l = $(".csLisitingArea").find(".jsBox").length;
		for (i; i < l; i++) {
			s[
				$(".csLisitingArea")
					.find(".jsBox:eq(" + i + ")")
					.data("id")
			] = i;
		}
		updateSortInDb(s);
	}

	//
	function updateSortInDb(s) {
		//
		let o = Object.assign({}, callOBJ.PolicySort.Main, {
			sort: s,
			type: "policies",
		});
		//
		$.post(handlerURL, o, (resp) => {
			ml(false, "policy");
		});
	}

	//
	function getHistoryTemplate() {
		let html = `
            <div class="tabel-responsive">
                <table class="table table-striped csCustomTableHeader">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Action</th>
                            <th>Action Type</th>
                            <th>Action Taken</th>
                        </tr>
                    </thead>
                    <tbody id="jsAllowedPolicyEmployeeTable"></tbody>
                </table>
            </div>
        `;

		return html;
	}

	//
	function getManagePolicyTemplate() {
		let html = `
            <div id="jsManagePolicyTable">    
            </div>
        `;
		return html;
	}

	//
	function getAllowedPolicyEmployee() {
		let html = `
            <div id="jsAllowedPolicyEmployeeTable">    
            </div>
        `;
		return html;
	}

	//
	function fetchPolicyHistory(policyId) {
		//
		ml(true, "jsPolicyHistoryLoader");
		//
		$("#jsPolicyHistoryTable").html("");
		//
		xhr = $.post(
			handlerURL,
			Object.assign(callOBJ.PolicyHistory.Main, {
				policyId: policyId,
			}),
			(resp) => {
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
				if (resp.Status === false) {
					alertify.alert("WARNING!", resp.Response, () => { });
					//
					ml(false, "jsPolicyHistoryLoader");
					//
					return;
				}
				//
				let rows = "";
				if (resp.Data.length === 0) {
					rows = `
                    <tr>
                        <td colspan="4">
                            <p class="alert alert-info text-center">${resp.Response}</p>
                        </td>
                    </tr>
                `;
				} else {
					resp.Data.map((v) => {
						rows += `
                        <tr>
                            <td>${remakeEmployeeName(v)}</td>
                            <td>${v.action.toUpperCase()}</td>
                            <td>${v.action_type.toUpperCase()}</td>
                            <td>${moment(v.created_at).format(
							timeoffDateFormatWithTime
						)}</td>
                        </tr>
                    `;
					});
				}

				//
				$("#jsPolicyHistoryTable").html(rows);
				//
				ml(false, "jsPolicyHistoryLoader");
			}
		);
	}

	function fetchPolicyRequests(policyId) {
		//
		PolicyID = policyId;
		//
		ml(true, "jsManagePolicyLoader");
		//
		$("#jsPolicyHistoryTable").html("");
		//

    	xhr = $.post(
			handlerURL,
			Object.assign(callOBJ.ManagePolicy.Main, {
				policyId: policyId,
			}),
			(resp) => {
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
				if (resp.Status === false) {
					alertify.alert("WARNING!", resp.Response, () => {

						$("#jsManagePolicy .jsModalCancel").trigger("click");
					});
					//
					ml(false, "jsManagePolicyLoader");
					//

					return;
				}

				//
				$("#jsManagePolicyTable").html(resp.View);
				//
				$("#jsAssigPolicyToAll").select2({
					closeOnSelect: true,
				});
				//
				$("#jsAssigPolicyToAll").select2("val", policyId);
				//
				ml(false, "jsManagePolicyLoader");
			}
		);
	}

	function migrateRequestPolicy(data) {
		//
		ml(true, "jsManagePolicyLoader");
		//
		xhr = $.post(
			handlerURL,
			Object.assign(callOBJ.MigratePolicy.Main, {
				Data: data,
			}),
			(resp) => {
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
				if (resp.Status === false) {
					alertify.alert("WARNING!", resp.Response, () => { });
					//
					ml(false, "jsManagePolicyLoader");
					//
					return;
				}
				//
				ml(false, "jsManagePolicyLoader");
				return alertify.alert(
					"Success",
					"The time off requests of the selected employees have been successfully transferred.",
					function () {
						$("#jsManagePolicy .jsModalCancel").trigger("click");
					}
				);
			}
		);
	}
	//
	function getPolicyBox(v) {
		let title =
			callOBJ.CompanyPolicies.Main.filter.archived != 0
				? "Activate Policy"
				: "Deactivate Policy",
			icon =
				callOBJ.CompanyPolicies.Main.filter.archived != 0
					? "fa-check-square-o"
					: "fa-archive",
			cl =
				callOBJ.CompanyPolicies.Main.filter.archived != 0
					? "js-activate-btn"
					: "js-archive-btn";
		//
		let accruals = JSON.parse(v.accruals);
		//
		let rows = "";
		//
		rows += `<div class="col-sm-4">`;
		rows += `    <div class="csBox csShadow csRadius5 jsBox" data-id="${v.policy_id}" data-name="${v.policy_title}">`;
		rows += `        <!-- Box Header -->`;
		rows += `        <div class="csBoxHeader csRadius5 csRadiusBL0 csRadiusBR0">`;

		rows += `            <span class="pull-right">`;
		rows += `                <span class="csCircleBtn csRadius50 jsTooltip js-clone-row-btn" title="Clone Policy" placement="top"><i class="fa fa-clone"></i></span>`;
		rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsAllowedPolicyEmployees" title="Policy applicable employee(s)" placement="top"><i class="fa fa-users"></i></span>`;
		rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsManagePolicy" title="Manage Policy" placement="top"><i class="fa fa-cog"></i></span>`;
		rows += `                <span class="csCircleBtn csRadius50 jsTooltip jsPolicyHistoryBtn" title="History" placement="top"><i class="fa fa-history"></i></span>`;
		rows += `                <span class="csCircleBtn csRadius50 jsTooltip js-edit-row-btn" title="Edit" placement="top"><i class="fa fa-pencil"></i></span>`;
		rows += `            </span>`;
		rows += `            <div class="clearfix"></div>`;
		rows += `        </div>`;
		rows += `        <!-- Box Content -->`;
		rows += `        <div class="csBoxContent">`;
		rows += `            <!-- Section 1 -->`;
		rows += `            <div class="csBoxContentDateSection pt10 pb10">`;
		rows += `                <div class="col-sm-12">`;
		rows += `                    <h3>${ucwords(
			v.policy_title
		)} (<span class="text-${v.category_type == 1 ? "" : "danger"}">${v.category_type == 1 ? "Paid" : "Unpaid"
			}</span>)</h3>`;
		rows += `                    <p>(${getTypeNames(v.type_sid)})</p>`;
		rows += `                </div>`;
		rows += `                <div class="clearfix"></div>`;
		rows += `            </div>`;
		rows += `            <!-- Section 3 -->`;
		rows += `            <div class="csBoxBalanceSection">`;
		rows += `                <div class="col-sm-12">`;
		rows += `                    <p><strong>${accruals.applicableDate === null ||
				accruals.applicableDate == "" ||
				accruals.applicableDate == 0
				? "Joining Date"
				: moment(accruals.applicableDate, "").format(timeoffDateFormat)
			}</strong></p>`;
		rows += `                    <p>Applicable Date</p>`;
		rows += `                </div>`;
		rows += `                <div class="clearfix"></div>`;
		rows += `            </div>`;
		rows += `            <!-- Section 3 -->`;
		rows += `            <div class="csBoxBalanceSection">`;
		rows += `                <div class="col-sm-12">`;
		rows += `                    <p><strong>${accruals.carryOverCheck == "yes" ? "Yes" : "No"
			}</strong></p>`;
		rows += `                    <p>Carryover</p>`;
		rows += `                </div>`;
		rows += `                <div class="clearfix"></div>`;
		rows += `            </div>`;
		rows += `            <!-- Section 3 -->`;
		rows += `            <div class="csBoxBalanceSection">`;
		rows += `                <div class="col-sm-12">`;
		rows += `                    <p><strong>${getAccrualText(
			accruals
		)}</strong></p>`;
		rows += `                    <p>Accruals</p>`;
		rows += `                </div>`;
		rows += `                <div class="clearfix"></div>`;
		rows += `            </div>`;
		rows += `            <!-- Section 3 -->`;
		rows += `            <div class="csBoxBalanceSection">`;
		rows += `                <div class="col-sm-12">`;
		rows += `                    <p><strong>${getAccrualText(
			accruals,
			true
		)}</strong></p>`;
		rows += `                    <p>New Hire</p>`;
		rows += `                </div>`;
		rows += `                <div class="clearfix"></div>`;
		rows += `            </div>`;
		rows += `        </div>`;
		rows += `            `;
		rows += `        <!-- Box Footer -->`;
		rows += `        <div class="csBoxFooter">`;
		rows += `            <div class="col-sm-12">`;
		if (v.default_policy == 0) {
			rows += `                <button class="btn btn-orange form-control ${cl}"><i class="fa ${icon}"></i>${title}</button>`;
		}
		rows += `            </div>`;
		rows += `        </div>`;
		rows += `    </div>`;
		rows += `</div>`;

		return rows;
	}

	//
	function fetchAllowedEmployees(policyId) {
		//
		ml(true, "jsAllowedPolicyEmployeesLoader");
		//
		$("#jsAllowedPolicyEmployeeTable").html("");
		//
		xhr = $.post(
			handlerURL,
			Object.assign(callOBJ.AllowedEmployees.Main, {
				policyId: policyId,
			}),
			(resp) => {
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
				if (resp.Status === false) {
					alertify.alert("WARNING!", resp.Response, () => { });
					//
					ml(false, "jsAllowedPolicyEmployeesLoader");
					//
					return;
				}

				//
				$("#jsAllowedPolicyEmployeeTable").html(resp.View);
				ml(false, "jsAllowedPolicyEmployeesLoader");
			}
		);
	}

	/**
	 * Capture history click event
	 */
	$(document).on("click", ".jsPolicyHistoryBtn", function (event) {
		// stop the event to occur
		event.preventDefault();
		// set the ids
		let policyId = $(this).closest(".jsBox").data("id");
		let policyTitle = $(this).closest(".jsBox").data("name");
		// open up modal
		Modal(
			{
				Id: "jsPolicyHistoryModal",
				Title: 'History for "' + policyTitle + '"',
				Loader: "jsPolicyHistoryModalLoader",
				Body: "<div id='jsPolicyHistoryModalBody'>" + "</div>",
			},
			getPolicyHistory.bind(this, policyId)
		);
	});
	/**
	 * Get the policy history
	 * @param {int} pid
	 */
	function getPolicyHistory(pid) {
		$.get(window.location.origin + "/timeoff/policy/" + pid + "/history")
			.success(function (resp) {
				$("#jsPolicyHistoryModalBody").html(resp.view);
				ml(false, "jsPolicyHistoryModalLoader");
			})
			.fail(function (resp) {
				//
				let response = JSON.parse(resp.responseText);
				//
				if (resp.status == 401) {
					return alertify.alert(
						"ERROR!",
						response.errors.join("<br />"),
						function () { }
					);
				}
			});
	}
});
