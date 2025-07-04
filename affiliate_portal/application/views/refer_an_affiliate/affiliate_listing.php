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
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr class="d-flex">
                                <th class="col-2">First Name</th>
                                <th class="col-2">Last Name</th>
                                <th class="col-4">Email</th>
                                <th class="col-3">Contact Number</th>
                                <th class="col-1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($count > 0) { ?>
                                <?php foreach($referred as $refer) {?>
                                <tr class="d-flex" bgcolor="#FFF">
                                    <td class="col-sm-2"><?php echo $refer['first_name']?></td>
                                    <td class="col-sm-2"><?php echo $refer['last_name']?></td>
                                    <td class="col-sm-4"><?php echo $refer['email']?></td>
                                    <td class="col-sm-3"><?php echo $refer['contact_number']?></td>
                                    <td class="col-sm-1 text-center">
                                        <a class="btn btn-primary" href="<?php echo base_url('refer-an-affiliate/'.$refer['sid']); ?>"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
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