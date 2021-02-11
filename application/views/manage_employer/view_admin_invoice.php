<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <a href="<?php echo base_url('settings/list_packages_addons_invoices/'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back To Invoices</a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="message-action-btn">
                                <a target="_blank" href="<?php echo base_url('settings/print_packages_addons_invoice/'. $invoice_sid); ?>" class="submit-btn">Print</a>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <?php echo $invoice; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            paging: false,
            info: false,
            stateSave: true
        });
    });
</script>