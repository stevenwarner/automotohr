$(function notifications() {
	const targets = {
		count: $("#js-notification-count"),
		box: $("#js-notification-box"),
	};

	loadNotifications();

	function loadNotifications() {
		$.get(
			baseUrl("notifications/get_notifications"),
			function (resp) {
				//
				if (resp.Status === false) {
					targets.count
						.parent()
						.find("i")
						.removeClass("faa-shake animated");
					console.log(resp.Response);
					return;
				}
				//
				var rows = "";
				resp.Data.map(function (v) {
					rows += "<li>";
					rows += '    <a href="' + v["link"] + '">';
					rows +=
						'        <span class="pull-left">' +
						v["title"] +
						" <b>(" +
						v["count"] +
						")</b></span>";
					rows +=
						'        <span class="pull-right"><i class="fa fa-eye"></i></span>';
					rows += "    </a>";
					rows += "</li>";
				});
				//
				targets.count.text(resp.Data.length);
				targets.box.prepend(rows);
				targets.count.parent().find("i").addClass("faa-shake animated");
			}
		);
	}
});
