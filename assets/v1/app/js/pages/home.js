$(function () {
	// translate
	$(".jsButtonAnimate").hover(
		function () {
			const moveWidth =
				$(this).find("p").outerWidth() + $(this).find("i").outerWidth();
			$(this).find("p").css("transform", "translate(20px,0)");
			$(this)
				.find("i")
				.css("transform", "translate(-" + moveWidth + "px,0)");
		},
		function () {
			$(this).find("p").css("transform", "translate(0,0)");
			$(this).find("i").css("transform", "translate(0,0)");
		}
	);
});
