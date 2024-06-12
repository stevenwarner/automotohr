$(function timeSheet() {
	//
	let XHR = null;

	let obj = {};

	$(".jsEditTimeSheet").click(function (event) {
		//
		event.preventDefault();

		//
		obj = $(this).closest("tr").data();

		showTimeSheetModal();
	});

	$(".jsAddTimeSheet").click(function (event) {
		//
		event.preventDefault();

		//
		obj = $(this).closest("tr").data();
		obj.id = 0;

		showTimeSheetModal();
	});

	function generateRow() {
		const rowId = getRandomCode();
		let html = `
        <div class="row jsEventRow" data-id="${rowId}">
            <br>
            <div class="col-sm-3">
                <label>Event type</label>
                <select name="event_type_${rowId}" class="form-control">
                    <option value="clocked_in_out">
                        Clock in/out
                    </option>
                    <option value="break_in_out">
                        Break start/end
                    </option>
                </select>
            </div>
            <div class="col-sm-3">
                <label>Start time</label>
                <input type="text" name="start_time_${rowId}" class="form-control jsTimeField" />
            </div>
            <div class="col-sm-3">
                <label>End time</label>
                <input type="text" name="end_time_${rowId}" class="form-control jsTimeField" />
            </div>
			<div class="col-sm-3">
				<label><br /></label>
				<button class="btn btn-red jsDeleteEventRow" type="button">
					<i class="fa fa-trash"></i>
				</button>
			</div>
        `;

		html += "</div>";

		$("#jsTimeSheetModalBody .panel-body").append(html);

		$(`[name="event_type_${rowId}"]`).rules("add", {
			required: true,
		});
		$(`[name="start_time_${rowId}"]`).rules("add", {
			required: true,
		});

		$(`[name="end_time_${rowId}"]`).rules("add", {
			required: true,
		});

		applyTimePicker();
	}

	function showTimeSheetModal() {
		//
		Modal(
			{
				Id: "jsTimeSheetModal",
				Loader: "jsTimeSheetModalLoader",
				Title:
					obj.id === 0
						? "Add"
						: "Edit" +
						  " time sheet for " +
						  moment(obj.date).format("MM/DD/Y"),
				Body: '<div id="jsTimeSheetModalBody"></div>',
			},
			getBody
		);
	}

	function getBody() {
		if (XHR !== null) {
			XHR.abort();
		}
		console.log("employee")
		XHR = $.ajax({
			url: baseUrl("v1/clock/timesheet/my/" + obj.id + "/" + obj.date),
			method: "get",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsTimeSheetModalBody").html(resp.view);

				ml(false, "jsTimeSheetModalLoader");

				$("#jsManageTimeSheetForm").validate({
					submitHandler: function (form) {
						const formData = [];
						if ($(".jsEventRow").length) {
							$(".jsEventRow").map(function () {
								const obj = {
									id: $(this).data("id"),
								};

								obj.startTime = $(this)
									.find(`[name="start_time_${obj.id}"]`)
									.val();
								obj.eventType = $(this)
									.find(
										`[name="event_type_${obj.id}"] option:selected`
									)
									.val();
								obj.endTime = $(this)
									.find(`[name="end_time_${obj.id}"]`)
									.val();
								formData.push(obj);
							});
						}

						const hookRef = callButtonHook(
							$(".jsManageTimeSheetBtn"),
							true,
							true
						);
						processTimeSheetLog(formData, hookRef);
					},
				});
				if ($(".jsEventRow").length) {
					$(".jsEventRow").map(function () {
						const rowId = $(this).data("id");
						$(`[name="event_type_${rowId}"]`).rules("add", {
							required: true,
						});
						$(`[name="start_time_${rowId}"]`).rules("add", {
							required: true,
						});

						$(`[name="end_time_${rowId}"]`).rules("add", {
							required: true,
						});
					});
				}

				$(".jsAddEventRow").click(function (event) {
					//
					event.preventDefault();

					generateRow();
				});

				$(document).on("click", ".jsDeleteEventRow", function (event) {
					//
					event.preventDefault();
					//
					const id = $(this).closest(".row").data("id");

					return _confirm(
						"Do you want to delete the selected event row? It is not revertible.",
						function () {
							deleteAttendanceLog(id);
						}
					);
				});

				applyTimePicker();
			});
	}

	function deleteAttendanceLog(logId) {
		$.ajax({
			url: baseUrl("v1/clock/timesheet/log/" + logId),
			method: "delete",
		})

			.fail(handleErrorResponse)
			.done(function (resp) {
				$(`.jsEventRow[data-id="${logId}"]`).remove();
			});
	}

	function processTimeSheetLog(formData, hookRef) {
		if (XHR !== null) {
			return;
		}
		$.ajax({
			url: baseUrl("v1/clock/timesheet/my/" + obj.id + "/" + obj.date),
			method: "post",
			data: {
				logs: formData,
			},
		})
			.always(function () {
				XHR = null;
				callButtonHook(hookRef, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.reload();
				});
			});
	}

	function applyTimePicker() {
		$(".jsTimeField").timepicker({
			timeFormat: "h:mm p",
			dynamic: false,
			dropdown: false,
			scrollbar: false,
		});
	}

	//
	$(".jsDateRangePicker").daterangepicker({
		showDropdowns: true,
		autoApply: true,
		locale: {
			format: "MM/DD/YYYY",
			separator: " - ",
		},
	});
});
