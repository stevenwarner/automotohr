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
                                        <h1 class="page-title"><i class="fa fa-money"></i>Process Payment For Admin Invoice # <?php echo $invoice['invoice_number'];?></h1>
                                        <a href="<?php echo base_url('manage_admin/invoice/list_admin_invoices'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Invoices</a>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $invoice['company_sid']);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title">Payment Processing Failed</h1>
                                        </div>
                                        <div class="row">
                                            <?php   echo "<pre>";
                                                    print_r($error_details);
                                                    echo "</pre>";
                                            ?>
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