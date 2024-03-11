<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <!-- page header -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">
                                <?php $this->load->view('manage_employer/company_logo_name'); ?> <br />
                                <?php echo $title; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php if ($settings["employeeNameWithRole"]) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="alert alert-info text-medium">
                                The last update to the settings was made by
                                <strong>
                                    <?= $settings["employeeNameWithRole"]; ?>
                                </strong> at
                                <strong>
                                    <?= formatDateToDB(
                                        $settings["updated_at"],
                                        DB_DATE_WITH_TIME,
                                        DATE_WITH_TIME
                                    ); ?>.
                                </strong>
                            </p>
                        </div>
                    </div>
                <?php } ?>
                <!--  -->
                <form action="#" method="post" id="jsAttendanceSettingsForm">
                    <!-- button -->
                    <div class="panel panel-default">
                        <div class="panel-footer text-right">
                            <button class="btn btn-orange jsSubmitButton">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                                Save changes
                            </button>
                        </div>
                    </div>
                    <!-- general -->
                    <div class="panel panel-default" id="jsPanelGeneral">
                        <div class="panel-heading" role="button" data-toggle="collapse" class="accordion-plus-toggle collapsed" data-parent="#jsPanelGeneral" href="#jsBodyGeneral" aria-expanded="true" aria-controls="jsBodyGeneral">
                            <h3 class="text-medium">
                                <strong>
                                    <i class="fa fa-cogs text-orange" aria-hidden="true"></i>
                                    General
                                </strong>
                            </h3>
                        </div>
                        <div class="panel-collapse collapse in" id="jsBodyGeneral" role="tabpanel" aria-labelledby="jsBodyGeneral">
                            <div class="panel-body">
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="general_daily_limit_status" <?= $settings["settings_json"]["general"]["daily_limit"]["status"] ? "checked" : ""; ?> />
                                            <span class="text-medium">
                                                Daily Limit
                                            </span>
                                            <div class="control__indicator"></div>
                                        </label>
                                        <p class="text-medium">
                                            You'll be notified when a limit is exceeded.
                                        </p>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control input-lg" name="general_daily_limit_value" <?= $settings["settings_json"]["general"]["daily_limit"]["status"] ? "" : "disabled"; ?>>
                                            <?= getClockHoursForSettings(
                                                $settings["settings_json"]["general"]["daily_limit"]["value"]
                                            ); ?>
                                        </select>
                                    </div>
                                </div>
                                <br />
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="general_auto_clock_out_status" <?= $settings["settings_json"]["general"]["auto_clock_out"]["status"] ? "checked" : ""; ?> />
                                            <span class="text-medium">
                                                Auto Clock Out
                                            </span>
                                            <div class="control__indicator"></div>
                                        </label>
                                        <p class="text-medium">
                                            Employees exceeding daily limit, will be automatically clocked out.
                                        </p>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control input-lg" name="general_auto_clock_out_value" <?= $settings["settings_json"]["general"]["auto_clock_out"]["status"] ? "" : "disabled"; ?>>
                                            <?= getClockHoursForSettings(
                                                $settings["settings_json"]["general"]["auto_clock_out"]["value"]
                                            ); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- customize -->
                    <div class="panel panel-default" id="jsPanelCustomize">
                        <div class="panel-heading" role="button" data-toggle="collapse" class="accordion-plus-toggle collapsed" data-parent="#jsPanelCustomize" href="#jsBodyCustomize" aria-expanded="true" aria-controls="jsBodyCustomize">
                            <h3 class="text-medium">
                                <i class="fa fa-cogs text-orange" aria-hidden="true"></i>
                                <strong>
                                    Decide how users can track their work time
                                </strong>
                            </h3>
                            <p class="text-medium">
                                Use the toggles below to customize the way your users can track or record their work hours
                            </p>
                        </div>
                        <div class="panel-collapse collapse in" id="jsBodyCustomize" role="tabpanel" aria-labelledby="jsBodyCustomize">
                            <div class="panel-body">
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="employee_can_clock_in" <?= $settings["settings_json"]["controls"]["employee_can_clock_in"] ? "checked" : ""; ?> />
                                            <span class="text-medium">
                                                Users can clock in and out from their compute's time clock
                                            </span>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <br />
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="employee_can_manipulate_time_sheet" <?= $settings["settings_json"]["controls"]["employee_can_manipulate_time_sheet"] ? "checked" : ""; ?> />
                                            <span class="text-medium">
                                                Users can manually request shift/break records to their time sheets.
                                            </span>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- reminders -->
                    <div class="panel panel-default" id="jsPanelReminders">
                        <div class="panel-heading" role="button" data-toggle="collapse" class="accordion-plus-toggle collapsed" data-parent="#jsPanelReminders" href="#jsBodyReminders" aria-expanded="true" aria-controls="jsBodyReminders">
                            <h3 class="text-medium">
                                <i class="fa fa-bell text-orange" aria-hidden="true"></i>
                                <strong>
                                    Reminders
                                </strong>
                            </h3>
                        </div>
                        <div class="panel-collapse collapse in" id="jsBodyReminders" role="tabpanel" aria-labelledby="jsBodyReminders">
                            <div class="panel-body">
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="text-medium">
                                            Reminders active on:
                                        </label>
                                        <br />
                                        <label for="mon" class="csCircleText jsCircleCheckBox <?= in_array("mon", $settings["settings_json"]["reminders"]["days"]) ? "active" : ""; ?>">
                                            M
                                        </label>
                                        <label for="tue" class="csCircleText jsCircleCheckBox <?= in_array("tue", $settings["settings_json"]["reminders"]["days"]) ? "active" : ""; ?>">
                                            T
                                        </label>
                                        <label for="wed" class="csCircleText jsCircleCheckBox <?= in_array("wed", $settings["settings_json"]["reminders"]["days"]) ? "active" : ""; ?>">
                                            W
                                        </label>
                                        <label for="thu" class="csCircleText jsCircleCheckBox <?= in_array("thu", $settings["settings_json"]["reminders"]["days"]) ? "active" : ""; ?>">
                                            T
                                        </label>
                                        <label for="fri" class="csCircleText jsCircleCheckBox <?= in_array("fri", $settings["settings_json"]["reminders"]["days"]) ? "active" : ""; ?>">
                                            F
                                        </label>
                                        <label for="sat" class="csCircleText jsCircleCheckBox <?= in_array("sat", $settings["settings_json"]["reminders"]["days"]) ? "active" : ""; ?>">
                                            S
                                        </label>
                                        <label for="sun" class="csCircleText jsCircleCheckBox <?= in_array("sun", $settings["settings_json"]["reminders"]["days"]) ? "active" : ""; ?>">
                                            S
                                        </label>
                                        <input type="checkbox" name="reminder_days" id="mon" <?= in_array("mon", $settings["settings_json"]["reminders"]["days"]) ? "checked" : ""; ?> autocomplete="off" class="hidden" value="mon" />
                                        <input type="checkbox" name="reminder_days" id="tue" <?= in_array("tue", $settings["settings_json"]["reminders"]["days"]) ? "checked" : ""; ?> autocomplete="off" class="hidden" value="tue" />
                                        <input type="checkbox" name="reminder_days" id="wed" <?= in_array("wed", $settings["settings_json"]["reminders"]["days"]) ? "checked" : ""; ?> autocomplete="off" class="hidden" value="wed" />
                                        <input type="checkbox" name="reminder_days" id="thu" <?= in_array("thu", $settings["settings_json"]["reminders"]["days"]) ? "checked" : ""; ?> autocomplete="off" class="hidden" value="thu" />
                                        <input type="checkbox" name="reminder_days" id="fri" <?= in_array("fri", $settings["settings_json"]["reminders"]["days"]) ? "checked" : ""; ?> autocomplete="off" class="hidden" value="fri" />
                                        <input type="checkbox" name="reminder_days" id="sat" <?= in_array("sat", $settings["settings_json"]["reminders"]["days"]) ? "checked" : ""; ?> autocomplete="off" class="hidden" value="sat" />
                                        <input type="checkbox" name="reminder_days" id="sun" <?= in_array("sun", $settings["settings_json"]["reminders"]["days"]) ? "checked" : ""; ?> autocomplete="off" class="hidden" value="sun" />
                                    </div>
                                </div>
                                <br />
                                <br />

                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="remind_employee_to_clock_in_status" <?= $settings["settings_json"]["reminders"]["remind_employee_to_clock_in"]["status"] ? "checked" : ""; ?> />
                                            <span class="text-medium">
                                                Remind employees to clock in:
                                            </span>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <select name="remind_employee_to_clock_in_value" class="form-control input-lg" <?= $settings["settings_json"]["reminders"]["remind_employee_to_clock_in"]["status"] ? "" : "disabled"; ?>>
                                            <option <?= $settings["settings_json"]["reminders"]["remind_employee_to_clock_in"]["value"] == "15m" ? "selected" : ""; ?> value="15m">15 Minutes prior</option>
                                            <option <?= $settings["settings_json"]["reminders"]["remind_employee_to_clock_in"]["value"] == "30m" ? "selected" : ""; ?> value="30m">30 Minutes prior</option>
                                        </select>
                                    </div>
                                </div>
                                <br />
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="remind_employee_to_clock_out_status" <?= $settings["settings_json"]["reminders"]["remind_employee_to_clock_out"]["status"] ? "checked" : ""; ?> />
                                            <span class="text-medium">
                                                Remind employees to clock out:
                                            </span>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <select name="remind_employee_to_clock_out_value" class="form-control input-lg" <?= $settings["settings_json"]["reminders"]["remind_employee_to_clock_out"]["status"] ? "" : "disabled"; ?>>
                                            <option <?= $settings["settings_json"]["reminders"]["remind_employee_to_clock_out"]["value"] == "15m" ? "selected" : ""; ?> value="15m">15 Minutes after</option>
                                            <option <?= $settings["settings_json"]["reminders"]["remind_employee_to_clock_out"]["value"] == "30m" ? "selected" : ""; ?> value="30m">30 Minutes after</option>
                                        </select>
                                    </div>
                                </div>
                                <br />
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" name="reminders_daily_limit_status" <?= $settings["settings_json"]["reminders"]["daily_limit"]["status"] ? "checked" : ""; ?> />
                                            <span class="text-medium">
                                                Daily Limit
                                            </span>
                                            <div class="control__indicator"></div>
                                        </label>
                                        <p class="text-medium">
                                            Employees will be notified when the limit is exceeded
                                        </p>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control input-lg" name="reminders_daily_limit_value" <?= $settings["settings_json"]["reminders"]["daily_limit"]["status"] ? "" : "disabled"; ?>>
                                            <?= getClockHoursForSettings(
                                                $settings["settings_json"]["reminders"]["daily_limit"]["value"]
                                            ); ?>
                                        </select>
                                    </div>
                                </div>
                                <br />
                            </div>
                        </div>
                    </div>

                    <!-- button -->
                    <div class="panel panel-default">
                        <div class="panel-footer text-right">
                            <button class="btn btn-orange jsSubmitButton">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                                Save changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>