(function LMSAdaptorForSCORM() {
	/**
	 *
	 * @param {*} CMIElements
	 */
	function sendCourseToSave(CMIElements) {
		//
		return console.log({ CMIElements });
		//
		// WARNING: For POST requests, body is set to null by browsers.
		var data = JSON.stringify(CMIElements);
		var xhr = new XMLHttpRequest();
		xhr.withCredentials = true;
		xhr.addEventListener("readystatechange", function () {
			if (this.readyState === 4) {
				console.log(this.responseText);
			}
		});
		xhr.open("POST", "http://127.0.0.1:3000/lms/course/1/");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.setRequestHeader(
			"Authorization",
			"Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjoiNGI4Njc2ODYtZmY4Yy0xMWVkLTliZTAtMDI0MmFjMTMwMDAyIiwiY29tcGFueUlkIjowLCJlbXBsb3llZUlkIjoxLCJpYXQiOjE2ODU5NDI1MTYsImV4cCI6MTY4NjAyODkxNn0.6otkKlv1lw5og8oAbSUY37QSMKaIXwp_BeEJmBseU7E"
		);

		xhr.send(data);
	}

	//
	window.sendCourseToSave = sendCourseToSave;
})();
