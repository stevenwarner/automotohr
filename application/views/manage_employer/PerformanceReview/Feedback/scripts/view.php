var baseURI = "<?=base_url('performance/handler')?>",
  pOBJ = {
    fetchManagerReviews: {
      page: 1,
      totalPages: 0,
      limit: 0,
      records: 0,
      totalRecords: 0,
      cb: fetchManagerReviews,
    },
  },
  filterOBJ = {
    Action: "fetch_manager_reviews",
    Page: 1,
    Query: "",
    Status: "-1",
    Reviewee: "-1",
    ApplyFilter: 0,
  },
  XHR = null;

  $('#js-reviewee').select2({
    closeOnSelect: false
  });

// Trigger when apply button is clicked
$(".js-apply-filter-btn").click((e) => {
  //
  e.preventDefault();
  //
  filterOBJ.Page = 1;
  filterOBJ.Query = $("#js-title").val().trim();
  filterOBJ.Status = $("#js-status").val();
  filterOBJ.Reviewee = $("#js-reviewee").val();
  filterOBJ.ApplyFilter = 1;
  //
  fetchManagerReviews();
});

// Triggered when reset button is clicked
$(".js-reset-filter-btn").click((e) => {
  //
  e.preventDefault();
  //
  filterOBJ.Page = 1;
  filterOBJ.Query = "";
  filterOBJ.Status = "-1";
  filterOBJ.Reviewee = "-1";
  filterOBJ.ApplyFilter = 0;
  //
  fetchManagerReviews();
});

// Fetch templates
function fetchManagerReviews() {
  //
  if (XHR !== null) XHR.abort();
  //
  loader(true);
  filterOBJ.Page = pOBJ["fetchManagerReviews"]["page"];
  //
  XHR = $.post(baseURI, filterOBJ, (resp) => {
    //
    if (resp.Status === false || resp.Data.length === 0) {
      $("tbody").html(
        '<tr><td colspan="' +
          $("thead th").length +
          '"><p class="alert alert-info text-center">No records found.</p></td></tr>'
      );
      //
      $(".cs-pagination-area").html("");
      loader(false);
      return;
    }

    //
    if (filterOBJ.Page === 1) {
      pOBJ["fetchManagerReviews"]["totalRecords"] = resp.Data.Count;
      pOBJ["fetchManagerReviews"]["limit"] = resp.Limit;
      pOBJ["fetchManagerReviews"]["totalPages"] = Math.ceil(
        resp.Data.Count / resp.Limit
      );
    }

    //
    setTableData(resp.Data);
    //
    // load_pagination(
    //   pOBJ["fetchManagerReviews"]["limit"],
    //   5,
    //   $(".cs-pagination-area"),
    //   "fetchManagerReviews"
    // );
  });
}

//
function setTableData(data) {
  let rows = "";
  //
  data.map((record) => {
    let cmt = (record.completed * 100 / record.total);
    //
    let viewBtn = `<a href="<?=base_url("performance/feedback/single");?>/${record.sid}" class="btn btn-success btn-xs"  title="Manage Feedback"><i class="fa fa-pencil"></i></a>`;
    rows += `<tr  data-id="${record.sid}">`;
    rows += "   <td>" + record.title + "</td>";
    rows += `   <td>
    <div class="progress cs-progress-bar">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary " role="progressbar"
            style="width: ${cmt}%;" aria-valuenow="${cmt}" aria-valuemin="0" aria-valuemax="${record.total}">Feedback Completed</div>
      </div>
      <p><strong>${record.completed}</strong> out of <strong>${record.total}</strong></p>
    </td>`;
    rows += `   <td>${viewBtn}</td>`;
    rows += "</tr>";
  });
  //
  $("tbody").html(rows);
  //
  $('.jsHasFeedback').popover({
    placement: "left",
    trigger: "hover",
    html: true
  });
  //
  loader(false);
}

//
function makeEmployeeName(obj) {
  let name = `${obj.first_name} ${obj.last_name}`;
  if (obj.job_title != "") name += ` (${obj.job_title})`;
  name += ` [${obj.access_level}${obj.access_level_plus == 1 ? " Plus" : ""}]`;
  //
  return name;
}

//
function loader(doShow) {
  if (doShow) {
    $(".cs-loader").show();
  } else {
    $(".cs-loader").hide();
  }
}

//
fetchManagerReviews();
