<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    Manage TimeSheet
                </div>

                <form action="javascript:void(0)" id="jsManageTimeSheetForm">
                    <div class="panel-body">
                        <?php if ($logs) {
                            foreach ($logs as $index => $v0) {
                                $indexStart = $v0["clocked_in"] ? "clocked_in" : "break_start";
                                $indexEnd = $v0["clocked_out"] ? "clocked_out" : "break_end";
                        ?>
                                <div class="row jsEventRow" data-id="<?= $v0["sid"]; ?>">
                                    <br>
                                    <div class="col-sm-3">
                                        <label>Event type</label>
                                        <select name="event_type_<?= $v0["sid"]; ?>" class="form-control">
                                            <option value="clocked_in_out" <?= $v0["clocked_in"] ? "selected" : ""; ?>>
                                                Clock in/out
                                            </option>
                                            <option value="break_in_out" <?= $v0["break_start"] ? "selected" : ""; ?>>
                                                Break start/end
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Start time</label>
                                        <input type="text" name="start_time_<?= $v0["sid"]; ?>" class="form-control jsTimeField" value="<?= $v0[$indexStart] ? formatDateToDB(
                                                                                                                                            $v0[$indexStart],
                                                                                                                                            DB_DATE_WITH_TIME,
                                                                                                                                            'h:i a'
                                                                                                                                        ) : ""; ?>" />
                                    </div>
                                    <div class="col-sm-3">
                                        <label>End time</label>
                                        <input type="text" name="end_time_<?= $v0["sid"]; ?>" class="form-control jsTimeField" value="<?= $v0[$indexEnd] ? formatDateToDB(
                                                                                                                                            $v0[$indexEnd],
                                                                                                                                            DB_DATE_WITH_TIME,
                                                                                                                                            'h:i a'
                                                                                                                                        ): ""; ?>" />
                                    </div>
                                    <?php if ($index != 0) { ?>
                                        <div class="col-sm-3">
                                            <label><br /></label>
                                            <button class="btn btn-red jsDeleteEventRow" type="button">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>

                                    <?php } ?>
                                </div>
                        <?php
                            }
                        } ?>
                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 text-left">
                                <button class="btn btn-blue jsAddEventRow">
                                    <i class="fa fa-plus-circle"></i>
                                    &nbsp;Add an Event
                                </button>
                            </div>
                            <div class="col-sm-6 text-right">
                                <button class="btn btn-black jsModalCancel" type="button">
                                    <i class="fa fa-times-circle"></i>
                                    &nbsp;Cancel
                                </button>
                                <button class="btn btn-orange jsManageTimeSheetBtn">
                                    <i class="fa fa-edit"></i>
                                    &nbsp;Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>