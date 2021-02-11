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
                                        <h1 class="page-title"><i class="fa fa-file-excel-o"></i>Admin Invoices</h1>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="field-row">
                                                <label for="company">Company</label>
                                                <div class="hr-select-dropdown">
                                                    <?php $temp = $this->uri->segment(6); ?>
                                                    <?php $temp = empty($temp) ? 'all' : $temp; ?>
                                                    <select class="invoice-fields" id="company" name="company">
                                                        <option value="all">All Companies</option>
                                                        <?php if(!empty($companies)) { ?>
                                                            <?php foreach($companies as $company) { ?>
                                                                <?php $default_selected = intval($temp) == $company['sid'] ? true : false; ?>
                                                                <option <?php echo set_select('company', $company['sid'], $default_selected)?> value="<?php echo $company['sid']; ?>"><?php echo ucwords(strtolower($company['CompanyName'])); ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-4">
                                            <div class="field-row">
                                                <label for="payment_status">Payment Status</label>
                                                <div class="hr-select-dropdown">
                                                    <?php $temp = $this->uri->segment(7); ?>
                                                    <?php $temp = empty($temp) ? 'all' : $temp; ?>
                                                    <select class="invoice-fields" id="payment_status" name="payment_status">
                                                        <option value="all">All</option>
                                                        <?php if(!empty($payment_statuses)) { ?>
                                                            <?php foreach($payment_statuses as $key => $status) { ?>
                                                                <?php $default_selected = $temp == $key ? true : false; ?>
                                                                <option <?php echo set_select('payment_status', $key, $default_selected)?> value="<?php echo $key; ?>"><?php echo $status; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="field-row">
                                                <label for="payment_method">Payment Method</label>
                                                <div class="hr-select-dropdown">
                                                    <?php $temp = $this->uri->segment(8); ?>
                                                    <?php $temp = empty($temp) ? 'all' : $temp; ?>
                                                    <select class="invoice-fields" id="payment_method" name="payment_method">
                                                        <option value="all">All</option>
                                                        <?php if(!empty($payment_methods)) { ?>
                                                            <?php foreach($payment_methods as $key => $method) { ?>
                                                                <?php $default_selected = $temp == $key ? true : false; ?>
                                                                <option <?php echo set_select('payment_method', $key, $default_selected)?> value="<?php echo $key; ?>"><?php echo $method; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="field-row">
                                                <label for="month">Month</label>
                                                <div class="hr-select-dropdown">
                                                    <?php $temp = $this->uri->segment(5); ?>
                                                    <?php $temp = empty($temp) ? date('m') : $temp; ?>
                                                    <select class="invoice-fields" id="month" name="month">
                                                        <?php for($iCount = 1; $iCount <= 12; $iCount++) { ?>
                                                            <?php $default_selected = intval($temp) == $iCount ? true : false; ?>
                                                            <option <?php echo set_select('month', $iCount, $default_selected); ?> value="<?php echo $iCount; ?>"><?php echo $months[$iCount]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="field-row">
                                                <label for="year">Year</label>
                                                <div class="hr-select-dropdown">
                                                    <?php $temp = $this->uri->segment(4); ?>
                                                    <?php $temp = empty($temp) ? date('Y') : $temp; ?>
                                                    <select class="invoice-fields" id="year" name="year">
                                                        <?php for($iCount = 2016; $iCount <= intval(date('Y')) + 1; $iCount++) { ?>
                                                            <?php $default_selected = intval($temp) == $iCount ? true : false; ?>
                                                            <option <?php echo set_select('year', $iCount, $default_selected); ?> value="<?php echo $iCount; ?>"><?php echo $iCount; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-4">
                                            <div class="field-row">
                                                <label>&nbsp;</label>
                                                <a id="search_btn" href="" class="btn btn-success btn-block btn-equalizer">Filter</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered pull-left">List of All Admin Invoices</h1>
                                        </div>
                                        <div class="table-responsive hr-innerpadding">
                                            <div class="scrollable-area">
                                                <table class="table table-bordered table-striped fixTable-header">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="4" class="text-center">Invoice Summary</th>
                                                            <?php $function_names = array('view_admin_invoice', 'apply_discount_admin_invoice', 'process_payment_admin_invoice', 'delete_admin_invoice'); ?>
                                                            <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                                <th rowspan="2" colspan="1" class="text-center">Actions</th>
                                                            <?php } ?>
                                                        </tr>
                                                        <tr>
                                                            <th>Description</th>
                                                            <th class="text-center">Value</th>
                                                            <th class="text-center">Discount</th>
                                                            <th class="text-center">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(!empty($invoices)){ ?>
                                                            <?php foreach($invoices as $invoice) {?>
                                                                <tr>
                                                                    <td class="col-lg-6">
                                                                        <div class="invoice-date">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="dotted-border">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Company</strong></div>
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo ucwords($invoice['company_name']); ?></div>
                                                                                        </div>
                                                                                   </div>
                                                                                    <div class="dotted-border">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Created Date</strong></div>
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo convert_date_to_frontend_format($invoice['created'], true);?></div>
                                                                                        </div>
                                                                                   </div>

                                                                                   <div class="dotted-border">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Invoice #</strong></div>
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo $invoice['invoice_number']; ?></div>
                                                                                        </div>
                                                                                   </div>
                                                                                   <div class="dotted-border">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Payment Status</strong></div>
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 <?php echo ucwords($invoice['payment_status']); ?>"><?php echo ucwords($invoice['payment_status']); ?></div>
                                                                                        </div>
                                                                                   </div>
                                                                                    <div class="dotted-border">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Payment Method</strong></div>
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo ucwords(implode(' ', explode('-', $invoice['payment_method']))); ?><?php if($invoice['check_number'] != NULL) { echo ' #'.$invoice['check_number']; } ?></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="dotted-border">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Payment Date</strong></div>
                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo convert_date_to_frontend_format($invoice['payment_date'], true); ?></div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <h5><strong>Item Summary</strong></h5>
                                                                            <ul class="item-name-summary">
                                                                                <?php   foreach($invoice['item_names'] as $item_name) { ?>
                                                                                            <li><?php echo $item_name['item_name']; ?></li>
                                                                                <?php   } ?>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                    <!-- Start Invoice Summary -->
                                                                    <td class="text-right col-xs-2">$<?php echo number_format($invoice['value'] ,2,'.', ',')?></td>
                                                                    <td class="text-right col-xs-2">$<?php echo number_format($invoice['discount_amount'] ,2,'.', ',')?></td>
                                                                    <td class="text-right col-xs-2">
                                                                        <?php /*if($invoice['is_discounted'] == 1) { */?><!--
                                                                            $<?php /*echo number_format($invoice['total_after_discount'] ,2,'.', ',')*/?>
                                                                        <?php /*} else { */?>
                                                                            $<?php /*echo number_format($invoice['value'] ,2,'.', ',')*/?>
                                                                        --><?php /*} */?>

                                                                        $<?php echo number_format($invoice['total_after_discount'] ,2,'.', ',')?>
                                                                    </td>
                                                                    <!-- End Invoice Summary -->


                                                                    <td>
                                                                        <?php if(check_access_permissions_for_view($security_details, 'view_admin_invoice')) { ?>
                                                                            <a class="hr-edit-btn invoice-links" href="<?php echo base_url('manage_admin/invoice/view_admin_invoice') . '/' . $invoice['sid']; ?>">View Invoice</a>
                                                                        <?php } ?>


                                                                        <?php if(check_access_permissions_for_view($security_details, 'apply_discount_admin_invoice')) { ?>
                                                                            <?php if($invoice['payment_status'] == 'unpaid') { ?>
                                                                                <a class="hr-edit-btn invoice-links" href="<?php echo base_url('manage_admin/invoice/apply_discount_admin_invoice') . '/' . $invoice['sid']; ?>">Apply Discount</a>
                                                                            <?php } else { ?>
                                                                                <a class="hr-edit-btn invoice-links disabled-btn" href="javascript:void(0);">Apply Discount</a>
                                                                            <?php } ?>
                                                                        <?php } ?>

                                                                        <?php if(check_access_permissions_for_view($security_details, 'process_payment_admin_invoice')) { ?>
                                                                            <?php if($invoice['payment_status'] == 'unpaid' && $invoice['discount_amount'] < $invoice['value']) { ?>
                                                                                <a class="hr-edit-btn invoice-links" href="<?php echo base_url('manage_admin/misc/process_payment_admin_invoice') . '/' . $invoice['sid']; ?>">Process Payment</a>
                                                                            <?php } elseif($invoice['discount_amount'] == $invoice['value'] && $invoice['payment_status'] == 'unpaid') { ?>
                                                                                <button type="button" class="hr-edit-btn invoice-links" onclick="fActivateInvoiceFeatures(<?php echo $invoice['company_sid']; ?>, <?php echo $invoice['sid']?>);">Activate Invoice</button>
                                                                            <?php } else { ?>
                                                                                <a class="hr-edit-btn invoice-links disabled-btn" href="javascript:void(0);">Process Payment</a>
                                                                            <?php } ?>
                                                                        <?php } ?>

                                                                        <?php if(check_access_permissions_for_view($security_details, 'delete_admin_invoice')) { ?>
                                                                            <?php if($invoice['payment_status'] == 'unpaid') { ?>
                                                                                <form id="form_delete_invoice_<?php echo $invoice['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo base_url('manage_admin/invoice/list_admin_invoices'); ?>">
                                                                                    <input type="hidden" id="perform_action" name="perform_action" value="delete_admin_invoice" />
                                                                                    <input type="hidden" id="invoice_sid" name="invoice_sid" value="<?php echo $invoice['sid']; ?>" />
                                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $invoice['company_sid']; ?>" />
                                                                                    <button type="button" class="hr-delete-btn invoice-links" onclick="fDeleteInvoice(<?php echo $invoice['sid']; ?>);" >Delete Invoice</button>
                                                                                </form>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan="5" class="text-center">
                                                                    <span class="no-data">No Invoices</span>
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
<script>
    $(document).ready(function () {
        $('select').on('change', func_build_search_url).trigger('change');
    });

    function func_build_search_url(){
        var year = $('#year').val();
        var month = $('#month').val();
        var company = $('#company').val();
        var payment_status = $('#payment_status').val();
        var payment_method = $('#payment_method').val();

        var url = '<?php echo base_url('manage_admin/invoice/list_admin_invoices'); ?>';

        url = url + '/' + year + '/' + month + '/' + company + '/' + payment_status + '/' + payment_method;

        $('#search_btn').attr('href', url);
    }

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

    function fActivateInvoiceFeatures(company_sid, invoice_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Activate all Products against this Invoice? <br /> This action is irreversable!',
            function () {
                var myUrl = '<?php echo base_url('manage_admin/invoice/ajax_responder')?>';
                var myRequest;

                myRequest = $.ajax({
                    url : myUrl,
                    type: 'POST',
                    data: {
                        perform_action: 'activate_invoice_features',
                        company_sid: company_sid,
                        invoice_sid: invoice_sid
                    }
                });

                myRequest.done(function (response) {
                    console.log(response);

                    if(response == 'success'){
                        myUrl = window.location.href;
                        window.location = myUrl;
                    }
                })

            },
            function () {
               //Cancel
            });
    }
</script>