<!-- External Employees section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="4">
                <strong>External Employee(s)</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($internalEmployees)) { ?>
            <tr>
                <th class="text-center">Name</th>
                <th class="text-center">Email</th>
                <th class="text-center">Added By</th>
                <th class="text-center">Added Date</th>
            </tr>
            <?php foreach ($internalEmployees as $externalEmployee) { ?>
                <tr>
                    <td class="text-center"><?php echo $externalEmployee['external_name']; ?></td>
                    <td class="text-center"><?php echo $externalEmployee['external_email']; ?></td>
                    <td class="text-center"><?php echo getEmployeeOnlyNameBySID($externalEmployee['created_by']); ?></td>
                    <td class="text-center"><?php echo formatDateToDB($externalEmployee['created_at'], DB_DATE_WITH_TIME, DATE); ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No External Employee Found</h2>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!-- External Employees section End -->