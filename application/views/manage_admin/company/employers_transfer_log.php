<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <br><br>
                                    <div class="hr-innerpadding">
                                        <div class="table-responsive">
                                            <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <?php $newEmployeeId = array_column($copyTransferEmployee, 'newEmployeeId'); ?>
                                                        <tr>
                                                            <th colspan="4"> <?php if (!empty($newEmployeeId[0])) {
                                                                                    echo getUserNameBySID($newEmployeeId[0]);
                                                                                } ?>
                                                            </th>

                                                        </tr>
                                                        <tr>
                                                            <th>From Company</th>
                                                            <th>To Company</th>
                                                            <th>Documents</th>
                                                            <th>Transfer Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($copyTransferEmployee)) {
                                                        ?>
                                                            <?php foreach ($copyTransferEmployee as $key => $value) {
                                                            ?>
                                                                <tr id="parent_<?= $value['sid'] ?>">
                                                                    <td> <?= $value['fromCompany']; ?></td>
                                                                    <td><?= $value['toCompany']; ?></td>
                                                                    <td>fdf</td>
                                                                    <td>
                                                                        <?php
                                                                        if (isset($value["copyDate"]) && !empty($value["copyDate"])) {
                                                                            echo formatDateToDB($value['copyDate'], DB_DATE_WITH_TIME, DATE);
                                                                        }
                                                                        ?>
                                                                    </td>

                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else {  ?>
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="no-data">No Employers Found</span>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>