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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="row">
                                        <div class="applicant-reg-date">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="form-wrp">
                                                        <form id="form-filters" method="post" enctype="multipart/form-data" action="">
                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="username">Username</label>
                                                                    <input type="text" id="username" class="invoice-fields" name="username" value="<?php echo $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : ''; ?>">
                                                                    <input type="hidden" id="search_sid" name="search_sid" value="<?php echo $this->uri->segment(4) != 0 ? urldecode($this->uri->segment(4)) : 0; ?>">
                                                                    <div class="hint-text text-muted"><b>Hint: </b> Type in the name and system will give suggestion. </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <label>Status</label>
                                                                    <?php $statuses = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : 'all';
                                                                    $statuses = explode(',',$statuses); ?>
                                                                    <div class="hr-select-dropdown ">
                                                                        <select class="chosen-select js-status" multiple="multiple" name="status[]" id="status" >
                                                                            <option value="all" <?= in_array('all',$statuses) ? 'selected' : '';?>>All</option>
                                                                            <option value="draft" <?= in_array('draft',$statuses) ? 'selected' : '';?>>Awaiting Candidate Input</option>
                                                                            <option value="pending" <?= in_array('pending',$statuses) ? 'selected' : '';?>>Pending</option>
                                                                            <option value="cancelled" <?= in_array('cancelled',$statuses) ? 'selected' : '';?>>Cancelled </option>
                                                                            <option value="completed" <?= in_array('completed',$statuses) ? 'selected' : '';?>>Completed</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                                                <div class="form-group">
                                                                    <label for="enddate">Company Name</label>
                                                                    <?php $order_by = $this->uri->segment(10) != 'all' ? urldecode($this->uri->segment(10)) : ''; ?>
                                                                    <select class="js-companies" id="company_name" name="company_name" style="width: 100%;">
                                                                        <option value="all">All</option>
                                                                        <?php foreach ($companies as $account) { ?>
                                                                            <option value="<?php echo $account["sid"] ?>" <?= $order_by == $account["sid"] ? 'selected' : '';?>>
                                                                                <?php echo ucwords($account["CompanyName"]);?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                                                <div class="form-group">
                                                                    <label>Order Placed By</label>
                                                                    <?php $order_by = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                                    <select class="invoice-fields" id="order_by" name="order_by">
                                                                        <option value="all">All</option>
                                                                    </select>
                                                                    <div class="hint-text text-muted"><b>Hint: </b> Employee name who executed the report. (Select Company)</div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <?php $startdate = $this->uri->segment(8) != 'all' && $this->uri->segment(8) != '' ? urldecode($this->uri->segment(8)) : date('m-01-Y');?>
                                                                    <label for="startdate">Start Date</label>
                                                                    <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="Start Date" readonly="" value="<?php echo set_value('startdate', $startdate);?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <?php $end_date = $this->uri->segment(9) != 'all' && $this->uri->segment(9) != '' ? urldecode($this->uri->segment(9)) : date('m-t-Y');?>
                                                                    <label for="enddate">End Date</label>
                                                                    <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="End Date" readonly="" value="<?php echo set_value('enddate', $end_date);?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                                                <div class="form-group">
                                                                    <label>Product Type</label>
                                                                    <?php $type = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : 'all'; ?>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields js-product-type" name="product_type" id="product_type">
                                                                            <option value="all" <?= $type == 'all' ? 'selected' : '';?>>All</option>
                                                                            <option value="background-checks" <?= $type == 'background-checks' ? 'selected' : '';?>>Background Checks</option>
                                                                            <option value="drug-testing" <?= $type == 'drug-testing' ? 'selected' : '';?>>Drug Testing</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                                                <div class="form-group pull-right">
                                                                    <label class="transparent-label">test</label>
                                                                    <div class="row">
                                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                            <button class="btn btn-success btn-block" id="btn_apply_filters">Filter</button>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                            <a class="btn btn-success btn-block" href="javascript:redirect()">Clear</a>
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
                                    </div>
                                    <br />
                                    <br />
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="margin-bottom: 20px;">
                                            <div class="hr-box-header js-info-box">
                                                <?=$info_box;?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="table-responsive table-outer" id="js-data-table">
                                                <div class="hr-displayResultsTable">
                                                    <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Ordered By</th>
                                                                <th>Candidate</th>
                                                                <th>Type</th>
                                                                <th>Product Name</th>
                                                                <th>Product Type</th>
                                                                <th>Company Name</th>
                                                                <th>Status</th>
                                                                <th class="no-print">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="10" class="text-center">
                                                                        <span class="no-data js-msg-row">Please wait, while we are fetching records...</span>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <input type="hidden" name="execute" value="multiple_action">
                                                        <input type="hidden" id="type" name="type" value="company">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="hr-box-header hr-box-footer js-info-box">
                                                <?=$info_box;?>
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
<script type="text/javascript">
    var applicantXHR = null;

    function redirect(){
        window.location.href = "<?=base_url('manage_admin/accurate_background/');?>/";
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

    $('body').on('change', '#company_name', function(){
        var selected = $(this).val();
        if(selected != 'all'){

            var my_data = {'company_sid': selected, 'perform_action': 'fetch_employee'};
            var myRequest = $.ajax({
                type: "POST",
                url: "<?php echo base_url('manage_admin/accurate_background/ajax_responder'); ?>",
                data: my_data
            });

            myRequest.done(function (response) {
                var options = '<option value="all">All</option>';
                $.each(response,function(index,object){
                    var temp = '<option value="'+object.id+'">'+object.value+'</option>';
                    options = options + temp;
                });
                $('#order_by').html(options);
            });
        }else{
            var options = '<option value="all">All</option>';
            $('#order_by').html(options);
        }
    });

    function get_applicant(req, res) {
        if(applicantXHR !== null) applicantXHR.abort();

        applicantXHR = $.get("<?= base_url();?>manage_admin/background-check/get-applicant/"+ req.term +"", function(resp){
            if(!resp) return false;
            res(resp);
            applicantXHR = null;
        });
    }

    $(document).ready(function () {
        // $('#company_name').trigger('change');
        // var order_by = '<?= urldecode($this->uri->segment(5)); ?>';
        // setTimeout(function(){
        //     $('#order_by').val(order_by);
        // },2000);
        // console.log(order_by);

        // $('#btn_apply_filters').on('click', function(e){
        //     e.preventDefault();
        //     generate_search_url();
        //     window.location = $(this).attr('href').toString();
        // });

        $("#startdate").datepicker({
            dateFormat: 'mm-dd-yy',
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

    // Created on: 29-05-2019
    // jQuery IFFY
    $(function(){
        var base_url = "<?=base_url('manage_admin/accurate_background/');?>/",
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
            if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                load_results();
            }
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
                window.location.href = "<?=base_url('manage_admin/accurate_background/download');?>/"+type+'/'+resp.Data;
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
            var startDate = $('#startdate').val();
            var endDate = $('#enddate').val();
            var status = $('#status').val();
            var product_type = $('#product_type').val();
            var username = $('#username').val();
            var search_sid = $('#search_sid').val();
            var order_by = $('#order_by').val();
            var company_name = $('#company_name').val();

            username = username != '' && username != null && username != undefined && username != 0 ? encodeURIComponent(username) : 'all';
            search_sid = search_sid != '' && search_sid != null && search_sid != undefined && search_sid != 0 ? encodeURIComponent(search_sid) : 'all';
            order_by = order_by != '' && order_by != null && order_by != undefined && order_by != 0 ? encodeURIComponent(order_by) : 'all';
            status = status != '' && status != null && status != undefined && status != 0 ? encodeURIComponent(status) : 'all';
            product_type = product_type != '' && product_type != null && product_type != undefined && product_type != 0 ? encodeURIComponent(product_type) : 'all';
            startDate = startDate != '' && startDate != null && startDate != undefined && startDate != 0 ? encodeURIComponent(startDate) : 'all';
            endDate = endDate != '' && endDate != null && endDate != undefined && endDate != 0 ? encodeURIComponent(endDate) : 'all';
            company_name = company_name != '' && company_name != null && company_name != undefined && company_name != 0 ? encodeURIComponent(company_name) : 'all';
            return (show_url === undefined ? '' : base_url)+username + '/' + search_sid + '/' + order_by + '/' + status + '/' + product_type + '/' + startDate + '/' + endDate + '/' + company_name + '/';
        }


         //
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