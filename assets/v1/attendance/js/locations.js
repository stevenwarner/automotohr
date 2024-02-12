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
	//
	console.log(jsMapData);
	//
	if(Object.keys(jsMapData).length){
		//
		$('#map').css('height', '440px')
		//
		const myLatLng = {
			lat: 39.696621,
			lng: -98.672710
		};
		//
		const map = new google.maps.Map(document.getElementById("map"), {
			zoom: 4,
			mapTypeControl: true,
			center: myLatLng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		//
		jsMapData.map(function(mark, i){
			//
			const icon = {
				url: mark['logo'], // url
				scaledSize: new google.maps.Size(25, 25), // scaled size
				origin: new google.maps.Point(0,0), // origin
				anchor: new google.maps.Point(0, 0) // anchor
			};
			//
			var pin = {lat: parseFloat(mark['lat']), 'lng': parseFloat(mark['lng'])};
			//
			new google.maps.Marker({
				position: pin,
				map: map,
				icon: icon,
				title: mark['name'],
				optimized: false
			});
			//
			console.log(mark)
			//
			// addCSSRule(document.styleSheets[0],
            //     'img[src="' + logo  +'#' + i + '"]',
            //     'background:url(' + logo + ') no-repeat 4px 4px');
		});
	} else {
		$('#map').html('<p class="alert alert-info text-center csF16">No foot prints found.</p>');
	}

	function addCSSRule(sheet, selector, rules, index) {
        if ("insertRule" in sheet) {
            sheet.insertRule(selector + "{" + rules + "}", index);
        } else if ("addRule" in sheet) {
            sheet.addRule(selector, rules, index);
        }
    }

	// $('.jsDatePicker').datepicker({
	// 	format: 'mm-dd-yyyy',
	// 	beforeShowDay: unavailable,
	// 	minDate: 1
	// });
	// console.log(locations);

	
	// locations.map(function(v,i){
	// 	console.log(v,i);
	// })

    // makeLocationMap("jsMapLocation",{"lat":51.508742,"lng":-0.120850});

    // /**
	//  * draw map
	//  */
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

	// function addMarker(options) {
	// 	let marker = new google.maps.Marker(options);

	// 	options.markers.push(marker);
	// 	options.coords.push({
	// 		lat: options.position.lat,
	// 		lng: options.position.lng,
	// 	});
	// }
});