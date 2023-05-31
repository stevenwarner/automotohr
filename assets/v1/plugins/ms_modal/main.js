// Modal
function Modal(options, cb) {
	//
	let html = `
    <!-- Custom Modal -->
    <div class="csModal" id="${options.Id}">
        <div class="${options.Cl ? options.Cl : "container"}">
            <div class="csModalHeader">
                <h3 class="csModalHeaderTitle">
                    <span>
                    ${options.Title}
                    </span>
                    <span class="csModalButtonWrap">
                    ${
						options.Buttons !== undefined &&
						options.Buttons.length !== 0
							? options.Buttons.join("")
							: ""
					}
                        <button class="btn btn-black btn-cancel csW jsModalCancel" ${
							options.Ask === undefined ? "" : 'data-ask="no"'
						} title="Close this window">Cancel</button>
                    </span>
                    <div class="clearfix"></div>
                </h3>
            </div>
            <div class="csModalBody">
                <div class="csIPLoader jsIPLoader" data-page="${
					options.Loader
				}"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                ${options.Body}
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    `;
	//
	$(`#${options.Id}`).remove();
	$("body").append(html);
	$(`#${options.Id}`).fadeIn(300);
	//
	$("body").css("overflow-y", "hidden");
	$(`#${options.Id} .csModalBody`).css(
		"top",
		$(`#${options.Id} .csModalHeader`).height() + 50
	);
	if (cb !== undefined) {
		cb();
	}
}

//
$(document).on("click", ".jsModalCancel", (e) => {
	//
	e.preventDefault();
	//
	if ($(e.target).data("ask") != undefined) {
		//
		alertify
			.confirm("Any unsaved changes will be lost.", () => {
				//
				$(e.target).closest(".csModal").fadeOut(300);
				//
				$("body").css("overflow-y", "auto");
				//
				$("#ui-datepicker-div").remove();
			})
			.set("labels", {
				ok: "LEAVE",
				cancel: "NO, i WILL STAY",
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

// Loader
function ml(doShow, p, msg) {
	//
	p = p === undefined ? `.jsIPLoader` : `.jsIPLoader[data-page="${p}"]`;
	//
	if (doShow === undefined || doShow === false) $(p).hide();
	else $(p).show();
	//
	if (msg !== undefined) {
		$(".jsIPLoaderText").text(msg);
	}
	//
	if (!doShow) {
		//
		$(".jsIPLoaderText").text(
			"Please wait, while we are generating a preview."
		);
	}
}
