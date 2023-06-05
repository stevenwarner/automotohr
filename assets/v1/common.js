// check if the browser version is old
generateBrowserAlert();

//
$(function () {
	$.ajaxSetup({
		headers: {
			Authorization: "Bearer " + apiAccessToken,
		},
		cache: false,
	});
});
