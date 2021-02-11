<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left">Marketing Agency: <b><?php echo ucwords($name); ?></b></h1>
                    <div class="btn-panel float-right">
                        <a href="<?= base_url() ?>dashboard" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="hr-search-main">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                        <div class="form-group">
                            <label>Company Name:</label>
<!--                            <div class="hr-select-dropdown">-->
                                <select class="form-control chosen-select" id="company_name" multiple="multiple">
                                    <option value="">Please Select Company</option>
                                    <?php if(!empty($all_companies)) { ?>
                                        <?php foreach($all_companies as $company) { ?>
                                            <option value="<?php echo $company['company_name']; ?>" <?php echo in_array($company['company_name'],$company_name_array) ? 'selected="selected"' : '';?>><?php echo ucwords($company['company_name']); ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
<!--                            </div>-->
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>Payment Status:</label>
                                    <?php $payment_type = $this->uri->segment(3);?>
<!--                                    <div class="hr-select-dropdown">-->
                                        <select class="form-control" name="payment_type" id="payment_type">
                                            <option value="all" <?php echo $payment_type == 'all' ? 'selected="selected"' : '';?>>All</option>
                                            <option value="paid" <?php echo $payment_type == 'paid' ? 'selected="selected"' : '';?>>Paid</option>
                                            <option value="unpaid" <?php echo $payment_type == 'unpaid' ? 'selected="selected"' : '';?>>Unpaid</option>
                                        </select>
<!--                                    </div>-->
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>Date From:</label>
                                    <?php $date_from = $this->uri->segment(4) != 'all' && $this->uri->segment(4) != '' ? urldecode($this->uri->segment(4)) : ''; ?>
                                    <input type="text" name="date_from" value="<?php echo $date_from;?>" class="form-control" id="start_date" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label>Date To:</label>
                                    <?php $date_to = $this->uri->segment(5) != 'all' && $this->uri->segment(5) != '' ? urldecode($this->uri->segment(5)) : ''; ?>
                                    <input type="text" name="date_to" value="<?php echo $date_to;?>" class="form-control" id="end_date" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group pull-right">
                            <a href="#" id="btn_apply_filters" class="btn btn-primary btn-sm">Search</a>
                            <a href="<?php echo base_url('invoice'); ?>" class="btn btn-primary btn-sm" style="margin-right: 10px;">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!empty($all_vouchers)) { ?>
            <?php foreach($all_vouchers as $company => $commission_invoices) { ?>
            <div class="row">
                <div class="col-xl-12">
<!--                    <div class="hr-box-header bg-header-green">-->
<!--                        <span class="hr-registered pull-left"><span class="text-success"></span>Company: --><?php //echo ucwords($company); ?><!--</span>-->
<!--                    </div>-->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5><strong>Company: <?php echo ucwords($company); ?></strong></h5>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-dark">
                                    <tr class="d-flex">
                                        <th class="col-1">S. No</th>
                                        <th class="col-2">Voucher Number</th>
                                        <th class="col-2">Commission Invoice No</th>
                                        <th class="col-2">Commission Type</th>
                                        <th class="col-3">Payment Date</th>
                                        <th class="col-1">Paid</th>
                                        <th class="col-1 text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(sizeof($commission_invoices) > 0) { $total_commission = 0;
                                        foreach ($commission_invoices as $key => $voucher) {
                                            ?>
                                            <tr class="d-flex">
                                                <td class="col-sm-1"><?= $key + 1; ?></td>
                                                <td class="col-sm-2"><?= $voucher['voucher_number'] ?></td>
                                                <td class="col-sm-2"><?= $voucher['commission_invoice_no'];?></td>
                                                <td class="col-sm-2"><?= ucwords($voucher['commission_applied']);
                                                    echo $voucher['commission_applied'] == 'secondary' ? '<br><b> Referred to:</b> ' . $voucher['secondary_agency'] : ''; ?></td>
                                                <td class="col-sm-3"><?= $voucher['payment_status'] == 'paid' ? date_with_time($voucher['payment_date']) : '<b>N/A</b>'; ?></td>
                                                <td class="col-sm-1"><?= '$' . ($voucher['paid_amount']); $total_commission += $voucher['paid_amount'];?></td>
                                                <td class="col-sm-1 text-center"><a href="<?= base_url('invoice/view_voucher/'.$voucher['sid'])?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a></td>
                                            </tr>
                                        <?php }?>

                                        <tr class="d-flex">
                                            <td class="col-sm-11 text-right"><b>Total: </b></td>
                                            <td class="col-sm-1"><?= '$' . $total_commission;?></td>
                                        </tr>
                                    <?php } else{ ?>
                                        <tr class="d-flex">
                                            <td class="col-sm-12 text-center"><b>No Voucher Found</b></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        <?php } else { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <tbody>
                            <tr class="d-flex">
                                <div class="col-sm-12 text-center"><b>You do not have any paying clients at this time.</b></div>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url(){
        var company_name = $('#company_name').val();
        var payment_type = $('#payment_type').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        company_name = company_name != '' && company_name != null && company_name != undefined && company_name != 0 ? encodeURIComponent(company_name) : 'all';
        payment_type = payment_type != '' && payment_type != null && payment_type != undefined && payment_type != 0 ? encodeURIComponent(payment_type) : 'all';
        start_date = start_date != '' && start_date != null && start_date != undefined && start_date != 0 ? encodeURIComponent(start_date) : 'all';
        end_date = end_date != '' && end_date != null && end_date != undefined && end_date != 0 ? encodeURIComponent(end_date) : 'all';

        var url = '<?php echo base_url('invoice'); ?>' + '/' + company_name + '/' + payment_type + '/' + start_date + '/' + end_date;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function (){

        $('.chosen-select').selectize({
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

        $('#start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
            onSelect: function (value) {
                //console.log(value);
                $('#end_date').datepicker('option', 'minDate', value);

//                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date').val());

        $('#end_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
            onSelect: function (value) {
                //console.log(value);
                $('#start_date').datepicker('option', 'maxDate', value);

//                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date').val());

        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });
    });
</script>
