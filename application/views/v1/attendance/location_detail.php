<style>
    .employee_clocked_activity {
        background-color: #4285F4;
        border-radius: 8px;
        color: #FFFFFF;
        font-size: 14px;
        padding: 10px 15px;
        position: relative;
    }

    .employee_clocked_activity::after {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translate(-50%, 0);
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid #4285F4;
    }

    .employee_break_activity {
        background-color: #fd7a2acc;
        border-radius: 8px;
        color: #FFFFFF;
        font-size: 14px;
        padding: 10px 15px;
        position: relative;
    }

    .employee_break_activity::after {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translate(-50%, 0);
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid #fd7a2acc;
    }

    .profile-img-responsive {
        width: 100%;
        height: 100%;
        border-radius: 5px;
    }
</style>
<!-- data -->
<hr>

<div class="row">
    <div class="col-sm-1">
        <img class="profile-img-responsive" src="<?= getImageURL($employeeInfo["profile_picture"]); ?>" alt="" />
    </div>
    <div class="col-sm-6">
        <p class="text-xxl weight-6 myb-0">
            <?= remakeEmployeeName($employeeInfo, true, true); ?>
        </p>
        <p class="text-large myb-0">
            <?= remakeEmployeeName($employeeInfo, false); ?>
        </p>
        <?php if (!empty($employeeInfo['PhoneNumber'])) { ?>
            <p class="text-large myb-0">
                <i class="fa fa-phone"></i> <?php echo $employeeInfo['PhoneNumber']; ?>
            </p>
        <?php } ?>
        <?php if (!empty($employeeInfo['email'])) { ?>
            <p class="text-large">
                <i class="fa fa-envelope"></i> <?php echo $employeeInfo['email']; ?>
            </p>
        <?php } ?>
    </div>
    <div class="col-sm-5 text-right">
        <p class="text-xxxl">
            <?= formatDateToDB($clockedInDate, DB_DATE, DATE); ?>
        </p>
    </div>
</div>

<hr>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="text-large">
            <strong>
                <i class="fa fa-map text-orange" aria-hidden="true"></i>
                &nbsp; Activity Map
            </strong>
        </h2>
    </div>


    <div class="panel-body">
        <div id="map" style="width: 100%;"></div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="text-large">
            <strong>
                <i class="fa fa-clock-o text-orange" aria-hidden="true"></i>
                &nbsp;Time Entries
            </strong>
        </h2>
    </div>
    <div class="panel-body jsTimeEntriesBox">
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th class="csW csBG4" scope="col">Type</th>
                        <th class="csW csBG4 text-right" scope="col">Time</th>
                        <th class="csW csBG4 text-center" scope="col">On location</th>
                        <th class="csW csBG4 text-right" scope="col">Duration</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="text-large">
            <strong>
                <i class="fa fa-info-circle text-orange" aria-hidden="true"></i>
                &nbsp;Shift Detail
            </strong>
        </h2>
    </div>
    <div class="panel-body">
        <?php if ($clockArray['periods']) { ?>
            <table class="table table-bordered table-condensed table-hover">
                <tbody>
                    <tr>
                        <th class="col-xs-6">Clocked Hour(s)</th>
                        <td class="col-xs-6"><?= $clockArray ? $clockArray['text']['clocked_time'] : "0h"; ?></td>
                    </tr>
                    <tr>
                        <th class="col-xs-6">Worked Hour(s)</th>
                        <td class="col-xs-6"><?= $clockArray ? $clockArray['text']['worked_time'] : "0h"; ?></td>
                    </tr>
                    <tr>
                        <th class="col-xs-6">Regular Hour(s)</th>
                        <td class="col-xs-6"><?= $clockArray ? $clockArray['text']['regular_time'] : "0h"; ?></td>
                    </tr>
                    <tr>
                        <th class="col-xs-6">Paid Break</th>
                        <td class="col-xs-6"><?= $clockArray ? $clockArray['text']['paid_break_time'] : "0h"; ?></td>
                    </tr>
                    <tr>
                        <th class="col-xs-6">Unpaid Break</th>
                        <td class="col-xs-6"><?= $clockArray ? $clockArray['text']['unpaid_break_time'] : "0h"; ?></td>
                    </tr>
                    <tr>
                        <th class="col-xs-6">Overtime</th>
                        <td class="col-xs-6"><?= $clockArray ? $clockArray['text']['overtime'] : "0h"; ?></td>
                    </tr>
                    <tr>
                        <th class="col-xs-6">Double Overtime</th>
                        <td class="col-xs-6"><?= $clockArray ? $clockArray['text']['double_overtime'] : "0h"; ?></td>
                    </tr>
                    <tr>
                        <th class="col-xs-6">Time Difference</th>
                        <td class="col-xs-6"><?= $clockArray ? $clockArray['text']['difference_time'] : "0h"; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <p class="alert alert-info text-center">No record found!</p>
                </div>
            </div>
        <?php } ?>    
    </div>
</div>

<script>
    var jsMapData = JSON.parse('<?=json_encode($history['locations']);?>');
    var logs = JSON.parse('<?=json_encode($history['logs']);?>');
</script>