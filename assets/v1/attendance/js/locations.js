$(function locations() {
	//
	$(".jsDatePicker").daterangepicker({
		showDropdowns: true,
		singleDatePicker: true,
		autoApply: true,
		locale: {
			format: "MM/DD/YYYY",
		},
	});

	// $('.jsDatePicker').datepicker({
	// 	format: 'mm-dd-yyyy',
	// 	beforeShowDay: unavailable,
	// 	minDate: 1
	// });

    makeLocationMap("jsMapLocation",{"lat":51.508742,"lng":-0.120850});

    /**
	 * draw map
	 */
	function makeLocationMap(containerId, location) {
		let map,
			markers = [],
			coords = [],
			latlng = [];

		location.lat = parseFloat(location.lat);
		location.lng = parseFloat(location.lng);

		if (location.lat_2 && !isNaN(location.lat_2)) {
			location.lat_2 = parseFloat(location.lat_2);
			location.lng_2 = parseFloat(location.lng_2);
		}

		//
		map = new google.maps.Map(document.getElementById(containerId), {
			center: {
				lat: location.lat,
				lng: location.lng,
			},
			mapTypeId: google.maps.MapTypeId.ROADMAP,
		});

		addMarker({
			markers,
			coords,
			map: map,
			position: {
				lat: location["lat"],
				lng: location["lng"],
			},
			title: location.title,
		});
		//
		latlng.push(new google.maps.LatLng(location.lat, location.lng));

		if (location.lat_2 && !isNaN(location.lat_2)) {
			addMarker({
				markers,
				coords,
				map: map,
				position: {
					lat: location["lat_2"],
					lng: location["lng_2"],
				},
				title: location.title,
			});
			//
			latlng.push(new google.maps.LatLng(location.lat_2, location.lng_2));
			let line = new google.maps.Polyline({
				path: coords,
				geodesic: true,
				strokeColor: "#FF0000",
				strokeOpacity: 1.0,
				strokeWeight: 2,
			});

			line.setMap(map);
		}

		if (location["constraint"]) {
			// Add circle overlay for radius
			const circle = new google.maps.Circle({
				map: map,
				center: {
					lat: parseFloat(location.constraint.lat),
					lng: parseFloat(location.constraint.lng),
				},
				radius: parseInt(50000 || location.constraint.allowed), // Radius in meters
				strokeColor: "red", // Circle outline color
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: "red", // Circle fill color
				fillOpacity: 0.35,
			});
			// Add info window
			const infoWindow = new google.maps.InfoWindow({
				content: location.constraint.title,
			});
			// Open info window when the circle is clicked
			google.maps.event.addListener(circle, "click", function (event) {
				infoWindow.setPosition(event.latLng);
				infoWindow.open(this.map);
			});

			latlng.push(
				new google.maps.LatLng(
					parseFloat(location.constraint.lat),
					parseFloat(location.constraint.lng)
				)
			);
		}

		let LatLngBounds = new google.maps.LatLngBounds();

		latlng.forEach((element) => {
			LatLngBounds.extend(element);
		});

		map.fitBounds(LatLngBounds);

		google.maps.event.trigger(map, "resize");
	}

	function addMarker(options) {
		let marker = new google.maps.Marker(options);

		options.markers.push(marker);
		options.coords.push({
			lat: options.position.lat,
			lng: options.position.lng,
		});
	}
});