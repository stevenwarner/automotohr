<form action="javascript:void(0)" id="jsPageTradeShiftForm" autocomplete="off">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-user text-orange" aria-hidden="true"></i>
                    Employees
                </h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Employees
                    </label>
                    <select name="employees" class="form-control multipleSelect" id="employees">
                        <option value="0" selected="selected">Please select an employee</option>
                        <?php if ($employees) {
                            foreach ($employees as $employee) {
                        ?>
                                <option value="<?= $employee["userId"]; ?>" <?php echo in_array($employee["userId"],$employeesOnLeave)?"disabled":""?>><?= remakeEmployeeName($employee); ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="panel-footer text-right">
            <button class="btn btn-orange jsPageCreateSingleShiftBtn">
                <i class="fa fa-exchange" aria-hidden="true"></i>
                &nbsp;Swap Shifts
            </button>
            <button class="btn btn-black jsModalCancel" type="button">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
        </div>
    </div>
</form>