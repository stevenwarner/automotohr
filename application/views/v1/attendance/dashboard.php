<div class="row">
    <div class="col-sm-6 text-left">
        <div class="form-group">
            <label>
                Select date range
                <strong class="text-danger">*</strong>
            </label>
            <input type="text" class="form-control jsDateRangePicker" readonly placeholder="MM/DD/YYYY" name="date" value="<?= $filter["date"] ?? ""; ?>" />
        </div>
    </div>
    <div class="col-sm-6 text-right">
        <br>
        <a href="<?= base_url("attendance/timesheet"); ?>" class="btn btn-orange">
            <i class="fa fa-cogs" aria-hidden="true"></i>
            &nbsp;Manage Time Sheet
        </a>
    </div>
</div>

<hr />
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="csF16 m0">
            <strong>
                Clocked In
            </strong>
        </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <?php if ($records["clocked_in"]) {
                foreach ($records["clocked_in"] as $v0) { ?>
                    <div class="col-sm-2">
                        <div class="csEmployeeBox">
                            <a href="<?= base_url("employee_profile/" . $v0["userId"]); ?>">
                                <img src="<?= getImageURL($v0["profile_picture"]); ?>" alt="" />
                                <p class=""><?= remakeEmployeeName($v0, true, true); ?></p>
                            </a>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="col-sm-12">
                    <div class="alert alert-info text-center">
                        <p>No staff members have logged in for their work shift.</p>
                    </div>
                </div>
            <?php  } ?>
        </div>

    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="csF16 m0">
            <strong>
                On Break or Lunch
            </strong>
        </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <?php if ($records["breaks"]) {
                foreach ($records["breaks"] as $v0) { ?>
                    <div class="col-sm-2">

                        <div class="csEmployeeBox">
                            <a href="<?= base_url("employee_profile/" . $v0["userId"]); ?>">
                                <img src="<?= getImageURL($v0["profile_picture"]); ?>" alt="" />
                                <p class=""><?= remakeEmployeeName($v0, true, true); ?></p>
                            </a>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="col-sm-12">
                    <div class="alert alert-info text-center">
                        <p>Currently, no employees are on a break.</p>
                    </div>
                </div>
            <?php  } ?>
        </div>

    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="csF16 m0">
            <strong>
                Clocked out
            </strong>
        </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <?php if ($records["clocked_out"]) {
                foreach ($records["clocked_out"] as $v0) { ?>
                    <div class="col-sm-2">

                        <div class="csEmployeeBox">
                            <a href="<?= base_url("employee_profile/" . $v0["userId"]); ?>">
                                <img src="<?= getImageURL($v0["profile_picture"]); ?>" alt="" />
                                <p class=""><?= remakeEmployeeName($v0, true, true); ?></p>
                            </a>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="col-sm-12">
                    <div class="alert alert-info text-center">
                        <p>All employees are present!</p>
                    </div>
                </div>
            <?php  } ?>
        </div>
    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="csF16 m0">
            <strong>
                Not clocked in
            </strong>
        </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <?php if ($records["absent"]) {
                foreach ($records["absent"] as $v0) { ?>
                    <div class="col-sm-2">

                        <div class="csEmployeeBox">
                            <a href="<?= base_url("employee_profile/" . $v0["userId"]); ?>">
                                <img src="<?= getImageURL($v0["profile_picture"]); ?>" alt="" />
                                <p class=""><?= remakeEmployeeName($v0, true, true); ?></p>
                            </a>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="col-sm-12">
                    <div class="alert alert-info text-center">
                        <p>All employees are present!</p>
                    </div>
                </div>
            <?php  } ?>
        </div>
    </div>
</div>