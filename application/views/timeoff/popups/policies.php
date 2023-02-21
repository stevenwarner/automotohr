<style>
    .csModalLoader {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        background-color: #ffffff;
    }

    .csModalLoader i {
        position: relative;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 50px;
        color: #81b431;
    }

    .bg-danger {
        background-color: #f2dede !important;
    }
</style>

<script>
    //
    let policyEmployeeId = null;
    let policyTimeslot = null;
    let policyFormat = null;
    // startBalanceProcess(58); 
    // Step 1
    async function startPolicyProcess(employeeId, employeeName) {
        // Load Modal
        let rows = `
        <!-- Modal -->
        <div class="modal fade" id="jsEmployeePolicyModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #81b431; color: #ffffff;">
                        <h4 class="modal-title">Balance Breakdown for ${employeeName} (As Of Today)</h4>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="csModalLoader jsEPModalLoader"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Policy</th>
                                            <th>Allowed Time</th>
                                            <th>Manual Balance</th>
                                            <th>Carryover Balance</th>
                                            <th>Consumed Time</th>
                                            <th>Remaining Time</th>
                                            <th>Employment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsEmployeePolicyModalBody"></tbody>
                                </table>
                            </div>
                        </div>
                        <div>
                            <div class="col-sm-12 bg-danger p10">
                                <span><strong><em>Note: Represents the policies that are not available to employees until they meet the Accrual or Qualify. <br/><br/>To see why, click '<i class="fa fa-question-circle"></i>' icon next to the policy title.</strong></em></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-black" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>`;
        //
        $('#jsEmployeePolicyModal').remove();
        //
        $('body').append(rows);
        $('.jsEPModalLoader').show(0);
        $('#jsEmployeePolicyModal').modal('show');
        // Set the employee id
        policyEmployeeId = employeeId;
        // Get employee policies
        let policies = await fetchEmployeePolicies();
        //
        if (policies.Status === false) {
            $('#jsEmployeePolicyModal').modal('hide');
            alertify.alert('ERROR!', 'System could not found any policies against the selected employee.', () => {});
            //
            return;
        } else policies = policies.Data;
        //
        let policyList = {};
        //
        policies.map(function(policy) {
            //
            if (!policyList.hasOwnProperty(policy.Category)) policyList[policy.Category] = [];
            //
            policyList[policy.Category].push(policy);
        });
        //
        policyList = sortObjectByKey(policyList);
        //
        let policyOptions = '';
        //
        Object.keys(policyList).map((p) => {
            //
            let policy = policyList[p];
            //
            policyOptions += `<tr><th colspan="7">${p}</th></tr>`;
            //
            policy.map((pi) => {
                policyOptions += `
                <tr ${ pi.Reason != '' ? 'class="bg-danger"' : '' }>
                    <td>${pi.Title}  ${(pi.CategoryType==1)?' (Paid)' : ' (Unpaid) '}  ${ pi.Reason != '' ? ` <i class="fa fa-question-circle jsPopover" title="Why?" data-content="${pi.Reason}"></i>` : '' }</td>
                    <td>${pi.AllowedTime !== undefined && pi.AllowedTime.M.minutes != 0 && pi.Reason == '' && pi.EmploymentStatus != 'probation' ? pi.AllowedTime.text : 'Unlimited'}</td>
                    <td>${pi.Balance !== undefined ? pi.Balance.text : '0'}</td>
                    <td>${pi.CarryOverTime !== undefined ? pi.CarryOverTime.text : '0'}</td>
                    <td>${pi.ConsumedTime !== undefined ? pi.ConsumedTime.text : '0'}</td>
                    <td>${pi.RemainingTime !== undefined && pi.AllowedTime !== undefined && pi.AllowedTime.M.minutes != 0 ? pi.RemainingTime.text : 'Unlimited'}</td>
                    <td>${ucwords(pi.EmployementStatus)}</td>
                    </tr>
              `;
            });
        });
        //
        $('#jsEmployeePolicyModalBody').html(policyOptions);
        $('.jsEPModalLoader').hide(0);
        $('.jsPopover').popover({
            html: true,
            trigger: 'click'
        })
    }

    // Fetch employee policies
    function fetchEmployeePolicies() {
        return new Promise((res, rej) => {
            //
            $.post("<?= base_url('timeoff/handler'); ?>", {
                action: 'get_employee_policies',
                companyId: <?= $company_sid; ?>,
                employerId: <?= $employer_sid; ?>,
                employeeId: policyEmployeeId
            }, function(resp) {
                res(resp);
            });
        });
    }

    // Object sorter
    var sortObjectByKey = function(obj) {
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
        jQuery.each(keys, function(i, key) {
            sorted_obj[key] = obj[key];
        });

        return sorted_obj;
    };
</script>

<style>
    .cs-required {
        font-weight: bold;
        font-size: 14px;
        color: #cc1100;
    }

    .js-number {
        height: 40px;
    }

    #ui-datepicker-div {
        z-index: 1051 !important;
    }
</style>