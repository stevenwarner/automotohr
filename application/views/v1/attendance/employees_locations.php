<style>
    .customMarker {
        position:absolute;
        cursor:pointer;
        background:#424242;
        width:50;
        height:50px;
        margin-left:-24px;
        margin-top:-60px; 
        border-radius:10px;
        padding:0px;
    }

    .customMarker:after {
        content:"";
        position: absolute;
        bottom: -10px;
        left: 14px;
        border-width: 10px 10px 0;
        border-style: solid;
        border-color: #424242 transparent;
        display: block;
        width: 0;
    }

    .customMarker img {
        width:46px;
        height:46px;
        margin:2px;
        border-radius:10px;
    }
</style>
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
                            Employees
                        </label>
                        <select name="employees[]" class="form-control multipleSelect" multiple>
                            <?php if ($employees) { ?>
                                <option value="all" <?php echo in_array("all", $filter["employees"]) ? 'selected="selected"' : ''; ?>>All</option>
                                <?php foreach ($employees as $v0) { ?>
                                    <option value="<?= $v0["userId"]; ?>" <?= in_array($v0["userId"], $filter["employees"]) ? "selected" : ""; ?>><?= remakeEmployeeName($v0); ?></option>
                                <?php } ?>
                            <?php } else { ?>
                                <option value="0">No employee Found</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>
                            Select date
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" class="form-control jsDatePicker" readonly placeholder="MM/DD/YYYY" name="clocked_in_date" value="<?= $filter["selectedDate"] ?? ""; ?>" />
                    </div>
                </div>
            </div>  

            <div class="row"> 
                <div class="col-sm-12 text-right">
                    <label for=""></label>
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
                    $filter["clockedInDate"],
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
    var baseURL = '<?php echo base_url(); ?>';
    var selectedDate = '<?php echo $filter["selectedDate"]; ?>';
</script>