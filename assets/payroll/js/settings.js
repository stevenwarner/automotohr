/**
 * Process employee onboard for payroll
 *
 * @package Payroll Settings
 * @version 1.0
 * @author  AutomotoHR <www.automotohr.com>
 */
$(function Settings() {
    /**
     * Saves the XHR (AJAX) object
     * @type {null|object}
     */
    var xhr = null;

    $('#jsPayrollSettingsSpeed').change(function() {
        //
        $('.jsRequiredLimit').show(0);
        //
        if ($(this).val() === '4-day') {
            $('.jsRequiredLimit').hide(0);
        }
    });


    $('.jsPayrollSettingsSaveBtn').click(function(event) {
        //
        if (xhr !== null) {
            return;
        }
        //
        event.preventDefault();
        //
        var obj = {
            payment_speed: $('#jsPayrollSettingsSpeed').val(),
            fast_speed_limit: $('#jsPayrollSettingsLimit').val().replace(/[^0-9]/g, '')
        };
        //
        var _this = $(this);
        //                               
        if (obj.payment_speed == '2-day' && !obj.fast_speed_limit) {
            return alertify.alert(
                'Warning!',
                'Fast payment limit is required.',
                ECB
            );
        }
        //
        $(this).text('Please wait, while we are updating.');
        //
        xhr = $.post(
                baseURI + 'payroll/' + companyId + '/settings',
                obj
            )
            .success(function() {
                //
                xhr = null;
                alertify.alert(
                    'Success!',
                    'You have successfully update the payroll setting.',
                    ECB
                )
                //
                _this.html('<i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update');
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

    //
    $('#jsPayrollSettingsSpeed').select2();
});