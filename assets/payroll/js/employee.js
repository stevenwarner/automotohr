$(function PayrollEmployees() {
    //
    var xhr = null;
    //
    var baseURL = baseURL('payroll/');
    //
    $('#jsFilterEmployees').select2();
    //
    $('.jsFilterBTN').click(function(event) {
        //
        event.preventDefault();
        //
        $(this).toggleClass('btn-orange');
        $(this).toggleClass('btn-black');
        $('.jsFilterBox').toggle();
    });
    //
    function GetEmployees(onPayroll) {
        //
        if (xhr !== null) {
            return;
        }
        //
        $('#jsPayrollEmployeesDataHolder').html('');
        //
        xhr = $.get(baseURL + 'payroll/employees/' + onPayroll)
            .done(function(resp) {
                //
                xhr = null;
                //
                $('.jsEmployeeCount').text(resp.length)
                    //
                if (!resp) {
                    return HandleError();
                }
                //
                SetView(resp)
            })
            .fail(HandleError);
    }
    //
    function HandleError() {
        //
        $('#jsPayrollEmployeesDataHolder').html('<tr><td colspan="' + ($('#jsPayrollEmployeesTable thead tr th').length) + '"><p class="alert alert-info text-center">You don\'t have any employees on the payroll.</p></td></tr>');
        //
        return ml(false, 'payroll_employees');
    }
    //
    function SetView(employeesList) {
        //

    }
    //
    // GetEmployees(1)
});