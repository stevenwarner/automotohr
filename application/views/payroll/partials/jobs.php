<!-- Basic Information Panel -->
<br>
<div class="panel panel-success">
    <div class="panel-heading">
        <h1 class="csF16 csB7 csW m0">
            Jobs
            <span class="pull-right">
                Jobs Found: <span class="csCounter">0</span>
            </span>
        </h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-xs-12 text-right">
                <button class="btn btn-success csF16 csB7 jsEAddNew">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add A New Job
                </button>
            </div>
        </div>
        <!--  -->
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col" class="csBG1 csF16 csB7 csW">Title</th>
                        <th scope="col" class="csBG1 csF16 csB7 csW text-right">Location</th>
                        <th scope="col" class="csBG1 csF16 csB7 csW text-right">Hire Date</th>
                        <th scope="col" class="csBG1 csF16 csB7 csW text-right">Last Modified</th>
                        <th scope="col" class="csBG1 csF16 csB7 csW text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="jsDataBody"></tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer text-right">
        <a href="<?=base_url("employee/add/".($employeeId)."?section=other");?>" class="btn btn-success csF16 csB7">
            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Next
        </a>
    </div>
</div>