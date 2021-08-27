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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-envelope-square"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <?php if(check_access_permissions_for_view($security_details, 'add_email_templates_group')){ ?>
                                        <div class="hr-add-new-template">
                                            
                                        </div>
                                    <?php } ?>
                                    <!-- Search Result table Start -->
                                    <form action="" method="post">
                                        <div class="table-responsive table-outer">
                                            <div class="hr-template-result">
                                                <table class="table table-bordered table-stripped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Template Name</th>
                                                            <?php $function_names = array('add_email_templates_group'); ?>
                                                            <?php if(check_access_permissions_for_view($security_details, $function_names)){ ?>
                                                                <th class="last-col" valign="center">Action</th>
                                                            <?php } ?>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($data as $value) { ?>
                                                            <tr>
                                                                <td width="70%"><?php echo $value['name']; ?></td>
                                                                <td width="30%" class="text-center"><a class="hr-edit-btn" href="<?php echo base_url('manage_admin/performance_management/edit_performance_template').'/'.$value['sid']; ?>">Edit Template Question</a></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>