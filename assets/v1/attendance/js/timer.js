$(function markAttendance() {
	/**
	 * holds the XHR
	 */
	let XHR = null;
	/**
	 * holds the initial value
	 */
	let initialDate;
	/**
	 * holds the initial value
	 */
	let initialDateDuration;
	/**
	 * holds the initial value
	 */
	let timeZone;
	/**
	 * holds the timer ref
	 */
	let timerREF;
	/**
	 * holds the clock ref
	 */
	let clockREF;
	/**
	 * holds the attendance reference
	 */
	let attendanceObj = {};

	/**
	 * capture the mark attendance event
	 */
	$(document).on("click", ".jsAttendanceBtn", function (event) {
		event.preventDefault();
		event.stopPropagation();
		//
		const eventType = $(this).data("type");
		return _confirm(
			"Do you want to " + getType(eventType + "_confirm") + "?",
			function () {
				if (
					eventType === "clocked_in" &&
					attendanceObj.job_sites.length
				) {
					// check and show
					showJobSitesModal();
				} else if (
					eventType === "break_started" &&
					attendanceObj.allowed_breaks.length
				) {
					// check and show
					showBreaksModal();
				} else {
					checkLocationPosition(eventType);
				}
			}
		);
	});

	/**
	 * show the job site selection modal
	 */
	function showJobSitesModal() {
		Modal(
			{
				Id: "jsJobSiteModal",
				Title: "Select a job site.",
				Loader: "jsJobSiteModalLoader",
				Body: generateJobSiteBody(),
			},
			function () {
				ml(false, "jsJobSiteModalLoader");
				$("#jsJobSiteModalBody").submit(function (event) {
					event.preventDefault();
					checkLocationPosition(
						"clocked_in",
						$(".jsJobSiteModalSelect option:selected").val()
					);
					$("#jsJobSiteModal").find(".jsModalCancel").click();
				});
			}
		);
	}

	/**
	 * show the breaks selection modal
	 */
	function showBreaksModal() {
		Modal(
			{
				Id: "jsBreaksModal",
				Title: "Select a break.",
				Loader: "jsBreaksModalLoader",
				Body: generateBreaksBody(),
			},
			function () {
				ml(false, "jsBreaksModalLoader");
				$("#jsBreaksModalBody").submit(function (event) {
					event.preventDefault();
					//
					const breakTimeId = $(
						".jsBreaksModelSelect option:selected"
					).val();

					// check for breaks count
					if (
						attendanceObj.allowed_breaks &&
						attendanceObj.allowed_breaks.length ==
							attendanceObj.breaks
					) {
						return _error("You have already taken allowed breaks.");
					}

					// get the break
					let selectedBreak = attendanceObj.allowed_breaks.filter(
						function (currentBreak) {
							return currentBreak["id"] == breakTimeId;
						}
					);
					// check on break start time
					if (
						selectedBreak[0]["start_time"] &&
						selectedBreak[0]["start_time"] != "" &&
						moment() <
							moment(selectedBreak[0]["start_time"], "hh:mm a")
					) {
						return _error(
							'You can not start break before "' +
								selectedBreak[0]["start_time"] +
								'".'
						);
					}
					// check the end time
					if (
						selectedBreak[0]["end_time"] &&
						selectedBreak[0]["end_time"] != "" &&
						moment() >
							moment(selectedBreak[0]["end_time"], "hh:mm a")
					) {
						return _error(
							'You can not start break after "' +
								selectedBreak[0]["end_time"] +
								'".'
						);
					}
					//
					checkLocationPosition("break_started", breakTimeId);
					$("#jsBreaksModal").find(".jsModalCancel").click();
				});
			}
		);
	}

	/**
	 * generates body for the job sites
	 *
	 * @returns string
	 */
	function generateJobSiteBody() {
		let html = "";

		html += '<div class="container" id="jsJobSiteModalBody">';
		html += '	<br /><div class="row">';
		html += '		<div class="col-sm-12">';
		html += '			<form id="jsJobSiteModalForm">';
		html += '				<div class="form-group">';
		html += '					<label class="tex-medium">';
		html += "						Please select a job site";
		html += '						<strong class="text-danger"></strong>';
		html += "					</label>";
		html += '					<select class="form-control jsJobSiteModalSelect">';
		html += '						<option value="0">Clock in without job site</option>';
		attendanceObj.job_sites.map(function (jobSite) {
			html +=
				'						<option value="' +
				jobSite.sid +
				'">' +
				jobSite.site_name +
				"</option>";
		});
		html += "					</select>";
		html += "				</div>";
		html += '				<div class="form-group text-right">';
		html += '					<button class="btn btn-success">';
		html += "						Clock In";
		html += "					</button>";
		html += "				</div>";
		html += "			</form>";
		html += "		</div>";
		html += "	</div>";
		html += "</div>";

		return html;
	}

	/**
	 * generates body for the job sites
	 *
	 * @returns string
	 */
	function generateBreaksBody() {
		let html = "";

		html += '<div class="container" id="jsBreaksModalBody">';
		html += '	<br /><div class="row">';
		html += '		<div class="col-sm-12">';
		html += '			<form id="jsBreaksModelForm">';
		html += '				<div class="form-group">';
		html += '					<label class="tex-medium">';
		html += "						Please select a break";
		html += '						<strong class="text-danger"></strong>';
		html += "					</label>";
		html += '					<select class="form-control jsBreaksModelSelect">';
		attendanceObj.allowed_breaks.map(function (v0) {
			//
			let duration = v0.duration + "m";
			if (v0.start_time) {
				duration = v0.start_time + " - " + v0.end_time;
			}
			//
			let isDisabled = isBreakAlreadyTaken(v0["id"]);
			html +=
				"						<option " +
				(isDisabled ? "disabled" : "") +
				' value="' +
				v0.id +
				'">' +
				v0.break +
				" (" +
				duration +
				")</option>";
		});
		html += "					</select>";
		html += "				</div>";
		html += '				<div class="form-group text-right">';
		html += '					<button class="btn btn-success">';
		html += "						Start Break";
		html += "					</button>";
		html += "				</div>";
		html += "			</form>";
		html += "		</div>";
		html += "	</div>";
		html += "</div>";

		return html;
	}

	/**
	 * fetch the attendance
	 */
	function fetchAttendance(syncInProgress) {
		if (!syncInProgress) {
			clearView();
		}
		// get the new attendance
		$.ajax({
			url: baseUrl("v1/clock"),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				attendanceObj = resp;
				//
				clearInterval(timerREF);
				clearInterval(clockREF);
				//
				if (resp.state === "" || resp.state == "clocked_out") {
					clearView();
				} else {
					initialDate = resp.clock_time;
					initialDateDuration = resp.time;
					timeZone = resp.timezone;
					timerREF = setInterval(handleTimer, 1000);
				}
				//
				showButtons(resp.state);
				//for clocked out event
				if (resp.state == "clocked_out") {
					showTimer(resp.time);
				}
				//
				clockREF = setInterval(startClock, 1000);

				if (resp.blocked) {
					$(".jsAttendanceBTNs").html("");
					$(".jsAttendanceClockHeaderBTNs").html("");
				}
			});
	}

	/**
	 * marks the attendance
	 * @param {string} eventType
	 * @param {string} latitude
	 * @param {string} longitude
	 * @param {number} jobSiteId
	 * @param {bool} confirmed
	 */
	function markAttendance(
		eventType,
		latitude,
		longitude,
		jobSiteId,
		confirmed
	) {
		// check if the call is already been made
		if (XHR !== null) {
			return;
		}
		const buttonRef = callButtonHook(
			$(`.jsAttendanceBtn[data-type="${eventType}"]`),
			true
		);
		//
		const passData = {
			type: eventType,
			latitude,
			longitude,
			job_site: jobSiteId,
		};
		// when confirmed
		if (confirmed !== undefined) {
			passData.confirmed = confirmed;
		}
		//
		XHR = $.ajax({
			url: baseUrl("v1/clock/mark"),
			method: "POST",
			data: JSON.stringify(passData),
			headers: { "content-type": "application/json" },
		})
			.always(function () {
				callButtonHook(buttonRef, false);
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				console.log(resp)
				// check for confirmation
				if (resp.confirm) {
					return _confirm(resp.msg, function () {
						markAttendance(
							eventType,
							latitude,
							longitude,
							jobSiteId,
							true
						);
					});
				}
				//
				_success("You have successfully " + getType(eventType) + ".");
				fetchAttendance();
			});
	}

	/**
	 * clear the view
	 */
	function clearView() {
		// flush the old value
		initialDate = "";
		initialDateDuration = "";
		// clear the interval
		clearInterval(timerREF);
		//
		$(".jsAttendanceClockHour").html("00");
		$(".jsAttendanceClockMinute").html("00");
		$(".jsAttendanceClockSeconds").html("00");
		$(".jsAttendanceClockSeparator").html(":");
		//
		$(".jsAttendanceBTNs").html("");
		$(".jsAttendanceClockHeaderBTNs").html("");
	}

	/**
	 * handles timer
	 */
	function handleTimer() {
		if (!initialDate) {
			$(".jsAttendanceClockHour").html("00");
			$(".jsAttendanceClockMinute").html("00");
			$(".jsAttendanceClockSeconds").html("00");
			$(".jsAttendanceClockSeparator").html(":");

			return;
		}
		// clock date
		const clockDateObj = moment
			.utc(initialDate).tz(timeZone)
			.subtract(initialDateDuration, "seconds");
		const todayDate = moment.utc().tz(timeZone);
		// const todayDate = moment.utc("2024-06-03 00:42:25");
		console.log(todayDate);
		// get the difference
		const diff = todayDate.diff(clockDateObj);
		//
		const dt = moment(diff).utc().tz(timeZone);
		//
		$(".jsAttendanceClockHour").html(dt.format("HH"));
		$(".jsAttendanceClockMinute").html(dt.format("mm"));
		$(".jsAttendanceClockSeconds").html(dt.format("ss"));
		$(".jsAttendanceClockSeparator").html(":");
	}
	/**
	 * handles timer
	 */
	function showTimer(duration) {
		const obj = convertSeconds(duration);
		//
		$(".jsAttendanceClockHour").html(obj.hours);
		$(".jsAttendanceClockMinute").html(obj.minutes);
		$(".jsAttendanceClockSeconds").html(obj.seconds);
		$(".jsAttendanceClockSeparator").html(":");
	}

	/**
	 * start the system clock timer
	 */
	function startClock() {
		// get the current time in user tz
		let newTime = moment.tz(attendanceObj.timezone);
		let atteTime = moment.tz(
			attendanceObj.timerDateTime,
			attendanceObj.timezone
		);
		// Calculate the time elapsed since the start date
		const elapsedTime = newTime.diff(attendanceObj.timerDateTime);

		atteTime.add(elapsedTime, "milliseconds");

		//
		$(".jsAttendanceCurrentClockDateTime").text(
			atteTime.format(`MMM D YYYY, ddd HH:mm:ss`)
		);
		$(".jsAttendanceLoader").hide();
	}

	/**
	 * show buttons on screen
	 * @param {string} state
	 */
	function showButtons(state) {
		//
		let buttons = "";
		//
		if (state === "clocked_in" || state === "break_ended") {
			// show break start and clock out buttons
			buttons += generateButton("break_start");
			buttons += generateButton("clocked_out");
		} else if (state === "break_started") {
			// show break end and clock out buttons
			buttons += generateButton("break_end");
			buttons += generateButton("clocked_out");
		} else {
			// show clock in
			buttons += generateButton("clocked_in");
		}
		//
		$(".jsAttendanceBTNs").html(buttons);
		$(".jsAttendanceClockHeaderBTNs").html(buttons);
	}

	/**
	 * generates the attendance button
	 *
	 * @param {string} type
	 * @returns
	 */
	function generateButton(type) {
		//
		let html = "";
		//
		if (type === "clocked_in") {
			html +=
				'<button class="btn btn-orange jsAttendanceBtn" data-type="clocked_in">';
			html +=
				'	<i class="fa fa-play-circle csF16" aria-hidden="true"></i>';
			html += "	&nbsp;Clock in";
			html += "</button>";
		} else if (type === "clocked_out") {
			html +=
				'&nbsp;<button class="btn btn-red jsAttendanceBtn" data-type="clocked_out">';
			html +=
				'	<i class="fa fa-stop-circle csF16" aria-hidden="true"></i>';
			html += "	&nbsp;Clock out";
			html += "</button>";
		} else if (type === "break_start") {
			html +=
				'&nbsp;<button class="btn btn-black jsAttendanceBtn" data-type="break_started">';
			html +=
				'	<i class="fa fa-pause-circle csF16" aria-hidden="true"></i>';
			html += "	&nbsp;Break start";
			html += "</button>";
		} else if (type === "break_end") {
			html +=
				'&nbsp;<button class="btn btn-yellow jsAttendanceBtn" data-type="break_ended">';
			html += '	<i class="fa fa-stop csF16" aria-hidden="true"></i>';
			html += "	&nbsp;Break end";
			html += "</button>";
		}

		return html;
	}

	/**
	 * check location permission
	 */
	function checkLocationPosition(eventType, jobSiteId = null) {
		if (navigator.geolocation) {
			/**
			 * Retrieves lat lon
			 * @param {object} position
			 */
			function onSuccess(position) {
				markAttendance(
					eventType,
					position.coords.latitude,
					position.coords.longitude,
					jobSiteId
				);
			}

			function onFail() {
				return alertify.alert(
					"Location permission denied!",
					generatePermissionFail()
				);
			}
			navigator.geolocation.getCurrentPosition(onSuccess, onFail);
		} else {
			markAttendance(eventType, 0, 0);
			console.log(
				"Geolocation is required for this page, but your browser doesn't support it. Try it with a browser that does"
			);
		}
	}

	/**
	 * generates location permission text
	 * @returns
	 */
	function generatePermissionFail() {
		let html = "";

		html +=
			'<p>The "location" permission is denied on your browser. Please allow the location before you can mark your attendance \
				Please follow the following steps to allow location permission.</p><br /> \
				<ol> \
  				<li>On your computer, open Chrome <img src="//storage.googleapis.com/support-kms-prod/Y57p9LEW3v1cnw4Svh3a53DOnyRPFkiDfTDc" width="18" height="18" alt="Chrome" data-mime-type="image/png">.</li> \
  				<li>At the top right, click More <img src="//lh3.googleusercontent.com/E2q6Vj9j60Dw0Z6NZFEx5vSB9yoZJp7C8suuvQXVA_2weMCXstGD7JEvNrzX3wuQrPtL=w36-h36" width="18" height="18" alt="More" data-mime-type="image/png" data-alt-src="//lh3.googleusercontent.com/E2q6Vj9j60Dw0Z6NZFEx5vSB9yoZJp7C8suuvQXVA_2weMCXstGD7JEvNrzX3wuQrPtL"> <img src="//lh3.googleusercontent.com/3_l97rr0GvhSP2XV5OoCkV2ZDTIisAOczrSdzNCBxhIKWrjXjHucxNwocghoUa39gw=w36-h36" width="18" height="18" alt="and then" data-mime-type="image/png" data-alt-src="//lh3.googleusercontent.com/3_l97rr0GvhSP2XV5OoCkV2ZDTIisAOczrSdzNCBxhIKWrjXjHucxNwocghoUa39gw"> <strong>Settings</strong>.</li> \
  				<li>Click <strong>Privacy and security</strong>&nbsp;<img src="//lh3.googleusercontent.com/3_l97rr0GvhSP2XV5OoCkV2ZDTIisAOczrSdzNCBxhIKWrjXjHucxNwocghoUa39gw=w36-h36" width="18" height="18" alt="and then" data-mime-type="image/png" data-alt-src="//lh3.googleusercontent.com/3_l97rr0GvhSP2XV5OoCkV2ZDTIisAOczrSdzNCBxhIKWrjXjHucxNwocghoUa39gw">&nbsp;<strong>Site Settings</strong>.</li> \
  				<li data-outlined="false" class="">Click <strong>Location</strong>.</li> \
  				<li>Choose the option you want as your default setting.</li> \
			</ol>';

		return html;
	}

	/**
	 * convert seconds to time
	 * @param {number} seconds
	 * @returns
	 */
	function convertSeconds(seconds) {
		//
		const obj = {
			hours: Math.floor(seconds / 3600),
			minutes: Math.floor((seconds % 3600) / 60),
			seconds: seconds % 60,
		};
		//
		obj.hours =
			obj.hours.toString().length === 1 ? "0" + obj.hours : obj.hours;
		obj.minutes =
			obj.minutes.toString().length === 1
				? "0" + obj.minutes
				: obj.minutes;
		obj.seconds =
			obj.seconds.toString().length === 1
				? "0" + obj.seconds
				: obj.seconds;

		return obj;
	}

	/**
	 * get the event type
	 * @param {string} type
	 * @returns
	 */
	function getType(type) {
		//
		const types = {
			clocked_in: "Clocked In",
			clocked_out: "Clocked Out",
			break_started: "Started your break",
			break_ended: "Ended your break",

			clocked_in_confirm: "clock in",
			clocked_out_confirm: "clock out",
			break_started_confirm: "start your break",
			break_ended_confirm: "end your break",
		};
		//
		return types[type];
	}

	/**
	 * check if break already taken
	 * @param {string} breakId
	 * @returns bool
	 */
	function isBreakAlreadyTaken(breakId) {
		let isSelected = false;
		const arraySize = attendanceObj.breaks.length;
		//
		for (let i = 0; i < arraySize; i++) {
			const cp = attendanceObj.breaks[i];
			if (cp["id"] == breakId) {
				isSelected = true;
				break;
			}
		}
		return isSelected;
	}

	// sync clock in every 10 seconds
	setInterval(function () {
		fetchAttendance(true);
	}, 10000);
	// initiate call
	clockREF = setInterval(startClock, 1000);
	// check attendance
	fetchAttendance();
});
