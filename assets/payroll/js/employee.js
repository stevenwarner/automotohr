$(function PayrollEmployees() {
    //
    var xhr = null;
    //
    var tab = getSegment(2);
    //
    var baseURI = baseURL('payroll/');
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
        xhr = $.get(baseURI + 'employees?on_payroll=' + onPayroll)
            .done(HandleSuccess)
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
    function HandleSuccess(resp) {
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
    }
    //
    function SetView(resp) {
        // Show counts
        $('.jsEmployeeCountPayroll').text('(' + (resp.payroll_employees_count) + ')');
        $('.jsEmployeeCountNormal').text('(' + (resp.normal_employees_count) + ')');
        //
        var rows = '';
        //
        if (!resp.list.length) {
            return HandleError();
        }
        //
        resp.list.map(function(emp) {
            //
            rows += '<tr class="jsPayrollEmployeeRow" data-id="' + (emp.sid) + '">';
            rows += '    <td class="vam">';
            rows += '        <div class="csF14">';
            rows += '            <strong>' + (emp.name) + '</strong><br>';
            rows += emp.role;
            rows += '        </div>';
            rows += '    </td>';
            rows += '    <td class="vam text-right">';
            rows += '        <div class="csF14">';
            rows += '            <strong>' + (emp.joined_on ? emp.joined_on : 'Not Specified') + '</strong>';
            rows += '        </div>';
            rows += '    </td>';
            rows += '    <td class="vam text-right">';
            rows += '        <div class="csF14">';
            rows += '            <strong>' + (tab != 'payroll' ? '-' : '27th December, 2020') + '</strong>';
            rows += '        </div>';
            rows += '    </td>';
            rows += '    <td class="vam text-right">';
            rows += '        <div class="csF14">';
            rows += '            <strong>' + (tab != 'payroll' ? 'Not On Payroll' : (emp.onboard_completed == 1 ? "Completed" : "Inprogress")) + '</strong>';
            rows += '        </div>';
            rows += '    </td>';
            rows += '    <td class="vam text-right">';
            if (tab == 'payroll') {
                rows += '        <button class="btn btn-orange csF14 jsPayrollAddProcess"><i class="fa csF16 fa-edit" aria-hidden="true"></i> Edit</button>';
                if (emp.onboard_completed == 0) {
                    rows += '        <button class="btn btn-danger csF14 jsPayrollDeleteEmployee"><i class="fa csF16 fa-times-circle" aria-hidden="true"></i> Delete</button>';
                }
            } else {
                rows += '        <button class="btn btn-orange csF14 jsPayrollAddProcess"><i class="fa csF16 fa-plus-circle" aria-hidden="true"></i> Add To Payroll</button>';
            }
            rows += '    </td>';
            rows += '</tr>';
        });
        //
        $('#jsPayrollEmployeesDataHolder').html(rows);
        //
        ml(false, 'payroll_employees');
    }
    //
    GetEmployees(tab == 'payroll' ? 1 : 0)
});