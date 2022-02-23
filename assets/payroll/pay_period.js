$(function CompanyLocation() {
    //
    var LOADER = 'company_pay_period';
    //
    var datePickerConfig = {
        changeYear: true,
        changeMonth: true,
        minDate: -1
    };

    /**
     * Add a company location
     */
    $('.jsAddPayPeriod').click(function(event) {
        // Stop the default action
        event.preventDefault();
        //
        Modal({
            Id: "jsAddPayPeriodModal",
            Title: "Add A Company Location",
            Loader: "jsAddPayPeriodModalLoader",
            Body: '<div id="jsAddPayPeriodModalBody">' + (GetBody('Add')) + '</div>'
        }, async function() {
            //
            $('.jsAddPayPeriodAnchorDate').datepicker(datePickerConfig);
            $('.jsAddPayPeriodAnchorEndDate').datepicker(datePickerConfig);
            //
            ml(false, "jsAddPayPeriodModalLoader");
        });
    });

    /**
     * Save
     */
    $(document).on('click', '.jsModalSave', function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        //
        o.Frequency = $('.jsAddPayPeriodFrequency option:selected').val();
        //
        o.AnchorDate = $('.jsAddPayPeriodAnchorDate').val();
        //
        o.AnchorEndDate = $('.jsAddPayPeriodAnchorEndDate').val();
        //
        o.Day1 = $('.jsAddPayPeriodDay1').val().replace(/[^1-9]/g, '');
        //
        o.Day2 = $('.jsAddPayPeriodDay2').val().replace(/[^1-9]/g, '');
        //
        if (!o.AnchorDate) {
            return alertify.alert('Error!', "Please select an anchor date.");
        }
        //
        if (!o.AnchorEndDate) {
            return alertify.alert('Error!', "Please select an anchor end of pay period.");
        }
        //
        if ((o.Frequecy == 'Monthly' || o.Frequecy == 'Twice per month') && !o.Day1) {
            return alertify.alert('Error!', "Day 1 is required.");
        }
        //
        if ((o.Frequecy == 'Monthly' || o.Frequecy == 'Twice per month') && (o.Day1 < 1 || o.Day1 > 31)) {
            return alertify.alert('Error!', "Day 1 must be between 1 and 31.");
        }
        //
        if ((o.Frequecy == 'Monthly' || o.Frequecy == 'Twice per month') && !o.Day2 && o.Day2 > 1 && o.Day2 <= 31) {
            return alertify.alert('Error!', "Day 2 is required.");
        }
        //
        if ((o.Frequecy == 'Monthly' || o.Frequecy == 'Twice per month') && (o.Day2 < 1 || o.Day2 > 31)) {
            return alertify.alert('Error!', "Day 2 must be between 1 and 31.");
        }
        //
        ml(true, "jsAddPayPeriodModalLoader");
        //
        $.ajax({
            url: API_URL,
            method: "POST",
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify(o)
        }).done(function(resp) {
            //
            ml(false, "jsAddPayPeriodModalLoader");
            //
            if (!resp.status) {
                //
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br/>") : resp.response);
            } else {
                //
                return alertify.alert('Success!', resp.response, function() {
                    //
                    $('#jsAddPayPeriodModal .jsModalCancel').click();
                });
            }
        });
    });


    /**
     * Get Pay Period Body
     * 
     * @param {String} prefix
     * @returns
     */
    function GetBody(prefix) {
        //
        var html = '';
        //
        html += '<div class="container">';
        if (prefix == 'Edit') {
            html += '    <!-- Last Modified -->';
            html += '    <div class="row">';
            html += '        <div class="col-md-12">';
            html += '            <p class="csF16">Last Modified By <span class="csB7 jsModifiedBy"></span> On <span class="csB7 jsModifiedOn"></span></p>';
            html += '        </div>';
            html += '    </div><hr/>';

        }
        html += '    <!-- Country -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">Frequency <span class="csRequired"></span></label>';
        html += '            <p class="csF14 csInfo">The frequency that employees on this pay schedule are paid.</p>';
        html += '            <select class="form-control js' + (prefix) + 'PayPeriodFrequency">';
        html += '               <option value="Every week">Every week</option>';
        html += '               <option value="Every other week">Every other week</option>';
        html += '               <option value="Twice per month">Twice per month</option>';
        html += '               <option value="Monthly" selected>Monthly</option>';
        html += '            </select>';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- State -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">Anchor Pay Date <span class="csRequired"></span></label>';
        html += '            <p class="csF14 csInfo">The first date that employees on this pay schedule are paid.</p>';
        html += '            <input type="text" class="form-control js' + (prefix) + 'PayPeriodAnchorDate" placeholder="MM/DD/YYYY" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- State -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">Anchor End Of Pay Period <span class="csRequired"></span></label>';
        html += '            <p class="csF14 csInfo">The last date of the first pay period. This can be the same date as the anchor pay date.</p>';
        html += '            <input type="text" class="form-control js' + (prefix) + 'PayPeriodAnchorEndDate" placeholder="MM/DD/YYYY" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- State -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">Day 1 <span class="csRequired"></span></label>';
        html += '            <p class="csF14 csInfo">An integer between 1 and 31 indicating the first day of the month that employees are paid. This field is only relevant for pay schedules with the “Twice per month” and “Monthly” frequencies. It will be null for pay schedules with other frequencies.</p>';
        html += '            <input type="text" class="form-control js' + (prefix) + 'PayPeriodDay1" placeholder="15" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- State -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12">';
        html += '            <label class="csF16 csB7">Day 2 <span class="csRequired"></span></label>';
        html += '            <p class="csF14 csInfo">An integer between 1 and 31 indicating the second day of the month that employees are paid. This field is the second pay date for pay schedules with the “Twice per month” frequency. It will be null for pay schedules with other frequencies.</p>';
        html += '            <input type="text" class="form-control js' + (prefix) + 'PayPeriodDay2" placeholder="30" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <br/><!-- -->';
        html += '    <div class="row">';
        html += '        <div class="col-md-12 text-right">';
        html += '            <button class="btn btn-cancel csW csF16 csB7 jsModalCancel">';
        html += '                <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancel';
        html += '            </button>';
        if (prefix == 'Add') {
            html += '            <button class="btn btn-success csF16 csB7 jsModalSave">';
            html += '                <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save Pay Period';
            html += '            </button>';
        } else {
            html += '            <button class="btn btn-success csF16 csB7 jsModalUpdate">';
            html += '                <i class="fa fa-update" aria-hidden="true"></i>&nbsp;Update Pay Period';
            html += '            </button>';
            html += '            <input type="hidden" class="jsModalUpdateId">';
        }
        html += '        </div>';
        html += '    </div>';
        html += '</div>';

        //
        return html;
    }

    /**
     * Get Company Pay periods
     */
    function GetCompanyPayPeriods() {
        //
        ml(true, LOADER);
        //
        $.get(API_URL)
            .done(function(resp) {
                //
                var rows = '';
                //
                if (resp.response.length) {
                    //
                    resp.response.map(function(v) {
                        //
                        rows += '<tr>';
                        rows += '   <td class="vam csF14">' + (v.Frequency) + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.AnchorPayDate) + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.AnchorPayEndDate) + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.Day1 || "N/A") + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.Day2 || "N/A") + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.PayrollId ? "Payroll" : "Not On Payroll") + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.PayrollId ? v.PayrollId + "<br>" + v.Version : "N/A") + '</td>';
                        rows += '   <td class="vam csF14 text-right">' + (v.Name) + '<br/>' + (v.LastModifiedOn) + '</td>';
                        rows += '</tr>';
                    });

                } else {
                    //
                    rows += '<tr>';
                    rows += '   <td colspan="10">';
                    rows += '       <p class="alert alert-info text-center csF16 csB7">No locations found.</p>';
                    rows += '   </td>';
                    rows += '</tr>';
                }
                //
                $("#jsPayPeriodBody").html(rows);
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
    GetCompanyPayPeriods();
});