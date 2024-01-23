$(function myAttendanceDashboard() {
	let googleCbInterval;
	let map,
		markers = [],
		coords = [];

	let graphXHR = null;
	let footprintXHR = null;
	let locations = [];
	let logs = [];
	/**
	 * get the week worked graph
	 */
	function getGraphs() {
		if (graphXHR !== null) {
			graphXHR.abort();
		}
		// starts the loader
		ml(true, "jsWeekGraph", "");
		//
		graphXHR = $.ajax({
			url: baseUrl("v1/clock/graphs/week_worked_time"),
			method: "GET",
			cache: false,
		})
			.always(function () {
				graphXHR = null;
				ml(false, "jsWeekGraph");
			})
			.fail(handleErrorResponse)
			.done(function (response) {
				// when there was empty response
				if (!Object.keys(response).length) {
					return;
				}
				//
				let workedTimeArray = [];
				let breakTimeArray = [];
				let totalTimeArray = [];
				//
				let total = 0;
				//
				$.each(response, function (i, v) {
					//
					workedTimeArray.push(
						parseFloat(
							(
								v?.workedTotalTime?.totalInnMinutes || 0.0
							).toFixed(2)
						)
					);
					breakTimeArray.push(
						parseFloat(
							(v?.breakTotalTime?.totalInnMinutes || 0.0).toFixed(
								2
							)
						)
					);
					totalTimeArray.push(
						parseFloat(
							(v?.totalTime?.totalInnMinutes || 0.0).toFixed(2)
						)
					);
					//
					total += v?.totalTime?.totalInnMinutes || 0;
				});
				//
				total = total.toFixed(0);
				//
				Highcharts.chart("container", {
					chart: {
						type: "column",
					},
					legend: {
						itemStyle: {
							fontSize: "12px",
						},
					},
					title: {
						text: `You have worked for ${convertSecondsToTime(
							total * 60
						)}.`,
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
							text: "# of worked minutes",
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
						style: {
							fontSize: "12px",
						},
						valueSuffix: "m",
						formatter(e) {
							const { x, y, colorIndex } = this;

							if (colorIndex === 0) {
								return `You have consumed breaks for ${convertSecondsToTime(
									y * 60
								)} on ${x}`;
							} else {
								return `You have worked for ${convertSecondsToTime(
									y * 60
								)} on ${x}`;
							}
						},
					},
					plotOptions: {
						series: {
							dataLabels: {
								style: {
									fontSize: "20px",
								},
							},
						},
						column: {},
					},
					series: [
						{
							name: "Break minutes",
							data: breakTimeArray,
							column: {
								labels: {
									style: {
										fontSize: "40px",
									},
								},
							},
						},
						{
							name: "Worked minutes",
							data: workedTimeArray,
							column: {
								labels: {
									style: {
										fontSize: "40px",
									},
								},
							},
						},
					],

					colors: ["red", "#fd7a2a"],
				});
			});
	}

	/**
	 * get the locations
	 */
	function getFootprints() {
		if (footprintXHR !== null) {
			footprintXHR.abort();
		}
		//
		footprintXHR = $.ajax({
			url: baseUrl("v1/clock/my/footprints/today"),
			method: "GET",
			cache: false,
		})
			.always(function () {
				footprintXHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (response) {
				if (response.logs) {
					if (
						locations.toString() !==
						response.logs.locations.toString()
					) {
						locations = response.logs.locations;
						callGoogleCB(initMap);
					}
				} else {
					$("#map").html(
						'<p class="alert alert-info text-center">No footprints yet!</p>'
					);
				}
				if (response.logs) {
					logs = response.logs.logs;
				} else {
					logs = [];
				}

				remakeEntries();
			});
	}

	/**
	 * Call to Google callback
	 * @param {reference} callTo
	 */
	function callGoogleCB(callTo) {
		if (typeof google === "undefined") {
			googleCbInterval = setInterval(function () {
				callGoogleCB(callTo);
			}, 3000);
		} else {
			clearInterval(googleCbInterval);
			callTo();
		}
	}

	/**
	 * draw map
	 */
	function initMap() {
		//
		map = new google.maps.Map(document.getElementById("map"), {
			center: {
				lat: parseFloat(locations[0].lat),
				lng: parseFloat(locations[0].lng),
			},
			mapTypeId: "roadmap",
		});

		const latlng = [];

		// // Call function to add markers
		locations.map(function (v0, i) {
			if (!v0["lat"] || !v0["lat"]) {
				return;
			}
			const options = {
				map: map,
				position: {
					lat: parseFloat(v0["lat"]),
					lng: parseFloat(v0["lng"]),
				},
				title: v0["title"],
			};
			if (i === 0) {
				options.icon = `http://maps.google.com/mapfiles/ms/icons/green-dot.png`;
			}

			addMarker(options);

			if (v0["constraint"]) {
				// Add circle overlay for radius
				var circle = new google.maps.Circle({
					map: map,
					center: {
						lat: parseFloat(v0.constraint.lat),
						lng: parseFloat(v0.constraint.lng),
					},
					radius: parseInt(50000 || v0.constraint.allowed), // Radius in meters
					strokeColor: "red", // Circle outline color
					strokeOpacity: 0.8,
					strokeWeight: 2,
					fillColor: "red", // Circle fill color
					fillOpacity: 0.35,
				});
				// Add info window
				var infoWindow = new google.maps.InfoWindow({
					content: v0.constraint.title,
				});
				// Open info window when the circle is clicked
				google.maps.event.addListener(
					circle,
					"click",
					function (event) {
						infoWindow.setPosition(event.latLng);
						infoWindow.open(this.map);
					}
				);
				latlng.push(
					new google.maps.LatLng(
						parseFloat(v0.constraint.lat),
						parseFloat(v0.constraint.lng)
					)
				);
			}
			//
			latlng.push(
				new google.maps.LatLng(
					options.position.lat,
					options.position.lng
				)
			);
		});

		let line = new google.maps.Polyline({
			path: coords,
			geodesic: true,
			strokeColor: "#FF0000",
			strokeOpacity: 1.0,
			strokeWeight: 2,
		});

		line.setMap(map);

		let latlngbounds = new google.maps.LatLngBounds();
		//
		latlng.forEach((element) => {
			latlngbounds.extend(element);
		});
		map.fitBounds(latlngbounds);
	}

	function addMarker(options) {
		let marker = new google.maps.Marker(options);

		markers.push(marker);
		coords.push({ lat: options.position.lat, lng: options.position.lng });
	}

	function remakeEntries() {
		// set the html holder
		let html = "";
		// when no entries are found
		if (!logs.length) {
			return $(".jsTimeEntriesBox tbody").html(
				'<tr><td colspan="4"><p class="alert alert-info text-center">No time entries yet!</p></td></tr>'
			);
		}
		let totalDuration = 0;
		//
		logs.forEach(function (v0) {
			//
			totalDuration += parseFloat(v0["duration"]);
			//
			html += '<tr data-id="' + v0["sid"] + '">';
			html += '	<td class="csVerticalAlignMiddle">';
			html += "		<p>";
			html += v0["text"];
			html += "		</p>";
			html += "	</td>";

			html += '	<td class="csVerticalAlignMiddle text-right">';
			html += "		<p>";
			html += moment(v0["startTime"], "YYYY-MM-DD HH:mm:ss").format(
				"hh:mm a"
			);
			html += "		 - ";
			if (v0["endTime"]) {
				html += moment(v0["endTime"], "YYYY-MM-DD HH:mm:ss").format(
					"hh:mm a"
				);
			} else {
				html += "		Missing";
			}
			html += "		</p>";
			html += "	</td>";
			if (v0.location.onSiteFlag) {
				html += '	<td class="csVerticalAlignMiddle text-center ">';
				html += "		<p>";
				html += '<i class="fa fa-check-circle text-success"></i>';
				html += "		</p>";
				html += "	</td>";
			} else {
				html += '	<td class="csVerticalAlignMiddle text-center">';
				html += "		<p>";
				html += '<i class="fa fa-times-circle text-danger"></i><br />';
				html += v0["location"]["text"];
				html += "		</p>";
				html += "	</td>";
			}
			html += '	<td class="csVerticalAlignMiddle text-right">';
			html += "		<p>";
			html += v0["durationText"];
			html += "		</p>";
			html += "	</td>";
			html += "</tr>";
		});
		//
		$(".jsTimeEntriesBox tbody").html(html);
		//
		html = "<tr>";
		html +=
			'	<th scope="col" colspan="4" class="csVerticalAlignMiddle text-right">';
		html += "	Total: ";
		html += convertSecondsToTime(totalDuration);
		html += "	</th>";
		html += "</tr>";
		$(".jsTimeEntriesBox tfoot").html(html);
	}

	function convertSecondsToTime(differenceInSeconds) {
		// Convert seconds to hours and minutes
		let hours = Math.floor(differenceInSeconds / 3600);
		let minutes = Math.floor((differenceInSeconds % 3600) / 60);

		return hours + "h" + (minutes > 0 ? " " + minutes + "m" : "");
	}

	setInterval(getGraphs, 10000 * 3);
	setInterval(getFootprints, 10000 * 2);
	//
	getGraphs();
	getFootprints();
});
