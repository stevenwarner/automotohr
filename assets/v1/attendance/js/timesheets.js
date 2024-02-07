$(function timeSheet() {
	$(".multipleSelect").select2();
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
