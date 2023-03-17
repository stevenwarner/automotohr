$(function companyOnboard() {
    //
    let companyId = 0;
    //
    let xhr = null;

    /**
     * Captures admin event
     */
    $('.jsManageGustoAdmins').click(function (event) {
        //
        event.preventDefault();
        //
        companyId = $(this).data('cid');
        //
        Modal({
            Id: 'jsManageGustoAdminsModal',
            Loader: 'jsManageGustoAdminsModalLoader',
            Body: '<div id="jsManageGustoAdminsModalBody"></div>',
            Title: 'Manage Admins for Payroll'
        }, fetchAdmins);
    });

    /**
     * 
     */
    $(document).on('click', '.jsAddAdmin', function (event) {
        //
        event.preventDefault();
        //
        $('.jsSection').removeClass('dn');
        $('.jsSection[data-key="view"]').addClass('dn');
    });

    /**
     * 
     */
    $(document).on('click', '.jsAdminView', function (event) {
        //
        event.preventDefault();
        //
        $('.jsSection').removeClass('dn');
        $('.jsSection[data-key="add"]').addClass('dn');
    });

    /**
     * 
     */
    $(document).on('click', '.jsAddAdminSaveBtn', function (event) {
        //
        event.preventDefault();
        //
        var obj = {
            firstName: $('#jsAdminFirstName').val().trim(),
            lastName: $('#jsAdminLastName').val().trim(),
            emailAddress: $('#jsAdminEmailAddress').val().trim(),
            companyId: companyId
        };
        //
        if (!obj.firstName) {
            return alertify.alert('Warning!', 'First name is required.', function () { });
        }
        //
        if (!obj.lastName) {
            return alertify.alert('Warning!', 'Last name is required.', function () { });
        }
        //
        if (!obj.emailAddress) {
            return alertify.alert('Warning!', 'Email address is required.', function () { });
        }
        //
        if (!obj.emailAddress.verifyEmail()) {
            return alertify.alert('Warning!', 'Email address is malformed.', function () { });
        }
        //
        ml(true, 'jsManageGustoAdminsModalLoader');
        //
        xhr = $.post(
            baseURI + 'payroll/admin/' + companyId,
            obj
        ).success(function (response) {
            //
            xhr = null;
            //
            ml(false, 'jsManageGustoAdminsModalLoader');
            //
            if (response.error) {
                return alertify.alert('Error!', response.error, function () { });
            }
            return alertify.alert('Success!', response.success, function () {
                //
                $('#jsManageGustoAdminsModal .jsModalCancel').trigger('click');
                $('.jsManageGustoAdmins').trigger('click')
            });
        })
            .fail(function () {
                //
                xhr = null;
                //
                ml(false, 'jsManageGustoAdminsModalLoader');
            });;
    });

    function fetchAdmins() {
        //
        if (xhr !== null) {
            xhr.abort();
        }
        //
        xhr = $.get(
            baseURI + 'get_payroll_admins/' + companyId
        )
            .success(function (response) {
                //
                xhr = null;
                //
                $('#jsManageGustoAdminsModalBody').html(response.view)
                //
                ml(false, 'jsManageGustoAdminsModalLoader');
            })
            .fail(function () {
                xhr = null;
                $('#jsManageGustoAdminsModalBody').html('<strong class="alert alert-danger text-center">Something went wrong. Please try again in few seconds.</strong>')
                ml(false, 'jsManageGustoAdminsModalLoader');
            });
    }
});