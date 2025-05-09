$(function () {
	/**
	 * holds the XHR call reference
	 */
	let XHR = null;
	//
	$(".jsDraggable").sortable({
		update: function(event, ui) {
			//
			var tagIndex = 0;
			var orderList = [];
			var indecators = ui.item.context.className.split(" ");
			//
			$("."+indecators[0]).map(function (i) {
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
			url: baseUrl("cms/update_sort_order/" + getSegment(2)),
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
	//
	// attach file uploader
	$("#jsSection0File").msFileUploader({
		allowedTypes: ["jpg", "jpeg", "png", "webp"],
		allowLinks: false,
		activeLink: section0.sourceType,
		placeholderImage: section0.sourceFile,
		fileLimit: "20mb",
	});
	// validate the form
	$("#jsSection0Form").validate({
		ignore: [],
		rules: {
			title: { required: true },
		},
		submitHandler: function (form) {
			// get the form object
			let formDataObj = formArrayToObj($(form).serializeArray());
			// attach the section id
			formDataObj.append("section", "section0");
			// get the file object
			const fileObject = $("#jsSection0File").msFileUploader("get");
			// validation
			if (!isValidFile(fileObject)) {
				return _error("Please select a valid image for banner.");
			}
			// verify the
			if (fileObject.link !== undefined) {
				formDataObj.append("source_type", fileObject.type);
				formDataObj.append("source_link", fileObject.link);
			} else {
				formDataObj.append("source_type", "upload");
				formDataObj.append("file", fileObject);
			}

			return processOldData(formDataObj, $(".jsSection0Btn"), "section0");
		},
	});

	/**
	 * adds a section
	 */
	$(".jsAddMainSection").click(function (event) {
		// stop the event
		event.preventDefault();
		//
		Modal(
			{
				Id: "jsAddModal",
				Loader: "jsAddModalLoader",
				Body: '<div id="jsAddModalBody"></div>',
				Title: "Add a Section",
			},
			function () {
				getPageUI("Add", "add", function () {
					//
					$("#jsAddForm").validate({
						rules: {
							title: { required: true },
						},
						submitHandler: function (form) {
							// get the form object
							let formDataObj = formArrayToObj(
								$(form).serializeArray()
							);
							// attach the section id
							formDataObj.append("section", "section0");
							formDataObj.append("index", "tags");
							//
							return processPageSection(
								formDataObj,
								$(".jsAddBtn"),
								"section0"
							);
						},
					});
				});
			}
		);
	});

	/**
	 * deletes a section
	 */
	$(".jsDeleteSection").click(function (event) {
		//
		event.preventDefault();
		event.stopPropagation();
		//
		const index = $(this).data("index");
		//
		return _confirm(
			"Do you really wan tto delete this section?",
			function () {
				deleteTag(index);
			}
		);
	});

	/**
	 * adds a section
	 */
	$(".jsAddCardSection").click(function (event) {
		// stop the event
		event.preventDefault();
		//
		const index = $(this).data("index");
		//
		Modal(
			{
				Id: "jsAddModal",
				Loader: "jsAddModalLoader",
				Body: '<div id="jsAddModalBody"></div>',
				Title: "Add a Card Section",
			},
			function () {
				//
				getPageUI("Add", "add_card", function () {
					//
					$("#jsSortOrderSection").removeClass("hidden");
					//
					$("#jsAddCardForm").validate({
						rules: {
							title: { required: true },
							details: { required: true },
							buttonText: { required: true },
							buttonLink: { required: true },
							sortOrder: { required: true },
						},
						submitHandler: function (form) {
							// get the form object
							let formDataObj = formArrayToObj(
								$(form).serializeArray()
							);
							console.log(formDataObj)
							// attach the section id
							formDataObj.append("section", "section0");
							formDataObj.append("tagIndex", index);
							//
							return processCardSection(
								formDataObj,
								$(".jsAddCardBtn"),
								"sectionTag" + index
							);
						},
					});
				});
			}
		);
	});

	/**
	 * deletes a tag card
	 */
	$(".jsDeleteTagCard").click(function (event) {
		//
		event.preventDefault();
		//
		const index = $(this).closest(".row").data("key");
		const tagIndex = $(this).closest(".row").data("index");
		//
		return _confirm("Do you really wan tto delete this card?", function () {
			deleteTagCard(index, tagIndex);
		});
	});

	/**
	 * edit a tag card
	 */
	$(".jsEditTagCard").click(function (event) {
		//
		event.preventDefault();
		//
		const index = $(this).closest(".row").data("key");
		const tagIndex = $(this).closest(".row").data("index");
		//
		Modal(
			{
				Id: "jsEditModal",
				Loader: "jsEditModalLoader",
				Body: '<div id="jsEditModalBody"></div>',
				Title: "Edit a Card Section",
			},
			function () {
				getEditPageUI(
					"Edit",
					"edit_card",
					function () {
						//
						$("#jsSortOrderSection").removeClass("hidden");
						//
						$("#jsEditCardForm").validate({
							rules: {
								title: { required: true },
								details: { required: true },
								buttonText: { required: true },
								buttonLink: { required: true },
								sortOrder: { required: true },
							},
							submitHandler: function (form) {
								// get the form object
								let formDataObj = formArrayToObj(
									$(form).serializeArray()
								);
								// attach the section id
								formDataObj.append("pageId", getSegment(2));
								formDataObj.append("tagIndex", tagIndex);
								formDataObj.append("index", index);
								//
								return processCardSection(
									formDataObj,
									$(".jsEditCardBtn"),
									"sectionTag" + tagIndex
								);
							},
						});
					},
					{
						section:
							"section0|tags|" + tagIndex + "|cards|" + index,
						pageId: getSegment(2),
					}
				);
			}
		);
	});

	/**
	 * get the ui
	 *
	 * @param {string} type
	 */
	function getPageUI(type, page, cb) {
		// check if XHR already in progress
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("cms/ui/" + page),
			method: "GET",
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				// attach form event
				$("#js" + type + "ModalBody").html(resp.view);
				if (cb !== undefined) {
					cb();
				}

				//
				ml(false, "js" + type + "ModalLoader");
			});
	}

	/**
	 * get the ui
	 *
	 * @param {string} type
	 */
	function getEditPageUI(type, page, cb, data) {
		// check if XHR already in progress
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("cms/ui/" + page),
			method: "post",
			data,
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				// attach form event
				$("#js" + type + "ModalBody").html(resp.view);
				if (cb !== undefined) {
					cb();
				}

				//
				ml(false, "js" + type + "ModalLoader");
			});
	}

	/**
	 * delete tag
	 *
	 * @param {string} index
	 */
	function deleteTag(index) {
		//
		$.ajax({
			url: baseUrl("cms/" + getSegment(2) + "/tag/" + index),
			method: "delete",
		})
			.fail(handleErrorResponse)
			.done(function (resp) {
				_success(resp.msg, function () {
					window.location.href = baseUrl(
						"manage_admin/edit_page/" +
							getSegment(2) +
							"/?page=section_0"
					);
				});
			});
	}

	/**
	 * delete tag
	 *
	 * @param {string} index
	 * @param {string} tagIndex
	 */
	function deleteTagCard(index, tagIndex) {
		//
		$.ajax({
			url: baseUrl(
				"cms/" + getSegment(2) + "/tag/" + index + "/" + tagIndex
			),
			method: "delete",
		})
			.fail(handleErrorResponse)
			.done(function (resp) {
				_success(resp.msg, function () {
					window.location.href = baseUrl(
						"manage_admin/edit_page/" +
							getSegment(2) +
							"/?page=sectionTag" +
							tagIndex
					);
				});
			});
	}

	$(document).on("keyup", '[data-target="buttonLink"]', function () {
		//
		const to = $(this).data("target");

		$('[name="' + to + '"]').val(
			$(this)
			.val()
			.replace(/[^a-z0-9]/ig, "-")
			.replace(/-+/g, "-")
			.toLowerCase()
		);
	});

//
	$(".jsEditSection").click(function (event) {
		//
		event.preventDefault();
		event.stopPropagation();
		//
		const index = $(this).data("index");
		const title = $(this).data("title");

		event.preventDefault();
		//
		Modal(
			{
				Id: "jsEditModal",
				Loader: "jsEditModalLoader",
				Body: '<div id="jsEditModalBody"></div>',
				Title: "Edit Section",
			},
			function () {
				getPageUI("Edit", "edit", function () {

					$("#jsSectionTitle").val(title);
					$("#jsSectionIndex").val(index);

					//
					$("#jsEditForm").validate({
						rules: {
							title: { required: true },
						},
						submitHandler: function (form) {
							// get the form object
							let formDataObj = formArrayToObj(
								$(form).serializeArray()
							);
							// attach the section id
							formDataObj.append("section", "section0");
							formDataObj.append("index", "tags");
							//
							return processPageSection(
								formDataObj,
								$(".jsAddBtn"),
								"section0"
							);
						},
					});
				});
			}
		);

	});

});
