$(function addSchedule() {
	/**
	 * holds xhr call
	 * @var object
	 */
	let XHR = null;

	// apply validator
	const validator = $("#jsAddScheduleForm").validate({
		rules: {
			// pay_frequency: { required: true },
			first_pay_date: { required: true, date: true },
			// first_pay_period_end_date: { required: true },
		},
		submitHandler: function (form) {
			// get the form object
			const formObj = convertFormArrayToObject($(form).serializeArray());
            formObj.first_pay_date = "2023-01-02";
            formObj.first_pay_period_end_date = "2023-01-01";

            const fpd = moment(formObj.first_pay_date, "YYYY-MM-DD");
            const fped = moment(
				formObj.first_pay_period_end_date,
				"YYYY-MM-DD"
			);

            validator.showErrors({
				first_pay_date: "I know that your firstname is Pete, Pete!",
			});
			// convert the dates to moment
			console.log(formObj);
			return false;
		},
	});

	/**
	 * convert form array to an object
	 * @param {array} formArray
	 * @returns object
	 */
	function convertFormArrayToObject(formArray) {
		//
		const obj = {};
		//
		formArray?.map(function (v) {
			obj[v.name] = v.value.trim();
		});
		//
		return obj;
	}
});
