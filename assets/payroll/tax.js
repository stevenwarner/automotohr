$(function BankAccount() {
    //
    var LOADER = 'company_tax';

    /**
     * Show tax updation history
     */
    $('.jsTaxHistory').click(function(event) {
        //
        event.preventDefault();
        //
        Modal({
            Id: 'jsTaxHistoryModal',
            Title: 'Tax History',
            Body: '<div id="jsTaxHistoryBody"></div>',
            Loader: "jsTaxHistoryModalLoader"
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
                            rows += '   <td class="csF16 vam text-right">' + (obj.LegalName) + '</td>';
                            rows += '   <td class="csF16 vam text-right">' + (obj.EIN) + '</td>';
                            rows += '   <td class="csF16 vam text-right">' + (obj.TaxPayerType) + '</td>';
                            rows += '   <td class="csF16 vam text-right">' + (obj.FillingForm) + '</td>';
                            rows += '   <td class="csF16 vam text-right">' + (obj.Scorp ? "Yes" : "No") + '</td>';
                            rows += '   <td class="csF16 vam text-right">' + (obj.Version) + '</td>';
                            rows += '   <td class="csF16 vam text-right csB7 ' + (obj.VerifiedStatus ? 'csFC1' : 'csFC3') + '">' + (obj.VerifiedStatus ? "VERIFIED" : "UNVERIFIED") + '</td>';
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
                    html += '                Legal Name';
                    html += '            </td>';
                    html += '            <td class="csBG2 csW vam csF16 csB7 text-right">';
                    html += '                EIN';
                    html += '            </td>';
                    html += '            <td class="csBG2 csW vam csF16 csB7 text-right">';
                    html += '                Tax Payer Type';
                    html += '            </td>';
                    html += '            <td class="csBG2 csW vam csF16 csB7 text-right">';
                    html += '                Filling Form';
                    html += '            </td>';
                    html += '            <td class="csBG2 csW vam csF16 csB7 text-right">';
                    html += '                Taxable As Scorp';
                    html += '            </td>';
                    html += '            <td class="csBG2 csW vam csF16 csB7 text-right">';
                    html += '                Version';
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
                    $('#jsTaxHistoryBody').html(html);
                    //
                    ml(false, 'jsTaxHistoryModalLoader');
                });
        });
    });

    /**
     * Update company tax details
     */
    $('.jsTaxUpdateBtn').click(function(event) {
        // Stop the default action
        event.preventDefault();
        // Set a plain object
        var o = {};
        // Set Legal Name
        o.LegalName = $('.jsTaxLegalName').val().trim();
        // Set EIN number
        o.EIN = $('.jsTaxEIN').val().trim();
        // Set Payer Type
        o.TaxPayerType = $('.jsTaxPayerType option:selected').val();
        // Set Filling Type
        o.FillingForm = $('.jsTaxFillingForm option:selected').val();
        // Set Scorp
        o.Scorp = $('.jsTaxScorp').prop('checked') ? 1 : 0;
        // Validation
        if (o.LegalName.length <= 3) {
            alertify.alert('Error!', 'The legal name must be of minimum 3 characters.');
            return;
        }
        //
        if (o.EIN.replace(/[^0-9]/g, '').length !== 9) {
            alertify.alert('Error!', 'The provided EIN is invalid. The EIN must be of 9 digits.');
            return;
        }
        //
        if (o.TaxPayerType == '0') {
            alertify.alert('Error!', 'Please, select a tax payer type.');
            return;
        }
        //
        if (o.FillingForm == '0') {
            alertify.alert('Error!', 'Please, select the filling form.');
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
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br>") : resp.response);
            }
            //
            return alertify.alert('Success!', resp.response, function() {
                GetTax();
            });
        });
    });

    /**
     * Refresh Status
     */
    $('.jsTaxRefresh').click(function(event) {
        // Stop the default action
        event.preventDefault();
        //
        ml(true, LOADER, "Please wait, while we are refreshing status.");
        //
        $.ajax({
            url: API_URL + '/refresh',
            method: "get",
            headers: { "Content-Type": "application/json" },
        }).done(function() {
            //
            ml(false, LOADER);
            //
            GetTax();
        });
    });

    /**
     * Get Company Bank Account
     */
    function GetTax() {
        //
        ml(true, LOADER);
        //
        $.get(API_URL)
            .done(function(resp) {
                // Hides the loader
                ml(false, LOADER);
                //
                if (resp.response) {
                    //
                    $('.jsStatus').text(resp.response.VerifiedStatus ? "VERIFIED" : "UNVERIFIED");
                    $('.jsStatus').removeClass("csFC3").removeClass("csFC1");
                    $('.jsStatus').addClass(resp.response.VerifiedStatus ? "csFC1" : "csFC3");
                    //
                    $('.jsTaxLegalName').val(resp.response.LegalName);
                    //
                    $('.jsTaxEIN').val(resp.response.EIN);
                    //
                    $('.jsTaxPayerType option[value="' + (resp.response.TaxPayerType) + '"]').prop("selected", true);
                    //
                    $('.jsTaxFillingForm option[value="' + (resp.response.FillingForm) + '"]').prop("selected", true);
                    //
                    $('.jsTaxScorp').prop("checked", resp.response.Scorp);
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
    GetTax();
});