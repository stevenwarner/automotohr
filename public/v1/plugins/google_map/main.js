(function ($) {
	$.fn.mapMarker = function (options) {
		// Default options
		const settings = $.extend(
			{
				center: { lat: 0, lng: 0 },
				radius: 5000,
				draggable: false,
				onDragEnd: function () {},
			},
			options
		);

		return this.each(function () {
			const $this = $(this);
			let map;
			let marker;
			let latLongObject;
			let cityCircle;

			function initMap() {
				latLongObject = google.maps.LatLng(
					settings.center.lat,
					settings.center.lng
				);
				map = new google.maps.Map($this[0], {
					center: settings.center,
					zoom: getZoomLevel(settings.radius),
				});

				marker = new google.maps.Marker({
					position: settings.center,
					map: map,
					title: "Draggable Marker",
					draggable: settings.draggable,
				});

				if (settings.radius) {
					// add the radius circle
					cityCircle = new google.maps.Circle({
						strokeColor: settings.circleColor || "#5cb85c",
						strokeOpacity: 0.8,
						strokeWeight: 2,
						fillColor: settings.circleColor || "#5cb85c",
						fillOpacity: 0.35,
						map: map,
						center: latLongObject,
						radius: settings.radius, // in meters
					});
					cityCircle.bindTo("center", marker, "position");
				}

				if (settings.draggable) {
					// Add a drag event listener to the marker
					google.maps.event.addListener(
						marker,
						"dragend",
						function () {
							settings.onDragEnd({
								lat: marker.getPosition().lat(),
								lng: marker.getPosition().lng(),
							});
						}
					);
				}
			}

			function getZoomLevel(radius) {
				return Math.round(14 - Math.log(radius / 500) / Math.LN2);
			}

			// holds the map loaded state
			const googleApiLoaded = setInterval(loadGoogleApi);
			// keep checking if the map is loaded or not
			function loadGoogleApi() {
				if (typeof google !== "undefined") {
					clearInterval(googleApiLoaded);
					initMap();
				}
			}
		});
	};
})(jQuery);
