// console.log(apiURL, apiAccessToken);
$(function LMSCourses() {
	// set the xhr
	let XHR = null;
	// set the default filter
	let filterObj = {
		title: "",
		status: "all",
		jobTitleIds: [],
	};

	// get LMS default courses
	function getLMSDefaultCourses() {
		// check and abort previous calls
		if (XHR !== null) {
			XHR.abort();
		}
		// show the loader
		ml(true, "jsPageLoader");
		// make the call
		XHR = $.ajax({
			url: apiURL + "sa/lms/courses?" + getFilterAsString(),
			method: "GET",
			headers: {
				"MS-API-TOKEN": apiAccessToken,
			},
		})
			.success(function (response) {
				// empty the call
				XHR = null;
				// set the view
				setView(response);
			})
			.fail(function (response) {
				// empty the call
				XHR = null;
				// hide the loader
				ml(false, "jsPageLoader");
				//
				return alertify.alert(
					"Errors!",
					response.responseJSON.errors.join("<br />"),
					CB
				);
			});
	}

	// convert filter object to string
	function getFilterAsString() {
		//
		let str = "";
		// set title
		str += "title=" + encodeURI(filterObj["title"]);
		// set status
		str += "&status=" + encodeURI(filterObj["status"]);
		// set jobTitleIds
		str += "&job_titles=" + filterObj["jobTitleIds"].join(",");
		//
		return str;
	}

	/**
	 * Set the view
	 *
	 * @param {*} response
	 */
	function setView(response) {
        // check for empty records
        if (!response.success.length) {
            
        }
		console.log(response);
	}

	//
	getLMSDefaultCourses();
});
