<br>
<!--  -->
<div class="row">

    <div class="col-sm-9 col-xs-12 table-responsive">
        <table class="table table-striped table-bordered">
            <caption>Employee Details.</caption>
            <tbody>
                <tr>
                    <td class="col-sm-3"><strong>Name</strong></td>
                    <td class="col-sm-6"><?= $Name; ?> <?= $Role; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Email</strong></td>
                    <td class="col-sm-6"><?= $Email; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Phone</strong></td>
                    <td class="col-sm-6"><?= !empty($Phone) ? $Phone : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Employment Type</strong></td>
                    <td class="col-sm-6"><?= !empty($EmploymentType) ? $EmploymentType : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Date Of Birth</strong></td>
                    <td class="col-sm-6"><?= !empty($DOB) ? formatDateToDB($DOB, 'Y-m-d', DATE) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Joined On</strong></td>
                    <td class="col-sm-6">
                        <?php
                        if (!empty($JoinedDate)) {
                            echo $JoinedDate;
                        } else {
                            echo "N/A";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Rehired On</strong></td>
                    <td class="col-sm-6">
                        <?php
                        if (!empty($RehiredDate)) {
                            echo $RehiredDate;
                        } else {
                            echo "N/A";
                        }
                        ?>
                    </td>
                </tr>

            </tbody>

        </table>
    </div>
    <div class="col-sm-3 col-xs-12" style="padding-top: 30px;">
        <figure><img style="display: block; max-width: 100%; margin: auto;" src="<?= getImageURL($Image); ?>" alt="Employee" />
        </figure>
    </div>

</div>

<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption>The selected employee is managing the following department(s) and team(s).</caption>
            <tbody>
                <tr>
                    <td class="col-sm-3"><strong>Department(s)</strong></td>
                    <td class="col-sm-9"><?= !empty($Managing['Departments']) ? implode('<br>', $Managing['Departments']) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Team(s)</strong></td>
                    <td class="col-sm-9"><?= !empty($Managing['Teams']) ? implode('<br>', $Managing['Teams']) : 'N/A'; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption>The selected employee is part of the following department(s) and team(s).</caption>
            <tbody>
                <tr>
                    <td class="col-sm-3"><strong>Department(s)</strong></td>
                    <td class="col-sm-9"><?= !empty($Departments) ? implode('<br>', $Departments) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Team(s)</strong></td>
                    <td class="col-sm-9"><?= !empty($Teams) ? implode('<br>', $Teams) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Colleague(s)</strong></td>
                    <td class="col-sm-9"><?= !empty($TeamMembers) ? implode('<br>', $TeamMembers) : 'N/A'; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption>The following employees are in charge of the selected employee.</caption>
            <tbody>
                <tr>
                    <td class="col-sm-3"><strong>Supervisor(s)</strong></td>
                    <td class="col-sm-9"><?= !empty($Supervisors) ? implode('<br>', $Supervisors) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Team Lead(s)</strong></td>
                    <td class="col-sm-9"><?= !empty($TeamLeads) ? implode('<br>', $TeamLeads) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Approver(s)</strong></td>
                    <td class="col-sm-9">
                        <?php
                        if (!empty($Approvers)) {
                            foreach ($Approvers as $Approver) {
                        ?>
                                <p><?= $Approver['user']; ?></p>
                                <?php
                                if (!empty($Approver['departments'])) {
                                ?>
                                    <p><strong>Departments:</strong> <br>
                                        <?php
                                        foreach ($Approver['departments'] as $dt) {
                                        ?>
                                            <span><?= $dt['Names']; ?> (<?= $dt['CanApprove']; ?>)</span>
                                        <?php
                                        }
                                        ?>
                                    </p>
                                <?php
                                }
                                ?>
                                <?php
                                if (!empty($Approver['teams'])) {
                                ?>
                                    <p><strong>Teams:</strong> <br>
                                        <?php
                                        foreach ($Approver['teams'] as $dt) {
                                        ?>
                                            <span><?= $dt['Names']; ?> (<?= $dt['CanApprove']; ?>)</span>
                                        <?php
                                        }
                                        ?>
                                    </p>
                                <?php
                                }
                                ?>
                                <hr>
                        <?php
                            }
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Reporting Manager(s)</strong></td>
                    <td class="col-sm-9"><?= !empty($ReporitngManagers) ? implode('<br>', $ReporitngManagers) : 'N/A'; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption>Visibility</caption>
            <tbody>
                <tr>
                    <td class="col-sm-3"><strong>Job(s)</strong></td>
                    <td class="col-sm-9">
                        <?php
                        if (!empty($Jobs)) {
                            foreach ($Jobs as $Job) {
                        ?>
                                <p><?= $Job['Title']; ?> (<span class="text-<?= $Job['active'] ? 'success' : 'danger'; ?>"><?= $Job['active'] ? 'Active' : 'InActive'; ?></span>) [<?= $Job['sid']; ?>]</p>
                        <?php
                            }
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption>Assign Authorized document</caption>
            <thead>
                <th>Company Name</th>
                <th>Name</th>
                <th>Type</th>
                <th>Document Title</th>
            </thead>
            <tbody>
                <?php if (!empty($assigned_auth_documents)) { ?>
                    <?php foreach ($assigned_auth_documents as $document) { ?>
                        <tr>
                            <td>
                                <?php echo ucwords($document["company_name"]); ?>
                            </td>
                            <td>
                                <?php echo ucwords($document["user_name"]); ?>
                            </td>
                            <td>
                                <?php echo ucwords($document["user_type"]); ?>
                            </td>
                            <td>
                                <?php echo $document["document_title"]; ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Document Not Assigned
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>