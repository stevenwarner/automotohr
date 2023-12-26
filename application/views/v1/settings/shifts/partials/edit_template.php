<form action="javascript:void(0)" id="jsPageShiftBreakForm">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text weight-6">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    Edit Shift Template
                </h2>
            </div>
            <div class="panel-body">
                <!--  -->
                <div class="row form-group">
                    <div class="col-sm-4">
                        <label class="text-medium">
                            Start Time
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control jsTimeField" name="start_time" placeholder="HH:MM" value="<?= formatDateToDB($record["start_time"], "H:i:m", "h:i a"); ?>" />
                    </div>
                    <div class="col-sm-4">
                        <label class="text-medium">
                            End Time
                            <strong class="text-red">*</strong>
                        </label>
                        <input type="text" class="form-control jsTimeField" name="end_time" placeholder="HH:MM" value="<?= formatDateToDB($record["end_time"], "H:i:m", "h:i a"); ?>" />
                    </div>
                </div>

                <!--  -->
                <div class="form-group jsBreakContainer"></div>

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
                        Notes
                    </label>
                    <textarea name="notes" rows="5" class="form-control"><?=$record["notes"];?></textarea>
                </div>
            </div>
            <!--  -->
            <div class="panel-footer text-right">
                <button class="btn btn-orange jsPageShiftBreakBtn">
                    <i class="fa fa-edit" aria-hidden="true"></i>
                    &nbsp;Update Shift Template
                </button>
                <button class="btn btn-black jsModalCancel" type="button">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    &nbsp;Cancel
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    breaksObject = <?= json_encode($breaks); ?>;
    usedBreaksObject = <?= $record["breaks_json"]; ?>;
</script>