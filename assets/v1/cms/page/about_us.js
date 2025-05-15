$(function () {
	let XHR = null;
	let pageId = getSegment(2);

	// section 1
	$("#jsSection1File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: aboutObj.sourceType,
		placeholderImage: aboutObj.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsSection1Form").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			subHeading: {
				required: true,
			},
			details: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const fileObject = $("#jsSection1File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());

			formDataObj.append("section", "section_1");

			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}

			return processData(formDataObj, $("#jsSection1Btn"), "section_1");
		},
	});

	// section 2
	$("#jsSection2File").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: section2.sourceType,
		placeholderImage: section2.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsSection2Form").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			details: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const fileObject = $("#jsSection2File").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			//
			formDataObj.append("section", "section_2");
			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}
			//
			return processData(formDataObj, $("#jsSection2Btn"), "section_2");
		},
	});

	// section 3
	$("#jsSection3Form").validate({
		rules: {
			mainHeading: {
				required: true,
			},
		},
		submitHandler: function (form) {
			//
			let formDataObj = formArrayToObj($(form).serializeArray());
			//
			formDataObj.append("section", "section_3");

			//
			return processData(formDataObj, $("#jsSection3Btn"), "section_3");
		},
	});

	//
	$("#jsSection3AddBtn").click(function (event) {
		//
		event.preventDefault();

		Modal(
			{
				Id: "jsAddModal",
				Loader: "jsAddModalLoader",
				Body: '<div id="jsAddModalBody"></div>',
				Title: "Add a Team member",
			},
			getAddPage
		);
	});

	//
	$(".jsEditPage").click(function (event) {
		//
		event.preventDefault();
		//
		const bannerRef = $(this).closest(".row").data("key");
		//
		Modal(
			{
				Id: "jsEditModal",
				Loader: "jsEditModalLoader",
				Body: '<div id="jsEditModalBody"></div>',
				Title: "Edit a Team member",
			},
			function () {
				getEditPage(bannerRef);
			}
		);
	});

	//
	$(".jsDeletePage").click(function (event) {
		//
		event.preventDefault();
		//
		const bannerRef = $(this).closest(".row").data("key");
		const buttonRef = $(this);
		//
		return _confirm(
			"Do you really want to delete this product section?",
			function () {
				processDelete(bannerRef, buttonRef, "section_3");
			}
		);
	});

	/**
	 * Updates the data on page
	 * @param {*} formDataObj
	 * @param {*} buttonRef
	 * @param {string} redirectTo
	 * @returns
	 */
	function processData(formDataObj, buttonRef, redirectTo) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook(buttonRef, true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/section/"),
			method: "POST",
			data: formDataObj,
			processData: false,
			contentType: false,
		})
			.always(function () {
				XHR = null;
				callButtonHook(btnHook, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl(
						"manage_admin/edit_page/" +
						getSegment(2) +
						"/?page=" +
						redirectTo
					);
				});
			});
	}

	/**
	 * get product add page
	 */
	function getAddPage() {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("cms/page/add_team"),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsAddModalBody").html(resp.view);
				//
				$("#sourceFile").msFileUploader({
					allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
					allowLinks: false,
					activeLink: "upload",
					fileLimit: "200mb",
				});
				//
				CKEDITOR.replace("detailsAddTeam");
				//
				$("#jsAddTeamForm").validate({
					ignore: [],
					rules: {
						mainHeading: {
							required: true,
						},
					},
					submitHandler: function (form) {
						const fileObject =
							$("#sourceFile").msFileUploader("get");
						//
						if (!isValidFile(fileObject)) {
							return _error("Please select a valid source.");
						}
						//
						let formDataObj = formArrayToObj(
							$(form).serializeArray()
						);

						//
						if (fileObject.link !== undefined) {
							formDataObj.append("source_type", fileObject.type);
							formDataObj.append("source_link", fileObject.link);
						} else {
							formDataObj.append("source_type", "upload");
							formDataObj.append("file", fileObject);
						}
						formDataObj.append(
							"details",
							CKEDITOR.instances.detailsAddTeam.getData()
						);

						formDataObj.append("section", "teams");

						return processAddPage(
							formDataObj,
							$(".jsAddTeamBtn"),
							"section_3"
						);
					},
				});
				//
				ml(false, "jsAddModalLoader");
			});
	}

	/**
	 * get product edit page
	 */
	function getEditPage(index) {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("cms/page/" + getSegment(2) + "/teams/" + index),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsEditModalBody").html(resp.view);
				//
				$("#sourceFile").msFileUploader({
					allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
					allowLinks: false,
					activeLink: resp.sourceType,
					placeholderImage: resp.sourceFile,
					fileLimit: "200mb",
				});
				CKEDITOR.replace("detailsEditTeam");
				//
				$("#jsEditTeamForm").validate({
					ignore: [],
					rules: {
						mainHeading: {
							required: true,
						},
					},
					submitHandler: function (form) {
						const fileObject =
							$("#sourceFile").msFileUploader("get");
						//
						if (!isValidFile(fileObject)) {
							return _error("Please select a valid source.");
						}
						//
						let formDataObj = formArrayToObj(
							$(form).serializeArray()
						);

						//
						if (fileObject.link !== undefined) {
							formDataObj.append("source_type", fileObject.type);
							formDataObj.append("source_link", fileObject.link);
						} else {
							formDataObj.append("source_type", "upload");
							formDataObj.append("file", fileObject);
						}
						formDataObj.append(
							"details",
							CKEDITOR.instances.detailsEditTeam.getData()
						);

						formDataObj.append("section", "teams");

						return processEditPage(
							formDataObj,
							$(".jsEditTeamBtn"),
							"section_3",
							resp.index
						);
					},
				});
				//
				ml(false, "jsEditModalLoader");
			});
	}

	/**
	 * process add product section
	 * @param {*} formDataObj
	 * @returns
	 */
	function processAddPage(formDataObj, buttonRef, redirectTo) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook(buttonRef, true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/page/" + pageId + "/add"),
			method: "POST",
			data: formDataObj,
			processData: false,
			contentType: false,
		})
			.always(function () {
				XHR = null;
				callButtonHook(btnHook, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl(
						"manage_admin/edit_page/" +
						getSegment(2) +
						"/?page=" +
						redirectTo
					);
				});
			});
	}

	/**
	 * process edit product section
	 * @param {*} formDataObj
	 * @returns
	 */
	function processEditPage(formDataObj, buttonRef, redirectTo, index) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook(buttonRef, true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/page/" + pageId + "/edit/" + index),
			method: "POST",
			data: formDataObj,
			processData: false,
			contentType: false,
		})
			.always(function () {
				XHR = null;
				callButtonHook(btnHook, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl(
						"manage_admin/edit_page/" +
						getSegment(2) +
						"/?page=" +
						redirectTo
					);
				});
			});
	}

	/**
	 * process product section delete
	 * @param {*} bannerRef
	 * @param {*} buttonRef
	 */
	function processDelete(bannerRef, buttonRef, redirectTo) {
		//
		const pageId = getSegment(2);
		const btnHook = callButtonHook(buttonRef, true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/page/" + pageId + "/teams/" + bannerRef),
			method: "DELETE",
		})
			.always(function () {
				XHR = null;
				callButtonHook(btnHook, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl(
						"manage_admin/edit_page/" +
						getSegment(2) +
						"/?page=" +
						redirectTo
					);
				});
			});
	}

	//
	$(".jsPageStatus").click(function (event) {
		//
		event.preventDefault();
		//
		const formDataObj = new FormData();
		formDataObj.append("section", "status");
		formDataObj.append("status", $(this).data("key"));
		//
		processPageSectionStatus(formDataObj, $(".jsPageStatus"), "pageDetails");
	});

	//
	function processPageSectionStatus(formDataObj, buttonRef, redirectTo) {
		//
		if (XHR !== null) {
			return false;
		}
		const btnHook = callButtonHook(buttonRef, true);
		//
		XHR = $.ajax({
			url: baseUrl("manage_admin/cms/page/edit/" + pageId),
			method: "POST",
			data: formDataObj,
			processData: false,
			contentType: false,
		})
			.always(function () {
				XHR = null;
				callButtonHook(btnHook, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				return _success(resp.msg, function () {
					window.location.href = baseUrl(
						"manage_admin/edit_page/" +
						pageId
					);
				});
			});
	}
});
