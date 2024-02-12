<?php
$timeSheetName = "";
?>
<!-- Filter -->

<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="text-large">
            <strong>
                <i class="fa fa-filter text-orange" aria-hidden="true"></i>
                &nbsp;Filter
            </strong>
        </h2>
    </div>
    <div class="panel-body">
        <form action="<?= current_url(); ?>" method="get">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>
                            Select date
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" class="form-control jsDatePicker" readonly placeholder="MM/DD/YYYY - MM/DD/YYYY" name="clocked_in_date" value="<?= $filter["clocked_in_date"] ?? ""; ?>" />
                    </div>
                </div>

                <div class="col-sm-6 text-right">
                    <button class="btn btn-orange">
                        <i class="fa fa-search"></i>
                        Apply filter
                    </button>
                    <a class="btn btn-black" href="<?= current_url(); ?>">
                        <i class="fa fa-times-circle"></i>
                        Clear filter
                    </a>
                </div>
            </div>
            <!--  -->
        </form>
    </div>
</div>

<!-- data -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="text-large">
            <strong>
                <i class="fa fa-map text-orange" aria-hidden="true"></i>
                &nbsp;Employees clocked In Map
            </strong>
            <p class="mt-5">
                <?= formatDateToDB(
                    $filter["date"],
                    DB_DATE,
                    DATE
                ); ?>
            </p>
        </h2>
    </div>


    <div class="panel-body">
    <div id="map" style="width: 100%;"></div>
    </div>
</div>

<script>
    var jsMapData = JSON.parse('<?=json_encode($markers);?>');
</script>