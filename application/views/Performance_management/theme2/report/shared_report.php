<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px;padding-right: 0px;">  
    <div class="panel panel-theme">
        <div class="panel-body">
            <div class="pane panel-theme">
                <div class="panel-heading" style="background-color: #81b431;">
                    <h5 class="csF16 csB7 mt0 mb0 csW">
                        <?= $row['Name']; ?>Shared Reports
                        <span class="pull-right">
                            Records Found: <?= count($sharedreports); ?>
                        </span>
                    </h5>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col">Shared By</th>
                                <th scope="col">Shared Date</th>
                                <th scope="col" class="js-actions">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($sharedreports)) {
                                foreach ($sharedreports as $row) {
                            ?>
                                    <tr>
                                        <td><?php echo remakeEmployeeName($row);?></td>
                                        <td><?php echo formatDateToDB($row['share_date'], DB_DATE, DATE); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('performance-management/sharedreportdetail/'.$row['sid']);?>" class="btn btn-orange csF16" target="_blank" style="font-size: 16px !important">
                                                <i class="fa fa-eye" aria-hidden="true"></i>&nbsp; View Detail
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>