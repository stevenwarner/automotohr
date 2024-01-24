$(function () {
	let start = moment().subtract(29, "days");
	let end = moment();
	// // make select to select2
	// $(".jsSelect2").select2({
	// 	closeOnSelect: false,
	// 	placeholder: "Placeholder",
	// 	allowHtml: true,
	// 	allowClear: true,
	// 	tags: true,
	// });

	//
	$('input[name="dates"]').daterangepicker({
		showDropdowns: true,
		showWeekNumbers: true,
		autoUpdateInput: false,
		open: "center",
		locale: {
			format: "MM/DD/YYYY",
		},
		applyButtonClasses: "csBtn csBtnOrange",
		cancelButtonClasses: "csBtn csBtnBlack",
	});

	$("#jsReportRange").daterangepicker(
		{
			applyButtonClasses: "csBtn csBtnOrange",
			cancelButtonClasses: "csBtn csBtnBlack",
			startDate: start,
			endDate: end,
			ranges: {
				Today: [moment(), moment()],
				Yesterday: [
					moment().subtract(1, "days"),
					moment().subtract(1, "days"),
				],
				"Last 7 Days": [moment().subtract(6, "days"), moment()],
				"Last 30 Days": [moment().subtract(29, "days"), moment()],
				"This Month": [
					moment().startOf("month"),
					moment().endOf("month"),
				],
				"Last Month": [
					moment().subtract(1, "month").startOf("month"),
					moment().subtract(1, "month").endOf("month"),
				],
			},
		},
		cb
	);

	function cb(start, end) {
		$("#jsReportRange span").html(
			start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
		);
	}

	cb(start, end);

	//
	$(".jsDateRangePicker").daterangepicker({
		showDropdowns: true,
		singleDatePicker: true,
		autoApply: true,
		locale: {
			format: "MM/DD/YYYY",
		},
	});

	$(".jsDateRangePicker").on("apply.daterangepicker", function (ev, picker) {
		window.location.href = baseUrl(
			`attendance/dashboard?date=${picker.startDate.format("MM/DD/YYYY")}`
		);
	});
});
