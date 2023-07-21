<?php if (!empty($data)) {

    foreach ($data as $row) {
        $status = json_decode($row['status']);
?>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-primary">
                    <th colspan="2">
                        <span class="pull-right">
                            <?php echo reset_datetime(array(
                                'datetime' => $row['created_at'],
                                'from_format' => DB_DATE_WITH_TIME,
                                'format' => DATE_WITH_TIME,
                                '_this' => $this
                            )); ?>
                        </span> <?php echo getUserNameBySID($row['created_by']); ?>
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td class="bg-danger"><?php echo $status[0]->OldStatus; ?></td>
                    <td class="bg-success"><?php echo $status[0]->NewStatus; ?></td>
                </tr>

            </tbody>
        </table>
    <?php } ?>

<?php } else { ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    <p class="alert alert-info text-center">
                        <strong>No records found.</strong>
                    </p>
                </th>
            </tr>
        </thead>

    </table>
<?php } ?>