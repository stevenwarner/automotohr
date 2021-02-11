const ratingText = `<?php echo json_encode($RatingText);?>`;

var baseURI = "<?= base_url('performance/handler') ?>",
  XHR = null,
  employees = `<?= json_encode($employees);?>`,
  template = {
    main: {},
    questions: [],
  };
const pages = {
  step1: loadStep1,
  step2: loadStep2,
  step4: loadStep4,
  step5: loadStep5,
  step3: () => {},
};
let eQuestion = {},
  eQuestionPlace = 0;
//
CKEDITOR.replace("js-template-description");
//
$("#js-template-status").select2();
// Step 0
$(".js-list-group-item").click(function (e) {
  $(".js-list-group-item").removeClass("active");
  $(this).addClass("active");
  // Update data
  $.post(
    `${baseURI}`,
    {
      Action: "fetch_single_template",
      Id: $(this).data("id"),
    },
    (resp) => {
      if (resp.Status === false) {
        alertify.alert("WARNING!", resp.Response);
        return;
      }
      //
      template.main = resp.Data.main;
      template.questions = resp.Data.questions;
      //
      loadStep(1);
    }
  );
});
// Back to 0
$(".js-back-btn-step1").click((e) => {
  e.preventDefault();
  loadStep(0);
});
// Step 1
$(".js-add-new-review").click((e) => {
  e.preventDefault();
  template.main = {};
  template.questions = [];
  loadStep(1);
});

// Add employees page
$(".js-add-employee").click(() => {});

// Employee add
function loadStep5() {}

//
$(".js-choose-reviewee").change(function (e) {
  $("#js-total-revieews").text(0);
  switch ($(this).val().toLowerCase()) {
    case "1":
      $("#js-total-revieews").text(employees.All.length);
      break;
    case "2":
      $("#js-total-revieews").text(employees.SB.length);
      break;
    case "3":
      loadReviewSelectBox(employees.All, "all");
      break;
    case "4":
      loadReviewSelectBox(employees.Departments, "custom");
      break;
  }
});

//
function loadReviewSelectBox(a, t) {
  $("#js-review-box").html("");
  //
  let employeesOptions = "";
  //
  let rows = "";
  //
  if (t == "all") {
    //
    employeesOptions = `<option value="0" disabled>[PLEASE SELECT]</value>`;
    a.map((employee) => {
      employeesOptions += `<option value="${
        employee["sid"]
      }">${makeEmployeeName(employee)}</value>`;
    });
    //
    rows = `
    <div class="form-group">
        <label>Select Employee(s) <span class="cs-required">*</span></label>
        <div>
            <select id="js-review-employees" multiple="true">
                    ${employeesOptions}
            </select>
        </div>
    </div>`;
    //
    $("#js-review-box").html(rows);
    $("#js-review-employees").select2({
      closeOnSelect: false,
    });
  }

  //
  if (t == "custom") {
    //
    employeesOptions = `<option value="0" disabled>[PLEASE SELECT]</value>`;
    a.map((employee) => {
      employeesOptions += `<option value="${
        employee["sid"]
      }">${makeEmployeeName(employee)}</value>`;
    });
    //
    rows = `
    <div class="form-group">
        <label>Select Employee(s) <span class="cs-required">*</span></label>
        <div>
            <select id="js-review-employees" multiple="true">
                    ${employeesOptions}
            </select>
        </div>
    </div>`;
    //
    $("#js-review-box").html(rows);
    $("#js-review-employees").select2({
      closeOnSelect: false,
    });
  }
}

//
$(document).on("change", "#js-review-employees", function () {
  $("#js-total-revieews").text(
    $(this).val() === null ? 0 : $(this).val().length
  );
});

//
$("#js-template-add-form").submit((e) => {
  e.preventDefault();
  let localOBJ = {};
  localOBJ.title = $("#js-template-title").val().trim();
  localOBJ.status = $("#js-template-status").val();
  localOBJ.description = CKEDITOR.instances[
    "js-template-description"
  ].getData();
  //
  if (localOBJ.title == "") {
    alertify.alert("WARNING!", "Template name is required.");
  } else {
    template.main = localOBJ;
    //
    loadStep(2);
  }
});

// Add Question Process Starts
//
$(".js-add-question").click((e) => {
  e.preventDefault();
  //
  $("#js-add-question-number").text(template.questions.length + 1);
  //
  if (CKEDITOR.instances["js-add-question-description"] === undefined)
    CKEDITOR.replace("js-add-question-description");
  //
  $(".js-add-use-label-box").hide(0);
  $("#js-add-use-label").prop("checked", false);
  $("#js-add-include-na").prop("checked", false);
  //
  $("#js-add-question-text").val("");
  CKEDITOR.instances["js-add-question-description"].setData("");
  $("#js-add-question-type").select2();
  $("#js-add-question-type").select2("val", "text");
  //
  $("#js-add-rating-scale").select2();
  $("#js-add-rating-scale").select2("val", "0");
  //
  $(".js-add-rating-box").hide(0);
  $(".js-add-rating-list-box").hide(0).html("");
  //
  loadStep(3);
});

//
$("#js-add-question-type").change((e) => {
  $("#js-add-use-label").prop("checked", false);
  $(".js-add-rating-list-box").show(0).html("");
  $(".js-add-rating-box").hide(0);
  if (e.target.value === "text") {
    $(".js-add-use-label-box").hide(0);
    $(".js-add-rating-list-box").hide(0).html("");
  } else {
    $(".js-add-use-label-box").show(0);
    $(".js-add-rating-box").show(0);
  }
});

//
$("#js-add-use-label").click(function (e) {
  if ($(this).prop("checked") === true) {
    $(".js-add-rating-list-box").show(0);
    $(".js-add-rating-list-box").html(
      getRatingRows($("#js-add-rating-scale").val())
    );
  } else {
    $(".js-add-rating-list-box").hide(0).html("");
  }
});

//
$("#js-add-rating-scale").change(function () {
  if ($("#js-add-use-label").prop("checked") === true) {
    $(".js-add-rating-list-box").show(0);
    $(".js-add-rating-list-box").html(getRatingRows($(this).val()));
  }
});

//
$("#js-add-question-submit").submit((e) => {
  e.preventDefault();
  //
  let q = {};
  //
  q.question = $("#js-add-question-text").val().trim();
  q.description = CKEDITOR.instances["js-add-question-description"].getData();
  q.type = $("#js-add-question-type").val();
  q.ratingScale = $("#js-add-rating-scale").val();
  q.useLabels = $("#js-add-use-label").prop("checked");
  q.includeNA = $("#js-add-include-na").prop("checked");
  q.sortOrder = 1;
  q.ratingLabels = [];
  if (q.type !== "text" && q.useLabels === true) {
    q.ratingLabels = getRatingLabels();
  }
  //
  if (q.question == "") {
    alertify.alert("WARNING!", "Question is required.", () => {});
  } else if (q.type === "0") {
    alertify.alert("WARNING!", "Question type is required.", () => {});
  } else {
    template.questions.push(q);
    alertify.alert("SUCCESS!", "Question added successfully.", () => {
      loadStep(2);
    });
  }
});

//
function getRatingLabels(typo) {
  //
  let dataRows = [];
  //
  $(".js-rating-text-row" + (typo === undefined ? "" : typo) + "").each(
    function (i, el) {
      dataRows.push({ id: i, text: $(this).find("input").val() });
    }
  );
  //
  return dataRows;
}

//
function getRatingRows(il, typo) {
  let i = 0,
    rows = "";
  //
  typo = typo === undefined ? "" : typo;
  if (il === 0) return "";

  for (i; i < il; i++) {
    rows += `<div class="cs-m5  js-rating-text-row${typo}"><label>Rating ${
      i + 1
    }</label><input class="form-control" value="${getRatingText(
      i,
      typo
    )}" /></div>`;
  }

  return rows;
}

//
function getRatingText(i, typo) {
  if (typo !== "") {
    return eQuestion.ratingLabels[i] === undefined
      ? ratingText[i]
      : eQuestion.ratingLabels[i].text;
  }
  return ratingText[i];
}

//
function callDrager() {
  $("#js-data-area").sortable({
    placeholder: "ui-state-highlight",
  });
  $("#js-data-area").disableSelection();
}

$("#js-data-area").on("sortstop", callSort);

function callSort(e, c) {
  //
  let new_index =
    $("#js-data-area")
      .find(`tr[data-number="${$(c.item).data("number")}"]`)
      .parent()
      .index() - 1;
  //
  template.questions = array_move(
    template.questions,
    $(c.item).data("number") - 1,
    new_index
  );
  //
  loadStep2();
}

function array_move(arr, old_index, new_index) {
  arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
  return arr; // for testing
}

// Add Question Process Ends

// Question remove starts
$(document).on("click", ".js-question-remove", function (e) {
  //
  e.preventDefault();
  //
  template.questions.splice($(this).closest("tr").data("number") - 1, 1);
  loadStep2();
});
// Question remove ends

// Question edit starts
$(document).on("click", ".js-question-edit", function (e) {
  e.preventDefault();
  //
  eQuestion = {};
  eQuestionPlace = $(this).closest("tr").data("number");
  //
  let question = template.questions[$(this).closest("tr").data("number") - 1];
  //
  if (question === undefined) return;
  //
  eQuestion = question;
  //
  loadStep(4);
});

//
function loadStep4() {
  //
  $("#js-edit-question-number").text(eQuestionPlace);
  //
  if (CKEDITOR.instances["js-edit-question-description"] === undefined)
    CKEDITOR.replace("js-edit-question-description");
  $(".js-edit-rating-box").hide(0);
  $(".js-edit-rating-list-box").hide(0).html("");
  //
  $("#js-edit-use-label").prop("checked", eQuestion.useLabels);
  $("#js-edit-include-na").prop("checked", eQuestion.includeNA);
  //
  $("#js-edit-question-type").select2();
  $("#js-edit-question-type").select2("val", eQuestion.type);
  //
  $("#js-edit-rating-scale").select2();
  $("#js-edit-rating-scale").select2("val", eQuestion.ratingScale);
  //
  //
  $("#js-edit-question-text").val(eQuestion.question);
  //
  CKEDITOR.instances["js-edit-question-description"].setData(
    eQuestion.description
  );
}

$("#js-edit-question-type").change((e) => {
  $(".js-edit-rating-box").hide(0);
  if ($(e.target).val() != "text") {
    $(".js-edit-rating-box").show(0);
  }
});

$("#js-edit-rating-scale").change((e) => {
  if ($("#js-edit-use-label").prop("checked") === true) {
    $(".js-edit-rating-list-box")
      .show(0)
      .html(getRatingRows($(e.target).val(), "-edit"));
  }
});

//
$("#js-edit-use-label").click(function (e) {
  if ($(this).prop("checked") === true) {
    $(".js-edit-rating-list-box").show(0);
    $(".js-edit-rating-list-box").html(
      getRatingRows($("#js-edit-rating-scale").val(), "-edit")
    );
  } else {
    $(".js-edit-rating-list-box").hide(0).html("");
  }
});

$(".js-edit-question-cancel").click((e) => {
  e.preventDefault();
  eQuestion = {};
  eQuestionPlace = 0;
  //
  loadStep(2);
});

$("#js-edit-question-submit").submit((e) => {
  e.preventDefault();
  //
  let q = {};
  //
  q.question = $("#js-edit-question-text").val().trim();
  q.description = CKEDITOR.instances["js-edit-question-description"].getData();
  q.type = $("#js-edit-question-type").val();
  q.ratingScale = $("#js-edit-rating-scale").val();
  q.useLabels = $("#js-edit-use-label").prop("checked");
  q.includeNA = $("#js-edit-include-na").prop("checked");
  q.sortOrder = 1;
  q.ratingLabels = [];
  //
  if (q.type !== "text" && q.useLabels === true) {
    q.ratingLabels = getRatingLabels("-edit");
  }
  //
  if (q.question == "") {
    alertify.alert("WARNING!", "Question is required.", () => {});
  } else if (q.type === "0") {
    alertify.alert("WARNING!", "Question type is required.", () => {});
  } else {
    template.questions[eQuestionPlace - 1] = q;
    alertify.alert("SUCCESS!", "Question updated successfully.", () => {
      loadStep(2);
    });
  }
});
// Question edit ends

//
$(".js-question-cancel").click((e) => {
  e.preventDefault();
  //
  loadStep(2);
});

$(".js-add-save-template").click((e) => {
  //
  e.preventDefault();
  //
  if (template.questions.length === 0) {
    alertify.alert("WARNING!", "Please add at-least one question.");
    return;
  }
  //
  loader(true);
  //
  $.post(
    `${baseURI}`,
    {
      Action: "save_template",
      Data: template,
    },
    (resp) => {
      //
      if (resp.Status === false) {
        alertify.alert("WARNING!", resp.Response, () => {});
        loader(false);
        return;
      }
      alertify.alert("SUCCESS!", resp.Response, () => {
        window.location.reload();
      });
    }
  );
  //
  console.log(template);
});

//
function loadStep2() {
  let rows = "";
  //
  if (template.questions.length > 0) {
    template.questions.map((question, i) => {
      rows += "<tr data-number=" + (i + 1) + ">";
      rows += `   <td>${question.question}</td>`;
      rows += `   <td>${question.type.toUpperCase()}</td>`;
      rows += `   <td>
      <a href="" class="btn btn-warning js-question-edit" title="Edit Question"><i class="fa fa-pencil"></i></a>
      <a href="" class="btn btn-danger js-question-remove" title="Remove Question"><i class="fa fa-remove"></i></a>
      </td>`;
      rows += "</tr>";
    });
    $(".js-add-save-template").attr("disabled", false);
  } else {
    rows = `<tr><td colspan="${
      $("table th").length
    }"><p class="alert alert-info text-center">No questions found.</p></td></tr>`;
    $(".js-add-save-template").prop("disabled", true);
  }
  $("table tbody").html(rows);
  callDrager();
}

//
function loadStep(step) {
  $(".js-template-step").hide(0);
  $('.js-template-step[data-step="' + step + '"]').show(0);
  pages[`step${step}`]();
}

//
function loader(doShow) {
  if (doShow) {
    $(".cs-loader").show();
  } else {
    $(".cs-loader").hide();
  }
}

$(".js-view-template").click((e) => {
  e.preventDefault();
  loadStep(1);
});

function loadStep1() {
  console.log(template.main);
  $("#js-template-title").val(template.main.title);
}

loader(false);
loadStep(5);

// New helpers
$(".js-back").click(function (e) {
  e.preventDefault();
  loadStep($(this).data("to"));
});

//
function makeEmployeeName(obj) {
  let name = `${obj.first_name} ${obj.last_name}`;
  if (obj.job_title != "" && obj.job_title != null)
    name += ` (${obj.job_title})`;
  name += ` [${obj.access_level}${obj.access_level_plus == 1 ? " Plus" : ""}]`;
  //
  return name;
}
