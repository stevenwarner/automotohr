var baseURI = "<?= base_url('performance/handler') ?>",
  filterOBJ = {
    Action: "fetch_reviews",
    Page: 1,
    Query: "",
    Status: "-1",
    ApplyFilter: 0,
  },
  XHR = null;

//
$(document).on("click", ".js-start-review", function (e) {
  e.preventDefault();
  loader("show");
  //
  $.post(
    baseURI,
    {
      Action: "start_employee_review",
      Id: $(this).closest("tr").data("id"),
    },
    (resp) => {
      if (resp.Status === false) {
        alertify.alert("WARNING!", resp.Response, () => {});
        return;
      }
      alertify.alert("WARNING!", resp.Response, () => {
        loader(false);
        $(this).closest("tr").find("td:nth-child(4)").text("Started");
        $(this)
          .removeClass("js-start-review")
          .removeClass("btn-success")
          .addClass("js-end-review")
          .addClass("btn-danger")
          .find("i")
          .removeClass("fa-shield")
          .addClass("fa-ban")
          ;
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
      Action: "end_employee_review",
      Id: $(this).closest("tr").data("id"),
    },
    (resp) => {
      if (resp.Status === false) {
        alertify.alert("WARNING!", resp.Response, () => {});
        return;
      }
      alertify.alert("WARNING!", resp.Response, () => {
        loader(false);
        $(this).closest("tr").find("td:nth-child(4)").text("Pending");
        $(this)
          .removeClass("js-end-review")
          .removeClass("btn-danger")
          .addClass("js-start-review")
          .addClass("btn-success")
          .find("i")
          .removeClass("fa-ban")
          .addClass("fa-shield");
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

loader(false);


$('.js-popover').popover({
  trigger: 'hover'
});
