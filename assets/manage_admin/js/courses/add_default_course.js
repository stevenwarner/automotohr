$(function defaultCourses() {
  var xhr = null;
  /**
   * Capture add course event
   */
  $(".jsCourseAdd").click(function (event) {
    //
    event.preventDefault();
    // start course process
    startAddCourseProcess();
  });

  /**
   * Capture add course event
   */
  $(document).on("click", 'input[name="jsAddCourseType"]', function () {
    //
    $(".jsAddCourseScormArea").addClass("hidden");
    //
    if ($(this).val() == "scorm") {
      $(".jsAddCourseScormArea").removeClass("hidden");
    }
  });

  /**
   * Capture add course event
   */
  $(document).on("submit", "#jsAddCourseForm", function (event) {
    //
    event.preventDefault();
    //
    var obj = {
      title: $("#jsAddCourseTitle").val().trim(),
      description: $("#jsAddCourseDescription").val().trim(),
      type: $('input[name="jsAddCourseType"]:checked').val(),
      version: $('input[name="jsAddCourseVersion"]:checked').val(),
      file_name: "",
      status: $('input[name="jsAddCourseStatus"]:checked').val(),
      job_roles: $("#jsAddCourseJobTitles").val(),
      course_json: "",
    };
    // Validation
    if (obj.title.length <= 3) {
      return alertify.alert("Course title must be greater than 3 characters.");
    }
    if (obj.type === undefined) {
      return alertify.alert("Course type is required.");
    }
    if (obj.type == "scorm" && obj.version === undefined) {
      return alertify.alert("SCORM version is required.");
    }

    var fileObj = $("#jsAddCourseFile").mFileUploader("get");
    //
    if (fileObj.length === 0) {
      return alertify.alert("Please select a SCORM package.");
    }
    if (fileObj.hasError === true) {
      return alertify.alert("Please select a valid SCORM package.");
    }
    //
    var form = new FormData();
    form.append("file", fileObj);
	for(let index in obj) {
		form.append(index, obj[index]);
	}
    //
    $.ajax({
      url: window.location.origin + "/courses/course/add",
      method: "POST",
      contentType: false,
      processData: false,
      data: form,
    });
    //
    alert("form submitted");
  });

  startAddCourseProcess();

  function startAddCourseProcess() {
    //
    Modal(
      {
        Id: "jsCourseAddModal",
        Loader: "jsCourseAddModalLoader",
        Body: '<div id="jsCourseAddModalBody"></div>',
        Title: "Add a New Course",
      },
      function () {
        // get view
        getAddCourseView();
      }
    );
  }

  function getAddCourseView() {
    XHR = $.get(window.location.origin + "/courses/get_view/add")
      .success(function (resp) {
        $("#jsCourseAddModalBody").html(resp.view);
        $("#jsAddCourseFile").mFileUploader({
          fileLimit: "50MB",
          allowedTypes: ["zip"],
          text: "Click / Drag to upload",
        });
        $("#jsAddCourseJobTitles").select2({
          closeOnSelect: false,
        });

        ml(false, "jsCourseAddModalLoader");
      })
      .fail(function () {
        xhr = null;
        ml(false, "jsCourseAddModalLoader");
      });
  }
});
