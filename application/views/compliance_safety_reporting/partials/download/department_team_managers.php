<!-- Department and team manager section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="4">
                <strong>Department/Team Visibility</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($managerList)) { ?>
            <tr>
                <th class="text-center">Name</th>
                <th class="text-center">Email</th>
                <th class="text-center">Departments</th>
                <th class="text-center">Teams</th>
            </tr>
            <?php foreach ($managerList as $manager) { ?>
                <tr>
                    <?php 
                        $allowedDepartments = isset($manager['departments']) ? implode(',', $manager['departments']) : 'N/A';
                        $allowedTeams = isset($manager['teams']) ? implode(',', $manager['teams']) : 'N/A';
                    ?>
                    <td class="text-center"><?php echo !empty($manager['name']) ? $manager['name'] : 'N/A'; ?></td>
                    <td class="text-center"><?php echo !empty($manager['email']) ? $manager['email'] : 'N/A'; ?></td>
                    <td class="text-center"><?php echo $allowedDepartments; ?></td>
                    <td class="text-center"><?php echo $allowedTeams; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No Manager Found</h2>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!-- Department and team manager section End -->