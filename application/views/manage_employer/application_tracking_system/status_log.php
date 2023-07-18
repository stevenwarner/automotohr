<?php if (!empty($data)) {

    foreach ($data as $row) {
        $status = json_decode($row['status']);
?>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-primary">
                    <th colspan="2"><span class="pull-right"><?php echo formatDateToDB($row['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);  ?></span> <?php echo getUserNameBySID($row['created_by']); ?> </th>
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
                <th><span>Record not found!</span> </th>
            </tr>
        </thead>

    </table>
<?php } ?>