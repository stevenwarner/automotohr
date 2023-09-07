$(function externalPayrollsEmployee() {
	/**
	 * XHR holder
	 */
	let XHR = null;
	/**
	 * external payroll id
	 */
	const externalPayrollId = parseInt(getSegment(2));
	/**
	 * employee id
	 */
	const employeeId = parseInt(getSegment(3));
	//
	$(".jsExternalPayrollSaveBtn").click(function (event) {
		//
		event.preventDefault();
		// lets create array
		const dataOBJ = {
			applicable_earnings: [],
			applicable_benefits: [],
			applicable_taxes: [],
		};
		// get the earnings
        $(".jsExternalPayrollApplicableInputs").map(function(){
            dataOBJ["applicable_earnings"].push(
                {
                    id: $(this).data('id'),
                    value: $(this).val().trim() || 0.0
                }
            );
        });
        // get the taxes
        $(".jsExternalPayrollTaxInputs").map(function () {
			dataOBJ["applicable_taxes"].push({
				id: $(this).data("id"),
				value: $(this).val().trim() || 0.0,
			});
		});

        console.log(dataOBJ)
		// startProcessOfExternalPayrollEmployeeUpdate();
	});

	/**
	 * start the process of external payroll deletion
	 */
	function startProcessOfExternalPayrollEmployeeUpdate() {
		// check if XHR is already in progress
		if (XHR !== null) {
			return false;
		}
		//
		ml(true, "jsPageLoader");
		//
		XHR = $.ajax({
			url: baseUrl(
				"payrolls/external/" + externalPayrollId + "/" + employeeId
			),
			method: "POST",
		})
			.success(function (resp) {
				return _success(resp.message, function () {
					window.location.reload();
				});
			})
			.fail(handleErrorResponse)
			.always(function () {
				//
				XHR = null;
				//
				ml(false, "jsPageLoader");
			});
	}
	//
	ml(false, "jsPageLoader");
});
