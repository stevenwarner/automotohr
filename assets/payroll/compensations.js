$(function Compensations() {
    //
    var JobDetails = {};
    //
    var JobId;
    //
    var type;

    /**
     * Show compensation add/edit
     */
    $(document).on('click', '.jsCompensation', function(event) {
        //
        event.preventDefault();
        //
        type = $(this).data('type');
        //
        Model({
            Id: 'jsJobDetails' + type + 'Modal',
            Title: (type == 'add' ? 'Add' : 'Edit') + ' Compensation Against Job - ' + JobDetails.Title,
            Body: '<div id="jsJobDetails' + type + 'Body">' + (GetBody()) + '</div>',
            Loader: 'jsJobDetails' + type + 'ModalLoader',
        }, function() {
            //
            $('.jsFLSAStatus').select2();
            //
            $('.jsPaymentUnit').select2();
            //
            $('.jsEffectiveDate').datepicker({
                changeYear: true,
                changeMonth: true,
            });
            //
            $(document).on('click', '#jsJobDetails' + type + 'Modal .jsModalCancel', function() {
                $('tr[data-id="' + (JobId) + '"] .jsView').trigger('click');
            });
            //
            if (type === 'add') {
                //
                ml(false, 'jsJobDetails' + type + 'ModalLoader');
            } else {
                // Fetch single compensation
                $.get(API_URL + 'compensation/' + jobId)
                    .done(function(resp) {
                        //
                        if (resp.status !== false) {

                        }
                    });
            }
        });
    });

    /**
     * Save/update compensation
     */
    $(document).on('click', '.jsSave', function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        //
        o.Rate = $('.jsRate').val().replace(/[^\d.]/g, '');
        o.PaymentUnit = $('.jsPaymentUnit option:selected').val();
        o.FlsaStatus = $('.jsFLSAStatus option:selected').val();
        o.EffectiveDate = $('.jsEffectiveDate').val().trim();
        // Validation
        if (!o.Rate) {
            return alertify.alert('Error!', 'Rate is required.');
        }
        if (!o.EffectiveDate) {
            return alertify.alert('Error!', 'Effective date is required.');
        }
        // Check Id
        if ($('.jsCompensationId').length) {
            o.Id = $('.jsCompensationId').val();
        }
        // Set job Id
        o.JobId = JobId;
        //
        ml(true, 'jsJobDetails' + type + 'ModalLoader');
        //
        if (o.Id) {
            UpdateCompensation(o);
        } else {
            AddCompensation(o);
        }
    });

    /**
     * Update Compensation
     * @param {Object} o 
     */
    function UpdateCompensation(o) {}

    /**
     * Add Compensation
     * @param {Object} o 
     */
    function AddCompensation(o) {
        //
        $.ajax({
            url: API_URL.replace(/employees/, '') + 'job/' + o.JobId + '/compensations',
            method: "POST",
            data: JSON.stringify(o),
            headers: { 'Content-Type': 'application/json' }
        }).done(function(resp) {
            //
            ml(false, 'jsJobDetails' + type + 'ModalLoader');
            //
            if (!resp.status) {
                return alertify.alert('Error!', typeof resp.response === 'object' ? resp.response.join("<br>") : resp.response);
            }
            //
            return alertify.alert('Success!', resp.response, function() {
                // GetJobDetails();
            });
        });
    }

    //
    function GetJobDetails(jobId) {
        //
        JobId = jobId;
        //
        $.get(
            API_URL.replace(/employees/, '') + 'job/' + JobId + '/compensations'
        ).done(function(job) {
            //
            JobDetails = job.response;
            //
            $('.jsEmployeeName').text(job.response.Name);
            $('.jsJobTitle').text(job.response.Title);
            $('.jsHireDate').text(job.response.HireDate);
            $('.jsLocation').text(CompanyLocationsObj[job.response.LocationId]);
            //
            var trs = '';
            //
            if (job.response.compensations.length) {
                //
                job.response.compensations.map(function(compensation) {
                    //
                    trs += '<tr data-id="' + (compensation.CompensationId) + '">';
                    trs += '    <td class="vam csF16">' + (compensation.CompensationId) + '</td>';
                    trs += '    <td class="vam csF16 text-right"><strong>$' + (parseFloat(compensation.Rate).toFixed(2)) + '</strong></td>';
                    trs += '    <td class="vam csF16 text-right">' + (compensation.PaymentUnit) + '</td>';
                    trs += '    <td class="vam csF16 text-right">' + (compensation.FlsaStatus) + '</td>';
                    trs += '    <td class="vam csF16 text-right">' + (compensation.EffectiveDate) + '</td>';
                    trs += '    <td class="vam csF16 text-right">';
                    trs += '        <button class="btn btn-warning csF16 csB7 jsCompensation" data-type="edit">';
                    trs += '            <i class="fa fa-edit csF16" aria-hidden="true"></i>&nbsp;Edit';
                    trs += '        </button>';
                    trs += '    </td>';
                    trs += '</tr>';
                });

            } else {
                trs += '<tr>';
                trs += '    <td colspan="6"><p class="text-center alert alert-info csF16">No compensations found.</p></td>';
                trs += '</tr>';
            }
            //
            $('#jsJobDetailsTable').html(trs);
            //
            ml(false, 'jsViewModalLoader');
        });
    }

    //
    function GetBody() {
        var html = '';
        html += '<div class="container">';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '           <label class="csF16 csB7">Rate <span class="csRequired"></span></label> ';
        html += '           <p class=" csF14">The dollar amount paid per payment unit.</p>';
        html += '           <div class="input-group">';
        html += '               <div class="input-group-addon csB7">$</div>';
        html += '               <input type="text" class="form-control jsRate jsAmountField" placeholder="20000.00" />';
        html += '           </div>';
        html += '        </div>';
        html += '    </div>';
        html += '    <br>';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Payment Unit <span class="csRequired"></span></label> ';
        html += '            <p class=" csF14">The unit accompanying the compensation rate. If the employee is an owner, rate should be \'Paycheck\'.</p>';
        html += '            <select class="jsPaymentUnit">';
        html += '                <option value="Hour">Hour</option>';
        html += '                <option value="Week">Week</option>';
        html += '                <option value="Month">Month</option>';
        html += '                <option value="Year">Year</option>';
        html += '                <option value="Paycheck">Paycheck</option>';
        html += '            </select>';
        html += '        </div>';
        html += '    </div>';
        html += '    <br>';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">FLSA Status <span class="csRequired"></span></label> ';
        html += '            <p class=" csF14">The FLSA status for this compensation.<br>Salaried (\'Exempt\') employees are paid a fixed salary every pay period.<br> Salaried with overtime (\'Salaried Nonexempt\') employees are paid a fixed salary every pay period, and receive overtime pay when applicable.<br> Hourly (\'Nonexempt\') employees are paid for the hours they work, and receive overtime pay when applicable.<br> Owners html (\'Owner\') are employees that own at least twenty percent of the company.</p>';
        html += '            <select class="jsFLSAStatus">';
        html += '                <option value="Exempt">Exempt</option>';
        html += '                <option value="Salaried Nonexempt">Salaried Nonexempt</option>';
        html += '                <option value="Nonexempt">Nonexempt</option>';
        html += '                <option value="Owner">Owner</option>';
        html += '            </select>';
        html += '        </div>';
        html += '    </div>';
        html += '    <br>';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12">';
        html += '            <label class="csF16 csB7">Effective Date <span class="csRequired"></span></label> ';
        html += '            <p class=" csF14">The effective date for this compensation. For the first compensation, this defaults to the job\'s hire date.</p>';
        html += '           <input type="text" class="form-control jsEffectiveDate" readonly placeholder="MM/DD/YYYY" />';
        html += '        </div>';
        html += '    </div>';
        html += '    <br>';
        html += '    <div class="row">';
        html += '        <div class="col-sm-12 text-right">';
        html += '            <button class="btn btn-success csF16 csB7 jsSave">';
        html += '                <i class="fa fa-save csF16" aria-hidden="true"></i>&nbsp;' + (type === 'edit' ? 'Update' : 'Save') + '';
        html += '            </button>';
        if (type === 'edit') {
            html += '<input type="hidden" class="jsCompensationId" />';
        }
        html += '        </div>';
        html += '    </div>';
        html += '</div>';

        return html;
    }

    window.GetJobDetails = GetJobDetails;
});