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
            <div class="row dn">
                <div class="col-md-12 text-right">
                    <button class="btn btn-success csF16 csB7 jsCompensation" data-type="Add">
                        <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>&nbsp;Add Compensation
                    </button>
                </div>
            </div>
            <div class="table-responsive">
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
</div>