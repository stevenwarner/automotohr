$(function benefits() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;

	/**
	 * add event
	 */
	$(document).on("click", ".jsAddBenefitCategory", function (event) {
		//
		event.preventDefault();
		//
		Modal(
			{
				Id: "jsAddBenefitCategoryModal",
				Loader: "jsAddBenefitCategoryModalLoader",
				Body: '<div id="jsAddBenefitCategoryModalBody"></div>',
				Title: "Add benefit category",
			},
			loadAddBenefitCategoryView
		);
	});

	/**
	 * saves benefit category
	 */
	$(document).on("click", ".jsBenefitCategorySave", function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			name: $(".jsName").val().trim(),
			description: $(".jsDescription").val().trim(),
		};
		//
		const errorsArray = [];
		// validation
		if (!obj.name) {
			errorsArray.push('"Name" is required.');
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		saveCategory(obj);
	});

	/**
	 * edit event
	 */
	$(document).on("click", ".jsBenefitCategoryEdit", function (event) {
		//
		event.preventDefault();
		//
		const id = $(this).closest("tr").data("id");
		//
		Modal(
			{
				Id: "jsEditBenefitCategoryModal",
				Loader: "jsEditBenefitCategoryModalLoader",
				Body: '<div id="jsEditBenefitCategoryModalBody"></div>',
				Title: "Edit benefit category",
			},
			function () {
				loadEditBenefitCategoryView(id);
			}
		);
	});

	/**
	 * updates benefit category
	 */
	$(document).on("click", ".jsBenefitCategoryUpdate", function (event) {
		//
		event.preventDefault();
		//
		const key = $(".jsKey").val();
		//
		const obj = {
			name: $(".jsName").val().trim(),
			description: $(".jsDescription").val().trim(),
		};
		//
		const errorsArray = [];
		// validation
		if (!obj.name) {
			errorsArray.push('"Name" is required.');
		}
		if (!key) {
			errorsArray.push('"Key" is required.');
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		updateCategory(obj, key);
	});

	/**
	 * add benefit event
	 */
	$(document).on("click", ".jsAddBenefit", function (event) {
		//
		event.preventDefault();
		//
		Modal(
			{
				Id: "jsAddBenefitModal",
				Loader: "jsAddBenefitModalLoader",
				Body: '<div id="jsAddBenefitModalBody"></div>',
				Title: "Add a benefit",
			},
			loadAddBenefitView
		);
	});

	/**
	 * saves benefit
	 */
	$(document).on("click", ".jsBenefitSave", function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			name: $(".jsName").val().trim(),
			description: $(".jsDescription").val().trim(),
			pretax: $(".jsPretax option:selected").val(),
			posttax: $(".jsPosttax option:selected").val(),
			imputed: $(".jsImputed option:selected").val(),
			healthcare: $(".jsHealthcare option:selected").val(),
			retirement: $(".jsRetirement option:selected").val(),
			yearly_limit: $(".jsYearlyLimit option:selected").val(),
			benefit_type: $(".jsType option:selected").val(),
		};
		//
		const errorsArray = [];
		// validation
		if (!obj.name) {
			errorsArray.push('"Name" is required.');
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		saveBenefit(obj);
	});

	/**
	 * edit benefit event
	 */
	$(document).on("click", ".jsEditBenefit", function (event) {
		//
		event.preventDefault();
		//
		const key = $(this).closest("tr").data("id");
		//
		Modal(
			{
				Id: "jsEditBenefitModal",
				Loader: "jsEditBenefitModalLoader",
				Body: '<div id="jsEditBenefitModalBody"></div>',
				Title: "Edit a benefit",
			},
			function () {
				loadEditBenefitView(key);
			}
		);
	});

	/**
	 * updates benefit
	 */
	$(document).on("click", ".jsBenefitUpdate", function (event) {
		//
		event.preventDefault();
		//
		const key = $(".jsKey").val();
		//
		const obj = {
			name: $(".jsName").val().trim(),
			description: $(".jsDescription").val().trim(),
			pretax: $(".jsPretax option:selected").val(),
			posttax: $(".jsPosttax option:selected").val(),
			imputed: $(".jsImputed option:selected").val(),
			healthcare: $(".jsHealthcare option:selected").val(),
			retirement: $(".jsRetirement option:selected").val(),
			yearly_limit: $(".jsYearlyLimit option:selected").val(),
			benefit_type: $(".jsType option:selected").val(),
		};
		//
		const errorsArray = [];
		// validation
		if (!obj.name) {
			errorsArray.push('"Name" is required.');
		}
		if (!key) {
			errorsArray.push('"Key" is required.');
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		updateBenefit(obj, key);
	});

	/**
	 * load garnishments
	 */
	function loadViews() {
		//
		$.ajax({
			url: baseUrl("sa/benefits/view"),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsBenefitCategoryBox").html(resp.categoryView);
				$("#jsBenefitBox").html(resp.benefitView);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");
			});
	}

	/**
	 * load add benefit view
	 */
	function loadAddBenefitCategoryView() {
		//
		$.ajax({
			url: baseUrl("sa/benefits/category/add/view"),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsAddBenefitCategoryModalBody").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				ml(false, "jsAddBenefitCategoryModalLoader");
			});
	}

	/**
	 * load add benefit view
	 */
	function saveCategory(obj) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsAddBenefitCategoryModalLoader",
			"Please wait while we are saving the category."
		);
		//
		XHR = $.ajax({
			url: baseUrl("sa/benefits/category"),
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
				ml(false, "jsAddBenefitCategoryModalLoader");
			});
	}

	/**
	 * load add benefit view
	 */
	function loadEditBenefitCategoryView(id) {
		//
		$.ajax({
			url: baseUrl("sa/benefits/category/" + id),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsEditBenefitCategoryModalBody").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				ml(false, "jsEditBenefitCategoryModalLoader");
			});
	}

	/**
	 * load add benefit view
	 */
	function updateCategory(obj, key) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsEditBenefitCategoryModalLoader",
			"Please wait while we are saving the category."
		);
		//
		XHR = $.ajax({
			url: baseUrl("sa/benefits/category/" + key),
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
				ml(false, "jsEditBenefitCategoryModalLoader");
			});
	}

	/**
	 * load add benefit view
	 */
	function loadAddBenefitView() {
		//
		$.ajax({
			url: baseUrl("sa/benefits/add"),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsAddBenefitModalBody").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				ml(false, "jsAddBenefitModalLoader");
			});
	}

	/**
	 * saves benefit
	 */
	function saveBenefit(obj) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsAddBenefitModalLoader",
			"Please wait while we are saving the benefit."
		);
		//
		XHR = $.ajax({
			url: baseUrl("sa/benefits/add"),
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
				ml(false, "jsAddBenefitModalLoader");
			});
	}

	/**
	 * load edit benefit view
	 */
	function loadEditBenefitView(key) {
		//
		$.ajax({
			url: baseUrl("sa/benefits/" + key),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsEditBenefitModalBody").html(resp.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				ml(false, "jsEditBenefitModalLoader");
			});
	}

	/**
	 * updates benefit
	 */
	function updateBenefit(obj, key) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsEditBenefitModalLoader",
			"Please wait while we are updating the benefit."
		);
		//
		XHR = $.ajax({
			url: baseUrl("sa/benefits/" + key),
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
				ml(false, "jsEditBenefitModalLoader");
			});
	}

	loadViews();
});
