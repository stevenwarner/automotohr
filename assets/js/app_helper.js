/**
 * Validate email address
 * @returns
 */
String.prototype.verifyEmail = function () {
	return this.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g) === null
		? false
		: true;
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

if (typeof $ !== "undefined") {
	// set filter height
	$(".jsFilterPanel").height(window.innerHeight);
	//
	$(document).on("click", ".jsFilterSectionBtn", function (event) {
		//
		event.preventDefault();
		//
		let key = $(this).data("key");
		//
		$(".jsFilterSection[data-key='" + key + "']").removeClass('hidden');
	});

	//
	$(document).on("click", ".jsFilterSectionHideBtn", function (event) {
		//
		event.preventDefault();
		//
		let key = $(this).data("key");
		//
		$(".jsFilterSection[data-key='" + key + "']").addClass('hidden');
	});
	
	//
	$(document).on("click", ".jsExpandAdminView", function (event) {
		//
		event.preventDefault();
		// 
		$(this).toggleClass('btn-success')
		$(".jsExpandContent").toggleClass('col-sm-12 col-md-12 col-lg-12 col-xs-12 col-lg-9 col-md-9 col-sm-9');
		$(".jsExpandSideBar").toggleClass(
			"hidden"
		);
	});

}
