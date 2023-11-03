$(function plans() {
	/**
	 * set XHR request holder
	 */
	let XHR = null;
	let step = 0;

	/**
	 * add event
	 */
	$(document).on("click", ".jsAddBenefitPlan", function (event) {
		//
		event.preventDefault();
		//
		Modal(
			{
				Id: "jsAddBenefitPlanModal",
				Loader: "jsAddBenefitPlanModalLoader",
				Body: '<div id="jsAddBenefitPlanModalBody"></div>',
				Title: "Add benefit Plan",
			},
			loadAddBenefitPlanView
		);
	});

	/**
	 * add useful link input section
	 */
	$(document).on("click", "#jsAddAnOtherlink", function (event) {
		//
		event.preventDefault();
		//
		let linkCount = $('.jsAdditionalLink').length;
		//
		if (linkCount < 2) {

			html = `<div class="row jsAdditionalLink mb10">
						<div class="col-sm-5 col-md-5 col-xs-12">
							<label>Link </label>
							<input type="text" class="form-control csRBC jsLink" />
						</div>
						<div class="col-sm-5 col-md-5 col-xs-12">
							<label>Link Display Name</label>
							<input type="text" class="form-control csRBC jsLinkName" /> 
						</div>
						<div class="col-sm-2 col-md-2 col-xs-12">
							<label>&nbsp;</label><br>
							<a href="#" class="jsRemovelink csF20 text-danger"><strong><i class="fa fa-trash"></i></strong></a>
						</div>
					</div>`;
			//
			$('.jsAdditionalLinkSection').append(html);				
		}
		
		if (linkCount == 1) {
			$("#jsAddAnOtherlink").hide();
		}
	});

	/**
	 * Remove useful link section
	 */
	$(document).on("click", ".jsRemovelink", function (event) {
		//
		event.preventDefault();
		//
		$("#jsAddAnOtherlink").show();
		$(this).closest('.jsAdditionalLink').remove();
	});

	/**
	 * add group
	 */
	$(document).on("click", "#jsAddAnOtherGroup", function (event) {
		//
		event.preventDefault();
		//
		let groupCount = $('.jsEligibleGroup').length;
		//
		$(".jsRemoveGroup").removeClass("dn");
		//
		html = `<div class="panel panel-default jsEligibleGroup">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-12 col-xs-12">
						<h1 class="csF16 m0" style="padding-top: 10px;">
							<i class="fa fa-users" aria-hidden="true"></i>&nbsp;
							<strong class="jsPlanStepHeading">
								Group ${groupCount + 1}

								<a href="#" class="jsRemoveGroup pull-right">
									<i class="fa fa-trash" aria-hidden="true"></i>
								</a>
							</strong>
						</h1>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<form action="">
					<!--  -->
					<div class="form-group">
						<label class="csF16">Which employees are eligible? <a href="#">0 selected</a></label>
					</div>

					<!--  -->
					<div class="form-group">
						<label class="csF16">When do new employees become eligible? <strong class="text-danger">*</strong></label>
						<select class="form-control jsEligiblity">
							<option value="">On a manually entered date</option>
							<option value="">Immediately upon hire</option>
							<option value="">After a waiting period</option>
							<option value="">First of the month following period</option>
						</select>
					</div>

					<!--  -->
					<div class="form-group">
						<label class="csF16">When do terminated employees lose eligibility?</label>
						<select class="form-control jsType">
							<option value="">On a manually entered date</option>
							<option value="">Day following termination</option>
							<option value="">1st of the month following terminated</option>
						</select>
					</div>

					<hr>

					<!--  -->
					<div class="form-group">
						<label class="csF16">How much will they pay?</label>
						<p class="text-danger"><em><strong>This number is provided by your carrier and can be found in your account structure document.</strong></em></p>
						<div class="table-responsive">
							<table class="table table-striped">
								<caption></caption>
								<thead>
									<tr>
										<th scope="col">Coverage Level</th>
										<th scope="col">Total Cost</th>
										<th scope="col">Employee Pays</th>
										<th scope="col">Company Pays</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="vam">
											Employee
										</td>
										<td class="vam">
											<input type="text" class="form-control jsTotalCost" />
										</td>
										<td class="vam">
											<nav aria-label="Page navigation example">
												<ul class="pagination">
													<li class="page-item"><a class="page-link" href="#"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
													<li class="page-item"><a class="page-link" href="#"><i class="fa fa-percent" aria-hidden="true"></i></a></li>
												</ul>
											</nav>
										</td>
										<td class="vam jsCompanyShare">
											-
										</td>
									</tr>
									<tr>
										<td class="vam">
											Employee + Spouse
										</td>
										<td class="vam">
											<input type="text" class="form-control jsTotalCost" />
										</td>
										<td class="vam">
											<nav aria-label="Page navigation example">
												<ul class="pagination">
													<li class="page-item"><a class="page-link" href="#"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
													<li class="page-item"><a class="page-link" href="#"><i class="fa fa-percent" aria-hidden="true"></i></a></li>
												</ul>
											</nav>
										</td>
										<td class="vam jsCompanyShare">
											-
										</td>
									</tr>
									<tr>
										<td class="vam">
											Employee + Children
										</td>
										<td class="vam">
											<input type="text" class="form-control jsTotalCost" />
										</td>
										<td class="vam">
											<nav aria-label="Page navigation example">
												<ul class="pagination">
													<li class="page-item"><a class="page-link" href="#"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
													<li class="page-item"><a class="page-link" href="#"><i class="fa fa-percent" aria-hidden="true"></i></a></li>
												</ul>
											</nav>
										</td>
										<td class="vam jsCompanyShare">
											-
										</td>
									</tr>
									<tr>
										<td class="vam">
											Employee + Family
										</td>
										<td class="vam">
											<input type="text" class="form-control jsTotalCost" />
										</td>
										<td class="vam">
											<nav aria-label="Page navigation example">
												<ul class="pagination">
													<li class="page-item"><a class="page-link" href="#"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
													<li class="page-item"><a class="page-link" href="#"><i class="fa fa-percent" aria-hidden="true"></i></a></li>
												</ul>
											</nav>
										</td>
										<td class="vam jsCompanyShare">
											-
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>`;
		//
		$('#jsEligibilityGroupSection').append(html);
	});

	/**
	 * Remove group
	 */
	$(document).on("click", ".jsRemoveGroup", function (event) {
		//
		event.preventDefault();
		//
		let groupCount = $('.jsEligibleGroup').length;
		//
		if (groupCount == 2) {
			$(".jsRemoveGroup").addClass("dn")
		}
		//
		$(this).closest('.jsEligibleGroup').remove();
	});

	/**
	* get carrier code
	*/
   $(document).on("change", ".jsCarrier", function (event) {
	   //
	   event.preventDefault();
	   //
	   let id = $(this).val();
	   $.ajax({
			url: baseUrl("sa/benefits/plan/get_carrier_code/"+id),
			method: "GET",
			cache: false,
		})
		.success(function (resp) {
			$(".jsCarrierNumber").val(resp.carrierCode);
		})
		.fail(handleErrorResponse)
		.always(function () {
			
		});
   });

	/**
	 * Save plan information
	 */
	$(document).on("click", ".jsSaveNextBtn", function (event) {
		//
		event.preventDefault();
		//
		if (step == 1) {
			savePlanDetails();
		}
	});

	/**
	 * load plans view
	 */
	function loadViews() {
		//
		$.ajax({
			url: baseUrl("sa/benefits/plans/view/"+benefitId),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsBenefitPlansBox").html(resp.plansView);
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");
			});
	}

	/**
	 * load add carrier view
	 */
	function loadAddBenefitPlanView() {
		//
		$.ajax({
			url: baseUrl("sa/benefits/plan/add/view"),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				//
				step = 1;
				//
				$("#jsAddBenefitPlanModalBody").html(resp.view);
				//
				$(".jsSaveNextBtn").text("Next: Coverage Options");
				//
				getAddPlanPartialView();
			})
			.fail(handleErrorResponse)
			.always(function () {
				ml(false, "jsAddBenefitPlanModalLoader");
			});
	}

	function getAddPlanPartialView () {
		$.ajax({
			url: baseUrl("sa/benefits/plans/add/partial/"+step),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsBenefitPlanSection").html(resp.partialView);
				//
				if (step == 1) {
					$("#jsPlanAttachmentUpload").msFileUploader({
						fileLimit: "10mb",
						allowedTypes: ['jpg', 'jpeg', 'png', 'gif'],
					});

					// Datepickers
					

					$("#jsPlanStartDate")
					.datepicker({
						dateFormat: "mm/dd/yy",
						changeYear: true,
						changeMonth: true,
						onSelect: function (value) {
							$("#jsPlanEndDate").datepicker(
								"option",
								"minDate",
								value
							);
						},
					})
					.datepicker(
						"option",
						"maxDate",
						$("#jsPlanEndDate").val()
					);

					$("#jsPlanEndDate")
						.datepicker({
							dateFormat: "mm/dd/yy",
							changeYear: true,
							changeMonth: true,
							onSelect: function (value) {
								$("#jsPlanStartDate").datepicker(
									"option",
									"maxDate",
									value
								);
								//
								$('.jsAddIndefiniteCourse').prop('checked', false);
								$(".jsRecurringCourses").show();
							},
						})
						.datepicker(
							"option",
							"minDate",
							$("#jsPlanStartDate").val()
						);
				}
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsPageLoader");
			});

	}

	async function savePlanDetails () {
		//
		let obj = {};
		//
		obj = {
			planName: $(".jsPlanName").val().trim(),
			planCarrier: $(".jsCarrier").val(),
			planType: $(".jsPlanType").val(),
			planStart: $(".jsPlanStartDate").val(),
			planEnd: $(".jsPlanEndDate").val(),
			planRate: $(".jsRate").val(),
		};
		//
		const errorsArray = [];
		// validation 
		//
		if (!obj.planName) {
			errorsArray.push('"Plan Name" is required.');
		}
		//
		if (!obj.planCarrier) {
			errorsArray.push('"Carrier" is required.');
		}
		//
		if (!obj.planType) {
			errorsArray.push('"Plan Type" is required.');
		}
		//
		if (!obj.planStart) {
			errorsArray.push('"Plan Start Date" is required.');
		}
		//
		if (!obj.planEnd) {
			errorsArray.push('"Plan End Date" is required.');
		}
		//
		if (!obj.planRate) {
			errorsArray.push('"Plan Rate" is required.');
		}
		//
		$.each($('.jsAdditionalLink'), function(i) {
			let link = $(this).closest('.jsLink').val();
			let linkName = $(this).closest('.jsLinkName').val();
			//
			if (!link || !linkName) {
				errorsArray.push(`"Additional Link ${i+1}" is required.`);
			}
		});
		//
		if (errorsArray.length) {
			return _error(getErrorsStringFromArray(errorsArray));
		}
		//
	}

	/**
	 * save carrier details
	 */
	async function saveCarrier(obj, fileObject) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsAddBenefitCarrierModalLoader",
			"Please wait while we are saving the carrier."
		);
		//
		// let uploadedFileObject = {};
		// //
		// uploadedFileObject = await uploadFile(fileObject);
		// //
		// if (typeof uploadedFileObject === "string") {
		// 	// parse json
		// 	uploadedFileObject = JSON.parse(uploadedFileObject);
		// }
		// //
		// //file upload failed
		// if (!Object.keys(uploadedFileObject).length) {
		// 	// hide the loader
		// 	ml(false, "jsAddBenefitCarrierModalLoader");
		// 	// show error
		// 	return alertify.alert("ERROR!", "Failed to upload logo.", CB);
		// }
		// // saves the file name
		// obj.logo = uploadedFileObject.data;
		obj.logo = 'https://automotohrattachments.s3.amazonaws.com/jay-antol-Xbf_4e7YDII-unsplash-8fyy1G.jpg';
		//
		XHR = $.ajax({
			url: baseUrl("sa/benefits/carrier"),
			method: "POST",
			data: obj,
			cache: false,
		})
			.success(function (resp) {
				return _success(resp.msg, function () {
					loadViews();
					$(".jsModalCancel").trigger("click");
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsAddBenefitCarrierModalLoader");
			});
	}

	/**
	 * load edit carrier view
	 */
	function loadEditBenefitCarrierView(id) {
		//
		$.ajax({
			url: baseUrl("sa/benefits/carrier/" + id),
			method: "GET",
			cache: false,
		})
			.success(function (resp) {
				$("#jsEditBenefitCarrierModalBody").html(resp.view);
				//
				var placeholder = $("#jsOldCarrierAttachmentUpload").val();
				//
				$("#jsCarrierAttachmentUpload").msFileUploader({
					fileLimit: "10mb",
					allowedTypes: ['jpg', 'jpeg', 'png', 'gif'],
					placeholderImage: placeholder
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				ml(false, "jsEditBenefitCarrierModalLoader");
			});
	}

	/**
	 * load edit carrier details
	 */
	async function updateCarrier(obj, key, uploadFile, fileObject) {
		//
		if (XHR !== null) {
			return;
		}
		//
		ml(
			true,
			"jsEditBenefitCarrierModalLoader",
			"Please wait while we are saving the carrier."
		);
		//
		if (uploadFile) {
			//
			// let uploadedFileObject = {};
			// //
			// uploadedFileObject = await uploadFile(fileObject);
			// //
			// if (typeof uploadedFileObject === "string") {
			// 	// parse json
			// 	uploadedFileObject = JSON.parse(uploadedFileObject);
			// }
			// //
			// //file upload failed
			// if (!Object.keys(uploadedFileObject).length) {
			// 	// hide the loader
			// 	ml(false, "jsAddBenefitCarrierModalLoader");
			// 	// show error
			// 	return alertify.alert("ERROR!", "Failed to upload logo.", CB);
			// }
			// // saves the file name
			// obj.logo = uploadedFileObject.data;
			obj.logo = 'jay-antol-Xbf_4e7YDII-unsplash-8fyy1G.jpg';
		}
		console.log(key)
		//
		XHR = $.ajax({
			url: baseUrl("sa/benefits/carrier/" + key),
			method: "POST",
			data: obj,
			cache: false,
		})
			.success(function (resp) {
				return _success(resp.msg, function () {
					loadViews();
					$(".jsModalCancel").trigger("click");
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				XHR = null;
				ml(false, "jsEditBenefitCarrierModalLoader");
			});
	}

	loadViews();
});
