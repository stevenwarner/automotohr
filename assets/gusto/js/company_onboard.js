$(function companyOnboard() {
    //
    let companyId = 0;
    //
    let xhr = null;

    if (typeof Modal === 'undefined') {
        Modal = Model;
    }

    /**
     * 
     */
    $(document).on('click', '.jsExpandRow', function (event) {
        //
        event.preventDefault();
        //
        $('.jsExpandRowArea[data-id="' + ($(this).data('id')) + '"]').toggleClass('dn');
    });

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
            if (response.errors) {
                return alertify.alert('Error!', response.errors.join('<br />'), function () { });
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

    /**
     * Captures admin event
     */
    $('.jsManageGustoSignatories').click(function (event) {
        //
        event.preventDefault();
        //
        companyId = $(this).data('cid');
        //
        Modal({
            Id: 'jsManageGustoSignatoriesModal',
            Loader: 'jsManageGustoSignatoriesModalLoader',
            Body: '<div id="jsManageGustoSignatoriesModalBody"></div>',
            Title: 'Manage Signatories for Payroll'
        }, fetchSignatories);
    });
    /**
     * 
     */
    $(document).on('click', '.jsAddSignatory', function (event) {
        //
        event.preventDefault();
        //
        $('.jsSection').removeClass('dn');
        $('.jsSection[data-key="edit"]').addClass('dn');
        $('.jsSection[data-key="view"]').addClass('dn');
    });
    /**
     * 
     */
    $(document).on('click', '.jsSignatoriesView', function (event) {
        //
        event.preventDefault();
        //
        $('.jsSection').removeClass('dn');
        $('.jsSection[data-key="add"]').addClass('dn');
        $('.jsSection[data-key="edit"]').addClass('dn');
    });
    /**
     * 
     */
    $(document).on('click', '.jsEditSignatory', function (event) {
        //
        event.preventDefault();
        //
        $('.jsSection').removeClass('dn');
        $('.jsSection[data-key="add"]').addClass('dn');
        $('.jsSection[data-key="view"]').addClass('dn');
    });
    /**
     * 
     */
    $(document).on('click', '.jsDeleteSignatory', function (event) {
        //
        event.preventDefault();
        //
        const signatoryId = $(this).data('id');
        //
        return alertify.confirm(
            'Are you sure you want to delete this signatory?',
            function () {
                deleteSignatory(signatoryId);
            }
        );
    });
    /**
    * 
    */
    $(document).on('click', '.jsAddSignatorySaveBtn', function (event) {
        //
        event.preventDefault();
        //
        var obj = {
            ssn: $('#jsSignatoryAddSsn').val().trim(),
            firstName: $('#jsSignatoryAddFirstName').val().trim(),
            middleInitial: $('#jsSignatoryAddMiddleInitial').val().trim(),
            lastName: $('#jsSignatoryAddLastName').val().trim(),
            email: $('#jsSignatoryAddEmail').val().trim(),
            title: $('#jsSignatoryAddTitle').val().trim(),
            birthday: $('#jsSignatoryAddBirthDay').val().trim(),
            phone: $('#jsSignatoryAddPhone').val().trim(),
            street1: $('#jsSignatoryAddStreet1').val().trim(),
            street2: $('#jsSignatoryAddStreet2').val().trim(),
            state: $('#jsSignatoryAddState').val().trim(),
            city: $('#jsSignatoryAddCity').val().trim(),
            zip: $('#jsSignatoryAddZip').val().trim(),
            companyId: companyId
        };
        //
        if (!obj.ssn) {
            return alertify.alert('Warning!', 'Social Security Number (SSN) is required.', function () { });
        }
        //
        if (!obj.firstName) {
            return alertify.alert('Warning!', 'First name is required.', function () { });
        }
        //
        if (!obj.lastName) {
            return alertify.alert('Warning!', 'Last name is required.', function () { });
        }
        //
        if (!obj.email) {
            return alertify.alert('Warning!', 'Email address is required.', function () { });
        }
        //
        if (!obj.email.verifyEmail()) {
            return alertify.alert('Warning!', 'Email address is malformed.', function () { });
        }
        //
        if (!obj.title) {
            return alertify.alert('Warning!', 'Title is required.', function () { });
        }
        //
        if (!obj.birthday) {
            return alertify.alert('Warning!', 'Birthday is required.', function () { });
        }
        //
        if (!obj.street1) {
            return alertify.alert('Warning!', 'Street 1 is required.', function () { });
        }
        //
        if (!obj.state) {
            return alertify.alert('Warning!', 'State is required.', function () { });
        }
        //
        if (!obj.city) {
            return alertify.alert('Warning!', 'City is required.', function () { });
        }
        //
        if (!obj.zip) {
            return alertify.alert('Warning!', 'Zip is required.', function () { });
        }
        //
        ml(true, 'jsManageGustoSignatoriesModalLoader');
        //
        xhr = $.post(
            baseURI + 'payroll/signatory/' + companyId,
            obj
        ).success(function (response) {
            //
            xhr = null;
            //
            ml(false, 'jsManageGustoSignatoriesModalLoader');
            //
            if (response.errors) {
                return alertify.alert('Error!', response.errors.join('<br />'), function () { });
            }
            return alertify.alert('Success!', response.success, function () {
                //
                $('#jsManageGustoSignatoriesModal .jsModalCancel').trigger('click');
                $('.jsManageGustoSignatories').trigger('click')
            });
        })
            .fail(function () {
                //
                xhr = null;
                //
                ml(false, 'jsManageGustoSignatoriesModalLoader');
            });;
    });
    /**
    * 
    */
    $(document).on('click', '.jsEditSignatorySaveBtn', function (event) {
        //
        event.preventDefault();
        //
        var obj = {
            ssn: $('#jsSignatoryEditSsn').val().trim(),
            firstName: $('#jsSignatoryEditFirstName').val().trim(),
            middleInitial: $('#jsSignatoryEditMiddleInitial').val().trim(),
            lastName: $('#jsSignatoryEditLastName').val().trim(),
            title: $('#jsSignatoryEditTitle').val().trim(),
            birthday: $('#jsSignatoryEditBirthDay').val().trim(),
            phone: $('#jsSignatoryEditPhone').val().trim(),
            street1: $('#jsSignatoryEditStreet1').val().trim(),
            street2: $('#jsSignatoryEditStreet2').val().trim(),
            state: $('#jsSignatoryEditState').val().trim(),
            city: $('#jsSignatoryEditCity').val().trim(),
            zip: $('#jsSignatoryEditZip').val().trim(),
            id: $('#jsSignatoryEditId').val().trim(),
            companyId: companyId
        };
        //
        if (!obj.ssn) {
            return alertify.alert('Warning!', 'Social Security Number (SSN) is required.', function () { });
        }
        //
        if (!obj.firstName) {
            return alertify.alert('Warning!', 'First name is required.', function () { });
        }
        //
        if (!obj.lastName) {
            return alertify.alert('Warning!', 'Last name is required.', function () { });
        }
        //
        if (!obj.title) {
            return alertify.alert('Warning!', 'Title is required.', function () { });
        }
        //
        if (!obj.birthday) {
            return alertify.alert('Warning!', 'Birthday is required.', function () { });
        }
        //
        if (!obj.street1) {
            return alertify.alert('Warning!', 'Street 1 is required.', function () { });
        }
        //
        if (!obj.state) {
            return alertify.alert('Warning!', 'State is required.', function () { });
        }
        //
        if (!obj.city) {
            return alertify.alert('Warning!', 'City is required.', function () { });
        }
        //
        if (!obj.zip) {
            return alertify.alert('Warning!', 'Zip is required.', function () { });
        }
        //
        ml(true, 'jsManageGustoSignatoriesModalLoader');
        //
        xhr = $.ajax({
            method: 'PUT',
            url: baseURI + 'payroll/signatory/' + companyId,
            data: obj
        }).success(function (response) {
            //
            xhr = null;
            //
            ml(false, 'jsManageGustoSignatoriesModalLoader');
            //
            if (response.errors) {
                return alertify.alert('Error!', response.errors.join('<br />'), function () { });
            }
            return alertify.alert('Success!', response.success, function () {
                //
                $('#jsManageGustoSignatoriesModal .jsModalCancel').trigger('click');
                $('.jsManageGustoSignatories').trigger('click');
            });
        })
            .fail(function () {
                //
                xhr = null;
                //
                ml(false, 'jsManageGustoSignatoriesModalLoader');
            });;
    });

    /**
     * Fetch admins
     */
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

    /**
     * Fetch signatories
     */
    function fetchSignatories() {
        //
        if (xhr !== null) {
            xhr.abort();
        }
        //
        xhr = $.get(
            baseURI + 'payroll/signatories/' + companyId
        )
            .success(function (response) {
                //
                xhr = null;
                //
                $('#jsManageGustoSignatoriesModalBody').html(response.view);
                //
                $('#jsSignatoryAddBirthDay').datepicker({
                    changeYear: true,
                    changeMonth: true
                });
                //
                $('#jsSignatoryEditBirthDay').datepicker({
                    changeYear: true,
                    changeMonth: true
                });
                //
                ml(false, 'jsManageGustoSignatoriesModalLoader');
            })
            .fail(function () {
                xhr = null;
                $('#jsManageGustoSignatoriesModalBody').html('<strong class="alert alert-danger text-center">Something went wrong. Please try again in few seconds.</strong>')
                ml(false, 'jsManageGustoSignatoriesModalLoader');
            });
    }

    /**
     * Delete signatory
     */
    function deleteSignatory(signatoryId) {
        //
        if (xhr !== null) {
            xhr.abort();
        }
        //
        xhr = $.ajax({
            method: 'DELETE',
            url: baseURI + 'payroll/signatories/' + companyId + '/' + signatoryId
        })
            .success(function (response) {
                //
                xhr = null;
                $('#jsManageGustoSignatoriesModal .jsModalCancel').trigger('click');
                $('.jsManageGustoSignatories').trigger('click')
            })
            .fail(function () {
                xhr = null;
                ml(false, 'jsManageGustoSignatoriesModalLoader');
            });
    }

    //
    $(document).on('change', '#jsadminemployees', function (event) {
        //
        event.preventDefault();
        //

        let employeeData = $(this).val();
        if (employeeData != '') {
            const employeeDataArray = employeeData.split("#");
            $('#jsAdminFirstName').val(employeeDataArray[0]);
            $('#jsAdminLastName').val(employeeDataArray[1]);
            $('#jsAdminEmailAddress').val(employeeDataArray[2]);
        }

    });

    //
    $(document).on('change', '#adminesignatories', function (event) {
        //
        event.preventDefault();
        let employeeData = $(this).val();
        //
        if (employeeData != '') {
            const employeeDataArray = employeeData.split("#");

            $('#jsSignatoryAddFirstName').val(employeeDataArray[0]);
            $('#jsSignatoryAddLastName').val(employeeDataArray[1]);
            $('#jsSignatoryAddEmail').val(employeeDataArray[2]);
            $('#jsSignatoryAddMiddleInitial').val(employeeDataArray[3]);
            $('#jsSignatoryAddSsn').val(employeeDataArray[4]);
            $('#jsSignatoryAddTitle').val(employeeDataArray[5]);
            $('#jsSignatoryAddPhone').val(employeeDataArray[7]);
            $('#jsSignatoryAddBirthDay').val(employeeDataArray[6]);
            $('#jsSignatoryAddStreet1').val(employeeDataArray[8]);
            $('#jsSignatoryAddStreet2').val(employeeDataArray[9]);
            $('#jsSignatoryAddState').val(employeeDataArray[12]);
            $('#jsSignatoryAddCity').val(employeeDataArray[11]);
            $('#jsSignatoryAddZip').val(employeeDataArray[10]);
        }

    });



    //
    $('.jsManagePayments').click(function (event) {
        //
        event.preventDefault();
        //
        companyId = $(this).data('cid');
        //
        Modal({
            Id: 'jsManagePaymentsModal',
            Loader: 'jsManageGustoSignatoriesModalLoader',
            Body: '<div id="jsManagePaymentsBody"></div>',
            Title: 'Manage Payment'
        }, fetchPayments);
    });


    //

    function fetchPayments() {
        //
        if (xhr !== null) {
            xhr.abort();
        }
        //
        xhr = $.get(
            baseURI + 'payroll/gusto/managepayment/' + companyId
        )
            .success(function (response) {
                //
                xhr = null;
                //
                $('#jsManagePaymentsBody').html(response.view);
                //
                ml(false, 'jsManageGustoSignatoriesModalLoader');
            })
            .fail(function () {
                xhr = null;
                $('#jsManagePaymentsBody').html('<strong class="alert alert-danger text-center">Something went wrong. Please try again in few seconds.</strong>')
                ml(false, 'jsManageGustoSignatoriesModalLoader');
            });
    }



});