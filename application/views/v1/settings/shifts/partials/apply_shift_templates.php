<form action="javascript:void(0)" id="jsPageApplyShiftTemplateForm">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    Shift templates
                </h2>
            </div>
            <div class="panel-body">
                <?php if ($templates) {
                    $counter = 1;
                    foreach ($templates as $template) {
                        if ($counter == 1) {
                            echo '<div class="row">';
                        }
                ?>
                        <div class="col-sm-4">
                            <div class="schedule-item schedule-item-selectable bg-default csRelative jsSelectScheduleTemplate" data-id="<?= $template["sid"]; ?>">
                                <?php if ($template['geo_fence']) { ?>
                                    <span class="circle circle-orange" title="Geo fence" placement="top"></span>
                                <?php } ?>
                                <p class="text-small text-black">
                                    <?= formatDateToDB($template["start_time"], "H:i:s", "h:i a"); ?> -
                                    <?= formatDateToDB($template["end_time"], "H:i:s", "h:i a"); ?>
                                    <?= $template["break_count"] ? "(" . ($template["break_count"]) . ")" : ""; ?>
                                </p>
                            </div>
                        </div>
                <?php

                        if ($counter === 3) {
                            echo '</div><br />';
                            $counter = 1;
                        } else {
                            $counter++;
                        }
                    }
                }
                ?>
            </div>

        </div>

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
                                <input type="checkbox" class="jsPageApplyTemplateEmployees" value="<?= $employee["userId"]; ?>" />
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

        <div class="panel-footer text-right">
            <button class="btn btn-orange jsPageApplyShiftTemplateBtn">
                <i class="fa fa-save" aria-hidden="true"></i>
                &nbsp;Apply Shifts To The Selected Employees
            </button>
            <button class="btn btn-black jsModalCancel" type="button">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
        </div>
    </div>
</form>