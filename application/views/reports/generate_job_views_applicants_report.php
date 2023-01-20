<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_company'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                        <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="job-views-applicants">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="row">
                                                <div class="applicant-count" style="background-color:#162c3a;">
                                                    <p>Total Jobs</p>
                                                    <span><?php echo count($all_jobs); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="row">
                                                <div class="applicant-count" style="background-color:#980b1e;">
                                                    <p>Total Job Views</p>
                                                    <span><?php echo $total_views; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                            <div class="row">
                                                <div class="applicant-count" style="background-color:#4f8d09;">
                                                    <p>Total Job Applicants</p>
                                                    <span><?php echo $total_applicants; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php if (isset($all_jobs) && sizeof($all_jobs) > 0) { ?>
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
                                            <div class="table-responsive table-outer" id="print_div">
                                                <div class="table-wrp mylistings-wrp border-none">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-2">Date</th>
                                                                <th class="col-xs-5">Job Title</th>
<!--                                                                <th class="col-xs-2">Deactivation Date</th>-->
                                                                <th class="col-xs-1">Status</th>
                                                                <th class="col-xs-1 text-center">Views</th>
                                                                <th class="col-xs-1 text-center">Applicants</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($all_jobs)) { ?>
                                                                <?php foreach ($all_jobs as $job) { ?>
                                                                    <?php
                                                                        $state = $city = '';
                                                                        if(isset($job['Location_City']) && $job['Location_City'] != null && $job['Location_City'] != '') $city = ' - '.ucfirst($job['Location_City']);
                                                                        if(isset($job['Location_State']) && $job['Location_State'] != null && $job['Location_State'] != '') $state = ', '.db_get_state_name($job['Location_State'])['state_name'];
                                                                    ?>
                                                                    <tr>
                                                                        <td><?=reset_datetime(array('datetime' => $job['activation_date'], '_this' => $this)); ?></td>
                                                                        <td><?php echo ucwords($job['Title'].$city.$state); ?></td>

<!--                                                                        <td>-->
<!--                                                                            --><?php //$deactivation_date = $job['deactivation_date']; ?>
<!--                                                                            --><?php //if($deactivation_date != null || $deactivation_date != '') { ?>
<!--                                                                                --><?php //echo date('F j, Y,', strtotime($job['deactivation_date'])) . '<br />' . date('g:i a', strtotime($job['deactivation_date'])); ?>
<!--                                                                            --><?php //} else { ?>
<!--                                                                                N.A.-->
<!--                                                                            --><?php //} ?>
<!--                                                                        </td>-->

                                                                            <?php $status = $job['active']; ?>
                                                                            <?php if($status == 0) { ?>
                                                                                <td class="red">Inactive</td>
                                                                            <?php } else if($status == 1) { ?>
                                                                                <td class="green">Active</td>
                                                                            <?php } else if($status == 2) { ?>
                                                                                <td class="red">Archived</td>
                                                                            <?php } ?>

                                                                        <td style="color: <?php echo($job['views'] == 0 ? 'red' : 'green'); ?>;" class="text-center"><?php echo $job['views']; ?></td>
                                                                        <td style="color: <?php echo($job['applicant_count'] == 0 ? 'red' : 'green'); ?>;" class="text-center"><?php echo $job['applicant_count']; ?></td>

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
                                    <hr />
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <!--                                            <div id="pie_chart" class=""></div>-->
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
