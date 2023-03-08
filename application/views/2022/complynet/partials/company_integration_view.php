<hr>
<div class="row">
    <div class="col-sm-12 text-right">
        <button class="btn btn-success jsShowAllJobRoles">Show ComplyNet Job Roles</button>
        <button class="btn btn-success jsShowAllDepartments">Show ComplyNet Departments</button>
        <button class="btn btn-success jsRefreshCompany">Refresh</button>
        <button class="btn btn-success jsSyncCompany">Sync</button>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-sm-4">
        <canvas id="jsCompanyCanvas"></canvas>
        <br>
        <p class="text-center"><em><strong class="text-danger">Company Progress</strong></em></p>
    </div>
    <div class="col-sm-4">
        <canvas id="jsEmployeeCanvas"></canvas>
        <br>
        <p class="text-center"><em><strong class="text-danger">Employee Progress</strong></em></p>
    </div>
    <div class="col-sm-4">
        <canvas id="jsDepartmentCanvas"></canvas>
        <br>
        <p class="text-center"><em><strong class="text-danger">Department Progress</strong></em></p>
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
                    <td><?= $company['complynet_company_sid']; ?></td>
                </tr>
                <tr>
                    <th scope="col" class="col-sm-3">ComplyNet Company Name</th>
                    <td><?= $company['complynet_company_name']; ?></td>
                </tr>
                <tr>
                    <th scope="col" class="col-sm-3">ComplyNet Location Id</th>
                    <td><?= $company['complynet_location_sid']; ?></td>
                </tr>
                <tr>
                    <th scope="col" class="col-sm-3">ComplyNet Location Name</th>
                    <td><?= $company['complynet_location_name']; ?></td>
                </tr>
                <tr>
                    <th scope="col" class="col-sm-3">Integrated At</th>
                    <td><?= formatDateToDB($company['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
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
                    <?php foreach ($departments as $department) {
                    ?>
                    <tr>
                        <td><?= $department['department_sid']; ?></td>
                        <td><?= $department['department_name']; ?></td>
                        <td><?= $department['complynet_department_sid']; ?></td>
                        <td><?= $department['complynet_department_name']; ?></td>
                        <td><?= formatDateToDB($department['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
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


    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#oncomplynet"><b>On ComplyNet</b></a></li>
        <li><a data-toggle="tab" href="#offcomplynet"><b>Off ComplyNet</b></a></li>
    </ul>

    <div class="tab-content">
        <div id="oncomplynet" class="tab-pane fade in active">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col">Email</th>
                                <th scope="col">ComplyNet Id</th>
                                <th scope="col">DateTime</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($employees)) {
                            ?>
                            <tr>
                                <td colspan="4">
                                    <p class="alert alert-info text-center">
                                        No employees on complyNet yet.
                                    </p>
                                </td>
                            </tr>
                            <?php
                            } else { ?>

                            <?php foreach ($employees as $employee) {
                                ?>
                            <tr data-id="<?=$employee['sid']?>">
                                <td>
                                    <?php
                                            $empData = json_decode($employee['complynet_json']);
                                            echo '<strong>' . $empData[0]->FirstName . ' ' . $empData[0]->LastName . '</strong><br>';
                                            echo $employee['email'];
                                            ?>
                                </td>
                                <td><?= $employee['complynet_employee_sid']; ?></td>
                                <td><?= formatDateToDB($employee['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                </td>
                                <td>
                                    <?php if ($employee['status'] == 1) {?>
                                    <!-- <button class='btn btn-warning jsDeactivateEmployee' title="Deactivate complyNet"><i class="fa fa-ban" aria-hidden="true"></i></button> -->
                                    <?php } else { ?>
                                        <!-- <button class='btn btn-success jsActivateEmployee' title="Activate complyNet"><i class="fa fa-shield" aria-hidden="true"></i></button> -->
                                    <?php } ?>
                                    <button class='btn btn-success jsShowComplyNetEmployeeDetails'><b>Detail</b></button>
                                </td>
                            </tr>
                            <?php
                                } ?>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <div id="offcomplynet" class="tab-pane fade">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col">Name / Email</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($offComplyNetEmployees as $emp) {
                                //
                                $errorArray = [];
                                //
                                if (empty($emp['first_name'])) {
                                    $errorArray[] = '<strong class="text-danger">First name is missing</strong>';
                                }
                                //
                                if (empty($emp['last_name'])) {
                                    $errorArray[] = '<strong class="text-danger">Last name is missing</strong>';
                                }
                                //
                                if (empty($emp['email'])) {
                                    $errorArray[] = '<strong class="text-danger">Email address is missing</strong>';
                                }
                                //
                                if (empty($emp['complynet_job_title'])) {
                                    $errorArray[] = '<strong class="text-danger">Job title is missing</strong>';
                                }
                                //
                                if (empty($emp['username'])) {
                                    $errorArray[] = '<strong class="text-danger">Username is missing</strong>';
                                }
                                //
                                if (empty($emp['PhoneNumber'])) {
                                    $errorArray[] = '<strong class="text-danger">Phone number is missing</strong>';
                                }
                                //
                                if ($emp['department_sid'] == '0') {
                                    $errorArray[] = '<strong class="text-danger">Department is missing</strong>';
                                }
                                //
                                if ($emp['team_sid'] == '0') {
                                    $errorArray[] = '<strong class="text-danger">Team is missing</strong>';
                                }
                            ?>
                            <tr>
                                <td>
                                    <?php
                                        echo '<strong>' . remakeEmployeeName($emp) . '</strong><br />';
                                        echo $emp['email'];
                                        ?>
                                </td>
                                <td>
                                    <?php if ($errorArray) {
                                            echo implode('<br />', $errorArray);
                                        } else {
                                            echo '-';
                                        } ?>
                                </td>
                                <td>
                                    <?php if (!$errorArray) { ?>
                                    <button class="btn btn-success jsSyncSingleEmployee"
                                        data-id="<?= $emp['sid']; ?>">Sync Employee</button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    // Company
    loadHourGraph('jsCompanyCanvas', {
        data: {
            labels: ['On ComplyNet', 'Off ComplyNet'],
            datasets: [{
                label: 'Dataset 1',
                data: [
                    <?= count($departments) + count($employees); ?>,
                    <?= ($allDepartmentCount - count($departments)) + count($offComplyNetEmployees); ?>,
                ],
                backgroundColor: [
                    '#fd7a2a',
                    '#3554dc',
                ],
            }]
        },
        textToShow: "Company"
    });
    // Department
    loadHourGraph('jsDepartmentCanvas', {
        data: {
            labels: ['On ComplyNet', 'Off ComplyNet'],
            datasets: [{
                label: 'Dataset 1',
                data: [
                    <?= count($departments); ?>,
                    <?= $allDepartmentCount - count($departments); ?>,
                ],
                backgroundColor: [
                    '#fd7a2a',
                    '#3554dc',
                ],
            }]
        },
        textToShow: "Employees"
    });
    // Employees
    loadHourGraph('jsEmployeeCanvas', {
        data: {
            labels: ['On ComplyNet', 'Off ComplyNet'],
            datasets: [{
                label: 'Dataset 1',
                data: [
                    <?= count($employees); ?>,
                    <?= count($offComplyNetEmployees); ?>,
                ],
                backgroundColor: [
                    '#fd7a2a',
                    '#3554dc',
                ],
            }]
        },
        textToShow: "Employees"
    });
    //
    function loadHourGraph(ref, options) {

        const config = {
            type: 'pie',
            data: options.data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: options.textToShow
                    }
                }
            },
        };
        new Chart(document.getElementById(ref), config);
    }
})
</script>
