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
                        <span class="page-heading down-arrow">
                            <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn">
                                <i class="fa fa-chevron-left"></i>
                                Back to Settings
                            </a>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <?php if (isset($products) && sizeof($products) > 0) { ?>
                    <div class="box-view reports-filtering">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <form method="post" id="export" name="export">
                                        <input type="submit" name="submit" class="submit-btn pull-right" value="Export" />
                                    </form>
                                    <a href="javascript:;" class="submit-btn pull-right" onclick="print_page('#print_div');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="table-responsive table-outer">
<!--                        <strong id="invoiceCount">Total Products: <?php echo $products_count; ?></strong>-->
                        <!-- ************************************************************************************* -->
                        <!-- Search Table Start -->
                        <div class="panel panel-default">
                            <div class="panel-heading" href="#search_filters" data-toggle="collapse">
                                <h3 class="panel-title">Click to modify search criteria</h3>
                            </div>
                            <div id="search_filters" class="panel-body collapse <?php
                            if ($flag == true) {
                                echo 'in';
                            }
                            ?>">
                                <div class="universal-form-style-v2">
                                    <form name="search" id="search" action="<?php echo base_url('job_products_report'); ?>">
                                        <ul class="row">
                                            <li class="col-lg-6">
                                                <label>Start Date</label>
                                                <input type="text" name="start" value="<?php echo isset($search['start']) ? $search['start'] : ''; ?>" class="invoice-fields eventdate">
                                            </li>
                                            <li class="col-lg-6">
                                                <label>End Date</label>
                                                <input type="text" name="end" value="<?php echo isset($search['end']) ? $search['end'] : ''; ?>" class="invoice-fields eventdate">
                                            </li>
                                            <li class="col-lg-6">
                                                <label>Products</label>
                                                <div class="hr-select-dropdown">
                                                    <?php if (sizeof($active_products) > 0) { ?>
                                                        <select class="invoice-fields" name="product_sid">
                                                            <option value="">Any Product</option>
                                                            <?php foreach ($active_products as $active_product) { ?>
                                                                <option <?php if (isset($search['product_sid']) && $search['product_sid'] == $active_product['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $active_product['sid']; ?>">
                                                                    <?php echo $active_product['name']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <p>No product found.</p>
                                                    <?php } ?>
                                                </div>
                                            </li>
                                            <li class="col-lg-6">
                                                <label>Jobs</label>
                                                <div class="hr-select-dropdown">
                                                    <?php if (sizeof($active_jobs) > 0) { ?>
                                                        <select class="invoice-fields" name="job_sid">
                                                            <option value="">Any Job</option>
                                                            <?php foreach ($active_jobs as $active_job) { ?>
                                                                <option <?php if (isset($search['job_sid']) && $search['job_sid'] == $active_job['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $active_job['sid']; ?>">
                                                                    <?php echo $active_job['Title']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <p>No job found.</p>
                                                    <?php } ?>
                                                </div>
                                            </li>
                                            <li class="col-lg-12 text-center autoheight">
                                                <input type="submit" name="submit" class="btn btn-success" value="Search">
                                                <a href="<?php echo base_url('job_products_report'); ?>" class="btn btn-success">Clear</a>
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- ************************************************************************************* -->

                        <div class="col-xs-12 col-sm-12 margin-top">
                            <div class="row"><?php echo $links; ?></div>
                        </div>
                        <div id="print_div">
                            <table class="table table-stripped table-hover table-bordered" id="example">
                                <thead>
                                    <tr>
                                        <th>Job ID</th>
                                        <th>Job Title</th>
                                        <th>Product Name</th>
                                        <th>Advertised Date</th>
    <!--                                    <th class="text-center">Expiry Date</th>-->
                                    </tr> 
                                </thead>
                                <tbody>
                                    <?php if (isset($products) && sizeof($products) > 0) { ?>
                                        <?php foreach ($products as $product) { ?>
                                            <tr>
                                                <td><?php echo $product['job_sid']; ?></td>
                                                <td><?php echo $product['job_title']; ?></td>
                                                <td><?php echo $product['product_name']; ?></td>
                                                <td><?=reset_datetime(array('datetime' => $product['purchased_date'], '_this' => $this)); ?></td>
            <!--                                            <td class="text-center"><?php //echo reset_datetime(array('datetime' => $product['expiry_date'], '_this' => $this)); ?></td>-->
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="5">No advertised products found.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 margin-top">
                        <div class="row"><?php echo $links; ?></div>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.eventdate').datepicker({dateFormat: 'mm-dd-yy', yearRange: "1960:+5"}).val();
    });
    
    
    function print_page(elem)
    {
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');
        
        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
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