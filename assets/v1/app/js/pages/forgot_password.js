$(function () {
	//
	$("#forgotForm").validate({
		ignore: ":hidden:not(select)",
		rules: {
			email: {
				required: true,
				email: true,
			},
		},
		messages: {
			email: {
				required:
					'<p class="error_message"><i class="fa fa-exclamation-circle"></i> Please provide a valid email.</p>',
			},
		},
	});
});
