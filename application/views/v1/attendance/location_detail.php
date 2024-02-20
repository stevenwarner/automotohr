<style>
    :root {
    --break-color: #FF9800;
    --house-color: #0288D1;
    --shop-color: #7B1FA2;
    --clocked-color: #558B2F;
    }
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
    
    .activity {
        align-items: center;
        background-color: #FFFFFF;
        border-radius: 50%;
        color: #263238;
        display: flex;
        font-size: 14px;
        gap: 15px;
        height: 30px;
        justify-content: center;
        padding: 4px;
        position: relative;
        position: relative;
        transition: all 0.3s ease-out;
        width: 30px;
    }

    .activity::after {
        border-left: 9px solid transparent;
        border-right: 9px solid transparent;
        border-top: 9px solid #FFFFFF;
        content: "";
        height: 0;
        left: 50%;
        position: absolute;
        top: 91%;
        transform: translate(-50%, 0);
        transition: all 0.3s ease-out;
        width: 0;
        z-index: 1;
    }

    .activity .icon {
        align-items: center;
        display: flex;
        justify-content: center;
        color: #FFFFFF;
    }

    .activity .icon svg {
        height: 20px;
        width: auto;
    }

    .activity .details {
        display: none;
        flex-direction: column;
        flex: 1;
    }

    .activity .address {
        color: #9E9E9E;
        font-size: 10px;
        margin-bottom: 10px;
        margin-top: 5px;
    }

    .activity .features {
        align-items: flex-end;
        display: flex;
        flex-direction: row;
        gap: 10px;
    }

    .activity .features > div {
        align-items: center;
        background: #F5F5F5;
        border-radius: 5px;
        border: 1px solid #ccc;
        display: flex;
        font-size: 10px;
        gap: 5px;
        padding: 5px;
    }

    /*
    * Property styles in highlighted state.
    */
    .activity.highlight {
        background-color: #FFFFFF;
        border-radius: 8px;
        box-shadow: 10px 10px 5px rgba(0, 0, 0, 0.2);
        height: 80px;
        padding: 8px 15px;
        width: auto;
    }

    .activity.highlight::after {
        border-top: 9px solid #FFFFFF;
    }

    .activity.highlight .details {
        display: flex;
    }

    .activity.highlight .icon svg {
        width: 50px;
        height: 50px;
    }

    .activity .bed {
        color: #FFA000;
    }

    .activity .bath {
        color: #03A9F4;
    }

    .activity .size {
        color: #388E3C;
    }

    /*
    * House icon colors.
    */
    .activity.highlight:has(.fa-house) .icon {
        color: var(--house-color);
    }

    .activity:not(.highlight):has(.fa-house) {
        background-color: var(--house-color);
    }

    .activity:not(.highlight):has(.fa-house)::after {
        border-top: 9px solid var(--house-color);
    }

    /*
    * Building icon colors.
    */
    .activity.highlight:has(.fa-coffee) .icon {
        color: var(--break-color);
    }

    .activity:not(.highlight):has(.fa-coffee) {
        background-color: var(--break-color);
    }

    .activity:not(.highlight):has(.fa-coffee)::after {
        border-top: 9px solid var(--break-color);
    }

    /*
    * Warehouse icon colors.
    */
    .activity.highlight:has(.fa-clock-o) .icon {
        color: var(--clocked-color);
    }

    .activity:not(.highlight):has(.fa-clock-o) {
        background-color: var(--clocked-color);
    }

    .activity:not(.highlight):has(.fa-clock-o)::after {
        border-top: 9px solid var(--clocked-color);
    }

    /*
    * Shop icon colors.
    */
    .activity.highlight:has(.fa-shop) .icon {
        color: var(--shop-color);
    }

    .activity:not(.highlight):has(.fa-shop) {
        background-color: var(--shop-color);
    }

    .activity:not(.highlight):has(.fa-shop)::after {
        border-top: 9px solid var(--shop-color);
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