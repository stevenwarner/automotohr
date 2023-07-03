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
                                                            <th># of Documents</th>
                                                            <th>Transfer Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($copyTransferEmployee)) {

                                                        ?>
                                                            <?php foreach ($copyTransferEmployee as $key => $value) {
                                                                $totalDoc = 0;

                                                                $totalDoc = $totalDoc + count($value['assignedDocuments']);
                                                                //echo $totalDoc ;
                                                                if ($value['docw4'] > 0) {
                                                                    $totalDoc = $totalDoc + 1;
                                                                }
                                                                if ($value['docw9'] > 0) {
                                                                    $totalDoc = $totalDoc + 1;
                                                                }
                                                                if ($value['doci9'] > 0) {
                                                                    $totalDoc = $totalDoc + 1;
                                                                }
                                                                if ($value['emergencyContact'] > 0) {
                                                                    $totalDoc = $totalDoc + 1;
                                                                }
                                                                if ($value['dependents'] > 0) {
                                                                    $totalDoc = $totalDoc + 1;
                                                                }
                                                                if ($value['directDeposit'] > 0) {
                                                                    $totalDoc = $totalDoc + 1;
                                                                }
                                                                if ($value['license'] > 0) {
                                                                    $totalDoc = $totalDoc + 1;
                                                                }

                                                            ?>
                                                                <tr id="parent_<?= $value['sid'] ?>">
                                                                    <td> <?= $value['fromCompany']; ?></td>
                                                                    <td><?= $value['toCompany']; ?></td>
                                                                    <td><?php echo $totalDoc; ?> Docs <a class="btn btn-success jsviewdoc" href="javascript:void()">View</a></td>
                                                                    <td>
                                                                        <?php
                                                                        if (isset($value["copyDate"]) && !empty($value["copyDate"])) {
                                                                            echo formatDateToDB($value['copyDate'], DB_DATE_WITH_TIME, DATE);
                                                                        }
                                                                        ?>
                                                                    </td>

                                                                </tr>

                                                                <tr style="display: none">
                                                                    <td colspan="4">
                                                                        <? if ($value['docw4'] > 0) { ?>
                                                                            <div class="row" style="margin-left:0px;">W4 Fillable<div>
                                                                                <?php } ?>
                                                                                <? if ($value['docw9'] > 0) { ?>
                                                                                    <div class="row" style="margin-left:0px;">W9 Fillable<div>
                                                                                        <?php } ?>

                                                                                        <? if ($value['doci9'] > 0) { ?>
                                                                                            <div class="row" style="margin-left:0px;">I9 Fillable<div>
                                                                                                <?php } ?>

                                                                                               
                                                                                                <? if ($value['emergencyContact'] > 0) { ?>
                                                                                            <div class="row" style="margin-left:0px;">Emergency Contacts<div>
                                                                                                <?php } ?>

                                                                                                <? if ($value['dependents'] > 0) { ?>
                                                                                            <div class="row" style="margin-left:0px;">Dependents<div>
                                                                                                <?php } ?>
                                                                                                <? if ($value['directDeposit'] > 0) { ?>
                                                                                            <div class="row" style="margin-left:0px;">Direct Deposit Information<div>
                                                                                                <?php } ?>
                                                                                                <? if ($value['license'] > 0) { ?>
                                                                                            <div class="row" style="margin-left:0px;">Drivers License Information<div>
                                                                                                <?php } ?>

                                                                                                       

                                                                                                <?php if (!empty($value['assignedDocuments'])) { 
                                                                                                    foreach ($value['assignedDocuments'] as $row){?>
                                                                                                    <div class="row" style="margin-left:0px;"><?php echo $row['document_title'];?><div>
                                                                                                        <?php } } ?>






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
<script>
    $(document).on('click', '.jsviewdoc', function(e) {
        e.preventDefault();
        $(this).parent().parent().next('tr').toggle();

    });
</Script>