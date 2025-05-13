$(function Overview() {
	//
	ml(false, "jsPageLoader");

	$(".jsDeleteReportBtn").click(
		function (event) {
			event.preventDefault();
			const ref = $(this)
			const id = ref.closest(".jsReportBox").data("id");

			_confirm(
				"Are you sure you want to delete this report? This action is not revertible.",
				function () {
					deleteReport(
						id, ref
					);
				}
			);
		}
	);


	function deleteReport(id, ref) {
		//
		const _html = callButtonHook(
			ref,
			true
		);
		//
		$
			.ajax({
				url: baseUrl(`compliance_safety_reporting/report/${id}`),
				method: "DELETE",

			})
			.always(function () {
				callButtonHook(_html, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				_success(resp.message, function () {
					ref.closest(".jsReportBox").remove();
				})
			});
	}

	loadMyAssignedCoursesBarChart(reportGraphData);

	//
	function loadMyAssignedCoursesBarChart(count) {
		Highcharts.chart("jsReportsGraph", {
			chart: {
				type: "column",
			},
			title: {
				align: "left",
				text: "Reports",
			},
			accessibility: {
				announceNewData: {
					enabled: true,
				},
			},
			xAxis: {
				type: "category",
				labels: {
					style: {
						fontSize: "12px", // Change this to your desired size
					},
				},
			},
			yAxis: {
				title: {
					text: "Total number of reports",
				},
				labels: {
					style: {
						fontSize: "12px", // Change this to your desired size
					},
				},
			},
			legend: {
				enabled: false,
			},
			plotOptions: {
				series: {
					borderWidth: 0,
					dataLabels: {
						enabled: true,
						format: "{point.y}",
					},
				},
			},

			tooltip: {
				headerFormat:
					'<span style="font-size:14px">{series.name}</span><br>',
				pointFormat:
					'<span style="font-size:12px; color:{point.color}">{point.name}:</span> <b style="font-size:12px">{point.y} reports</b>',
			},

			series: [
				{
					name: "Course(s)",
					colorByPoint: true,
					point: {
						events: {
							click: function (e) {
								// window.open(e.point.url, "_blank");
								e.preventDefault();
							},
						},
					},
					data: [
						{
							name: "Pending ",
							color: "#6B8ABB",
							y: count.pending,
						},
						{
							name: "On Hold",
							color: "#ff834e",
							y: count.on_hold,
						},
						{
							name: "Completed",
							color: "#2caffe",
							y: count.completed,
						},
					],
					dataLabels: {
						style: {
							fontSize: "12px", // Change this to your desired size
						},
					},
				},
			],
		});
	}
});
