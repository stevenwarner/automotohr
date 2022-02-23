/**
 * Set AJAX default setting
 */
$.ajaxSetup({ headers: { "Key": API_KEY } });

//
$(function jsGarnishmentDataBody() {
    //
    var LOADER = 'employee_garnishment';
    //

    /**
     * Adds a new garnishment
     */
    $('.jsGarnishentAdd').click(function(event) {
        //
        event.preventDefault();
        //
        Model({
            Id: "jsGarnishmentModal",
            Title: "Add Garnishment for " + employeeNameWithRole,
            Loader: "jsGarnishmentModalLoader",
            Body: '<div class="jsGarnishmentModalBody">' + (GetBody('add')) + '</div>'
        }, function() {
            //
            ml(false, 'jsGarnishmentModalLoader');
        });
    });

    /**
     * Add garnishment process
     */
    $(document).on('click', '.jsGarnishmentSave', function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.active = $('.jsGarnishmentActive').prop('checked');
        o.amount = $('.jsGarnishmentAmount').val().replace(/[^\d.]/g, '');
        o.description = $('.jsGarnishmentDescription').val().trim();
        o.court_ordered = $('.jsGarnishmentCourtOrdered').prop('checked');
        o.times = $('.jsGarnishmentTimes').val().replace(/[^\d.]/g, '');
        o.recurring = $('.jsGarnishmentRecurring').prop('checked');
        o.annual_maximum = $('.jsGarnishmentAnnualMaximum').val().replace(/[^\d.]/g, '');
        o.pay_period_maximum = $('.jsGarnishmentPayPeriodMaximum').val().replace(/[^\d.]/g, '');
        o.deduct_as_percentage = $('.jsGarnishmentDeductAsPercentage').prop('checked');
        // Validation
        if (o.amount.length === 0) {
            return alertify.alert('Error!', 'Amount is required.');
        } else if (o.description.length === 0) {
            return alertify.alert('Error!', 'Description is required.');
        }
        //
        ml(true, 'jsGarnishmentModalLoader');
        //
        $.ajax({
            method: "POST",
            url: API_URL + '/' + employeeId + '/garnishments',
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify(o)
        }).done(function(resp) {
            //
            ml(false, 'jsGarnishmentModalLoader');
            //
            if (!resp.status) {
                //
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br/>") : resp.response);
            } else {
                //
                return alertify.alert('Success!', resp.response, function() {
                    //
                    $('#jsGarnishmentModal .jsModalCancel').click();
                    //
                    Get();
                });
            }
        });
    });

    /**
     * Get all employee garnishments
     */
    function Get() {
        //
        ml(true, LOADER);
        //
        $.get(API_URL + '/' + employeeId + '/garnishments')
            .done(function(resp) {
                //
                var rows = '';
                //
                if (resp.status) {
                    //
                    resp.response.map(function(garnishment) {
                        //
                        rows += '<tr data-id="' + (garnishment.garnishmentId) + '">';
                        rows += '   <td class="vam">' + (garnishment.description) + '</td>';
                        rows += '   <td class="vam text-right">' + (garnishment.deduct_as_percentage == 'true' ? '%' : '$') + '' + (parseFloat(garnishment.amount).toFixed(2)) + '</td>';
                        rows += '   <td class="vam text-right">' + (garnishment.court_ordered == 1 ? "Yes" : "No") + '</td>';
                        rows += '   <td class="vam text-right">' + (garnishment.times) + '</td>';
                        rows += '   <td class="vam text-right">' + (garnishment.recurring == 1 ? "Yes" : "No") + '</td>';
                        rows += '   <td class="vam text-right">' + (garnishment.deduct_as_percentage == 'true' ? "Yes" : "No") + '</td>';
                        rows += '   <td class="vam text-right">' + (garnishment.active == 1 ? "Yes" : "No") + '</td>';
                        rows += '   <td class="vam text-right">' + (garnishment.name) + '</td>';
                        rows += '   <td class="vam text-right">';
                        rows += '       <button class="btn btn-warning csF16 csB7"><i class="fa fa-edit csF16" aria-hidden="true"></i>&nbsp;Edit</button>';
                        rows += '   </td>';
                        rows += '</tr>';
                    });
                    //
                    $('.jsGarnishmentCount').text(resp.response.length);
                } else {
                    rows += '<tr>';
                    rows += '   <td colspan="' + ($('#jsGarnishmentDataBody th').length) + '">';
                    rows += '       <p class="alert alert-info text-center csF16 csB7">No records found</p>';
                    rows += '   </td>';
                    rows += '</tr>';
                }
                //
                ml(false, LOADER);
                //
                $('#jsGarnishmentDataBody').html(rows);
            });
    }

    /**
     * Generate garnishment modal content
     * @param {String} prefix
     * @returns
     */
    function GetBody(prefix) {
        //
        var html = '';
        html += '<div class="container">';
        html += '    <!--  -->';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Description&nbsp;<span class="csRequired"></span></label>';
        html += '            <p class="csF14">The description of the garnishment.</p>';
        html += '            <input type="text" class="form-control jsGarnishmentDescription" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <!--  -->';
        html += '    <br />';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Amount&nbsp;<span class="csRequired"></span></label>';
        html += '            <p class="csF14">The amount of the garnishment. Either a percentage or a fixed dollar amount. Represented as a float, e.g. "8.00".</p>';
        html += '            <input type="text" class="form-control jsGarnishmentAmount" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <!--  -->';
        html += '    <br />';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Annual Maximum</label>';
        html += '            <p class="csF14">The maximum deduction per annum. A 0 value indicates no maximum. Represented as a float, e.g. "200.00".</p>';
        html += '            <input type="text" class="form-control jsGarnishmentAnnualMaximum" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <!--  -->';
        html += '    <br />';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Pay Period Maximum</label>';
        html += '            <p class="csF14">The maximum deduction per pay period. A 0 value indicates no maximum. Represented as a float, e.g. "16.00".</p>';
        html += '            <input type="text" class="form-control jsGarnishmentPayPeriodMaximum" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <!--  -->';
        html += '    <br />';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Times</label>';
        html += '            <p class="csF14">The number of times to apply the garnisment. Ignored if recurring is true.</p>';
        html += '            <input type="text" class="form-control jsGarnishmentTimes" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <!--  -->';
        html += '    <br />';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7 control control--checkbox">Court Ordered&nbsp;<span class="csRequired"></span>';
        html += '                <input type="checkbox" class="jsGarnishmentCourtOrdered" />';
        html += '                <div class="control__indicator"></div>';
        html += '            </label>';
        html += '            <p class="csF14">Whether the garnishment is court ordered.</p>';
        html += '        </div>';
        html += '    </div>';
        html += '    <!--  -->';
        html += '    <br />';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7 control control--checkbox">Recurring';
        html += '                <input type="checkbox" class="jsGarnishmentRecurring" />';
        html += '                <div class="control__indicator"></div>';
        html += '            </label>';
        html += '            <p class="csF14">Whether the garnishment should recur indefinitely.</p>';
        html += '        </div>';
        html += '    </div>';
        html += '    <!--  -->';
        html += '    <br />';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7 control control--checkbox">Deduct As Percentage';
        html += '                <input type="checkbox" class="jsGarnishmentDeductAsPercentage" />';
        html += '                <div class="control__indicator"></div>';
        html += '            </label>';
        html += '            <p class="csF14">Whether the amount should be treated as a percentage to be deducted per pay period.</p>';
        html += '        </div>';
        html += '    </div>';
        html += '    <!--  -->';
        html += '    <br />';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7 control control--checkbox">Active';
        html += '                <input type="checkbox" class="jsGarnishmentActive" />';
        html += '                <div class="control__indicator"></div>';
        html += '            </label>';
        html += '            <p class="csF14">Whether or not this garnishment is currently active.</p>';
        html += '        </div>';
        html += '    </div>';
        html += '    <!--  -->';
        html += '    <br />';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12 text-right">';
        html += '            <button class="btn btn-success csF16 csB7 jsGarnishmentSave">';
        html += '                <i class="fa fa-' + (prefix === 'add' ? "save" : "edit") + '" aria-hidden="true"></i>&nbsp;' + (prefix === 'add' ? "Save" : "Update") + '';
        html += '            </button>';
        html += '        </div>';
        html += '    </div>';
        html += '</div>';
        //
        return html;
    }

    //
    Get();

});