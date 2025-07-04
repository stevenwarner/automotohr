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
                                        <h1 class="page-title"><i class="fa fa-file-excel-o"></i>Companies With Unpaid Invoices</h1>
                                    </div>
                                    <br />

                                    <div class="table-responsive hr-innerpadding">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Company</th>
                                                <th class="text-center col-xs-1">Unpaid Invoices</th>
                                                <?php if(check_access_permissions_for_view($security_details, 'view_unpaid_invoices')) { ?>
                                                    <th class="text-center col-xs-1">Actions</th>
                                                <?php } ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($companies as $company) {?>
                                                <tr>
                                                    <td>
                                                        <?php echo !empty($company['CompanyName']) ? ucwords(strtolower($company['CompanyName'])) : '<span class="text-danger">( Company Deleted From System )</span>'; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo $company['invoice_count']; ?>
                                                    </td>
                                                    <?php if(check_access_permissions_for_view($security_details, 'view_unpaid_invoices')) { ?>
                                                        <td>
                                                            <a href="<?php echo base_url('manage_admin/invoice/view_pending_invoices/' . $company['company_sid']); ?>" class="btn btn-sm btn-success">View Details</a>
                                                        </td>
                                                    <?php } ?>
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
<script>
    function fDeleteInvoice(invoice_sid) {
        alertify.confirm('Are you sure?', 'Are you sure you want to delete this Invoice?',
            function () {
                //ok

                $('#form_delete_invoice_' + invoice_sid).submit();
            },
            function () {
                //cancel
            });
    }
</script>