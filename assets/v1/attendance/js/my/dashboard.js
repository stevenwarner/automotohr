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
				//
				const values = Object.values(response);

				let passValues = [];
				//
				let total = 0;
				//
				values?.map(function (value, index) {
					let loggedTime = 0;
					if (value.minutes) {
						loggedTime =
							parseFloat(value.minutes) +
							parseFloat(value.hours * 60);
						total += parseInt(loggedTime);
					}

					passValues.push(parseInt(loggedTime));
				});
				total = total.toFixed(0);
				//
				Highcharts.chart("container", {
					chart: {
						type: "column",
					},
					title: {
						text: `You have worked for ${total}m.`,
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
						valueSuffix: "m",
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
							name: "Worked minutes",
							data: passValues,
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
				if (
					locations.toString() !== response.logs.locations.toString()
				) {
					locations = response.logs.locations;
					callGoogleCB(initMap);
				}
				logs = response.logs.logs;


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

		delete(map)
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
				// var circle = new google.maps.Circle({
				// 	map: map,
				// 	center: {
				// 		lat: parseFloat(v0.constraint.lat),
				// 		lng: parseFloat(v0.constraint.lng),
				// 	},
				// 	radius: parseInt(50000 || v0.constraint.allowed), // Radius in meters
				// 	strokeColor: "blue", // Circle outline color
				// 	strokeOpacity: 0.8,
				// 	strokeWeight: 2,
				// 	fillColor: "blue", // Circle fill color
				// 	fillOpacity: 0.35,
				// });
				// // Add info window
				// var infoWindow = new google.maps.InfoWindow({
				// 	content: v0.constraint.title,
				// });
				// // Open info window when the circle is clicked
				// google.maps.event.addListener(
				// 	circle,
				// 	"click",
				// 	function (event) {
				// 		infoWindow.setPosition(event.latLng);
				// 		infoWindow.open(this.map);
				// 	}
				// );
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

		var latlngbounds = new google.maps.LatLngBounds();
		for (var i = 0; i < latlng.length; i++) {
			latlngbounds.extend(latlng[i]);
		}
		map.fitBounds(latlngbounds);
	}

	function addMarker(options) {
		let marker = new google.maps.Marker(options);

		markers.push(marker);
		coords.push({ lat: options.position.lat, lng: options.position.lng });
	}

	// Function to calculate distance between two sets of coordinates using Haversine formula
	function calculateDistance(lat1, lng1, lat2, lng2) {
		const earthRadius = 6371; // Radius of the Earth in kilometers

		const toRadians = (angle) => (angle * Math.PI) / 180;

		const deltaLat = toRadians(lat2 - lat1);
		const deltaLng = toRadians(lng2 - lng1);

		const a =
			Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
			Math.cos(toRadians(lat1)) *
				Math.cos(toRadians(lat2)) *
				Math.sin(deltaLng / 2) *
				Math.sin(deltaLng / 2);

		const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

		const distance = earthRadius * c; // Distance in kilometers

		return distance;
	}

	// Function to check if a point is outside a given radius
	function isOutsideRadius(centerLat, centerLng, pointLat, pointLng, radius) {
		const distance = calculateDistance(
			centerLat,
			centerLng,
			pointLat,
			pointLng
		);
		return distance > radius;
	}

	function metersToFeet(meters) {
		const feetPerMeter = 3.28084;
		return meters * feetPerMeter;
	}

	function remakeEntries() {
		// set the html holder
		let html = "";
		// when no entries are found
		if (!logs.length) {
			return $(".jsTimeEntriesBox").html(
				'<p class="alert alert-info text-center">No time entries yet!</p>'
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
			'	<th scope="col" colspan="3" class="csVerticalAlignMiddle text-right">';
		html += "	Total: ";
		html += convertSecondsToTime(totalDuration);
		html += "	</th>";
		html += "</tr>";
		$(".jsTimeEntriesBox tfoot").html(html);
	}

	setInterval(getGraphs, 10000 * 5);
	setInterval(getFootprints, 10000 * 2);
	//
	getGraphs();
	getFootprints();
});
