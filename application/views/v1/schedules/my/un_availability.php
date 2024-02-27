<form action="javascript:void(0)" id="jsPageCreateSingleShiftForm" autocomplete="off">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text weight-6">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    Create Employee Shift
                </h2>
            </div>
            <div class="panel-body">
                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
                        Employee
                        <strong class="text-red">*</strong>
                    </label>
                    <select name="shift_employee" class="form-control">
                        <?php if ($employees) {
                            foreach ($employees as $v0) {
                        ?>
                                <option value="<?= $v0["userId"]; ?>"><?= remakeEmployeeName($v0); ?></option>

                        <?php
                            }
                        } ?>
                    </select>
                </div>
                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
                        Date
                        <strong class="text-red">*</strong>
                    </label>
                    <input type="text" class="form-control" readonly name="shift_date" />
                </div>

                <!--  -->
                <div class="row form-group">
                    <div class="col-sm-4">
                        <label class="text-medium">
                            Start Time
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control jsTimeField" name="start_time" placeholder="HH:MM" />
                    </div>
                    <div class="col-sm-4">
                        <label class="text-medium">
                            End Time
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control jsTimeField" name="end_time" placeholder="HH:MM" />
                    </div>
                </div>

                <?php if ($jobSites) {?>
                <div class="form-group">
                    <label class="text-medium">
                        Job Sites
                    </label>
                    <br>
                    <div class="row">
                        <?php
                            foreach ($jobSites as $v0) {
                        ?>
                                <div class="col-sm-4">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="job_sites[]" value="<?= $v0["sid"]; ?>" />
                                        <?= $v0["site_name"]; ?>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <?php }?>

                <!--  -->
                <div class="form-group jsBreakContainer"></div>

                <!--  -->
                <div class="form-group">
                    <br>
                    <button class="btn btn-orange jsAddBreak">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        Add Break
                    </button>
                </div>

                <!--  -->
                <div class="form-group">
                    <br>
                    <label class="text-medium">
                        Note
                    </label>
                    <textarea name="notes" rows="5" class="form-control"></textarea>
                </div>
            </div>
            <!--  -->
            <div class="panel-footer text-right">
                <button class="btn btn-orange jsPageCreateSingleShiftBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Save Shift
                </button>
                <button class="btn btn-black jsModalCancel" type="button">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    &nbsp;Cancel
                </button>
            </div>
        </div>
    </div>
</form>