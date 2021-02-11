<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">                                
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies/manage_company') . '/' . $company_sid; ?>">
                                            <i class="fa fa-long-arrow-left"></i> 
                                            Back to Manage Company Dashboard
                                        </a>
                                    </div>
                                    <div class="table-responsive hr-promotions">
                                        <div class="hr-displayResultsTable">
                                            <br>
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-5">Name</th>
                                                        <th class="col-xs-5">Website</th>
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php if (sizeof($brands) > 0) { ?>
                                                        <?php foreach ($brands as $brand) { ?>
                                                            <tr id='<?php echo $brand['sid']; ?>'>
                                                                <td><?php echo $brand['oem_brand_name']; ?></td>
                                                                <td><?php echo $brand['brand_website']; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan='2'>No OEM, Independent, Vendor found.</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
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