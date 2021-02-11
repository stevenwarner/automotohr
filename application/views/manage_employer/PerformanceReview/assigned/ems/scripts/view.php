var baseURI = "<?=base_url('performance/handler')?>",
  pOBJ = {
    fetchAssignedReviews: {
      page: 1,
      totalPages: 0,
      limit: 0,
      records: 0,
      totalRecords: 0,
      cb: fetchAssignedReviews,
    },
  },
  filterOBJ = {
    Action: "fetch_assigned_reviews",
    Page: 1,
    Query: "",
    Status: "-1",
    ApplyFilter: 0,
  },
  XHR = null;

// Trigger when apply button is clicked
$(".js-apply-filter-btn").click((e) => {
  //
  e.preventDefault();
  //
  filterOBJ.Page = 1;
  filterOBJ.Query = $("#js-title").val().trim();
  filterOBJ.Status = $("#js-status").val();
  filterOBJ.ApplyFilter = 1;
  //
  fetchAssignedReviews();
});

// Triggered when reset button is clicked
$(".js-reset-filter-btn").click((e) => {
  //
  e.preventDefault();
  //
  filterOBJ.Page = 1;
  filterOBJ.Query = "";
  filterOBJ.Status = "-1";
  filterOBJ.ApplyFilter = 0;
  //
  fetchAssignedReviews();
});

// Fetch templates
function fetchAssignedReviews() {
  //
  if (XHR !== null) XHR.abort();
  //
  loader(true);
  filterOBJ.Page = pOBJ["fetchAssignedReviews"]["page"];
  //
  XHR = $.post(baseURI, filterOBJ, (resp) => {
    //
    if (resp.Status === false || resp.Data.Records.length === 0) {
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
      pOBJ["fetchAssignedReviews"]["totalRecords"] = resp.Data.Count;
      pOBJ["fetchAssignedReviews"]["limit"] = resp.Limit;
      pOBJ["fetchAssignedReviews"]["totalPages"] = Math.ceil(
        resp.Data.Count / resp.Limit
      );
    }

    //
    setTableData(resp.Data);
    //
    load_pagination(
      pOBJ["fetchAssignedReviews"]["limit"],
      5,
      $(".cs-pagination-area"),
      "fetchAssignedReviews"
    );
  });
}

//
function setTableData(data) {
  let rows = "";
  //
  data.Records.map((record) => {
    //
    let viewBtn = `<a href="<?=base_url("performance/assigned/view");?>/${record.sid}" class="btn btn-info"  title="View Review">View</a>`;
    rows += `<tr data-id="${record.sid}">`;
    rows += "   <td>" + record.sid + "</td>";
    rows += `<td>${makeEmployeeName({
      first_name: record.efirst_name,
      last_name: record.elast_name,
      access_level: record.eaccess_level,
      access_level_plus: record.eaccess_level_plus,
      job_title: record.ejob_title,
      is_executive_admin: record.eis_executive_admin,
      pay_plan_flag: record.epay_plan_flag,
    })}</td>`;
    rows += `   <td><strong>
    ${record.is_completed == 1 ? "Completed" : "Pending"} 
    </strong></td>`;
    rows += `<td>${viewBtn}</td>`;
    rows += "</tr>";
  });
  //
  $("tbody").html(rows);
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
fetchAssignedReviews();
