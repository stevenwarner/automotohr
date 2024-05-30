<style>
    .jsSectionOne:nth-child(even) {
        background: #eee;
    }

    textarea {
        resize: none;
    }
</style>
<br />
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="text-medium panel-heading-text">
                <i class="fa fa-users text-orange" aria-hidden="true"></i>
                Assigned Employees
            </h2>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($employees) { ?>
                            <?php foreach ($employees as $employeeId) { ?>
                                <tr>
                                    <td>
                                        <?=getUserNameBySID($employeeId)?>
                                    </td>
                                    <td>
                                        <?=db_get_employee_profile($employeeId)[0]['email']?>
                                    </td>
                                    <td><a href="<?= base_url('hr_documents_management/employee_document/' . $employeeId) ?>" target="_blank" class="btn btn-success btn-sm">View All</a></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>