$(function () {
	let XHR = null;
	//
	$(".jsAddBanner").click(function (event) {
		//
		event.preventDefault();

		Modal(
			{
				Id: "jsAddSliderModal",
				Loader: "jsAddSliderModalLoader",
				Body: '<div id="jsAddSliderModalBody"></div>',
				Title: "Add a banner",
			},
			getAddBannerPage
		);
	});

	//
	$(".jsEditBanner").click(function (event) {
		//
		event.preventDefault();
		//
		const bannerRef = $(this).closest(".row").data("key");
		//
		Modal(
			{
				Id: "jsEditSliderModal",
				Loader: "jsEditSliderModalLoader",
				Body: '<div id="jsEditSliderModalBody"></div>',
				Title: "Edit a banner",
			},
			function () {
				getEditBannerPage(bannerRef);
			}
		);
	});

	//
	$(".jsDeleteBanner").click(function (event) {
		//
		event.preventDefault();
		//
		const bannerRef = $(this).closest(".row").data("key");
		const buttonRef = $(this);
		//
		return _confirm(
			"Do you really want to delete this slider?",
			function () {
				processDeleteBanner(bannerRef, buttonRef);
			}
		);
	});

	//
	function getAddBannerPage() {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("cms/page/banner"),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsAddSliderModalBody").html(resp.view);
				//
				$("#bannerImage").msFileUploader({
					fileLimit: "200mb",
					allowedTypes: ["jpg", "jpeg", "png", "gif", "webp"],
				});
				//
				$("#jsAddBannerForm").validate({
					ignore: [],
					rules: {
						heading: {
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
						// check for image
						if (
							!isValidFile(
								$("#bannerImage").msFileUploader("get")
							)
						) {
							return _error("Banner image is required.");
						}

						return processAddBanner(form);
					},
				});
				//
				ml(false, "jsAddSliderModalLoader");
			});
	}

	//
	function getEditBannerPage(bannerRef) {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("cms/page/" + getSegment(2) + "/banner/" + bannerRef),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsEditSliderModalBody").html(resp.view);
				//
				$("#bannerEditImage").msFileUploader({
					fileLimit: "200mb",
					allowedTypes: ["jpg", "jpeg", "png", "gif", "webp"],
					placeholderImage: resp.data.image,
				});
				//
				$("#jsUpdateBannerForm").validate({
					ignore: [],
					rules: {
						heading: {
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
							$("#bannerEditImage").msFileUploader("get");
						// check for image
						if (!isValidFile(fileObject)) {
							return _error("Banner image is required.");
						}
						const formDataObj = formArrayToObj(
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

						return processEditBanner(formDataObj);
					},
				});
				//
				ml(false, "jsEditSliderModalLoader");
			});
	}

	//
	function processEditBanner(formDataObj) {
		//
		if (XHR !== null) {
			return false;
		}
		//
		const pageId = getSegment(2);
		const btnHook = callButtonHook($(".jsUpdateBanner"), true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/slider/" + $("#jsId").val()),
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
						"/?page=slider"
					);
				});
			});
	}

	//
	function processAddBanner(form) {
		//
		if (XHR !== null) {
			return false;
		}
		//
		const formObj = formArrayToObj($(form).serializeArray());
		const pageId = getSegment(2);
		const btnHook = callButtonHook($(".jsSaveBanner"), true);
		//
		formObj.append("banner_image", $("#bannerImage").msFileUploader("get"));
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/slider/"),
			method: "POST",
			data: formObj,
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
						"/?page=slider"
					);
				});
			});
	}

	/**
	 *
	 * @param {*} bannerRef
	 */
	function processDeleteBanner(bannerRef, buttonRef) {
		//
		const pageId = getSegment(2);
		const btnHook = callButtonHook(buttonRef, true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/slider/" + bannerRef),
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
						"/?page=slider"
					);
				});
			});
	}
});
