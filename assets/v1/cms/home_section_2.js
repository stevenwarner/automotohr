$(function () {
	let XHR = null;

	$("#jsHomeSection2Form").validate({
		rules: {
			jsMainHeading: {
				required: true,
			},
			jsSubHeading: {
				required: true,
			},
		},
		submitHandler: function (form) {
			//
			const formDataObj = formArrayToObj($(form).serializeArray());
			return processData(formDataObj);
		},
	});

	//
	$(".jsHomeSection2AddProductBtn").click(function (event) {
		//
		event.preventDefault();

		Modal(
			{
				Id: "jsAddModal",
				Loader: "jsAddModalLoader",
				Body: '<div id="jsAddModalBody"></div>',
				Title: "Add a Product",
			},
			getAddPage
		);
	});

	//
	$(".jsEditHomeProduct").click(function (event) {
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
				Title: "Edit a product",
			},
			function () {
				getEditPage(bannerRef);
			}
		);
	});

	//
	$(".jsDeleteHomeProduct").click(function (event) {
		//
		event.preventDefault();
		//
		const bannerRef = $(this).closest(".row").data("key");
		const buttonRef = $(this);
		//
		return _confirm(
			"Do you really want to delete this product section?",
			function () {
				processDeleteBProduct(bannerRef, buttonRef);
			}
		);
	});

	//
	function getAddPage() {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("cms/page/home/product"),
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
					allowLinks: true,
					activeLink: "upload",
					fileLimit: "200mb",
				});
				//
				$("#jsAddHomeProduct").validate({
					ignore: [],
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
						button_text: {
							required: true,
						},
						button_link: {
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

						return processAddProduct(formDataObj);
					},
				});
				//
				ml(false, "jsAddModalLoader");
			});
	}

	//
	function getEditPage(index) {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl(
				"cms/page/" + getSegment(2) + "/home/product/" + index
			),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsEditModalBody").html(resp.view);
				//
				$("#sourceFileEdit").msFileUploader({
					allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
					allowLinks: true,
					activeLink: resp.sourceType,
					placeholderImage: resp.sourceFile,
					fileLimit: "200mb",
				});
				//
				$("#jsEditHomeProduct").validate({
					ignore: [],
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
						button_text: {
							required: true,
						},
						button_link: {
							required: true,
						},
					},
					submitHandler: function (form) {
						const fileObject =
							$("#sourceFileEdit").msFileUploader("get");
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

						return processEditProduct(formDataObj, resp.index);
					},
				});
				//
				ml(false, "jsEditModalLoader");
			});
	}

	//
	function processData(formDataObj) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook($(".jsHomeSection2Btn"), true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/home/section2/"),
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
						"/?page=home_section_2"
					);
				});
			});
	}

	//
	function processAddProduct(formDataObj) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook($(".jsSaveProductBtn"), true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/home/product/"),
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
						"/?page=home_section_2"
					);
				});
			});
	}

	//
	function processEditProduct(formDataObj, index) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook($(".jsUpdateProductBtn"), true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/home/product/" + index),
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
						"/?page=home_section_2"
					);
				});
			});
	}

	/**
	 *
	 * @param {*} bannerRef
	 */
	function processDeleteBProduct(bannerRef, buttonRef) {
		//
		const pageId = getSegment(2);
		const btnHook = callButtonHook(buttonRef, true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/home/product/" + bannerRef),
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
						"/?page=home_section_2"
					);
				});
			});
	}
});
