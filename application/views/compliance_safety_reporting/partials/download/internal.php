<!-- Internal Employees section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="5">
                <strong>Internal Employee(s)</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($internalEmployees)) { ?>
            <tr>
                <th class="text-center">Name</th>
                <th class="text-center">Phone</th>
                <th class="text-center">Email</th>
                <th class="text-center">Added By</th>
                <th class="text-center">Added Date</th>
            </tr>
            <?php foreach ($internalEmployees as $internalEmployee) { ?>
                <tr>
                    <?php 
                        $employeeInfo = getEmployeeBasicInfo($internalEmployee['employee_sid']);
                        $addedBy = getEmployeeOnlyNameBySID($internalEmployee['created_by']);
                    ?>
                    <td class="text-center"><?php echo !empty($employeeInfo['name']) ? $employeeInfo['name'] : 'N/A'; ?></td>
                    <td class="text-center"><?php echo !empty($employeeInfo['phone']) ? $employeeInfo['phone'] : 'N/A'; ?></td>
                    <td class="text-center"><?php echo !empty($employeeInfo['email']) ? $employeeInfo['email'] : 'N/A'; ?></td>
                    <td class="text-center"><?php echo $addedBy; ?></td>
                    <td class="text-center"><?php echo formatDateToDB($internalEmployee['created_at'], DB_DATE_WITH_TIME, DATE); ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No Internal Employee Found</h2>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!-- Internal Employees section End -->