/**
 * @author: Mubashir Ahmed
 * @version: 1.0
 * @package: File uploader (msFileUploader)
 * @description: Upload files using drag an drop / choose from file.
 *               It depends on jQuery.
 * @example:
 *
 *  $('#fileInputReference').msFileUploader({
 *      fileLimit: '2MB', // Default is '2MB', Use -1 for no limit (Optional)
 *      allowedTypes: ['jpg','jpeg','png','gif','pdf','doc','docx','rtf','ppt','xls','xlsx','csv'],  (Optional)
 *      text: 'Click / Drag to upload', // (Optional)
 *      onSuccess: (file, event) => {}, // file will the uploaded file object and event will be the actual event  (Optional)
 *      onError: (errorCode, event) => {}, // errorCode will either 'size' or 'type' and event will be the actual event  (Optional)
 *      placeholderImage: '' // Default is empty ('') but can be set any image  (Optional)
 *  })
 *
 *  $('#fileInputReference').msFileUploader('get'); // It will return the uploaded file object
 *                                                    with an addition index of 'hasError'.
 *                                                    hasError will be true there was an error
 *                                                    with file and false when everything is okay.
 */
(function ($) {
	//
	let instances = {};
	//
	$.fn.msFileUploader = function (opt) {
		// Save the current instance
		let _this = this.length > 1 ? this[0] : this;
		//
		if (typeof opt === "string") {
			switch (opt) {
				case "get":
					return instances[_this.selector];
				case "clear":
					return delete instances[_this.selector];
			}
			return;
		}
		//
		let oFile = {},
			//
			randKey = Math.ceil(Math.random() * 1000),
			//
			options = {};
		//
		instances[_this.selector] = oFile;
		//
		options["s3"] =
			(opt !== undefined && opt.s3) ||
			`https://automotohrattachments.s3.amazonaws.com/`;
		options["path"] =
			opt === undefined || opt.path === undefined ? true : opt.path;
		options["placeholderImage"] =
			(opt !== undefined && opt.placeholderImage) || "";
		options["fileLimit"] =
			opt === undefined || $.inArray("mp4", opt.allowedTypes) === -1
				? -1
				: opt.fileLimit;
		options["allowedTypes"] = (opt !== undefined && opt.allowedTypes) || [
			"jpg",
			"jpeg",
			"png",
			"gif",
			"pdf",
			"doc",
			"docx",
			"rtf",
			"ppt",
			"xls",
			"xlsx",
			"csv",
			"zip",
		];
		options["text"] =
			(opt !== undefined && opt.text) || `Click / Drag to upload`;
		options["onSuccess"] =
			(opt !== undefined && opt.onSuccess) || function () {};
		options["onError"] =
			(opt !== undefined && opt.onError) || function () {};
		options["onClear"] =
			(opt !== undefined && opt.onClear) || function () {};

		//
		options["mainDivName"] = `jsUploadArea${randKey}`;
		options["mainImageViewer"] = `jsUploadedImageArea${randKey}`;
		options["errorMSG"] = `jsUploadedAreaError${randKey}`;
		options["jsMFUPreviewFile"] = `jsMFUPreviewFile${randKey}`;
		options["jsMFUClearFile"] = `jsMFUClearFile${randKey}`;
		options["jsMFUModal"] = `jsMFUModal${randKey}`;
		options["jsMFUD"] = `jsMFUD${randKey}`;

		//
		let errorCodes = {
			size: `File size exceeded from ${options.fileLimit}`,
			type: `Invalid file type.`,
		};

		// Remover
		const destroyView = () => {
			$(_this.selector).siblings("div.jsMainUploadAreaWrapper").remove();
			$.fn.msFileUploader.__proto__.instances[_this.selector] = {};
		};

		// Remove any previous instance
		destroyView();

		// Setters
		const destroyOldInstance = () => {
			$(_this.selector).siblings("div.csUploadArea").remove();
			$.fn.msFileUploader.__proto__.instances[_this.selector] = {};
		};

		if ($.fn.msFileUploader.instances[_this.selector] !== undefined) {
			destroyOldInstance();
		}

		// Setters
		const setMainView = () => {
			$(_this).before(getWrapper());
		};

		//
		const generateModalHTML = (tag, filename) => {
			return `
            <!-- Modal -->
            <div class="modal fade" id="${options["jsMFUModal"]}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #81b431; color: #ffffff;">
                            <h5 class="modal-title">${filename}</h5>
                        </div>
                        <div class="modal-body" style="min-height: 300px;">
                            <div class="csMFULoader jsMFULoader"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success ${options["jsMFUD"]}">Download</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            `;
		};

		// Getters
		const getWrapper = () => {
			return `
            <div class="jsMainUploadAreaWrapper">
                <div class="csUploadArea" id="${options.mainDivName}">
                    <div class="csUploadInnerArea">
                        <p>${options.text}</p>
                        ${
							options.fileLimit != -1
								? `<em style="color:rgb(255, 155, 0);"><i class="fa fa-warning"></i> Maximum allowed file size is ${options.fileLimit}</em><br />`
								: ""
						}
                        <span>(${options.allowedTypes})</span> <br /><br />
                        <span style="color: red; font-weight: bold; display: none;" id="${
							options.errorMSG
						}"></span>
                        <div style="${
							options.placeholderImage ? "" : "display: none;"
						}">
                            <button class="btn btn-success" id="${
								options["jsMFUPreviewFile"]
							}" data-key="${
				options.placeholderImage
			}">Preview File</button>
                            <button class="btn btn-default" id="${
								options["jsMFUClearFile"]
							}" style="display: none;">Clear File</button>
                        </div>
                    </div>
                    <!-- Image -->
                </div>
                <div class="clearfix"></div>
                <!-- <div style="${
					options.placeholderImage ? "" : "display: none;"
				}">
                <br /> 
                    <label><strong>FileName:</strong> <span>${
						options.placeholderImage
							? options.placeholderImage[0]
							: ""
					}</span></label>
                    <img src="${
						options.placeholderImage
							? options.placeholderImage[1]
							: ""
					}" class="csUploadAreaImage" id="${
				options.mainImageViewer
			}" />
                </div> -->
            </div>
            `;
		};

		//
		const getFileExtension = (filename) => {
			// Will always return last extension of file name
			let t =
				filename === undefined
					? oFile.name.split(".")
					: filename.split(".");
			return t[t.length - 1].toLowerCase().trim();
		};

		//
		const getLimit = () => {
			//
			let sp = options.fileLimit
				.replace(/\s+/, "")
				.toUpperCase()
				.split(/([0-9]+)([A-Za-z]+)/);
			sp = [sp[1], sp[2]];
			//
			if (sp[1] == "MB") return sp[0] * 1000000;
			else if (sp[1] == "GB") return sp[0] * 1000 * 1000000;
			else if (sp[1] == "KB") return sp[0] * 1000;
			return 0;
		};

		//
		const readURL = (input) => {
			if (input) {
				let reader = new FileReader();
				reader.onload = (e) => {
					$(`#${options["jsMFUPreviewFile"]}`).data(
						"key",
						e.target.result
					);
					$(`#${options["jsMFUPreviewFile"]}`).parent().show();
					$(`#${options["jsMFUClearFile"]}`).show();
				};
				reader.readAsDataURL(input); // convert to base64 string
			}
		};

		// Validators
		const validateFile = (e) => {
			//
			$(`#${options["jsMFUPreviewFile"]}`).parent().hide(0);
			$(`#${options["jsMFUClearFile"]}`).hide(0);
			$(`#${options.errorMSG}`).hide();
			$(`#${options.mainImageViewer}`).parent().hide();
			$(`#${options.mainImageViewer}`).attr("src", null);
			$(`#${options.mainImageViewer}`)
				.parent()
				.find("label span")
				.text("");
			if (oFile.hasOwnProperty("hasError")) oFile.hasError = false;
			// Check the file type
			if ($.inArray(getFileExtension(), options.allowedTypes) === -1) {
				$(`#${options.errorMSG}`).text(errorCodes["type"]).show();
				options.onError(errorCodes["type"], e);
				oFile.hasError = true;
				oFile.errorCode = errorCodes["type"];
				//
				instances[_this.selector] = oFile;
				oFile = {};
				return;
			}

			// Check file size
			if (options.fileLimit !== -1 && oFile.size > getLimit()) {
				$(`#${options.errorMSG}`).text(errorCodes["size"]).show();
				options.onError(errorCodes["size"], e);
				oFile.hasError = true;
				oFile.errorCode = errorCodes["size"];
				//
				instances[_this.selector] = oFile;
				oFile = {};
				return;
			}
			//
			readURL(oFile);
			//
			oFile.hasError = false;
			//
			instances[_this.selector] = oFile;
			//
			options.onSuccess(oFile, e);
		};

		//
		this.getValue = () => {
			return oFile;
		};

		// Events

		// When file is dropped
		$(document).on("drop", `#${options.mainDivName}`, function (e) {
			e.preventDefault();
			oFile = e.originalEvent.dataTransfer.files[0];
			validateFile(e);
		});

		// When cursor entered the area
		$(document).on("dragenter", `#${options.mainDivName}`, function (e) {
			e.preventDefault();
			$(this).css("opacity", ".8");
		});

		// When cursor leaves the area
		$(document).on("dragover", `#${options.mainDivName}`, function (e) {
			e.preventDefault();
			$(this).css("opacity", "1");
		});

		// When drop area is clicked
		$(document).on("click", `#${options.mainDivName}`, function (e) {
			//
			e.preventDefault();
			//
			$(_this).trigger("click");
		});

		// When file is uploaded
		$(document).on("change", _this.selector, function (e) {
			oFile = e.target.files[0];
			validateFile(e);
		});

		//
		$(document).on("click", `.${options["jsMFUD"]}`, function (e) {
			e.preventDefault();
			e.stopPropagation();

			let filename = "",
				fullFileName = "";

			if (
				$(`#${options["jsMFUPreviewFile"]}`)
					.data("key")
					.match(/data:/) === null
			) {
				filename = $(`#${options["jsMFUPreviewFile"]}`).data("key");
				fullFileName = options["path"]
					? options["s3"] + filename
					: filename;
			} else {
				filename = oFile.name;
				fullFileName = $(`#${options["jsMFUPreviewFile"]}`).data("key");
			}
			//
			let link = document.createElement("a");
			link.target = "_blank";
			link.href = fullFileName;
			link.download = filename;
			link.dispatchEvent(new MouseEvent("click"));
		});

		// Clear the uploaded file
		$(document).on("click", `#${options["jsMFUClearFile"]}`, function (e) {
			e.preventDefault();
			e.stopPropagation();
			//
			$(`#${options["jsMFUPreviewFile"]}`).parent().hide(0);
			$(`#${options["jsMFUClearFile"]}`).hide(0);
			oFile = {};
			instances[_this.selector] = oFile;
			//
			if (options.placeholderImage != "") {
				$(`#${options["jsMFUPreviewFile"]}`).data(
					"key",
					options["placeholderImage"]
				);
				$(`#${options["jsMFUPreviewFile"]}`).parent().show(0);
			}
			//
			options.onClear();
		});

		// When preview button is clicked
		$(document).on(
			"click",
			`#${options["jsMFUPreviewFile"]}`,
			function (e) {
				e.preventDefault();
				e.stopPropagation();
				//
				let filename = "",
					extension = "",
					fullFileName = "",
					tag = "";

				if ($(this).data("key").match(/data:/) === null) {
					filename = $(this).data("key");
					extension = getFileExtension(filename);
					fullFileName = options["path"]
						? options["s3"] + filename
						: filename;
					//
					if (
						$.inArray(extension, [
							"xls",
							"xlsx",
							"ppt",
							"pptx",
							"doc",
							"docx",
							"rtf",
							"csv",
						]) !== -1
					)
						tag = `<iframe frameborder="0" width="100%" height="600" class="jsMFUIframe" src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURI(
							fullFileName
						)}"></iframe>`;
					else if (
						$.inArray(extension, [
							"png",
							"jpg",
							"jpeg",
							"gif",
							"svg",
						]) !== -1
					)
						tag = `<img class="img-responsive" src="${fullFileName}" />`;
					else if ($.inArray(extension, ["mp4"]) !== -1)
						tag = `<video style="width: 100%;" class="img-responsive" src="${fullFileName}" controls></video>`;
					else
						tag = `<iframe frameborder="0" style="width: 100%; height: 600px;" class="jsMFUIframe" src="https://docs.google.com/gview?url=${fullFileName}&embedded=true"></iframe>`;
				} else {
					filename = oFile.name;
					extension = getFileExtension();
					fullFileName = $(this).data("key");
					//
					if (
						$.inArray(extension, [
							"png",
							"jpg",
							"jpeg",
							"gif",
							"svg",
						]) !== -1
					)
						tag = `<img class="img-responsive" src="${fullFileName}" />`;
					else if ($.inArray(extension, ["mp4"]) !== -1)
						tag = `<video class="img-responsive" src="${fullFileName}" controls></video>`;
					else
						tag = `<iframe frameborder="0" style="width: 100%; height: 600px;" class="jsMFUIframe" src="${fullFileName}"></iframe>`;
				}
				// Geneate and load modal on dom
				const modalHTML = generateModalHTML(tag, filename);
				$(`#${options["jsMFUModal"]}`).remove();
				$("body").append(modalHTML);
				$(`#${options["jsMFUModal"]}`).modal("show");
				//
				if (tag.match(/img/) !== null)
					$(`#${options["jsMFUModal"]} .modal-body`).html(tag);
				else {
					$(`#${options["jsMFUModal"]} .modal-body`).html(tag);
				}
			}
		);

		// Callers
		setMainView();

		return this;
	};

	//
	$.fn.msFileUploader.__proto__.instances = instances;
})(jQuery || $);
