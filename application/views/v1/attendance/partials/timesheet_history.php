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
                                            <!-- <th scope="col">Breaks</th> -->
                                            <!-- <th scope="col">On Location</th> -->
                                            <td scope="col">Location</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($v0 as $v1) { ?>
                                            <?php

                                            // Example usage

                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $v1["text"]; ?>
                                                </td>
                                                <td>
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
                                                <td>
                                                    <?= $v1["durationText"]; ?>
                                                </td>
                                                <td>
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