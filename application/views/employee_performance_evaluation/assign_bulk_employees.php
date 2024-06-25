<style>
    .jsSectionOne:nth-child(even) {
        background: #eee;
    }

    textarea {
        resize: none;
    }
</style>
<br />
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="text-medium panel-heading-text">
                <i class="fa fa-users text-orange" aria-hidden="true"></i>
                Select employee to send performance evaluation document
            </h2>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn csW csBG3 csF16 jsSelectAll">
                        Select all
                    </button>
                    <button class="btn csW csBG4 csF16 jsUnSelectAll">
                        Remove all
                    </button>
                </div>
            </div>
            <hr />
            <?php if ($employees) {
                $counter = 1;
                foreach ($employees as $employee) {
                    if ($counter == 1) {
                        echo '<div class="row">';
                    }
            ?>
                    <!--  -->
                    <div class="col-sm-6">
                        <label class="control control--checkbox">
                            <input type="checkbox" class="jsAssignBulkEmployees" value="<?= $employee["sid"]; ?>" name="employees[]" />
                            <?= remakeEmployeeName($employee); ?>
                            <div class="control__indicator"></div>
                        </label>
                    </div>

            <?php
                    if ($counter === 2) {
                        echo '</div><br />';
                        $counter = 1;
                    } else {
                        $counter++;
                    }
                }
            } ?>
        </div>

        <div class="panel-footer text-right">
            <button class="btn btn-orange jsAssignBulkDocument">
                <i class="fa fa-users" aria-hidden="true"></i>
                &nbsp;Assign Employees
            </button>
            <button class="btn btn-black jsModalCancel" type="button">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
        </div>
    </div>
</div>