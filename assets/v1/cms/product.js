$(function () {
	let XHR = null;

	let pageId = getSegment(2);

	$("#jsProductSliderForm").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			details: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "banner");
			return processData(formDataObj, $(".jsProductSliderBtn"), "slider");
		},
	});

	$("#jsProductAboutFile").msFileUploader({
		allowedTypes: ["mov", "mp4", "jpg", "jpeg", "png", "webp"],
		allowLinks: true,
		activeLink: aboutObj.sourceType,
		placeholderImage: aboutObj.sourceFile,
		fileLimit: "200mb",
	});

	$("#jsProductAboutForm").validate({
		rules: {
			mainHeading: {
				required: true,
			},
			subHeading: {
				required: true,
			},
			heading: {
				required: true,
			},
			details: {
				required: true,
			},
		},
		submitHandler: function (form) {
			const fileObject = $("#jsProductAboutFile").msFileUploader("get");
			//
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid source.");
			}
			const formDataObj = formArrayToObj($(form).serializeArray());
			//
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}
			formDataObj.append("section", "about");
			return processData(formDataObj, $(".jsProductAboutBtn"), "about");
		},
	});

	$("#jsProductPageForm").validate({
		rules: {
			mainHeading: {
				required: true,
			},
		},
		submitHandler: function (form) {
			//
			const formDataObj = formArrayToObj($(form).serializeArray());
			formDataObj.append("section", "product");
			return processData(formDataObj, $(".jsProductPageBtn"), "products");
		},
	});

	//
	$(".jsProductPageAddProductBtn").click(function (event) {
		//
		event.preventDefault();

		Modal(
			{
				Id: "jsAddModal",
				Loader: "jsAddModalLoader",
				Body: '<div id="jsAddModalBody"></div>',
				Title: "Add a Product Section",
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
		let sectionStatus = $(this).data("status");

		let statusMsg = "";
		if (sectionStatus == "1") {
			statusMsg = " De-Activate ";
		} else if (sectionStatus == "0") {
			statusMsg = " Activate ";
		}
		else {
			statusMsg = " De-Activate ";
		}

		//
		return _confirm(
			"Do you really want to " + statusMsg + "  this product section?",
			function () {
				processDeleteBProduct(bannerRef, buttonRef);
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
			url: baseUrl("cms/page/product"),
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
				$("#jsAdd").validate({
					ignore: [],
					rules: {
						mainHeading: {
							required: true,
						},

						details: {
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

	/**
	 * process add product section
	 * @param {*} formDataObj
	 * @returns
	 */
	function processAddProduct(formDataObj) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook($(".jsSaveProductBtn"), true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/product/"),
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
						"/?page=products"
					);
				});
			});
	}

	/**
	 * get product section edit page
	 * @param {*} index
	 */
	function getEditPage(index) {
		//
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("cms/page/" + getSegment(2) + "/product/" + index),
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
						details: {
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

	/**
	 * process product section edit
	 * @param {*} formDataObj
	 * @param {*} index
	 * @returns
	 */
	function processEditProduct(formDataObj, index) {
		//
		if (XHR !== null) {
			return false;
		}
		const pageId = getSegment(2);
		const btnHook = callButtonHook($(".jsUpdateProductBtn"), true);
		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/product/" + index),
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
						"/?page=products"
					);
				});
			});
	}

	/**
	 * process product section delete
	 * @param {*} bannerRef
	 * @param {*} buttonRef
	 */
	function processDeleteBProduct(bannerRef, buttonRef) {
		//
		const pageId = getSegment(2);
		const btnHook = callButtonHook(buttonRef, true);

		//
		XHR = $.ajax({
			url: baseUrl("cms/" + pageId + "/product/" + bannerRef),
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
						"/?page=products"
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

	//
	$(".jsDraggable").sortable({
		update: function (event, ui) {
			//
			var tagIndex = 0;
			var orderList = [];
			var indecators = ui.item.context.className.split(" ");
			//
			$("." + indecators[0]).map(function (i) {
				tagIndex = $(this).data("index");
				orderList.push($(this).data("key"));
			});
			// 
			var obj = {};
			obj.tagIndex = tagIndex;
			obj.sortOrders = orderList;
			//
			updateCardsSortOrder(obj);
		}
	});
	//
	function updateCardsSortOrder(data) {
		// check if XHR already in progress
		if (XHR !== null) {
			XHR.abort();
		}

		//
		XHR = $.ajax({
			url: baseUrl("cms/update_solutions_sort_order/" + getSegment(2)),
			method: "post",
			data,
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {

			});
	}

});