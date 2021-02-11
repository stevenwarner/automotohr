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

                                    <div class="dashboard-content">
                                        <div class="dash-inner-block">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                                        <a href="<?php echo base_url('manage_admin/security_settings')?>" class="btn black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Security Settings</a>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="alert alert-info">
                                                                <strong>Info!</strong> You can only deactivate <strong>"Access levels"</strong> which are not assigned to any of the users.
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="add-new-company">
                                                        <div class="heading-title page-title">
                                                            <span class="page-title pull-left">Access Level :&nbsp;<?php echo $access_level; ?></span>
                                                            <span class="pull-right" style="font-weight: normal;">
                                                                Total Members :&nbsp;<?php echo count($members); ?>
                                                            </span>
                                                        </div>

                                                    </div>
                                                    <div class="hr-box">
                                                        <div class="hr-innerpadding">
                                                            <div class="hr-box-body">
                                                                <div class="table-responsive">
                                                                    <form id="form_update_access_levels" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="update_access_levels" />
                                                                        <input type="hidden" id="current_access_level_sid" name="current_access_level_sid" value="<?php echo $access_level_details['sid']; ?>" />
                                                                        <input type="hidden" id="current_access_level" name="current_access_level" value="<?php echo $access_level_details['access_level']; ?>" />

                                                                        <table class="table table-bordered table-stripped table-hover">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="col-xs-5">Company Name</th>
                                                                                    <th class="col-xs-4">User Name</th>
                                                                                    <th class="col-xs-3">Access Level</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php if(!empty($members)) { ?>
                                                                                    <?php foreach($members as $member) { ?>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <?php echo ucwords($member['CompanyName']); ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php echo ucwords($member['first_name'] . ' ' . $member['last_name']); ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <?php if(!empty($access_levels)) { ?>
                                                                                                    <div class="hr-select-dropdown">
                                                                                                        <select id="access_level_<?php echo $member['parent_sid'] . '_' . $member['user_sid']; ?>" name="access_level_<?php echo $member['parent_sid'] . '_' . $member['user_sid']; ?>" class="invoice-fields">
                                                                                                            <?php foreach($access_levels as $access_level) { ?>
                                                                                                                <?php $default_selected = ($access_level['access_level'] == $member['access_level'] ? true : false); ?>
                                                                                                                <option value="<?php echo $access_level['access_level']; ?>" <?php echo set_select('access_level_' . $member['parent_sid'] . '_' . $member['user_sid'], $access_level['access_level'], $default_selected ); ?>><?php echo $access_level['access_level']; ?></option>
                                                                                                            <?php } ?>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                <?php } ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                <?php } else { ?>
                                                                                    <tr>
                                                                                        <td class="text-center" colspan="3">
                                                                                            <span class="no-data">No Members</span>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </form>
                                                                </div>
                                                            </div>

                                                            <div class="hr-box-footer">
                                                                <?php if(count($members) > 0) { ?>
                                                                    <button onclick="func_update_access_levels();" type="button" class="btn btn-success pull-left">Update Access Levels</button>
                                                                <?php } ?>

                                                                <?php if($access_level_details['status'] == 0) { ?>
                                                                    <form class="pull-left hidden-small-form" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="activate_security_access_level" />
                                                                        <input type="hidden" id="access_level_sid" name="access_level_sid" value="<?php echo $access_level_details['sid']; ?>" />
                                                                        <button type="submit" class="btn btn-success">Activate</button>
                                                                    </form>
                                                                    &nbsp;
                                                                <?php } else { ?>
                                                                    <?php if(count($members) <= 0) { ?>
                                                                        <form class="pull-left hidden-small-form" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                            <input type="hidden" id="perform_action" name="perform_action" value="deactivate_security_access_level" />
                                                                            <input type="hidden" id="access_level_sid" name="access_level_sid" value="<?php echo $access_level_details['sid']; ?>" />
                                                                            <button type="submit" class="btn btn-danger">De Activate</button>
                                                                        </form>
                                                                        &nbsp;
                                                                    <?php } else { ?>
                                                                        <button type="button" class="btn btn-danger disabled hidden-small-form" disabled="disabled">De Activate</button>
                                                                        &nbsp;
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <a href="<?php echo base_url('manage_admin/security_settings')?>" class="btn black-btn">Cancel</a>
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
    function func_update_access_levels(){
        $('#form_update_access_levels').submit();
    }
</script>