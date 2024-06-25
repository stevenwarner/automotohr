let cmnOBJ = {
	Employees: {
		Main: {
			action: "get_company_employees",
			companyId: companyId,
			employerId: employerId,
			employeeId: employeeId,
			public: 0,
		},
	},
	Policies: {
		Main: {
			action: "get_policy_list_by_company",
			companyId: companyId,
			employerId: employerId,
			employeeId: employeeId,
			public: 0,
		},
	},
};

//
fetchEmployees();
fetchPolicies();
fetchHolidays();

// Employees
function fetchEmployees() {
	$.post(handlerURL, cmnOBJ.Employees.Main, function (resp) {
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
			window.timeoff.employees = [];
			console.log("Failed to load employees.");
			return;
		}
		//
		window.timeoff.employees = resp.Data;
		//
		let rows = "";
		//
		rows += '<option value="all">All</option>';
		//
		window.timeoff.employees.map(function (v) {
			let status = "";
			if (v.terminated_status === "1") {
				status = " - Terminated";
			} else if (v.active === "0") {
				status = " - Deactivated";
			}
			rows +=
				'<option value="' +
				v.user_id +
				'">' +
				remakeEmployeeName(v) +
				status +
				"</option>";
		});
		//
		$("#js-employee-add").html(rows);
		$("#js-employee-add").select2();
		$("#js-employee-add").select2MultiCheckboxes({
			templateSelection: function (selected, total) {
				total--;
				return (
					"Selected " +
					($.inArray("all", $("#js-employee-add").val()) !== -1
						? total
						: selected.length) +
					" of " +
					total
				);
			},
		});
		//
		$("#js-employee-edit").html(rows);
		$("#js-employee-reset").html(rows);
		$("#js-filter-employee").html(rows);
		$("#js-filter-employee").select2();
	});
}

// Polciies
function fetchPolicies() {
	$.post(handlerURL, cmnOBJ.Policies.Main, function (resp) {
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
			console.log("Failed to load policies.");
			return;
		}
		//
		let rows = "";
		//
		rows += '<option value="all">All</option>';
		//
		resp.Data.map(function (v) {
			rows += `<option value="${v.policy_id}">${v.policy_title}</option>`;
		});
		//
		$("#js-filter-policies").html(rows);
		$("#js-filter-policies").select2();
	});
}

function fetchHolidays() {
	$.post(
		handlerURL,
		{
			action: "get_company_all_holidays",
			companyId: companyId,
			employerId: employerId,
			employeeId: employeeId,
		},
		function (resp) {
			if (resp.Status === false) {
				console.log(resp.Response);
				return;
			}
			window.timeoff.holidays = resp.Data;
		}
	);
}

//
function getUserById(userId, users) {
	//
	let r = [];
	//
	$.each(users, (index, user) => {
		if (user.user_id == userId) r = user;
	});
	//
	return r;
}

function getHolidayText(v) {
	var b = moment(v.from_date, "YYYY-MM-DD"),
		a = moment(v.to_date, "YYYY-MM-DD"),
		c = a.diff(b, "days");
	//
	if (c >= 2) {
		return (
			moment(v.from_date, "YYYY-MM-DD").format("MMMM, D") +
			" - " +
			moment(v.to_date, "YYYY-MM-DD").format("MMMM, D")
		);
	}
	return moment(v.from_date, "YYYY-MM-DD").format("MMMM, D");
}
//
function getApproverLisiting(history) {
	if (history.length == 0) return "";
	//
	let rows = "";
	let arr = [];
	//
	history.map((his) => {
		if (his.action == "create") return "";
		if (his.note == "{}") return "";
		//
		let action = JSON.parse(his.note);
		//
		if (action.canApprove == undefined) return "";
		//
		let msg = `${remakeEmployeeName(his)}`,
			il = "";
		//
		if (action.status == "pending") return "";
		if (action.status == "approved") {
			msg += ` has approved the time-off at ${moment(
				his.created_at
			).format(timeoffDateFormatWithTime)}`;
			il = '<i class="fa fa-check-circle text-success"></i>';
		} else if (action.status == "rejected") {
			msg += ` has rejected the time-off at ${moment(
				his.created_at
			).format(timeoffDateFormatWithTime)}`;
			il = '<i class="fa fa-times-circle text-danger"></i>';
		}
		//
		if ($.inArray(his.userId, arr) !== -1) return "";
		arr.push(his.userId);
		//
		rows += `
            <div class="csApproverBox" title="Approver" data-content="${msg}">
                <img src="${
					his.image == null || his.image == ""
						? awsURL + "test_file_01.png"
						: awsURL + his.image
				}" style="width: 40px; height: 40px;" />
                ${il}
            </div>
        `;
	});
	//
	return rows;
}

function getApproverComments(history) {
	if (history.length == 0) return "";
	//
	let rows = "";
	//
	history.map((his) => {
		if (his.action == "create") return "";
		if (his.note == "{}") return "";
		//
		let action = JSON.parse(his.note);
		//
		if (action.canApprove == undefined) return "";
		//
		let msg = "";
		//
		if (action.status == "pending") return "";
		msg += '        <div class="employee-info" style="padding: 7px">';
		msg += "            <figure>";
		msg += `                <img src="${getImageURL(
			his.image
		)}" class="img-circle emp-image" />`;
		msg += "            </figure>";
		msg += '            <div class="text">';
		msg += `            <p>
                                <span>${action.comment}</span><br />
                                <span style="font-size: 11px;">${moment(
									his.created_at
								).format(
									timeoffDateFormatWithTime
								)}</span></p>`;
		msg += "            </div>";
		msg += "        </div>";
		msg += '      </div><div class="csSeprator"></div>';
		//
		rows += msg;
	});
	//
	return rows;
}
