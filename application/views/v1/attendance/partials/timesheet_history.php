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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($v0 as $v1) { ?>
                                            <?php

                                            // Example usage
                                            $point1 = ["lat" => $v0["lat"] ?? 0, "lon" => $v0["lng"] ?? 0];
                                            $point2 = ["lat" => 34.0522, "lon" => -118.2437];
                                            $radius = 1000; // 1,000 kilometers
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