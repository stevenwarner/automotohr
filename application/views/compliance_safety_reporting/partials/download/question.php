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
        <?php if ($questions) { ?>
            <?php foreach ($questions as $question) { ?>
                <?php if (!empty($question['question'])) { ?>
                    <tr>
                        <td colspan="3">
                            <label>
                                <strong><?php echo 'Question: '.$question['question']; ?></strong>
                            </label>
                            <span class="value-box bg-gray">
                                <?php
                                    $ans = @unserialize($question['answer']);
                                    if ($ans !== false) {
                                        echo implode(', ', $ans);
                                    } else {
                                        echo ucfirst($question['answer']);
                                    }
                                ?>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        <?php } else if ($answers_json) { ?> 
            <?php
                $decodedJSON = json_decode(
                    $answers_json,
                    true
                );
                //
                $departments = empty($decodedJSON['departments']) ? [] : $decodedJSON['departments'];
                $compliance_date = empty($decodedJSON['compliance_date']) ? '' : $decodedJSON['compliance_date'];
                //
                $allowedDepartments = '';
                //
                if ($departments) {
                    foreach ($departments as $departmentId) {
                        if ($allowedDepartments) {
                            $allowedDepartments .= ', '.getDepartmentNameBySID($departmentId);
                        } else {
                            $allowedDepartments .= getDepartmentNameBySID($departmentId);
                        }
                        
                    }
                }    

            ?>

            <tr>
                <td colspan="3">
                    <label>
                        <strong>Department Safety Condition exists</strong>
                    </label>
                    <span class="value-box bg-gray">
                        <?php echo $allowedDepartments; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <label>
                        <strong>Original Date of Non-Compliance</strong>
                    </label>
                    <span class="value-box bg-gray">
                        <?php echo $compliance_date; ?>
                    </span>
                </td>
            </tr>

        <?php } else { ?> 
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No Questions Found</h2>
                    </div>
                </td>
            </tr> 
        <?php } ?>  
    </tbody>
</table>
<!-- Question section End -->