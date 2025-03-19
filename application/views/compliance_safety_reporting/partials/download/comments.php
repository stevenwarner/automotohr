<!-- Report Notes section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="2">
                <strong>Note(s)</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if ($notes) { ?>
            <?php foreach ($notes as $note) { ?>
                <tr>
                    <td>
                        <label>
                            <strong>Name :</strong>

                        </label>
                        <span>
                            <?php echo $note['first_name']. ' ' .$note['last_name']; ?>
                        </span>

                    </td>
                    <td>
                        <label>
                            <strong>Date :</strong>
                        </label>
                        <span>
                            <time><?php echo formatDateToDB($note['created_at'], DB_DATE_WITH_TIME, DATE); ?></time>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label>
                            <strong>note :</strong>
                        </label>
                        <span class="value-box bg-gray">
                            <?php echo strip_tags($note['notes']); ?>
                        </span>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No Note Found</h2>
                    </div>
                </td>
            </tr>
        <?php } ?>    
    </tbody>
</table>
<!-- Report Notes section End -->