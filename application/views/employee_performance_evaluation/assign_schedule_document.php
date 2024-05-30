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
                Schedule performance evaluation document
            </h2>
        </div>
        <div class="panel-body">
            <div class="row">
                <!-- Selection row -->
                <div class="col-sm-12">
                    <!-- None -->
                    <label class="control control--radio">
                        None &nbsp;&nbsp;
                        <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="none" />
                        <div class="control__indicator"></div>
                    </label>
                    <!-- Daily -->
                    <label class="control control--radio">
                        Daily &nbsp;&nbsp;
                        <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="daily" />
                        <div class="control__indicator"></div>
                    </label>
                    <!-- Weekly -->
                    <label class="control control--radio">
                        Weekly &nbsp;&nbsp;
                        <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="weekly" />
                        <div class="control__indicator"></div>
                    </label>
                    <!-- Monthly -->
                    <label class="control control--radio">
                        Monthly &nbsp;&nbsp;
                        <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="monthly" />
                        <div class="control__indicator"></div>
                    </label>
                    <!-- Yearly -->
                    <label class="control control--radio">
                        Yearly &nbsp;&nbsp;
                        <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="yearly" />
                        <div class="control__indicator"></div>
                    </label>
                    <!-- Custom -->
                    <label class="control control--radio">
                        Custom &nbsp;&nbsp;
                        <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="custom" />
                        <div class="control__indicator"></div>
                    </label>
                    <!--  -->
                </div>
            </div>
            <!--  -->
            <div class="row">
                <!-- Custom date row -->
                <div class="col-sm-12 jsCustomDateRow">
                    <br />
                    <label id="jsScheduleCustomLabel">Select a date & time</label>
                    <div class="row">
                        <div class="col-sm-4" id="jsScheduleCustomDate">
                            <input type="text" class="form-control jsScheduleDate" readonly="true" />
                        </div>
                        <div class="col-sm-3" id="jsScheduleCustomDay">
                            <select class="form-control" id="jsScheduleCustomDaySLT">
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                                <option value="6">Saturday</option>
                                <option value="7">Sunday</option>
                            </select>
                        </div>
                        <div class="col-sm-4 nopaddingleft">
                            <input type="text" class="form-control jsScheduleTime" readonly="true" />
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <hr />
                </div>
            </div>
            <!--  -->
            <div class="row">
                <!-- Against Selected Employees -->
                <div class="col-sm-12">
                    <label>Employee(s)</label>
                    <select multiple="true" name="assignAndSendSelectedEmployees[]" class="jsAssignSelectedEmployees">
                        <option value="-1">All</option>
                        <?php foreach ($employees as $key => $employee) { ?>
                            <option value="<?= $employee['sid']; ?>"><?= remakeEmployeeName($employee); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="panel-footer text-right">
            <button class="btn btn-orange jsSaveScheduleSetting">
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