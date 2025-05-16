$(function () {
	let XHR = null;

	let loaderRef = "#jsMainLoader";
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
			updateHomePageProductSortOrder(obj);
		}
	});

	//
	function updateHomePageProductSortOrder(data) {
		// check if XHR already in progress
		if (XHR !== null) {
			XHR.abort();
		}
		//
		loader(true);
		XHR = $.ajax({
			url: baseUrl("cms/update_product_sort_order/" + getSegment(2)),
			method: "post",
			data,
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				loader(false);
			});
	}


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


	function loader(cond, msg = "") {

		if (cond) {
			$(loaderRef).show();
			$(loaderRef + " .jsLoaderText").html(
				msg || "Please wait, while we process your request."
			);
		} else {
			$(loaderRef).hide();
			$(loaderRef + " .jsLoaderText").html(
				"Please wait, while we process your request."
			);
		}
	}


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



	//	
	$(".jsDeactivateHomeProduct").click(function (event) {
		//
		event.preventDefault();
		event.stopPropagation();

		//
		const bannerRef = $(this).closest(".row").data("key");
		const buttonRef = $(this);
		//
		return _confirm(
			"Do you really want deactivate this section?",
			function () {
				changeHomeProductStatus(bannerRef, buttonRef, 'deactivate');
			}
		);
	});

	//	
	$(".jsActivateHomeProductSection").click(function (event) {
		//
		event.preventDefault();
		event.stopPropagation();
		//
		const bannerRef = $(this).closest(".row").data("key");
		const buttonRef = $(this);
		//
		return _confirm(
			"Do you really want activate this section?",
			function () {
				changeHomeProductStatus(bannerRef, buttonRef, 'activate');
			}
		);
	});


	//
	function changeHomeProductStatus(bannerRef, buttonRef, statusAction) {
		//
		const pageId = getSegment(2);
		const btnHook = callButtonHook(buttonRef, true);
		$.ajax({
			url: baseUrl("cms/" + pageId + "/home/product/status/" + bannerRef + "/" + statusAction),
		})
			.fail(handleErrorResponse)
			.done(function (resp) {
				_success(resp.msg, function () {
					window.location.href = baseUrl(
						"manage_admin/edit_page/" +
						getSegment(2) +
						"/?page=home_section_2"
					);
				});
			});
	}


	$(document).ready(function () {
		var charLimit = 100;

		$('.text').each(function () {
			var fullText = $(this).text();

			if (fullText.length > charLimit) {
				var shortText = fullText.substring(0, charLimit);
				var remainingText = fullText.substring(charLimit);

				var html = `
          ${shortText}<span class="dots">...</span><span class="more-text" style="display:none;">${remainingText}</span>
          <a href="#" class="read-more" style="font-weight: bold;"> Read more</a>
        `;

				$(this).html(html);
			}
		});

		$(document).on('click', '.read-more', function (e) {
			e.preventDefault();
			var $link = $(this);
			var $text = $link.closest('.text');

			$text.find('.dots, .more-text').toggle();
			$link.text($link.text().includes("more") ? " Read less" : " Read more");
		});
	});


});
