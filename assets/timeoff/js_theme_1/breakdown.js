$(function () {
	let selectedEmployeeId = employeeId,
		selectedEmployeeName = employeeName;

	//
	$(document).on("click", ".jsBreakdownRequest", function (e) {
		//
		e.preventDefault();
		//
		let employeeId = $(this).data("id");
		selectedEmployeeId = employeeId;
		//
		Modal({
			Id: "breakdownModal",
			Title: `Time-off for ${selectedEmployeeName}`,
			Body: "",
			Loader: "breakdownModalLoader",
		},
			async () => {
				//
				let bodyText = `
            <div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Policy</th>
                            <th>Allowed Time</th>
                            <th>Consumed Time</th>
                            <th>Remaining Time</th>
                        </tr>
                    </thead>
                    <tbody id="jsEmployeePolicyModalBody"></tbody>
                </table>
            </div>
            </div>
            `;

				$("#breakdownModal").find(".csModalBody").append(bodyText);
				// Get employee policies
				let policies = await fetchEmployeePolicies();
				policies = policies.Data;
				//
				let policyList = {};
				//
				policies.map(function (policy) {
					//
					if (!policyList.hasOwnProperty(policy.Category))
						policyList[policy.Category] = [];
					//
					policyList[policy.Category].push(policy);
				});
				//
				policyList = sortObjectByKey(policyList);
				//
				let policyOptions = "";
				//
				Object.keys(policyList).map((p) => {
					//
					let policy = policyList[p];
					//
					policyOptions += `<tr><th colspan="7">${p}</th></tr>`;
					//
					policy.map((pi) => {
						if (pi.Reason != "") return;
						policyOptions += `
                <tr ${pi.Reason != "" ? 'class="bg-danger"' : ""}>
                <td>${pi.Title} (<strong class="text-${pi.CategoryType == 1 ? 'success' : 'danger'}">${pi.CategoryType == 1 ? 'Paid' : 'Unpaid'}</strong>) ${pi.Reason != ""
								? ` <i class="fa fa-question-circle jsPopover" title="Why?" data-content="${pi.Reason}"></i>`
								: ""
							}</td>
                <td>${pi.AllowedTime !== undefined &&
								pi.AllowedTime.M.minutes != 0 &&
								pi.Reason == "" &&
								pi.EmploymentStatus != "probation"
								? pi.AllowedTime.text
								: "Unlimited"
							}</td>
                <td>${pi.ConsumedTime !== undefined ? pi.ConsumedTime.text : "0"
							}</td>
                <td>${pi.RemainingTime !== undefined &&
								pi.AllowedTime !== undefined &&
								pi.AllowedTime.M.minutes != 0
								? pi.RemainingTime.text
								: "Unlimited"
							}</td>
                </tr>
                `;
					});
				});

				$("#jsEmployeePolicyModalBody").append(policyOptions);

				//
				ml(false, "breakdownModalLoader");
			}
		);
	});

	// Fetch employee policies
	function fetchEmployeePolicies() {
		return new Promise((res, rej) => {
			//
			$.post(
				handlerURL,
				{
					action: "get_employee_policies",
					companyId: companyId,
					employerId: employerId,
					employeeId: selectedEmployeeId,
				},
				function (resp) {
					res(resp);
				}
			);
		});
	}

	// Object sorter
	var sortObjectByKey = function (obj) {
		var keys = [];
		var sorted_obj = {};

		for (var key in obj) {
			if (obj.hasOwnProperty(key)) {
				keys.push(key);
			}
		}

		// sort keys
		keys.sort();

		// create new array based on Sorted Keys
		jQuery.each(keys, function (i, key) {
			sorted_obj[key] = obj[key];
		});

		return sorted_obj;
	};
});