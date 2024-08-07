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

	$(".jsViewTimeSheet").click(function (event) {
		//
		event.preventDefault();

		showHistoryModal($(this).closest("tr").data());
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
	function showHistoryModal(obj) {
		Modal(
			{
				Id: "jsTimeSheetHistoryModal",
				Loader: "jsTimeSheetHistoryModalLoader",
				Title: "History for " + moment(obj.date).format("MM/DD/Y"),
				Body: '<div id="jsTimeSheetHistoryModalBody"></div>',
			},
			function () {
				getTimeSheetHistory(obj);
			}
		);
	}

	function getBody() {
		if (XHR !== null) {
			XHR.abort();
		}

		XHR = $.ajax({
			url: baseUrl("v1/clock/timesheet/" + obj.id + "/" + obj.date),
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
								obj.employeeId = profileUserInfo.userId;
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

	function getTimeSheetHistory(obj) {
		if (XHR !== null) {
			XHR.abort();
		}

		XHR = $.ajax({
			url: baseUrl("v1/clock/timesheet/history/" + obj.id),
			method: "get",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				//
				$("#jsTimeSheetHistoryModalBody").html(resp.view);

				if (resp.locations && resp.locations.length > 0) {
					resp.locations.forEach((v0) => {
						makeLocationMap("map_" + v0.id, v0);
					});
				}

				ml(false, "jsTimeSheetHistoryModalLoader");
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
			url: baseUrl("v1/clock/timesheet/" + obj.id + "/" + obj.date),
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

	$(".jsSelectAll").click(function () {
		$(".jsSingleSelect").prop("checked", $(".jsSelectAll").prop("checked"));
	});

	$(".jsSingleSelect").click(function () {
		$(".jsSelectAll").prop(
			"checked",
			$(".jsSingleSelect:checked").length === $(".jsSingleSelect").length
		);
	});

	$(".jsApproveTimeSheet").click(function (event) {
		//
		event.preventDefault();
		//

		processSheetStatus(1);
	});
	$(".jsUnApproveTimeSheet").click(function (event) {
		//
		event.preventDefault();
		//

		processSheetStatus(0);
	});

	function processSheetStatus(type) {
		if ($(".jsSingleSelect:checked").length === 0) {
			return _success("Please select at least one employee.");
		}
		const ids = [];
		$(".jsSingleSelect:checked").map(function () {
			ids.push($(this).val());
		});

		if (XHR !== null) {
			return;
		}

		const hookRef = callButtonHook(
			type == 1 ? $(".jsApproveTimeSheet") : $(".jsUnApproveTimeSheet"),
			true
		);
		$.ajax({
			url: baseUrl("v1/clock/timesheet/status"),
			method: "post",
			data: {
				ids: ids,
				status: type,
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

	/**
	 * draw map
	 */
	function makeLocationMap(containerId, location) {
		let map,
			markers = [],
			coords = [],
			latlng = [];

		location.lat = parseFloat(location.lat);
		location.lng = parseFloat(location.lng);

		if (location.lat_2 && !isNaN(location.lat_2)) {
			location.lat_2 = parseFloat(location.lat_2);
			location.lng_2 = parseFloat(location.lng_2);
		}

		//
		map = new google.maps.Map(document.getElementById(containerId), {
			center: {
				lat: location.lat,
				lng: location.lng,
			},
			mapTypeId: google.maps.MapTypeId.ROADMAP,
		});

		addMarker({
			markers,
			coords,
			map: map,
			position: {
				lat: location["lat"],
				lng: location["lng"],
			},
			title: location.title,
		});
		//
		latlng.push(new google.maps.LatLng(location.lat, location.lng));

		if (location.lat_2 && !isNaN(location.lat_2)) {
			addMarker({
				markers,
				coords,
				map: map,
				position: {
					lat: location["lat_2"],
					lng: location["lng_2"],
				},
				title: location.title,
			});
			//
			latlng.push(new google.maps.LatLng(location.lat_2, location.lng_2));
			let line = new google.maps.Polyline({
				path: coords,
				geodesic: true,
				strokeColor: "#FF0000",
				strokeOpacity: 1.0,
				strokeWeight: 2,
			});

			line.setMap(map);
		}

		if (location["constraint"]) {
			// Add circle overlay for radius
			const circle = new google.maps.Circle({
				map: map,
				center: {
					lat: parseFloat(location.constraint.lat),
					lng: parseFloat(location.constraint.lng),
				},
				radius: parseInt(50000 || location.constraint.allowed), // Radius in meters
				strokeColor: "red", // Circle outline color
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: "red", // Circle fill color
				fillOpacity: 0.35,
			});
			// Add info window
			const infoWindow = new google.maps.InfoWindow({
				content: location.constraint.title,
			});
			// Open info window when the circle is clicked
			google.maps.event.addListener(circle, "click", function (event) {
				infoWindow.setPosition(event.latLng);
				infoWindow.open(this.map);
			});

			latlng.push(
				new google.maps.LatLng(
					parseFloat(location.constraint.lat),
					parseFloat(location.constraint.lng)
				)
			);
		}

		let LatLngBounds = new google.maps.LatLngBounds();

		latlng.forEach((element) => {
			LatLngBounds.extend(element);
		});

		map.fitBounds(LatLngBounds);

		google.maps.event.trigger(map, "resize");
	}

	function addMarker(options) {
		let marker = new google.maps.Marker(options);

		options.markers.push(marker);
		options.coords.push({
			lat: options.position.lat,
			lng: options.position.lng,
		});
	}

	$(document).on("click", ".jsToggleMapView", function (event) {
		//
		event.preventDefault();
		const sid = $(this).data("id");
		$(".mapRow" + sid).toggleClass("hidden");
	});

	//
	$(".jsDateRangePicker")
		showDropdowns: true,
		autoApply: true,
		locale: {
			format: "MM/DD/YYYY",
			separator: " - ",
		},
	});
});
