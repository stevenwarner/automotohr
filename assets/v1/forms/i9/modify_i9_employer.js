$(function i9Form() {
	const rulesObj = {
		section1_last_name: {
			required: true,
		},
		section1_first_name: {
			required: true,
		},
		section1_address: {
			required: true,
		},
		section1_city_town: {
			required: true,
		},
		section1_state: {
			required: true,
		},
		section1_zip_code: {
			required: true,
			pattern: /^[0-9]{5}$/,
		},
		section1_date_of_birth: {
			required: true,
		},
		section1_social_security_number: {
			required: true,
			pattern: /^[0-9]{9}$/,
		},
		section1_emp_email_address: {
			required: true,
		},
		section1_emp_telephone_number: {
			required: true,
		},
		section1_today_date: {
			required: true,
		},
	};

	const messagesObj = {
		section1_last_name: {
			required: "Last Name is required.",
		},
		section1_first_name: {
			required: "First Name is required.",
		},
		section1_address: {
			required: "Address is required",
		},
		section1_city_town: {
			required: "City/Town is required",
		},
		section1_state: {
			required: "State is required",
		},
		section1_zip_code: {
			required: "Zip Code is required",
			pattern: "Zip Code must be 5 digits long",
		},
		section1_date_of_birth: {
			required: "Date of Birth is required",
		},
		section1_social_security_number: {
			required: "Social Security number is required",
			pattern: "Social Security Number must be 9 digits long",
		},
		section1_emp_email_address: {
			required: "This email address is required",
		},
		section1_emp_telephone_number: {
			required: "This telephone number is required",
		},
		section1_today_date: {
			required: "Date is required",
		},
	};

	// attach datepicker
	$(".date_of_birth")
		.datepicker({
			dateFormat: "mm/dd/yy",
			changeMonth: true,
			changeYear: true,
			yearRange: "1920:+0",
		})
		.val();

	$(".date_picker").datepicker({
		dateFormat: "mm/dd/yy",
		setDate: new Date(),
		maxDate: new Date(),
		minDate: new Date(),
	});

	$(".date_picker2").datepicker({
		dateFormat: "mm/dd/yy",
		changeYear: true,
		changeMonth: true,
	});

	/**
	 * show popup models
	 */
	$(".modalShow").click(function (event) {
		event.preventDefault();
		let info_id = $(this).attr("src");
		let title_string = $(this).parent().text();
		let model_title = title_string.replace("*", "");
		if (info_id == "section_2_alien_number") {
			if ($("#alien_authorized_to_work").is(":checked")) {
				info_id = "section_21_alien_number";
			}
		}
		let model_content = $("#" + info_id).html();
		let tmpModal = $("#myPopupModal");
		tmpModal.find(".modal-title").text(model_title);
		tmpModal.find(".modal-body").html(model_content);
		tmpModal.modal("show");
	});

	/**
	 *
	 * @returns
	 */
	$('[name="section1_penalty_of_perjury"]').click(function (event) {
		//
		$(".jsSectionAdmission").addClass("hidden");
		$(".jsSection1Hide").addClass("hidden");
		$(".jsSection1UICS").addClass("hidden");
		//
		if ($(this).val() === "alien-work") {
			$(".jsSection1Option4").removeClass("hidden");
			$(".jsSectionAdmission").removeClass("hidden");
		}

		if ($(this).val() === "permanent-resident") {
			$(".jsSection1UICS").removeClass("hidden");
		}
	});

	/**
	 *
	 * @returns
	 */
	$("#section1_alien_registration_number_two").change(function (event) {
		//
		$(".jsSection1Hide").addClass("hidden");
		//
		$(".jsSection1Option4").removeClass("hidden");
		if ($(this).val() === "Alien-Number") {
			$(".jsSection1Option4").removeClass("hidden");
			$(".jsSectionAdmission").removeClass("hidden");
		} else if ($(this).val() === "USCIS-Number") {
			$(".jsSection1UICS").removeClass("hidden");
		} else if ($(this).val() === "Foreign-Number") {
			$(".jsSection1Foreign").removeClass("hidden");
		}
	});

	/**
	 *
	 */
	$(".section1_preparer_or_translator").change(function () {
		//
		if ($(this).val() === "not-used") {
			//
			$(".jsTranslatorBlock").addClass("hidden");
			return;
		}
		//
		$(".jsTranslatorBlock").removeClass("hidden");
	});

	// form validation
	$("#jsI9Form").validate({
		ignore: ":hidden:not(select)",
		rules: {},
		messages: messagesObj,
		submitHandler: function () {
			//
			processI9();
		},
	});

	//
	function processI9() {
		// set the pass object
		const obj = {
			section1_last_name: $('[name="section1_last_name"]').val().trim(),
			section1_first_name: $('[name="section1_first_name"]').val().trim(),
			section1_middle_initial: $('[name="section1_middle_initial"]')
				.val()
				.trim(),
			section_1_other_last_names_used: $(
				'[name="section_1_other_last_names_used"]'
			)
				.val()
				.trim(),
			section1_address: $('[name="section1_address"]').val().trim(),
			section1_city_town: $('[name="section1_city_town"]').val().trim(),
			section1_state: $(".section1_state option:selected").val(),
			section1_zip_code: $('[name="section1_zip_code"]').val().trim(),
			section1_date_of_birth: $('[name="section1_date_of_birth"]')
				.val()
				.trim(),
			section1_social_security_number: $(
				'[name="section1_social_security_number"]'
			)
				.val()
				.trim(),
			section1_emp_email_address: $('[name="section1_emp_email_address"]')
				.val()
				.trim(),
			section1_emp_telephone_number: $(
				'[name="section1_emp_telephone_number"]'
			)
				.val()
				.trim(),
			section1_penalty_of_perjury: $(
				'[name="section1_penalty_of_perjury"]:checked'
			)
				.val()
				.trim(),
			section1_today_date: $('[name="section1_today_date"]').val().trim(),
			section1_alien_registration_number_two: $(
				"#section1_alien_registration_number_two option:selected"
			).val(),
			alien_authorized_expiration_date: $(
				'[name="alien_authorized_expiration_date"]'
			).val(),
			section1_uscis_registration_number_one: $(
				"#section1_uscis_registration_number_one"
			).val(),
			foreign_passport_number: $("#foreign_passport_number").val().trim(),
			country_of_issuance: $("#country_of_issuance").val().trim(),
			form_admission_number: $("#form_admission_number").val().trim(),
			section1_preparer_or_translator: $(
				".section1_preparer_or_translator:checked"
			).val(),
			section1_preparer: {},
		};
		//
		if (obj.section1_preparer_or_translator === "used") {
			//
			if (
				$("#section1_preparer_today_date_1").val().trim() !== "" ||
				$("#section1_preparer_last_name_1").val().trim() !== "" ||
				$("#section1_preparer_middle_initial_1").val().trim() !== "" ||
				$("#section1_preparer_first_name_1").val().trim() !== "" ||
				$("#section1_preparer_address_1").val().trim() !== "" ||
				$("#section1_preparer_city_town_1").val().trim() !== "" ||
				$("#section1_preparer_zip_code_1").val().trim() !== ""
			) {
				obj.section1_preparer["1"] = {
					last_name: $("#section1_preparer_last_name_1").val().trim(),
					first_name: $("#section1_preparer_first_name_1")
						.val()
						.trim(),
					middle_initial: $("#section1_preparer_middle_initial_1")
						.val()
						.trim(),
					address: $("#section1_preparer_address_1").val().trim(),
					city: $("#section1_preparer_city_town_1").val().trim(),
					state: $(
						"#section1_preparer_state_1 option:selected"
					).val(),
					zip_code: $("#section1_preparer_zip_code_1").val().trim(),
					today_date: $("#section1_preparer_today_date_1")
						.val()
						.trim(),
				};
			}
			//
			if (
				$("#section1_preparer_signature_2").val().trim() !== "" ||
				$("#section1_preparer_today_date_2").val().trim() !== "" ||
				$("#section1_preparer_last_name_2").val().trim() !== "" ||
				$("#section1_preparer_middle_initial_2").val().trim() !== "" ||
				$("#section1_preparer_first_name_2").val().trim() !== "" ||
				$("#section1_preparer_address_2").val().trim() !== "" ||
				$("#section1_preparer_city_town_2").val().trim() !== "" ||
				$("#section1_preparer_zip_code_2").val().trim() !== ""
			) {
				obj.section1_preparer["2"] = {
					last_name: $("#section1_preparer_last_name_2").val().trim(),
					first_name: $("#section1_preparer_first_name_2")
						.val()
						.trim(),
					middle_initial: $("#section1_preparer_middle_initial_2")
						.val()
						.trim(),
					address: $("#section1_preparer_address_2").val().trim(),
					city: $("#section1_preparer_city_town_2").val().trim(),
					state: $(
						"#section1_preparer_state_2 option:selected"
					).val(),
					zip_code: $("#section1_preparer_zip_code_2").val().trim(),
					today_date: $("#section1_preparer_today_date_2")
						.val()
						.trim(),
				};
			}

			//
			if (
				$("#section1_preparer_signature_3").val().trim() !== "" ||
				$("#section1_preparer_today_date_3").val().trim() !== "" ||
				$("#section1_preparer_last_name_3").val().trim() !== "" ||
				$("#section1_preparer_middle_initial_3").val().trim() !== "" ||
				$("#section1_preparer_first_name_3").val().trim() !== "" ||
				$("#section1_preparer_address_3").val().trim() !== "" ||
				$("#section1_preparer_city_town_3").val().trim() !== "" ||
				$("#section1_preparer_zip_code_3").val().trim() !== ""
			) {
				obj.section1_preparer["3"] = {
					last_name: $("#section1_preparer_last_name_3").val().trim(),
					first_name: $("#section1_preparer_first_name_3")
						.val()
						.trim(),
					middle_initial: $("#section1_preparer_middle_initial_3")
						.val()
						.trim(),
					address: $("#section1_preparer_address_3").val().trim(),
					city: $("#section1_preparer_city_town_3").val().trim(),
					state: $(
						"#section1_preparer_state_3 option:selected"
					).val(),
					zip_code: $("#section1_preparer_zip_code_3").val().trim(),
					today_date: $("#section1_preparer_today_date_3")
						.val()
						.trim(),
				};
			}
			//
			if (
				$("#section1_preparer_signature_4").val().trim() !== "" ||
				$("#section1_preparer_today_date_4").val().trim() !== "" ||
				$("#section1_preparer_last_name_4").val().trim() !== "" ||
				$("#section1_preparer_middle_initial_4").val().trim() !== "" ||
				$("#section1_preparer_first_name_4").val().trim() !== "" ||
				$("#section1_preparer_address_4").val().trim() !== "" ||
				$("#section1_preparer_city_town_4").val().trim() !== "" ||
				$("#section1_preparer_zip_code_4").val().trim() !== ""
			) {
				obj.section1_preparer["4"] = {
					last_name: $("#section1_preparer_last_name_4").val().trim(),
					first_name: $("#section1_preparer_first_name_4")
						.val()
						.trim(),
					middle_initial: $("#section1_preparer_middle_initial_4")
						.val()
						.trim(),
					address: $("#section1_preparer_address_4").val().trim(),
					city: $("#section1_preparer_city_town_4").val().trim(),
					state: $(
						"#section1_preparer_state_4 option:selected"
					).val(),
					zip_code: $("#section1_preparer_zip_code_4").val().trim(),
					today_date: $("#section1_preparer_today_date_4")
						.val()
						.trim(),
				};
			}
		}
		//
		const btnRef = callButtonHook($(".jsI9SaveBtn"), true);
		//
		$.ajax({
			url: baseUrl(
				"forms/i9/modify/" + getSegment(3) + "/" + getSegment(4)
			),
			method: "POST",
			data: obj,
		})
			.always(function () {
				callButtonHook(btnRef, false);
			})
			.fail(handleErrorResponse)
			.success(function (resp) {
				_success(resp.message, window.location.refresh);
			});
	}


    $(".section1_penalty_of_perjury:checked").click();
});
