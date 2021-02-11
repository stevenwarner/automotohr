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
                                    <form enctype="multipart/form-data" method="POST">
                                        <div class="heading-title page-title">
                                            <span class="page-title"><i class="fa fa-briefcase"></i><?php echo $page_title; ?></span>
                                            <a href="<?php echo base_url('manage_admin/marketing_agencies'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Marketing Agencies</a>
                                        </div>
                                        <br /><hr />
                                        <div class="heading-title page-title">
                                            <span class="page-title">Marketing Agency:</span>&nbsp;<span class="text-success"><?php echo ucwords($marketing_agency_info['full_name']); ?></span><!--&nbsp;<span class="text-default"><small><?php /*echo $marketing_agency_info['email'] != '' ? '( ' . $marketing_agency_info['email'] . ' )' : ''; */?></small></span>-->
                                            <a href="<?= base_url('manage_admin/marketing_agencies/add_manual_commission/'.$marketing_agency_info['sid']); ?>" class="btn btn-success pull-right" style="margin-left: 5px;">Add Commissions</a>
                                        </div>
                                    </form>

                                   
                                    <div class="hr-search-criteria">
                                        <strong>Export CSV Filters</strong>
                                    </div>

                                    <div class="hr-search-main">
                                        <form method="POST" action="">
                                            <input type="hidden" id="perform_action" name="perform_action" value="perform_action" />
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                                                <label>Company Name:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="chosen-select" multiple="multiple" name="company_name[]">
                                                        <option value="">Please Select Company</option>
                                                            <?php if(!empty($marketing_agencies_group)) { ?>
                                                                <?php foreach($marketing_agencies_group as $company => $commission_invoices) { ?>
                                                                    <option value="<?php echo $company; ?>" ><?php echo ucwords($company); ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                    </select>
                                                    <?php echo form_error('cat_sid'); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label>Payment Status:</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="payment_type" id="payment_type">
                                                                <option value="all">All</option>
                                                                <option value="paid">Paid</option>
                                                                <option value="unpaid">Unpaid</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label>Date From:</label>
                                                        <input type="text" name="date_from" value="" class="invoice-fields end_date" autocomplete="off">
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label>Date To:</label>
                                                        <input type="text" name="date_to" value="" class="invoice-fields start_date" autocomplete="off">
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                                                <div class="row text-right">
                                                    <?php $company_id = $this->uri->segment(4); ?>
                                                    <a href="<?php echo base_url('manage_admin/marketing_agencies/manage_commissions/'.$company_id); ?>" class="btn btn-success" style="margin-right: 10px;">Clear</a>
                                                    <button type="submit" class="btn btn-success filters">Export CSV</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="hr-search-criteria">
                                        <strong>Search Filters</strong>
                                    </div>

                                    <div class="hr-search-main" id="search_filter_panel">
                                        <form method="GET" action="">
                                            <input type="hidden" id="perform_action" name="perform_action" value="perform_action" />
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                                                <label>Company Name:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="chosen-select" multiple="multiple" name="company_name[]" id="marketing_agencies_name">
                                                        <option value="">Please Select Company</option>
                                                            <?php if(!empty($marketing_agencies_group)) { ?>
                                                                <?php foreach($marketing_agencies_group as $company => $commission_invoices) { ?>
                                                                    <option value="<?php echo $company; ?>" <?php echo $search_filters['companies'] != 'all' && in_array($company, $search_filters['companies']) ? 'selected="selected"' : ''; ?> ><?php echo ucwords($company); ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                    </select>
                                                    <?php echo form_error('cat_sid'); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label>Payment Status:</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="payment_type" id="marketing_agencies_payment_type">
                                                                <option value="all" <?php echo isset($search_filters['payment']) && $search_filters['payment'] == 'all' ? 'selected="selected"' : ''; ?>>All</option>
                                                                <option value="paid" <?php echo isset($search_filters['payment']) && $search_filters['payment'] == 'paid' ? 'selected="selected"' : ''; ?>>Paid</option>
                                                                <option value="unpaid" <?php echo isset($search_filters['payment']) && $search_filters['payment'] == 'unpaid' ? 'selected="selected"' : ''; ?>>Unpaid</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label>Date From:</label>
                                                        <input type="text" name="date_from" id="marketing_agencies_from_date" value="<?php echo $search_filters['end'] != 'all' ? $search_filters['start'] : ''; ?>" class="invoice-fields end_date" autocomplete="off">
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label>Date To:</label>
                                                        <input type="text" name="date_to" id="marketing_agencies_to_date" value="<?php echo $search_filters['start'] != 'all' ? $search_filters['end'] : ''; ?>" class="invoice-fields start_date" autocomplete="off">
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                                                    <div class="row text-right">
                                                        <?php $company_id = $this->uri->segment(4); ?>
                                                        <a href="<?php echo base_url('manage_admin/marketing_agencies/manage_commissions/'.$company_id); ?>" class="btn btn-success" style="margin-right: 10px;">Clear</a>
                                                        <a href="javascript:;" id="search" class="btn btn-success">Search</a>
                                                    </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="text-right">
                                                <a target="_blank" type="button" class="btn btn-success btn-sm" id="pdf" href="<?= str_replace('manage_commissions','pdf_download',current_url());?>">Download PDF</a>
                                                <a target="_blank" href="javascript:;"  onclick="PrintElem('#mydiv')" class="btn btn-success btn-sm">Print</a>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if(!empty($marketing_agency_invoices_group)) {
                                        $all_company_commission = 0; ?>
                                        <?php foreach($marketing_agency_invoices_group as $company => $commission_invoices) {
                                            $total_commission = 0; ?>
                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <span class="hr-registered pull-left"><span class="text-success"></span><?php echo ucwords($company); ?></span>
                                                    <span class="hr-registered pull-right" id="<?= str_replace(' ','-',$company);?>" style="font-size: 18px"></span>
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hoverg table-stripped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center col-xs-2">Date</th>
                                                                    <th class="col-xs-6">Invoice Number</th>
                                                                    <th class="text-center col-xs-2">Commission Payable</th>
                                                                    <th class="text-center col-xs-2" colspan="4">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php   if(!empty($commission_invoices)) {
                                                                        foreach($commission_invoices as $invoice) {
                                                                            $invoice['payment_status'] == 'paid' ? $total_commission += $invoice['total_commission_after_discount'] : ''; ?>
                                                                        <tr id="parent_<?=$invoice['sid']?>">
                                                                            <td class="text-center"><?php echo convert_date_to_frontend_format($invoice['created'], true); ?></td>
                                                                            <td class="text-left">
                                                                                <?php echo STORE_CODE . '-' . str_pad($invoice['invoice_sid'], 6, 0, STR_PAD_LEFT); ?><br>
                                                                                <?php echo $invoice['payment_status'] == 'paid' ? '<b>Payment Date:</b> '.convert_date_to_frontend_format($invoice['payment_date']) : '<b>Un-Paid</b>';?><br>
                                                                                <?php echo $invoice['invoice_number']; ?>
                                                                            </td>
                                                                            <td class="text-right" style="font-size: 16px">
                                                                                <?php if($invoice['discount_percentage'] > 0 && $invoice['discount_amount']> 0 ) { ?>
                                                                                        <span <?php echo $invoice['payment_status'] == 'paid' ? 'class="text-success"' : 'style="color: red"';?>>
                                                                                            <b>$<?php echo number_format($invoice['total_commission_after_discount'], 2); ?></b>
                                                                                        </span>
                                                                                <?php } else { ?>
                                                                                        <span <?php echo $invoice['payment_status'] == 'paid' ? 'class="text-success"' : 'style="color: red"';?>>
                                                                                            <b>$<?php echo number_format($invoice['commission_value'], 2); ?></b>
                                                                                        </span>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a href="javascipt:;" class="btn btn-danger btn-sm" title="Delete Invoice & Voucher"  data-toggle="tooltip" onclick="delete_invoice(<?= $invoice['sid'] ?>, 'delete_inovice_and_voucher', 'Invoice and Payment Voucher')"><i class="fa fa-times"></i></a>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a href="<?php echo base_url('manage_admin/marketing_agencies/view_commission_details/' . $invoice['sid']); ?>" class="btn btn-success btn-sm">View Details</a>
                                                                            </td>
                                                                    <?php   if($invoice['payment_voucher_sid'] == 0) {
                                                                                $voucher_title = 'Generate Voucher';
                                                                                $css_class = 'btn-warning';
                                                                                echo '<td class="text-center"><a href="javascipt:;" class="btn btn-danger btn-sm disabled" title="Voucher Not Found" data-toggle="tooltip"><i class="fa fa-times"></i></a></td>';
                                                                            } else {
                                                                                $voucher_title = 'View Voucher';
                                                                                $css_class = 'btn-success'; ?>
                                                                                <td class="text-center">
                                                                                    <a href="javascipt:;" class="btn btn-danger btn-sm" title="Delete Voucher Data" data-toggle="tooltip" onclick="delete_invoice(<?=$invoice['sid']?>, 'delete_voucher_data', 'Payment Voucher')"><i class="fa fa-times"></i></a>
                                                                                </td>
                                                                    <?php   } ?>
                                                                            <td class="text-center">
                                                                                <a href="<?php echo base_url('manage_admin/marketing_agencies/payment_voucher/' . $invoice['sid']); ?>" class="btn <?=$css_class?> btn-sm"><?php echo $voucher_title; ?></a>
                                                                            </td>
                                                                        </tr>
<!--                                                                        <input type="hidden" class="company-total-commission" value="--><?//= $invoice['payment_status'] == 'paid' ? $total_commission += $invoice['total_commission_after_discount'] : $total_commission;?><!--">-->
                                                                    <?php } ?>
                                                                    <tr>
                                                                        <td class="text-right" colspan="6"><b>Total: </b></td>
                                                                        <td class="text-success text-right" colspan="7" style="font-size: 16px"><?= '<b>$' . $total_commission . '</b>'; $all_company_commission += $total_commission;?></td>
                                                                        <script type="text/javascript">$('#<?= str_replace(' ','-',$company);?>').html('<?= 'Total Paid: $' . $total_commission;?>');</script>
                                                                    </tr>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td class="text-center" colspan="8">
                                                                            <span class="no-data">No Invoices</span>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green pull-right">
                                                <span class="hr-registered pull-right" style="font-size: 18px"><span class="text-success"></span><?= ucwords($marketing_agency_info['full_name']); ?> - Grand Total: <b>$<?= $all_company_commission; ?></b></span>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="hr-box">
                                            <div class="hr-innerpadding text-center">
                                                <span class="no-data">This Marketing Agency Has not Brought any Business Yet</span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main" id="mydiv" style="display: none;">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <span class="page-title">Marketing Agency:</span>&nbsp;<span class="text-success"><?php echo ucwords($marketing_agency_info['full_name']); ?></span><!--&nbsp;<span class="text-default"><small><?php /*echo $marketing_agency_info['email'] != '' ? '( ' . $marketing_agency_info['email'] . ' )' : ''; */?></small></span>-->
                                    </div>
                                    <?php if(!empty($marketing_agency_invoices_group)) {
                                        $all_company_commission = 0; ?>
                                        <?php foreach($marketing_agency_invoices_group as $company => $commission_invoices) {
                                            $total_commission = 0; ?>
                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <span class="hr-registered pull-left"><span class="text-success"></span><?php echo ucwords($company); ?></span>
                                                    <span class="hr-registered pull-right" id="<?= str_replace(' ','_',$company);?>" style="font-size: 18px"></span>
                                                </div>
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hoverg table-stripped">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center col-xs-3">Date</th>
                                                                <th class="col-xs-6">Invoice Number</th>
                                                                <th class="text-center col-xs-3">Commission Payable</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(!empty($commission_invoices)) {
                                                                foreach($commission_invoices as $invoice) {
                                                                    if ($invoice['payment_status'] == 'paid') {
                                                                        $total_commission += $invoice['total_commission_after_discount']; ?>
                                                                        <tr id="parent_<?= $invoice['sid'] ?>">
                                                                            <td class="text-center"><?php echo convert_date_to_frontend_format($invoice['created'], true); ?></td>
                                                                            <td class="text-left">
                                                                                <?php echo STORE_CODE . '-' . str_pad($invoice['invoice_sid'], 6, 0, STR_PAD_LEFT); ?>
                                                                                <br>
                                                                                <?php echo $invoice['payment_status'] == 'paid' ? '<b>Payment Date:</b> ' . convert_date_to_frontend_format($invoice['payment_date']) : '<b>Un-Paid</b>'; ?>
                                                                                <br>
                                                                                <?php echo $invoice['invoice_number']; ?>
                                                                            </td>
                                                                            <td class="text-right" style="font-size: 16px">
                                                                                <?php if ($invoice['discount_percentage'] > 0 && $invoice['discount_amount'] > 0) { ?>
                                                                                    <span <?php echo $invoice['payment_status'] == 'paid' ? 'class="text-success"' : 'style="color: red"';?>>
                                                                                        <b>$<?php echo number_format($invoice['total_commission_after_discount'], 2); ?></b>
                                                                                    </span>
                                                                                <?php } else { ?>
                                                                                    <span <?php echo $invoice['payment_status'] == 'paid' ? 'class="text-success"' : 'style="color: red"';?>>
                                                                                        <b>$<?php echo number_format($invoice['commission_value'], 2); ?></b>
                                                                                    </span>
                                                                                <?php } ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php }
                                                                }?>
                                                                <tr>
                                                                    <td class="text-right" colspan="2"><b>Total: </b></td>
                                                                    <td class="text-success text-right" colspan="3" style="font-size: 16px"><?= '<b>$' . $total_commission . '</b>'; $all_company_commission += $total_commission;?></td>
                                                                    <script type="text/javascript">$('#<?= str_replace(' ','_',$company);?>').html('<?= '<b>Total Paid: $' . $total_commission;?> </b>');</script>
                                                                </tr>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td class="text-center" colspan="8">
                                                                        <span class="no-data">No Invoices</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <span class="hr-registered pull-right" style="font-size: 18px"><span class="text-success"></span><?= ucwords($marketing_agency_info['full_name']); ?> - Grand Commission: <b>$<?= $all_company_commission; ?></b></span>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="hr-box">
                                            <div class="hr-innerpadding text-center">
                                                <span class="no-data">This Marketing Agency Has not Brought any Business Yet</span>
                                            </div>
                                        </div>
                                    <?php } ?>
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
    function PrintElem(elem) {
        $(".bottom-buttons").hide();
        Popup($(elem).html());
        $(".bottom-buttons").show();
    }

    function Popup(data) {
        var mywindow = window.open('', 'Print Invoice', 'height=800,width=1200');
        mywindow.document.write('<html><head><title>Marketing Agency: <?= ucwords($marketing_agency_info['full_name']); ?></title>');
        /*optional stylesheet*/
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10
        return true;
    }
    function func_delete_marketing_agency(sid) {
        alertify.confirm(
        'Are you sure?',
        'Are you sure you want to delete this Marketing Agency?',
        function () {
            $('#form_delete_marketing_agency_' + sid).submit();
        }, function () {
            alertify.error('Cancelled');
        });
    }
       
    function delete_invoice(id, perform_action, message) {
        url = "<?= base_url() ?>manage_admin/marketing_agencies/ajax_responder";
        alertify.confirm('Confirmation Delete', "Are you sure you want to delete "+message+"?",
                function () {
                    $.post(url, {perform_action: perform_action, sid: id})
                            .done(function (data) {
                                $("#parent_" + id).remove();
                                alertify.success('Success: '+message+' Deleted.');
                                
                                if(perform_action == 'delete_voucher_data') {
                                    location.reload();
                                }
                            });
                },
                function () {
                    alertify.error('Canceled');
                });
    }
    
    $(document).ready(function () {
        var total_commission = $('#company-total-commission').val();
        $('#company-total-amount').html('Total Paid Amount: '+total_commission);
        $('[data-toggle=tooltip]').tooltip();

        $('.start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
            onSelect: function (value) {
                generate_search_url();
            }
        });

        $('.end_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
            onSelect: function (value) {
                generate_search_url();
            }
        });

        var company_select = $('.chosen-select').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        var search_status = '<?php echo $search_filter_status; ?>';
        if (search_status == 1) {
            $('#search_filter_panel').css('display','block');
        }
    });

    $('#marketing_agencies_name').on('change', function () {
        generate_search_url();
    });

     $('#marketing_agencies_payment_type').on('change', function () {
        generate_search_url();
    });

    function generate_search_url() {
        var startdate = $('#marketing_agencies_from_date').val();
        var enddate = $('#marketing_agencies_to_date').val();
        var agencies_name = $('#marketing_agencies_name').val();
        var payment_method = $('#marketing_agencies_payment_type').val();
        var marketing_agency_sid = '<?php echo $marketing_agency_info['sid']; ?>';

        startdate = startdate != '' && startdate != null && startdate != undefined && startdate != 0 ? encodeURIComponent(startdate) : 'all';
        enddate = enddate != '' && enddate != null && enddate != undefined && enddate != 0 ? encodeURIComponent(enddate) : 'all';
        agencies_name = agencies_name != '' && agencies_name != null && agencies_name != undefined && agencies_name != 0 ? encodeURIComponent(agencies_name) : 'all';
        payment_method = payment_method != '' && payment_method != null && payment_method != undefined && payment_method != 0 ? encodeURIComponent(payment_method) : 'all';


        var url = '<?php echo base_url('manage_admin/marketing_agencies/manage_commissions'); ?>' + '/' + marketing_agency_sid + '/' + startdate + '/' + enddate + '/' + agencies_name + '/' + payment_method + '/' ;

        $('#search').attr('href', url);
    }
</script>


