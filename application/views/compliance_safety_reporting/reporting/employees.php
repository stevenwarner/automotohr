
<div class="tab-pane <?= $this->input->get("tab", true) == "employees" ? "active" : ""; ?>" id="tab-employees" role="tabpanel">
    <!-- Add Department and Teams -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>Add Department</strong>
            </h1>
        </div>
        <div class="panel-body">
            <?php if (empty($departments)) { ?>
                <div class="row" style="margin: 5px 5px;">
                    <div class="col-lg-12" style="font-weight: 700; color: red;">Kindly ensure the department is
                        added to the report before proceeding.</div>
                </div>
            <?php } ?>
            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="form-group autoheight">
                        <label>Departments</label>
                        <select name="departments[]" id="jsCompanyDepartments" multiple>
                            <?php
                            //
                            $allowedDepartments = empty($report['allowed_departments']) ? [] : explode(',', $report['allowed_departments']);
                            //
                            if (!empty($departments)) {
                                foreach ($departments as $v) {
                            ?>
                                    <option value="<?= $v['sid']; ?>" <?= in_array($v['sid'], $allowedDepartments) ? 'selected' : ''; ?>><?= $v['name']; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <br />

            <!-- Teams -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="form-group autoheight">
                        <label>Teams</label>
                        <select name="teams[]" id="jsTeams" multiple>
                            <?php
                            //
                            $allowedTeams = empty($report['allowed_teams']) ? [] : explode(',',$report['allowed_teams']);
                            //
                            if (!empty($teams)) {
                                foreach ($teams as $v) {
                            ?>
                                    <option value="<?= $v['sid']; ?>" <?= in_array($v['sid'], $allowedTeams) ? 'selected' : ''; ?>><?= $v['name']; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-orange jsAddDepartmentsAndTeams">
                <i class="fa fa-plus-circle"></i>
                Save Changes
            </button>
            <?php if (!empty($allowedDepartments) || !empty($allowedTeams)) { ?>
                <button class="btn btn-red jsRemoveDepartmentsAndTeams">
                    <i class="fa fa-refresh"></i>
                    Reset Department & Team
                </button>
            <?php } ?>
        </div>
    </div>

    <!-- Employees -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-heading-text text-medium">
                <strong>Internal Employees</strong>
            </h1>
        </div>
        <div class="panel-body">
            <?php if ($employees):
                $selectedEmployees = array_column($report["internal_employees"], "employee_sid"); ?>
                <div class="row">
                    <?php foreach ($employees as $employee): ?>
                        <div class="col-lg-4">
                            <label class="control control--checkbox">
                                <input type="checkbox" name="report_employees[]" class="jsInternalEmployees"
                                    value="<?= $employee["sid"]; ?>" <?= in_array($employee["sid"], $selectedEmployees) ? "checked" : ""; ?>
                                    <?= $allDTEmployeeIds && in_array($employee["sid"], $allDTEmployeeIds) ? "checked disabled title='Coming from the selected department'" : ""; ?> />
                                <div class="control__indicator"></div>
                                <span><?= remakeEmployeeName($employee); ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-danger">No employees found.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-orange jsUpdateItemBtn" data-type="internal">
                <i class="fa fa-save"></i>
                Save Changes
            </button>
        </div>
    </div>

    <!-- Employees -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="panel-heading-text text-medium">
                        <strong>
                            External Employees
                        </strong>
                    </h1>

                </div>
                <div class="col-sm-6 text-right">
                    <button class="btn btn-orange jsAddExternalEmployee">
                        <i class="fa fa-plus"></i>
                        Add External Employee
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body jsAddExternalBody">
            <?php if ($report["external_employees"]): ?>
                <?php foreach ($report["external_employees"] as $key => $item): ?>
                    <div class="row jsEER" data-external="<?= $key; ?>" data-id="<?= $item["sid"]; ?>">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="external_employee_name">Name</label>
                                <input type="text" name="external_employees_names[<?= $key; ?>]['name']"
                                    class="form-control jsExternalEmployeeName"
                                    value="<?= $item["external_name"]; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="external_employee_email">Email</label>
                                <input type="email" name="external_employees_emails[<?= $key; ?>]['email']"
                                    class="form-control jsExternalEmployeeEmail"
                                    value="<?= $item["external_email"]; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-red btn-block jsRemoveExternalEmployee">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    No External employees found
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>    