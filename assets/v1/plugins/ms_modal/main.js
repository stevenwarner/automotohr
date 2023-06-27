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
		$(`#jsMsModal`).remove();
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
					$(`#jsMsModal`).remove();
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
			if (document.getElementsByClassName("csModalBody").length){
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
