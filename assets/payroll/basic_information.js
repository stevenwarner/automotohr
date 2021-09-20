$(function Employee() {
    //
    var LOADER = 'employee';

    /**
     * 
     */
    $('.jsEUpdateBasicInformation').click(function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        //
        o.FirstName = $('.jsEFirstName').val().trim();
        //
        o.MiddleInitial = $('.jsEMiddleInitial').val().replace(/[^a-zA-Z]/g, '').trim();
        //
        o.LastName = $('.jsELastName').val().trim();
        //
        o.SSN = $('.jsESSN').val().replace(/[^0-9]/g, '').trim();
        //
        o.DOB = $('.jsEDOB').val().trim();
        //
        o.Email = $('.jsEEmail').val().trim();
        //
        if (!o.FirstName) {
            return alertify.alert(
                "Error!",
                "First name is required."
            );
        }
        //
        if (!o.MiddleInitial) {
            return alertify.alert(
                "Error!",
                "Middle initial is required."
            );
        }
        //
        if (o.MiddleInitial.length !== 1) {
            return alertify.alert(
                "Error!",
                "Middle initial must be of 1 character."
            );
        }
        //
        if (!o.LastName) {
            return alertify.alert(
                "Error!",
                "Last name is required."
            );
        }
        //
        if (!o.SSN) {
            return alertify.alert(
                "Error!",
                "SSN is required."
            );
        }
        //
        if (o.SSN.length !== 9) {
            return alertify.alert(
                "Error!",
                "SSN must be of 9 digits."
            );
        }
        //
        if (!o.DOB) {
            return alertify.alert(
                "Error!",
                "Date of birth is required."
            );
        }
        //
        if (!o.Email) {
            return alertify.alert(
                "Error!",
                "Email is required."
            );
        }
        //
        ml(true, LOADER);
        //
        o.OnPayroll = onPayroll;
        //
        $.ajax({
            url: API_URL + '/' + employeeId,
            method: "POST",
            data: JSON.stringify(o),
            headers: { 'Content-Type': "application/json" }
        }).done(function(resp) {
            //
            ml(false, LOADER);
            //
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join('<br>') : resp.response);
            }
            //
            return alertify.alert('Success!', resp.response, function() {
                window.location = window.location.origin + window.location.pathname + '?section=bank_accounts';
            });
        });
    });

    /**
     * Get data
     */
    function Get() {
        //
        ml(true, LOADER);
        //
        var columns = [
            'first_name',
            'last_name',
            'middle_initial',
            'date_of_birth',
            'ssn',
            'email'
        ];
        //
        $.get(API_URL + '/' + employeeId + '?includes=' + (columns.join(',')))
            .done(function(resp) {
                // Hides the loader
                ml(false, LOADER);
                //
                if (resp.status) {
                    $('.jsEFirstName').val(resp.response.first_name).prop('disabled', true);
                    $('.jsELastName').val(resp.response.last_name).prop('disabled', true);
                    $('.jsEMiddleInitial').val(resp.response.middle_initial);
                    $('.jsESSN').val(resp.response.ssn);
                    $('.jsEDOB').val(resp.response.dob);
                    $('.jsEEmail').val(resp.response.email).prop('disabled', true);
                }
            });
    }

    /**
     * Set AJAX default setting
     */
    $.ajaxSetup({ headers: { "Key": API_KEY } });

    /**
     * 
     */
    $('.jsEDOB').datepicker({
        changeYear: true,
        changeMonth: true,
        yearRange: "-90:-13"
    });

    /**
     * Call
     */
    Get();
});