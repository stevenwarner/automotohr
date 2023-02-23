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
                                    <div class="dashboard-content">
                                        <div class="dash-inner-block">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                                    </div>
                                                    <div class="hr-promotions table-responsive">
                                                        <table class="table table-bordered ">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-7">Access Level</th>
                                                                    <th class="col-xs-1 text-center">Status</th>
                                                                    <th class="col-xs-4 text-center" colspan="2">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php   if (!empty($security_types)) { ?>
                                                                <?php foreach ($security_types as $access_level) { ?>
                                                                    <tr>
                                                                        <td class="">
                                                                            <?php echo $access_level['access_level']; ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if($access_level['access_level'] != 'Admin') { ?>
                                                                                <?php echo ($access_level['status'] == 1 ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>'); ?>
                                                                            <?php } ?>
                                                                        </td>

                                                                        <td class="col-xs-1 text-center">
                                                                            <?php if($access_level['access_level'] != 'Admin') { ?>
                                                                                <a href="<?php echo base_url('manage_admin/security_settings/manage_members/' .  $access_level['sid']); ?>" class="btn btn-success btn-sm">Manage Members</a>
                                                                            <?php } ?>
                                                                        </td>

                                                                        <td class="col-xs-1 text-center">
                                                                            <?php echo anchor('manage_admin/security_settings/manage_permissions/' . $access_level['sid'], '<span class="btn btn-success btn-sm">Permissions</span>'); ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td class="text-center" colspan="2">
                                                                        <span class="no-data" >No Access Levels Defined</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>

                                                            <tr>
                                                                    <td class="text-center" colspan="4">
                                                                        <?php echo anchor('manage_admin/security_settings/add_level', '<span class="btn btn-success btn-sm">Add Access Level</span>'); ?>

                                                                    </td>
                                                                </tr>


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
</div>

<script>
    function func_set_status(access_level, status){

    }
</script>