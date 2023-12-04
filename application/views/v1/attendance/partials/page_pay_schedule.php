<div class="container">
    <form action="javascript:void(0)" id="jsPagePayScheduleForm">
        <!--  -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-edit text-orange" aria-hidden="true"></i>
                    Manage pay schedule
                </h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="text-medium">
                        Pay Schedule
                        <strong class="text-red">
                            *
                        </strong>
                    </label>
                    <select name="pay_schedule" class="form-control">
                        <option value="">[Please select a pay schedule]</option>
                        <?php if ($companyPaySchedules) {
                            foreach ($companyPaySchedules as $v0) {
                        ?>
                                <option value="<?= $v0["sid"]; ?>"><?= $v0["custom_name"] ?? $v0["frequency"]; ?></option>
                        <?php
                            }
                        } ?>
                    </select>
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-orange jsPagePayScheduleBtn">
                    <i class="fa fa-edit" aria-hidden="true"></i>
                    Update
                </button>
                <button class="btn btn-black jsModalCancel">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    Cancel
                </button>
            </div>
        </div>
    </form>
</div>