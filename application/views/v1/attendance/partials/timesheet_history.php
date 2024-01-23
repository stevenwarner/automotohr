<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                    Time Sheet History
                </div>
            </div>

            <!--  -->
            <?php if ($history) {
                foreach ($history as $index => $v0) {
            ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-cogs" aria-hidden="true"></i>
                            <?= $index; ?>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Event</th>
                                            <th scope="col">Period</th>
                                            <th scope="col">Duration</th>
                                            <th scope="col" class="text-center">On Location</th>
                                            <th scope="col">Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($v0 as $v1) { ?>
                                            <tr>
                                                <td class="csVerticalAlignMiddle">
                                                    <?= $v1["text"]; ?>
                                                </td>
                                                <td class="csVerticalAlignMiddle">
                                                    <?= formatDateToDB(
                                                        $v1["startTime"],
                                                        DB_DATE_WITH_TIME,
                                                        "h:i a"
                                                    ); ?>
                                                    -
                                                    <?= $v1["endTime"] ? formatDateToDB(
                                                        $v1["endTime"],
                                                        DB_DATE_WITH_TIME,
                                                        "h:i a"
                                                    ) : "Missing"; ?>
                                                </td>
                                                <td class="csVerticalAlignMiddle">
                                                    <?= $v1["durationText"]; ?>
                                                </td>
                                                <td class="text-center csVerticalAlignMiddle">
                                                    <p class="text-<?= $v1["location"]["onSiteFlag"] ? "success" : "danger"; ?>">
                                                        <i class="fa fa-<?= $v1["location"]["onSiteFlag"] ? "check-circle" : "times-circle"; ?>"></i>
                                                    </p>
                                                    <?= !$v1["location"]["onSiteFlag"] ? $v1["location"]["text"] : ""; ?>
                                                </td>
                                                <td class="csVerticalAlignMiddle">
                                                    <button class="btn btn-link jsToggleMapView" data-id="<?= $v1["sid"]; ?>">
                                                        View Map
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="hidden mapRow<?= $v1["sid"]; ?>">
                                                <td colspan="4">
                                                    <div id="map_<?= $v1["sid"]; ?>" style="height: 400px"></div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>