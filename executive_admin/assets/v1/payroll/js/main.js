(function () {
	let modelId;
	let additionalHeight = 0;
	// Modal
	function Modal(options, cb) {
		//
		let html = `
			<!-- Custom Modal -->
			<div class="csModal jsMsModal" id="${options.Id}">
				<div class="${options.Cl ? options.Cl : "container"}">
					<div class="csModalHeader">
						<h3 class="csModalHeaderTitle">
							<span>${options.Title}</span>
							<span class="csModalButtonWrap">
							${
								options.Buttons !== undefined &&
								options.Buttons.length !== 0
									? options.Buttons.join("")
									: ""
							}
								<button class="btn btn-black btn-cancel csW jsModalCancel" ${
									options.Ask === undefined
										? ""
										: 'data-ask="no"'
								} title="Close this window">Cancel</button>
							</span>
							<div class="clearfix"></div>
						</h3>
					</div>
					<div class="csModalBody">
						<div class="csIPLoader jsIPLoader" data-page="${options.Loader}">
							<div class="csIPLoaderBox">
								<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i><br><br>
								<br>
								<span class="jsIPLoaderText">Please wait while we process your request.</span>
							</div>
						</div>
						${options.Body}
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			`;
		// save the current modal reference
		modelId = options.Id;
		// save the header height
		additionalHeight = $(`#${options.Id} .csModalHeader`).height() + 50;
		// remove the modals
		$(`.jsMsModal`).remove();
		// remove specific modal
		$(`#${options.Id}`).remove();
		// append the modal to body
		$("body").append(html);
		// show the modal
		$(`#${options.Id}`).fadeIn(300);
		// set overflow of body to none
		$("body").css("overflow-y", "hidden");
		// set the header height
		$(`#${options.Id} .csModalBody`).css("top", additionalHeight);
		// call the CallBack if set
		if (cb !== undefined) {
			cb();
		}
	}

	/**
	 * Captures the modal close event
	 */
	$(document).on("click", ".jsModalCancel", (e) => {
		//
		e.preventDefault();
		//
		if ($(e.target).data("ask") == "yes") {
			//
			alertify
				.confirm("Any unsaved changes will be lost.", () => {
					//
					$(e.target).closest(".csModal").fadeOut(300);
					//
					$("body").css("overflow-y", "auto");
					//
					$("#ui-datepicker-div").remove();
					$(`.jsMsModal`).remove();
				})
				.set("labels", {
					ok: "LEAVE",
					cancel: "NO, I WILL STAY",
				})
				.set("title", "Notice!");
		} else {
			//
			$(e.target).closest(".csModal").fadeOut(300);
			//
			$("body").css("overflow-y", "auto");
			$(`.jsMsModal`).remove();
			//
			$("#ui-datepicker-div").remove();
		}
	});

	/**
	 * Handles loader show and hide
	 *
	 * @param {bool} doShow
	 * @param {string} p
	 * @param {string} msg
	 */
	function ml(doShow, p, msg) {
		// set the loader reference
		p = p === undefined ? `.jsIPLoader` : `.jsIPLoader[data-page="${p}"]`;
		// when modal is set
		if (modelId !== undefined) {
			// always scroll to top when loader appear
			if (document.getElementsByClassName("csModalBody").length) {
				document.getElementsByClassName("csModalBody")[0].scrollTop = 0;
			}
		}
		// only appears when loader is shown
		if (modelId !== undefined && doShow) {
			// set the loader height to body height
			$(".jsIPLoader").height(
				$(`#${modelId}Body`).height() + additionalHeight
			);
		}
		// hide the modal
		if (doShow === undefined || doShow === false) $(p).hide();
		else $(p).show();
		// place text
		if (msg !== undefined) {
			$(".jsIPLoaderText").text(msg);
		}
		// set to default text
		if (!doShow) {
			//
			$(".jsIPLoaderText").text(
				"Please wait, while we are generating a preview."
			);
		}
	}
	// set reference to window
	window._ml = window.ml = ml;
	window.Model = window.Modal = Modal;
})();


/**
 * Validate email address
 * @returns
 */
String.prototype.verifyEmail = function () {
	return this.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g) === null
		? false
		: true;
};

String.prototype.isValidYoutubeLink = function () {
	return this.match(
		/^(https?\:\/\/)?((www\.)?youtube\.com|youtu\.be)\/.+$/g
	) === null
		? false
		: true;
};

String.prototype.isValidVimeoLink = function () {
	return this.match(
		/^(http|https)?:\/\/(www\.|player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?)$/gim
	) === null
		? false
		: true;
};

String.prototype.isValidInteger = function () {
	return this.match(/^[1-9]\d*$/g) === null ? false : true;
};

if (typeof ml === "undefined") {
	/**
	 * Loader
	 *
	 * @param {bool}   action
	 * @param {string} id
	 */
	function ml(action, id) {
		//
		if (action) {
			$(".jsIPLoader[data-page='" + id + "']").show();
		} else {
			$(".jsIPLoader[data-page='" + id + "']").hide();
		}
	}
}

if (typeof CB === "undefined") {
	/**
	 * Empty callback
	 */
	function CB() {}
}

if (typeof getErrorsStringFromArray === "undefined") {
	/**
	 * Error message
	 *
	 * @param {*} errorArray
	 * @param {*} errorMessage
	 * @returns
	 */
	function getErrorsStringFromArray(errorArray, errorMessage) {
		return (
			"<strong><p>" +
			(errorMessage
				? errorMessage
				: "Please, resolve the following errors") +
			"</p></strong><br >" +
			errorArray.join("<br />")
		);
	}
}

if (typeof getBrowserVersion === "undefined") {
	function getBrowserVersion() {
		var ua = navigator.userAgent;
		var tem;
		var M =
			ua.match(
				/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i
			) || [];
		if (/trident/i.test(M[1])) {
			tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
			return "IE " + (tem[1] || "");
		}
		if (M[1] === "Chrome") {
			tem = ua.match(/\b(OPR|Edge)\/(\d+)/);
			if (tem != null)
				return tem.slice(1).join(" ").replace("OPR", "Opera");
		}
		M = M[2]
			? [M[1], M[2]]
			: [navigator.appName, navigator.appVersion, "-?"];
		if ((tem = ua.match(/version\/(\d+)/i)) != null) M.splice(1, 1, tem[1]);
		return M;
	}
}

if (typeof generateBrowserAlert === "undefined") {
	function generateBrowserAlert() {
		//
		const compatibleListObj = {
			chrome: 55,
			edge: 14,
			firefox: 52,
			opera: 42,
			safari: 10.1,
			chromeandroid: 55,
			firefoxandroid: 52,
			operaandroid: 42,
			safariios: 10.3,
			samsumginternet: 6.0,
		};
		// get browser version
		const browserVersion = getBrowserVersion();
		//
		if (
			compatibleListObj[browserVersion[0].toLowerCase()] &&
			browserVersion[1] <
				compatibleListObj[browserVersion[0].toLowerCase()]
		) {
			return alert(
				"This module require '" +
					browserVersion[0] +
					"' with version greater or equal then '" +
					browserVersion[1] +
					"'."
			);
		}
	}
}

if (typeof uploadFile === "undefined") {
	/**
	 * Upload file to the server
	 * @param {*} fileObject
	 * @returns
	 */
	function uploadFile(fileObject) {
		return new Promise(function (resolve) {
			// create form instance
			const formData = new FormData();
			// set the file object
			formData.append("file", fileObject);
			// push the file to server
			$.ajax({
				url: apiURL + "uploader",
				method: "POST",
				timeout: 0,
				processData: false,
				mimeType: "multipart/form-data",
				contentType: false,
				data: formData,
			})
				.success(function (response) {
					resolve(response);
				})
				.fail(function (error) {
					resolve(error.responseText);
				});
		});
	}
}

if (typeof uploadStream === "undefined") {
	/**
	 * Upload stream to the server
	 * @param {*} streamData
	 * @returns
	 */
	function uploadStream(streamData) {
		return new Promise(function (resolve) {
			// push the file to server
			$.ajax({
				url: apiURL + "uploader/stream",
				method: "POST",
				timeout: 0,
				contentType: "application/json",
				data: JSON.stringify({ stream: streamData }),
			})
				.success(function (response) {
					resolve(response);
				})
				.fail(function () {
					resolve({});
				});
		});
	}
}

if (typeof $ !== "undefined") {
	// set filter height
	$(".jsFilterPanel").height($(document).height());
	//
	$(document).on("click", ".jsFilterSectionBtn", function (event) {
		//
		event.preventDefault();
		//
		let key = $(this).data("key");
		//
		$(".jsFilterSection[data-key='" + key + "']").removeClass("hidden");
	});

	//
	$(document).on("click", ".jsFilterSectionHideBtn", function (event) {
		//
		event.preventDefault();
		//
		let key = $(this).data("key");
		//
		$(".jsFilterSection[data-key='" + key + "']").addClass("hidden");
	});

	//
	$(document).on("click", ".jsExpandAdminView", function (event) {
		//
		event.preventDefault();
		//
		$(this).toggleClass("btn-success");
		$(".jsExpandContent").toggleClass(
			"col-sm-12 col-md-12 col-lg-12 col-xs-12 col-lg-9 col-md-9 col-sm-9"
		);
		$(".jsExpandSideBar").toggleClass("hidden");
	});
}

if (typeof handleErrorResponse === "undefined") {
	/**
	 * Handle AJAX errors
	 * @param {*} response
	 * @returns
	 */
	function handleErrorResponse(response) {
		// when connection is lost
		if (response.status == 0) {
			//
			return alertify.alert(
				"Errors!",
				"The connection to the server has been lost. Kindly reach out to the system administrator for assistance.",
				CB
			);
		}
		//
		const parsedJSON =
			response.responseJSON || JSON.parse(response.responseText);
		// when error object came in
		return alertify.alert("Errors!", parsedJSON.errors.join("<br />"), CB);
	}
}

if (typeof getQuestionsFromArray === "undefined") {
	/**
	 * Error message
	 *
	 * @param {*} errorArray
	 * @param {*} errorMessage
	 * @returns
	 */
	function getQuestionsFromArray(errorArray, errorMessage) {
		return (
			"<strong><p>" +
			(errorMessage
				? errorMessage
				: "Please, provide the following question answer") +
			"</p></strong><br >" +
			errorArray.join("<br />")
		);
		let json = response.responseJSON || JSON.parse(response.responseText);
		// when error object came in
		return alertify.alert("Errors!", json.errors.join("<br />"), CB);
	}
}

if (typeof baseUrl === "undefined") {
	/**
	 * get the base url
	 *
	 * @param {string} appendUrl
	 * @returns
	 */
	function baseUrl(appendUrl = "") {
		// return the url
		return window.location.origin + "/" + appendUrl;
	}
}


/**
 *  Earning types
 *
 * @package AutomotoHR
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 */
$(function manageEarningTypes() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;
	/**
	 * holds the modal id
	 */
	let modalId = "jsEarningTypesFlowModal";
	/**
	 * holds the earning id
	 */
	let earningId = 0;
	/**
	 * holds the page loader
	 */
	let pageLoader = "jsPageLoader";

	/**
	 * capture the view admin event
	 */
	$(document).on("click", ".jsMenuTrigger", function (event) {
		//
		event.preventDefault();
		//
		loadEditView($(this).data("step"));
	});

	/**
	 * deactivate
	 */
	$(".jsDeactivateEarningType").click(function (event) {
		//
		event.preventDefault();
		//
		const earningTypeId = $(this).closest("tr").data("id");
		//
		return alertify
			.confirm(
				"Do you really want to deactivate this earning type?<br /> This action is not revertible.",
				function () {
					startDeactivateLink(earningTypeId);
				}
			)
			.setHeader("Confirm!");
	});

	/**
	 * add an earning type
	 */
	$(".jsAddEarningType").click(function (event) {
		//
		event.preventDefault();
		//
		Modal(
			{
				Id: "jsAddEarningType",
				Title: "Add an Earning Type",
				Loader: "jsAddEarningTypeLoader",
				Body: '<div id="jsAddEarningTypeBody"></div>',
			},
			function () {
				loadView("add");
			}
		);
	});

	/**
	 * edit an earning type
	 */
	$(".jsUpdateEarningType").click(function (event) {
		//
		event.preventDefault();
		//
		earningId = $(this).closest("tr").data("id");
		//
		Modal(
			{
				Id: "jsEditEarningType",
				Title: "Edit an Earning Type",
				Loader: "jsEditEarningTypeLoader",
				Body: '<div id="jsEditEarningTypeBody"></div>',
			},
			function () {
				loadEditView("edit");
			}
		);
	});

	/**
	 * add an earning type
	 */
	$(document).on("submit", "#jsEarningForm", function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			name: $("#jsEarningName").val().trim(),
		};
		//
		let errorArray = [];
		// validation
		if (!obj.name) {
			errorArray.push('"Name" is missing.');
		}
		//
		if (errorArray.length) {
			return alertify.alert(
				"Error!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		//
		ml(
			true,
			`jsAddEarningTypeLoader`,
			"Please wait, while we are creating earning type."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/earnings/add"),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				$(".jsModalCancel").trigger("click");
				//
				return alertify.alert("Success!", resp.msg, function () {
					window.location.reload();
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `jsAddEarningTypeLoader`);
			});
	});

	/**
	 * edi an earning type
	 */
	$(document).on("submit", "#jsEarningEditForm", function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			name: $("#jsEarningName").val().trim(),
		};
		//
		let errorArray = [];
		// validation
		if (!obj.name) {
			errorArray.push('"Name" is missing.');
		}
		//
		if (errorArray.length) {
			return alertify.alert(
				"Error!",
				getErrorsStringFromArray(errorArray),
				CB
			);
		}
		//
		ml(
			true,
			`jsEditEarningTypeLoader`,
			"Please wait, while we are updating earning type."
		);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/earnings/edit/" + earningId + ""),
			method: "POST",
			data: obj,
		})
			.success(function (resp) {
				//
				$(".jsModalCancel").trigger("click");
				//
				return alertify.alert("Success!", resp.msg, function () {
					window.location.reload();
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, `jsEditEarningTypeLoader`);
			});
	});

	/**
	 * deactivates an earning type
	 *
	 * @param {number} earningTypeId The id of the earning type
	 */
	function startDeactivateLink(earningTypeId) {
		// show the loader
		ml(true, pageLoader);
		//
		XHR = $.ajax({
			url: baseUrl("payrolls/earnings/deactivate/" + earningTypeId),
			method: "DELETE",
		})
			.success(function () {
				return alertify.alert(
					"Success!",
					"You have successfully deactivated the earning type.",
					function () {
						window.location.reload();
					}
				);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, pageLoader);
			});
	}

	/**
	 * Load add page
	 * @param {string} step
	 */
	function loadView(step) {
		//
		_ml(true, `jsAddEarningTypeLoader`);
		//
		let url = "payrolls/earnings/" + step;
		//
		XHR = $.ajax({
			url: baseUrl(url),
			method: "GET",
			caches: false,
		})
			.success(function (response) {
				//
				$(`#jsAddEarningTypeBody`).html(response.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				_ml(false, `jsAddEarningTypeLoader`);
			});
	}

	/**
	 * Load edit page
	 * @param {string} step
	 */
	function loadEditView(step) {
		//
		_ml(true, `jsEditEarningTypeLoader`);
		//
		let url = "payrolls/earnings/" + step + "/" + earningId;
		//
		XHR = $.ajax({
			url: baseUrl(url),
			method: "GET",
			caches: false,
		})
			.success(function (response) {
				//
				$(`#jsEditEarningTypeBody`).html(response.view);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				_ml(false, `jsEditEarningTypeLoader`);
			});
	}

	//
	ml(false, pageLoader);
});


