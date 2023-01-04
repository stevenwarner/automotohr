<hr>
<div class="row">
    <div class="col-sm-12 text-right">
        <!-- <button class="btn btn-success">View ComplyNet Departments</button> -->
        <button class="btn btn-success jsRefreshCompany">Refresh</button>
        <button class="btn btn-success jsSyncCompany">Sync</button>
    </div>
</div>
<br>
<!--  -->
<div class="panel panel-default">
    <div class="panel-heading">
        <strong> Details</strong>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <tr>
                    <th scope="col" class="col-sm-3">ComplyNet Company Id</th>
                    <td><?=$company['complynet_company_sid'];?></td>
                </tr>
                <tr>
                    <th scope="col" class="col-sm-3">ComplyNet Location Id</th>
                    <td><?=$company['complynet_location_sid'];?></td>
                </tr>
                <tr>
                    <th scope="col" class="col-sm-3">Integrated At</th>
                    <td><?=formatDateToDB($company['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!--  -->
<div class="panel panel-default">
    <div class="panel-heading">
       <strong> Departments</strong>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col">Department Id</th>
                        <th scope="col">Department Name</th>
                        <th scope="col">ComplyNet Department Id</th>
                        <th scope="col">ComplyNet Department Name</th>
                        <th scope="col">DateTime</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($departments as $department){
                    ?>
                    <tr>
                        <td><?=$department['department_sid'];?></td>
                        <td><?=$department['department_name'];?></td>
                        <td><?=$department['complynet_department_sid'];?></td>
                        <td><?=$department['complynet_department_name'];?></td>
                        <td><?=formatDateToDB($department['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?></td>
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!--  -->
<div class="panel panel-default">
    <div class="panel-heading">
       <strong> Employees</strong>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col">Employee Id</th>
                        <th scope="col">Email</th>
                        <th scope="col">ComplyNet Id</th>
                        <th scope="col">DateTime</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($employees as $employee){
                    ?>
                    <tr>
                        <td><?=$employee['employee_sid'];?></td>
                        <td><?=$employee['email'];?></td>
                        <td><?=$employee['complynet_employee_sid'];?></td>
                        <td><?=formatDateToDB($employee['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?></td>
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>