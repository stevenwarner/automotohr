$(function CompanyOnboard() {
    //
    var LOADER = 'company_onboard';

    /**
     * 
     */
    $('.jsSaveBTN').click(function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.company_name = $('#jsCompanyName').val().trim();
        o.legal_name = $('#jsTradeName').val().trim();
        o.ein = $('#jsEin').val().replace(/[^\d]/g, '');
        o.first_name = $('#jsFirstName').val().trim();
        o.last_name = $('#jsLastName').val().trim();
        o.email = $('#jsEmail').val().trim();
        o.phone = $('#jsPhone').val().replace(/[^\d]/g, '');
        // Validation
        if (!o.company_name) {
            return alertify.alert('Error!', 'Company name is required.');
        }
        if (!o.legal_name) {
            return alertify.alert('Error!', 'Company trade name is required.');
        }
        if (!o.ein) {
            return alertify.alert('Error!', 'Company EIN is required.');
        }
        if (o.ein.length !== 9) {
            return alertify.alert('Error!', 'Company EIN must be of 9 digits.');
        }
        if (!o.first_name) {
            return alertify.alert('Error!', 'Admin first name is required.');
        }
        if (!o.last_name) {
            return alertify.alert('Error!', 'Admin last name is required.');
        }
        if (!o.email) {
            return alertify.alert('Error!', 'Admin email address is required.');
        }
        if (o.phone && o.phone.length !== 10) {
            return alertify.alert('Error!', 'Admin phone must be of 10 digits.');
        }
        //
        ml(true, LOADER);
        //
        $.ajax({
            method: "POST",
            url: API_URL,
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify(o)
        }).done(function(resp) {
            //
            ml(false, LOADER);
            //
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br>") : resp.response);
            }
            //
            return alertify.alert('Success!', resp.response, function() {
                //
                // window.location.reload();
            });
        });
    });


    /**
     * Set AJAX default setting
     */
    $.ajaxSetup({ headers: { "Key": API_KEY } });


    //
    ml(false, LOADER);
});