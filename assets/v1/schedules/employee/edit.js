$(function editEmployeesSchedule() {
	/**
	 * holds xhr call
	 * @var object
	 */
	let XHR = null;

	/**
	 * trigger click event
	 */
	$(".jsUpdateEmployeesPaySchedule").click(function (event) {
		event.preventDefault();
		//
		let passObj = {};
		//
		$(".jsPayScheduleRow").map(function () {
			//
			const selectedValue = $(this)
				.find(".jsPaySchedule option:selected")
				.val();

			if (selectedValue != 0) {
				//
				if (!passObj[selectedValue]) {
					//
					passObj[selectedValue] = [];
				}
				passObj[selectedValue].push($(this).data("id"));
			}
		});
		// when nothing is found
		if (!Object.keys(passObj).length) {
			return _error("Please select at least one employee pay schedule.");
		}
		// todo add check for comparison
		processEmployeeSchedule(passObj);
	});

	/**
	 * process schedule
	 * @param {object} formObj
	 * @param {object} buttonRef
	 */
	function processEmployeeSchedule(formObj) {
		// check the XHR object
		if (XHR !== null) {
			return false;
		}
		//
		const buttonHook = callButtonHook(
			$(".jsUpdateEmployeesPaySchedule"),
			true,
			true
		);
		//
		XHR = $.ajax({
			url: baseUrl("schedules/employees/edit"),
			method: "POST",
			data: {
                data: formObj
            },
		})
			.always(function () {
				callButtonHook(buttonHook, false);
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				//
				return _success(resp.msg, function () {
					window.location.href = baseUrl("schedules/employees/edit");
				});
			});
	}
});
