<div>
    <table style="border: thin solid grey; border-collapse: collapse; text-align: left; width: 100%; background-color: rgba(255,255,255, .5); ">
        <tbody>
            <tr style="border: thin solid grey; border-collapse: collapse;">
                <th style="padding:5px; border: thin solid grey; border-collapse: collapse; width: 25%; text-align: left; background-color: lightgrey;">Scheduled By</th>
                <td style="padding:5px; border: thin solid grey; border-collapse: collapse; text-align: left;">
                    <span><?php echo $schedule['created_by_first_name'] . ' ' . $schedule['created_by_last_name']; ?> ( <?php echo $schedule['created_by_email']; ?> )</span>
                </td>
            </tr>
            <tr style="border: thin solid grey; border-collapse: collapse;">
                <th style="padding:5px; border: thin solid grey; border-collapse: collapse; width: 25%; text-align: left; background-color: lightgrey;">Task</th>
                <td style="padding:5px; border: thin solid grey; border-collapse: collapse; text-align: left;"><?php echo $schedule['schedule_type']; ?></td>
            </tr>
            <tr style="border: thin solid grey; border-collapse: collapse;">
                <th style="padding:5px; border: thin solid grey; border-collapse: collapse; width: 25%; text-align: left; background-color: lightgrey;">Date and Time</th>
                <td style="padding:5px; border: thin solid grey; border-collapse: collapse; text-align: left;"><?php echo date('m/d/Y @ h:i A', strtotime($schedule['schedule_datetime'])); ?></td>
            </tr>
            <tr style="border: thin solid grey; border-collapse: collapse;">
                <th style="padding:5px; border: thin solid grey; border-collapse: collapse; width: 33%; text-align: left; background-color: lightgrey;">Client</th>
                <td style="padding:5px; border: thin solid grey; border-collapse: collapse; text-align: left;"><?php echo $schedule['fdr_first_name'] . ' ' . $schedule['fdr_last_name']?></td>
            </tr>
            <tr style="border: thin solid grey; border-collapse: collapse;">
                <th style="padding:5px; border: thin solid grey; border-collapse: collapse; width: 25%; text-align: left; background-color: lightgrey;">Email</th>
                <td style="padding:5px; border: thin solid grey; border-collapse: collapse; text-align: left;"><?php echo $schedule['fdr_email']?></td>
            </tr>
            <tr style="border: thin solid grey; border-collapse: collapse;">
                <th style="padding:5px; border: thin solid grey; border-collapse: collapse; width: 25%; text-align: left; background-color: lightgrey;">Job Role</th>
                <td style="padding:5px; border: thin solid grey; border-collapse: collapse; text-align: left;"><?php echo $schedule['fdr_job_role']?></td>
            </tr>
            <tr>
                <th style="padding:5px; border: thin solid grey; border-collapse: collapse; width: 25%; text-align: left; background-color: lightgrey;">Phone</th>
                <td style="padding:5px; border: thin solid grey; border-collapse: collapse; text-align: left;"><?php echo $schedule['fdr_phone_number']?></td>
            </tr>
            <tr style="border: thin solid grey; border-collapse: collapse;">
                <th style="padding:5px; border: thin solid grey; border-collapse: collapse;width: 25%; text-align: left; background-color: lightgrey;">Company</th>
                <td style="padding:5px; border: thin solid grey; border-collapse: collapse; text-align: left;"><?php echo $schedule['fdr_company_name']?></td>
            </tr>
            <tr style="border: thin solid grey; border-collapse: collapse;">
                <th style="padding:5px; border: thin solid grey; border-collapse: collapse; width: 25%; text-align: left; background-color: lightgrey;">Task Description</th>
                <td style="padding:5px; border: thin solid grey; border-collapse: collapse; text-align: left;"><?php echo $schedule['schedule_description']?></td>
            </tr>
        </tbody>
    </table>
</div>