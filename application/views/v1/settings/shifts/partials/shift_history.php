    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-history" aria-hidden="true"></i>
                    History
                </h2>
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-lg-12">

                        <div class="table-responsive">
                            <table class="table table-striped table-condensed pto-policy-table csCustomTableHeader">
                                <thead style="background-color: #fd7a2a;" class="js-table-head">
                                    <tr>
                                        <th>Status</th>
                                        <th>Requested At</th>
                                        <th>Updated At</th>
                                        <th>From Employee</th>
                                        <th>To Employee</th>
                                    </tr>
                                </thead>
                                <tbody id="js-data-area">
                                    <?php if (!empty($shiftHistory)) {
                                        foreach ($shiftHistory as $historyRow) {
                                    ?>
                                            <tr>
                                                <td><?php echo $historyRow['request_status']; ?></td>
                                                <td><?php echo $historyRow['created_at']; ?></td>
                                                <td><?php echo $historyRow['updated_at']; ?></td>
                                                <td><?php echo $historyRow['from_employee']; ?></td>
                                                <td><?php echo $historyRow['to_employee']; ?></td>
                                            </tr>
                                    <?php }
                                    } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>