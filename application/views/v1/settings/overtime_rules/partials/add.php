<form action="javascript:void(0)" id="jsPageOvertimeRuleForm">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    Add Overtime Rule
                </h2>
            </div>
            <div class="panel-body">
                <!--  -->
                <div class="row form-group">
                    <div class="col-sm-12">
                        <label class="text-medium">
                            Name
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control" name="rule_name" />
                    </div>
                </div>
                <!--  -->
                <div class="row form-group">
                    <div class="col-sm-3 col-xs-12">
                        <label class="text-medium">
                            Overtime rate
                            <strong class="text-red">*</strong>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="overtime_multiplier" placeholder="1.5" />
                            <div class="input-group-addon">times</div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <label class="text-medium">
                            Double time rate
                            <strong class="text-red">*</strong>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="double_overtime_multiplier" placeholder="2" />
                            <div class="input-group-addon">times</div>
                        </div>
                    </div>
                </div>
                <br />

                <!--  -->
                <div id="collapseArea">
                    <!-- daily collapse -->
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" data-parent="#collapseArea" href="#dailyCollapse" aria-expanded="false" aria-controls="dailyCollapse">
                            <h2 class="text-medium panel-heading-text">
                                Daily
                            </h2>
                        </div>
                        <div class="panel-body collapse" id="dailyCollapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                Day
                                            </th>
                                            <th scope="col">
                                                Overtime
                                            </th>
                                            <th scope="col">
                                                Double time
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"] as $v0) { ?>
                                            <tr>
                                                <td class="csVerticalAlignMiddle">
                                                    <h2 class="text-medium panel-heading-text">
                                                        <?= ucwords($v0); ?>
                                                    </h2>
                                                </td>
                                                <td class="csVerticalAlignMiddle">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <label class="control control--checkbox mt-5">
                                                                <input type="checkbox" name="<?= $v0; ?>_overtime_status" class="jsAddChangeStatus" data-target="<?= $v0; ?>_overtime_hours" /> after
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="<?= $v0; ?>_overtime_hours" disabled />
                                                                <div class="input-group-addon">hours</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="csVerticalAlignMiddle">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <label class="control control--checkbox mt-5">
                                                                <input type="checkbox" name="<?= $v0; ?>_double_status" class="jsAddChangeStatus" data-target="<?= $v0; ?>_double_hours" /> after
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="<?= $v0; ?>_double_hours" disabled />
                                                                <div class="input-group-addon">hours</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="collapseArea2">
                    <!-- Weekly collapse -->
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" data-parent="#collapseArea2" href="#weeklyCollapse" aria-expanded="false" aria-controls="weeklyCollapse">
                            <h2 class="text-medium panel-heading-text">
                                Weekly
                            </h2>
                        </div>
                        <div class="panel-body collapse " id="weeklyCollapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="row form-group">
                                <div class="col-sm-3">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="weekly_overtime_status" class="jsAddChangeStatus" data-target="weekly_overtime_hours" />
                                        Overtime after
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="weekly_overtime_hours" disabled />
                                        <div class="input-group-addon">hours</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-3">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="weekly_double_status" class="jsAddChangeStatus" data-target="weekly_double_hours" />
                                        Double time after
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="weekly_double_hours" disabled />
                                        <div class="input-group-addon">hours</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="collapseArea3">
                    <!-- Consecutive collapse -->
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" data-parent="#collapseArea3" href="#consecutiveCollapse" aria-expanded="false" aria-controls="consecutiveCollapse">
                            <h2 class="text-medium panel-heading-text">
                                On 7th Consecutive Day
                            </h2>
                        </div>
                        <div class="panel-body collapse" id="consecutiveCollapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="row form-group">
                                <div class="col-sm-3">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="consecutive_overtime_status" class="jsAddChangeStatus" data-target="consecutive_overtime_hours" />
                                        Overtime after
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="consecutive_overtime_hours" disabled />
                                        <div class="input-group-addon">hours</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-3">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="consecutive_double_status" class="jsAddChangeStatus" data-target="consecutive_double_hours" />
                                        Double time after
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="consecutive_double_hours" disabled />
                                        <div class="input-group-addon">hours</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="collapseArea4">
                    <!-- holidays collapse -->
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" data-parent="#collapseArea4" href="#holidaysCollapse" aria-expanded="false" aria-controls="holidaysCollapse">
                            <h2 class="text-medium panel-heading-text">
                                On holidays
                            </h2>
                        </div>
                        <div class="panel-body collapse" id="holidaysCollapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="row form-group">
                                <div class="col-sm-3">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="holidays_overtime_status" class="jsAddChangeStatus" data-target="holidays_overtime_hours" />
                                        Overtime after
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="holidays_overtime_hours" disabled />
                                        <div class="input-group-addon">hours</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-3">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="holidays_double_status" class="jsAddChangeStatus" data-target="holidays_double_hours" />
                                        Double time after
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="holidays_double_hours" disabled />
                                        <div class="input-group-addon">hours</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="panel-footer text-right">
                <button class="btn btn-orange jsPageOvertimeRuleBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Save Overtime Rule
                </button>
                <button class="btn btn-black jsModalCancel" type="button">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    &nbsp;Cancel
                </button>
            </div>
        </div>

    </div>
</form>