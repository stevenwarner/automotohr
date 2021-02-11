var baseURI = "<?=base_url('performance/handler')?>",
  pOBJ = {
    fetchReviews: {
      page: 1,
      totalPages: 0,
      limit: 0,
      records: 0,
      totalRecords: 0,
      cb: fetchReviews,
    },
  },
  filterOBJ = {
    Action: "fetch_reviews",
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
  fetchReviews();
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
  fetchReviews();
});

// Fetch templates
function fetchReviews() {
  //
  if (XHR !== null) XHR.abort();
  //
  loader(true);
  filterOBJ.Page = pOBJ["fetchReviews"]["page"];
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
      pOBJ["fetchReviews"]["totalRecords"] = resp.Data.Count;
      pOBJ["fetchReviews"]["limit"] = resp.Limit;
      pOBJ["fetchReviews"]["totalPages"] = Math.ceil(
        resp.Data.Count / resp.Limit
      );
    }

    //
    setTableData(resp.Data);
    //
    load_pagination(
      pOBJ["fetchReviews"]["limit"],
      5,
      $(".cs-pagination-area"),
      "fetchReviews"
    );
  });
}

//
function setTableData(data) {
  let rows = "";
  //
  data.Records.map((record) => {
    //
    let editBTN = `<a href="<?=base_url("performance/review/edit");?>/${record.sid}" class="btn btn-success"  title="Edit Review"><i class="fa fa-pencil"></i></a>`;
    let startBtn = `<a href="javascript:void(0)" class="btn btn-success js-start-review"  title="Start Review"><i class="fa fa-shield"></i></a>`;
    let endBTN = `<a href="javascript:void(0)" class="btn btn-danger js-end-review"  title="End Review"><i class="fa fa-ban"></i></a>`;
    let viewBtn = `<a href="<?=base_url("performance/review/view");?>/${record.sid}" class="btn btn-success"  title="View Review"><i class="fa fa-eye"></i></a>`;
    let manageBtn = `<a href="<?=base_url("performance/edit");?>/${record.sid}" class="btn btn-success"  title="Manage Reviewee"><i class="fa fa-th-list"></i></a>`;
    rows += `<tr data-id="${record.sid}" style="vertical-align: middle">`;
    rows += "   <td>" + record.title + "</td>";
    rows +=
      "   <td>" +
      moment(record.start_date, "YYYY-MM-DD HH:mm:ss").format("MM/DD/YYYY") +
      "</td>";
    rows +=
      "   <td>" +
      moment(record.end_date, "YYYY-MM-DD HH:mm:ss").format("MM/DD/YYYY") +
      "</td>";
    rows += `   <td><strong>
    ${record.status.ucFirst()} 
    </strong></td>`;
    rows += `<td>${editBTN}  ${
      record.status === "started" ? endBTN : startBtn
    } ${viewBtn} ${manageBtn}</td>`;
    rows += "</tr>";
  });
  //
  $("tbody").html(rows);
  //
  loader(false);
}

//
$(document).on("click", ".js-start-review", function (e) {
  e.preventDefault();
  loader("show");
  //
  $.post(
    baseURI,
    {
      Action: "start_review",
      Id: $(this).closest("tr").data("id"),
    },
    (resp) => {
      if (resp.Status === false) {
        alertify.alert("WARNING!", resp.Response, () => {});
        return;
      }
      alertify.alert("WARNING!", resp.Response, () => {
        loader(false);
        $(this).closest("tr").find("td:nth-child(4) strong").text("Started");
        $(this)
          .removeClass("js-start-review")
          .removeClass("btn-success")
          .addClass("js-end-review")
          .addClass("btn-danger")
          .find('i')
          .removeClass('fa-shield')
          .addClass('fa-ban');
      });
    }
  );
});

//
$(document).on("click", ".js-end-review", function (e) {
  e.preventDefault();
  loader("show");
  //
  $.post(
    baseURI,
    {
      Action: "end_review",
      Id: $(this).closest("tr").data("id"),
    },
    (resp) => {
      if (resp.Status === false) {
        alertify.alert("WARNING!", resp.Response, () => {});
        return;
      }
      alertify.alert("WARNING!", resp.Response, () => {
        loader(false);
        $(this).closest("tr").find("td:nth-child(4) strong").text("Ended");
        $(this)
          .removeClass("js-end-review")
          .removeClass("btn-danger")
          .addClass("js-start-review")
          .addClass("btn-success")
          .find('i')
          .removeClass('fa-ban')
          .addClass('fa-shield');
      });
    }
  );
});

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
fetchReviews();
