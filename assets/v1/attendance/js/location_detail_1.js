$(function attendanceDetail() {
	let googleCbInterval;
	let map,
		markers = [],
		coords = [];

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
        //
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
			if (!v0["lat"] || !v0["lat"]) {
				return;
			}
            //
            coords.push(
                { 
                    lat: parseFloat(v0["lat"]),
					lng: parseFloat(v0["lng"])
                }
            ); 
            //
			const AdvancedMarkerElement = new google.maps.marker.AdvancedMarkerElement({
				map,
				content: buildContent(v0),
				position: {
					lat: parseFloat(v0["lat"]),
					lng: parseFloat(v0["lng"]),
				},
				title: v0["title"],
			});
            //
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
					parseFloat(v0["lat"]),
					parseFloat(v0["lng"])
				)
			);
			//
			AdvancedMarkerElement.addListener("click", () => {
				toggleHighlight(AdvancedMarkerElement, v0);
			});
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

	function toggleHighlight(markerView, activity) {
		if (markerView.content.classList.contains("highlight")) {
		  markerView.content.classList.remove("highlight");
		  markerView.zIndex = null;
		} else {
		  markerView.content.classList.add("highlight");
		  markerView.zIndex = 1;
		}
	}
	  
	function buildContent(activity) {
        //
        if(activity['title'].search("Clocked") != -1) {
			icon = "fa-clock-o";
		} else {
			icon = "fa-coffee"; 
		}
        //
		const content = document.createElement("div");
	  
		content.classList.add("activity");
		content.innerHTML = `
			<div class="icon">
				<i aria-hidden="true" class="fa ${icon}"></i>
			</div>
			<div class="details">
				<div class="price">${activity.title}</div>
				<div class="address">${activity.title}</div>
				<div class="features">
                    <div>
                        <i aria-hidden="true" class="fa fa-bed fa-lg bed" title="bedroom"></i>
                        <span class="fa-sr-only">bedroom</span>
                        <span>${activity.title}</span>
                    </div>
                    <div>
                        <i aria-hidden="true" class="fa fa-bath fa-lg bath" title="bathroom"></i>
                        <span class="fa-sr-only">bathroom</span>
                        <span>${activity.title}</span>
                    </div>
                    <div>
                        <i aria-hidden="true" class="fa fa-ruler fa-lg size" title="size"></i>
                        <span class="fa-sr-only">size</span>
                        <span>${activity.title} ft<sup>2</sup></span>
                    </div>
				</div>
			</div>
		`;
		//
		return content;
	}

    async function getLocationAddress () {
		//
		const geocoder = new google.maps.Geocoder();
		//
		let allPromises = locations.map(async function (record, index) {
			geocoder
				.geocode({ location: {
					lat: parseFloat(record["lat"]),
					lng: parseFloat(record["lng"]),
				} })
				.then((response) => {
					if (response.results[0]) {
						console.log(response.results[0].formatted_address)
						
					} else {
						console.log("Unknown Location");
					}
				})
				.catch((e) => {
					console.log("Unknown Location");
				});
		});
		// make sure all promises are resolved
		await Promise.all(allPromises);
		//
		console.log(locations);
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


	if (!locations.length) {
		$("#map").html(
			'<p class="alert alert-info text-center">No footprints yet!</p>'
		);
	} else {
		getLocationAddress();
		// callGoogleCB(initMap);
	}
	
	remakeEntries();
});

