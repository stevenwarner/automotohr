//
window.timeoff = {
	PaginationOBJ: {},
};
//
holidayDatesArray = [];
//
if (holidayDates.length > 0) {
	holidayDates.map((hld) => {
		holidayDatesArray.push(hld.from_date);
	});
}

//
$(".btn-apply-filter").click(function () {
	$(this).parent().next(".filter-content").toggle();
});

// Mobile menu
$(".csMobile span i").click(function (e) {
	$(".csVertical").toggle();
});
//
$(".jsCalendarView").click(function (e) {
	//
	e.preventDefault();
	//
	Modal(
		{
			Id: "calendarModal",
			Title: "Calendar",
			Body: `<iframe src="${baseURL}calendar/my_events/iframe${
				window.location.pathname.match(
					/(lms)|(employee_management_system)|(dashboard)/gi
				) !== null
					? "/ems"
					: ""
			}" width="100%" height="${$(window).height() - 90}"></iframe>`,
			Loader: "jsCalendarLoader",
		},
		() => {
			ml(false, "jsCalendarLoader");
		}
	);
});

$(document).on("click", "#calendarModal .jsModalCancel", function () {
	window.location.href = window.location.href;
});

//
function getImageURL(img) {
	if (img == "" || img == null) {
		return `${baseURL}assets/images/img-applicant.jpg`;
	} else return `${awsURL}${img}`;
}

//
function getEmployeeId(i, n) {
	return n == "" || n == null ? i : n;
}

//
function ucwords(str) {
	return (str + "").replace(/^([a-z])|\s+([a-z])/g, function ($1) {
		return $1.toUpperCase();
	});
}

//
function isEmpty(str) {
	return str == "" || str == null || str == undefined ? true : false;
}

//
$(document).on("keyup", ".js-number", function () {
	$(this).val(
		$(this)
			.val()
			.replace(/[^0-9]/, "")
	);
});

//
$(".jsToggle").click(function (e) {
	//
	e.preventDefault();
	//
	if ($(this).find("i").hasClass("fa-minus-circle")) {
		$(this)
			.find("i")
			.removeClass("fa-minus-circle")
			.addClass("fa-plus-circle");
		//
		$(`div[data-target="${$(this).data("target")}"]`).hide();
	} else {
		$(this)
			.find("i")
			.removeClass("fa-plus-circle")
			.addClass("fa-minus-circle");
		//
		$(`div[data-target="${$(this).data("target")}"]`).show();
	}
});

//
function remakeEmployeeName(o, d) {
	//
	let r = "";
	//
	if (d === undefined) r += o.first_name + " " + o.last_name;
	//
	if (o.job_title != "" && o.job_title != null) r += " (" + o.job_title + ")";
	//
	r += " [";
	//
	if (
		typeof o["is_executive_admin"] !== undefined &&
		o["is_executive_admin"] != 0
	)
		r += "Executive ";
	//
	if (o["access_level_plus"] == 1 && o["pay_plan_flag"] == 1)
		r += o["access_level"] + " Plus / Payroll";
	else if (o["access_level_plus"] == 1) r += o["access_level"] + " Plus";
	else if (o["pay_plan_flag"] == 1) r += o["access_level"] + " Payroll";
	else r += o["access_level"];
	//
	r += "]";
	//
	if (o.timezone) {
		r += " (" + o.timezone + ")";
	}

	//
	if(o.employee_type){
		r += " (" + formateEmployeeJobType(o.employee_type) + ")";
	}
	//
	return r;
}

//
function getArrayFromMinutes(minutes, defaultTimeFrame, slug) {
	var r = {};
	r["timeFrame"] = defaultTimeFrame;
	r["originalMinutes"] = minutes;
	r["D:H:M"] = {};
	//
	r["D:H:M"]["days"] = parseInt(minutes / (defaultTimeFrame * 60));
	r["D:H:M"]["hours"] = parseInt((minutes % (defaultTimeFrame * 60)) / 60);
	r["D:H:M"]["minutes"] = parseInt((minutes % (defaultTimeFrame * 60)) % 60);

	r["H:M"] = {};
	r["H:M"]["hours"] = parseInt(minutes / 60);
	r["H:M"]["minutes"] = parseInt(minutes % 60);

	r["D"] = {};
	r["D"]["days"] = (minutes / (defaultTimeFrame * 60)).toFixed(2);

	r["M"] = {};
	r["M"]["minutes"] = minutes;

	r["H"] = {};
	r["H"]["hours"] = (minutes / 60).toFixed(2);

	r["active"] = r[slug];
	r["text"] = "";

	if (r[slug]["days"] !== undefined)
		r["text"] +=
			r[slug]["days"] + " day" + (r[slug]["days"] > 1 ? "s" : "") + ", ";
	if (r[slug]["hours"] !== undefined)
		r["text"] +=
			r[slug]["hours"] +
			" hour" +
			(r[slug]["hours"] > 1 ? "s" : "") +
			", ";
	if (r[slug]["minutes"] !== undefined)
		r["text"] +=
			r[slug]["minutes"] +
			" minute" +
			(r[slug]["minutes"] > 1 ? "s" : "") +
			", ";
	r["text"] = r["text"].substring(0, r["text"].length - 2);

	return r;
}

//
function getAccrualPlans(type, same) {
	//
	var r = [];
	//
	var is_error = false;
	//
	// if($('#js-accrual-method-'+( type )+'').val() == 'unlimited') return r;
	//
	if ($(".js-plan-row-" + type + "").length === 0) return r;
	//
	$(".js-plan-row-" + type + "").map(function (i) {
		//
		if (is_error) return;
		//
		var l = {
			accrualRate: $(this).find(".js-pt").val().trim(),
			accrualType: $(this).find(".js-py").val().trim(),
			accrualTypeM: $(this).find(".js-pyt").val(),
		};
		//
		if (
			(l.accrualType == 0 || l.accrualType == "") &&
			(l.accrualRate == 0 || l.accrualRate == "") &&
			(l.accrualTypeM == 0 || l.accrualTypeM == "")
		)
			return;
		//
		if (l.accrualType == 0 || l.accrualType == "") is_error = true;
		if (l.accrualRate == 0 || l.accrualRate == "") is_error = true;
		if (l.accrualTypeM == 0 || l.accrualTypeM == "") is_error = true;
		//
		if (is_error) {
			//
			alertify.alert(
				"WARNING!",
				"Accrual fields are mendatory for " + ++i + " plan."
			);
			//
			return;
		}
		//
		r.push(
			type == "edit-reset" || same !== undefined
				? {
						plan_title: l.accrualType,
						accrual_rate: l.accrualRate,
						accrual_type_m: l.accrualTypeM,
				  }
				: l
		);
	});
	//
	return is_error ? is_error : r;
}

// Loader
function ml(doShow, p) {
	//
	p = p === undefined ? `.jsIPLoader` : `.jsIPLoader[data-page="${p}"]`;
	//
	if (doShow === undefined || doShow === false) $(p).hide();
	else $(p).show();
}

//
function getField(f) {
	let field = $(f).val();
	if (!field || field == 0 || field == null) return 0;
	return field;
}

// Modal
function Modal(options, cb) {
	//
	let html = `
    <!-- Custom Modal -->
    <div class="csModal" id="${options.Id}">
        <div class="container-fluid">
            <div class="csModalHeader">
                <h3 class="csModalHeaderTitle">
                    <span>
                    ${options.Title}
                    </span>
                    <span class="csModalButtonWrap">
                    ${
						options.Buttons !== undefined &&
						options.Buttons.length !== 0
							? options.Buttons.join("")
							: ""
					}
                        <button class="btn btn-black jsModalCancel" ${
							options.Ask === undefined ? "" : 'data-ask="no"'
						} title="Close this window">Cancel</button>
                    </span>
                    <div class="clearfix"></div>
                </h3>
            </div>
            <div class="csModalBody">
                <div class="csIPLoader jsIPLoader" data-page="${
					options.Loader
				}"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                ${options.Body}
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    `;
	//
	$(`#${options.Id}`).remove();
	$("body").append(html);
	$(`#${options.Id}`).fadeIn(300);
	//
	$("body").css("overflow-y", "hidden");
	$(`#${options.Id} .csModalBody`).css(
		"top",
		$(`#${options.Id} .csModalHeader`).height() + 50
	);
	cb();
}

//
$(document).on("click", ".jsModalCancel", (e) => {
	//
	e.preventDefault();
	//
	if ($(e.target).data("ask") != undefined) {
		//
		alertify
			.confirm("Any unsaved changes will be lost.", () => {
				//
				$(e.target).closest(".csModal").fadeOut(300);
				//
				$("body").css("overflow-y", "auto");
				//
				$("#ui-datepicker-div").remove();
			})
			.set("labels", {
				ok: "LEAVE",
				cancel: "NO, i WILL STAY",
			})
			.set("title", "Notice!");
	} else {
		//
		$(e.target).closest(".csModal").fadeOut(300);
		//
		$("body").css("overflow-y", "auto");
		//
		$("#ui-datepicker-div").remove();
	}
});

// Pagination
// Pagination
// Get previous page
$(document).on("click", ".js-pagination-prev", pagination_event);
// Get first page
$(document).on("click", ".js-pagination-first", pagination_event);
// Get last page
$(document).on("click", ".js-pagination-last", pagination_event);
// Get next page
$(document).on("click", ".js-pagination-next", pagination_event);
// Get page
$(document).on("click", ".js-pagination-shift", pagination_event);
// TODO convert it into a plugin
function load_pagination(limit, list_size, target_ref, page_type) {
	//
	var obj = window.timeoff.PaginationOBJ[page_type];
	// parsing to int
	limit = parseInt(limit);
	obj["page"] = parseInt(obj["page"]);
	// get paginate array
	var page_array = paginate(
		obj["count"],
		obj["Main"]["page"],
		limit,
		list_size
	);
	// append the target ul
	// to top and bottom of table
	var rows = "";
	rows += '<div class="">';
	rows += '<div class="col-lg-12">';
	rows += '   <div class="row pto-pagination">';
	rows += '       <div class="col-xs-12 col-lg-3">';
	rows +=
		'           <div class="pagination-left-content js-showing-target">';
	rows += '               <div class="js-show-record"></div>';
	rows += "           </div>";
	rows += "       </div>";
	rows += '       <div class="col-xs-12 col-lg-9">';
	rows += '           <nav aria-label="Pagination">';
	rows +=
		'               <ul class="pagination cs-pagination js-pagination"></ul>';
	rows += "           </nav>";
	rows += "       </div>";
	rows += "   </div>";
	rows += "</div>";
	rows += "</div>";

	target_ref.html(rows);
	// set rows append table
	var target = target_ref.find(".js-pagination");
	var targetShowing = target_ref.find(".js-showing-target");
	// get total items number
	var total_records = page_array.total_pages;
	// load pagination only there
	// are more than one page
	if (obj["count"] >= limit) {
		// generate li for
		// pagination
		var rows = "";
		// move to one step back
		rows +=
			'<li class="page-item"><a href="javascript:void(0)" data-page-type="' +
			page_type +
			'" class="' +
			(obj["Main"]["page"] == 1 ? "" : "js-pagination-first") +
			'">First</a></li>';
		rows +=
			'<li class="page-item"><a href="javascript:void(0)" data-page-type="' +
			page_type +
			'" class="' +
			(obj["Main"]["page"] == 1 ? "" : "js-pagination-prev") +
			'">&laquo;</a></li>';
		// generate 5 li
		$.each(page_array.pages, function (index, val) {
			rows +=
				'<li class="' +
				(val == obj["Main"]["page"] ? "active page-item" : "") +
				'"><a href="javascript:void(0)" data-page-type="' +
				page_type +
				'" data-page="' +
				val +
				'" class="' +
				(obj["Main"]["page"] != val ? "js-pagination-shift" : "") +
				'">' +
				val +
				"</a></li>";
		});
		// move to one step forward
		rows +=
			'<li class="page-item"><a href="javascript:void(0)" data-page-type="' +
			page_type +
			'" class="' +
			(obj["Main"]["page"] == page_array.total_pages
				? ""
				: "js-pagination-next") +
			'">&raquo;</a></li>';
		rows +=
			'<li class="page-item"><a href="javascript:void(0)" data-page-type="' +
			page_type +
			'" class="' +
			(obj["Main"]["page"] == page_array.total_pages
				? ""
				: "js-pagination-last") +
			'">Last</a></li>';
		// append to ul
		target.html(rows);
	}
	// append showing of records
	targetShowing.html(
		"<p>Showing " +
			(page_array.start_index + 1) +
			" - " +
			(page_array.end_index != -1 ? page_array.end_index + 1 : 1) +
			" of " +
			obj["count"] +
			"</p>"
	);
}
// Paginate logic
function paginate(total_items, current_page, page_size, max_pages) {
	// calculate total pages
	var total_pages = Math.ceil(total_items / page_size);

	// ensure current page isn't out of range
	if (current_page < 1) current_page = 1;
	else if (current_page > total_pages) current_page = total_pages;

	var start_page, end_page;
	if (total_pages <= max_pages) {
		// total pages less than max so show all pages
		start_page = 1;
		end_page = total_pages;
	} else {
		// total pages more than max so calculate start and end pages
		var max_pagesBeforecurrent_page = Math.floor(max_pages / 2);
		var max_pagesAftercurrent_page = Math.ceil(max_pages / 2) - 1;
		if (current_page <= max_pagesBeforecurrent_page) {
			// current page near the start
			start_page = 1;
			end_page = max_pages;
		} else if (current_page + max_pagesAftercurrent_page >= total_pages) {
			// current page near the end
			start_page = total_pages - max_pages + 1;
			end_page = total_pages;
		} else {
			// current page somewhere in the middle
			start_page = current_page - max_pagesBeforecurrent_page;
			end_page = current_page + max_pagesAftercurrent_page;
		}
	}

	// calculate start and end item indexes
	var start_index = (current_page - 1) * page_size;
	var end_index = Math.min(start_index + page_size - 1, total_items - 1);

	// create an array of pages to ng-repeat in the pager control
	var pages = Array.from(Array(end_page + 1 - start_page).keys()).map(
		(i) => start_page + i
	);

	// return object with all pager properties required by the view
	return {
		total_items: total_items,
		// current_page: current_page,
		// page_size: page_size,
		total_pages: total_pages,
		start_page: start_page,
		end_page: end_page,
		start_index: start_index,
		end_index: end_index,
		pages: pages,
	};
}
//
function pagination_event() {
	//
	var i = $(this).data("page-type");
	// When next is press
	if ($(this).hasClass("js-pagination-next") === true) {
		window.timeoff.PaginationOBJ[i]["Main"]["page"] =
			window.timeoff.PaginationOBJ[i]["Main"]["page"] + 1;
		window.timeoff.PaginationOBJ[i]["cb"]($(this));
	} else if ($(this).hasClass("js-pagination-prev") === true) {
		window.timeoff.PaginationOBJ[i]["Main"]["page"] =
			window.timeoff.PaginationOBJ[i]["Main"]["page"] - 1;
		window.timeoff.PaginationOBJ[i]["cb"]($(this));
	} else if ($(this).hasClass("js-pagination-first") === true) {
		window.timeoff.PaginationOBJ[i]["Main"]["page"] = 1;
		window.timeoff.PaginationOBJ[i]["cb"]($(this));
	} else if ($(this).hasClass("js-pagination-last") === true) {
		window.timeoff.PaginationOBJ[i]["Main"]["page"] =
			window.timeoff.PaginationOBJ[i]["pages"];
		window.timeoff.PaginationOBJ[i]["cb"]($(this));
	} else if ($(this).hasClass("js-pagination-shift") === true) {
		window.timeoff.PaginationOBJ[i]["Main"]["page"] = parseInt(
			$(this).data("page")
		);
		window.timeoff.PaginationOBJ[i]["cb"]($(this));
	}
}

let inObject = function (val, searchIn) {
	for (obj in searchIn) {
		if (searchIn[obj].diff >= 2) {
			if (
				searchIn[obj]["from_date"] <= val &&
				searchIn[obj]["to_date"] >= val
			)
				return searchIn[obj];
		} else {
			if (searchIn[obj]["from_date"] == val) return searchIn[obj];
		}
	}
	return -1;
};

//
var policyOffDays = undefined;

//
function remakeRangeRows(t1, t2, target, slug) {
	let startDate = $(t1).val(),
		endDate = $(t2).val();
	//
	$(target).hide();
	$(`${target} tbody tr`).remove();
	//
	if (startDate == "" || endDate == "") {
		return;
	}
	//
	startDate = moment(startDate);
	endDate = moment(endDate);
	//
	let diff = endDate.diff(startDate, "days");
	//
	slug = slug === undefined ? "" : "-" + slug;
	//
	var policyDayOffs =
		policyOffDays === undefined ? timeOffDays : policyOffDays;
	//
	let rows = "";
	let i = 0,
		il = diff;
	for (i; i <= il; i++) {
		//
		let sd = moment(startDate).add(i, "days");
		if (
			$.inArray(sd.format("MM-DD-YYYY"), holidayDates) === -1 &&
			$.inArray(
				sd.format("dddd").toString().toLowerCase(),
				policyDayOffs
			) === -1
		) {
			rows +=
				'<tr data-id="' +
				i +
				'" data-date="' +
				sd.format("MM-DD-YYYY") +
				'">';
			rows +=
				'    <th style="vertical-align: middle">' +
				sd.format("MMMM Do, YYYY - dddd") +
				"</th>";
			rows += '    <th style="vertical-align: middle">';
			rows += "        <div>";
			rows += '            <label class="control control--radio">';
			rows += "                Full Day";
			rows +=
				'                <input type="radio" name="' +
				i +
				"_day_type" +
				slug.replace(/-/g, "_") +
				'" checked="true" value="fullday" />';
			rows += '                <span class="control__indicator"></span>';
			rows += "            </label> <br />";
			rows += '            <label class="control control--radio">';
			rows += "                Partial Day";
			rows +=
				'                <input type="radio" name="' +
				i +
				"_day_type" +
				slug.replace(/-/g, "_") +
				'" value="partialday" />';
			rows += '                <span class="control__indicator"></span>';
			rows += "            </label>";
			rows += "        </div>";
			rows += "    </th>";
			rows += "    <th>";
			rows += '        <div class="rowd" id="row_' + i + '">';
			rows += setTimeView(
				"#row_" + i + "",
				"-el" + slug + i,
				sd.format("MMDDYYYY"),
				0
			);
			rows += "        </div>";
			rows += "    </th>";
			rows += "</tr>";
		}
		//
	}
	//
	$(`${target} tbody`).html(rows);
	$(target).show();
}

function remakeRangeRowsEdit(d1, d2, target, data) {
	let startDate = $(d1).val(),
		endDate = $(d2).val();
	//
	let d = {};
	//
	if (typeof data === "object") {
		data.days.map(function (v, i) {
			d[v.date] = v;
			if (v.partial === undefined) d[v.date].partial = "fullday";
		});
	}

	//
	$(`${target}`).hide();
	$(`${target} tbody tr`).remove();
	//
	if (startDate == "" || endDate == "") {
		return;
	}
	//
	startDate = moment(startDate);
	endDate = moment(endDate);
	let diff = endDate.diff(startDate, "days");
	//
	let rows = "";
	let i = 0,
		il = diff;
	for (i; i <= il; i++) {
		let sd = moment(startDate).add(i, "days");
		let ld = d[sd.format("MM-DD-YYYY")];
		if (
			$.inArray(sd.format("MM-DD-YYYY"), holidayDates) === -1 &&
			$.inArray(
				sd.format("dddd").toString().toLowerCase(),
				timeOffDays
			) === -1
		) {
			console.log(ld);
			rows +=
				'<tr data-id="' +
				i +
				'" data-date="' +
				sd.format("MM-DD-YYYY") +
				'">';
			rows +=
				'    <th style="vertical-align: middle">' +
				sd.format("MMMM Do, YYYY") +
				"</th>";
			rows += '    <th style="vertical-align: middle">';
			rows += "        <div>";
			rows += '            <label class="control control--radio">';
			rows += "                Full Day";
			rows +=
				'                <input type="radio" name="' +
				i +
				'_day_type_edit" value="fullday" ' +
				(ld !== undefined && ld.partial == "fullday"
					? 'checked="true"'
					: ld == undefined
					? 'checked="true"'
					: "") +
				" />";
			rows += '                <span class="control__indicator"></span>';
			rows += "            </label>";
			rows += '            <label class="control control--radio">';
			rows += "                Partial Day";
			rows +=
				'                <input type="radio" name="' +
				i +
				'_day_type_edit" value="partialday" ' +
				(ld !== undefined && ld.partial != "fullday"
					? 'checked="true"'
					: "") +
				" />";
			rows += '                <span class="control__indicator"></span>';
			rows += "            </label>";
			rows += "        </div>";
			rows += "    </th>";
			rows += "    <th>";
			rows += '        <div class="rowd" id="row_' + i + '_edit">';
			rows += setTimeView("#row_" + i + "", "-el-edit" + i, ld);
			rows += "        </div>";
			rows += "    </th>";
			rows += "</tr>";
			//
		}
	}
	//
	if (rows == "") return;
	//
	$(`${target} tbody`).html(rows);
	$(`${target}`).show();
}

//
function setTimeView(target, prefix, position) {
	//
	let row = "";
	//
	if (typeof position === "object")
		position = position.date.replace(/-/gi, "");
	//
	let slug = window.timeoff.cPolicies[0]["Settings"]["Slug"];
	if (slug == "D:H:M") {
		row += '<div class="col-sm-4">';
		row += '    <div class="form-group">';
		row += "        <label>Days </label>";
		row +=
			'        <input type="text" data-ids="' +
			position +
			'" data-page="' +
			(prefix.match(/edit/gi) !== null ? "edit" : "add") +
			'" data-type="hour" class="form-control js-number" id="js-request-days' +
			(prefix === undefined ? "" : prefix) +
			'"  />';
		row += "    </div>";
		row += "</div>";
		row += '<div class="col-sm-4">';
		row += '    <div class="form-group">';
		row += "        <label>Hours </label>";
		row +=
			'        <input type="text" data-ids="' +
			position +
			'" data-page="' +
			(prefix.match(/edit/gi) !== null ? "edit" : "add") +
			'" data-type="hour" class="form-control js-number" id="js-request-hours' +
			(prefix === undefined ? "" : prefix) +
			'" value="' +
			getTime(position, "hour", prefix) +
			'" />';
		row += "    </div>";
		row += "</div>";
		row += '<div class="col-sm-4">';
		row += '    <div class="form-group">';
		row += "        <label>Minutes </label>";
		row +=
			'        <input type="text" data-ids="' +
			position +
			'" data-page="' +
			(prefix.match(/edit/gi) !== null ? "edit" : "add") +
			'" data-type="minute" class="form-control js-number" id="js-request-minutes' +
			(prefix === undefined ? "" : prefix) +
			'" value="' +
			getTime(position, "minute", prefix) +
			'" />';
		row += "    </div>";
		row += "</div>";
	} else if (slug == "D:H") {
		row += '<div class="col-sm-6">';
		row += '    <div class="form-group">';
		row += "        <label>Days </label>";
		row +=
			'        <input type="text" data-ids="' +
			position +
			'" data-page="' +
			(prefix.match(/edit/gi) !== null ? "edit" : "add") +
			'" data-type="day" class="form-control js-number" id="js-request-days' +
			(prefix === undefined ? "" : prefix) +
			'" value="' +
			getTime(position, "day", prefix) +
			'" />';
		row += "    </div>";
		row += "</div>";
		row += '<div class="col-sm-6">';
		row += '    <div class="form-group">';
		row += "        <label>Hours </label>";
		row +=
			'        <input type="text" data-ids="' +
			position +
			'" data-page="' +
			(prefix.match(/edit/gi) !== null ? "edit" : "add") +
			'" data-type="hour" class="form-control js-number" id="js-request-hours' +
			(prefix === undefined ? "" : prefix) +
			'" value="' +
			getTime(position, "hour", prefix) +
			'" />';
		row += "    </div>";
		row += "</div>";
	} else if (slug == "H:M") {
		row += '<div class="col-sm-6">';
		row += '    <div class="form-group">';
		row += "        <label>Hours </label>";
		row +=
			'        <input type="text" data-ids="' +
			position +
			'" data-page="' +
			(prefix.match(/edit/gi) !== null ? "edit" : "add") +
			'" data-type="hour" class="form-control js-number" id="js-request-hours' +
			(prefix === undefined ? "" : prefix) +
			'" value="' +
			getTime(position, "hour", prefix) +
			'" />';
		row += "    </div>";
		row += "</div>";
		row += '<div class="col-sm-6">';
		row += '    <div class="form-group">';
		row += "        <label>Minutes </label>";
		row +=
			'        <input type="text" data-ids="' +
			position +
			'" data-page="' +
			(prefix.match(/edit/gi) !== null ? "edit" : "add") +
			'" data-type="minute" class="form-control js-number" id="js-request-minutes' +
			(prefix === undefined ? "" : prefix) +
			'" value="' +
			getTime(position, "minute", prefix) +
			'" />';
		row += "    </div>";
		row += "</div>";
	} else if (slug == "H") {
		row += '<div class="col-sm-12">';
		row += '    <div class="form-group">';
		row += "        <label>Hours </label>";
		row +=
			'        <input type="text" data-ids="' +
			position +
			'" data-page="' +
			(prefix.match(/edit/gi) !== null ? "edit" : "add") +
			'" data-type="hour" class="form-control js-number" id="js-request-hours' +
			(prefix === undefined ? "" : prefix) +
			'" value="' +
			getTime(position, "hour", prefix) +
			'" />';
		row += "    </div>";
		row += "</div>";
	} else {
		row += '<div class="col-sm-12">';
		row += '    <div class="form-group">';
		row += "        <label>Minutes </label>";
		row +=
			'        <input type="text" data-ids="' +
			position +
			'" data-page="' +
			(prefix.match(/edit/gi) !== null ? "edit" : "add") +
			'" data-type="minute" class="form-control js-number" id="js-request-minutes' +
			(prefix === undefined ? "" : prefix) +
			'" value="' +
			getTime(position, "minute", prefix) +
			'" />';
		row += "    </div>";
		row += "</div>";
	}
	//
	if (prefix !== undefined) return row;
	$(target).html(row);
}

let timeRowsOBJ = {};
let timeRowsOBJEdit = {};

$(document).on("keyup", ".js-number", function () {
	//
	if ($(this).data("page") == "add")
		timeRowsOBJ[$(this).data("ids")][$(this).data("type")] = $(this).val();
	else
		timeRowsOBJEdit[$(this).data("ids")][$(this).data("type")] =
			$(this).val();
});

//
function getTime(ind, indd, prefix) {
	//
	if (ind === undefined) return "";
	if (prefix.match(/edit/gi) !== null) {
		if (!timeRowsOBJEdit.hasOwnProperty(ind)) {
			timeRowsOBJEdit[ind] = {};
			timeRowsOBJEdit[ind]["hour"] =
				window.timeoff.cPolicies[0]["Settings"]["ShiftHours"];
			timeRowsOBJEdit[ind]["minute"] =
				window.timeoff.cPolicies[0]["Settings"]["ShiftMinutes"];
		}
		return timeRowsOBJEdit[ind][indd];
	} else {
		if (!timeRowsOBJ.hasOwnProperty(ind)) {
			timeRowsOBJ[ind] = {};
			timeRowsOBJ[ind]["hour"] =
				window.timeoff.cPolicies[0]["Settings"]["ShiftHours"];
			timeRowsOBJ[ind]["minute"] =
				window.timeoff.cPolicies[0]["Settings"]["ShiftMinutes"];
		}
		return timeRowsOBJ[ind][indd];
	}
}

function getRequestedDays(target, slug) {
	//
	let totalTime = 0,
		err = false,
		arr = [];
	//
	slug = slug === undefined ? "" : "-" + slug;
	//
	$(`${target} tbody tr`).map(function (i, v) {
		if (err) return;
		var time = getTimeInMinutes(`el${slug}${$(this).data("id")}`);
		//
		if (time.requestedMinutes < 0) {
			err = true;
			alertify.alert(
				"WARNING!",
				"Please, add request time for date <b>" +
					$(this).data("date") +
					"</b>.",
				function () {
					return;
				}
			);
		} else if (time.requestedMinutes > time.defaultTimeslotMinutes) {
			err = true;
			alertify.alert(
				"WARNING!",
				"Requested time off can not be greater than shift time.",
				function () {
					return;
				}
			);
		}
		//
		arr.push({
			date: $(this).data("date"),
			partial: $(this)
				.find(
					'input[name="' +
						i +
						"_day_type_" +
						slug.replace(/-/, "") +
						'"]:checked'
				)
				.val(),
			time: time.requestedMinutes,
		});
		//
		totalTime = parseInt(totalTime) + parseInt(time.requestedMinutes);
	});

	return {
		totalTime: totalTime,
		days: arr,
		error: err,
	};
}

function getTimeInMinutes(typo) {
	typo = typo === undefined ? "" : "-" + typo;
	var days = 0,
		hours = 0,
		minutes = 0,
		format = "",
		inText = "";
	//
	if ($("#js-request-days" + typo + "").length !== 0) {
		format += "D,";
		days = isNaN(
			parseInt(
				$("#js-request-days" + typo + "")
					.val()
					.trim()
			)
		)
			? 0
			: parseInt(
					$("#js-request-days" + typo + "")
						.val()
						.trim()
			  );
	}
	if ($("#js-request-hours" + typo + "").length !== 0) {
		format += "H,";
		hours = isNaN(
			parseInt(
				$("#js-request-hours" + typo + "")
					.val()
					.trim()
			)
		)
			? 0
			: parseInt(
					$("#js-request-hours" + typo + "")
						.val()
						.trim()
			  );
	}
	if ($("#js-request-minutes" + typo + "").length !== 0) {
		format += "M,";
		minutes = isNaN(
			parseInt(
				$("#js-request-minutes" + typo + "")
					.val()
					.trim()
			)
		)
			? 0
			: parseInt(
					$("#js-request-minutes" + typo + "")
						.val()
						.trim()
			  );
	}
	//
	if (format == "D:H:M")
		inText =
			days +
			" day" +
			(days > 1 ? "s" : "") +
			", " +
			hours +
			" hour" +
			(hours > 1 ? "s" : "") +
			", and " +
			minutes +
			" minute" +
			(minutes > 1 ? "s" : "");
	else if (format == "D") inText = days + " day" + (days > 1 ? "s" : "");
	else if (format == "H") inText = hours + " day" + (hours > 1 ? "s" : "");
	else if (format == "M")
		inText = minutes + " minute" + (minutes > 1 ? "s" : "");
	else
		inText =
			hours +
			" hour" +
			(hours > 1 ? "s" : "") +
			", and " +
			minutes +
			" minute" +
			(minutes > 1 ? "s" : "");
	return {
		days: days,
		hours: hours,
		minutes: minutes,
		defaultTimeslot: window.timeoff.cPolicies[0]["Settings"]["Shift"],
		defaultTimeslotMinutes:
			window.timeoff.cPolicies[0]["Settings"]["Shift"] * 60,
		format: format.replace(/,$/, ""),
		formated: inText,
		requestedMinutes:
			days * window.timeoff.cPolicies[0]["Settings"]["Shift"] * 60 +
			hours * 60 +
			minutes,
	};
}

//
function getPolicy(policyId, policyList) {
	//
	let i = 0,
		il = policyList.length;
	//
	for (i; i < il; i++) {
		//
		if (policyList[i]["PolicyId"] == policyId) return policyList[i];
	}
	//
	return {};
}

//
function getUserById(userId, users, ind) {
	//
	let r = [];
	ind = ind === undefined ? user_id : ind;
	//
	$.each(users, (index, user) => {
		if (user[ind] == userId) r = user;
	});
	//
	return r;
}

function timeConvert(n) {
	let num = n;
	let hours = num / 60;
	let rhours = Math.floor(hours);
	let minutes = (hours - rhours) * 60;
	let rminutes = Math.round(minutes);
	//
	return {
		hours: rhours,
		minutes: rminutes,
	};
}

//
function loadTitles() {
	$('[title][placement="left"]').tooltip({ placement: "left" });
	$('[title][placement="right"]').tooltip({ placement: "right" });
	$('[title][placement="top"]').tooltip({ placement: "top" });
	$("[title]").tooltip({ placement: "bottom" });
}

//
function get_array_from_minutes(fromMinutes, employeeShiftTime, format) {
	let returnArray = {};
	returnArray["timeFrame"] = employeeShiftTime;
	returnArray["originalMinutes"] = fromMinutes;
	returnArray["D:H:M"] = {};
	//
	returnArray["D:H:M"]["days"] = parseInt(
		fromMinutes / (employeeShiftTime * 60)
	);
	returnArray["D:H:M"]["hours"] = parseInt(
		(fromMinutes % (employeeShiftTime * 60)) / 60
	);
	returnArray["D:H:M"]["minutes"] = parseInt(
		(fromMinutes % (employeeShiftTime * 60)) % 60
	);

	returnArray["H:M"] = {};
	returnArray["H:M"]["hours"] = parseInt(fromMinutes / 60);
	returnArray["H:M"]["minutes"] = parseInt(fromMinutes % 60);

	returnArray["D"] = {};
	returnArray["D"]["days"] = (fromMinutes / (employeeShiftTime * 60)).toFixed(
		2
	);

	returnArray["M"] = {};
	returnArray["M"]["minutes"] = fromMinutes;

	returnArray["H"] = {};
	returnArray["H"]["hours"] = (fromMinutes / 60).toFixed(2);

	returnArray["active"] = returnArray[format];
	returnArray["text"] = "";

	if (returnArray[format]["days"] !== undefined)
		returnArray["text"] +=
			returnArray[format]["days"] +
			" day" +
			(returnArray[format]["days"] > 1 || returnArray[format]["days"] == 0
				? "s"
				: "") +
			" & ";
	if (returnArray[format]["hours"] !== undefined)
		returnArray["text"] +=
			returnArray[format]["hours"] +
			" hour" +
			(returnArray[format]["hours"] > 1 ||
			returnArray[format]["hours"] == 0
				? "s"
				: "") +
			" & ";
	if (returnArray[format]["minutes"] !== undefined)
		returnArray["text"] +=
			returnArray[format]["minutes"] +
			" minute" +
			(returnArray[format]["minutes"] > 1 ||
			returnArray[format]["minutes"] == 0
				? "s"
				: "") +
			" & ";
	returnArray["text"] = returnArray["text"].replace(/&$/, "");

	return returnArray;
}

loadTitles();
loadPopovers();
whoIsOnOffToday();

//
async function whoIsOnOffToday() {
	//
	const employeesOnOffToday = await fetchTodaysOffEmployees();
	//
	if (employeesOnOffToday.Data.length === 0) return;
	//
	let rows = "";
	let m_rows = "";
	//
	employeesOnOffToday.Data.map((emp) => {
		//
		rows += `
        <li>
            <a 
                href="${baseURL}employee_profile/${emp.userId}" 
                target="_blank"
                class="jsOffEmployees"
                data-content="<span>${emp.first_name} ${
			emp.last_name
		}<br /><span>${remakeEmployeeName(emp, false)}</span></span>"
            >
                <img src="${getImageURL(emp.image)}" class="csRadius50" />
            </a>
        </li>
        `;
		// For mobile
		m_rows += `
            <li>
                <a  href="${baseURL}employee_profile/${
			emp.userId
		}" style="width: auto; height: auto;" >
                    <div class="employee-info">
                        <figure>
                            <img src="${getImageURL(
								emp.image
							)}" class="img-circle emp-image" />
                        </figure>
                        <div class="text">
                            <h4>${emp.first_name} ${emp.last_name} </h4>
                            <p>${remakeEmployeeName(emp, false)}</p>
                        </div>
                    </div>
                </a>
            </li>
        `;
	});
	//
	$(".csTopNavOutMobile").html(`
        <ul style="margin-top: 5px;">
        ${m_rows}
        </ul>
    `);
	//
	$(".csTopNavOut").html(` 
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Who is off today?</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <ul style="margin-top: 5px;">
                    ${rows}
                    </ul>
                </div>
            </div>
        </div>`);
	$(".jsOffOutSideMenu").html(`<h4>Who is off today?</h4><ul>${rows}</ul>`);
	//
	$(".jsOffEmployees").popover({
		placement: "top",
		trigger: "hover",
		html: true,
	});
}

//
function fetchTodaysOffEmployees() {
	return new Promise((res) => {
		$.post(
			handlerURL,
			{
				action: "get_today_off_employees",
				companyId: companyId,
				employerId: employerId,
				employeeId: employeeId,
			},
			(resp) => {
				res(resp);
			}
		);
	});
}

function loadPopovers() {
	$(".jsPopover").popover({
		trigger: "hover",
		html: true,
		placement: "top",
	});
}

//
$(".jsFilterBtn").click(function (e) {
	e.preventDefault();
	$(`.${$(this).data("target")}`).toggle();
});

// strips tags
function strip_tags(str) {
	return $("<div/>").html(str).text();
}

//
function getParams(index) {
	//
	if (window.location.search == "") {
		return index !== undefined ? "all" : {};
	}
	//
	var t = {};
	//
	window.location.search
		.replace("?", "")
		.split("&")
		.map(function (b) {
			var l = b.split("=");
			t[l[0]] = l[1];
		});
	//
	return index !== undefined
		? t[index] === undefined
			? "all"
			: t[index]
		: t;
}

//
function getText(o) {
	//
	var r = "";
	//
	if (o.minutes !== undefined) {
		//
		if (o.hours !== undefined) {
			//
			if (o.minutes / 60 >= 1) {
				o.hours += (o.minutes / 60).split(".")[0];
				o.minutes += Math.floor((o.minutes / 60).split(".")[1]);
			}
			r = o.hours + " hours & " + o.minutes + " minutes";
		} else {
			r = o.minutes + " minutes";
		}
	} else if (o.hours !== undefined) {
		r = o.hours + " hours";
	}
	//
	return r.replace(/&$/, "").trim();
}

//
function setUpcomingTimeOffs(employeeId) {
	//
	$.post(handlerURL, {
		action: "get_employee_upcoming_timeoffs",
		companyId: companyId,
		employerId: employerId,
		employeeId: employeeId,
	}).done(function (resp) {
		//
		if (resp.Data.length === 0) {
			$(".jsTimeOffUpComing").html(
				'<p class="alert alert-info text-center">Currently, there are no upcoming time-offs.</p>'
			);
			return;
		}
		//
		var rows = "";
		//
		rows += "<ul>";
		//
		resp.Data.map(function (to) {
			//
			var startDate = moment(to.request_from_date, "YYYY-MM-DD").format(
					timeoffDateFormat
				),
				endDate = moment(to.request_to_date, "YYYY-MM-DD").format(
					timeoffDateFormat
				);
			//
			var diff =
				moment(to.request_to_date, "YYYY-MM-DD").diff(
					moment(to.request_from_date, "YYYY-MM-DD"),
					"days"
				) + 1;
			//
			rows += '    <li style="display: block;">';
			rows += "        <p>";
			rows +=
				"            <strong>" +
				to.title +
				' <span class="pull-right text-' +
				(to.status == "approved" ? "success" : "warning") +
				'">';
			rows +=
				"                <strong>" + ucwords(to.status) + "</strong>";
			rows += "            </span></strong> <br/>";
			rows +=
				"            <span>" +
				startDate +
				(startDate == endDate ? "" : " - " + endDate) +
				"</span><br/>";
			rows +=
				"            <span>" +
				diff +
				" Day" +
				(diff <= 1 ? "" : "s") +
				"</span>";
			rows += "            ";
			rows += "        </p>";
			rows += "    </li>";
		});
		//
		rows += "</ul>";
		//
		$(".jsTimeOffUpComing").html(rows);
	});
}



function formateEmployeeJobType(jobtype)
{
    //
    if (jobtype == "fulltime") {
        return "Full-Time";
    } else if (jobtype == "parttime") {
        return "Part-Time";
    } else if (jobtype == "contract") {
        return "Contractual";
    }else{
        return jobtype;
    }
}