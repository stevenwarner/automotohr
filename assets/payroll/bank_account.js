$(function BankAccount() {

    //
    var LOADER = 'company_bank_account';
    //
    var oldData = {};

    /**
     * Verify the bank account
     */
    $('.jsVerifyBankAccount').click(function(event) {
        //
        event.preventDefault();
        //
        alertify.confirm(
            "Verify a company bank account by confirming the two micro-deposits sent to the bank account.<br> 1- $0.02<br>2- $0.42<br>Would you like to continue?",
            function() {
                //
                ml(true, LOADER, 'Please wait while we are adding a request to verify bank account.');
                //
                $.ajax({
                    url: API_URL + '/verify',
                    method: "PUT",
                    headers: { "Content-Type": "application/json" },
                    data: JSON.stringify({ uid: oldData.PayrollUUID })
                }).done(function(resp) {
                    //
                    ml(false, LOADER);
                    //
                    return alertify.alert("Success!", resp.response);
                });
            }
        );
    });

    /**
     * Refresh the payrol bank account status
     */
    $('.jsRefreshBankAccount').click(function(event) {
        //
        event.preventDefault();
        //
        ml(true, LOADER, 'Please wait while we are fetching the status....');
        //
        $.ajax({
            url: API_URL + '/refresh',
            method: "GET",
            headers: { "Content-Type": "application/json" },
        }).done(function() {
            //
            ml(false, LOADER);
            //
            GetBankAccount();
        });
    });

    /**
     * Show bank update history
     */
    $('.jsBankAccountHistory').click(function(event) {
        //
        event.preventDefault();
        //
        Modal({
            Id: 'jsBankAccountHistoryModal',
            Title: 'Bank Account History',
            Body: '<div id="jsBankAccountHistoryBody"></div>',
            Loader: "jsBankAccountHistoryModalLoader"
        }, function() {
            //
            $.get(API_URL + '/history')
                .done(function(resp) {
                    //
                    var rows = '';
                    //
                    if (resp.response.length) {
                        resp.response.map(function(obj) {
                            rows += '<tr>';
                            rows += '   <td class="csF16 vam">' + (obj.Name) + '</td>';
                            rows += '   <td class="csF16 vam text-right">' + (obj.RoutingNumber) + '</td>';
                            rows += '   <td class="csF16 vam text-right">' + (obj.AccountNumber) + '</td>';
                            rows += '   <td class="csF16 vam text-right">' + (obj.AccountType.toUpperCase()) + '</td>';
                            rows += '   <td class="csF16 vam text-right">' + (obj.VerifiedStatus.toUpperCase()) + '</td>';
                            rows += '</tr>';
                        });
                    } else {
                        rows += '<tr>';
                        rows += '    <td colspan="5"><p class="alert alert-info">No records found.</p></td>';
                        rows += '</tr>';
                    }
                    //
                    var html = '';
                    //
                    html += '<div class="container">';
                    html += '<table class="table table-striped">';
                    html += '    <caption></caption>';
                    html += '    <tbody>';
                    html += '        <tr>';
                    html += '            <td class="csBG2 csW vam csF16 csB7">';
                    html += '                Employee';
                    html += '            </td>';
                    html += '            <td class="csBG2 csW vam csF16 csB7 text-right">';
                    html += '                Routing Number';
                    html += '            </td>';
                    html += '            <td class="csBG2 csW vam csF16 csB7 text-right">';
                    html += '                Account Number';
                    html += '            </td>';
                    html += '            <td class="csBG2 csW vam csF16 csB7 text-right">';
                    html += '                Account Type';
                    html += '            </td>';
                    html += '            <td class="csBG2 csW vam csF16 csB7 text-right">';
                    html += '                Verified Status';
                    html += '            </td>';
                    html += '        </tr>';
                    html += rows;
                    html += '    </tbody>';
                    html += '</table>';
                    html += '</div>';
                    //
                    $('#jsBankAccountHistoryBody').html(html);
                    //
                    ml(false, 'jsBankAccountHistoryModalLoader');
                });
        });
    });

    /**
     * Update company bank account
     */
    $('.jsBankAccountUpdate').click(function(event) {
        // Stop the default action
        event.preventDefault();
        // Set a plain object
        var o = {};
        // Set Routing Number
        o.RoutingNumber = $('.jsBankAccountRoutingNumber').val().trim();
        // Set Account Number
        o.AccountNumber = $('.jsBankAccountNumber').val().trim();
        // Set Account type
        o.AccountType = $('.jsBankAccountType option:selected').val();
        // Validation
        if (o.RoutingNumber.replace(/[^0-9]/g, '').length !== 9) {
            alertify.alert('Error!', 'The provided routing number is invalid. The routing number must be of 9 digits.');
            return;
        }
        if (o.AccountNumber.replace(/[^0-9]/g, '').length !== 9) {
            alertify.alert('Error!', 'The provided account number is invalid. The account number must be of 9 digits.');
            return;
        }
        //
        ml(true, LOADER);
        //
        $.ajax({
            url: API_URL,
            method: "POST",
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify(o)
        }).done(function(resp) {
            //
            ml(false, LOADER);
            //
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join('<br>') : resp.response);
            }
            //
            oldData.RoutingNumber = o.RoutingNumber;
            oldData.AccountNumber = o.AccountNumber;
            oldData.AccountType = o.AccountType;
            //
            return alertify.alert('Success!', resp.response, function() {
                GetBankAccount();
            });
        });
    });

    /**
     * Get Company Bank Account
     */
    function GetBankAccount() {
        //
        ml(true, LOADER);
        //
        $.get(API_URL)
            .done(function(resp) {
                //
                if (resp.response) {
                    //
                    $('.jsStatus').text(resp.response.VerifiedStatus.toUpperCase().replace(/_/, ' '));
                    //
                    $('.jsBankAccountRoutingNumber').val(resp.response.RoutingNumber);
                    //
                    $('.jsBankAccountNumber').val(resp.response.AccountNumber);
                    //
                    $('.jsBankAccountType option[value="' + (resp.response.AccountType) + '"]').prop('selected', true);
                    //
                    $('.jsLastModified').removeClass('dn');
                    //
                    $('.jsLastModifiedPerson').text(resp.response.Name);
                    //
                    $('.jsLastModifiedTime').text(resp.response.LastModifiedOn);
                    //
                    if (resp.response.PayrollUUID) {
                        //
                        $('.jsRefreshBankAccount').removeClass('dn');
                    }
                    //
                    if (resp.response.PayrollUUID && resp.response.VerifiedStatus == 'ready_for_verification') {
                        //
                        $('.jsVerifyBankAccount').removeClass('dn');
                    }
                    //
                    oldData = resp.response;
                }
                // Hides the loader
                ml(false, LOADER);
            });
    }

    /**
     * Set AJAX default setting
     */
    $.ajaxSetup({ headers: { "Key": API_KEY } });

    /**
     * Call
     */
    GetBankAccount();
});