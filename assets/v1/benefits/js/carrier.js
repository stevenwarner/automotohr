$(function carriers() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;

	/**
	 * add event
	 */
	$(document).on("click", ".jsAddBenefitCarrier", function (event) {
		//
		event.preventDefault();
		//
		Modal(
			{
				Id: "jsAddBenefitCarrierModal",
				Loader: "jsAddBenefitCarrierModalLoader",
				Body: '<div id="jsAddBenefitCarrierModalBody"></div>',
				Title: "Add benefit carrier",
			},
			loadAddBenefitCarrierView
		);
	});

	/**
	 * saves category
	 */
	$(document).on("click", ".jsBenefitCarrierSave", function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			ein: $(".jsCarrierEin").val().trim(),
			name: $(".jsCarrierName").val().trim(),
			code: $(".jsCarrierNumber").val().trim(),
		};
		//
		const errorsArray = [];
		// validation
		var reg = new RegExp('^[0-9*#]+$'); 
		//
		if (!obj.ein) {
			errorsArray.push('"Carrier EIN" is required.');
		} else if (!reg.test(obj.ein)) {
			errorsArray.push('"Carrier EIN" is only number.');
		} else if (obj.ein.length != 9) {
			errorsArray.push('"Carrier EIN" is not valid.');
		}
		//
		if (!obj.name) {
			errorsArray.push('"Carrier Name" is required.');
		}
		//
		if (!obj.code) {
			errorsArray.push('"Carrier Code" is required.');
		}
		//
		// get the uploaded file
		fileObject = $("#jsCarrierAttachmentUpload").msFileUploader("get");
		//
		if (!Object.keys(fileObject).length) {
			errorsArray.push(
				'"Carrier logo" is mandatory. Please upload a file.'
			);
		} else if (fileObject.errorCode) {
			errorsArray.push(fileObject.errorCode);
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		saveCarrier(obj,fileObject);
	});

	/**
	 * edit event
	 */
	$(document).on("click", ".jsBenefitCarrierEdit", function (event) {
		//
		event.preventDefault();
		//
		const id = $(this).closest("tr").data("id");
		//
		Modal(
			{
				Id: "jsEditBenefitCarrierModal",
				Loader: "jsEditBenefitCarrierModalLoader",
				Body: '<div id="jsEditBenefitCarrierModalBody"></div>',
				Title: "Edit benefit carrier",
			},
			function () {
				loadEditBenefitCarrierView(id);
			}
		);
	});

	/**
	 * updates carrier
	 */
	$(document).on("click", ".jsBenefitCarrierUpdate", function (event) {
		//
		event.preventDefault();
		//
		const key = $(".jsKey").val();
		//
		const obj = {
			ein: $(".jsCarrierEin").val().trim(),
			name: $(".jsCarrierName").val().trim(),
			code: $(".jsCarrierNumber").val().trim(),
		};
		//
		const errorsArray = [];
		// validation
		let reg = new RegExp('^[0-9*#]+$'); 
		//
		if (!obj.ein) {
			errorsArray.push('"Carrier EIN" is required.');
		} else if (!reg.test(obj.ein)) {
			errorsArray.push('"Carrier EIN" is only number.');
		} else if (obj.ein.length != 9) {
			errorsArray.push('"Carrier EIN" is not valid.');
		}
		//
		if (!obj.name) {
			errorsArray.push('"Carrier Name" is required.');
		}
		//
		if (!obj.code) {
			errorsArray.push('"Carrier Code" is required.');
		}
		//
		let uploadFile = true;
		// get the uploaded file
		fileObject = $("#jsCarrierAttachmentUpload").msFileUploader("get");
		//
		if (!Object.keys(fileObject).length) {
			uploadFile = false;
		}

		if (fileObject.errorCode) {
			errorsArray.push(fileObject.errorCode);
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		updateCarrier(obj, key, uploadFile, fileObject);
	});

	/**
	 * load carriers view
	 */
	function loadViews() {
		//
		$.ajax({
			url: baseUrl("sa/benefits/carrier/view"),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsBenefitCarrierBox").html(resp.carrierView);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");
			});
	}

	/**
	 * load add carrier view
	 */
	function loadAddBenefitCarrierView() {
		//
		$.ajax({
			url: baseUrl("sa/benefits/carrier/add/view"),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsAddBenefitCarrierModalBody").html(resp.view);
				//
				$("#jsCarrierAttachmentUpload").msFileUploader({
					fileLimit: "10mb",
					allowedTypes: ['jpg', 'jpeg', 'png', 'gif'],
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				ml(false, "jsAddBenefitCarrierModalLoader");
			});
	}

	/**
	 * save carrier details
	 */
	async function saveCarrier(obj, fileObject) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsAddBenefitCarrierModalLoader",
			"Please wait while we are saving the carrier."
		);
		//
		// let uploadedFileObject = {};
		// //
		// uploadedFileObject = await uploadFile(fileObject);
		// //
		// if (typeof uploadedFileObject === "string") {
		// 	// parse json
		// 	uploadedFileObject = JSON.parse(uploadedFileObject);
		// }
		// //
		// //file upload failed
		// if (!Object.keys(uploadedFileObject).length) {
		// 	// hide the loader
		// 	ml(false, "jsAddBenefitCarrierModalLoader");
		// 	// show error
		// 	return alertify.alert("ERROR!", "Failed to upload logo.", CB);
		// }
		// // saves the file name
		// obj.logo = uploadedFileObject.data;
		obj.logo = 'https://automotohrattachments.s3.amazonaws.com/jay-antol-Xbf_4e7YDII-unsplash-8fyy1G.jpg';
		//
		XHR = $.ajax({
			url: baseUrl("sa/benefits/carrier"),
			method: "POST",
			data: obj,
			cache: false,
		})
			.success(function (resp) {
				return _success(resp.msg, function () {
					loadViews();
					$(".jsModalCancel").trigger("click");
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsAddBenefitCarrierModalLoader");
			});
	}

	/**
	 * load edit carrier view
	 */
	function loadEditBenefitCarrierView(id) {
		//
		$.ajax({
			url: baseUrl("sa/benefits/carrier/" + id),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsEditBenefitCarrierModalBody").html(resp.view);
				//
				var placeholder = $("#jsOldCarrierAttachmentUpload").val();
				//
				$("#jsCarrierAttachmentUpload").msFileUploader({
					fileLimit: "10mb",
					allowedTypes: ['jpg', 'jpeg', 'png', 'gif'],
					placeholderImage: placeholder
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				ml(false, "jsEditBenefitCarrierModalLoader");
			});
	}

	/**
	 * load edit carrier details
	 */
	async function updateCarrier(obj, key, uploadFile, fileObject) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsEditBenefitCarrierModalLoader",
			"Please wait while we are saving the carrier."
		);
		//
		if (uploadFile) {
			//
			// let uploadedFileObject = {};
			// //
			// uploadedFileObject = await uploadFile(fileObject);
			// //
			// if (typeof uploadedFileObject === "string") {
			// 	// parse json
			// 	uploadedFileObject = JSON.parse(uploadedFileObject);
			// }
			// //
			// //file upload failed
			// if (!Object.keys(uploadedFileObject).length) {
			// 	// hide the loader
			// 	ml(false, "jsAddBenefitCarrierModalLoader");
			// 	// show error
			// 	return alertify.alert("ERROR!", "Failed to upload logo.", CB);
			// }
			// // saves the file name
			// obj.logo = uploadedFileObject.data;
			obj.logo = 'jay-antol-Xbf_4e7YDII-unsplash-8fyy1G.jpg';
		}
		console.log(key)
		//
		XHR = $.ajax({
			url: baseUrl("sa/benefits/carrier/" + key),
			method: "POST",
			data: obj,
			cache: false,
		})
			.success(function (resp) {
				return _success(resp.msg, function () {
					loadViews();
					$(".jsModalCancel").trigger("click");
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsEditBenefitCarrierModalLoader");
			});
	}

	loadViews();
});
