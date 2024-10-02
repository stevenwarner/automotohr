<!-- Reviewees -->
<?php if ($load_view) {
$panelHeading = 'background-color: #3554DC';
} else {
$panelHeading = 'background-color: #81b431';
}
?>
<div class="panel panel-theme">
    <div class="panel-heading" style="<?=$panelHeading?>">
        <div class="row">
            <div class="col-xs-11">
                <p class="csF16 csB7 csW mb0">Select Reviewees <small>(The reviewee's are the employee's against which the review will run)</small></p>
            </div>
            <div class="col-xs-1">
                <span class="pull-right">
                    <i class="fa fa-minus-circle csCP csF18 csB7 jsPageBTN" aria-hidden="true" data-target="basic"></i>
                </span>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="panel-body jsPageBody" data-page="basic">

        <!-- Info -->
        <div class="row">
            <div class="col-xs-12">
                <p class="csF16 pl10"><i class="fa fa-info-circle csF18 csB7" aria-hidden="true"></i>&nbsp;<em class="csInfo">Use the Rule Settings to define which workers are included (or excluded) from this review</em>.</p>
            </div>
        </div>

        <!-- Main -->
        <div class="row">
            <br />
            <!-- Filter -->
            <div class="col-md-4 col-xs-12">
                <div class="panel panel-theme">
                    <div class="panel-heading">
                        <p class="csF16 csB7 csW mb0">Rule Settings</p>
                    </div>
                    <div class="panel-body">
                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7 bbb">Include Employee(s)</p>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7">Role(s)</p>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <select id="jsReviewRevieweeFilterRoles" multiple>
                                    <?php foreach (getRoles() as $index => $role) : ?>
                                        <option value="<?= $index; ?>"><?= $role; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7">Department(s)</p>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <select id="jsReviewRevieweeFilterDepartments" multiple>
                                    <?= $efj['DepartmentRows']; ?>
                                </select>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7">Team(s)</p>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <select id="jsReviewRevieweeFilterTeams" multiple>
                                    <?= $efj['TeamRows']; ?>
                                </select>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7">Employee(s)</p>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <select id="jsReviewRevieweeFilterEmployees" multiple>
                                    <?= $efj['EmployeeRows']; ?>
                                </select>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7">Job Title(s)</p>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <select id="jsReviewRevieweeFilterJob" multiple>
                                    <?php if (!empty($job_titles)) : ?>
                                        <?php foreach ($job_titles as $job_title) : ?>
                                            <option value="<?= stringToSlug($job_title); ?>"><?= $job_title; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7">Employment Type(s)</p>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <select id="jsReviewRevieweeFilterType" multiple>
                                    <option value="fulltime">Full time</option>
                                    <option value="parttime">Part time</option>
                                    <option value="contractual">Contractual</option>
                                </select>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7 bbb">Exclude Employee(s)</p>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7">Employee(s)</p>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <select id="jsReviewRevieweeFilterExcludeEmployees" multiple>
                                    <?= $efj['EmployeeRows']; ?>
                                </select>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-xs-12">
                                <p class="csF14 csB7">Hire Date</p>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <select id="jsReviewRevieweeFilterExcludeFrame">
                                    <option value="0">None</option>
                                    <option value="30">30 days from hire date</option>
                                    <option value="60">60 days from hire date</option>
                                    <option value="90">90 days from hire date</option>
                                </select>
                            </div>
                        </div>

                        <!--  -->
                        <div class="row">
                            <br />
                            <div class="col-sm-12">
                                <button class="btn btn-orange form-control csF16 jsReviewRevieweeSearchBtn">
                                    <i class="fa fa-search csB7" aria-hidden="true"></i>&nbsp;
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <br />
                            <div class="col-sm-12">
                                <button class="btn btn-black form-control csF16 jsReviewRevieweeResetBtn">
                                    <i class="fa fa-refresh csB7" aria-hidden="true"></i>&nbsp;
                                    Reset Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-8 col-xs-12">
                <div class="panel panel-theme">
                    <div class="panel-heading">
                        <p class="csF16 csB7 csW mb0">
                            Selected Reviewee(s): <span id="jsReviewRevieweesCount"><?= count($company_employees); ?></span>
                        </p>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-condensed">
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th scope="col" class="csF16">Employee</th>
                                    <th scope="col" class="csF16 text-center" style="vertical-align: middle;">Department</th>
                                    <th scope="col" class="csF16 text-center" style="vertical-align: middle;">Team</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($company_employees)) : ?>
                                    <?php foreach ($company_employees as $employee) : ?>
                                        <?php
                                        //
                                        $departmentNames =
                                            $teamNames = 'N/A';
                                        //
                                        if (!empty($employee['Departments'])) {
                                            //
                                            $departmentNames = '';
                                            //
                                            foreach ($employee['Departments'] as $t) {
                                                //
                                                $departmentNames .= $company_dt['Departments'][$t]['Name'] . ',';
                                            }
                                            //
                                            $departmentNames = rtrim($departmentNames, ',');
                                        }
                                        //
                                        if (!empty($employee['Teams'])) {
                                            //
                                            $teamNames = '';
                                            //
                                            foreach ($employee['Teams'] as $t) {
                                                //
                                                $teamNames .= $company_dt['Teams'][$t]['Name'] . ',';
                                            }
                                            //
                                            $teamNames = rtrim($teamNames, ',');
                                        }
                                        ?>
                                        <tr class="jsReviewRevieweesRow" data-id="<?= $employee['Id']; ?>" data-role="<?= stringToSlug($employee['BasicRole']); ?>" data-job="<?= stringToSlug($employee['JobTitle']); ?>" data-join="<?= $employee['JoinedDate']; ?>" data-departments="<?= implode(',', $employee['Departments']); ?>" data-teams="<?= implode(',', $employee['Teams']); ?>" data-type="<?= $employee['EmploymentType']; ?>">
                                            <td style="vertical-align: middle;">
                                                <h6 class="csF14 csB7 mb0"><?= $employee['Name']; ?></h6>
                                                <p class="csF14"><?= $employee['Role']; ?></p>
                                                <p class="csF14">Joined On: <?= formatDate($employee['JoinedDate']); ?></p>
                                            </td>
                                            <td class="text-center csF14" style="vertical-align: middle;"><?= $departmentNames; ?></td>
                                            <td class="text-center csF14" style="vertical-align: middle;"><?= $teamNames; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </select>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Buttons -->
<div class="row">
    <div class="col-sm-12">
        <div class="bbb"></div>
        <br />
        <button class="btn btn-black csF16 jsPageSectionBtn" data-to="schedule"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp; Back To Schedule</button>
        <span class="pull-right">
            <button class="btn btn-orange csF16" id="jsReviewRevieweesSaveBtn"><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp; Save & Next</button>
            <button class="btn btn-black csF16"><i class="fa fa-archive" aria-hidden="true"></i>&nbsp; Finish Later</button>
        </span>
    </div>
    <div class="clearfix"></div>
</div>