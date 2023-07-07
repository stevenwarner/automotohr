<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <?php $newEmployeeId = array_column($copyTransferEmployee, 'newEmployeeId'); ?>
            <tr>
                <th scope="col" colspan="4">
                    <strong>
                        <?php if (!empty($newEmployeeId[0])) {
                            echo getUserNameBySID($newEmployeeId[0]);
                        } ?>
                    </strong>
                </th>

            </tr>
            <tr>
                <th scope="col">From Company</th>
                <th scope="col">To Company</th>
                <th scope="col"># of Documents</th>
                <th scope="col">Transfer Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($copyTransferEmployee)) { ?>
                <?php foreach ($copyTransferEmployee as $key => $value) {
                    $totalDoc = 0;

                    $totalDoc = $totalDoc + count($value['assignedDocuments']);
                    if ($value['docw4'] > 0) {
                        $totalDoc = $totalDoc + 1;
                    }
                    if ($value['docw9'] > 0) {
                        $totalDoc = $totalDoc + 1;
                    }
                    if ($value['doci9'] > 0) {
                        $totalDoc = $totalDoc + 1;
                    }
                    if ($value['emergencyContact'] > 0) {
                        $totalDoc = $totalDoc + 1;
                    }
                    if ($value['dependents'] > 0) {
                        $totalDoc = $totalDoc + 1;
                    }
                    if ($value['directDeposit'] > 0) {
                        $totalDoc = $totalDoc + 1;
                    }
                    if ($value['license'] > 0) {
                        $totalDoc = $totalDoc + 1;
                    }

                ?>
                    <tr data-id="<?= $value['sid'] ?>">
                        <td> <?= $value['fromCompany']; ?></td>
                        <td><?= $value['toCompany']; ?></td>
                        <td>
                            <button class="btn btn-link jsToggleRow"><strong><?php echo $totalDoc; ?> Documents transferred</strong></button>
                        <td>
                            <?php
                            if (isset($value["copyDate"]) && !empty($value["copyDate"])) {
                                echo formatDateToDB($value['copyDate'], DB_DATE_WITH_TIME, DATE);
                            }
                            ?>
                        </td>

                    </tr>

                    <tr style="display: none" class="jsToggleTable<?=$value['sid'];?>">
                        <td colspan="4">
                            <?php if ($value['docw4'] > 0) { ?>
                                <p>W4 Fillable</p>
                                <hr>
                            <?php } ?>
                            <?php if ($value['docw9'] > 0) { ?>
                                <p>W9 Fillable</p>
                                <hr>
                            <?php } ?>

                            <?php if ($value['doci9'] > 0) { ?>
                                <p>I9 Fillable</p>
                                <hr>
                            <?php } ?>

                            <?php if ($value['emergencyContact'] > 0) { ?>
                                <p>Emergency Contacts</p>
                                <hr>
                            <?php } ?>

                            <?php if ($value['dependents'] > 0) { ?>
                                <p>Dependents</p>
                                <hr>
                            <?php } ?>
                            <?php if ($value['directDeposit'] > 0) { ?>
                                <p>Direct Deposit Information</p>
                                <hr>
                            <?php } ?>
                            <?php if ($value['license'] > 0) { ?>
                                <p>Drivers License Information</p>
                                <hr>
                            <?php } ?>


                            <?php if (!empty($value['assignedDocuments'])) {
                                foreach ($value['assignedDocuments'] as $row) { ?>
                                    <p><?php echo $row['document_title']; ?></p>
                                    <hr>
                            <?php }
                            } ?>

                        </td>
                    </tr>
                <?php } ?>
            <?php } else {  ?>
                <tr>
                    <td colspan="8" class="text-center">
                        <span class="no-data">No Record Found</span>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>