$(function () {

    async function makeSecureCallToApiServer(
        url,
        options
    ) {
        return new Promise(function (resolve, reject) {
            const defaultOptions = {
                url: `${apiURL}${url}`,
                headers: {
                    Authorization: "Bearer " + apiAccessToken
                },
                cache: false,
            };

            const finalOptions = $.extend(true, {}, options, defaultOptions);

            $
                .ajax(finalOptions)
                .fail(function (resp) {
                    reject(resp);
                })
                .done(function (resp) {
                    resolve(resp)
                });

        });
    }

    window.makeSecureCallToApiServer = makeSecureCallToApiServer;

    $(document).on('click', '.rating-stars .control--radio', function () {
        $(this).siblings().find('.star').css('color', '#fd7a2a');
        $(this).prevAll().find('.star').css('color', '#fd7a2a');
        $(this).find('.star').css('color', '#fd7a2a');
        $(this).nextAll().find('.star').css('color', '#ccc');
    });

    $(document).on("click", ".jsCollapseBtn", function (event) {
        event.preventDefault();
        $("#" + $(this).data("target")).toggleClass("hidden")
    });
});


function getDescriptionPreview(obj) {
    // set the HTML
    let rows = "";

    rows += `<div class="panel panel-default jsQuestionDescription" data-id="desc_${obj.question_id}">`;
    rows += `<div class="panel-heading">`;
    rows += `   <div class="row">`;
    rows += `       <div class="col-xs-9">`;
    rows += `           <h1 class="panel-text-heading text-medium jsCollapseBtn" data-target="collapse${obj.question_id}">`;
    rows += `               <strong>`;
    rows += `               <i class="fa fa-align-left"></i>&nbsp;`;
    rows += obj.plainDescription;
    rows += `               </strong>`;
    rows += `           </h1>`;
    rows += `       </div>`;
    rows += `       <div class="col-xs-3 text-right panel-text-heading" style="margin-top: 5px;">`;
    rows += `               <button type="button" class="btn btn-warning jsTemplateEditDescription" title="Edit Question">`;
    rows += `                   <i class="fa fa-edit"></i>`;
    rows += `               </button>`;
    rows += `               <button type="button" class="btn btn-danger jsRemoveDescription" title="Remove Question">`;
    rows += `                   <i class="fa fa-trash"></i>`;
    rows += `               </button>`;
    rows += `       </div>`;
    rows += `   </div>`;
    rows += `</div>`;
    // panel ends
    rows += `   </div>`;
    rows += `</div>`;

    return rows;
}


function getQuestionPreview(questionObject, answer = "") {

    // set the HTML
    let rows = "";

    rows += `<div class="panel panel-default jsQuestionView" data-id="${questionObject.question_id}">`;

    rows += `<div class="panel-heading">`;
    rows += `   <div class="row">`;
    rows += `       <div class="col-xs-8">`;
    rows += `           <h1 class="panel-text-heading text-medium jsCollapseBtn" data-target="collapse${questionObject.question_id}">`;
    rows += `               <strong>`;
    rows += `               <i class="fa fa-${getIconFromQuestionType(questionObject.question_type)}"></i>`;
    rows += `               ${questionObject.question_title.length > 30 ? questionObject.question_title.substr(0, 30) : questionObject.question_title}...`;
    rows += `               </strong>`;
    rows += `           </h1>`;
    rows += `       </div>`;
    rows += `       <div class="col-xs-4 text-right panel-text-heading" style="margin-top: 5px;">`;
    rows += `               <button type="button" class="btn btn-warning jsEditQuestion" title="Edit Question">`;
    rows += `                   <i class="fa fa-edit"></i>`;
    rows += `               </button>`;
    rows += `               <button type="button" class="btn btn-danger jsRemoveQuestion" title="Remove Question">`;
    rows += `                   <i class="fa fa-trash"></i>`;
    rows += `               </button>`;
    rows += `       </div>`;
    rows += `   </div>`;
    rows += `</div>`;

    rows += `   <div class="panel-body hidden" id="collapse${questionObject.question_id}">`;
    // Question title
    rows += `       <div class="row">`;
    rows += `           <div class="col-sm-12 col-md-${questionObject["video_file_name"] ? "8" : "12"}">`;
    rows += `               <h1 class="text-medium">`;
    rows += `                   <strong>`;
    rows += `                   ${questionObject["question_title"]}`;
    // check for required question
    rows += `                   ${questionObject["question_required"] == true ? "<span class=\"text-danger\">*</span>" : ""}`;
    rows += `                   </strong>`;
    rows += `               </h1>`;
    rows += `               <p class="text-medium">`;
    rows += `                   ${questionObject["question_content"]}`;
    rows += `               </p>`;
    rows += `           </div>`;
    // check if there is an attachment
    if (questionObject["video_file_name"] && questionObject["video_file_name"] != "none") {
        rows += `           <div class="col-sm-12 col-md-4">`;
        rows += getFileWithTag(questionObject["video_file_name"], { style: "width: 100%", controls: "true" });
        rows += `           </div>`;
    }
    rows += `       </div>`;
    rows += `       <br/>`;

    // question type area
    rows += `       <div class="row">`;
    rows += `           <div class="col-sm-12 col-md-12">`;

    if (questionObject["question_type"] === "text") {
        rows += `<textarea type="text" name="textarea_${questionObject["question_id"]}" class="form-control cs-text-answer" is_required="${questionObject["question_required"] == true ? 1 : 0}" rows="5" style="resize: none;" placeholder="Write the answer here...."></textarea>`;
    } else if (questionObject["question_type"] === "yes_no") {
        rows += `<div class="row">`;
        rows += `	<div class="col-sm-12">`;
        rows += `       <label class="control control--radio">`;
        rows += `           <input type="radio" name="yes_no_${questionObject["question_id"]}" value="yes"> Yes`;
        rows += `	        <div class="control__indicator"></div>`;
        rows += `	    </label> &nbsp;&nbsp;`;
        rows += `		<label class="control control--radio">`;
        rows += `			<input type="radio" name="yes_no_${questionObject["question_id"]}" value="no"> No`;
        rows += `			<div class="control__indicator"></div>`;
        rows += `		</label>`;
        rows += `	</div>`;
        rows += `</div>`;
    } else if (questionObject["question_type"] === "rating") {
        rows += `<div class="row">`;
        rows += `   <div class="col-sm-12">`;
        rows += `       <div class="rating-container" style="text-align: center; font-size: 2rem; display: flex; justify-content: center; width: 100%;">`;
        rows += `           <div class="rating-stars" style="display: flex; justify-content: space-between; width: 100%;">`;
        for (let i = 1; i <= questionObject["choice_list"]["rating"]; i++) {
            rows += `               <label class="control control--radio" style="cursor: pointer; flex: 1; text-align: center;">`;
            rows += `                   <input type="radio" name="rating_${questionObject["question_id"]}" value="${i}" style="display: none;">`;
            rows += `                   <span class="star" style="color: #ccc; font-size: 50px;">&#9733;</span>`;
            rows += `               </label>`;
        }
        rows += `           </div>`;
        rows += `       </div>`;
        rows += `   </div>`;
        rows += `   <div class="col-xs-6 text-left">`;
        rows += `           <span class="rating-text text-medium" style="margin-right: 10px;">${questionObject["choice_list"]["min"] || "Poor"}</span>`;
        rows += `   </div>`;
        rows += `   <div class="col-xs-6 text-right">`;
        rows += `           <span class="rating-text text-medium" style="margin-left: 10px;">${questionObject["choice_list"]["max"] || "Excellent"}</span>`;
        rows += `   </div>`;
        rows += `</div>`;
    } else {
        const inputType = questionObject["question_type"] === "multiple_choice" ? "checkbox" : "radio";
        const inputName = (questionObject["question_type"] === "multiple_choice" ? "multiple_choice" : "single_choice") +
            "_" + questionObject["question_id"];
        //
        for (const index in questionObject["choice_list"]) {
            const current = questionObject["choice_list"][index];

            rows += `
            <label class="control control--${inputType}">
                <input type="${inputType}" name="${inputName}" value="${current.answer_choice}"> ${current.answer_choice}
                <div class="control__indicator"></div>
            </label> &nbsp;&nbsp;`;
        }
    }

    rows += `           </div>`;
    rows += `       </div>`;

    // panel ends
    rows += `   </div>`;
    rows += `</div>`;

    return rows;
};

function getQuestionViewPreview(questionObject, answer = "") {
    if (questionObject["choices_json"]) {
        questionObject["choice_list"] = JSON.parse(questionObject["choices_json"]);
    }
    // set the HTML
    let rows = "";

    rows += `<div class="panel panel-default jsQuestionView" data-id="${questionObject.question_id}">`;

    rows += `<div class="panel-heading">`;
    rows += `   <div class="row">`;
    rows += `       <div class="col-xs-8">`;
    rows += `           <h1 class="panel-text-heading text-medium jsCollapseBtn" data-target="collapse${questionObject.question_id}">`;
    rows += `               <strong>`;
    rows += `               <i class="fa fa-${getIconFromQuestionType(questionObject.question_type)}"></i>`;
    rows += `               ${questionObject.question_title.length > 30 ? questionObject.question_title.substr(0, 30) : questionObject.question_title}...`;
    rows += `               </strong>`;
    rows += `           </h1>`;
    rows += `       </div>`;
    rows += `       <div class="col-xs-4 text-right panel-text-heading" style="margin-top: 5px;">`;
    rows += `               <button type="button" class="btn btn-warning jsEditQuestion" title="Edit Question">`;
    rows += `                   <i class="fa fa-edit"></i>`;
    rows += `               </button>`;
    rows += `               <button type="button" class="btn btn-danger jsRemoveQuestion" title="Remove Question">`;
    rows += `                   <i class="fa fa-trash"></i>`;
    rows += `               </button>`;
    rows += `       </div>`;
    rows += `   </div>`;
    rows += `</div>`;

    rows += `   <div class="panel-body hidden" id="collapse${questionObject.question_id}">`;
    // Question title
    rows += `       <div class="row">`;
    rows += `           <div class="col-sm-12 col-md-${questionObject["video_file_name"] ? "8" : "12"}">`;
    rows += `               <h1 class="text-medium">`;
    rows += `                   <strong>`;
    rows += `                   ${questionObject["question_title"]}`;
    // check for required question
    rows += `                   ${questionObject["question_required"] == true ? "<span class=\"text-danger\">*</span>" : ""}`;
    rows += `                   </strong>`;
    rows += `               </h1>`;
    rows += `               <p class="text-medium">`;
    rows += `                   ${questionObject["question_content"]}`;
    rows += `               </p>`;
    rows += `           </div>`;
    // check if there is an attachment
    if (questionObject["video_file_name"] && questionObject["video_file_name"] != "none") {
        rows += `           <div class="col-sm-12 col-md-4">`;
        rows += getFileWithTag(questionObject["video_file_name"], { style: "width: 100%", controls: "true" });
        rows += `           </div>`;
    }
    rows += `       </div>`;
    rows += `       <br/>`;

    // question type area
    rows += `       <div class="row">`;
    rows += `           <div class="col-sm-12 col-md-12">`;

    if (questionObject["question_type"] === "text") {
        rows += `<textarea type="text" name="textarea_${questionObject["question_id"]}" class="form-control cs-text-answer" is_required="${questionObject["question_required"] == true ? 1 : 0}" rows="5" style="resize: none;" placeholder="Write the answer here...."></textarea>`;
    } else if (questionObject["question_type"] === "yes_no") {
        rows += `<div class="row">`;
        rows += `	<div class="col-sm-12">`;
        rows += `       <label class="control control--radio">`;
        rows += `           <input type="radio" name="yes_no_${questionObject["question_id"]}" value="yes"> Yes`;
        rows += `	        <div class="control__indicator"></div>`;
        rows += `	    </label> &nbsp;&nbsp;`;
        rows += `		<label class="control control--radio">`;
        rows += `			<input type="radio" name="yes_no_${questionObject["question_id"]}" value="no"> No`;
        rows += `			<div class="control__indicator"></div>`;
        rows += `		</label>`;
        rows += `	</div>`;
        rows += `</div>`;
    } else if (questionObject["question_type"] === "rating") {
        rows += `<div class="row">`;
        rows += `   <div class="col-sm-12">`;
        rows += `       <div class="rating-container" style="text-align: center; font-size: 2rem; display: flex; justify-content: center; width: 100%;">`;
        rows += `           <div class="rating-stars" style="display: flex; justify-content: space-between; width: 100%;">`;
        for (let i = 1; i <= questionObject["choice_list"]["rating"]; i++) {
            rows += `               <label class="control control--radio" style="cursor: pointer; flex: 1; text-align: center;">`;
            rows += `                   <input type="radio" name="rating_${questionObject["question_id"]}" value="${i}" style="display: none;">`;
            rows += `                   <span class="star" style="color: #ccc; font-size: 50px;">&#9733;</span>`;
            rows += `               </label>`;
        }
        rows += `           </div>`;
        rows += `       </div>`;
        rows += `   </div>`;
        rows += `   <div class="col-xs-6 text-left">`;
        rows += `           <span class="rating-text text-medium" style="margin-right: 10px;">${questionObject["choice_list"]["min"] || "Poor"}</span>`;
        rows += `   </div>`;
        rows += `   <div class="col-xs-6 text-right">`;
        rows += `           <span class="rating-text text-medium" style="margin-left: 10px;">${questionObject["choice_list"]["max"] || "Excellent"}</span>`;
        rows += `   </div>`;
        rows += `</div>`;
    } else {
        const inputType = questionObject["question_type"] === "multiple_choice" ? "checkbox" : "radio";
        const inputName = (questionObject["question_type"] === "multiple_choice" ? "multiple_choice" : "single_choice") +
            "_" + questionObject["question_id"];
        //
        for (const index in questionObject["choice_list"]) {
            const current = questionObject["choice_list"][index];

            rows += `
            <label class="control control--${inputType}">
                <input type="${inputType}" name="${inputName}" value="${current.answer_choice}"> ${current.answer_choice}
                <div class="control__indicator"></div>
            </label> &nbsp;&nbsp;`;
        }
    }

    rows += `           </div>`;
    rows += `       </div>`;

    // panel ends
    rows += `   </div>`;
    rows += `</div>`;

    return rows;
};


function generateQuestionPreviewView(questionObject) {
    if (questionObject["choices_json"]) {
        questionObject["choice_list"] = JSON.parse(questionObject["choices_json"]);
    }
    // set the HTML
    let rows = "";

    rows += `<div class="panel panel-default jsQuestionView" data-id="${questionObject.question_id}">`;
    if (questionObject["question_type"] === "tag") {

        rows += `<div class="panel-heading">`;
        rows += `   <div class="row">`;
        rows += `       <div class="col-xs-8">`;
        rows += `           <h1 class="panel-text-heading text-medium jsCollapseBtn" data-target="collapse${questionObject.question_id}">`;
        rows += `               <strong>`;
        rows += `               <i class="fa fa-${getIconFromQuestionType(questionObject.question_type)}"></i>`;
        rows += `               ${questionObject.question_title}`;
        rows += `               </strong>`;
        rows += `           </h1>`;
        rows += `       </div>`;;
        rows += `   </div>`;
        rows += `</div>`;
    }

    if (questionObject["question_type"] != "tag") {
        rows += `   <div class="panel-body" id="collapse${questionObject.question_id}">`;
        // Question title
        rows += `       <div class="row">`;
        rows += `           <div class="col-sm-12 col-md-${questionObject["video_file_name"] ? "8" : "12"}">`;
        rows += `               <h1 class="text-medium">`;
        rows += `                   <strong>`;
        rows += `               <i class="fa fa-${getIconFromQuestionType(questionObject.question_type)}"></i>`;

        rows += `                   ${questionObject["question_title"]}`;
        // check for required question
        rows += `                   ${questionObject["question_required"] == true ? "<span class=\"text-danger\">*</span>" : ""}`;
        rows += `                   </strong>`;
        rows += `               </h1>`;
        rows += `               <p class="text-medium">`;
        rows += `                   ${questionObject["question_content"]}`;
        rows += `               </p>`;
        rows += `           </div>`;
        // check if there is an attachment
        if (questionObject["video_file_name"] && questionObject["video_file_name"] != "none") {
            rows += `           <div class="col-sm-12 col-md-4">`;
            rows += getFileWithTag(questionObject["video_file_name"], { style: "width: 100%", controls: "true" });
            rows += `           </div>`;
        }
        rows += `       </div>`;
        rows += `       <br/>`;

        // question type area
        rows += `       <div class="row">`;
        rows += `           <div class="col-sm-12 col-md-12">`;

        if (questionObject["question_type"] === "text") {
            rows += `<textarea type="text" name="textarea_${questionObject["question_id"]}" class="form-control cs-text-answer" is_required="${questionObject["question_required"] == true ? 1 : 0}" rows="5" style="resize: none;" placeholder="Write the answer here...."></textarea>`;
        } else if (questionObject["question_type"] === "yes_no") {
            rows += `<div class="row">`;
            rows += `	<div class="col-sm-12">`;
            rows += `       <label class="control control--radio">`;
            rows += `           <input type="radio" name="yes_no_${questionObject["question_id"]}" value="yes"> Yes`;
            rows += `	        <div class="control__indicator"></div>`;
            rows += `	    </label> &nbsp;&nbsp;`;
            rows += `		<label class="control control--radio">`;
            rows += `			<input type="radio" name="yes_no_${questionObject["question_id"]}" value="no"> No`;
            rows += `			<div class="control__indicator"></div>`;
            rows += `		</label>`;
            rows += `	</div>`;
            rows += `</div>`;
        } else if (questionObject["question_type"] === "rating") {
            rows += `<div class="row">`;
            rows += `   <div class="col-sm-12">`;
            rows += `       <div class="rating-container" style="text-align: center; font-size: 2rem; display: flex; justify-content: center; width: 100%;">`;
            rows += `           <div class="rating-stars" style="display: flex; justify-content: space-between; width: 100%;">`;
            for (let i = 1; i <= questionObject["choice_list"]["rating"]; i++) {
                rows += `               <label class="control control--radio" style="cursor: pointer; flex: 1; text-align: center;">`;
                rows += `                   <input type="radio" name="rating_${questionObject["question_id"]}" value="${i}" style="display: none;">`;
                rows += `                   <span class="star" style="color: #ccc; font-size: 50px;">&#9733;</span>`;
                rows += `               </label>`;
            }
            rows += `           </div>`;
            rows += `       </div>`;
            rows += `   </div>`;
            rows += `   <div class="col-xs-6 text-left">`;
            rows += `           <span class="rating-text text-medium" style="margin-right: 10px;">${questionObject["choice_list"]["min"] || "Poor"}</span>`;
            rows += `   </div>`;
            rows += `   <div class="col-xs-6 text-right">`;
            rows += `           <span class="rating-text text-medium" style="margin-left: 10px;">${questionObject["choice_list"]["max"] || "Excellent"}</span>`;
            rows += `   </div>`;
            rows += `</div>`;
        } else {
            const inputType = questionObject["question_type"] === "multiple_choice" ? "checkbox" : "radio";
            const inputName = (questionObject["question_type"] === "multiple_choice" ? "multiple_choice" : "single_choice") +
                "_" + questionObject["question_id"];
            //
            for (const index in questionObject["choice_list"]) {
                const current = questionObject["choice_list"][index];

                rows += `
            <label class="control control--${inputType}">
                <input type="${inputType}" name="${inputName}" value="${current.answer_choice}"> ${current.answer_choice}
                <div class="control__indicator"></div>
            </label> &nbsp;&nbsp;`;
            }
        }
    }

    rows += `           </div>`;
    rows += `       </div>`;

    // panel ends
    rows += `   </div>`;
    rows += `</div>`;

    return rows;
};

function getIconFromQuestionType(questionType) {
    if (questionType === "rating") {
        return "star-o";
    } else if (questionType === "text") {
        return "comment-o";
    } else if (questionType === "tag") {
        return "align-left";
    } else {
        return "square-o";
    }
}
/**
 * Generates the tag for the uploaded files
 * @param {string} incomingFile
 * @param {object} props
 * @returns
 */
function getFileWithTag(incomingFile, props = {}, _hasPrefix = "y") {
    // props to string
    let strProps = "";
    // check if props are set
    if (Object.keys(props).length) {
        // loop through the props
        for (let index in props) {
            strProps += `${index}="${props[index]}" `;
        }
    }
    //
    if (
        incomingFile.match(
            /^(https?\:\/\/)?((www\.)?youtube\.com|youtu\.be)\/.+$/g
        ) !== null
    ) {
        return `
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/${youtubeLinkParser(
            incomingFile
        )}" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		</div>`;
    }
    //
    if (
        incomingFile.match(
            /^(http|https)?:\/\/(www\.|player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?)$/gim
        ) !== null
    ) {
        return `
		<div class="embed-responsive embed-responsive-16by9">
			<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/${vimeoLinkParser(
            incomingFile
        )}"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		</div>`;
    }

    // check if zip
    if (incomingFile.match(/.zip/i) !== null) {
        return `
		<a class="btn btn-black" 
			title="Download file"
			placement="top"
			href="${getFileWithUrl(incomingFile)}"
		><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download file</a>`;
    }
    // check if PPT
    if (incomingFile.match(/.ppt|.pptx/i) !== null) {
        return `
		<iframe 
			${strProps}
			src="https://view.officeapps.live.com/op/embed.aspx?src=${getFileWithUrl(
            incomingFile
        )}"
			frameborder="0"
		></iframe>`;
    }

    if (incomingFile.match(/.jpg|.jpeg|.png|.gif/i) !== null) {
        return `
		<img 
			${strProps}
			src="${getFileWithUrl(incomingFile)}"
		>`;
    }

    if (incomingFile.match(/.mp3|.wav/i) !== null) {
        return `
		 <audio controls>
        	<source src="${getFileWithUrl(incomingFile)}" type="audio/mp3">
    	</audio>`;
    }

    // just return as video
    return `
        <video ${strProps}>
            <source type="video/mp4" src="${getFileWithUrl(incomingFile)}" />
        </video>`;
};


/**
 * Get Youtube video ID from link
 * @param {string} link
 * @returns
 */
function youtubeLinkParser(link) {
    const regExp =
        /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
    let match = link.match(regExp);
    return match && match[7].length == 11 ? match[7] : false;
}

/**
 * Generates the S3 URL
 * @param {string} incomingFile
 * @returns
 */
function getFileWithUrl(incomingFile) {
    return "https://automotohrattachments.s3.amazonaws.com/" + incomingFile;
};

/**
 * Get Vimeo video ID from link
 * @param {string} link
 * @returns
 */
function vimeoLinkParser(link) {
    const regExp =
        /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
    const match = link.match(regExp);
    return match && match[5].length == 9 ? match[5] : false;
}

function getSlug(description) {
    return $("<div>").html(description).text().trim()
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '') // Remove invalid characters
        .replace(/\s+/g, '-') // Replace spaces with hyphens
        .replace(/-+/g, '-'); // Collapse multiple hyphens
}

function generateRandomAndUniqueId() {
    // Generate a random number between 1 and 1000000
    const randomNumber = Math.floor(Math.random() * 1000000) + 1;

    // Get the current timestamp
    const timestamp = Date.now();

    // Combine the random number and timestamp to create a unique ID
    const uniqueId = `${randomNumber}-${timestamp}`;

    return uniqueId;
}