<?php
if ($records) {
    foreach ($records as $record) {
?>
        <div class="panel panel-default">
            <div class="panel-body">
                <!--  -->
                <div class="row">
                    <div class="col-sm-12">
                        <p><strong>Employee:</strong> <?= $record['user']; ?></p>
                        <p><strong>Date & Time:</strong> <?= $record['created_at']; ?></p>
                    </div>
                </div>
                <hr />
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th class="bg-danger">Old Value</th>
                                <th class="bg-success">New Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($record['difference'] as $column => $difference) {
                            ?>
                                <tr>
                                    <th><?= ucwords(str_replace('_', ' ', $column)); ?></th>
                                    <?php if ($column == 'accrual_plans') { ?>
                                        <td class="bg-danger">
                                            <?php foreach ($difference['old_value'] as $plan) { ?>
                                                <p>Allow <?= $plan['accrualRate']; ?> extra hour(s) after <?= $plan['accrualType']; ?> <?= $plan['accrualTypeM']; ?></p>
                                            <?php } ?>
                                        </td>
                                        <td class="bg-success">
                                            <?php foreach ($difference['new_value'] as $plan) { ?>
                                                <p>Allow <?= $plan['accrualRate']; ?> extra hour(s) after <?= $plan['accrualType']; ?> <?= $plan['accrualTypeM']; ?></p>
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($column == 'employees') { ?>
                                        <td class="bg-danger">
                                            <?php foreach ($difference['old_value'] as $employee) { ?>
                                                <p><?= $employee; ?></p>
                                            <?php } ?>
                                        </td>
                                        <td class="bg-success">
                                            <?php foreach ($difference['new_value'] as $plan) { ?>
                                                <p><?= $employee; ?></p>
                                            <?php } ?>
                                        </td>
                                    <?php } else if ($column == 'allowed_approver') { ?>
                                        <td class="bg-danger">
                                            <?php foreach ($difference['old_value'] as $employee) { ?>
                                                <p><?= $employee; ?></p>
                                            <?php } ?>
                                        </td>
                                        <td class="bg-success">
                                            <?php foreach ($difference['new_value'] as $plan) { ?>
                                                <p><?= $employee; ?></p>
                                            <?php } ?>
                                        </td>

                                    <?php } else { ?>
                                        <td class="bg-danger"><?= $difference['old_value']; ?></td>
                                        <td class="bg-success"><?= $difference['new_value']; ?></td>
                                    <?php } ?>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    }
} else {
    ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <p class="text-center alert alert-info">
                <strong>No history found!</strong>
            </p>
        </div>
    </div>
<?php
}
?>