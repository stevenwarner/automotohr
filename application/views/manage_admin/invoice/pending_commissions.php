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
                                        <h1 class="page-title"><i class="fa fa-file-excel-o"></i>Unpaid Commissions</h1>
                                    </div>
                                    <br />

                                    <div class="table-responsive hr-innerpadding">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center col-xs-2">Commission Invoice Number</th>
                                                <th class="text-center col-xs-2">Company Name</th>
                                                <th class="text-center col-xs-2">Marketing Agency</th>
                                                <th class="text-center col-xs-2">Total Commission Payable</th>
                                                <th class="text-center col-xs-2">Date</th>
                                                <?php if(check_access_permissions_for_view($security_details, 'view_pending_commission')) { ?>
                                                    <th class="text-center col-xs-2">Actions</th>
                                                <?php } ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($unpaid_commissions as $commission) { ?>
                                                <?php //if (!empty($commission['full_name'])) { ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php if($commission['is_read'] == 0) { ?>
                                                                <img src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                            <?php } else { ?>
                                                                <img src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                            <?php } echo $commission['invoice_number']; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo $commission['company_name']; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo $commission['full_name']; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($commission['discount_amount'] > 0) { ?>
                                                                $ <?php echo number_format($commission['total_commission_after_discount'], 2); ?>
                                                            <?php } else { ?>
                                                                $ <?php echo number_format($commission['commission_value'], 2); ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo convert_date_to_frontend_format($commission['created']); ?>
                                                        </td>
                                                        <?php if(check_access_permissions_for_view($security_details, 'view_pending_commission')) { ?>
                                                            <td class="text-center">
                                                                <a href="<?php echo base_url('manage_admin/invoice/view_pending_commissions/' . $commission['sid']); ?>" class="btn btn-sm btn-success" title="View Invoice Detail"><i class="fa fa-eye"></i></a>
                                                                <a href="javascript:;" class="btn btn-sm btn-danger" title="Delete Invoice" onclick="fDeleteInvoice('<?php echo $commission['sid']; ?>')"><i class="fa fa-trash"></i></a>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php //} ?>
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
                var myurl = '<?php echo base_url('manage_admin/invoice/delete_commission'); ?>'+'/'+invoice_sid;
                
                $.ajax({
                    type: 'POST',
                    url: myurl,
                    success: function(data){
                        alertify.success('Invoice deleted Successfully!');
                        location.reload();
                    },
                    error: function(){

                    }
                });
            },
            function () {
                alertify.error('Cancel');
            });
    }
</script>