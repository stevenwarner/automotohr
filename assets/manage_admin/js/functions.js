$(document).ready(function () {
	// Keep Active tab open
	$('a[data-toggle="tab"]').on("show.bs.tab", function (e) {
		localStorage.setItem("activeTab", $(e.target).attr("href"));
	});
	var activeTab = localStorage.getItem("activeTab");
	if (activeTab) {
		// $('#tabs a[href="' + activeTab + '"]').tab('show');
	}

	var url = window.location;
	$('ul.nav-tabs a[href="' + url + '"]')
		.parent()
		.addClass("active");

	$("ul.nav-tabs a")
		.filter(function () {
			return this.href == url;
		})
		.parent()
		.addClass("active");

	// Left Menu Toggle Function
	jQuery(".hr-closed-menu").click(function () {
		jQuery(this).next().slideToggle("1000");
		jQuery(this).toggleClass("hr-opened-menu");
	});
	// Jquery UI Date Picker Function
	jQuery(function () {
		$("#startdate")
			.datepicker({
				dateFormat: "mm-dd-yy",
				onSelect: function (selected) {
					var dt = $.datepicker.parseDate("mm-dd-yy", selected);
					dt.setDate(dt.getDate() + 1);
					$("#enddate").datepicker("option", "minDate", dt);
				},
			})
			.on("focusin", function () {
				$(this).prop("readonly", true);
			})
			.on("focusout", function () {
				$(this).prop("readonly", false);
			});

		$("#enddate")
			.datepicker({
				dateFormat: "mm-dd-yy",
				setDate: new Date(),
				onSelect: function (selected) {
					var dt = $.datepicker.parseDate("mm-dd-yy", selected);
					dt.setDate(dt.getDate() - 1);
					$("#startdate").datepicker("option", "maxDate", dt);
				},
			})
			.on("focusin", function () {
				$(this).prop("readonly", true);
			})
			.on("focusout", function () {
				$(this).prop("readonly", false);
			});
		jQuery(".expiryDate").datepicker({
			dateFormat: "mm-dd-yy",
			setDate: new Date(),
		});
		$(".ui-datepicker-trigger").each(function () {
			$(this).click(function () {
				$(this).datepicker("show");
			});
		});
		//jQuery("#enddate").datepicker({dateFormat: 'mm-dd-yy'});
	});
	// Search Area Toggle Function
	jQuery(".hr-search-criteria").click(function () {
		jQuery(this).next().slideToggle("10000");
		jQuery(this).toggleClass("opened");
	});
	// Email Template Toggle Function
	jQuery(".hr-spec-vars-title").click(function () {
		jQuery(".specific-vars").stop(true, true).slideToggle("slow");
		jQuery(this).toggleClass("down");
	});

	// Equal Height Boxes
	var heights = $(".eq-height")
			.map(function () {
				return $(this).height();
			})
			.get(),
		maxHeight = Math.max.apply(null, heights);

	$(".eq-height").height(maxHeight);
	// Table Header Fixed
	if (typeof tableHeadFixer !== 'undefined') $(".fixTable-header").tableHeadFixer();

	// Equal height Grid
	function ResetGridColumns() {
		$(".grid-columns").each(function () {
			// find all columns
			var $cs = $(this).children('[class*="col-"]');

			// reset the height
			$cs.css("height", "auto");

			// set the heights per row
			var rowWidth = $(this).width();
			var $curCols = $();
			var curMax = 0;
			var curWidth = 0;
			$cs.each(function () {
				var w = $(this).width();
				var h = $(this).height();
				if (curWidth + w <= rowWidth) {
					$curCols = $curCols.add(this);
					curWidth += w;
					if (h > curMax) curMax = h;
				} else {
					if ($curCols.length > 1)
						$curCols.css("height", curMax + "px");
					$curCols = $(this);
					curWidth = w;
					curMax = h;
				}
			});
			if ($curCols.length > 1) $curCols.css("height", curMax + "px");
		});
	}

	$(document).ready(function () {
		$(window).resize(function () {
			ResetGridColumns();
		});
		ResetGridColumns();
	});
	// New Code Goes here
});
