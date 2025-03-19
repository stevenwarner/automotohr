<!-- Incident section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="4">
                <strong>Incident(s)</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($incidents)) { ?>
            <?php 
                $documentLinks = [];
            ?>
            <tr>
                <th class="text-center">Type</th>
                <th class="text-center">Status</th>
                <th class="text-center">Completed Date</th>
                <th class="text-center">Created By</th>
            </tr>
            <?php foreach ($incidents as $incident) { ?>
                <tr>
                    <td class="text-center"><?php echo $incident['compliance_incident_type_name']; ?></td>
                    <td class="text-center"><?php echo ucwords($incident['status']); ?></td>
                    <td class="text-center"><?php echo !empty($incident['completed_at']) ? formatDateToDB($incident['completed_at'], DB_DATE_WITH_TIME, DATE) : 'N/A'; ?></td>
                    <td class="text-center"><?php echo $incident['first_name'] . ' ' . $incident['last_name']; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No incident Found</h2>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!-- Incident section End -->