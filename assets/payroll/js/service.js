/**
 * Process employee onboard for payroll
 *
 * @package Accept service terms
 * @version 1.0
 * @author  AutomotoHR <www.automotohr.com>
 */
$(function Service() {

    var xhr = null;


    $('.jsPayrollAcceptTerms').click(function(event) {
        //
        if (xhr !== null) {
            return;
        }
        //
        event.preventDefault();
        //
        var _this = $(this);
        //
        $(this).text('Please wait.....');
        //
        xhr = $.post(
                baseURI + 'payroll/' + companyId + '/service', {
                    a: 'a'
                }
            )
            .success(function(response) {
                //
                xhr = null;
                //
                $(this).html('<i class="fa fa-save" aria-hidden="true"></i>&nbsp;Accept Service Terms');
                //
                if (!response.status) {
                    return alertify.alert('Error!', response.response, ECB);
                }
                return alertify.alert('Success!', 'You have successfully accepted the terms', function() {
                    window.location.reload();
                });
            })
            .fail(ErrorHandler);
    });
    /**
     * Handles XHR errors
     * @param {object} error 
     */
    function ErrorHandler(error) {
        //
        xhr = null;
        //
        alertify.alert(
            "Error!",
            "The system failed to process your request. (" + (error.status) + ")",
            ECB
        );
    }

    /**
     * Alertify callback error
     * @returns
     */
    function ECB() {}
});