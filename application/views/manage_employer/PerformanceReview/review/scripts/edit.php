<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.20/lodash.min.js"></script>
<script>
    const ratingText = <?php echo json_encode($RatingText); ?>;

    var baseURI = "<?= base_url('performance/handler') ?>",
        XHR = null,
        employees = <?= json_encode($employees); ?>,
        template = {
            main: <?= json_encode($review['main']); ?>,
            questions: <?= json_encode($review['questions']); ?>,
            Reviewees: <?= json_encode($review['reviewees']); ?>,
            Reviewers: <?= json_encode($review['reviewers']); ?>,
            ReportingManagers: <?= json_encode($review['reporting_managers']); ?>
        };
    const pages = {
        step1: loadStep1,
        step2: loadStep2,
        step3: () => {},
        step4: loadStep4,
        step5: loadStep5,
        step6: loadStep6,
        step3: () => {},
    };
    let eQuestion = {},
        eQuestionPlace = 0;
    //
    CKEDITOR.replace("js-template-description");
    //
    $("#js-template-status").select2();
    loadStep(1);
    // Step 0
    $(".js-list-group-item").click(function(e) {
        $(".js-list-group-item").removeClass("active");
        $(this).addClass("active");
        // Update data
        $.post(
            `${baseURI}`, {
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

    // step 5
    // Add employees page
    $(".js-add-employee").click(() => {
        //
        loadStep(5);
    });

    $(".js-add-employee-reviewers").click(() => {
        //
        let obj = {};
        //
        obj.revieweeType = $('.js-choose-reviewee:checked').val();
        obj.department = $('#js-review-department').length > 0 ? $('#js-review-department').val() : false;
        obj.selectedEmployees = [];
        //
        obj.selectedEmployees = obj.revieweeType == 1 ? employees.All : (obj.revieweeType == 2 ? employees.SB : getSelectedEmployees());
        //
        if (obj.revieweeType == 3 && obj.selectedEmployees.length === 0) {
            alertify.alert("WARNING!", "Please select at least one employee.", () => {});
            return;
        }
        //
        if (obj.revieweeType == 4 && obj.selectedEmployees.length === 0) {
            alertify.alert("WARNING!", "Please select at least one employee.", () => {});
            return;
        }
        //
        template.Reviewees = obj;
        localStorage.setItem('reviews', JSON.stringify(template.Reviewees));
        //
        loadStep(6);
    });

    // Employee add
    function loadStep5() {
        //
        let review_type = template.Reviewees.revieweeType;
        //
        $("#js-total-revieews").text(0);
        $("#js-review-box").html("");
        //
        $("input[name=reviewees][value="+(review_type)+"]").prop("checked",true)
        employeeViewSetter(review_type);
    }

    //
    $(".js-choose-reviewee").click(function(e) {
        $("#js-total-revieews").text(0);
        $("#js-review-box").html("");
        employeeViewSetter($(this).val());
    });

    function employeeViewSetter(v) {
        $("#js-total-revieews").text(0);
        $("#js-review-box").html("");
        switch (v.toLowerCase()) {
            case "1":
                $("#js-total-revieews").text(employees.All.length);
                break;
            case "2":
                $("#js-total-revieews").text(employees.SB.length);
                break;
            case "3":
                loadReviewSelectBox(employees, "all");
                break;
            case "4":
                loadReviewSelectBox(employees, "custom");
                break;
        }
    }

    //
    function loadReviewSelectBox(a, t) {
        //
        let selectedEmployees = template.Reviewees.selectedEmployees;
        //
        let employeesOptions = "";
        //
        let rows = "";
        //
        employeesOptions = `<option value="0" disabled>[PLEASE SELECT]</value>`;
        a.All.map((employee) => {
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
        //
        $("#js-review-employees").select2('val', selectedEmployees);
        $("#js-total-revieews").text(selectedEmployees.length);

        //
        if (t == "custom") {
            //
            employeesOptions = `<option value="0">[PLEASE SELECT]</value>`;
            $.each(a.Departments, (i, department) => {
                employeesOptions += `<optgroup label="${i}">`;
                $.each(department, (i1, team) => {
                    employeesOptions += `<option value="${team.TeamId}">${team.TeamName}</option>`;
                });
                employeesOptions += `</optgroup>`;
            });
            //
            rows = `
			<div class="form-group">
				<label>Select Department(s) <span class="cs-required">*</span></label>
				<div>
					<select id="js-review-department">
							${employeesOptions}
					</select>
				</div>
			</div>`;
            //
            $("#js-review-box").append(rows);
            $("#js-review-department").select2();
        }
    }

    //
    $(document).on("change", "#js-review-employees", loadReviewCount);
    $(document).on("change", '#js-department-employee', loadReviewCount);

    //
    $(document).on('change', '#js-review-department', function() {
        if ($('#js-department-employee').length > 0) {
            $('#js-department-employee').select2('destroy');
            $('#js-department-employee').remove();
            $('.js-department-employee-box').remove();

        }
        if ($(this).val() === null || $(this).val() == 0) {
            //
            //
            loadReviewCount();
            //
            return;
        }
        let employeesOptions = "";
        //
        let rows = "";
        //
        employeesOptions = `<option value="0">[PLEASE SELECT]</value>`;
        employees.All.map((employee) => {
            employeesOptions += `<option value="${
					employee["sid"]
				}">${makeEmployeeName(employee)}</value>`;
        });
        //
        rows = `
			<div class="form-group js-department-employee-box">
				<label>Select Department Employee(s) <span class="cs-required">*</span></label>
				<div>
					<select id="js-department-employee" multiple="true">
						${employeesOptions}
					</select>
				</div>
    		</div>`;
        //
        $("#js-review-box").append(rows);
        $("#js-department-employee").select2({
            closeOnSelect: false
        });
    });

    //
    function loadReviewCount() {
        //
        $("#js-total-revieews").text(getSelectedEmployees().length);
    }

    //
    function getSelectedEmployees() {
        //
        let a = [];
        //
        if ($('#js-review-employees').length > 0) {
            if ($('#js-review-employees').val() !== null && $('#js-review-employees').val() != 0) a = a.concat($('#js-review-employees').val());
        }
        if ($('#js-department-employee').length > 0) {
            if ($('#js-department-employee').val() !== null && $('#js-department-employee').val() != 0) a = a.concat($('#js-department-employee').val());
        }

        return _.union(a);
    }

    //
    $("#js-template-add-form").submit((e) => {
        e.preventDefault();
        let localOBJ = {};
        localOBJ.title = $("#js-template-title").val().trim();
        localOBJ.status = $("#js-template-status").val();
        localOBJ.startDate = $("#js-template-start-date").val();
        localOBJ.dueDays = $("#js-template-review-due").val();
        localOBJ.description = CKEDITOR.instances[
            "js-template-description"
        ].getData();
        //
        if (localOBJ.title == "") {
            alertify.alert("WARNING!", "Template name is required.");
        } else if (localOBJ.startDate == "") {
            alertify.alert("WARNING!", "Start date is required.");
        } else if (localOBJ.dueDays == "" || localOBJ.dueDays == "0") {
            alertify.alert("WARNING!", "Review due day is required.");
        } else {
            template.main = localOBJ;
            //
            loadStep(2);
        }
    });

    //
    function loadStep6() {
        //
        $('#js-reviewees-count').text(0);
        $('#js-reviewing-listing').html('');
        //
        let employeesOptions = '';
        //
        employees.All.map((emp) => {
            employeesOptions += `<option value="${emp.sid}">${makeEmployeeName(emp)}</option>`;
        });
        //
        $('#js-reviewees-count').text(template.Reviewees.selectedEmployees.length);
        template.Reviewees.selectedEmployees.map((emp, i) => {
            if (typeof emp === 'string') emp = getEmployeeDetail(emp);
            $('#js-reviewing-listing').append(`
				${i == 0 ? "<hr />" : ""}
				<div class="row js-reviewer-row" data-id="${emp.sid}">
					<div class="col-sm-4">
						<h5 class="text-left"><strong>${makeEmployeeName(emp)}</strong></h5>
					</div>
					<div class="col-sm-5">
						<select id="jsRevieweeSelect2${emp.sid}" class="select2" multiple="true">
							${employeesOptions}
						</select>
					</div>
					<div class="col-sm-2">
						<h6><strong class="js-selected-employee-count">0</strong> Employee(s) selected </h6>
					</div>
				</div>
				<hr />
			`);
        });
        //
        // cons
        //
        $('#js-reporting-managers').html(employeesOptions);
        //
        $('.select2').select2({
            closeOnSelect: false
        });
        $('#js-reporting-managers').select2({
            closeOnSelect: false
        });
        $('#js-reporting-managers').select2('val', template.ReportingManagers);

        // See if we got preselected data
        if(Object.keys(template.Reviewers).length > 0){
            Object.keys(template.Reviewers).map((v) => {
                let reviewer = template.Reviewers[v];
                $(`#jsRevieweeSelect2${v}`).select2('val', reviewer);
                $(`.js-reviewer-row[data-id="${v}"] strong.js-selected-employee-count`).text(reviewer.length);
            });
        }
    }

    //
    function getEmployeeDetail(sid) {
        let i = 0,
            il = employees.All.length;
        //
        for (i; i < il; i++) {
            if (employees.All[i]['sid'] == sid) return employees.All[i];
        }
        //
        return [];
    }

    //
    $(document).on('change', '.select2', function(e) {
        //
        if ($(this).val() === null) {
            $(this).parent().parent().find('strong.js-selected-employee-count').text(0);
            return;
        }
        $(this).parent().parent().find('strong.js-selected-employee-count').text($(this).val().length);
    });

    //
    $(".js-save-review").click((e) => {
        e.preventDefault();
        //
        let obj = {};
        let hasError = false;
        //
        $('.js-reviewer-row').each((i, el) => {
            obj[$(el).data('id')] = $(el).find('select').val() === null ? [] : $(el).find('select').val();
            //
            if ($(el).find('select').val() === null) {
                hasError = true;
                alertify.alert('WARNING!', 'Please select reviewers.', () => {});
            }
            if ($('#js-self-review').prop('checked') === true) {
                obj[$(el).data('id')].push($(el).data('id'));
            }
        });
        //
        if (hasError) return;
        //
        template.Reviewers = obj;
        template.main.sid = <?=$review['main']['sid'];?>;
        template.ReportingManagers = $('#js-reporting-managers').val();
        //
        if (template.ReportingManagers === null) {
            alertify.alert('WARNING!', 'Please select reporting managers', () => {});
            return;
        }
        loader(true);
        //
        $.post(baseURI, {
            Action: "edit_review",
            Data: template,
        }, (resp) => {
            if (resp.Status === false) {
                loader(false);
                alertify.alert("WARNING!", resp.Response, () => {});
                return;
            }
            alertify.alert("SUCCESS!", resp.Response, () => {
                window.location.href = "<?= base_url('performance/review/view'); ?>";
            });
        });
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
    $("#js-add-use-label").click(function(e) {
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
    $("#js-add-rating-scale").change(function() {
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
            function(i, el) {
                dataRows.push({
                    id: i,
                    text: $(this).find("input").val()
                });
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
            return eQuestion.ratingLabels[i] === undefined ?
                ratingText[i] :
                eQuestion.ratingLabels[i].text;
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
    $(document).on("click", ".js-question-remove", function(e) {
        //
        e.preventDefault();
        //
        template.questions.splice($(this).closest("tr").data("number") - 1, 1);
        loadStep2();
    });
    // Question remove ends

    // Question edit starts
    $(document).on("click", ".js-question-edit", function(e) {
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
    $("#js-edit-use-label").click(function(e) {
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
        if(template.questions[eQuestionPlace - 1].sid !== undefined){
            q.sid = template.questions[eQuestionPlace - 1].sid;
        }
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
            `${baseURI}`, {
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
        //
        let sd = moment(template.main.start_date);
        let ed = moment(template.main.end_date);
        //
        $("#js-template-title").val(template.main.title);
        CKEDITOR.instances['js-template-description'].setData(template.main.description);
        $("#js-template-start-date").val(
            moment(template.main.start_date).format('MM/DD/YYYY')
        );
        $("#js-template-review-due").val(
            ed.diff(sd, 'days')
        );
        $('#js-template-start-date').datepicker();
    }

    loader(false);
    // loadStep(6);

    // New helpers
    $(".js-back").click(function(e) {
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
</script>
