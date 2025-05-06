<div class="container">
    <form action="javascript:void(0)" id="jsPageCreateSingleShiftForm" autocomplete="off">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6">
                        <h2 class="text-medium panel-heading-text weight-6">
                            <i class="fa fa-save text-orange" aria-hidden="true"></i>
                            Edit Open Shift
                        </h2>
                    </div>

                </div>
            </div>
            <div class="panel-body">
                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
                        Date
                        <strong class="text-red">*</strong>
                    </label>

                    <input type="hidden" class="form-control" name="id" value="<?php echo $shift['sid']; ?>" />
                    <input type="text" class="form-control" readonly name="shift_date" value="<?php echo date("m/d/Y", strtotime($shift['shift_date'])) ?>" />
                </div>


                <!--  -->
                <div class="row form-group">
                    <div class="col-sm-4">
                        <label class="text-medium">
                            Start Time
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control jsTimeField" name="start_time" placeholder="HH:MM" value="<?php echo formatDateToDB($shift['start_time'], "H:i:s", "h:i A"); ?>" />
                    </div>
                    <div class="col-sm-4">
                        <label class="text-medium">
                            End Time
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control jsTimeField" name="end_time" placeholder="HH:MM" value="<?php echo formatDateToDB($shift['end_time'], "H:i:s", "h:i A"); ?>" />
                    </div>
                </div>

                <?php if ($jobSites) { ?>
                    <div class="form-group">
                        <label class="text-medium">
                            Job Sites
                        </label>
                        <br>
                        <div class="row">
                            <?php
                            $selectedJobSotes = [];
                            $selectedJobSotes = json_decode($shift['job_sites']);
                            foreach ($jobSites as $v0) {
                            ?>
                                <div class="col-sm-4">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="job_sites[]" value="<?= $v0["sid"]; ?>" <?php echo in_array($v0["sid"], $selectedJobSotes) ? "checked" : "" ?> />
                                        <?= $v0["site_name"]; ?>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <!--  -->
                <div class="form-group jsBreakContainer">
                    <?
                    $savedBreaks = json_decode($shift['breaks_json'], true);
                    ?>
                    <?php
                    if (!empty($savedBreaks)) {
                        foreach ($savedBreaks as $key => $breakRow) {
                    ?>

                            <div class="row jsBreakRow" data-key="<?php echo $key; ?>"> <br>
                                <div class="col-sm-5"> <label class="text-medium"> Break <strong class="text-red">*</strong> </label>
                                    <select name="breaks[<?php echo $key; ?>][break]" class="form-control jsBreakSelect">

                                        <option></option>
                                        <?php foreach ($breaks as $brak) { ?>
                                            <option value="<?php echo $brak['break_name']; ?>" data-duration="<?php echo $brak['break_duration']; ?>" <?php echo $brak['break_name'] == $breakRow['break'] ? ' selected ' : '' ?>><?php echo $brak['break_name'] . '(' . $brak['break_type'] . ')'; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-3"> <label class="text-medium"> Duration <strong class="text-red">*</strong> </label>
                                    <div class="input-group"> <input type="number" class="form-control jsDuration" name="breaks[<?php echo $key; ?>][duration]" value="<?php echo $breakRow['duration'] ?>">
                                        <div class="input-group-addon">mins</div>
                                    </div>
                                </div>
                                <div class="col-sm-3"> <label class="text-medium"> Start TIme </label> <input type="text" class="form-control jsTimeField jsStartTime" placeholder="HH:MM" name="breaks[<?php echo $key; ?>][start_time]" value="<?php echo formatDateToDB($breakRow['start_time'], "H:i", "h:i A"); ?>"> </div>
                                <div class="col-sm-1"> <br> <button class="btn btn-red jsDeleteBreakRow" title="Delete this break" type="button"> <i class="fa fa-trash" style="margin-right: 0"></i> </button> </div>
                            </div>
                    <?php }
                    } ?>

                </div>

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
                    <textarea name="notes" rows="5" class="form-control"><?php echo $shift['notes']; ?></textarea>
                </div>
                <div class="form-group">
                    <br>
                    <label class="text-medium">
                        Number Of Employees Can Claim
                    </label>
                    <input type="text" class="form-control" name="claim_limit" placeholder="0" value="<?php echo $shift['claim_limit']; ?>" />
                </div>

                <div class="form-group">
                    <label class="text-medium">
                        Number Of Employees Already Claimed [<?php echo $shift['claimed']; ?>]
                    </label>
                </div>

                <div class="form-group">
                    <label class="control control--checkbox">
                        <input type="checkbox" name="employee_can_claim" value="1" <?php echo $shift['employee_can_claim'] ? 'checked' : '' ?> />
                        Employee Can Claim Shift
                        <div class="control__indicator"></div>
                    </label><br>
                    <label class="control control--checkbox">
                        <input type="checkbox" name="employee_need_approval_for_claim" value="1" <?php echo $shift['employee_need_approval_for_claim'] ? 'checked' : '' ?> />
                        Employee Need Approval For Claim Shift
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
            <!--  -->


            <?php
            $todayDate = date('Y-m-d');
            $pastDate = 0;
            if (strtotime($shift['shift_date']) < strtotime($todayDate)) {
                $pastDate = 1;
            }
            ?>

            <div class="panel-footer text-right">
                <?php if (!empty($openRequests)) { ?>
                    <button class="btn btn-orange" type="button" data-toggle="collapse" data-target="#demo">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                        &nbsp;Claim Requests
                    </button>
                <?php } ?>


                <button class="btn btn-orange jsPageCreateSingleShiftBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update Shift
                </button>
                <button class="btn btn-black jsModalCancel" type="button">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    &nbsp;Cancel
                </button>

            </div>
        </div>
    </form>

    <?php if (!empty($openRequests)) { ?>
        <div class="table-responsive collapse" id="demo">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2 class="text-medium panel-heading-text weight-6">
                                Requests
                            </h2>
                        </div>

                    </div>
                </div>
                <div class="panel-body">

                    <div class="col-sm-12 text-right" id="jsApprovedRejectBtn" style="padding-right: -50px;">
                        <button class="btn btn-red jsAdminRejectOpenShiftRequests">
                            Reject requests
                        </button>

                        <table class="table table-bordered table-hover table-striped">
                            <thead style="background-color: #fd7a2a;" class="js-table-head">


                    </div>
                    <tr>
                        <th style="width:5%">
                            <label class="control control--checkbox" style="margin-bottom: 20px;">
                                <input type="checkbox" name="checkit[]" id="check_all">
                                <div class="control__indicator"></div>
                            </label>
                        </th>
                        <th style="width:25%">Employee</th>
                        <th style="width:10%">Requested At</th>
                        <th style="width:20%;padding-right: 30px;" class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody id="js-data-area">
                        <?php
                        foreach ($openRequests as $requestRow) {
                        ?>

                            <tr id="rowId<?php echo $requestRow['shift_sid'] ?>">
                                <td class="text-left">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" name="checkit[]" value="<?php echo $requestRow['shift_sid']; ?>" class="my_checkbox" data-status="">
                                        <div class="control__indicator"></div>
                                    </label>
                                </td>
                                <td class="text-left"><?php echo $requestRow['from_employee'] ?> </td>
                                <td class="text-left"><?php echo $requestRow['created_at'] ?></td>

                                <td>
                                    <div class="col-sm-12 text-right">
                                        <button class="btn btn-red jsAdminRejectOpenShiftRequest" data-shiftid="<?php echo $requestRow['shift_sid'] ?>" data-toemployeeid="<?php echo $requestRow['employee_sid']; ?>">Reject
                                        </button>

                                        <button class="btn btn-orange jsApproveOpenShiftRequest" data-shiftid="<?php echo $requestRow['shift_sid'] ?>" data-toemployeeid="<?php echo $requestRow['employee_sid']; ?>">Approve
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        <?php  } ?>

                    </tbody>
                    </table>
                </div>
            </div>

        <?php } ?>

        </div>
        <script>
            breaksObject = <?= json_encode($breaks); ?>
        </script>