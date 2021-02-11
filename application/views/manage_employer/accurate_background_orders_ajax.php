<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php 
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
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?><!-- main/employer_column_left_view -->
                </div>  
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php echo $title; ?> </span>
                        </div>
                        <div class="box-wrapper">
                            <div class="row">
                                <div class="applicant-reg-date">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="rows">
                                            <form id="form-filters" method="post" enctype="multipart/form-data" action="">
                                                <div class="row" >
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="form-col-100">
                                                            <label for="username">Username</label>
                                                            <input type="text" id="username" class="invoice-fields" name="username" value="<?php echo $this->uri->segment(2) != 'all' ? urldecode($this->uri->segment(2)) : ''; ?>">
                                                            <input type="hidden" id="search_sid" name="search_sid" value="<?php echo $this->uri->segment(3) != 0 ? urldecode($this->uri->segment(3)) : 0; ?>">
                                                            <div class="video-link" style='font-style: italic;'><b>Hint: </b> Type in the name and system will give suggestion. </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <div class="form-col-100">
                                                            <label>Order Placed By</label>
                                                            <?php $order_by = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                                            <select class="" id="order_by" name="order_by">
                                                                <option value="all">All</option>
                                                                <?php foreach ($company_accounts as $account) { ?>
                                                                    <option value="<?php echo $account["sid"] ?>" <?= $order_by == $account["sid"] ? 'selected' : '';?>>
                                                                        <?php if ($employer_id == $account["sid"]) { ?>
                                                                            You
                                                                        <?php } else {
                                                                            if($account["is_executive_admin"] == 1) {
                                                                                $employee_type = "Executive Admin";
                                                                            } else {
                                                                                $employee_type = $account["access_level"];
                                                                            }
                                                                            echo $account["first_name"]."&nbsp;".$account["last_name"].' ('.$employee_type.')';
                                                                        } ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                            <div class="video-link" style='font-style: italic;'><b>Hint: </b> Employee name who executed the report</div>
                                                            <!-- <label for="keyword">Order By</label> -->
                                                            <!-- <input type="text" id="order_by" class="invoice-fields" name="order_by" value="<?php //echo $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : ''; ?>-->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 margin-top">
                                                        <div class="form-col-100">
                                                            <label>Status</label>
                                                            <?php $statuses = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : 'all';
                                                            $statuses = explode(',',$statuses); ?>
                                                            <div class="hr-select-dropdown ">
                                                                <select class="chosen-select" multiple="multiple" name="status[]" id="status" >
                                                                    <option value="all" <?= in_array('all',$statuses) ? 'selected' : '';?>>All</option>
                                                                    <option value="draft" <?= in_array('draft',$statuses) ? 'selected' : '';?>>Awaiting Candidate Input</option>
                                                                    <option value="pending" <?= in_array('pending',$statuses) ? 'selected' : '';?>>Pending</option>
                                                                    <option value="cancelled" <?= in_array('cancelled',$statuses) ? 'selected' : '';?>>Cancelled </option>
                                                                    <option value="completed" <?= in_array('completed',$statuses) ? 'selected' : '';?>>Completed</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 margin-top">
                                                        <div class="form-col-100">
                                                            <label>Product Type</label>
                                                            <?php $type = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : 'all'; ?>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="product_type" id="product_type">
                                                                    <option value="all" <?= $type == 'all' ? 'selected' : '';?>>All</option>
                                                                    <option value="background-checks" <?= $type == 'background-checks' ? 'selected' : '';?>>Background Checks</option>
                                                                    <option value="drug-testing" <?= $type == 'drug-testing' ? 'selected' : '';?>>Drug Testing</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 margin-top">
                                                        <div class="form-col-100">
                                                            <?php $startdate = $this->uri->segment(7) != 'all' && $this->uri->segment(7) != '' ? urldecode($this->uri->segment(7)) : date('m-01-Y');?>
                                                            <label for="startdate">Start Date</label>
                                                            <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="Start Date" readonly="" value="<?php echo set_value('startdate', $startdate);?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 margin-top">
                                                        <div class="form-col-100">
                                                            <?php $end_date = $this->uri->segment(8) != 'all' && $this->uri->segment(8) != '' ? urldecode($this->uri->segment(8)) : date('m-t-Y');?>
                                                            <label for="enddate">End Date</label>
                                                            <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="End Date" readonly="" value="<?php echo set_value('enddate', $end_date);?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 pull-right">
                                                    <div class="report-btns">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <button class="form-btn js-search">Filter</button>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <a class="form-btn" href="javascript:void()">Clear</a>
                                                                <!--                                                                        <button class="form-btn" onclick="fClearDateFilters();">Clear</button>-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="">
                            <?php if ($is_check_active == 1) { ?>
                                <strong id="invoiceCount">Placed Orders: <span class="js-total">0</span></strong>
                                <br />
                                <br />
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 js-info-box">
                                        <div class="hr-box-header hr-box-footer" style="margin-bottom: 20px">
                                            <?=$info_box;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive table-outer" id="js-data-table">
                                    <table class="table table-bordered table-stripped table-hover " id="example"  data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th><a>Date</a></th>
                                                <th><a>Ordered By</a></th>
                                                <th><a>Username</a></th>
                                                <th><a>User Type</a></th>
                                                <th><a>Product Name</a></th>
                                                <th><a>Product Type</a></th>
                                                <th><a>Company Name</a></th>
                                                <th>Status</th>
                                                <th class="no-print"><a>Action</a></th>
                                            </tr> 
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9" class="text-center">
                                                    <span class="no-data js-msg-row">Please wait, while we are fetching records...</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 js-info-box">
                                        <div class="hr-box-header hr-box-footer" style="margin-top: 20px">
                                            <?=$info_box;?>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="table-wrp data-table product-detail-area mylistings-wrp text-center">
                                    <p>Background checks not activated.</p>
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
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">

<script>
    var applicantXHR = null;
    function fApplyDateFilters(){
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();
        var status = $('#status').val();
        var product_type = $('#product_type').val();
        var username = $('#username').val();
        var search_sid = $('#search_sid').val();
        var order_by = $('#order_by').val();
        url = '<?php echo base_url();?>' + 'accurate_background/';

        if(username != ''){
            url += encodeURI(username)+ '/';
        } else {
            url += encodeURI('all/');
        }

        if(search_sid != 0){
            url += encodeURI(search_sid)+ '/';
        } else {
            url += encodeURI('0/');
        }

        if(order_by != '' && order_by != null){
            url += encodeURI(order_by)+ '/';
        } else {
            url += encodeURI('all/');
        }

        if(status != '' && status != null){
            url += encodeURIComponent(status)+ '/';
        } else {
            url += encodeURI('all/');
        }

        if(product_type != ''){
            url += encodeURI(product_type)+ '/';
        } else {
            url += encodeURI('all/');
        }

        if(startDate != '' && endDate == ''){
            url += encodeURIComponent(startDate) + '/all/';
        }

        if(endDate != '' && startDate == ''){
            url += 'all/' + encodeURIComponent(endDate) + '/';
        }

        if((startDate != '') && (endDate != '')){
            url += encodeURIComponent(startDate) + '/' + encodeURIComponent(endDate) + '/';
        }
        
        $('#form-filters').attr('action', url);
        $('#form-filters').submit();
    }

    $('#username').autocomplete({
        source: get_applicant,
        minLength: 2,
        select: function(e, ui){
            $('#search_sid').val(ui.item.id);
        }
    });

    $(document).on('keydown','#username',function(e){
        if(e.keyCode == 8){
            $('#search_sid').val(0);
        }
    });

    function get_applicant(req, res) {
        if(applicantXHR !== null) applicantXHR.abort();

        applicantXHR = $.get("<?= base_url();?>background-check/get-applicant/"+ req.term +"", function(resp){
            if(!resp) return false;
            res(resp);
            applicantXHR = null;
        });
    }

    $(document).ready(function () {
        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
           // generate_search_url();
            fApplyDateFilters();
           // window.location = $(this).attr('href').toString();
        });
        
        $("#startdate").datepicker({
            dateFormat: 'mm-dd-yy',
            yearRange: "1960:+0",
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

        $("#enddate").datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date(),
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
    });
</script>

<script>
    // jQuery IFFY
    // Created on: 30-05-2019
    $(function(){
        var base_url = "<?=base_url('accurate_background');?>/",
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
        target_append = $('table tbody');

        $('.js-product-type').select2();
        $('#order_by').select2();

        $('.datepicker').datepicker({ dateFormat: 'mm-dd-yy' });

        $('button.js-search').click(function(e) {
            e.preventDefault();
            // Reset
            total_records = 0;
            current_page = 1;
            total_pages = 1;
            target_append.html('');

            //
            if(from_pop === false)
                history.pushState({url: func_apply_filters()}, "", func_apply_filters(true));
            
            fetch_records();
            from_pop = false;
        });

        //
        $('button.js-search').trigger('click');
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

        //
        function fetch_records(do_print){
            if(current_page > total_pages) return;
            if(xhr != null) return;
            loader();
            xhr = $.post(func_apply_filters(), {
                action: 'get_records',
                total_records: total_records,
                page: current_page,
                status_records: status_records
            }, function(resp) {
                if(resp.Status === false) return;
                if(resp.Data == '') { reset_table(); }
                else if(resp.Data.length == 0) { reset_table(); }
                else {
                    $('.js-msg-row').hide(0);
                    $('.js-info-box').show(0);
                }

                if(current_page == 1){
                    total_records = resp.TotalRecords;
                    limit = resp.Limit;
                    total_pages = resp.TotalPages;
                    if(resp.Overwrite === true)
                        status_records = resp.StatusRecords;
                    target_from.text( resp.from_records);
                }

                target_to.text( resp.to_records);
                target_total.text( isNaN(parseInt(resp.TotalRecords)) ? 0 : resp.TotalRecords);
                load_view(resp);
                loader(false);
                xhr = null;
                if(do_print !== undefined) fetch_records(do_print);
                if(current_page == total_pages) { 
                    if( do_print !== undefined) do_print();
                } 
                current_page++;
            });
        }

        //
        function load_view(resp){
            if(current_page == 1)
                target_append.html(resp.Data);
            else
                target_append.append(resp.Data);
        }

        // Set URL
        function func_apply_filters(add_url){
            var 
            username = $('#username').val(),
            search_id = $('#search_sid').val(),
            order_by = $('#order_by').val(),
            status = $('#status').val(),
            product_type = $('#product_type').val(),
            startDate = $('#startdate').val(),
            endDate = $('#enddate').val();


            username = username != '' && username != null && username != undefined && username != 0 ? encodeURIComponent(username) : 'all';
            order_by = order_by != '' && order_by != null && order_by != undefined && order_by != 0 ? encodeURIComponent(order_by) : 'all';
            status = status != '' && status != null && status != undefined && status != 0 ? encodeURIComponent(status) : 'all';
            product_type = product_type != '' && product_type != null && product_type != undefined && product_type != 0 ? encodeURIComponent(product_type) : 'all';
            startDate = startDate != '' && startDate != null && startDate != undefined && startDate != 0 ? encodeURIComponent(startDate) : 'all';
            endDate = endDate != '' && endDate != null && endDate != undefined && endDate != 0 ? encodeURIComponent(endDate) : 'all';
            return (base_url === undefined ? '' : base_url)+username + '/'+search_id + '/' + order_by + '/' + status + '/' + product_type + '/' + startDate + '/' + endDate + '/';
        }

        
        window.onpopstate = function(e) {
            if(e.state == null) return;
            var tmp = e.state.url.split('/');
            from_pop = true;
            if(tmp.length > 0){
                $('.js-company option').prop('selected', false);
                $('.js-company option[value="'+tmp[0]+'"]').prop('selected', true);
                $('.js-company').select2('val', tmp[0]);
                $('.js-product-type').select2('val', ""+tmp[1]+"");
                $('.js-status').select2('val', ""+tmp[2]+"");
                $('.js-start-date').val(""+tmp[3]+"");
                $('.js-end-date').val(""+tmp[4]+"");
                $('button.js-search').trigger('click');
            }
        }


        // Load on scroll
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                fetch_records();
            }
        });


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
        function reset_table(){
            target_append.html('');
            target_to.text(0);
            target_from.text(0);
            target_total.text(0);
            $('.js-info-box').hide(0);
            $('.js-msg-row').html('No records found.').show(0);
        }

        //
        function export_result(type){
            type = type === undefined ? 'csv' : type;
            $.post(func_apply_filters(), {
                action: 'export',
                type: type,
                pages: 'all',
                total_records: total_records,
                page: current_page,
                status_records: status_records
            }, function(resp) {
                loader(false);
                window.location.href = "<?=base_url('accurate_background/download');?>/"+type+'/'+resp.Data;
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
            $('#print_from').html($('#js-data-table').html());
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
            else fetch_records(do_print);
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

    .select2-container{ background-color: #eeeeee; padding: 0 5px; }
</style>