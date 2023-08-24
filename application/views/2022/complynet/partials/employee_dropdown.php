<select id="jsCIComplyNetEmployees" style="width: 100%;">
    <option value="0"> Add A New Employee </option>
    <?php foreach ($employeeData as $row) { ?>
        <option value="<?php echo $row['UserName']; ?>"><?php echo $row['FirstName'] . ' ' . $row['LastName'] . ' [' . $row['Email'] . '] ' ?> </option>
    <?php } ?>
</select>