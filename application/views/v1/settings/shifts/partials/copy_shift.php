<form action="javascript:void(0)" id="jsPageCreateSingleShiftForm" autocomplete="off">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text weight-6">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    From Cycle
                </h2>
            </div>

            <div class="panel-body" style="min-height: 100px;">
                <div class="row form-group">
                    <div class="col-sm-4">
                        <label class="text-medium">
                            From Date
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control datepicker hasDatepicker" name="last_shift_date_from" id="last_shift_date_from" />
                    </div>
                    <div class="col-sm-4">
                        <label class="text-medium">
                            To Date
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control" name="last_shift_date_to" id="last_shift_date_to" />
                    </div>
                </div>
            </div>

            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text weight-6">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    To Cycle
                </h2>
            </div>

            <div class="panel-body" style="min-height: 100px;">
                <div class="row form-group">
                    <div class="col-sm-4">
                        <label class="text-medium">
                            From Date
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control datepicker hasDatepicker" name="shift_date_from" id="shift_date_from" />
                    </div>
                    <div class="col-sm-4">
                        <label class="text-medium">
                            To Date
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control" name="shift_date_to" id="shift_date_to" />
                    </div>

                </div>

                <div class="row form-group">
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label class="control control--checkbox">
                                <input type="checkbox" name="job_dayoff" value="1" />
                                Mark Day Off as Working Day
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    Employees
                </h2>
            </div>
            <div class="panel-body">

                <!--  -->
                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-orange jsSelectAll" type="button">
                            Select all
                        </button>
                        <button class="btn btn-black jsRemoveAll" type="button">
                            Clear all
                        </button>
                    </div>
                </div>

                <hr>

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
                                <input type="checkbox" class="jsPageApplyTemplateEmployees" value="<?= $employee["userId"]; ?>" name="employees[]" />
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
        </div>

        <!--  -->
        <div class="panel-footer text-right">
            <button class="btn btn-orange jsPageCreateSingleShiftBtn">
                <i class="fa fa-copy" aria-hidden="true"></i>
                &nbsp;Copy Shift
            </button>
            <button class="btn btn-black jsModalCancel" type="button">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
        </div>

</form>

<script>
    breaksObject = <?= json_encode($breaks); ?>
</script>