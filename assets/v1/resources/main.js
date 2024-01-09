$(function () {
	// set the xhr
	let XHR = null;
	let blogPage = 2;
	let resourceBlog = 2;
	let buttonPressed = true;
	/**
	 * load more blogs
	 */
	$(document).on("click", ".jsLoadMoreBlog", function (event) {
		//
		event.preventDefault();
		//
		if (XHR !== null) {
			return false;
		}
		// call the function
		const buttonRef = callButtonHook($(this), true, true);
		//
		XHR = $.ajax({
			url: baseUrl("resources/blog/load?page=" + blogPage),
			method: "GET",
		})
			.always(function () {
				// empty the call
				XHR = null;
				callButtonHook(buttonRef, false);
			})
			.fail(handleErrorResponse)
			.done(function (response) {
				//
				$("#jsBlogSection").append(response.view);
				//
				if (response.count == 0) {
					$(".jsLoadMoreBlog").remove();
				} else {
					blogPage++;
				}
			});
	});

	/**
	 * apply filter
	 */
	$(document).on("click", ".resources-checkbox", function () {
		//
		resourceBlog = 1;
		//
		buttonPressed = true;
		//
		$("#jsResourceSection").html("");
		//
		getMoreResources(true);
	});

	/**
	 * apply filter - keywords
	 */
	$("#jsSearchResources").keyup(function () {
		//
		resourceBlog = 1;
		//
		buttonPressed = true;
		//
		$("#jsResourceSection").html("");
		//
		getMoreResources(true);
	});

	/**
	 * get resources
	 */
	$(document).on("click", ".jsLoadMoreResources", function (event) {
		//
		event.preventDefault();
		//
		if (buttonPressed) {
			buttonPressed = false;
			resourceBlog = 2;
		}
		//
		getMoreResources(false);
	});

	$(document).on("click", "#jsSubscribeCommunity", function (event) {
		//
		event.preventDefault();
		//
		let email = $("#jsSubscriberEmail").val().trim();
		//
		if (!isEmail(email)) {
			return alertify.alert(
				"Error",
				"Please Enter valid email",
				function () {}
			);
		}
		//
		if (XHR !== null) {
			return;
		}
		//
		const buttonRef = callButtonHook($(this), true, true);
		//
		var obj = {
			scriber_email: email,
		};
		//
		XHR = $.ajax({
			method: "POST",
			url: baseUrl("subscribeCommunity"),
			data: obj,
		})
			.always(function () {
				XHR = null;
				callButtonHook(buttonRef, false);
			})
			.fail(function () {
				XHR = null;
				//
				return alertify.alert(
					"Error!",
					"Oops! Something went wrong. Please try again in a few moments.",
					function () {}
				);
			})
			.done(function (response) {
				XHR = null;
				//
				$("#exampleModal").modal("show");
				$("#jsSubscriberEmail").val("");
			});
		//
	});

	/**
	 * get the resources
	 */
	function getMoreResources(filterApplied) {
		//
		if (XHR !== null) {
			return;
		}
		// get the filter
		let filterObj = getFilterCheckbox();
		// call the function
		loader(true);
		//
		$(".jsLoadMoreResources").prop("disabled", false);

		XHR = $.ajax({
			url: baseUrl(
				"resources/resource/load?page=" +
					resourceBlog +
					"&keywords=" +
					filterObj.keywords +
					"&categories=" +
					filterObj.category
			),
			method: "GET",
		})
			.always(function () {
				// empty the call
				XHR = null;
				//
				loader(false);
			})
			.fail(handleErrorResponse)
			.done(function (response) {
				// empty the call
				XHR = null;
				//
				if (filterApplied) {
					$("#jsResourceSection").html(response.view);
				} else {
					$("#jsResourceSection").append(response.view);
				}
				//
				if (response.count === 0) {
					$(".jsLoadMoreResources").prop("disabled", true);
				} else {
					//
					resourceBlog++;
				}
			});
	}

	/**
	 * get the filter
	 * @returns object
	 */
	function getFilterCheckbox() {
		let categoryTypes = $("input[name=resourceType]:checked")
			.map(function () {
				return $(this).val();
			})
			.get();
		//
		let filterString = $("#jsSearchResources").val().trim();
		//
		return {
			category: categoryTypes,
			keywords: filterString,
		};
	}

	function isEmail(email) {
		var regex =
			/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}

	// Loader
	function loader(show_it, msg) {
		msg =
			msg === undefined
				? "Please, wait while we are processing your request."
				: msg;
		show_it =
			show_it === undefined || show_it == true || show_it === "show"
				? "show"
				: show_it;
		if (show_it === "show") {
			$("#js-loader").show();
			$("#js-loader-text").html(msg);
		} else {
			$("#js-loader").hide();
			$("#js-loader-text").html("");
		}
	}
});
