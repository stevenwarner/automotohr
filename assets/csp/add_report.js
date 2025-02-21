$(function Overview() {
	let externalPointer = 0;
	let XHR = null;
	//
	$("#report_status").select2({
		minimumResultsForSearch: -1,
	});

	$("#report_date").datetimepicker({
		format: "m/d/Y",
		datepicker: true,
		timepicker: false,
		changeYear: true,
		changeMonth: true,
	});

	$("#report_completion_date").datetimepicker({
		format: "m/d/Y",
		datepicker: true,
		timepicker: false,
		changeYear: true,
		changeMonth: true,
	});

	//
	$("#jsAddReportForm").validate({
		rules: {
			report_title: {
				required: true,
			},
			report_date: {
				required: true,
			},
		},
		messages: {
			report_title: {
				required: "Please enter report title",
			},
			report_date: {
				required: "Please select report date",
			},
		},
		submitHandler: function (form) {
			handleFormSubmission(form);
		},
	});

	$(".jsAddExternalEmployee").click(function (event) {
		event.preventDefault();
		$(".jsAddExternalBody .alert").remove();
		generateExternalEmployees();
	});

	$(document).on("click", ".jsRemoveExternalEmployee", function () {
		const external = $(this).closest(".row");
		alertify.confirm(
			"Are you sure you want to remove this external employee?",
			function () {
				$(
					`input[name="external_employees[${external.data(
						"external"
					)}]['name']"]`
				).rules("remove");
				$(
					`input[name="external_employees[${external.data(
						"external"
					)}]['email']"]`
				).rules("remove");
				external.remove();
				externalPointer--;

				if (externalPointer == 0) {
					$(".jsAddExternalBody").html(
						' <div class="alert alert-info text-center">No External employees found</div>'
					);
				}
			}
		);
	});

	function generateExternalEmployees() {
		let html = `
        <div class="row" data-external="${externalPointer}">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="external_employee_name">Name</label>
                    <input type="text" name="external_employees_names[${externalPointer}]['name']" class="form-control" required>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="external_employee_email">Email</label>
                    <input type="email" name="external_employees_emails[${externalPointer}]['email']" class="form-control" required>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-red btn-block jsRemoveExternalEmployee">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        `;

		externalPointer++;
		$(".jsAddExternalBody").append(html);
		$(
			`input[name="external_employees[${externalPointer - 1}]['name']"]`
		).rules("add", {
			required: true,
			messages: {
				required: "Please enter the name",
			},
		});
		$(
			`input[name="external_employees[${externalPointer - 1}]['email']"]`
		).rules("add", {
			required: true,
			email: true,
			messages: {
				required: "Please enter the email",
				email: "Please enter a valid email address",
			},
		});
		return html;
	}

	function handleFormSubmission(form) {
		//
		if (XHR === null) {
			//
			ml(true, "jsPageLoader");
			//
			XHR = $.ajax({
				url: baseUrl(
					"compliance_safety_reporting/add/" + getSegment(2)
				),
				method: "POST",
				data: $(form).serializeArray(),
			})
				.always(function () {
					XHR = null;
					ml(false, "jsPageLoader");
				})
				.fail(handleErrorResponse)
				.done(function (resp) {
					_success(resp.message, function () {
						window.location.href = baseUrl(
							"compliance_safety_reporting/edit/" + resp.id
						);
					});
				});
		}
	}

	ml(false, "jsPageLoader");
});
