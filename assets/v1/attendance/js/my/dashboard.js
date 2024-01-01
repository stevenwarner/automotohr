$(function myAttendanceDashboard() {
	let googleCbInterval;
	/**
	 * get the week worked graph
	 */
	function getGraphs() {
		// starts the loader
		ml(true, "jsWeekGraph", "");
		//
		$.ajax({
			url: baseUrl("v1/clock/graphs/week_worked_time"),
			method: "GET",
			cache: false,
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
				values?.map(function (value) {
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

	let map,
		markers = [],
		coords = [];

	/**
	 * draw map
	 */
	function initMap() {
		if (!footprintLocations) {
			return;
		}

		console.log(footprintLocations);
		//
		map = new google.maps.Map(document.getElementById("map"), {
			center: {
				lat: parseFloat(footprintLocations[0].lat),
				lng: parseFloat(footprintLocations[0].lng),
			},
			// mapTypeId: "roadmap",
		});

		const latlng = [];

		// // Call function to add markers
		footprintLocations.map(function (v0, i) {
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

	//
	getGraphs();

	callGoogleCB(initMap);
});
