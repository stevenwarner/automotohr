$(function CompanyBankAccounts() {
	let XHR = null;

	$(document).on("click", ".jsCompanyBankAccountAddBtn", function (event) {
		event.preventDefault();
		let btnREF = callButtonHook($(this), true);
		Modal(
			{
				Title: "Add A Company Bank Account",
				Id: "jsCompanyBankAccount",
				Loader: "jsCompanyBankAccountLoader",
				Body: '<div id="jsCompanyBankAccountBody"></div>',
			},
			function () {
				// get the view
				loadAddView(btnREF);
				//
				$("#jsCompanyBankAccount .jsModalCancel").click(function () {
					XHR = null;
					callButtonHook(btnREF, false);
				});
			}
		);
	});

	$(document).on("click", ".jsCompanyBankAccountDeleteBtn", function (event) {
		event.preventDefault();
		const eventId = $(this).closest("tr").data("id");
		const _this = $(this);
		return _confirm(
			"Do you really want to delete this bank account?",
			function () {
				deleteTheRow(eventId, callButtonHook(_this, true));
			}
		);
	});

	function loadAddView(btnREF) {
		if (XHR !== null) {
			XHR.abort();
		}

		XHR = $.ajax({
			url: baseUrl("settings/company/accounts/add"),
			method: "GET",
		})
			.always(function () {
				XHR = null;
				callButtonHook(btnREF, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				$("#jsCompanyBankAccountBody").html(resp.view);
				ml(false, "jsCompanyBankAccountLoader");
				applyFormValidation();
			});
	}

	function applyFormValidation() {
		$("#jsCompanyBankAccountForm").validate({
			rules: {
				jsBankName: {
					required: true,
				},
				jsRoutingNumber: {
					required: true,
					minlength: 9,
					maxlength: 9,
					pattern: /\d/,
				},
				jsAccountNumber: {
					required: true,
					minlength: 3,
					pattern: /\d/,
				},
				jsAccountType: {
					required: true,
				},
			},
			submitHandler: function (form) {
				submitForm(formArrayToObj($(form).serializeArray(), true));
			},
		});
	}

	function submitForm(formData) {
		//
		if (XHR !== null) {
			return false;
		}
		//
		const btnREF = callButtonHook($(".jsCompanyBankAccountFormBtn"), true);
		//
		XHR = $.ajax({
			url: baseUrl("settings/company/accounts/add"),
			method: "POST",
			data: formData,
		})
			.always(function () {
				XHR = null;
				callButtonHook(btnREF, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				//
				if (resp.required) {
					return _confirm(resp.message, function () {
						formData["confirmed"] = true;
						submitForm(formData);
					});
				}
				_success(resp.message, function () {
					$("#jsCompanyBankAccount .jsModalCancel").click();
					window.location.refresh();
				});
			});
	}

	function deleteTheRow(eventId, btnREF) {
		//
		$.ajax({
			url: baseUrl("settings/company/accounts/" + eventId),
			method: "DELETE",
		})
			.always(function () {
				callButtonHook(btnREF, false);
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				_success(resp.message, window.location.refresh);
			});
	}
});
