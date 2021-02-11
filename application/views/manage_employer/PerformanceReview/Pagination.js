// Pagination Script
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
  var obj = pOBJ[page_type];
  // parsing to int
  limit = parseInt(limit);
  obj["page"] = parseInt(obj["page"]);
  // get paginate array
  var page_array = paginate(obj["totalRecords"], obj["page"], limit, list_size);
  // append the target ul
  // to top and bottom of table
  target_ref.html('<ul class="pagination cs-pagination js-pagination"></ul>');
  // set rows append table
  var target = target_ref.find(".js-pagination");
  // get total items number
  var total_records = page_array.total_pages;
  // load pagination only there
  // are more than one page
  if (obj["totalRecords"] >= limit) {
    // generate li for
    // pagination
    var rows = "";
    // move to one step back
    rows +=
      '<li><a href="javascript:void(0)" data-page-type="' +
      page_type +
      '" class="' +
      (obj["page"] == 1 ? "" : "js-pagination-first") +
      '">First</a></li>';
    rows +=
      '<li><a href="javascript:void(0)" data-page-type="' +
      page_type +
      '" class="' +
      (obj["page"] == 1 ? "" : "js-pagination-prev") +
      '">&laquo;</a></li>';
    // generate 5 li
    $.each(page_array.pages, function (index, val) {
      rows +=
        "<li " +
        (val == obj["page"] ? 'class="active"' : "") +
        '><a href="javascript:void(0)" data-page-type="' +
        page_type +
        '" data-page="' +
        val +
        '" class="' +
        (obj["page"] != val ? "js-pagination-shift" : "") +
        '">' +
        val +
        "</a></li>";
    });
    // move to one step forward
    rows +=
      '<li><a href="javascript:void(0)" data-page-type="' +
      page_type +
      '" class="' +
      (obj["page"] == page_array.total_pages ? "" : "js-pagination-next") +
      '">&raquo;</a></li>';
    rows +=
      '<li><a href="javascript:void(0)" data-page-type="' +
      page_type +
      '" class="' +
      (obj["page"] == page_array.total_pages ? "" : "js-pagination-last") +
      '">Last</a></li>';
    // append to ul
    target.html(rows);
  }
  // remove showing
  target.find(".js-show-record").remove();
  // append showing of records
  target.before(
    '<span class="pull-left js-show-record" style="margin-top: 27px; padding-right: 10px;">Showing ' +
      (page_array.start_index + 1) +
      " - " +
      (page_array.end_index != -1 ? page_array.end_index + 1 : 1) +
      " of " +
      obj["totalRecords"] +
      "</span>"
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
    pOBJ[i]["page"] = pOBJ[i]["page"] + 1;
    pOBJ[i]["cb"]($(this));
  } else if ($(this).hasClass("js-pagination-prev") === true) {
    pOBJ[i]["page"] = pOBJ[i]["page"] - 1;
    pOBJ[i]["cb"]($(this));
  } else if ($(this).hasClass("js-pagination-first") === true) {
    pOBJ[i]["page"] = 1;
    pOBJ[i]["cb"]($(this));
  } else if ($(this).hasClass("js-pagination-last") === true) {
    pOBJ[i]["page"] = pOBJ[i]["totalPages"];
    pOBJ[i]["cb"]($(this));
  } else if ($(this).hasClass("js-pagination-shift") === true) {
    pOBJ[i]["page"] = parseInt($(this).data("page"));
    pOBJ[i]["cb"]($(this));
  }
}
