<div class="row">
    <div class="col-sm-12 text-right">
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
                            <a href="javascript:void(0)">
                                <img src="<?= getImageURL($v0["profile_picture"]); ?>" alt="" />
                                <p class=""><?= remakeEmployeeName($v0, true, true); ?></p>
                                <p class=""><?= remakeEmployeeName($v0, true, false); ?></p>
                            </a>
                        </div>
                    </div>
            <?php }
            }
            ?>
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
                            <a href="javascript:void(0)">
                                <img src="<?= getImageURL($v0["profile_picture"]); ?>" alt="" />
                                <p class=""><?= remakeEmployeeName($v0, true, true); ?></p>
                                <p class=""><?= remakeEmployeeName($v0, true, false); ?></p>
                            </a>
                        </div>
                    </div>
            <?php }
            }
            ?>
        </div>

    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="csF16 m0">
            <strong>
                Clocked Out
            </strong>
        </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <?php if ($records["absent"]) {
                foreach ($records["absent"] as $v0) { ?>
                    <div class="col-sm-2">

                        <div class="csEmployeeBox">
                            <a href="javascript:void(0)">
                                <img src="<?= getImageURL($v0["profile_picture"]); ?>" alt="" />
                                <p class=""><?= remakeEmployeeName($v0, true, true); ?></p>
                                <p class=""><?= remakeEmployeeName($v0, true, false); ?></p>
                            </a>
                        </div>
                    </div>
            <?php }
            }
            ?>
        </div>

    </div>
</div>