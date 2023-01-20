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
                                        <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <?php if (isset($users) && sizeof($users) > 0) { ?>
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
                                            <?php } ?>
                                            <!-- table -->
                                            <div class="table-responsive table-outer" id="print_div">
                                                <?php if (isset($users) && sizeof($users) > 0) { ?>
                                                    <?php foreach ($users as $user => $references) { ?>
                                                        <div class="hr-box">                                        
                                                            <div class="hr-box-header bg-header-green">
                                                                <h1 class="hr-registered">
                                                                    <span><?php echo $user; ?></span>
                                                                    <span style="float:right;"><?php echo sizeof($references) . ' ' .ucwords($references[0]['users_type']); ?></span>
                                                                </h1>
                                                            </div>
                                                            <div class="table-responsive hr-innerpadding">
                                                                <table class="table table-stripped table-hover table-bordered" id="example">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Reference Title</th>
                                                                            <th>Name</th>
                                                                            <th>Type</th>
                                                                            <th>Relation</th>
                                                                            <th>Email</th>
                                                                            <th>Department</th>
                                                                            <th>Branch</th>
                                                                            <th>Reference Organization</th>
                                                                        </tr> 
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (isset($references) && sizeof($references) > 0) { ?>
                                                                            <?php foreach ($references as $reference) { ?>
                                                                                <tr>
                                                                                    <td><?php echo $reference['reference_title']; ?></td>
                                                                                    <td><?php echo $reference['reference_name']; ?></td>
                                                                                    <td><?php echo ucwords($reference['reference_type']); ?></td>
                                                                                    <td><?php echo $reference['reference_relation']; ?></td>
                                                                                    <td><?php echo $reference['reference_email']; ?></td>
                                                                                    <td><?php echo $reference['department_name']; ?></td>
                                                                                    <td><?php echo $reference['branch_name']; ?></td>
                                                                                    <td><?php echo $reference['organization_name']; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <tr>
                                                                                <td class="text-center" colspan="8">
                                                                                        <?php if (!isset($references)) { ?>                                                                    
                                                                                        <div class="no-data">Please select company...</div>
                                                                                    <?php } else if (isset($references) && sizeof($references) <= 0) { ?>
                                                                                        <div class="no-data">No references found.</div>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <div class="hr-box">                                        
                                                        <div class="table-responsive hr-innerpadding">
                                                            <table class="table table-stripped table-hover table-bordered" id="example">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Reference Title</th><th>Name</th><th>Type</th><th>Relation</th><th>Email</th><th>Department</th><th>Branch</th><th>Reference Organization</th>
                                                                        <?php if (isset($search['company_or_brand']) && $search['company_or_brand'] == 'brand') { ?>
                                                                            <th>Company Name</th>
                                                                        <?php } ?>
                                                                    </tr> 
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="text-center" colspan="<?php
                                                                        if (isset($search['company_or_brand']) && $search['company_or_brand'] == 'brand') {
                                                                            echo '9';
                                                                        } else {
                                                                            echo '8';
                                                                        }
                                                                        ?>">
                                                                                <?php if (!isset($users)) { ?>                                                                    
                                                                                <div class="no-data">Please select company...</div>
                                                                            <?php } else if (isset($users) && sizeof($users) <= 0) { ?>
                                                                                <div class="no-data">No references found.</div>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <!-- table -->
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
