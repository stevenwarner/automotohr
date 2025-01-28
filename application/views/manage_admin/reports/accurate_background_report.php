<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.11/lodash.core.min.js"></script>
<?php
$company_sid = $this->uri->segment(4);
$product_type = $this->uri->segment(5);
$status = $this->uri->segment(6);
$from_date = $this->uri->segment(7);
$to_date = $this->uri->segment(8);
?>

<?php
$info_box = '';
$info_box .= '<div class="col-xs-12 col-sm-6">';
$info_box .= '    <strong>Showing <span class="js-from-records">0</span> to <span class="js-to-records">0</span> Orders out of <span class="js-total">0</span></strong>';
$info_box .= '</div>';
$info_box .= '<div class="col-xs-12 col-sm-6">';
$info_box .= '    <div class="pull-right">';
$info_box .= '        <button class="btn btn-success js-export"><i class="fa fa-download"></i> &nbsp; Export CSV</button>';
$info_box .= '        <button class="btn btn-success js-print"><i class="fa fa-print"></i> &nbsp; Print</button>';
$info_box .= '    </div>';
$info_box .= '</div>';
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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $title; ?></h1>
                                    </div>
                                    <br />
                                    <br />
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-search-criteria opened">
                                                <strong>Click to modify search criteria</strong>
                                            </div>
                                            <div class="hr-search-main search-collapse-area" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-6 col-sm-6 col-lg-3">

                                                        <div class="field-row">
                                                            <label>Company</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields js-company" id="company_sid" name="company_sid">
                                                                    <option value="all">All Companies</option>
                                                                    <?php foreach ($companies as $company) { ?>
                                                                        <option <?= $company_sid == $company['sid'] ? 'selected="selected"' : ''; ?> value="<?php echo $company['sid'] ?>"><?php echo ucwords($company['CompanyName']); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-6 col-sm-6 col-lg-2">
                                                        <div class="field-row">
                                                            <label>Product type:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields js-product-type">
                                                                    <option <?= $product_type == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                                                    <option <?= $product_type == 'background_check' ? 'selected="selected"' : ''; ?> value="background_check">Background Check</option>
                                                                    <option <?= $product_type == 'drug_check' ? 'selected="selected"' : ''; ?> value="drug_check">Drugs Check</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-md-4 col-sm-4 col-lg-2">
                                                        <div class="field-row">
                                                            <label>Status:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields js-status">
                                                                    <option <?= $status == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                                                    <option <?= $status == 'awaiting_candidate_input' ? 'selected="selected"' : ''; ?> value="awaiting_candidate_input">Awaiting Candidate Input</option>
                                                                    <option <?= $status == 'cancelled' ? 'selected="selected"' : ''; ?> value="cancelled">Cancelled</option>
                                                                    <option <?= $status == 'completed' ? 'selected="selected"' : ''; ?> value="completed">Completed</option>
                                                                    <option <?= $status == 'pending' ? 'selected="selected"' : ''; ?> value="pending">Pending</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-4 col-sm-4 col-lg-2">
                                                        <?php $from_date = empty($from_date) || is_null($from_date) ?  date('m-01-Y') : $from_date; ?>
                                                        <div class="field-row">
                                                            <label>Date From:</label>
                                                            <input readonly="true" class="invoice-fields datepicker js-start-date" id="date_start" name="date_start" value="<?= $from_date; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-4 col-sm-4 col-lg-2">
                                                        <?php $to_date = empty($to_date) || is_null($to_date) ?  date('m-t-Y') : $to_date; ?>
                                                        <div class="field-row">
                                                            <label>Date To:</label>
                                                            <input readonly="true" class="invoice-fields datepicker js-end-date" id="date_end" name="date_end" value="<?= $to_date; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-4 col-sm-4 col-lg-1">
                                                        <div class="field-row">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-success btn-block btn-equalizer js-search">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <div class="row js-info-box">
                                                        <div class="col-xs-12">
                                                            <div class="hr-box-header hr-box-footer">
                                                                <?= $info_box; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12" style="margin-top: 20px;">
                                                            <div class="table-responsive" id="js-data-table">
                                                                <table class="table table-bordered table-hover table-striped table-condensed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Date Applied</th>
                                                                            <th>Ordered By</th>
                                                                            <th>Username</th>
                                                                            <th>User Type</th>
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
                                                                            <td colspan="9" class="text-center">
                                                                                <span class="no-data js-msg-row">Please wait, while we are fetching records...</span>
                                                                            </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row js-info-box">
                                                        <div class="col-xs-12">
                                                            <div class="hr-box-header hr-box-footer">
                                                                <?= $info_box; ?>
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
        </div>
    </div>
</div>
</div>
<script>
    // jQuery IFFY
    // Created on: 30-05-2019
    $(function() {
        var base_url = "<?= base_url('manage_admin/reports/accurate_background'); ?>/",
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
        $('.js-status').select2();
        $('.js-company').select2();

        $('.datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            dateFormat: 'mm-dd-yy'
        });

        $('button.js-search').click(function(e) {
            e.preventDefault();
            // Reset
            total_records = 0;
            current_page = 1;
            total_pages = 1;
            target_append.html('');
            //
            if (from_pop === false)
                history.pushState({
                    url: func_apply_filters()
                }, "", func_apply_filters(true));

            fetch_records();
            from_pop = false;
        });

        //
        $('button.js-search').trigger('click');

        //
        $('.js-export').click(function(e) {
            e.preventDefault();
            loader('show', export_text);
            export_result('csv');
        });

        // Print
        $('.js-print').click(function(e) {
            e.preventDefault();
            alertify.customPrint('Please, select one of the options. <br /><br /> <strong>Print All</strong><p>It will print all the records matching current filter</p><br /> <strong>Print Current</strong><p>It will only print the records showing on this page.</p>', )
                .set('labels', {
                    printcurrent: 'Print Current'
                })
                .set('closable', false)
                .set({
                    onprintall: function() {
                        start_print_process('all');
                    },
                    oncancel: function() {
                        return;
                    },
                    onprintcurrent: function() {
                        start_print_process('current');
                    },
                });
        });

        //
        function fetch_records(do_print) {
            if (current_page > total_pages) return;
            if (xhr != null) return;
            loader();
            xhr = $.post(base_url + func_apply_filters(), {
                action: 'get_records',
                total_records: total_records,
                page: current_page,
                status_records: status_records
            }, function(resp) {
                if (resp.Status === false) return;
                if (resp.Data == '' && current_page == 1) {
                    reset_table();
                } else if (resp.Data.length == 0 && current_page == 1) {
                    reset_table();
                } else {
                    $('.js-msg-row').hide(0);
                    $('.js-info-box').show(0);
                }

                if (current_page == 1) {
                    total_records = resp.TotalRecords;
                    limit = resp.Limit;
                    total_pages = resp.TotalPages;
                    if (resp.Overwrite === true)
                        status_records = resp.StatusRecords;
                    target_from.text(resp.from_records);
                }

                target_to.text(resp.to_records);
                target_total.text(resp.TotalRecords);
                load_view(resp);
                loader(false);
                xhr = null;
                if (do_print !== undefined) fetch_records(do_print);
                if (current_page == total_pages) {
                    if (do_print !== undefined) do_print();
                }
                current_page++;
            });
        }

        //
        function load_view(resp) {
            if (current_page == 1)
                target_append.html(resp.Data);
            else
                target_append.append(resp.Data);
        }

        // Set URL
        function func_apply_filters(add_url) {
            var company_sid = $('#company_sid').val(),
                product_type = $('.js-product-type').find(':selected').val(),
                status = $('.js-status').find(':selected').val(),
                date_start = $('#date_start').val(),
                date_end = $('#date_end').val();

            company_sid = company_sid == '' || company_sid == undefined || company_sid == null ? 'all' : encodeURIComponent(company_sid);
            product_type = product_type == '' || product_type == undefined || product_type == null ? 'all' : encodeURIComponent(product_type);
            status = status == '' || status == undefined || status == null ? 'all' : encodeURIComponent(status);
            date_start = date_start == '' || date_start == undefined || date_start == null ? 'all' : encodeURIComponent(date_start);
            date_end = date_end == '' || date_end == undefined || date_end == null ? 'all' : encodeURIComponent(date_end);

            return (add_url !== undefined ? base_url : '') + company_sid + '/' + product_type + '/' + status + '/' + date_start + '/' + date_end;
        }

        //
        window.onpopstate = function(e) {
            if (e.state == null) return;
            var tmp = e.state.url.split('/');
            from_pop = true;
            if (tmp.length > 0) {
                $('.js-company option').prop('selected', false);
                $('.js-company option[value="' + tmp[0] + '"]').prop('selected', true);
                $('.js-company').select2('val', tmp[0]);
                $('.js-product-type').select2('val', "" + tmp[1] + "");
                $('.js-status').select2('val', "" + tmp[2] + "");
                $('.js-start-date').val("" + tmp[3] + "");
                $('.js-end-date').val("" + tmp[4] + "");
                $('button.js-search').trigger('click');
            }
        }


        // Load on scroll
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                fetch_records();
            }
        });


        // Loader hide and show
        // @param is_show - Show/hide loader
        // @param content - Set text on loader
        function loader(is_show, content) {
            if (is_show === false) $('.js-loader').hide(0);
            else $('.js-loader').show(0);

            if (content === undefined) $('.cs-loader-text').text(fetch_text);
            else $('.cs-loader-text').text(content);
        }

        //
        function reset_table() {
            target_append.html('');
            target_to.text(0);
            target_from.text(0);
            target_total.text(0);
            $('.js-info-box').hide(0);
            $('.js-msg-row').html('No records found.').show(0);
        }

        //
        function export_result(type) {
            type = type === undefined ? 'csv' : type;
            $.post(base_url + func_apply_filters(), {
                action: 'export',
                type: type,
                pages: 'all',
                total_records: total_records,
                page: current_page,
                status_records: status_records
            }, function(resp) {
                loader(false);
                window.location.href = "<?= base_url('manage_admin/reports/accurate_background/download'); ?>/" + type + '/' + resp.Data;
            });
        }

        // 
        function start_print_process(type) {
            if (type == 'current') do_print();
            else fetch_all_records_for_print();
        }

        //
        function do_print() {
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
        function fetch_all_records_for_print() {
            var start_page = current_page - 1;
            if (start_page == total_pages) do_print();
            else if (start_page > total_pages) do_print();
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
                settings: {
                    onprintcurrent: null,
                    onprintall: null
                },
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
<div class="text-center cs-loader js-loader">
    <div class="cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="cs-loader-text">Please wait, while we are fetching more results</div>
    </div>
</div>

<style>
    .cs-loader {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 9999;
        background: rgba(0, 0, 0, .5);
    }

    .cs-loader-box {
        position: absolute;
        top: 50%;
        bottom: 0;
        left: 0;
        right: 0;
        width: 300px;
        margin: auto;
        margin-top: -190px;
    }

    .cs-loader-box i {
        font-size: 14em;
        color: #81b431;
    }

    .cs-loader-box div.cs-loader-text {
        display: block;
        padding: 10px;
        color: #000;
        background-color: #fff;
        border-radius: 5px;
        text-align: center;
        font-weight: 600;
        margin-top: 35px;
    }

    .cs-calendar {
        margin-top: 10px;
    }

    /**/
    .ajs-ok {
        display: none !important;
    }

    .ajs-button {
        background-color: #81b431 !important;
        color: #ffffff !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
        border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        -o-border-radius: 4px;
        border-color: #4cae4c !important;
    }

    .ajs-header {
        background-color: #81b431 !important;
        color: #ffffff !important;
    }
</style>