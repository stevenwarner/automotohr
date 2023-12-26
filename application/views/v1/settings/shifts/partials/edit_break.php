<form action="javascript:void(0)" id="jsPageShiftBreakForm">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    Edit Break
                </h2>
            </div>
            <div class="panel-body">
                <!--  -->
                <div class="row form-group">
                    <!-- name -->
                    <div class="col-sm-8">
                        <label class="text-medium">
                            Name
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control" name="break_name" value="<?= $record["break_name"]; ?>" />
                    </div>
                    <div class="col-sm-4">
                        <label class="text-medium">
                            Duration
                            <strong class="text-red">*</strong>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="break_duration" value="<?= $record["break_duration"]; ?>" />
                            <div class="input-group-addon">mins</div>
                        </div>
                    </div>
                </div>
                <br>
                <!--  -->
                <div class="form-group">
                    <label class="text-medium">
                        Type
                        <strong class="text-red">*</strong>
                    </label>
                    <br>
                    <label class="control control--radio">
                        <input type="radio" name="break_type" value="paid" <?= $record["break_type"] === "paid" ? "checked" : ""; ?> />
                        <span class="text-medium weight-5">
                            Paid
                        </span>
                        <div class="control__indicator"></div>
                    </label>
                    &nbsp;&nbsp;
                    <label class="control control--radio">
                        <input type="radio" name="break_type" value="unpaid" <?= $record["break_type"] === "unpaid" ? "checked" : ""; ?> />
                        <span class="text-medium weight-5">
                            Unpaid
                        </span>
                        <div class="control__indicator"></div>
                    </label>
                </div>


            </div>
            <!--  -->
            <div class="panel-footer text-right">
                <button class="btn btn-orange jsPageShiftBreakBtn">
                    <i class="fa fa-edit" aria-hidden="true"></i>
                    &nbsp;Update Break
                </button>
                <button class="btn btn-black jsModalCancel" type="button">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    &nbsp;Cancel
                </button>
            </div>
        </div>

    </div>
</form>