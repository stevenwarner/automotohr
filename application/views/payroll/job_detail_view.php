<div class="container">
    <!-- Job Details -->
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <label class="csF16 csB7">Employee Name</label>
            <p class="csF16 jsEmployeeName"></p>
        </div>
        <div class="col-md-3 col-xs-12">
            <label class="csF16 csB7">Job Title</label>
            <p class="csF16 jsJobTitle"></p>
        </div>
        <div class="col-md-3 col-xs-12">
            <label class="csF16 csB7">Hire Date</label>
            <p class="csF16 jsHireDate"></p>
        </div>
        <div class="col-md-3 col-xs-12">
            <label class="csF16 csB7">Location</label>
            <p class="csF16 jsLocation"></p>
        </div>
    </div>
    <br>
    <!-- Compendations -->
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="csF16 csB7 csW m0 p0">Compensations</h3>
        </div>
        <div class="panel-body">
            <!--  -->
            <div class="row">
                <div class="col-md-12">
                    <p class="csInfo csF16 csB7">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Compensations contain information on how much is paid out for a job. Jobs may have many compensations, but only one is active. The current compensation is the one with the most recent effective date.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-success csF16 csB7 jsAddCompensation">
                        <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>&nbsp;Add Compensation
                    </button>
                </div>
            </div>
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col" class="vam csF16 csB7 csBG1 csW">Reference</th>
                        <th scope="col" class="vam csF16 csB7 csBG1 csW text-right">Rate</th>
                        <th scope="col" class="vam csF16 csB7 csBG1 csW text-right">Payment Unit</th>
                        <th scope="col" class="vam csF16 csB7 csBG1 csW text-right">FLSA Status</th>
                        <th scope="col" class="vam csF16 csB7 csBG1 csW text-right">Effective Date</th>
                        <th scope="col" class="vam csF16 csB7 csBG1 csW text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="jsJobDetailsTable"></tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(function Compensations(){
        //
        var JobId = <?=$jobId;?>;
        //
        var JobDetails = {};
        //
        $('.jsAddCompensation').click(function(event){
            //
            event.preventDefault();
            //
            Model({
                Id: 'jsJobDetailsModal',
                Title: 'Add Compensation Against Job - '+ JobDetails.Title,
                Body: '<div id="jsJobDetailsBody">'+(GetBody())+'</div>',
                Loader: 'jsJobDetailsModalLoader',
            }, function(){
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
                $(document).on('click', '#s .jsModalCancel', function(){
                   $('tr[data-id="'+(JobId)+'"] .jsView').trigger('click');
                });
                //
                ml(false, 'jsJobDetailsModalLoader');
            });
        });

        //
        function GetJobDetails(){
            //
            $.get(
                API_URL.replace(/employees/, '')+'job/'+JobId+'/compensations'
            ).done(function(job){
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
                if(job.response.compensations.length){
                    //
                    job.response.compensations.map(function(compensation){
                        //
                        trs += '<tr>';
                        trs += '    <td class="vam csF16">13121233132132132</td>';
                        trs += '    <td class="vam csF16 text-right">$20.00</td>';
                        trs += '    <td class="vam csF16 text-right">Hourly</td>';
                        trs += '    <td class="vam csF16 text-right">Exempt</td>';
                        trs += '    <td class="vam csF16 text-right">Jan 09 2013, Wed</td>';
                        trs += '    <td class="vam csF16 text-right">';
                        trs += '        <button class="btn btn-warning csF16 csB7">';
                        trs += '            <i class="fa fa-edit csF16" aria-hidden="true"></i>&nbsp;Edit';
                        trs += '        </button>';
                        trs += '    </td>';
                        trs += '</tr>';
                    });

                } else{
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
        function GetBody(){
            var html = '';
            html += '<div class="container">';
            html += '    <div class="row">';
            html += '        <div class="col-sm-12">';
            html += '           <label class="csF16 csB7">Rate <span class="csRequired"></span></label> ';
            html += '           <p class="csInfo csF14">The dollar amount paid per payment unit.</p>';
            html += '           <input type="text" class="form-control jsRate" placeholder="20000.00" />';
            html += '        </div>';
            html += '    </div>';
            html += '    <br>';
            html += '    <div class="row">';
            html += '        <div class="col-sm-12">';
            html += '            <label class="csF16 csB7">Payment Unit <span class="csRequired"></span></label> ';
            html += '            <p class="csInfo csF14">The unit accompanying the compensation rate. If the employee is an owner, rate should be \'Paycheck\'.</p>';
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
            html += '            <p class="csInfo csF14">The FLSA status for this compensation. Salaried (\'Exempt\') employees are paid a fixed salary every pay period. Salaried with overtime (\'Salaried Nonexempt\') employees are paid a fixed salary every pay period, and receive overtime pay when applicable. Hourly (\'Nonexempt\') employees are paid for the hours they work, and receive overtime pay when applicable. Owners html (\'Owner\') are employees that own at least twenty percent of the company.</p>';
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
            html += '            <p class="csInfo csF14">The effective date for this compensation. For the first compensation, this defaults to the job\'s hire date.</p>';
            html += '           <input type="text" class="form-control jsEffectiveDate" readonly placeholder="MM/DD/YYYY" />';
            html += '        </div>';
            html += '    </div>';
            html += '    <br>';
            html += '    <div class="row">';
            html += '        <div class="col-sm-12 text-right">';
            html += '            <button class="btn btn-success csF16 csB7">';
            html += '                <i class="fa fa-save csF16" aria-hidden="true"></i>&nbsp;Save';
            html += '            </button>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';

            return html;
        }

        //
        GetJobDetails();
    });
</script>