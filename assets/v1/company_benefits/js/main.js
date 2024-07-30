$(function () {
	/**
	 * set XHR request holder
	 */
	let XHR = null;

	/**
	 * add a benefit
	 */
	$(document).on("click", ".jsAddBenefit", function (event) {
		//
		event.preventDefault();
		//
		Modal(
			{
				Id: "jsAddBenefitModal",
				Loader: "jsAddBenefitModalLoader",
				Body: '<div id="jsAddBenefitModalBody"></div>',
				Title: "Add benefit",
			},
			loadAddBenefitView
		);
	});

	/**
	 * add a benefit process
	 */
	$(document).on("click", ".jsSaveBenefit", function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			description: $(".jsName").val().trim(),
			benefit_type: $(".jsType option:selected").val(),
			employer_taxes: $(".jsEmployerTaxes option:selected").val(),
			employee_w2: $(".jsEmployeeW2 option:selected").val(),
			active: $(".jsActive option:selected").val(),
		};
		//
		const errorsArray = [];
		// validation
		if (!obj.description) {
			errorsArray.push('"Name" is required.');
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		saveBenefit(obj);
	});

	/**
	 * edit a benefit
	 */
	$(document).on("click", ".jsEditBenefit", function (event) {
		//
		event.preventDefault();
		//
		const key = $(this).closest("tr").data("id");
		//
		Modal(
			{
				Id: "jsEditBenefitModal",
				Loader: "jsEditBenefitModalLoader",
				Body: '<div id="jsEditBenefitModalBody"></div>',
				Title: "Edit benefit",
			},
			function () {
				loadEditBenefitView(key);
			}
		);
	});

	/**
	 * edit a benefit process
	 */
	$(document).on("click", ".jsUpdateBenefit", function (event) {
		//
		event.preventDefault();
		//
		const obj = {
			description: $(".jsName").val().trim(),
			active: $(".jsActive option:selected").val(),
		};
		//
		const key = $(".jsKey").val();
		//
		const errorsArray = [];
		// validation
		if (!obj.description) {
			errorsArray.push('"Name" is required.');
		}
		if (!key) {
			errorsArray.push('"Key" is required.');
		}
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		updateBenefit(obj, key);
	});

	/**
	 * delete a benefit
	 */
	$(document).on("click", ".jsDeleteBenefit", function (event) {
		//
		event.preventDefault();
		//
		const key = $(this).closest("tr").data("id");
		let rows = "";
		rows += "<p><strong>This action is not revertible</strong></p><br />";
		rows +=
			"<p>The following must be true in order to delete a company benefit</p><br />";
		rows +=
			"<p>There are no employee benefits associated with the company benefit</p>";
		rows +=
			"<p>There are no payroll items associated with the company benefit</p>";

		return _confirm(rows, function () {
			deleteBenefit(key);
		});
	});

	/**
	 * edit benefit employees
	 */
	$(document).on("click", ".jsEditEmployeesBenefit", function (event) {
		//
		event.preventDefault();
		//
		const key = $(this).data("id");
		//
		Modal(
			{
				Id: "jsEditEmployeeBenefitModal",
				Loader: "jsEditEmployeeBenefitModalLoader",
				Body: '<div id="jsEditEmployeeBenefitModalBody"></div>',
				Title: "Manage benefit employees",
			},
			function () {
				loadEditEmployeeBenefitView(key);
			}
		);
	});

	/**
	 * select all employees
	 */
	$(document).on("click", ".jsSelectAll", function (event) {
		//
		event.preventDefault();
		//
		$('[name="employees[]"]').prop("checked", true);
	});

	/**
	 * deselect all employees
	 */
	$(document).on("click", ".jsDeSelectAll", function (event) {
		//
		event.preventDefault();
		//
		$('[name="employees[]"]').prop("checked", false);
	});

	/**
	 * edit benefit employees
	 */
	$(document).on("click", ".jsUpdateBenefitEmployees", function (event) {
		//
		event.preventDefault();
		//
		if (!$('input[name="employees[]"]:checked').length) {
			return _error("Please select at least one employee.");
		}
		//
		const key = $(this).data("key");
		//
		const obj = {
			employees: [],
			employee_deductions: 0,
			company_contribution: 0,
			company_contribution_annual_maximum: "",
			limit_option: "",
			catch_up: "false",
			coverage_amount: "",
			coverage_salary_multiplier: "0.00",
			deduction_reduces_taxable_income: null,
		};
		// get the selected employees
		$('input[name="employees[]"]:checked').map(function () {
			obj.employees.push($(this).val());
		});
		// get the employee deductions
		obj.employee_deductions = $(".jsDeduction").val().trim();
		// get the company contributions
		obj.company_contribution = $(".jsCompanyContribution").val().trim();
		// get the company contributions
		obj.company_contribution_annual_maximum = $(
			".jsCompanyContributionAnnualMaximum"
		)
			.val()
			.trim();
		// get the company contributions
		obj.limit_option = $(".jsCompanyLimitOption").val().trim();
		// get the company contributions
		obj.catch_up = $(".jsCompanyCatchUp option:selected").val().trim();
		// get the company contributions
		obj.coverage_amount = $(".jsCoverageAmount").val().trim();
		// get the company contributions
		obj.coverage_salary_multiplier = $(".jsCoverageSalaryMultiplier")
			.val()
			.trim();
		// get the company contributions
		obj.deduction_reduces_taxable_income = $(
			".jsDeductionReducesTaxableIncome option:selected"
		)
			.val()
			.trim();
		//
		if (!obj.employee_deductions && !obj.company_contribution) {
			return _error(
				"Employee deduction or company contribution is required."
			);
		} else if (
			obj.employee_deductions == 0 &&
			obj.company_contribution == 0
		) {
			return _error(
				"Employee deduction or company contribution is required."
			);
		}
		updateBenefitEmployees(obj, key);
	});

	/**
	 * edit benefit employees
	 */
	$(document).on("click", ".jsBenefitEmployeesListing", function (event) {
		//
		event.preventDefault();
		//
		const key = $(this).closest("tr").data("id");
		//
		Modal(
			{
				Id: "jsEmployeeBenefitListingModal",
				Loader: "jsEmployeeBenefitListingModalLoader",
				Body: '<div id="jsEmployeeBenefitListingModalBody"></div>',
				Title: "Manage employees",
			},
			function () {
				loadEmployeeBenefitListingView(key);
			}
		);
	});

	/**
	 * edit benefit employees
	 */
	$(document).on(
		"click",
		".jsBenefitEmployeesListingWithin",
		function (event) {
			//
			event.preventDefault();
			//
			loadEmployeeBenefitListingView($(this).data("key"));
		}
	);

	/**
	 * update benefit employees
	 */
	$(document).on("click", ".jsEditEmployeeBenefit", function (event) {
		//
		event.preventDefault();
		//
		loadEmployeeBenefitEditView($(this).closest("tr").data("id"));
	});

	/**
	 * update benefit employees process
	 */
	$(document).on("click", ".jsUpdateBenefitEmployee", function (event) {
		//
		event.preventDefault();
		//
		const key = $(".jsKey").val();
		const key2 = $(".jsBenefitEmployeesListingWithin").data("key");
		const obj = {
			employee_deductions: $(".jsDeduction").val().trim() || 0,
			company_contribution: $(".jsCompanyContribution").val().trim() || 0,
			active: $(".jsActive option:selected").val(),
			company_contribution_annual_maximum:
				$(".jsCompanyContributionAnnualMaximum").val().trim() || 0,
			limit_option: $(".jsCompanyLimitOption").val().trim(),
			catch_up: $(".jsCompanyCatchUp option:selected").val().trim(),
			coverage_amount: $(".jsCoverageAmount").val().trim(),
			coverage_salary_multiplier: $(".jsCoverageSalaryMultiplier")
				.val()
				.trim(),
			deduction_reduces_taxable_income: $(
				".jsDeductionReducesTaxableIncome option:selected"
			)
				.val()
				.trim(),
		};
		//
		const errorsArray = [];
		// validation
		if (!key) {
			errorsArray.push('"Key" is required.');
		}
		if (!obj.employee_deductions && !obj.company_contribution) {
			errorsArray.push(
				"Employee deduction or company contribution is required."
			);
		} else if (
			obj.employee_deductions == 0 &&
			obj.company_contribution == 0
		) {
			errorsArray.push(
				"Employee deduction or company contribution is required."
			);
		}

		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
		updateBenefitEmployeeById(key, key2, obj);
	});

	/**
	 * delete benefit employees process
	 */
	$(document).on("click", ".jsRemoveEmployeeFromBenefit", function (event) {
		//
		event.preventDefault();
		//
		const key = $(this).closest("tr").data("id");
		//
		return _confirm(
			"This will stop all deductions and contributions for this employee. This action cannot be undone.",
			function () {
				deleteEmployeeBenefit(key);
			}
		);
	});

	/**
	 * load benefits
	 */
	function loadView() {
		//
		$.ajax({
			url: baseUrl("benefits/all"),
			method: "GET",
			cache: false,
		})
			.always(function () {
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsBenefitArea").html(resp.view);
			});
	}

	/**
	 * load add benefit view
	 */
	function loadAddBenefitView() {
		//
		$.ajax({
			url: baseUrl("benefits/add"),
			method: "GET",
			cache: false,
		})
			.always(function () {
				ml(false, "jsAddBenefitModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsAddBenefitModalBody").html(resp.view);
			});
	}

	/**
	 * save benefit process
	 *
	 * @param {object} obj
	 */
	function saveBenefit(obj) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsAddBenefitModalLoader",
			"Please wait while we are saving the benefit."
		);
		//
		XHR = $.ajax({
			url: baseUrl("benefits/add"),
			method: "POST",
			data: obj,
			cache: false,
		})
			.always(function () {
				XHR = null;
				ml(false, "jsAddBenefitModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				loadView();
				$(".jsModalCancel").trigger("click");
				_success(resp.message);
			});
	}

	/**
	 * load edit benefit view
	 *
	 * @param {number} key
	 */
	function loadEditBenefitView(key) {
		//
		$.ajax({
			url: baseUrl("benefits/edit/" + key),
			method: "GET",
			cache: false,
		})
			.always(function () {
				ml(false, "jsEditBenefitModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsEditBenefitModalBody").html(resp.view);
			});
	}

	/**
	 * save benefit process
	 *
	 * @param {object} obj
	 * @param {number} key
	 */
	function updateBenefit(obj, key) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsEditBenefitModalLoader",
			"Please wait while we are updating the benefit."
		);
		//
		XHR = $.ajax({
			url: baseUrl("benefits/edit/" + key),
			method: "POST",
			data: obj,
			cache: false,
		})
			.always(function () {
				XHR = null;
				ml(false, "jsEditBenefitModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				loadView();
				$(".jsModalCancel").trigger("click");
				_success(resp.message);
			});
	}

	/**
	 * delete benefit process
	 *
	 * @param {number} key
	 */
	function deleteBenefit(key) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsPageLoader",
			"Please wait while we are deleting the selected benefit."
		);
		//
		XHR = $.ajax({
			url: baseUrl("benefits/" + key),
			method: "DELETE",
			cache: false,
		})
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				loadView();
				_success(resp.message);
			});
	}

	/**
	 * load edit benefit employees view
	 *
	 * @param {number} key
	 */
	function loadEditEmployeeBenefitView(key) {
		//
		$.ajax({
			url: baseUrl("benefits/employees/" + key),
			method: "GET",
			cache: false,
		})
			.always(function () {
				ml(false, "jsEditEmployeeBenefitModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsEditEmployeeBenefitModalBody").html(resp.view);
			});
	}

	/**
	 * save benefit employees process
	 *
	 * @param {object} obj
	 * @param {number} key
	 */
	function updateBenefitEmployees(obj, key) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsEditEmployeeBenefitModalLoader",
			"Please wait while we are updating the benefit employees."
		);
		//
		XHR = $.ajax({
			url: baseUrl("benefits/edit/" + key + "/employees"),
			method: "POST",
			data: obj,
			cache: false,
		})
			.always(function () {
				XHR = null;
				ml(false, "jsEditEmployeeBenefitModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				loadView();
				$(".jsModalCancel").trigger("click");
				_success(resp.message);
			});
	}

	/**
	 * load benefit employees listing view
	 *
	 * @param {number} key
	 */
	function loadEmployeeBenefitListingView(key) {
		//
		$.ajax({
			url: baseUrl("benefits/" + key + "/employees/listing"),
			method: "GET",
			cache: false,
		})
			.always(function () {
				ml(false, "jsEmployeeBenefitListingModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsEmployeeBenefitListingModalBody").html(resp.view);
			});
	}

	/**
	 * load benefit employees edit view
	 *
	 * @param {number} employeeBenefitId
	 */
	function loadEmployeeBenefitEditView(employeeBenefitId) {
		//
		ml(
			true,
			"jsEmployeeBenefitListingModalLoader",
			"Please wait while we are generating a preview."
		);
		//
		$.ajax({
			url: baseUrl("benefits/employees/" + employeeBenefitId + "/edit"),
			method: "GET",
			cache: false,
		})
			.always(function () {
				ml(false, "jsEmployeeBenefitListingModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsEmployeeBenefitListingModalBody").html(resp.view);
			});
	}

	/**
	 * load benefit employees edit view
	 *
	 * @param {number} employeeBenefitId
	 * @param {number} benefitId
	 * @param {object} data
	 */
	function updateBenefitEmployeeById(employeeBenefitId, benefitId, data) {
		//
		ml(
			true,
			"jsEmployeeBenefitListingModalLoader",
			"Please wait while we are updating the benefit."
		);
		//
		$.ajax({
			url: baseUrl("benefits/employees/" + employeeBenefitId),
			method: "POST",
			data,
			cache: false,
			employeeBenefitId,
		})
			.always(function () {
				ml(false, "jsEmployeeBenefitListingModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				loadView();
				loadEmployeeBenefitListingView(benefitId);
				_success(resp.message);
			});
	}

	/**
	 * delete employee benefit
	 *
	 * @param {number} employeeBenefitId
	 */
	function deleteEmployeeBenefit(employeeBenefitId) {
		//
		ml(
			true,
			"jsEmployeeBenefitListingModalLoader",
			"Please wait while we are deleting employee from benefit."
		);
		//
		$.ajax({
			url: baseUrl("benefits/employees/" + employeeBenefitId),
			method: "DELETE",
			cache: false,
		})
			.always(function () {
				ml(false, "jsEmployeeBenefitListingModalLoader");
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				loadView();
				loadEmployeeBenefitListingView(resp.key);
				_success(resp.message);
			});
	}

	// get the benefits
	loadView();
});
