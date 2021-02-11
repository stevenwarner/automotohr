$(function() {
    //
    getEmployeeGraphBalance();

    //
    function getEmployeeGraphBalance() {
        $.post(
            handlerURL, {
                action: 'get_employee_balances',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                public: 0
            },
            (resp) => {

                //
                $('#jsRemainingTime').html(`${resp.Data.Balance.Remaining.text} `);
                $('#jsConsumedTime').html(`${resp.Data.Balance.Consumed.text} `);
                //
                let total = 0;
                $.each(resp.Data.Timeoffs, (i, v) => {
                    total += v[0];
                });
                //
                $('#jsTotalTimeoffs').html(`${total} Time-off${total > 1 || total == 0 ? 's' : ''} approved for ${moment().format('YYYY')}`);

            }
        );
    }


});