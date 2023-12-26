$(function () {
	function getGraphs() {
		ml(true, "jsWeekGraph", "");
		//
		$.ajax({
			url: apiURL + "attendance/worked_hours",
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
						text: `${total}h`,
						align: "left",
					},
					xAxis: {
						categories: Object.keys(response),
						crosshair: true,
						accessibility: {
							description: "Week days",
						},
					},
					yAxis: {
						min: 0,
						title: {
							text: "",
						},
					},
					tooltip: {
						valueSuffix: "h",
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0,
						},
					},
					series: [
						{
							name: "Worked hours",
							data: values,
						},
					],
					colors: ["#fd7a2a"],
				});
			});
	}
	//
	getGraphs();
});
