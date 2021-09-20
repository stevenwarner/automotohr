$(function Employee() {
    //
    var LOADER = 'employee';
    //
    var CompanyLocations = [];

    /**
     * 
     */
    $('.jsEAddBankAccount').click(function(event) {
        //
        event.preventDefault();
        //
        Model({
            Id: 'jsEAddBankAccountModal',
            Title: 'Add A Bank Account',
            Body: '<div class="jsEAddBankAccountModalBody"></div>',
            Loader: 'jsEAddBankAccountModalLoader'
        }, function() {
            //
            var html = '';
            html += '<div class="container">';
            html += '    <div class="row">';
            html += '        <div class="col-md-12 col-xs-12">';
            html += '            <label class="csF16 csB7">';
            html += '                Name&nbsp;<span class="csRequired"></span>';
            html += '            </label>';
            html += '            <input type="text" class="form-control jsEName" placeholder="BoA Checking Account" />';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-md-12 col-xs-12">';
            html += '            <label class="csF16 csB7">';
            html += '                Routing Number&nbsp;<span class="csRequired"></span>';
            html += '            </label>';
            html += '            <input type="text" class="form-control jsERoutingNumber" placeholder="266905059" />';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-md-12 col-xs-12">';
            html += '            <label class="csF16 csB7">';
            html += '                Account Number&nbsp;<span class="csRequired"></span>';
            html += '            </label>';
            html += '            <input type="text" class="form-control jsEAccountNumber" placeholder="5809431207" />';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-md-12 col-xs-12">';
            html += '            <label class="csF16 csB7">';
            html += '                Account Type&nbsp;<span class="csRequired"></span>';
            html += '            </label>';
            html += '            <select class="form-control jsEAccountType">';
            html += '                <option value="checking">Checking</option>';
            html += '                <option value="saving">Savings</option>';
            html += '            </select>';
            html += '        </div>';
            html += '    </div><br>';
            html += '    <div class="row">';
            html += '        <div class="col-md-12 col-xs-12 text-right jsSaveBankAccount">';
            html += '            <button class="btn btn-success csF16 csB7">';
            html += '               <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Add Bank Account';
            html += '            </button>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';
            //
            $('.jsEAddBankAccountModalBody').html(html);
            //
            ml(false, 'jsEAddBankAccountModalLoader');
        });
    });

    /**
     * Adds a new bank account to payroll
     */
    $(document).on('click', '.jsSaveBankAccount', function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.Name = $('.jsEName').val().trim();
        o.RoutingNumber = $('.jsERoutingNumber').val().replace(/[^0-9]/g, '').trim();
        o.AccountNumber = $('.jsEAccountNumber').val().replace(/[^0-9]/g, '').trim();
        o.AccountType = $('.jsEAccountType option:selected').val();
        // Validation
        if (!o.Name) {
            return alertify.alert('Error!', 'Name is required.');
        }
        if (!o.RoutingNumber) {
            return alertify.alert('Error!', 'Routing number is required.');
        }
        if (o.RoutingNumber.length !== 9) {
            return alertify.alert('Error!', 'Routing number must be of 9 digits.');
        }
        if (!o.AccountNumber) {
            return alertify.alert('Error!', 'Routing number is required.');
        }
        if (o.AccountNumber.length !== 9) {
            return alertify.alert('Error!', 'Account number must be of 9 digits.');
        }
        //
        ml(true, 'jsEAddBankAccountModalLoader');
        //
        $.ajax({
            method: "POST",
            url: API_URL + '/' + employeeId + '/bank_accounts',
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify(o)
        }).done(function(resp) {
            //
            ml(false, "jsEAddBankAccountModalLoader");
            //
            if (!resp.status) {
                //
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br/>") : resp.response);
            } else {
                //
                return alertify.alert('Success!', resp.response, function() {
                    //
                    $('#jsEAddBankAccountModal .jsModalCancel').click();
                    //
                    Get();
                });
            }
        });
    });

    /**
     * 
     */
    $(document).on('click', '.jsDeleteBankAccount', function(event) {
        //
        event.preventDefault();
        //
        var Id = $(this).closest('tr').data('id');
        //
        return alertify.confirm("Do you really want to delete this bank account?", function() {
            DeleteBankAccount(Id);
        });
    });

    /**
     * 
     * @param {String} accountId 
     */
    function DeleteBankAccount(accountId) {
        //
        ml(true, LOADER);
        //
        $.ajax({
            method: "DELETE",
            url: API_URL + '/' + employeeId + '/bank_accounts/' + accountId
        }).done(function(resp) {
            //
            ml(false, LOADER);
            //
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join('<br>') : resp.response);
            }
            //
            return alertify.alert('Success!', resp.response, function() {
                Get();
            });
        });
    }

    /**
     * Get data
     */
    function Get() {
        //
        ml(true, LOADER);
        //
        $.get(API_URL + '/' + employeeId + '/jobs')
            .done(function(resp) {
                // Hides the loader
                ml(false, LOADER);
                //
                var rows = '';
                //
                if (resp.response.length) {
                    //
                    resp.response.map(function(record) {
                        //
                        rows += '<tr data-id="' + (record.payroll_uuid) + '">';
                        rows += '   <td class="csF16 vam">' + (record.name) + '</td>';
                        rows += '   <td class="csF16 vam text-right">' + (record.routing_number) + '</td>';
                        rows += '   <td class="csF16 vam text-right">' + (record.account_number) + '</td>';
                        rows += '   <td class="csF16 vam text-right">' + (record.account_type) + '</td>';
                        rows += '   <td class="csF16 vam text-right">';
                        rows += '       <button class="btn btn-danger csF16 csB7 jsDeleteBankAccount"><i class="fa fa-times-circle csF16"></i>&nbsp;Delete</button>';
                        rows += '   </td>';
                        rows += '</tr>';
                    });
                } else {
                    //
                    rows += '<tr>';
                    rows += '   <td class="csF16 vam" colspan="5"><p class="alert alert-info text-center csF16 csB7">No records found</p></td>';
                    rows += '</tr>';
                }
                //
                $('.csCounter').text(resp.response.length);
                //
                $('#jsDataBody').html(rows);
            });
    }

    /**
     * Get Company Locations
     */
    function GetCompanyLocations() {
        //
        $.get(API_URL.replace(/employees/, 'company/locations'))
            .done(function(resp) {
                //
                CompanyLocations = resp.response;
                //
                Get();
            });
    }

    /**
     * Set AJAX default setting
     */
    $.ajaxSetup({ headers: { "Key": API_KEY } });

    /**
     * Calls
     */
    GetCompanyLocations();
});