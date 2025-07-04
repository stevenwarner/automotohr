<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left"><?= $title; ?></h1>
                    <div class="btn-panel float-right">
                        <a href="<?= base_url() ?>dashboard" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr class="d-flex">
                                <th class="col-3">Company Name</th>
                                <th class="col-3">Type</th>
                                <th class="col-3">Registration date</th>
                                <th class="col-3">Expiration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($primary_count > 0) { ?>
                                <?php foreach($paying_clients as $paying_client) {?>
                                    <tr class="d-flex" bgcolor="#FFF">
                                        <td class="col-sm-3"><?php echo ucwords($paying_client['CompanyName']);?></td>
                                        <td class="col-sm-3">Primary</td>
                                        <td class="col-sm-3"><?php echo date_with_time($paying_client['registration_date']);?></td>
                                        <td class="col-sm-3"><?php echo $paying_client['expiry_date'] != NULL && !empty($paying_client['expiry_date']) ? date_with_time($paying_client['expiry_date']) : 'N/A';?></td>
                                    </tr>
                                <?php }?>
                            <?php } if ($secondary_count > 0) { ?>
                                <?php foreach($secondary_agency as $paying_client) {?>
                                    <tr class="d-flex" bgcolor="#FFF">
                                        <td class="col-sm-3"><?php echo ucwords($paying_client['CompanyName']);?></td>
                                        <td class="col-sm-3">Secondary / <br><b>Marketing Agency:</b> <?= !empty($paying_client['full_name']) ? $paying_client['full_name'] : 'N/A'; ?></td>
                                        <td class="col-sm-3"><?php echo date_with_time($paying_client['registration_date']);?></td>
                                        <td class="col-sm-3"><?php echo $paying_client['expiry_date'] != NULL && !empty($paying_client['expiry_date']) ? date_with_time($paying_client['expiry_date']) : 'N/A'?></td>
                                    </tr>
                                <?php }?>
                            <?php } if ($primary_count == 0 && $secondary_count == 0) { ?>
                                <tr class="d-flex" bgcolor="#FFF">
                                    <td class="col-sm-12" style="text-align: center;">No Record Found</td>
                                </tr>
                            <?php } ?> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
