/**
 * Job Sites
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Time & Attendance
 */
$(function jobSites() {
	/**
	 * XHR holder
	 */
	let XHR = null;

	/**
	 * XHR validator
	 */
	let validatorRef = null;

	/**
	 * holds the modal page id
	 */
	const modalId = "jsModalPage";
	const modalLoader = modalId + "Loader";
	const modalBody = modalId + "Body";
	// location holder
	const locationHolder = {};
	// holds current lat lng
	let currentLatLong = {};

	/**
	 * add
	 */
	$(".jsAddJobSiteBtn").click(function (event) {
		// stop the event
		event.preventDefault();
		// flush the lat lng
		currentLatLong = {};
		//
		makePage("New Job Site", "job_site", 0, function () {
			// hides the loader
			ml(false, modalLoader);
			//
			validatorRef = $("#jsPageJobSiteForm").validate({
				rules: {
					site_name: { required: true },
					street_1: { required: true },
					city: { required: true },
					state: { required: true },
					zip_code: {
						required: true,
						digits: true,
						minlength: 5,
						maxlength: 5,
					},
					site_radius: { required: true, digits: true },
				},
				errorPlacement: function (error, element) {
					if ($(element).parent().hasClass("input-group")) {
						$(element).parent().after(error);
					} else {
						$(element).after(error);
					}
				},
				submitHandler: function (form) {
					//
					if (!currentLatLong.lat || !currentLatLong.lng) {
						return validatorRef.showErrors({
							site_radius:
								"Address latitude and longitude are missing.",
						});
					}
					//
					const formObj = formArrayToObj($(form).serializeArray());
					//
					formObj.append("lat", currentLatLong.lat);
					formObj.append("lng", currentLatLong.lng);
					//
					return processCall(
						formObj,
						$(".jsPageJobSiteBtn"),
						"settings/job_sites"
					);
				},
			});
		});
	});

	/**
	 * edit
	 */
	$(".jsEditJobSiteBtn").click(function (event) {
		// stop the event
		event.preventDefault();
		// holds the reference
		const reference = $(this).closest(".jsBox");
		// holds the id event
		const id = reference.data("id");
		// flush the lat lng
		currentLatLong = {};
		//
		makePage("Edit Job Site", "job_site", id, function (resp) {
			// hides the loader
			ml(false, modalLoader);
			//
			validatorRef = $("#jsPageJobSiteForm").validate({
				rules: {
					site_name: { required: true },
					street_1: { required: true },
					city: { required: true },
					state: { required: true },
					zip_code: {
						required: true,
						digits: true,
						minlength: 5,
						maxlength: 5,
					},
					site_radius: { required: true, digits: true },
				},
				errorPlacement: function (error, element) {
					if ($(element).parent().hasClass("input-group")) {
						$(element).parent().after(error);
					} else {
						$(element).after(error);
					}
				},
				submitHandler: function (form) {
					//
					if (!currentLatLong.lat || !currentLatLong.lng) {
						return validatorRef.showErrors({
							site_radius:
								"Address latitude and longitude are missing.",
						});
					}
					//
					const formObj = formArrayToObj($(form).serializeArray());
					//
					formObj.append("id", id);
					formObj.append("lat", currentLatLong.lat);
					formObj.append("lng", currentLatLong.lng);
					//
					return processCall(
						formObj,
						$(".jsPageJobSiteBtn"),
						"settings/job_sites"
					);
				},
			});
			//
			$("#job_site_map").mapMarker({
				center: { lat: resp.data.lat, lng: resp.data.lng },
				radius: parseInt($('[name="site_radius"]').val().trim()),
				draggable: true,
				onDragEnd: function (latLng) {
					// Callback function when the marker is dragged
					currentLatLong = latLng;
				},
			});
		});
	});

	/**
	 * delete
	 */
	$(".jsDeleteJobSiteBtn").click(function (event) {
		// stop the event
		event.preventDefault();
		// holds the reference
		const reference = $(this).closest(".jsBox");
		// holds the id event
		const id = reference.data("id");
		//
		return _confirm(
			"Are you sure you want to delete it? This action cannot be undone.",
			function () {
				return processDelete(reference, id);
			}
		);
	});

	/**
	 * Process address
	 */
	$(document).on("keyup", '[name="street_1"]', processAddress);
	$(document).on("keyup", '[name="street_2"]', processAddress);
	$(document).on("keyup", '[name="city"]', processAddress);
	$(document).on("keyup", '[name="zip_code"]', processAddress);
	$(document).on("keyup", '[name="site_radius"]', processAddress);
	$(document).on("change", '[name="state"]', processAddress);

	/**
	 * generates the modal
	 * @param {string} pageTitle
	 * @param {string} pageSlug
	 * @param {number} pageId
	 * @param {function} cb
	 */
	function makePage(pageTitle, pageSlug, pageId, cb) {
		Modal(
			{
				Id: modalId,
				Title: pageTitle,
				Body: '<div id="' + modalBody + '"></div>',
				Loader: modalLoader,
			},
			function () {
				fetchPage(pageSlug, pageId, cb);
			}
		);
	}

	/**
	 * fetch page from server
	 * @param {string} pageSlug
	 * @param {function} cb
	 */
	function fetchPage(pageSlug, pageId, cb) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			XHR.abort();
		}
		// make a new call
		XHR = $.ajax({
			url: baseUrl("settings/page/" + pageSlug + "/" + pageId),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				// load the new body
				$("#" + modalBody).html(resp.view);
				// call the callback
				cb(resp);
			});
	}

	/**
	 * process the call
	 * @param {object} formObj
	 * @param {string} buttonRef
	 * @param {string} url Optional
	 */
	function processCall(formObj, buttonRef, url) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			return;
		}
		//
		const btnRef = callButtonHook(buttonRef, true);
		// make a new call
		XHR = $.ajax({
			url: baseUrl(url),
			method: "POST",
			data: formObj,
			processData: false,
			contentType: false,
		})
			.always(function () {
				//
				callButtonHook(btnRef, false);
				//
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				//
				validatorRef?.destroy();
				return _success(resp.msg, function () {
					window.location.href = baseUrl("settings/job_sites");
				});
			});
	}

	/**
	 * process delete
	 * @param {object} reference
	 * @param {number} id
	 */
	function processDelete(reference, id) {
		// check if call is already made
		if (XHR !== null) {
			// abort the call
			return;
		}
		//
		const btnRef = callButtonHook(
			reference.find(".jsDeleteShiftTemplateBtn"),
			true
		);
		// make a new call
		XHR = $.ajax({
			url: baseUrl("settings/job_sites/" + id),
			method: "DELETE",
		})
			.always(function () {
				//
				callButtonHook(btnRef, false);
				//
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl("settings/job_sites");
				});
			});
	}

	/**
	 * make map out of address
	 */
	function processAddress() {
		// flush the old details
		currentLatLong = {};
		// set the address object
		const obj = {
			street_1: $('[name="street_1"]').val().trim(),
			street_2: $('[name="street_2"]').val().trim(),
			city: $('[name="city"]').val().trim(),
			state: $(
				'[name="state"] option[value="' +
					$('[name="state"] option:selected').val() +
					'"]'
			).text(),
			zip_code: $('[name="zip_code"]').val().trim(),
			site_radius: $('[name="site_radius"]').val().trim(),
		};
		//
		if (
			obj.street_1 &&
			obj.city &&
			obj.state &&
			obj.zip_code &&
			obj.site_radius &&
			obj.zip_code.length == 5
		) {
			// process
			// get the lat lng from address
			if (XHR !== null) {
				XHR.abort();
			}
			//
			let addressString = obj.street_1;
			if (obj.street_2) {
				addressString += "," + obj.street_2;
			}
			addressString += "," + obj.city;
			addressString += "," + obj.state;
			addressString += "," + obj.zip_code;
			addressString += ", USA";
			//
			addressString = encodeURI(addressString);
			// when we have details
			if (locationHolder[addressString]) {
				// add from cache
				currentLatLong = locationHolder[addressString];
				//
				return $("#job_site_map").mapMarker({
					center: locationHolder[addressString],
					radius: parseInt($('[name="site_radius"]').val().trim()),
					draggable: true,
					onDragEnd: function (latLng) {
						// Callback function when the marker is dragged
						currentLatLong = latLng;
					},
				});
			}
			//
			XHR = $.ajax({
				url:
					"https://maps.googleapis.com/maps/api/geocode/json?address=" +
					addressString +
					"&key=AIzaSyAbODx02cE7VVn-ufkpSO9HwUoXfgXTCng",
				method: "GET",
				cache: true,
			})
				.always(function () {
					XHR = null;
				})
				.done(function (resp) {
					if (resp.results[0]) {
						const latLngObj =
							resp.results[0]["geometry"]["location"];

						locationHolder[addressString] = {
							lat: latLngObj["lat"],
							lng: latLngObj["lng"],
						};

						currentLatLong = latLngObj;

						$("#job_site_map").mapMarker({
							center: latLngObj,
							radius: parseInt(
								$('[name="site_radius"]').val().trim()
							),
							draggable: true,
							onDragEnd: function (latLng) {
								// Callback function when the marker is dragged
								currentLatLong = latLng;
							},
						});
					}
				});
		} else {
			// flush
			$("#job_site_map").html(
				'<p class="alert alert-danger">Please select the address first</p>'
			);
		}
	}
});
