<!-- Question section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="2">
                <strong>Question Answer</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $decodedJSON = json_decode(
            $questions,
            true
        );
        //
        $report_to_dashboard = empty($decodedJSON['report_to_dashboard']) ? 'no' : $decodedJSON['report_to_dashboard'];
        $ongoing_issue = empty($decodedJSON['ongoing_issue']) ? 'no' : $decodedJSON['ongoing_issue'];
        $reported_by = empty($decodedJSON['reported_by']) ? 'no' : $decodedJSON['reported_by'];
        $category_of_issue = empty($decodedJSON['category_of_issue']) ? '' : $decodedJSON['category_of_issue'];
        ?>
        <tr>
            <td colspan="3">
                <label>
                    <strong>Report to Dashboard</strong>
                </label>
                <span class="value-box bg-gray">
                    <?php echo $report_to_dashboard == 'yes' ? 'YES' : 'NO'; ?>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>
                    <strong>Is this a Repeat or Ongoing Issue?</strong>
                </label>
                <span class="value-box bg-gray">
                    <?php echo $ongoing_issue == 'yes' ? 'YES' : 'NO'; ?>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>
                    <strong>Was this reported by an employee?</strong>
                </label>
                <span class="value-box bg-gray">
                    <?php echo $reported_by == 'yes' ? 'YES' : 'NO'; ?>
                </span>
            </td>
        </tr>    
        <tr>
            <td colspan="3">
                <label>
                    <strong>Category of issue:</strong>
                </label>
                <span class="value-box bg-gray">
                    <?= $category_of_issue ?>
                </span>
            </td>
        </tr>  
    </tbody>
</table>
<!-- Question section End -->