let cmnOBJ = {
	Employees: {
		Main: {
			action: "get_company_employees",
			companyId: companyId,
			employerId: employerId,
			employeeId: employeeId,
			public: 0,
			all: 0,
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
// fetchPolicies();

//
$(document).on("click", ".jsExpandBalance", function (e) {
	e.preventDefault();
	//
	if ($(this).find("i").hasClass("fa-plus-circle"))
		$(this)
			.find("i")
			.removeClass("fa-plus-circle")
			.addClass("fa-minus-circle");
	else
		$(this)
			.find("i")
			.removeClass("fa-minus-circle")
			.addClass("fa-plus-circle");
	$(`#${$(this).data("target")}`).toggle();
});

//
$(".jsImportBalance").click((e) => {
	//
	e.preventDefault();
	//
	Modal(
		{
			Id: "importBalance",
			Title: "Import consumed balance for previous years",
			Body: getImportHTML(),
			Loader: "importBalanceLoader",
			Buttons: [
				'<button class="btn btn-success dn jsStartImport">Start Import</button>',
			],
		},
		() => {
			//
			$("#jsImportFile").mFileUploader({
				allowedTypes: ["csv"],
				limit: -1,
			});
			//
			$(".jsStartImport").removeClass("dn");
			//
			ml(false, "importBalanceLoader");
		}
	);
});

//
$(document).on("click", ".jsStartImport", function (e) {
	//
	e.preventDefault();
	//
	let fileOBJ = $("#jsImportFile").mFileUploader("get");
	//
	if (fileOBJ.name === undefined) {
		alertify.alert(
			"WARNING!",
			"Please, select a file to import.",
			() => {}
		);
		return;
	}
	//
	if (fileOBJ.hasError === true) {
		alertify.alert(
			"WARNING!",
			"Please, use a correct file format to import.",
			() => {}
		);
		return;
	}
	// Read file
	getFileContent(fileOBJ);
});

// Employees
function fetchEmployees() {
    if (typeof getSearchParam === "undefined") {
        return setTimeout(function(){
            fetchEmployees();
        }, 1000)
    }
		cmnOBJ.Employees.Main.all = getSearchParam("employee_status") || 0;

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
			$('.jsIPLoader[data-page="balance"]').hide(0);
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
			console.log("Failed to load employees.");
			$('.jsIPLoader[data-page="balance"]').hide(0);
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

//
function getImportHTML() {
	return `
        <div class="row">
            <div class="col-sm-12">
                <div style="background-color: #eee; padding: 10px;">
                    <h4><strong>The CSV file should be in the below format</strong></h4>
                    <h5><strong>Employee Email, Consumed Hours, Policy</strong></h5>
                    <p>john.doe@automotohr.com, 20, Vacation</p>
                    <p>john.smith@automotohr.com, 80, PTO</p>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-sm-12">
                <label>Select a file <span class="cs-required">*</span></label>
                <input type="file" id="jsImportFile" class="hidden" />
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-success dn jsStartImport">Start Import</button>
            </div>
        </div>
    `;
}

//
function getFileContent(fileOBJ) {
	const reader = new FileReader();
	reader.onload = startImportProcess;
	reader.readAsText(fileOBJ);
}

//
function startImportProcess(file) {
	//
	let indexes = file.target.result.split(/\n/g)[0].split(",");
	let newIndexes = [];
	let is_error = false;
	//
	indexes.map((index) => {
		//
		if (is_error) return;
		//
		index = getIndex(index.trim());
		//
		if (index === false) {
			is_error = true;
			alertify.alert(
				"ERROR!",
				"The file content doesn't match with the content sample format.",
				() => {}
			);
			return;
		}
		newIndexes.push(index);
	});
	//
	if (is_error == true) return;
	//
	let pushArray = [];
	//
	let values = file.target.result.split(/\n/g);
	//
	values.map((v, i) => {
		//
		if (i == 0) return;
		//
		let newVal = v.split(",");
		//
		if (newVal[0] == "" || newVal[1] == "" || newVal[2] == "") return;
		//
		let t = {};
		t[newIndexes[0]] = newVal[0];
		t[newIndexes[1]] = newVal[1];
		t[newIndexes[2]] = newVal[2];
		//
		pushArray.push(t);
	});
	//
	if (pushArray.length === 0) {
		alertify.alert("WARNING!", "No data found to import.", () => {});
		return;
	}
	//
	ml(true, "importBalanceLoader");
	//
	$.post(
		handlerURL,
		{
			action: "import_balance",
			companyId: companyId,
			employerId: employerId,
			employeeId: employeeId,
			data: pushArray,
		},
		(resp) => {
			alertify.alert(
				"SUCCESS",
				"Balance is successfully imported",
				() => {
					window.location.reload();
				}
			);
		}
	);
}

//
function getIndex(index) {
	//
	index = index.replace(/[^a-zA-Z]/gi, "").toLowerCase();
	if ($.inArray(index, ["employeeemail", "email", "emailladdress"]) !== -1)
		return "email";
	if ($.inArray(index, ["hours", "consumed", "consumedhours"]) !== -1)
		return "hours";
	if ($.inArray(index, ["policy", "title", "policytitle"]) !== -1)
		return "policy";
	//
	return false;
}
