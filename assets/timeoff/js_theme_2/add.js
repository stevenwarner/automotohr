$(function () {
	let loggedInId = employeeId,
		loggedInName = employeeName,
		selectedEmployeeId = employeeId,
		selectedEmployeeName = employeeName,
		selectedPolicy = 0,
		currentLoader = "",
		cOBJ = {
			policyId: 0,
			startDate: 0,
			endDate: 0,
			dateRows: "",
			status: 0,
			reason: 0,
			comment: 0,
			sendEmailNotification: 0,
			fromAdmin: 1,
		};
	//
	$(document).on("click", ".jsCreateTimeOffBTN", function (e) {
		//
		e.preventDefault();
		//
		let policy = getSelectedPolicy(getField("#jsAddPolicy"));
		//
		if (policy.length == 0) {
			//
			alertify.alert(
				"WARNING!",
				"You don't have any policies. Please select a different date.",
				() => { }
			);
			//
			return;
		}
		//



		cOBJ.policyId = getField("#jsAddPolicy");
		cOBJ.startDate = getField("#jsStartDate");
		cOBJ.endDate = getField("#jsEndDate");
		cOBJ.status = getField("#jsStatus");
		cOBJ.reason = CKEDITOR.instances["jsReason"].getData();
		cOBJ.comment = CKEDITOR.instances["jsComment"].getData();
		cOBJ.sendEmailNotification = getField(".js-send-email:checked");
		cOBJ.dateRows = getRequestedDays(".jsDurationBox");
		//
		if (cOBJ.policyId == 0) {
			//
			alertify.alert("WARNING!", "Please select a policy.", () => { });
			//
			return;
		}

		//
		if (cOBJ.dateRows.totalTime <= 0) {

			alertify.alert(
				'WARNING!',
				'The total time request must be greater than 0.',
				() => { }
			);
			//
			return;
		}

		//
		if (cOBJ.startDate == 0) {
			//
			alertify.alert(
				"WARNING!",
				"Please select the start date.",
				() => { }
			);
			//
			return;
		}
		//
		if (cOBJ.endDate == 0) {
			//
			alertify.alert("WARNING!", "Please select an end date.", () => { });
			//
			return;
		}
		//
		if (
			window.location.pathname.match(
				/(lms)|(employee_management_system)|(dashboard)/gi
			) === null
		) {
			//
			if (cOBJ.status == "pending") {
				//
				alertify.alert(
					"WARNING!",
					"Please, either select approve or reject.",
					() => { }
				);
				//
				return;
			}
		}
		//
		if (cOBJ.dateRows.error) return;
		//
		let selectedPolicy = getPolicy(cOBJ.policyId, window.timeoff.cPolicies);

		console.log(selectedPolicy);
		// Check if it's not unlimited
		if (selectedPolicy.IsUnlimited == 0) {
			//
			if (selectedPolicy.RemainingTimeWithNegative.M.minutes <= 0) {
				alertify.alert(
					"WARNING!",
					`You don't have any time left against this policy.`,
					() => { }
				);
				return;
			}
			//
			if (
				cOBJ.dateRows.totalTime >
				selectedPolicy.RemainingTimeWithNegative.M.minutes
			) {
				alertify.alert(
					"WARNING!",
					`Requested time-off can not be greater than the allowed time i.e. "${selectedPolicy.RemainingTimeWithNegative.text}"`,
					() => { }
				);
				return;
			}
		}
		//
		cOBJ.fromAdmin = 1;
		if (window.location.pathname.match(/(lms)|(dashboard)/gi) !== null) {
			cOBJ.fromAdmin = 0;
		}
		//
		$.post(
			handlerURL,
			Object.assign(
				{
					action: "check_timeoff_request",
					companyId: companyId,
					employerId: employerId,
					employeeId: selectedEmployeeId,
				},
				cOBJ
			),
			(resp) => {
				if (resp.Response.code == 1) {
					console.log("varified");
					saveTimeOffRequest(cOBJ);
				}
				//
				if (resp.Response.code == 2) {
					if (resp.Response.conflictStatus == "approved") {
						alertify.alert(
							"Conflict!",
							resp.Response.message,
							() => { }
						);
						return;
					} else {
						alertify
							.confirm(
								resp.Response.message,
								() => {
									console.log("confirm");
									saveTimeOffRequest(cOBJ);
								},
								() => {
									return;
								}
							)
							.set("labels", {
								ok: "Yes",
								cancel: "NO",
							})
							.set("title", "Conflict!");
					}
				}
			}
		);
		//
	});

	function saveTimeOffRequest(cOBJ) {
		//
		ml(true, currentLoader);
		//
		$.post(
			handlerURL,
			Object.assign(
				{
					action: "create_timeoff",
					companyId: companyId,
					employerId: employerId,
					employeeId: selectedEmployeeId,
				},
				cOBJ
			),
			(resp) => {
				if (resp.Status === false) {
					ml(false, currentLoader);
					alertify.alert("WARNING!", resp.Response, () => { });
					return;
				}
				//
				ml(false, currentLoader);
				//
				alertify.alert("SUCCESS!", resp.Response, () => {
					$(".jsModalCancel").removeAttr("data-ask");
					$(".jsModalCancel").trigger("click");
					window.location.reload();
				});
				//
				return;
			}
		);
	}

	//
	$(document).on("click", ".jsCreateRequest", function (e) {
		//
		e.preventDefault();
		//
		timeRowsOBJ = {};
		selectedPolicy = 0;
		//
		if ($(this).prop("id") !== undefined && $(this).prop("id") != "") {
			loadAdminProcess();
			return;
		}
		//
		let employeeId = 0,
			employeeName = "";
		//
		if (window.location.pathname.match("/create_employee/") !== null) {
			employeeId = window.location.pathname.split("/")[3];
			employeeName = selectedEmployeeName;
		} else if ($(this).data("id") !== undefined) {
			//
			selectedEmployeeId = loggedInId;
			selectedEmployeeName = loggedInName;
			employeeId = selectedEmployeeId;
			employeeName = selectedEmployeeName;
		} else {
			employeeId = $(this).closest(".jsBox").data("id");
			employeeName = $(this).closest(".jsBox").data("name");
		}
		selectedEmployeeId = employeeId;
		selectedEmployeeName = employeeName;
		//
		if ($(this).data("policyid") !== undefined)
			selectedPolicy = $(this).data("policyid");
		//
		Modal(
			{
				Id: "addModal",
				Title: `Create Time-off for ${selectedEmployeeName}`,
				Body: "",
				Buttons: [
					'<button class="btn btn-orange btn-theme jsCreateTimeOffBalance" style="margin-right: 10px;" title="See employee balance" placement="left"><i class="fa fa-balance-scale" aria-hidden="true"></i>&nbsp;View Balance History</button>',
					'<button class="btn btn-black jsCreateTimeOffBalanceBack dn" style="margin-right: 10px;" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;Back To Create</button>',
					'<button class="btn btn-orange btn-theme jsCreateTimeOffBTN"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Create</button>',
				],
				Loader: "addModalLoader",
				Ask: false,
			},
			async () => {
				loadAddSectionPage("addModalLoader", "addModal");
			}
		);
	});

	/**
	 * @param {Object} event
	 */
	$(document).on("click", ".jsCreateTimeOffBalance", function (event) {
		//
		event.preventDefault();
		//
		if (selectedEmployeeId == null) {
			alertify.alert("Warning!", "Please, select an employee.");
			return;
		}
		//
		$(this).hide(0);
		$(".jsCreateTimeOffBalanceBack").show(0);
		$(".jsCreateTimeOffBTN").hide(0);
		$("#addModal").find('[data-page="main"]').hide(0);
		$("#addModal").find('[data-page="balance-view"]').show(0);
		//
		$("#addModal")
			.find(".csModalHeaderTitle span:nth-child(1)")
			.text(
				$("#addModal")
					.find(".csModalHeaderTitle span:nth-child(1)")
					.text()
					.trim()
					.replace("Create Time-off", "Balance History ")
			);
		//
		$.post(handlerURL, {
			action: "get_employee_balance_history",
			companyId: companyId,
			employerId: employerId,
			employeeId: selectedEmployeeId,
		}).done(function (resp) {
			//
			var rows = "";
			//
			if (resp.Data.length === 0) {
				rows += "<tr>";
				rows += '   <td colspan="6">';
				rows += '       <p class="alert alert-info text-center">';
				rows += "          No balance history found.";
				rows += "       </p>";
				rows += "   </td>";
				rows += "</tr>";
			} else {
				var totalTOs = 0,
					totalTimeTaken = {},
					totalManualTime = {};
				//
				if (resp.Data[0].timeoff_breakdown.active.hour !== undefined) {
					totalTimeTaken["hour"] = 0;
					totalManualTime["hour"] = 0;
				}

				//
				if (
					resp.Data[0].timeoff_breakdown.active.minutes !== undefined
				) {
					totalTimeTaken["minutes"] = 0;
					totalManualTime["minutes"] = 0;
				}

				//
				resp.Data.map(function (balance) {
					//
					var startDate = "",
						endDate = "",
						employeeName = "",
						employeeRole = "";
					//
					if (balance.is_manual == 0 && balance.is_allowed == 1) {
						startDate = moment(
							balance.effective_at,
							"YYYY-MM-DD"
						).format(timeoffDateFormat);
						endDate = "";
						employeeName = "-";
						employeeRole = "";
					} else if (balance.is_manual == 1) {
						startDate = moment(
							balance.effective_at,
							"YYYY-MM-DD"
						).format(timeoffDateFormat);
						endDate = moment(
							balance.effective_at,
							"YYYY-MM-DD"
						).format(timeoffDateFormat);
						employeeName =
							balance.first_name + " " + balance.last_name;
						employeeRole = remakeEmployeeName(balance, false);
						//
						if (
							balance.timeoff_breakdown.active.hours !== undefined
						) {
							//
							if (totalManualTime["hours"] === undefined) {
								totalManualTime["hours"] = 0;
							}
							totalManualTime["hours"] += parseInt(
								balance.timeoff_breakdown.active.hours
							);
						}
						//
						if (
							balance.timeoff_breakdown.active.minutes !==
							undefined
						) {
							//
							if (totalManualTime["minutes"] === undefined) {
								totalManualTime["minutes"] = 0;
							}
							totalManualTime["minutes"] += parseInt(
								balance.timeoff_breakdown.active.minutes
							);
						}
					} else {
						totalTOs++;
						startDate = moment(
							balance.request_from_date,
							"YYYY-MM-DD"
						).format(timeoffDateFormat);
						endDate = moment(
							balance.request_to_date,
							"YYYY-MM-DD"
						).format(timeoffDateFormat);
						employeeName = balance.approverName;
						employeeRole = balance.approverRole;
						//
						if (
							balance.timeoff_breakdown.active.hours !== undefined
						) {
							//
							if (totalTimeTaken["hours"] === undefined) {
								totalTimeTaken["hours"] = 0;
							}
							totalTimeTaken["hours"] += parseInt(
								balance.timeoff_breakdown.active.hours
							);
						}
						//
						if (
							balance.timeoff_breakdown.active.minutes !==
							undefined
						) {
							//
							if (totalTimeTaken["minutes"] === undefined) {
								totalTimeTaken["minutes"] = 0;
							}
							totalTimeTaken["minutes"] += parseInt(
								balance.timeoff_breakdown.active.minutes
							);
						}
					}
					//
					rows += "<tr>";
					rows += "   <td>";
					rows += "       <strong>";
					rows += employeeName + "<br>";
					rows += "       </strong>";
					rows += employeeRole;
					rows += "   </td>";
					rows += "   <td>";
					rows += "       <strong>" + balance.title + "</strong>";
					rows +=
						"       <p>" +
						startDate +
						(endDate != "" ? " - " + endDate : "") +
						"</p>";
					rows += "   </td>";
					rows +=
						' <td class="' +
						(balance.is_added == 0
							? "text-danger"
							: "text-success") +
						'"><i class="fa fa-arrow-' +
						(balance.is_added == 0 ? "down " : "up") +
						'"></i>&nbsp;' +
						balance.timeoff_breakdown.text +
						"</td>";
					rows += "   <td>";
					rows += balance.note != "" ? balance.note : "-";
					rows += "   </td>";
					rows += "   <td>";
					rows += moment(balance.created_at, "YYYY-MM-DD").format(
						timeoffDateFormatWithTime
					);
					rows += "   </td>";
					rows += "   <td>";
					rows +=
						'       <strong class="text-' +
						(balance.is_manual == 1 ? "success" : "danger") +
						'">' +
						(balance.is_manual == 1 ? "Yes" : "No") +
						"</strong>";
					rows += "   </td>";
					rows += "</tr>";
					rows += "<tr>";
					rows += '   <td colspan="6">';
					if (balance.is_manual == 0 && balance.is_allowed == 1) {
						rows +=
							"       <p><strong>Note</strong>: A balance of <b>" +
							balance.added_time / 60 +
							'</b> hours is available against policy <b>"' +
							balance.title +
							'"</b> effective from <b>' +
							moment(balance.effective_at, "YYYY-MM-DD").format(
								timeoffDateFormat
							) +
							"</b>";
					} else {
						rows +=
							"       <p><strong>Note</strong>: <strong>" +
							employeeName +
							"</strong> has " +
							(balance.is_manual == 1
								? balance.is_added == 1
									? "added balance"
									: "subtracted balance"
								: "approved time off") +
							' against policy "<strong>' +
							balance.title +
							'</strong>" on <strong>' +
							moment(balance.created_at, "YYYY-MM-DD").format(
								timeoffDateFormatWithTime
							) +
							"</strong> which will take effect " +
							(startDate == endDate ? "on " : " from ") +
							" <strong>" +
							startDate +
							"" +
							(startDate != endDate ? " to  " + endDate : "") +
							"</strong>.</p>";
					}
					rows += "   </td>";
					rows += "</tr>";
				});
				//
				$(".jsCreateTimeOffNumber").text(totalTOs);
				$(".jsCreateTimeOffTimeTaken").text(getText(totalTimeTaken));
				$(".jsCreateTimeOffManualAllowedTime").text(
					getText(totalManualTime)
				);
			}
			//
			$("#jsCreateTimeoffBalanceBody").html(rows);
			//
			ml(false, "balance-view");
		});
	});

	/**
	 * @param {Object} event
	 */
	$(document).on("click", ".jsCreateTimeOffBalanceBack", function (event) {
		//
		event.preventDefault();
		//
		$(this).hide(0);
		$(".jsCreateTimeOffBalance").show(0);
		$(".jsCreateTimeOffBTN").show(0);
		$("#addModal").find('[data-page="balance-view"]').hide(0);
		$("#addModal").find('[data-page="main"]').show(0);
		//
		$("#addModal")
			.find(".csModalHeaderTitle span:nth-child(1)")
			.text(
				$("#addModal")
					.find(".csModalHeaderTitle span:nth-child(1)")
					.text()
					.trim()
					.replace("Balance History ", "Create Time-off")
			);
	});

	/**
	 * @param {Object} event
	 */
	$(document).on("click", ".jsCreateTimeOffBalanceAdmin", function (event) {
		//
		event.preventDefault();
		//
		if (selectedEmployeeId == null) {
			alertify.alert("Warning!", "Please, select an employee.");
			return;
		}
		//
		$(this).hide(0);
		$(".jsCreateTimeOffBalanceBackAdmin").show(0);
		$(".jsCreateTimeOffBTN").hide(0);
		$("#addAdminModal").find('[data-page="main"]').hide(0);
		$("#addAdminModal").find('[data-page="balance-view"]').show(0);
		//
		$("#addAdminModal")
			.find(".csModalHeaderTitle span:nth-child(1)")
			.text(
				$("#addAdminModal")
					.find(".csModalHeaderTitle span:nth-child(1)")
					.text()
					.trim()
					.replace("Create a time-off", "Balance History")
			);
		//
		$.post(handlerURL, {
			action: "get_employee_balance_history",
			companyId: companyId,
			employerId: employerId,
			employeeId: selectedEmployeeId,
		}).done(function (resp) {
			//
			var rows = "";
			//
			if (resp.Data.length === 0) {
				rows += "<tr>";
				rows += '   <td colspan="6">';
				rows += '       <p class="alert alert-info text-center">';
				rows += "          No balance history found.";
				rows += "       </p>";
				rows += "   </td>";
				rows += "</tr>";
			} else {
				var totalTOs = 0,
					totalTimeTaken = {},
					totalManualTime = {};
				//
				if (resp.Data[0].timeoff_breakdown.active.hour !== undefined) {
					totalTimeTaken["hour"] = 0;
					totalManualTime["hour"] = 0;
				}

				//
				if (
					resp.Data[0].timeoff_breakdown.active.minutes !== undefined
				) {
					totalTimeTaken["minutes"] = 0;
					totalManualTime["minutes"] = 0;
				}
				//
				resp.Data.map(function (balance) {
					//
					var startDate = "",
						endDate = "",
						employeeName = "",
						employeeRole = "";
					//
					if (balance.is_manual == 0 && balance.is_allowed == 1) {
						startDate = moment(
							balance.effective_at,
							"YYYY-MM-DD"
						).format(timeoffDateFormat);
						endDate = "";
						employeeName = "-";
						employeeRole = "";
					} else if (balance.is_manual == 1) {
						startDate = moment(
							balance.effective_at,
							"YYYY-MM-DD"
						).format(timeoffDateFormat);
						endDate = moment(
							balance.effective_at,
							"YYYY-MM-DD"
						).format(timeoffDateFormat);
						employeeName =
							balance.first_name + " " + balance.last_name;
						employeeRole = remakeEmployeeName(balance, false);
						//
						if (
							balance.timeoff_breakdown.active.hours !== undefined
						) {
							//
							if (totalManualTime["hours"] === undefined) {
								totalManualTime["hours"] = 0;
							}
							totalManualTime["hours"] += parseInt(
								balance.timeoff_breakdown.active.hours
							);
						}
						//
						if (
							balance.timeoff_breakdown.active.minutes !==
							undefined
						) {
							//
							if (totalManualTime["minutes"] === undefined) {
								totalManualTime["minutes"] = 0;
							}
							totalManualTime["minutes"] += parseInt(
								balance.timeoff_breakdown.active.minutes
							);
						}
					} else {
						totalTOs++;
						startDate = moment(
							balance.request_from_date,
							"YYYY-MM-DD"
						).format(timeoffDateFormat);
						endDate = moment(
							balance.request_to_date,
							"YYYY-MM-DD"
						).format(timeoffDateFormat);
						employeeName = balance.approverName;
						employeeRole = balance.approverRole;
						//
						if (
							balance.timeoff_breakdown.active.hours !== undefined
						) {
							//
							if (totalTimeTaken["hours"] === undefined) {
								totalTimeTaken["hours"] = 0;
							}
							totalTimeTaken["hours"] += parseInt(
								balance.timeoff_breakdown.active.hours
							);
						}
						//
						if (
							balance.timeoff_breakdown.active.minutes !==
							undefined
						) {
							//
							if (totalTimeTaken["minutes"] === undefined) {
								totalTimeTaken["minutes"] = 0;
							}
							totalTimeTaken["minutes"] += parseInt(
								balance.timeoff_breakdown.active.minutes
							);
						}
					}
					//
					rows += "<tr>";
					rows += "   <td>";
					rows += "       <strong>";
					rows += employeeName + "<br>";
					rows += "       </strong>";
					rows += employeeRole;
					rows += "   </td>";
					rows += "   <td>";
					rows += "       <strong>" + balance.title + "</strong>";
					rows +=
						"       <p>" +
						startDate +
						(endDate != "" ? " - " + endDate : "") +
						"</p>";
					rows += "   </td>";
					rows +=
						' <td class="' +
						(balance.is_added == 0
							? "text-danger"
							: "text-success") +
						'"><i class="fa fa-arrow-' +
						(balance.is_added == 0 ? "down " : "up") +
						'"></i>&nbsp;' +
						balance.timeoff_breakdown.text +
						"</td>";
					rows += "   <td>";
					rows += balance.note != "" ? balance.note : "-";
					rows += "   </td>";
					rows += "   <td>";
					rows += moment(balance.created_at, "YYYY-MM-DD").format(
						timeoffDateFormatWithTime
					);
					rows += "   </td>";
					rows += "   <td>";
					rows +=
						'       <strong class="text-' +
						(balance.is_manual == 1 ? "success" : "danger") +
						'">' +
						(balance.is_manual == 1 ? "Yes" : "No") +
						"</strong>";
					rows += "   </td>";
					rows += "</tr>";
					rows += "<tr>";
					rows += '   <td colspan="6">';
					if (balance.is_manual == 0 && balance.is_allowed == 1) {
						rows +=
							"       <p><strong>Note</strong>: A balance of <b>" +
							balance.added_time / 60 +
							'</b> hours is available against policy <b>"' +
							balance.title +
							'"</b> effective from <b>' +
							moment(balance.effective_at, "YYYY-MM-DD").format(
								timeoffDateFormat
							) +
							"</b>";
					} else {
						rows +=
							"       <p><strong>Note</strong>: <strong>" +
							employeeName +
							"</strong> has " +
							(balance.is_manual == 1
								? balance.is_added == 1
									? "added balance"
									: "subtracted balance"
								: "approved time off") +
							' against policy "<strong>' +
							balance.title +
							'</strong>" on <strong>' +
							moment(balance.created_at, "YYYY-MM-DD").format(
								timeoffDateFormatWithTime
							) +
							"</strong> which will take effect " +
							(startDate == endDate ? "on " : " from ") +
							" <strong>" +
							startDate +
							"" +
							(startDate != endDate ? " to  " + endDate : "") +
							"</strong>.</p>";
					}
					rows += "   </td>";
					rows += "</tr>";
				});
				//
				$(".jsCreateTimeOffNumber").text(totalTOs);
				$(".jsCreateTimeOffTimeTaken").text(getText(totalTimeTaken));
				$(".jsCreateTimeOffManualAllowedTime").text(
					getText(totalManualTime)
				);
			}
			//
			$("#jsCreateTimeoffBalanceBody").html(rows);
			//
			ml(false, "balance-view");
			console.log(resp);
		});
	});

	/**
	 * @param {Object} event
	 */
	$(document).on(
		"click",
		".jsCreateTimeOffBalanceBackAdmin",
		function (event) {
			//
			event.preventDefault();
			//
			$(this).hide(0);
			$(".jsCreateTimeOffBalanceAdmin").show(0);
			$(".jsCreateTimeOffBTN").show(0);
			$("#addAdminModal").find('[data-page="balance-view"]').hide(0);
			$("#addAdminModal").find('[data-page="main"]').show(0);
			//
			$("#addAdminModal")
				.find(".csModalHeaderTitle span:nth-child(1)")
				.text(
					$("#addAdminModal")
						.find(".csModalHeaderTitle span:nth-child(1)")
						.text()
						.trim()
						.replace("Balance History", "Create a time-off")
				);
		}
	);

	//
	function getModalBody(type) {
		return new Promise((res) => {
			$.post(
				handlerURL,
				{
					action: "get_modal",
					companyId: companyId,
					employerId: employerId,
					employeeId: employeeId,
					type: type,
					formLMS:
						window.location.pathname.match(
							/(lms)|(create_employee)|(employee_management_system)|(dashboard)/gi
						) !== null
							? 1
							: 0,
				},
				(resp) => {
					res(resp);
				}
			);
		});
	}

	//
	function getEmployeePolicies() {
		return new Promise((res) => {
			$.post(
				handlerURL,
				{
					action: "get_employee_policies_with_approvers",
					companyId: companyId,
					employerId: employerId,
					employeeId: selectedEmployeeId,
				},
				(resp) => {
					res(resp);
				}
			);
		});
	}


	//
	function getSideBarPolicies() {
		//
		$('.jsAsOfTodayPolicies').html('');
		//
		$.post(
			handlerURL, {
			action: 'get_employee_policies_by_date',
			companyId: companyId,
			employerId: employerId,
			employeeId: selectedEmployeeId,
			fromDate: $('#jsStartDate').val()
		},
			(resp) => {
				//
				window.timeoff.cPolicies = resp.Data;
				$('.jsCreateTimeOffBTN').prop('disabled', false);
				$('#jsEndDate').prop('disabled', false);
				$('#jsAddPolicy').prop('disabled', false);
				//
				let newPolicies = [];
				let newPoliciesObj = {};
				//
				if (resp.Data.length > 0) {
					//
					let rows = '';
					//
					resp.Data.map((policy) => {
						if (policy.Reason != '') return;
						//
						newPolicies.push(policy);
						//
						if (newPoliciesObj[policy['Category']] === undefined) newPoliciesObj[policy['Category']] = [];
						newPoliciesObj[policy['Category']].push(policy);
						//
						rows += `
                        <div class="p10">
                        <strong>${policy.Title} (<strong class="text-${policy.categoryType == 1 ? "success" : "danger"
							}">${policy.categoryType == 1 ? "Paid" : "Unpaid"
							}</strong>)</strong>
                        <br />
                        <span>(${policy.Category})</span>  
                        <br />
                        <span>${policy.IsUnlimited
								? "Unlimited"
								: policy.RemainingTime.text
							} remaining</span>
                        <br />
                        <span>${policy.IsUnlimited
								? "Unlimited"
								: policy.ConsumedTime.text
							} scheduled</span>
                        <br />
                        <span>Employment status: ${ucwords(
								policy.EmployementStatus
							)}</span><br>
                        <span>Policy Cycle: ${moment(
								policy.lastAnniversaryDate,
								"YYYY/MM/DD"
							).format(timeoffDateFormat)} -
                        ${moment(
								policy.upcomingAnniversaryDate,
								"YYYY/MM/DD"
							).format(timeoffDateFormat)}
                        </span> 
                        </div>
                        <hr />
                        `;
					});
					//
					window.timeoff.cPolicies = newPolicies;
					//
					$('#jsAsOfTodayPolicies').html(rows);
					//
					// add policy dropdown with selected date.
					let policyRows = '<option value="0" selected="true">[Select a policy]</option>';
					//
					$.each(newPoliciesObj, (category, policies) => {
						policyRows += `<optgroup label="${category}">`;
						//
						policies.map((policy) => {
							policyRows += `<option value="${policy.PolicyId}">${policy.Title} (<strong class="text-${policy.categoryType == 1 ? 'success' : 'danger'}">${policy.categoryType == 1 ? 'Paid' : 'Unpaid'}</strong>)</option>`;
						});
						policyRows += `</optgroup>`;
					});
					//
					$('#jsAddPolicy').html(policyRows);
					$('#jsAddPolicy').select2();
				}
				//
				if (window.timeoff.cPolicies.length == 0) {
					$('#jsAsOfTodayPolicies').html('<div class="alert alert-success">No policies found.</div>');
					$('.jsCreateTimeOffBTN').prop('disabled', true);
					$('#jsEndDate').prop('disabled', true);
					$('#jsAddPolicy').prop('disabled', true);
				}
			}
		);
	}

	//
	function unavailable(date) {
		//
		var checkOffDays =
			policyOffDays === undefined ? timeOffDays : policyOffDays;
		//
		var dmy = moment(date).format("MM-DD-YYYY");
		let d = moment(date).format("dddd").toString().toLowerCase();
		let t = 1;
		if ($.inArray(d, checkOffDays) !== -1) {
			t = { work_on_holiday: 0, holiday_title: "Weekly off" };
		} else {
			t = inObject(dmy, holidayDates);
		}
		if (t == -1) {
			return [true, ""];
		} else {
			if (t.work_on_holiday == 1) {
				return [true, "set_disable_date_color", t.holiday_title];
			} else {
				return [false, "", t.holiday_title];
			}
		}
	}

	//
	async function loadAdminProcess() {
		// lets fetch all employees
		const resp = await fetchCompanyEmployees();
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
		if (resp.Status == false || resp.Data.length == 0) {
			alertify.alert(
				"WARNING!",
				"We are unable to find any active employees.",
				() => { }
			);
			return;
		}
		//
		let options = '<option value="0">Please select an employee</option>';
		//
		window.timeoff.companyEmployees = resp.Data;
		//
		resp.Data.map((employee) => {
			if (employee.terminated_status === "1" || employee.active === "0" || employee.user_id == employerId) {
				return;
			}
			//
			options += `<option value="${employee.user_id
				}">${remakeEmployeeName(employee)}</option>`;
		});

		//
		Modal(
			{
				Id: "addAdminModal",
				Title: `Create a time-off`,
				Body: `
                <div class="col-sm-6">
                <label>Select an employee <span class="cs-required">*</span></label>
                <select id="jsAddAdminModalList">${options}</select>
                </div>
                <div class="col-sm-12">
                    <hr />
                </div>
                <div class="clearfix"></div>
            `,
				Buttons: [
					'<button class="btn btn-orange btn-theme jsCreateTimeOffBalanceAdmin" style="margin-right: 10px;" title="See employee balance" placement="left"><i class="fa fa-balance-scale" aria-hidden="true"></i>&nbsp;View Balance History</button>',
					'<button class="btn btn-black jsCreateTimeOffBalanceBackAdmin dn" style="margin-right: 10px;" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;Back To Create</button>',
					'<button class="btn btn-orange btn-theme jsCreateTimeOffBTN"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Create</button>',
				],
				Loader: "addAdminModalLoader",
				Ask: false,
			},
			async () => {
				//
				$("#jsAddAdminModalList").select2({
					templateSelection: (opt) => {
						//
						$(".jsAddTimeOff").remove();
						$(".jsCreateTimeOffBTN").addClass("dn");
						//
						if (opt.id == 0) {
							return opt.text;
						}
						//
						const employeeDetail = getUserById(
							opt.id,
							resp.Data,
							"user_id"
						);
						selectedEmployeeId = employeeDetail.user_id;
						selectedEmployeeName = `${employeeDetail.first_name} ${employeeDetail.last_name}`;
						//
						ml(true, "addAdminModalLoader");
						//
						loadAddSectionPage(
							"addAdminModalLoader",
							"addAdminModal"
						);
						//
						return $(
							`<span><img  style="padding: 5px; margin-left: 5px;" src="${employeeDetail.image == "" ||
								employeeDetail.image == null
								? awsURL + "test_file_01.png"
								: awsURL + employeeDetail.image
							}" width="60px" /> <span>${opt.text}</span></span>`
						);
					},
				});
				//
				ml(false, "addAdminModalLoader");
			}
		);
	}

	//
	function fetchCompanyEmployees() {
		return new Promise((res) => {
			$.post(
				handlerURL,
				{
					action: "get_company_employees",
					companyId: companyId,
					employerId: employerId,
					employeeId: selectedEmployeeId,
				},
				(resp) => {
					res(resp);
				}
			);
		});
	}

	//
	async function loadAddSectionPage(loader, modalId) {
		//
		policyOffDays = undefined;
		//
		if (window.timeoff.companyEmployees === undefined) {
			const resp1 = await fetchCompanyEmployees();
			window.timeoff.companyEmployees = resp1.Data;
		}
		// Reset page
		$("#addAdminModal")
			.find(".csModalHeaderTitle span:nth-child(1)")
			.text("Create a time-off");
		$("#addModal")
			.find(".csModalHeaderTitle span:nth-child(1)")
			.text(
				$("#addModal")
					.find(".csModalHeaderTitle span:nth-child(1)")
					.text()
					.replace("Balance History ", "Create Time-off")
			);
		$(".jsCreateTimeOffBalanceBack").hide(0);
		$(".jsCreateTimeOffBalanceBackAdmin").hide(0);
		$(".jsCreateTimeOffBalance").show(0);
		$(".jsCreateTimeOffBalanceAdmin").show(0);
		$(".jsCreateTimeOffBTN").show(0);
		//
		currentLoader = loader;
		// Get modal body
		const resp = await getEmployeePolicies();
		const policies = resp.Data.Policies;
		$(`#${modalId}`).find(".csModalBody .alert").remove();
		//
		if (policies.length === 0) {
			$(".jsAddTimeOff").remove();
			$(`#${modalId}`)
				.find(".csModalBody")
				.append(
					`<div class="alert alert-success text-center">We are unable to find policies against this employee.</div>`
				);
			ml(false, loader);
			return;
		}
		//
		let c = policies.length,
			d = 0;
		//
		let newPolicies = {};
		//
		window.timeoff.cPolicies = policies;
		//
		policies.map((policy) => {
			if (policy.Reason != "") {
				d++;
				return;
			}
			//
			if (newPolicies[policy["Category"]] === undefined)
				newPolicies[policy["Category"]] = [];
			//
			newPolicies[policy["Category"]].push(policy);
		});
		//
		if (c == d) {
			$(".jsAddTimeOff").remove();
			$(`#${modalId}`)
				.find(".csModalBody")
				.append(
					`<div class="alert alert-success text-center">We are unable to find policies against this employee.</div>`
				);
			ml(false, loader);
			return;
		}
		//
		let policyRows =
			'<option value="0" selected="true">[Select a policy]</option>';
		//
		$.each(newPolicies, (category, policies) => {
			policyRows += `<optgroup label="${category}">`;
			//
			policies.map((policy) => {
				policyRows += `<option value="${policy.PolicyId}">${policy.Title
					} (<strong class="text-${policy.categoryType == 1 ? "success" : "danger"
					}">${policy.categoryType == 1 ? "Paid" : "Unpaid"
					}</strong>)</option>`;
			});
			policyRows += `</optgroup>`;
		});
		//
		const bodyText = await getModalBody("add");
		//
		$(".jsAddTimeOff").remove();
		$(`#${modalId}`).find(".csModalBody").append(bodyText);
		//
		$("#jsAddPolicy").html(policyRows);
		$("#jsAddPolicy").select2();
		//
		if (selectedPolicy != 0)
			$("#jsAddPolicy").select2("val", selectedPolicy);
		//
		$("#jsStartDate").datepicker({
			format: "mm-dd-yyyy",
			changeYear: true,
			changeMonth: true,
			beforeShowDay: unavailable,
			onSelect: (date) => {
				$("#jsEndDate").datepicker("option", "minDate", date);

				//
				$("#asoffdate").text(
					"AS Of  " +
					moment($("#jsStartDate").val(), "MM/DD/YYYY").format(
						timeoffDateFormat
					)
				);
				getSideBarPolicies();

				//
				remakeRangeRows("#jsStartDate", "#jsEndDate", ".jsDurationBox");
			},
		});
		$("#jsEndDate").datepicker({
			format: "mm-dd-yyyy",
			changeYear: true,
			changeMonth: true,
			beforeShowDay: unavailable,
			onSelect: (date) => {
				//
				remakeRangeRows("#jsStartDate", "#jsEndDate", ".jsDurationBox");
			},
		});
		$("#js-vacation-return-date").datepicker({
			format: "mm-dd-yyyy",
			changeYear: true,
			changeMonth: true,
			minDate: 1,
			beforeShowDay: unavailable,
		});
		$("#js-bereavement-return-date").datepicker({
			format: "mm-dd-yyyy",
			changeYear: true,
			changeMonth: true,
			minDate: 1,
			beforeShowDay: unavailable,
		});
		$("#js-compensatory-return-date").datepicker({
			format: "mm-dd-yyyy",
			changeYear: true,
			changeMonth: true,
			minDate: 1,
			beforeShowDay: unavailable,
		});
		$("#js-compensatory-start-time").datepicker({
			format: "g:i A",
			formatTime: "g:i A",
			step: 15,
		});
		$("#js-compensatory-end-time").datepicker({
			format: "g:i A",
			formatTime: "g:i A",
			step: 15,
		});
		//
		if (
			window.location.pathname.match(
				/(lms)|(employee_management_system)|(dashboard)/gi
			) !== null
		) {
			// Hide comment area
			$("#jsComment").parent().hide();
			$(".jsEmailBoxAdd").hide();
			$("#jsStatus")
				.html('<option value="pending">Pending</option>')
				.parent()
				.hide(0);
			$("#jsStartDate").datepicker("option", "minDate", -1);
			$("#jsEndDate").datepicker("option", "minDate", -1);
		} else {
			//
			$("#jsStatus").select2({ minimumResultsForSearch: 5 });
		}

		//
		CKEDITOR.config.toolbar = [["Bold", "Italic", "Underline"]];
		//
		if ($("#jsReason").length > 0) CKEDITOR.replace("jsReason");
		if ($("#jsComment").length > 0) CKEDITOR.replace("jsComment");
		//
		window.timeoff.companyEmployees.map(function (emp) {
			if (emp.user_id == selectedEmployeeId) {
				var employeeJoinedAt =
					emp["joined_at"] == null
						? emp["joined_at"]
						: emp["registration_date"];
				//
				employeeJoinedAt = moment(
					employeeJoinedAt,
					"YYYY-MM-DD"
				).format(timeoffDateFormat);
				//
				$("#jsEmployeeInfoAdd").html(`
                <figure>
                    <img src="${getImageURL(emp.image)}"
                        class="csRadius50">
                    <div class="csTextBox">
                        <p>${emp.first_name} ${emp.last_name}</p>
                        <p class="csTextSmall"> ${remakeEmployeeName(
					emp,
					false
				)}</p>
                        <p class="csTextSmall">${emp.email}</p>
                        <p class="csTextSmall">${emp.anniversary_text}</p>
                    </div>
                </figure>
                <div class="clearfix"></div>
                `);
			}
		});
		// Get policies by date
		getSideBarPolicies(selectedEmployeeId);
		setApproversView(resp.Data.Approvers);
		//
		$(".jsCreateTimeOffBTN").removeClass("dn");
		//
		loadTitles();
		//
		setUpcomingTimeOffs(selectedEmployeeId);
		//
		ml(false, loader);
	}

	//
	function setApproversView(approvers) {
		if (approvers.length === 0) {
			$("#jsApproversListingAdd").html("<p>No approvers found.</p>");
			return;
		}
		//
		let rows = "";
		let mRows = "";
		//
		approvers.map((approver) => {
			//
			let msg = `${remakeEmployeeName(approver)}`;
			//
			rows += `
                <div class="csApproverBox" title="Approver" data-content="${msg}">
                    <img src="${approver.profile_picture == null ||
					approver.profile_picture == ""
					? awsURL + "test_file_01.png"
					: awsURL + approver.profile_picture
				}" />
                </div>
            `;
			mRows += `
                <div class="csApproverBox">
                    <div class="employee-info">            
                        <figure>                
                            <img src="${approver.profile_picture == null ||
					approver.profile_picture == ""
					? awsURL + "test_file_01.png"
					: awsURL + approver.profile_picture
				}" />          
                        </figure>            
                        <div class="text">                
                            <h4>${msg}</h4>                
                            <p><a href="http://automotohr.local/employee_profile/${approver.userId
				}" target="_blank">Id: ${getEmployeeId(
					approver.userId,
					approver.employee_number
				)}</a></p>            
                        </div>        
                    </div>
                </div>
            `;
		});
		//
		$("#jsApproversListingMobileAdd").html(mRows);
		$("#jsApproversListingAdd").html(rows);
		//
		$(".csApproverBox").popover({
			html: true,
			trigger: "hover",
			placement: "top",
		});
	}

	//
	function getSelectedPolicy(policyId) {
		if (window.timeoff.cPolicies.length === 0) return [];
		//
		let selectedPolicy = [];
		//
		window.timeoff.cPolicies.map((policy) => {
			if (policy.PolicyId == policyId) selectedPolicy = policy;
		});
		//
		return selectedPolicy;
	}
});
