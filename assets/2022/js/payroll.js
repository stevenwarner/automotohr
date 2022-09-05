/**
 * Company Payroll Onbaord
 */
$(function Payroll() {
    //
    var LOADER = $('#jsPayrollLoader');
    /**
     * Start's the company onboard
     */
    $('.jsEnablePayroll').click(function(event) {
        //
        event.preventDefault();
        //
        return alertify.confirm(
            "This action will activate the payroll against your company.<br>Would like to continue?",
            EnableCompanyOnboard
        ).setHeader('Confirm!');
    });

    /**
     * 
     */
    function EnableCompanyOnboard() {
        //
        if (company.Ein.replace(/[^\d.]/, '').length !== 9) {
            return alertify.alert('Error!', 'Company EIN is not valid.<br> Company\'s EIN can be updated from "Company Profile".');
        }
        //
        Loader(true, "Please wait while we validate. This might take a few minutes.");
        //
        $.ajax({
            method: "POST",
            url: BaseURL("create_partner_company"),
            data: {
                sid: company.Id
            }
        }).done(function(resp) {
            //
            Loader(false);
            //
            if (resp.Errors) {
                return alertify.alert(
                    'Error!',
                    typeof resp.Errors === 'object' ? resp.Errors.join('<br>') : resp.Errors
                );
            }
            return alertify.alert(
                'Success!',
                resp.Message,
                function() {
                    window.location.reload();
                }
            );
        });
    }

    /**
     * 
     * @param {*}      type 
     * @param {String} message 
     */
    function Loader(type, message) {
        //
        if (type) {
            LOADER.show();
        } else {
            LOADER.hide();
        }
        //
        LOADER.find('.jsLoaderText').html(message || "Please wait while we process your request.")
    }

    /**
     * 
     * @param {String} url 
     * @returns 
     */
    function BaseURL(url) {
        return window.location.origin + '/' + (url || "");
    }
});