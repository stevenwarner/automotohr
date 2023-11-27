$(function myAttendanceDashboard() {
	/**
	 * holds the XHR
	 */
	let XHR = null;

	function getGraphs() {
		ml(true, "jsWeekGraph", "");
		//
		$.ajax({
			url: apiURL + "attendance/graph/week_worked_time",
			method: "GET",
		})
			.always(function () {
				ml(false, "jsWeekGraph");
			})
			.fail(handleErrorResponse)
			.done(function (response) {
				//
				const values = Object.values(response);
				//
				let total = 0;
				//
				values &&
					values.map(function (value) {
						total += parseFloat(value);
					});
				//
				Highcharts.chart("container", {
					chart: {
						type: "column",
					},
					title: {
						text: `You have worked for ${total}h.`,
						align: "left",
						style: {
							fontSize: "16px",
						},
					},
					xAxis: {
						categories: Object.keys(response).map(function (index) {
							return moment(index, "YYYY-MM-DD").format(
								"MMM DD YYYY, ddd"
							);
						}),
						crosshair: true,
						accessibility: {
							description: "Week days",
						},
						labels: {
							style: {
								fontSize: "12px",
							},
						},
					},
					yAxis: {
						min: 0,
						title: {
							text: "# of worked hours",
							style: {
								fontSize: "14px",
							},
						},
						labels: {
							style: {
								fontSize: "12px",
							},
						},
					},
					tooltip: {
						valueSuffix: "h",
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0,
							labels: {
								style: {
									fontSize: "40px",
								},
							},
						},
					},
					series: [
						{
							name: "Worked hours",
							data: values,
							column: {
								labels: {
									style: {
										fontSize: "40px",
									},
								},
							},
						},
					],

					colors: ["#fd7a2a"],
				});
			});
	}
	//
	getGraphs();
});
