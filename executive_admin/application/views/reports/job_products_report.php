<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i><?php echo $title; ?></h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard/reports/' . $company_sid); ?>">
                        <i class="fa fa-long-arrow-left"></i> 
                        Back to Reports
                    </a>
                </div>
                <!-- search form drop down -->
                <div class="hr-search-criteria opened">
                    <strong>Click to modify search criteria</strong>
                </div>
                <div class="hr-search-main" style="display:block;">
                    <form method="GET" action="<?php echo base_url('reports/job_products_report/' . $company_sid); ?>" name="search" id="search">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                <label>Products:</label>
                                <?php $product_sid = $this->uri->segment(4); ?>
                                <select name="product_sid" id="product_sid">
                                    <?php if (!empty($active_products)) { ?>
                                        <option value="">Any Product</option>
                                        <?php foreach ($active_products as $active_product) { ?>
                                            <option <?php echo set_select('product_sid', $active_product['sid'], $product_sid == $active_product['sid']); ?> value="<?php echo $active_product['sid']; ?>">
                                                <?php echo $active_product['name']; ?>
                                            </option>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <option value="">Any Product</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                <label>Jobs:</label>
                                <?php $job_sid = $this->uri->segment(5); ?>
                                <select class="chosen-select" multiple="multiple" name="job_sid[]" id="job_sid">
                                    <?php if (!empty($active_jobs)) { ?>
                                        <option value="all" <?php if (in_array('all', $job_sid_array)) { ?> selected="selected" <?php } ?>>All Job</option>
                                        <?php foreach ($active_jobs as $active_job) { ?>
                                            <option value="<?= $active_job['sid'] ?>" <?php if (in_array($active_job['sid'], $job_sid_array)) { ?> selected="selected" <?php } ?>>
                                                <?php echo $active_job['Title']; ?>
                                            </option>
<!--                                            <option --><?php //echo set_select('product_sid', $active_job['sid'], $job_sid == $active_job['sid']); ?><!-- value="--><?php //echo $active_job['sid']; ?><!--">-->
<!--                                                --><?php //echo $active_job['Title']; ?>
<!--                                            </option>-->
                                        <?php } ?>
                                    <?php } else { ?>
                                        <option value="">Any Job</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                <label>Date From</label>
                                <?php $start_date = $this->uri->segment(6);?>
                                <?php $start_date = $start_date != 'all' && $start_date != '' ? $start_date : date('m-d-Y');?>
                                <input type="text" name="startdate" value="<?php echo set_value('startdate', $start_date); ?>" class="invoice-fields" id="startdate" readonly>
                            </div>

                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                <label>Date To</label>
                                <?php $end_date = $this->uri->segment(7);?>
                                <?php $end_date = $end_date != 'all' && $end_date != '' ? $end_date : date('m-d-Y');?>
                                <input type="text" name="enddate" value="<?php echo set_value('enddate', $end_date); ?>" class="invoice-fields" id="enddate" readonly>
                            </div>
                            <div class="col-lg-12 text-right field-row field-row-autoheight">
                                <a href="#" class="btn btn-success" id="btn_search">Search</a>
                                <a href="<?php echo base_url('reports/job_products_report/' . $company_sid . '/all/all/all/all'); ?>" class="btn btn-success">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- search form drop down -->
                <?php if (isset($products) && sizeof($products) > 0) { ?>
                <div class="col-xs-12 col-sm-12 margin-top">
                    <div class="row">
                        <div class="bt-panel">
                            <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                                <i class="fa fa-print" aria-hidden="true"></i> 
                                Print
                            </a>
                            <form method="post" id="export" name="export">
                                <input type="hidden" name="submit" value="Export" />
                                <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                            </form>
                        </div>                                                               
                    </div>
                </div>
                <?php } ?>

                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <span class="pull-left">
                            <h1 class="hr-registered">Job Products Report</h1>
                        </span>
                        <span class="pull-right">
                            <h1 class="hr-registered">Total Records Found : <?php echo $record_count;?></h1>
                        </span>

                    </div>

                    <div class="hr-innerpadding">
                        <div class="row">
                            <div class="col-xs-12">
                                <span class="pull-left">
                                    <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $record_count?></p>
                                </span>
                                <span class="pull-right" style="margin-top: 20px; margin-bottom: 20px;">
                                    <?php echo $page_links?>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div id="print_div" class="table-responsive">
                                    <table class="table table-stripped table-bordered" id="example">
                                        <thead>
                                            <tr>
                                                <th class="col-xs-1">Job ID</th>
                                                <th class="col-xs-5">Job Title</th>
                                                <th class="col-xs-4">Product Name</th>
                                                <th class="col-xs-2">Advertised Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (isset($products) && sizeof($products) > 0) { ?>
                                            <?php foreach ($products as $product) { ?>
                                                <tr>
                                                    <td><?php echo $product['job_sid']; ?></td>
                                                    <td><?php echo $product['job_title']; ?></td>
                                                    <td><?php echo $product['product_name']; ?></td>
                                                    <td>
<!--                                                        --><?php //echo date_with_time($product['purchased_date']); ?>
                                                        <?php echo reset_datetime(array(
                                                            'datetime' => $product['purchased_date'],
                                                            'from_format' => 'm/t/Y', // Y-m-d H:i:s
                                                            'format' => 'm/t/Y', //
                                                            'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                            'from_timezone' => $executive_user['timezone'], //
                                                            '_this' => $this
                                                        )) ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="text-center" colspan="5">
                                                    <div class="no-data">
                                                        No advertised products found.
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xs-12">
                                <span class="pull-left">
                                    <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $record_count?></p>
                                </span>
                                <span class="pull-right" style="margin-top: 20px; margin-bottom: 20px;">
                                    <?php echo $page_links?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isset($products) && sizeof($products) > 0) { ?>
                    <div class="col-xs-12 col-sm-12 margin-top">
                        <div class="row">
                            <div class="bt-panel">
                                <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                    Print
                                </a>
                                <form method="post" id="export" name="export">
                                    <input type="hidden" name="submit" value="Export" />
                                    <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>               					
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_search').click();
        }
    });
    function generate_search_url(){
        var product_sid = $('#product_sid').val();
        var job_sid = $('#job_sid').val();
        var start_date = $('#startdate').val();
        var end_date = $('#enddate').val();

        product_sid = product_sid != '' && product_sid != null && product_sid != undefined  && product_sid != 0? encodeURIComponent(product_sid) : 'all';
        job_sid = job_sid != '' && job_sid != null && job_sid != undefined && job_sid != 0 ? encodeURIComponent(job_sid) : 'all';
        start_date = start_date != '' && start_date != null && start_date != undefined && start_date ? encodeURIComponent(start_date) : 'all';
        end_date = end_date != '' && end_date != null && end_date != undefined && end_date ? encodeURIComponent(end_date) : 'all';

        var url = '<?php echo base_url('reports/job_products_report/' . $company_sid); ?>' + '/' + product_sid + '/' + job_sid + '/' + start_date + '/' + end_date;

        $('#btn_search').attr('href', url);
    }


    $(document).ready(function () {

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

        $('#btn_search').on('click', function(e){
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
        $('#product_sid').selectize({
            onChange: function (value) {
                generate_search_url();
            }
        });
        $('#job_sid').selectize({
            onChange: function (value) {
                generate_search_url();
            }
        });


        // Search Area Toggle Function    
        jQuery('.hr-search-criteria').click(function() {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });

        $('#startdate').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function(value){
                $('#enddate').datepicker('option', 'minDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#enddate').val());

        $('#enddate').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function(value){
                $('#startdate').datepicker('option', 'maxDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#startdate').val());
    });

    function print_page(elem)
    {
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
    }
</script>