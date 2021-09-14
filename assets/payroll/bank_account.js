$(function BankAccount() {

    //
    var LOADER = 'company_bank_account';
    //
    var oldData = {};

    /**
     * 
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
        if (JSON.stringify(oldData) === JSON.stringify(o)) {
            return alertify.alert('Error!', 'You haven\'t made any change to the bank account.');
        }
        //
        oldData.RoutingNumber = o.RoutingNumber;
        oldData.AccountNumber = o.AccountNumber;
        oldData.AccountType = o.AccountType;
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
                return alertify.alert('Error!', resp.response);
            }
            //
            return alertify.alert('Success!', resp.response);
        });
    });

    /**
     * Get Company Bank Account
     */
    function GetBankAccount() {
        //
        $.get(API_URL)
            .done(function(resp) {
                // Hides the loader
                ml(false, LOADER);
                //
                if (resp.response) {
                    //
                    $('.jsStatus').text(resp.response.VerifiedStatus.toUpperCase());
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
                    oldData = {
                        RoutingNumber: resp.response.RoutingNumber.toString(),
                        AccountNumber: resp.response.AccountNumber.toString(),
                        AccountType: resp.response.AccountType
                    };
                }
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