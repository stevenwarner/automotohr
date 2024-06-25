$(function locations() {
	//
	$(".multipleSelect").select2();
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
	let googleCbInterval;
	let map;
	let locations = jsMapData;

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
	async function initMap() {
		$('#map').css('height', '440px');
		const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
		//
		map = new google.maps.Map(document.getElementById("map"), {
			center: {
				lat: parseFloat(locations[0].lat),
				lng: parseFloat(locations[0].lng),
			},
			mapTypeId: "roadmap",
			mapId: "4504f8b37365c3d0",
		});
		//
		const latlng = [];
		// 
		// Call function to add markers
		locations.map(function (v0, i) {
			//
			if (!v0["lat"] || !v0["lat"]) {
				return;
			}
			//
			const AdvancedMarkerElement = new google.maps.marker.AdvancedMarkerElement({
				map,
				content: buildContentNew(v0),
				position: {
					lat: parseFloat(v0["lat"]),
					lng: parseFloat(v0["lng"]),
				},
				title: v0["name"],
			});
			//
			AdvancedMarkerElement.addListener("click", () => {
				
			});  
			//
			latlng.push(
				new google.maps.LatLng(
					parseFloat(v0["lat"]),
					parseFloat(v0["lng"])
				)
			);
		});
		//
		let latlngbounds = new google.maps.LatLngBounds();
		//
		latlng.forEach((element) => {
			latlngbounds.extend(element);
		});
		//
		map.fitBounds(latlngbounds);
	}
	//
	function buildContentNew(info) {
		const content = document.createElement("div");
		var link = baseURL+'attendance/location_detail?date='+selectedDate+'&sid='+info['employeeId'];
	  
		content.classList.add("customMarker");
		content.innerHTML = `
		  	<a href="${link}" target="_blank">
			  	<img src="${info['logo']}"/>
		  	</a>`;

		return content;
	}
	//
	if (!locations.length) {
		$("#map").html(
			'<p class="alert alert-info text-center">Employees footprint not found!</p>'
		);
	} else {
		callGoogleCB(initMap);
	}
	//
});