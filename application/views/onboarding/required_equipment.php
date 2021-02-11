<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <a href="<?php echo base_url('employee_management_system') ?>" class="btn btn-info mb-2"><i class="fa fa-angle-left"></i> Dashboard</a>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="title-heading">Required Equipment</h1>
                </div>

                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <form id="form_required_equipment" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                    <input type="hidden" id="perform_action" name="perform_action" value="save_required_equipment" />
                    <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo isset($applicant_sid) ? $applicant_sid : NULL; ?>" />
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="dashboard-conetnt-wrp">
                                        <div class="row grid-columns">
                                            <?php if($equipment_info) {
                                                foreach($equipment_info as $equipment){ ?>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 grid-mb">
                                                    <article class="listing-article no-image">
                                                        <div class="text">
                                                            <h3><?php echo ucwords($equipment['equipment_type']); ?></h3>
                                                            <div class="post-options">
                                                                <ul>
                                                                    <li><strong>Brand Name:</strong> <?php
                                                                        echo ucwords($equipment['brand_name']); ?>
                                                                    </li>
                                                                    <?php if($equipment['issue_date'] != NULL && !empty($equipment['issue_date']) && $equipment['issue_date'] != '0000-00-00 00:00:00'){?>
                                                                        <br><li><strong>Date Assigned:</strong> <?php echo date_with_time($equipment['issue_date']); ?></li>
                                                                    <?php } if($equipment['model'] != NULL && !empty($equipment['model'])){?>
                                                                        <br><li><strong>Model:</strong> <?php echo $equipment['model']; ?></li>
                                                                    <?php } if($equipment['imei_no'] != NULL && !empty($equipment['imei_no'])){?>
                                                                        <br><li><strong>IMEI:</strong> <?php echo $equipment['imei_no']; ?></li>
                                                                    <?php } if($equipment['product_id'] != NULL && !empty($equipment['product_id'])){?>
                                                                        <br><li><strong>Product Id:</strong> <?php echo $equipment['product_id']; ?></li>
                                                                    <?php } if($equipment['specification'] != NULL && !empty($equipment['specification'])){?>
                                                                        <br><li><strong>Specification:</strong> <?php echo $equipment['specification']; ?></li>
                                                                    <?php } if($equipment['vin_number'] != NULL && !empty($equipment['vin_number'])){?>
                                                                        <br><li><strong>Engine Number:</strong> <?php echo $equipment['vin_number']; ?></li>
                                                                    <?php } if($equipment['transmission_type'] != NULL && !empty($equipment['transmission_type'])){?>
                                                                        <br><li><strong>Transmission Type:</strong> <?php echo $equipment['transmission_type']; ?></li>
                                                                    <?php } if($equipment['fuel_type'] != NULL && !empty($equipment['fuel_type'])){?>
                                                                        <br><li><strong>Fuel Type:</strong> <?php echo $equipment['fuel_type']; ?></li>
                                                                    <?php } if($equipment['serial_number'] != NULL && !empty($equipment['serial_number'])){?>
                                                                        <br><li><strong>Serial Number:</strong> <?php echo $equipment['serial_number']; ?></li>
                                                                    <?php } if($equipment['color'] != NULL && !empty($equipment['color'])){?>
                                                                        <br><li><strong>Color:</strong> <span class="form-control" style="background-color: <?php echo $equipment['color']; ?>"></span></li>
                                                                    <?php }?>

                                                                </ul>
                                                            </div>
                                                            <?php if($equipment['notes'] != NULL && !empty($equipment['notes'])){?>
                                                                <div class="full-width announcement-des"
                                                                     style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                                    <strong>Notes:</strong> <?php echo !empty($equipment['notes']) ? $equipment['notes'] : 'N/A'; ?>
                                                                </div>
                                                            <?php }?>
                                                    </article>
                                                </div>
                                                <?php }
                                            }else{?>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="text">
                                                    <h3><a href="javascript:;">No Equipment Assigned</a></h3>
                                                </div>
                                            </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
