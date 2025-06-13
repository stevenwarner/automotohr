$(function () {
	//
	let callOBJ = {
		Requests: {
			Main: {
				action: "get_requests",
				companyId: companyId,
				employerId: employerId,
				employeeId: employeeId,
				level: level,
				type: "pending",
				filter: {
					employees: "all",
					policies: "all",
					status: "all",
					order: "upcoming",
					startDate: "",
					endDate: "",
				},
				public: 0,
			},
		},
	},
		xhr = null;
	//
	$("#js-filter-status").select2();
	$("#js-filter-sort").select2({
		minimumResultsForSearch: -1
	});
	//
	// fetchTimeOffs();
	// $('.jsReportTab[data-key="pending"]').trigger('click');

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
	$(".jsReportTab").click(function (e) {
		//
		e.preventDefault();
		//
		callOBJ.Requests.Main.type = $(this).data("key");
		//
		//
		$(".jsReportTab").parent().removeClass("active").removeClass('csActiveTab');
		$(this).parent().addClass("active").addClass('csActiveTab');
		//
		fetchTimeOffs();
	});

    $('.jsReportTab[data-key="pending"]').trigger('click');

	$("#js-filter-sort").change(function () {
		callOBJ.Requests.Main.filter.order = $(this).val();
		fetchTimeOffs();
	});

	//
	function resetFilter(e) {
		//
		e.preventDefault();
		//
		$("#js-filter-employee").select2("val", "all");
		$("#js-filter-policies").select2("val", "all");
		$("#js-filter-status").select2("val", "all");
		$("#js-filter-sort").select2("val", "upcoming");
		$("#js-filter-from-date").val("");
		$("#js-filter-end-date").val("");
		//
		callOBJ.Requests.Main.filter.employees = "all";
		callOBJ.Requests.Main.filter.policies = "all";
		callOBJ.Requests.Main.filter.status = "all";
		callOBJ.Requests.Main.filter.order = "upcoming";
		callOBJ.Requests.Main.filter.startDate = "";
		callOBJ.Requests.Main.filter.endDate = "";
		//
		fetchTimeOffs();
	}

	//
	function applyFilter(e) {
		//
		e.preventDefault();
		//
		callOBJ.Requests.Main.filter.employees = $("#js-filter-employee").val();
		callOBJ.Requests.Main.filter.policies = $("#js-filter-policies").val();
		callOBJ.Requests.Main.filter.status = $("#js-filter-status").val();
		callOBJ.Requests.Main.filter.order = $("#js-filter-sort").val();
		callOBJ.Requests.Main.filter.startDate = $("#js-filter-from-date").val();
		callOBJ.Requests.Main.filter.endDate = $("#js-filter-to-date").val();
		//
		fetchTimeOffs();
	}

	// Fetch plans
	function fetchTimeOffs() {
		//
		if (window.timeoff.employees === undefined) {
			setTimeout(fetchTimeOffs, 1000);
			return;
		}
		//
		if (xhr != null) return;
		//
		ml(true, "requests");
		//
		$(".js-error-row").remove();
		//
		xhr = $.post(handlerURL, callOBJ.Requests.Main, function (resp) {
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
					`<tr class="js-error-row"><td colspan="${$(".js-table-head").find("th").length
					}"><p class="alert alert-info text-center">${resp.Response
					}</p></td></tr>`
				);
				//
				ml(false, "requests");
				//
				return;
			}
			//
			if (resp.Status === false) {
				//
				$(".js-ip-pagination").html("");
				//
				ml(false, "requests");
				//
				return;
			}
			//
			setTable(resp);
		});
	}

	//
	function setTable(resp) {
		//
		let rows = "";
		//
		if (resp.Data.length == 0) {
			$("#js-data-area").html(
				`<tr><td colspan="6"><p class="alert alert-info text-center">No time-offs found.</p></td></tr>`
			);
			ml(false, "requests");
			return;
		}
		//
		$.each(resp.Data, function (i, v) {
			//
			if (v.employee_sid == employeeId) return;
			//
			let allow_update = v.allow_update;
			let userRow = getUserById(
				v.employee_sid,
				window.timeoff.employees,
				"user_id"
			);
			//
			if (Object.keys(userRow).length == 0) return;
			//
			let tab_status = callOBJ.Requests.Main.type;
			let bgStatusColor = '';
			//
			if (tab_status == "pending") {
				$("#request_status_info").show();
				if (v.level_status == 'approved') {
					bgStatusColor = 'background: rgba(129, 180, 49, .2)';
				} else if (v.level_status == 'rejected') {
					bgStatusColor = 'background: rgba(242, 222, 222, .5)';
				}
			} else {
				$("#request_status_info").hide();
			}
			//
			rows += `<tr class="jsBox" data-id="${v.sid}" style="${bgStatusColor}" data-status="${v.status}" data-userid="${v.employee_sid}" data-name="${userRow.first_name} ${userRow.last_name}">`;
			rows += '    <td scope="row">';
			rows += '        <div class="employee-info">';
			rows += "            <figure>";
			rows += `                <img src="${getImageURL(
				userRow.image
			)}" class="img-circle emp-image" />`;
			rows += "            </figure>";
			rows += '            <div class="text">';
			rows += `                <h4>${userRow.first_name} ${userRow.last_name} </h4>`;
			rows += `                <p>${remakeEmployeeName(userRow, false)} </p>`;
			rows += `                <p><a href="${baseURL}employee_profile/${userRow.user_id
				}" target="_blank">Id: ${getEmployeeId(
					userRow.user_id,
					userRow.employee_number
				)}</a></p>
				${userRow.anniversary_text}`;
			rows += "            </div>";
			rows += "        </div>";
			rows += "    </td>";
			rows += `<td>
                        <div class="upcoming-time-info">            
                            <div class="icon-image">                   
                                <img src="${baseURL}assets/images/upcoming-time-off-icon.png" class="emp-image" alt="emp-1">             
                            </div>             
                            <div class="text">                  
                                <h4>${v.request_from_date == v.request_to_date
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
                                <span>${v.title} (<strong class="text-${v.categoryType == 1 ? 'success' : 'danger'}">${v.categoryType == 1 ? 'Paid' : 'Unpaid'}</strong>)</span><br />          
                                <span>${v.breakdown.text}</span>
                            </div>       
                        </div>
                    </td>`;

			rows += `<td>
                        <div class="progress" style="margin-top: 10px;">
                            <div class="progress-bar progress-bar-success" role="progressbar" style="width: ${v.status == "pending"
					? v.level_status != "pending"
						? 50
						: 0
					: 100
				}%;">
                                <span class="sr-only"> ${v.status == "pending"
					? v.level_status != "pending"
						? 50
						: 0
					: 100
				} % Complete</span>
                            </div>
                            <p>${v.status == "pending"
					? v.level_status != "pending"
						? 50
						: 0
					: 100
				}%</p>
                        </div>
                        ${getApproverLisiting(v.history)}
                    </td>`;
			rows += `<td class="cs-vam">
                        <p>${v.reason == "" || v.reason == null ? "-" : v.reason
				}</p>
                    </td>`;
			rows += `<td class="cs-vam">
                        <p>${moment(v.created_at).format(
				timeoffDateFormatWithTime
			)}</p>
                    </td>`;

			rows += `    <td>`;
			rows += `    <div class="dropdown" style="margin-top: 10px;">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Action
                            <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="right:0; left: auto;">`;
			if (allow_update == 'yes') {
				rows += `<li><a href="#" class="jsEditTimeOff">Edit Time-off</a></li>`;
				let approversSids = getApproverSids(v.history);
				if ($.inArray(employerId, approversSids) == -1) {
					rows += `<li><a href="#" class="jsEditNote" title="Edit Comment" data-empSid="${userRow.user_id}" data-reqSid="${v.sid}">Edit Comment</a></li>`;
				}
			}
			if (allow_update == 'yes') {
				rows += ` ${v.status == "cancelled" || v.status == "cancel"
					? ""
					: `<li><a href="#" class="${v.archive == 1
						? "jsActiveTimeOff"
						: "jsArchiveTimeOff"
					}">${v.archive == 1 ? "Activate" : "Archive"
					}</a></li>`
					}`;
			}
			rows += `<li><a href="#" class="jsHistoryTimeOff">View History</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="${baseURL}timeoff/print/requests/${v.sid
				}" target="_blank">Print</a></li>
                                                        <li><a href="${baseURL}timeoff/download/requests/${v.sid
				}" target="_blank">Download</a></li>
                            </ul>
                        </div>`;
			rows += `    </td>`;
			rows += `</tr>`;
		});

		//
		$("#js-data-area").html(rows);

		//
		$(".js-type-popover").popover({
			html: true,
			trigger: "hover",
		});
		//
		$('[data-toggle="tooltip"]').tooltip();
		//
		$(".csApproverBox").popover({
			html: true,
			trigger: "hover",
			placement: "left",
		});
		//
		ml(false, "requests");
	}

	//
	$(document).on("click", ".jsArchiveTimeOff", function (e) {
		//
		e.preventDefault();
		//
		let requestId = $(this).closest("tr").data("id");
		//
		alertify
			.confirm(
				"Do you really want to archive this time off?",
				() => {
					//
					ml(true, "requests");
					//
					$.post(
						handlerURL,
						Object.assign(
							{
								action: "archive_request",
								companyId: companyId,
								employerId: employerId,
								employeeId: employeeId,
							},
							{ requestId: requestId, archive: 1 }
						),
						(resp) => {
							//
							alertify.alert("SUCCESS!", resp.Response, () => {
								window.location.reload();
							});
						}
					);
				},
				() => { }
			)
			.set("labels", {
				ok: "YES",
				cancel: "NO",
			})
			.setHeader("CONFIRM!");
	});

	//
	$(document).on("click", ".jsActiveTimeOff", function (e) {
		//
		e.preventDefault();
		//
		let requestId = $(this).closest("tr").data("id");
		//
		alertify
			.confirm(
				"Do you really want to activate this time off?",
				() => {
					//
					ml(true, "requests");
					//
					$.post(
						handlerURL,
						Object.assign(
							{
								action: "archive_request",
								companyId: companyId,
								employerId: employerId,
								employeeId: employeeId,
							},
							{ requestId: requestId, archive: 0 }
						),
						(resp) => {
							//
							alertify.alert("SUCCESS!", resp.Response, () => {
								window.location.reload();
							});
						}
					);
				},
				() => { }
			)
			.set("labels", {
				ok: "YES",
				cancel: "NO",
			})
			.setHeader("CONFIRM!");
	});

	//
	$(document).on("click", ".jsHistoryTimeOff", function (e) {
		//
		e.preventDefault();
		//
		let requestId = $(this).closest("tr").data("id");
		//
		Modal(
			{
				Id: "jsTimeOffHistory",
				Title: "Time-off History",
				Body: ` 
                <div id="jsData">

                </div>
                <div class="tabel-responsive">
                    <table class="table table-striped csCustomTableHeader">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Details</th>
                                <th>Action</th>
                                <th>Comment</th>
                                <th>Action Taken</th>
                            </tr>
                        </thead>
                        <tbody id="jsTimeOffHistoryTable"></tbody>
                    </table>
                </div>`,
				Loader: "jsTimeOffHistoryLoader",
			},
			() => {
				//
				ml(true, "jsTimeOffHistoryLoader");
				//
				$("#jsPolicyHistoryTable").html("");
				//
				$.post(
					handlerURL,
					Object.assign(
						{
							action: "get_request_history",
							companyId: companyId,
							employerId: employerId,
							employeeId: employeeId,
						},
						{
							requestId: requestId,
						}
					),
					(resp) => {
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
						$("#jsData").html(
							`<div class="row">
                            <div class="col-sm-3">
                                ${$(this)
								.closest("tr")
								.find(".employee-info")
								.parent()
								.html()}
                            </div>
                            <div class="col-sm-4">
                                ${$(this)
								.closest("tr")
								.find(".upcoming-time-info")
								.parent()
								.html()}
                            </div>
                            <div class="clearfix"></div>
                            <hr />
                        </div>
                        `
						);
						//
						if (resp.Status === false) {
							alertify.alert("WARNING!", resp.Response, () => { });
							//
							ml(false, "jsTimeOffHistoryLoader");
							//
							return;
						}
						//
						let rows = "";
						//
						if (resp.Data.length === 0) {
							rows = `
                            <tr>
                                <td colspan="5">
                                    <p class="alert alert-info text-center">${resp.Response}</p>
                                </td>
                            </tr>
                        `;
						} else {
							//
							resp.Data.map((v) => {
								//
								let note = JSON.parse(v.note);
								//
								let act = "",
									comment = "-";
								if (v.action == "create") {
									act = "Time-off created.";
								} else {
									if (note.status !== undefined) {
										//
										if (note.status == "archive")
											act = "Time-off marked as archive";
										else if (note.status == "activate")
											act = "Time-off marked as active";
										else if (note.status == "approved" && note.canApprove == 1)
											act = "Time-off approved 100%";
										else if (note.status == "approved" && note.canApprove == 0)
											act = "Time-off approved 50%";
										else if (note.status == "rejected" && note.canApprove == 1)
											act = "Time-off rejected 100%";
										else if (note.status == "rejected" && note.canApprove == 0)
											act = "Time-off rejected 50%";
										else if (note.status == "pending" && note.canApprove == 0)
											act = "Time-off updated";
										else if (note.status == "cancelled")
											act = "Time-off cancelled";

										//
										if (note.comment != undefined && note.comment != "")
											comment = note.comment;
									} else {
										act = "";
									}
									if (v.action == "update" && act == "")
										act = "Time-off updated.";
								}
								//
								rows += `<tr>`;
								rows += `<td>`;
								rows += '        <div class="employee-info">';
								rows += "            <figure>";
								rows += `                <img src="${getImageURL(
									v.image
								)}" class="img-circle emp-image" />`;
								rows += "            </figure>";
								rows += '            <div class="text">';
								rows += `                <h4>${v.first_name} ${v.last_name} </h4>`;
								rows += `                <p>${remakeEmployeeName(v, false)}</p>`;
								rows += `                <p><a href="${baseURL}employee_profile/${v.userId
									}" target="_blank">Id: ${getEmployeeId(
										v.userId,
										v.employee_number
									)}</a></p>`;
								rows += "            </div>";
								rows += "        </div></td>";
								rows += `                <td>`;
								//
								if (note.details !== undefined) {
									rows += `                   <div class="upcoming-time-info">            
                        <div class="icon-image">                   
                            <img src="${baseURL}assets/images/upcoming-time-off-icon.png" class="emp-image" alt="emp-1">             
                        </div>             
                        <div class="text">                  
                            <h4>${note.details.startDate == note.details.endDate
											? moment(note.details.startDate).format(
												timeoffDateFormat
											)
											: moment(note.details.startDate).format(
												timeoffDateFormat
											) +
											" - " +
											moment(note.details.endDate).format(
												timeoffDateFormat
											)
										}</h4>                  
                            <span>${note.details.policyTitle}</span><br />          
                            <span>${get_array_from_minutes(note.details.time, v.user_shift_hours, 'H:M').text}</span>
                        </div>       
                    </div>`;
								}
								rows += `                </td>`;
								rows += `                <td>${act}</td>`;
								rows += `                <td>${comment}</td>`;
								rows += `                <td>${moment(v.created_at).format(timeoffDateFormatWithTime)}</td>`;
								rows += `            </tr>`;

							});
						}

						//
						$("#jsTimeOffHistoryTable").html(rows);
						//
						ml(false, "jsTimeOffHistoryLoader");
					}
				);
			}
		);
	});

	//
	function getApproverSids(history) {
		if (history.length == 0) return "";
		//
		let arr = [];
		//
		history.map((his) => {
			//
			if ($.inArray(his.userId, arr) !== -1) return "";
			arr.push(his.userId);
			//
		});
		//
		return arr;
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
				msg += ` has approved the time-off at ${moment(his.created_at).format(
					timeoffDateFormatWithTime
				)}`;
				il = '<i class="fa fa-check-circle text-success"></i>';
			} else if (action.status == "rejected") {
				msg += ` has rejected the time-off at ${moment(his.created_at).format(
					timeoffDateFormatWithTime
				)}`;
				il = '<i class="fa fa-times-circle text-danger"></i>';
			} else if (action.status == "cancelled" || action.status == "cancel") {
				msg += ` has cancelled the time-off at ${moment(his.created_at).format(
					timeoffDateFormatWithTime
				)}`;
				il = '<i class="fa fa-times-circle text-danger"></i>';
			}
			//
			if ($.inArray(his.userId, arr) !== -1) return "";
			arr.push(his.userId);
			//
			rows += `
                    <div class="csApproverBox" title="Approver" data-content="${msg}">
                        <img src="${his.image == null || his.image == ""
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

	//
	$(document).on('click', '.jsEditNote', function () {
		let request_sid = $(this).closest('.jsBox').data('id');
		let employeeSid = $(this).attr('data-empSid');
		let obj = {
			action: 'get_employee_note',
			companyId: companyId,
			employerId: employerId,
			employeeId: employeeSid,
			requestId: request_sid
		};
		//
		$.post(
			handlerURL,
			obj,
			function (resp) {
				$("#jsRequestSid").val(request_sid);
				$("#jsEmployeeSid").val(employeeSid);
				$("#jsNoteSection").val(resp.Comment);
				$("#jsAddNoteModal").modal("show");
			}
		);

	});

	//
	$(document).on('click', '#jsSaveEmployeeNote', function () {
		//
		$('#jsAddNoteModalLoader').show();
		// 
		if ($("#jsNoteSection").val().trim().length == 0) {
			alertify.alert("Notice", "Comment is required!")
		} else {
			$('#jsSaveEmployeeNote').prop('disabled', true);
			//
			let obj = {
				action: 'update_employee_note',
				companyId: companyId,
				employerId: employerId,
				employeeId: $("#jsEmployeeSid").val(),
				requestId: $("#jsRequestSid").val(),
				comment: $("#jsNoteSection").val()
			};
			//
			$.post(
				handlerURL,
				obj,
				function (resp) {
					alertify.alert(
						'SUCCESS!',
						resp.Response,
						function () {
							$("#jsAddNoteModal").modal('hide');
							fetchTimeOffs();
						}
					)
				}
			);
		}
	});
});