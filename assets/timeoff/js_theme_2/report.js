$(function () {
	//
	let selectedEmployees = [];
	//
	$("#jsIndividualEmployees").select2({ closeOnSelect: false });
	$("#jsDepartments").select2({ closeOnSelect: false });
	$("#jsTeams").select2({ closeOnSelect: false });
	$("#jsJobTitles").select2({ closeOnSelect: false });
	$("#jsEmploymentTypes").select2({ closeOnSelect: false });
	$("#jsNewHires").select2({ closeOnSelect: false });
	$(".jsFilterEmployeeStatus").select2({
		minimumResultsForSearch: -1,
	});
	//
	$("#jsReportStartDate").datepicker({
		format: "m/d/y",
		changeMonth: true,
		changeYear: true,
		onSelect: function (e) {
			$("#jsReportEndDate").datepicker("option", "minDate", e);
		},
	});
	//
	$("#jsReportEndDate").datepicker({
		format: "m/d/y",
		changeMonth: true,
		changeYear: true,
	});
	//
	$(
		"#jsIndividualEmployees, #jsDepartments, #jsTeams, #jsJobTitles, #jsEmploymentTypes, #jsNewHires"
	).change(setReportView);
	//
	$("#jsReportClearFilter").click(function (event) {
		//
		event.preventDefault();
		//
		$("#jsIndividualEmployees").select2("val", null);
		$("#jsDepartments").select2("val", null);
		$("#jsTeams").select2("val", null);
		$("#jsJobTitles").select2("val", null);
		$("#jsEmploymentTypes").select2("val", null);
		$("#jsNewHires").select2("val", 0);
		//
		$(".jsReportEmployeeRow").show();
	});
	//
	$(".jsReportLink").click(function (event) {
		//
		event.preventDefault();
		//
		let startDate = $("#jsReportStartDate").val() || "all",
			endDate = $("#jsReportEndDate").val() || "all";

		isChecked = $("#includeStartandEndDate").is(":checked");
		if (isChecked == false) {
			startDate = "";
			endDate = "";
		}

		//
		window.open(
			$(this).prop("href") +
				"?start=" +
				startDate +
				"&end=" +
				endDate +
				""
		);
	});
	//
	$(".jsReportLinkBulk").click(function (event) {
		//
		event.preventDefault();
		//

		let startDate = $("#jsReportStartDate").val() || "all",
			endDate = $("#jsReportEndDate").val() || "all";
		sToken = $("#session_key").val();
		//
		isChecked = $("#includeStartandEndDate").is(":checked");
		if (isChecked == false) {
			startDate = "";
			endDate = "";
		}

		//
		window.open(
			$(this).prop("href") +
				"/" +
				(selectedEmployees.length == 0 ||
				selectedEmployees.length == employeeList.length
					? "all"
					: selectedEmployees.join(",")) +
				"?start=" +
				startDate +
				"&end=" +
				endDate +
				"&token=" +
				sToken
		);
	});
	//
	function setReportView() {
		//
		ml(true, "report");
		//
		selectedEmployees = [];
		//
		let individualEmployees = $("#jsIndividualEmployees").val() || [],
			departments = $("#jsDepartments").val() || [],
			teams = $("#jsTeams").val() || [],
			jobs = $("#jsJobTitles").val() || [],
			types = $("#jsEmploymentTypes").val() || [],
			newHires = $("#jsNewHires").val() || [];
		//
		let finalEmployeeList = [];
		//
		finalEmployeeList = _.concat(finalEmployeeList, individualEmployees);
		//
		employeeList.map(function (emp) {
			// Find employee with matching departments
			if (_.intersection(emp.DepartmentIds, departments).length > 0) {
				finalEmployeeList.push(emp.sid);
			}
			// Find employee with matching teams
			if (_.intersection(emp.TeamIds, teams).length > 0) {
				finalEmployeeList.push(emp.sid);
			}
			// Find employee with matching jobs
			let job =
				emp["job_title"] != "" && emp["job_title"] != null
					? emp["job_title"]
							.replace(/[^a-zA-Z]/g, "")
							.trim()
							.toLowerCase()
					: "";
			//
			if ($.inArray(job, jobs) !== -1) {
				finalEmployeeList.push(emp.sid);
			}
			// Find employee with matching types
			if ($.inArray(emp.employee_type, types) !== -1) {
				finalEmployeeList.push(emp.sid);
			}
			// Find employee with new hire
			let joinedAt;
			//
			if (emp["joined_at"] != "" && emp["joined_at"] != null) {
				joinedAt = moment(emp["joined_at"], "YYYY-MM-DD");
			} else if (
				emp["registration_date"] != "" &&
				emp["registration_date"] != null
			) {
				joinedAt = moment(emp["joined_at"], "YYYY-MM-DD H:m:s");
			}
			//
			if (
				(newHires == 30 && moment().diff(joinedAt, "days") <= 30) ||
				(newHires == 60 && moment().diff(joinedAt, "days") <= 60) ||
				(newHires == 90 && moment().diff(joinedAt, "days") <= 90)
			) {
				finalEmployeeList.push(emp.sid);
			}
		});
		//
		finalEmployeeList = _.uniq(finalEmployeeList);
		//
		if (finalEmployeeList.length > 0) {
			//
			selectedEmployees = finalEmployeeList;
			//
			$(".jsReportEmployeeRow").hide();
			//
			finalEmployeeList.map(function (id) {
				$('.jsReportEmployeeRow[data-id="' + id + '"]').show();
			});
		} else {
			$(".jsReportEmployeeRow").show();
		}
		//
		ml(false, "report");
	}
});
