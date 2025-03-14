<?php 
    $start_date = $this->uri->segment(6) ? $this->uri->segment(6) : date('m-01-Y');
    $end_date   = $this->uri->segment(7) ? $this->uri->segment(7) : date('m-t-Y');
    $customer   = $this->uri->segment(2) ? $this->uri->segment(2) : 'all';
    $invoice    = $this->uri->segment(3) && $this->uri->segment(3) != 'all' ? $this->uri->segment(3) : '';
    $payment_method = $this->uri->segment(4) ? $this->uri->segment(4) : 'all';
    $status         = $this->uri->segment(5) ? $this->uri->segment(5) : 'all';
 
    $info_box = '';
    $info_box .='<div class="col-xs-12 col-sm-6">';
    $info_box .='    <strong>Showing <span class="js-from-records">0</span> to <span class="js-to-records">0</span> Orders out of <span class="js-total">0</span></strong>';
    $info_box .='</div>';
    $info_box .='<div class="col-xs-12 col-sm-6">';
    $info_box .='    <div class="pull-right">';
    $info_box .='        <button class="btn btn-success js-export"><i class="fa fa-download"></i> &nbsp; Export CSV</button>';
    $info_box .='        <button class="btn btn-success js-print"><i class="fa fa-print"></i> &nbsp; Print</button>';
    $info_box .='    </div>';
    $info_box .='</div>';
?>
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
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back to Settings</a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <!--  -->
                    <div class="row">
                        <div class="applicant-reg-date">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <div class="form-wrp">
                                        <form id="form-filters" method="post" enctype="multipart/form-data" action="">
                                            
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="startdate">Start Date</label>
                                                    <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="Start Date" readonly="" value="<?=$start_date;?>">
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="enddate">End Date</label>
                                                    <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="End Date" readonly="" value="<?=$end_date;?>">
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="username">Customer Name</label>
                                                    <select id="customer_name" class="invoice-fields" name="customer_name">
                                                        <option <?=$customer == 'all' ? 'selected="true"' : '';?> value="all">All</option>
                                                        <?php if(sizeof($employees)) {  ?>
                                                        <?php foreach ($employees as $k0 => $v0) { ?>
                                                            <option <?=$customer == $v0['sid'] ? 'selected="true"' : '';?> value="<?=$v0['sid'];?>"><?=$v0['sid'] == $employer_sid ? 'You' : remakeEmployeeName($v0);?></option>
                                                        <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="username">Invoice</label>
                                                    <input type="text" id="invoice" class="invoice-fields" name="invoice" value="<?=$invoice;?>">
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Payment Method</label>
                                                    <div class="hr-select-dropdown ">
                                                        <select class="chosen-select js-payment-method" name="payment_method" id="payment_method" >
                                                            <option <?=$payment_method == 'any' ? 'selected="true"' : '';?> value="any">Any Payment Method</option>
                                                            <option <?=$payment_method == 'background_check_refund' ? 'selected="true"' : '';?> value="background_check_refund">Background Check Refund</option>
                                                            <option <?=$payment_method == 'Free_checkout' ? 'selected="true"' : '';?> value="Free_checkout">Free Checkout</option>
                                                            <option <?=$payment_method == 'Paypal' ? 'selected="true"' : '';?> value="Paypal">Paypal</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="chosen-select js-status" name="status" id="status" >
                                                            <option <?=$status == 'all' ? 'selected="true"' : '';?> value="all">All</option>
                                                            <option <?=$status == 'Paid' ? 'selected="true"' : '';?> value="Paid">Paid</option>
                                                            <option <?=$status == 'UnPaid' ? 'selected="true"' : '';?> value="UnPaid">UnPaid</option>
                                                            <option <?=$status == 'Pending' ? 'selected="true"' : '';?> value="Pending">Pending</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                                <div class="form-group pull-right">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <button class="btn btn-success btn-block" id="btn_apply_filters">Filter</button>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <a class="btn btn-success btn-block" href="javascript:redirect()">Clear</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    <br />
                    <!--  -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="margin-bottom: 20px;">
                            <strong id="invoiceCount">Placed Orders: <span class="js-total">0</span></strong>
                        </div>
                    </div>
                    <!--  -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="margin-bottom: 20px;">
                            <div class="hr-box-header js-info-box">
                                <?=$info_box;?>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive  table-outer" id="js-order-parent">
                                <table class="table table-stripped table-hover table-bordered" id="js-order">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Customer Name</th>   
                                            <th>Company Name</th>                                          
                                            <th class="col-xs-4">Description</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Payment Method</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center no-print">Actions</th>
                                        </tr> 
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <span class="no-data js-msg-row">Please wait, while we are fetching records...</span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="margin-top: 20px;">
                            <div class="hr-box-header js-info-box">
                                <?=$info_box;?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <!--  -->
                            <div class="table-responsive table-outer  remaining-products">
                                <strong id="invoiceCount">Purchased Available Market Place Product(s)</strong>
                                <div class="product-detail-area table-wrp data-table">
                                    <table class="table">
                                        <thead>
                                            <tr>           
                                                <td>Product</td>
                                                <td width="60%">Name</td>
                                                <td class="text-align">Remaining Quantity</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(sizeof($products)) { ?>
                                            <?php foreach ($products as $value) { ?>
                                                <tr>
                                                    <td>
                                                        <figure>
                                                            <?php if (!empty($value['product_image'])) { ?>
                                                                <img src="<?php echo AWS_S3_BUCKET_URL . $value['product_image']; ?>">
                                                            <?php } ?>
                                                        </figure>
                                                    </td>
                                                    <td>
                                                        <h3 class="details-title--polite"><?php echo $value['name']; ?></h3>
                                                    </td>
                                                    <td class="text-align"><?php echo $value['remaining_qty']; ?></td>
                                                </tr>
                                            <?php }} ?>
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

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">


<script>
    $('select').select2();

    function redirect(){
        window.location.href = "<?=base_url('order_history');?>/";
    }
    
    $("#startdate").datepicker({
        dateFormat: 'mm-dd-yy',
        yearRange: "1960:+5",
        changeYear: true,
        changeMonth: true,
        onSelect: function (selected) {
            var dt = $.datepicker.parseDate("mm-dd-yy", selected);
            dt.setDate(dt.getDate() + 1);
            $("#enddate").datepicker("option", "minDate", dt);
        }
    }).on('focusin', function () {
        $(this).prop('readonly', true);
    }).on('focusout', function () {
        $(this).prop('readonly', false);
    });

    $("#enddate").datepicker({
        dateFormat: 'mm-dd-yy',
        setDate: new Date(),
        changeYear: true,
        changeMonth: true,
        onSelect: function (selected) {
            var dt = $.datepicker.parseDate("mm-dd-yy", selected);
            dt.setDate(dt.getDate() - 1);
            $("#startdate").datepicker("option", "maxDate", dt);
        }
    }).on('focusin', function () {
        $(this).prop('readonly', true);
    }).on('focusout', function () {
        $(this).prop('readonly', false);
    });
</script>



<script>
     // Created on: 29-05-2019
    // jQuery IFFY
    $(function(){
        var base_url = "<?=base_url('order_history');?>/",
        total_records = 0,
        current_page = 1,
        limit = 100,
        total_pages = 1,
        xhr = null,
        from_pop = false,
        status_records = [],
        target_from = $('.js-from-records'),
        target_to = $('.js-to-records'),
        target_total = $('.js-total'),
        fetch_text = "Please wait, while we are fetching more results.",
        export_text = "Please wait, while we are generating CSV.",
        target_append = $('table#js-order tbody');

        $('.js-companies').select2();
        $('.js-product-type').select2();

        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            // Reset
            total_records = 0;
            current_page = 1;
            total_pages = 1;
            target_append.html('');
            //
            if(from_pop === false)
                history.pushState({url: generate_search_url()}, "", generate_search_url(true));
            
            load_results();
            from_pop = false;
        });

        $('#btn_apply_filters').trigger('click');

        loader(false);
        //
        $('.js-export').click(function(e) {
            e.preventDefault();
            loader('show', export_text);
            export_result('csv');
        });
        
        // Print
        $('.js-print').click(function(e) {
            e.preventDefault();
            alertify.customPrint('Please, select one of the options. <br /><br /> <strong>Print All</strong><p>It will print all the records matching current filter</p><br /> <strong>Print Current</strong><p>It will only print the records showing on this page.</p>', 
            )
            .set('labels', { printcurrent:'Print Current'})
            .set('closable', false)
            .set({
                onprintall: function(){ start_print_process('all'); },
                oncancel: function(){ return; },
                onprintcurrent: function(){ start_print_process('current'); },
            });
        });

        // Load on scroll
        $(window).scroll(function() {

            if($(window).scrollTop() > $('#js-order').height() - 100 ) load_results();
            // if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            //     load_results();
            // }
        });

        // Load records on DOM
        function load_results(do_print){
            if(current_page > total_pages) return false; 
            if(xhr != null) return false;
            loader();
            //
            xhr = $.post(generate_search_url(true), {
                action: 'get_records',
                total_records: total_records,
                page: current_page
            }, function(resp) {
                //
                // resp = $.parseJSON(resp);
                //
                if(resp.Status === false) return;
                if(resp.Data == '' || resp.Data == undefined) { reset_table(); return; }
                else if(resp.Data.length == 0) { reset_table(); return; }
                else {
                    $('.js-msg-row').hide(0);
                    $('.js-info-box').show(0);
                }

                if(current_page == 1){
                    limit = resp.Limit;
                    total_records = resp.TotalRecords;
                    total_pages   = resp.TotalPages;
                    if(resp.Overwrite === true)
                        status_records = resp.StatusRecords;
                    target_from.text(resp.from_records);
                }

                target_append.append(resp.Data);
                target_to.text(resp.to_records);
                target_total.text(resp.TotalRecords);
                checks_count = resp.TotalRecords;
                xhr = null;
                loader(false);
                if(current_page == total_pages) { 
                    if( do_print !== undefined) do_print();
                } 
                if(do_print !== undefined) load_results(do_print);
                current_page++;
            });
        }

        // Loader hide and show
        // @param is_show - Show/hide loader
        // @param content - Set text on loader
        function loader(is_show, content){
            if(is_show === false) $('.js-loader').hide(0);
            else $('.js-loader').show(0);

            if(content === undefined) $('.cs-loader-text').text(fetch_text);
            else $('.cs-loader-text').text(content);
        }

        //
        function export_result(type){
            type = type === undefined ? 'csv' : type;
            $.post(generate_search_url(true), {
                action: 'export',
                type: type,
                pages: 'all',
                total_records: total_records,
                page: current_page,
                status_records: status_records
            }, function(resp) {
                loader(false);
                window.location.href = "<?=base_url('order_history/download');?>/"+type+'/'+resp.Data;
            });
        }

        // 
        function start_print_process(type){
            if(type == 'current') do_print();
            else fetch_all_records_for_print();
        }

        //
        function do_print(){
            $('#print_from').remove();
            $('body').append('<div id="print_from"></div>');
            $('#print_from').html($('#js-order-parent').html());
            $('#print_from').find('table').prop('border', 1);
            $('#print_from').find('table').prop('cellspacing', 0);
            $('#print_from').find('.no-print').remove();
            console.log($('#print_from tr').length); 
            $('#print_from').print();
            $('#print_from').remove();
        }

        //
        function fetch_all_records_for_print(){
            var start_page = current_page - 1;
            if(start_page == total_pages) do_print();
            else if(start_page > total_pages) do_print();
            else load_results(do_print);
        }

        //
        function reset_table(){
            target_append.html('');
            target_to.text(0);
            target_from.text(0);
            target_total.text(0);
            $('.js-info-box').hide(0);
            $('.js-msg-row').html('No records found.').show(0);
        }

        //
        $.fn.extend({
            print: function() {
                var frameName = 'js-iframe',
                doc = window.frames[frameName];
                if (!doc) {
                    $('<iframe>').hide().attr('name', frameName).appendTo(document.body);
                    doc = window.frames[frameName];
                }
                doc.document.body.innerHTML = this.html();
                doc.window.print();
                return this;
            }
        });

        // Create print buttons
        alertify.dialog('customPrint', function() {
            var settings;
            return {
                setup: function() {
                    var settings = alertify.confirm().settings;
                    for (var prop in settings)
                        this.settings[prop] = settings[prop];
                    var setup = alertify.confirm().setup();
                    setup.buttons.push({ 
                        text: 'Print All',
                        key: 67 /*c*/ ,
                        scope: 'auxiliary',
                    }, { 
                        text: 'Print Current',
                        key: 67 /*c*/ ,
                        scope: 'auxiliary',
                    });
                    return setup;
                },
                settings: { onprintcurrent: null, onprintall: null },
                callback: function(closeEvent) {
                    if (closeEvent.index == 3) {
                        if (typeof this.get('onprintcurrent') === 'function') {
                            returnValue = this.get('onprintcurrent').call(this, closeEvent);
                            if (typeof returnValue !== 'undefined') {
                                closeEvent.cancel = !returnValue;
                            }
                        }
                        
                    } else if (closeEvent.index == 2) {
                         if (typeof this.get('onprintall') === 'function') {
                            returnValue = this.get('onprintall').call(this, closeEvent);
                            if (typeof returnValue !== 'undefined') {
                                closeEvent.cancel = !returnValue;
                            }
                        }
                    } else {
                        alertify.confirm().callback.call(this, closeEvent);
                    }
                }
            };
        }, false, 'confirm');


        function generate_search_url(show_url) {
            var 
            startDate = $('#startdate').val(),
            endDate   = $('#enddate').val(),
            payment_method = $('#payment_method').val(),
            status = $('#status').val(),
            customer_name = $('#customer_name').val(),
            invoice = $('#invoice').val()
            ;

            customer_name = customer_name != '' && customer_name != null && customer_name != undefined && customer_name != 0 ? encodeURIComponent(customer_name) : 'all';
            invoice = invoice != '' && invoice != null && invoice != undefined && invoice != 0 ? encodeURIComponent(invoice) : 'all';
            status = status != '' && status != null && status != undefined && status != 0 ? encodeURIComponent(status) : 'all';
            payment_method = payment_method != '' && payment_method != null && payment_method != undefined && payment_method != 0 ? encodeURIComponent(payment_method) : 'all';
            startDate = startDate != '' && startDate != null && startDate != undefined && startDate != 0 ? encodeURIComponent(startDate) : 'all';
            endDate = endDate != '' && endDate != null && endDate != undefined && endDate != 0 ? encodeURIComponent(endDate) : 'all';
            return (show_url === undefined ? '' : base_url)+customer_name + '/' + invoice + '/' + status + '/' + payment_method + '/' + startDate + '/' + endDate + '/';
        }

        //
        window.onpopstate = function(e) {
            if(e.state == null) return;
            var tmp = e.state.url.split('/');
            from_pop = true;
            // if(tmp.length > 0){
            //     $('.js-company option').prop('selected', false);
            //     $('.js-company option[value="'+tmp[0]+'"]').prop('selected', true);
            //     $('.js-company').select2('val', tmp[0]);
            //     $('.js-product-type').select2('val', ""+tmp[1]+"");
            //     $('.js-status').select2('val', ""+tmp[2]+"");
            //     $('.js-start-date').val(""+tmp[3]+"");
            //     $('.js-end-date').val(""+tmp[4]+"");
            //     $('button.js-search').trigger('click');
            // }
        }
    })
</script>



<!-- Loader -->
<div class="text-center cs-loader js-loader" >
    <div class="cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="cs-loader-text">Please wait, while we are fetching more results.</div>
    </div>
</div>

<style>
    .cs-loader{ position: fixed; top: 0; bottom: 0; left: 0; right: 0; width: 100%; z-index: 1; background: rgba(0,0,0,.5);}
    .cs-loader-box{ position: absolute; top: 50%; bottom: 0; left: 0; right: 0; width: 300px; margin: auto; margin-top: -190px;}
    .cs-loader-box i{ font-size: 14em; color: #81b431; }
    .cs-loader-box div.cs-loader-text{ display: block; padding: 10px; color: #000; background-color: #fff; border-radius: 5px; text-align: center; font-weight: 600; margin-top: 35px; }
    .cs-calendar{ margin-top: 10px; }
    /**/
    .ajs-ok{ display: none !important; }
    .ajs-button{ background-color: #81b431 !important; color: #ffffff !important; padding-left: 5px !important; padding-right: 5px !important; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -o-border-radius: 4px; border-color: #4cae4c !important; }
    .ajs-header{ background-color: #81b431 !important; color: #ffffff !important; }
</style>