var baseURI = "<?=base_url('performance/handler')?>",
  pOBJ = {
    fetchTemplates: {
      page: 1,
      totalPages: 0,
      limit: 0,
      records: 0,
      totalRecords: 0,
      cb: fetchTemplates,
    },
  },
  filterOBJ = {
    Action: "fetch_templates",
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
  fetchTemplates();
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
  fetchTemplates();
});

// Fetch templates
function fetchTemplates() {
  //
  if (XHR !== null) XHR.abort();
  //
  loader(true);
  filterOBJ.Page = pOBJ["fetchTemplates"]["page"];
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
      pOBJ["fetchTemplates"]["totalRecords"] = resp.Data.Count;
      pOBJ["fetchTemplates"]["limit"] = resp.Limit;
      pOBJ["fetchTemplates"]["totalPages"] = Math.ceil(
        resp.Data.Count / resp.Limit
      );
    }

    //
    setTableData(resp.Data);
    //
    load_pagination(
      pOBJ["fetchTemplates"]["limit"],
      5,
      $(".cs-pagination-area"),
      "fetchTemplates"
    );
  });
}

//
function setTableData(data) {
  let rows = "";
  //
  data.Records.map((record) => {
    //
    let editBTN = `<a href="<?=base_url("performance/template/edit");?>/${record.sid}" class="btn btn-success"  title="Edit Template">Edit</a>`;
    let viewBTN = `<a href="<?=base_url("performance/template/view");?>/${record.sid}" class="btn btn-default"  title="View Template Questions">View Questions</a>`;
    rows += "<tr>";
    rows += "   <td>" + record.sid + "</td>";
    rows += "   <td>" + record.title + "</td>";
    rows += `   <td class="${
      record.status == 1 ? "text-success" : "text-danger"
    }"><strong>
    ${record.status == 1 ? "Active" : "InActve"} 
    </strong></td>`;
    rows += `<td>${makeEmployeeName(record)}</td>`;
    rows += `<td>${moment(record.created_at, "YYYY-MM-DD HH:mm:ss").format(
      "MM/DD/YYYY HH:mm:ss"
    )}</td>`;
    rows += `<td>${editBTN} ${viewBTN}</td>`;
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
fetchTemplates();
