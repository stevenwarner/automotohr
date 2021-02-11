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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="hr-search-criteria <?php
                                    if ($flag == true) {
                                        echo 'opened';
                                    }
                                    ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" <?php
                                    if ($flag == true) {
                                        echo "style='display:block'";
                                    }
                                    ?>>
                                        <form method="GET" action="<?php echo base_url('manage_admin/reports/job_products_report'); ?>" name="search" id="search">
                                            <div class="row">
                                                <div class="field-row field-row-autoheight">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12"><b>Please Select : </b></div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label class="control control--radio">Company
                                                                    <input type="radio" name="company_or_brand" value="company" id="company" <?php
                                                                    if ($company_or_brand == 'company') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="control control--radio">Oem,Independent,Vendor
                                                                    <input type="radio" name="company_or_brand" value="brand" id="brand" <?php
                                                                    if ($company_or_brand == 'brands') {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="control control--radio">All
                                                                    <input type="radio" name="company_or_brand" value="all" id="all" <?php
                                                                    if ($company_or_brand == 'all' ) {
                                                                        echo 'checked';
                                                                    }
                                                                    ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Products:</label>
                                                    <div class="hr-select-dropdown">
                                                        <?php if (sizeof($active_products) > 0) { ?>
                                                            <select class="invoice-fields" name="product_sid" id="product_sid">
                                                                <option value="">Any Product</option>
                                                                <?php foreach ($active_products as $active_product) { ?>
                                                                    <option <?php if ($this->uri->segment(4) != 'all' && urldecode($this->uri->segment(4)) == $active_product['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $active_product['sid']; ?>">
                                                                        <?php echo $active_product['name']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <p>No product found.</p>
                                                        <?php } ?>     
                                                    </div>                                               
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Jobs:</label>
                                                    <div class="hr-select-dropdown">
                                                        <?php if (sizeof($active_jobs) > 0) { ?>
                                                            <select class="chosen-select" multiple="multiple" name="job_sid[]" id="job_sid">
                                                                <option value="all" <?php if (in_array('all', $job_sid_array)) { ?> selected="selected" <?php } ?>>Any Job</option>
                                                                <?php foreach ($active_jobs as $active_job) { ?>
                                                                    <option value="<?= $active_job['sid'] ?>" <?php if (in_array($active_job['sid'], $job_sid_array)) { ?> selected="selected" <?php } ?>>
                                                                        <?php echo $active_job['Title']; ?>
                                                                    </option>
<!--                                                                    <option --><?php //if ($this->uri->segment(5) != 'all' && urldecode($this->uri->segment(5)) == $active_job['sid']) { ?><!-- selected="selected" --><?php //} ?><!-- value="--><?php //echo $active_job['sid']; ?><!--">-->
<!--                                                                        --><?php //echo $active_job['Title']; ?>
<!--                                                                    </option>-->
                                                                <?php } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <p>No job found.</p>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 field-row">
                                                    <div id="company_div">
                                                        <label>Companies :</label>
                                                        <div class="hr-select-dropdown">
                                                            <?php if (sizeof($active_companies) > 0) { ?>
                                                                <select class="invoice-fields" name="company_sid" id="company_sid">
                                                                    <option value="">Any Company</option>
                                                                    <?php foreach ($active_companies as $active_company) { ?>
                                                                        <option <?php if ($this->uri->segment(6) != 'all' && urldecode($this->uri->segment(6)) == $active_company['sid']) { ?>
                                                                            selected="selected" <?php } ?> value="<?php echo $active_company['sid']; ?>">
                                                                            <?php echo $active_company['CompanyName']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <p>No company found.</p>
                                                            <?php } ?>
                                                        </div>                                                        
                                                    </div>
                                                    <div id="brand_div">
                                                        <label>Oem,Independent,Vendor : </label>
                                                        <div class="hr-select-dropdown">
                                                            <?php if (sizeof($brands) > 0) { ?>
                                                                <select class="invoice-fields" name="brand_sid" id="brand_sid">
                                                                    <option value="">Please Select</option>
                                                                    <?php foreach ($brands as $brand) { ?>
                                                                        <option <?php if ($this->uri->segment(7)!= 'all' && urldecode($this->uri->segment(7)) == $brand['sid']) { ?>
                                                                                selected="selected" <?php } ?> value="<?php echo $brand['sid']; ?>">
                                                                                <?php echo $brand['oem_brand_name']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <p>No Oem,Independent,Vendor found.</p>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-5 field-row">
                                                    <label>Date From:</label>
                                                    <?php $start_date = $this->uri->segment(8) != 'all' && $this->uri->segment(8) != '' ? urldecode($this->uri->segment(8)) : date('m-d-Y');?>
                                                    <input class="invoice-fields"
                                                           placeholder="<?php echo date('m-d-Y'); ?>"
                                                           type="text"
                                                           name="start_date_applied"
                                                           id="start_date_applied"
                                                           value="<?php echo set_value('start_date_applied', $start_date); ?>"/>
<!--                                                    <input type="text" name="start" value="--><?php //echo isset($search['start']) ? $search['start'] : ''; ?><!--" class="invoice-fields" id="startdate" readonly>-->
                                                </div>
<!--                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-2 text-center field-row">-->
<!--                                                    <label class="transparent-label">empty</label>-->
<!--                                                    <label class="valign-middle">To</label>-->
<!--                                                </div>-->
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-5 field-row">
                                                    <label>Date To:</label>
                                                    <?php $end_date = $this->uri->segment(9) != 'all' && $this->uri->segment(9) != '' ? urldecode($this->uri->segment(9)) : date('m-d-Y');?>
                                                    <input class="invoice-fields"
                                                           placeholder="<?php echo date('m-d-Y'); ?>"
                                                           type="text"
                                                           name="end_date_applied"
                                                           id="end_date_applied"
                                                           value="<?php echo set_value('end_date_applied', $end_date); ?>"/>
<!--                                                    <input type="text" name="end" value="--><?php //echo isset($search['end']) ? $search['end'] : ''; ?><!--" class="invoice-fields" id="enddate" readonly>-->
                                                </div>
                                                <div class="col-lg-12 text-right field-row field-row-autoheight">
                                                    <a id="btn_apply_filters" class="btn btn-success" href="#" >Apply Filters</a>
<!--                                                    <input type="submit" class="btn btn-success" value="Search" name="submit">-->
                                                    <a href="<?php echo base_url('manage_admin/reports/job_products_report'); ?>" class="btn btn-success">Reset Filters</a>
                                                </div>
                                            </div>
                                        </form>
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
                                                    <button class="btn btn-success" type="submit">
                                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> 
                                                        Export To Excel
                                                    </button>
                                                </form>
                                            </div>                                                               
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-xs-12 col-sm-12 margin-top">
                                        <div class="row"><?php echo $links; ?></div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Products Report</h1>
                                            </span>
                                            <span class="pull-right">
                                                <h1 class="hr-registered">Total Records Found : <?php echo sizeof($products);?></h1>
                                            </span>
                                        </div>
                                        <div class="table-responsive hr-innerpadding" id="print_div">
                                            <table class="table table-stripped table-hover table-bordered" id="example">
                                                <thead>
                                                    <tr>
                                                        <th>Job ID</th>
                                                        <th>Job Title</th>
                                                        <th>Product Name</th>
                                                        <th>Company Name</th>
                                                        <th>Advertised Date</th>
                    <!--                                    <th class="text-center">Expiry Date</th>-->
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($products) && sizeof($products) > 0) { ?>
                                                        <?php foreach ($products as $product) {?>
                                                            <tr>
                                                                <td><?php echo $product['job_sid']; ?></td>
                                                                <?php
                                                                    $city = '';
                                                                    $state='';
                                                                    if (isset($product['Location_City']) && $product['Location_City'] != NULL) {
                                                                        $city = ' - '.ucfirst($product['Location_City']);
                                                                    }
                                                                    if (isset($product['Location_State']) && $product['Location_State'] != NULL) {
                                                                        $state = ', '.db_get_state_name($product['Location_State'])['state_name'];
                                                                    }
                                                                ?>
                                                                <td><?php echo $product['job_title'].$city.$state; ?></td>
                                                                <td><?php echo $product['product_name']; ?></td>
                                                                <td><?php echo $product['company_name']; ?></td>
                                                                <td><?php echo date_with_time($product['purchased_date']); ?></td>
                            <!--                                            <td class="text-center"><?php echo date_with_time($product['expiry_date']); ?></td>-->
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="text-center" colspan="5"><div class="no-data">No advertised products found.</div></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 margin-top">
                                        <div class="row"><?php echo $links; ?></div>
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
                                                    <button class="btn btn-success" type="submit">
                                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> 
                                                        Export To Excel
                                                    </button>
                                                </form>
                                            </div>                                                               
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
<script type="text/javascript">

    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });
    function generate_search_url(){

        var company_sid = $("#company_sid").val();
        var brand_sid = $("#brand_sid").val();
        var job_sid = $('#job_sid').val();
        var product_sid = $('#product_sid').val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var all = 0;
        if($('input[name="company_or_brand"]:checked').val() == 'all'){
            all = 1;
        }

        company_sid = company_sid != '' && company_sid != null && company_sid != undefined && company_sid != 0 ? encodeURIComponent(company_sid) : 'all';
        brand_sid = brand_sid != '' && brand_sid != null && brand_sid != undefined && brand_sid != 0 ? encodeURIComponent(brand_sid) : 'all';
        job_sid = job_sid != '' && job_sid != null && job_sid != undefined && job_sid != 0 ? encodeURIComponent(job_sid) : 'all';
        product_sid = product_sid != '' && product_sid != null && product_sid != undefined && product_sid != 0 ? encodeURIComponent(product_sid) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';


        var url = '<?php echo base_url('manage_admin/reports/job_products_report'); ?>' + '/' + product_sid + '/' + job_sid + '/' + company_sid + '/' + brand_sid + '/' + start_date_applied + '/' + end_date_applied + '/' + all;

        $('#btn_apply_filters').attr('href', url);
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

        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
//        $('.eventdate').datepicker({dateFormat: 'mm-dd-yy'}).val();
        var div_to_show = $('input[name="company_or_brand"]:checked').val();
        if(div_to_show == 'brand'){
            $('#company_sid').val('');
            $('#company_sid').removeProp('disabled','disabled');
            $('#brand_sid').removeProp('disabled','disabled');
        }
        else if(div_to_show == 'company'){
            $('#brand_sid').val('');
            $('#company_sid').removeProp('disabled','disabled');
            $('#brand_sid').removeProp('disabled','disabled');
        }
        else{
            $('#company_sid').attr('disabled','disabled');
            $('#brand_sid').attr('disabled','disabled');
            $('#company_sid').val('');
            $('#brand_sid').val('');
        }
        display(div_to_show);

        $("#company_sid").change(function () {
            generate_search_url();
        });

        $('#job_sid').change(function(){
            generate_search_url();
        });
        $('#product_sid').change(function () {
            generate_search_url();
        });
        $('#brand_sid').change(function() {
            generate_search_url();
        });

        $('input[name="company_or_brand"]').change(function (e) {
            var div_to_show = $(this).val();
            if(div_to_show == 'brand'){
                $('#company_sid').val('');
                $('#company_sid').removeProp('disabled','disabled');
                $('#brand_sid').removeProp('disabled','disabled');
            }
            else if(div_to_show == 'company'){
                $('#brand_sid').val('');
                $('#company_sid').removeProp('disabled','disabled');
                $('#brand_sid').removeProp('disabled','disabled');
            }
            else{
                $('#company_sid').attr('disabled','disabled');
                $('#brand_sid').attr('disabled','disabled');
                $('#company_sid').val('');
                $('#brand_sid').val('');
                generate_search_url();
            }
            display(div_to_show);
        });

        $('.datepicker').datepicker({dateFormat: 'mm-dd-yy'}).val();

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (value) {
                //console.log(value);
                $('#end_date_applied').datepicker('option', 'minDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (value) {
                //console.log(value);
                $('#start_date_applied').datepicker('option', 'maxDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());
    });

    $('#apply_filters_submit').click(function () {
        $("#search").validate({
            ignore: [],
            rules: {
                company_sid: {required: function (element) {
                        return $('input[name=company_or_brand]:checked').val() == 'company';
                    }
                },
                brand_sid: {required: function (element) {
                        return $('input[name=company_or_brand]:checked').val() == 'brand';
                    }
                },
                company_or_brand: {
                    required: true,
                }
            },
            messages: {
                company_sid: {
                    required: 'Company name is required'
                },
                brand_sid: {
                    required: 'Brand name is required'
                },
                company_or_brand: {
                    required: 'Please select one of the options'
                }
            }
        });
    });

    // ***** on radio button change ***** //
    function display(div_to_show) {
        if (div_to_show == 'company') {
            $('#company_div').show();
            $('#brand_div').hide();
        } else if (div_to_show == 'brand'){
            $('#company_div').hide();
            $('#brand_div').show();
        }
        else{
            $('#company_div').show();
            $('#brand_div').hide();
        }
    }
    // ***** on radio button change ***** //
    
    
    function print_page(elem)
    {
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');
        
        mywindow.document.write('<html><head><title>' + '<?php echo $page_title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome-animation.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/responsive.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/jquery-ui.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/images/favi-icon.png'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/select2.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close(); 
        mywindow.focus(); 
    }
</script>