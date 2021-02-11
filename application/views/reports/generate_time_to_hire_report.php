<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="field-row">
                                        <label>Keyword</label>
                                        <?php $keyword = $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : '';?>
                                        <input type="text" class="invoice-fields" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-8"></div>
                                <div class="col-xs-2">
                                    <a href="#" id="btn_apply_filters" class="btn btn-success btn-block">Apply Filters</a>
                                </div>
                                <div class="col-xs-2">
                                    <a href="<?php echo base_url('reports/generate_time_to_hire'); ?>" id="btn_clear_filters" class="btn btn-success btn-block">Clear Filters</a>
                                </div>
                            </div>

                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <?php if (isset($jobs) && sizeof($jobs) > 0) { ?>
                                                <div class="box-view reports-filtering">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <form method="post" id="export" name="export">
                                                                    <input type="submit" name="submit" class="submit-btn pull-right" value="Export" />
                                                                </form>
                                                                <a href="javascript:;" class="submit-btn pull-right" onclick="print_page('#print_div');">
                                                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <hr>
                                                </div>
                                                <div class="page-header-area">
                                                    <span class="page-heading pull-right">
                                                        <b><?= 'Total number of applicants:    ' . sizeof($jobs)?></b>
                                                    </span>
                                                </div>
                                            <?php } ?>
                                            <div class="table-responsive table-outer" id="print_div">
                                                <div class="border-none mylistings-wrp">
                                                    <table class="table table-stripped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-6">Job Title</th>
                                                                <th class="col-xs-2">Job Date</th>
                                                                <th class="col-xs-1">Applicants</th>
                                                                <th class="col-xs-3">Average Days To Hire</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (isset($jobs) && sizeof($jobs) > 0) { ?>
                                                                <?php foreach ($jobs as $job) { ?>
                                                                    <?php
                                                                        $state = $city = '';
                                                                        if(isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - '.ucfirst($job['Location_City']);
                                                                        if(isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', '.db_get_state_name($job['Location_State'])['state_name'];
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $job['Title'].$city.$state; ?></td>
                                                                        <td><?=reset_datetime(array('datetime' => $job['activation_date'], '_this' => $this)); ?></td>
                                                                        <td><?php echo $job['applicant_count']; ?></td>
                                                                        <td><?php echo $job['average_days_to_hire']; ?> Day(s)</td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr><td colspan="4">No jobs found.</td></tr>
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
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script>

    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });
    function generate_search_url(){
        var keyword = $('#keyword').val();
        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';

        var url = '<?php echo base_url('reports/generate_time_to_hire'); ?>' + '/' + keyword;
        $('#btn_apply_filters').attr('href', url);
    }
    $(document).ready(function () {
        $('#keyword').on('keyup', function(){
            generate_search_url();
        });
        $('#keyword').trigger('keyup');
        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
    });

    function print_page(elem) {
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
