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
                                        <h1 class="page-title"><i class="fa fa-list"></i><?php echo $page_title; ?></h1>
                                        
                                        <?php   if(check_access_permissions_for_view($security_details, 'add_edit_delete_recurring_payments')) { ?>
                                                    <a class="btn btn-success pull-right" href="<?php echo base_url('manage_admin/recurring_payments/add'); ?>" >Add Recurring Payment</a>
                                        <?php   } ?>
                                    </div>
                                    <hr />
                                    <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" <?= $flag ? "style='display:block'" : "" ?>>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                                                    <?php $name = $this->uri->segment(3) == 'all' ? '' : $this->uri->segment(3); ?>
                                                    <label>Company Name</label>
                                                    <input type="text" name="name" id="name" value="<?php echo urldecode($name); ?>" class="invoice-fields">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 field-row">
                                                    <label>&nbsp;</label>
                                                    <a id="search_btn" href="#" class="btn btn-success btn-block" style="padding: 9px;">Search</a>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 field-row">
                                                    <label>&nbsp;</label>
                                                    <a id="clear" href="<?= base_url('manage_admin/recurring_payments')?>" class="btn btn-success btn-block" style="padding: 9px;">Clear</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <table class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th class="col-xs-1 text-center">Status</th>
                                                            <th class="col-xs-1 text-center">Created</th>
                                                            <th class="col-xs-3 text-center">Company</th>
                                                            <th class="col-xs-1 text-center">Day</th>
                                                            <th class="col-xs-2 text-center">Discount Amount</th>
                                                            <th class="col-xs-1 text-center">Rooftops</th>
                                                            <th class="col-xs-2 text-center">Total</th>
                                                            <?php $function_names = array('add_edit_delete_recurring_payments'); ?>
                                                            <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                                <th colspan="2" class="text-center">Actions</th>
                                                            <?php } ?>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php if (!empty($active_recurring_payments)) { ?>
                                                            <?php foreach($active_recurring_payments as $recurring_payment) { ?>
                                                                <tr>
                                                                    <td class="text-center" style="color: <?php echo ($recurring_payment['status'] == 'active' ? 'green' : 'red') ?>; font-weight: bold;"><?php echo ucwords($recurring_payment['status']); ?></td>
                                                                    <td class="text-center" ><?php echo date_with_time($recurring_payment['created']); ?></td>
                                                                    <td><?php echo ucwords($recurring_payment['CompanyName']); ?></td>
                                                                    <td class="text-center"><?php echo $recurring_payment['payment_day']; ?></td>
                                                                    <td class="text-right">$<?php echo number_format($recurring_payment['discount_amount'], 2, '.', ','); ?></td>
                                                                    <td class="text-center"><?php echo $recurring_payment['number_of_rooftops']; ?></td>
                                                                    <td class="text-right">$<?php echo number_format($recurring_payment['total_after_discount'], 2, '.', ','); ?></td>

                                                                    <?php if(check_access_permissions_for_view($security_details, 'add_edit_delete_recurring_payments')) { ?>
                                                                        <td class="text-center">
                                                                            <a href="<?php echo base_url('manage_admin/recurring_payments/edit/' . $recurring_payment['sid']); ?>" class="btn btn-success btn-sm">Edit</a>
                                                                        </td>
                                                                    <?php } ?>

                                                                    <?php if(check_access_permissions_for_view($security_details, 'add_edit_delete_recurring_payments')) { ?>
                                                                        <td class="text-center">
                                                                            <form method="post" enctype="multipart/form-data" id="form_delete_recurring_payment_record_<?php echo $recurring_payment['sid'];?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_recurring_payment" />
                                                                                <input type="hidden" id="recurring_payment_sid" name="recurring_payment_sid" value="<?php echo $recurring_payment['sid']; ?>" />

                                                                                <button type="button" onclick="fDeleteRecurringPaymentRecord(<?php echo $recurring_payment['sid'];?>, '<?php echo ucwords($recurring_payment['CompanyName']); ?>');" class="btn btn-danger btn-sm">Delete</button>
                                                                            </form>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <?php if (!empty($inactive_recurring_payments)) { ?>
                                                            <?php foreach($inactive_recurring_payments as $recurring_payment) { ?>
                                                                <tr>
                                                                    <td class="text-center" style="color: <?php echo ($recurring_payment['status'] == 'active' ? 'green' : 'red') ?>; font-weight: bold;"><?php echo ucwords($recurring_payment['status']); ?></td>
                                                                    <td class="text-center" ><?php echo date_with_time($recurring_payment['created']); ?></td>
                                                                    <td><?php echo ucwords($recurring_payment['CompanyName']); ?></td>
                                                                    <td class="text-center"><?php echo $recurring_payment['payment_day']; ?></td>
                                                                    <td class="text-right">$<?php echo number_format($recurring_payment['discount_amount'], 2, '.', ','); ?></td>
                                                                    <td class="text-center"><?php echo $recurring_payment['number_of_rooftops']; ?></td>
                                                                    <td class="text-right">$<?php echo number_format($recurring_payment['total_after_discount'], 2, '.', ','); ?></td>

                                                                    <?php if(check_access_permissions_for_view($security_details, 'add_edit_delete_recurring_payments')) { ?>
                                                                        <td class="text-center">
                                                                            <a href="<?php echo base_url('manage_admin/recurring_payments/edit/' . $recurring_payment['sid']); ?>" class="btn btn-success btn-sm">Edit</a>
                                                                        </td>
                                                                    <?php } ?>

                                                                    <?php if(check_access_permissions_for_view($security_details, 'add_edit_delete_recurring_payments')) { ?>
                                                                        <td class="text-center">
                                                                            <form method="post" enctype="multipart/form-data" id="form_delete_recurring_payment_record_<?php echo $recurring_payment['sid'];?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="delete_recurring_payment" />
                                                                                <input type="hidden" id="recurring_payment_sid" name="recurring_payment_sid" value="<?php echo $recurring_payment['sid']; ?>" />

                                                                                <button type="button" onclick="fDeleteRecurringPaymentRecord(<?php echo $recurring_payment['sid'];?>, '<?php echo ucwords($recurring_payment['CompanyName']); ?>');" class="btn btn-danger btn-sm">Delete</button>
                                                                            </form>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <?php if(empty($active_recurring_payments) && empty($inactive_recurring_payments)) {?>
                                                            <tr>
                                                                <td colspan="9" class="text-center">
                                                                    <span class="no-data">No Records Found</span>
                                                                </td>
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
    </div>
</div>
<script>
    $(document).keypress(function(e) {
        if(e.which == 13) { // enter pressed
            $('#search_btn').click();
        }
    });

    $(document).ready(function(){
        $('#name').on('keyup', update_url);
        $('#name').on('blur', update_url);
        $('#search_btn').on('click', function(e){
            e.preventDefault();
            update_url();
            window.location = $(this).attr('href').toString();
        });
    });

    function update_url(){
        var url = '<?php echo base_url('manage_admin/recurring_payments/'); ?>';
        var name = $('#name').val();

        name = name == '' ? 'all' : name;
        url = url + '/' + encodeURIComponent(name);
        $('#search_btn').attr('href', url);
    }
    function fDeleteRecurringPaymentRecord(recurring_payment_sid, company_name) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete recurring payment setup up for "' + company_name + '" ?',
            function () {
                $('#form_delete_recurring_payment_record_' + recurring_payment_sid).submit();
            },
            function () {
                alertify.error('Cancelled');
            });
    }
</script>