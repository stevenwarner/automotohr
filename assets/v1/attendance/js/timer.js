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
	 * holds the timer ref
	 */
	let timerREF;
	/**
	 * holds the clock ref
	 */
	let clockREF;

	/**
	 * capture the mark attendance event
	 */
	$(document).on("click", ".jsAttendanceBtn", function (event) {
		event.preventDefault();
		//
		const eventType = $(this).data("type");
		return _confirm(
			"Do you want to " + getType(eventType + "_confirm") + "?",
			function () {
				checkLocationPosition(eventType);
			}
		);
	});

	/**
	 * fetch the attendance
	 */
	function fetchAttendance(syncInProgress) {
		if (!syncInProgress) {
			clearView();
		}
		// get the new attendance
		$.ajax({
			url: apiURL + "attendance",
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				//
				clearInterval(timerREF);
				clearInterval(clockREF);
				//
				if (resp.state === "" || resp.state == "clocked_out") {
					clearView();
				} else {
					initialDate = resp.clock_time;
					initialDateDuration = resp.time;
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
			});
	}

	/**
	 * marks the attendance
	 * @param {string} eventType
	 * @param {string} latitude
	 * @param {string} longitude
	 */
	function markAttendance(eventType, latitude, longitude) {
		// check if the call is already been made
		if (XHR !== null) {
			return;
		}
		let ref = _showNotification(
			"Please wait, while we are processing your request."
		);
		//
		XHR = $.ajax({
			url: apiURL + "attendance/mark",
			method: "POST",
			data: JSON.stringify({ type: eventType, latitude, longitude }),
			headers: { "content-type": "application/json" },
		})
			.always(function () {
				closeAlert(ref);
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function () {
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
			.utc(initialDate)
			.subtract(initialDateDuration, "seconds");
		const todayDate = moment.utc();
		// get the difference
		const diff = todayDate.diff(clockDateObj);
		//
		const dt = moment(diff).utc();
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
		//
		$(".jsAttendanceCurrentClockDateTime").text(
			moment().format("MMM D YYYY, ddd HH:mm:ss")
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
			html += "	&nbsp;Break end & clock in";
			html += "</button>";
		}

		return html;
	}

	/**
	 * check location permission
	 */
	function checkLocationPosition(eventType) {
		if (navigator.geolocation) {
			/**
			 * Retrieves lat lon
			 * @param {object} position
			 */
			function onSuccess(position) {
				markAttendance(
					eventType,
					position.coords.latitude,
					position.coords.longitude
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

	// sync clock in every 10 seconds
	setInterval(function () {
		fetchAttendance(true);
	}, 10000);
	// initiate call
	clockREF = setInterval(startClock, 1000);
	// check attendance
	fetchAttendance();
});
