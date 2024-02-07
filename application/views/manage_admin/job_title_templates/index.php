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
                                    <div>
                                        <div class="add-new-promotions">
                                            <?php if(check_access_permissions_for_view($security_details, 'add_edit_group')){ ?>
                                                <a class="site-btn" href="<?php echo base_url('manage_admin/job_title_groups/add/'); ?>">Add New Group</a>
                                            <?php } ?>
                                            <?php if(check_access_permissions_for_view($security_details, 'add_edit_job_listing_templates')){ ?>
                                                <a class="site-btn" href="<?php echo base_url('manage_admin/job_title_templates/add/'); ?>">Add New Title</a>
                                            <?php } ?>
                                        </div>
                                        <div class="hr-promotions table-responsive">
                                            <div class="heading-title page-title">
                                                <h1 class="page-title"><i class="fa fa-list-alt"></i>Template Groups</h1>
                                            </div>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-4">Name</th>
                                                        <th class="col-xs-6">Description</th>
                                                        <?php $function_names = array('fDeleteTemplateGroup', 'fDeleteTemplate', 'fSwitchStatus', 'add_edit_group'); ?>
                                                        <?php if(check_access_permissions_for_view($security_details, $function_names)){ ?>
                                                            <th width="1%" colspan="3" class="actions">Actions</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($groups as $group){?>
                                                    <tr>
                                                        <td><?php echo $group['name']?></td>
                                                        <td><?php echo $group['description']?></td>
                                                        <?php if(check_access_permissions_for_view($security_details, 'add_edit_group')){ ?>
                                                            <td>
                                                                <a href="<?php echo base_url('manage_admin/job_title_groups/edit') . '/' . $group['sid']; ?>" class="hr-edit-btn" >EDIT</a>
                                                            </td>
                                                        <?php } ?>
                                                        <?php if(check_access_permissions_for_view($security_details, 'fDeleteTemplateGroup')){ ?>
                                                            <td>
                                                                <form method="post" action="<?php echo base_url('manage_admin/job_title_groups/add')?>" id="form_delete_template_group_<?php echo $group['sid'];?>">
                                                                    <input type="hidden" id="sid" name="sid" value="<?php echo $group['sid'];?>" />
                                                                    <input type="hidden" id="action" name="action" value="delete_job_listing_template_group" />
                                                                </form>
                                                                    <?php if ($group['sid'] != 1){?>
                                                                        <input type="button" id="btn-delete" onclick="fDeleteTemplateGroup(this);" class="hr-delete-btn" value="DELETE" data-sid="<?php echo $group['sid'];?>" />
                                                                    <?php } ?>
                                                            </td>
                                                        <?php } ?>
                                                        <?php if(check_access_permissions_for_view($security_details, 'fSwitchStatus')){ ?>
                                                            <td>
                                                                <form method="post" action="<?php echo base_url('manage_admin/job_title_groups/add')?>" id="form_switch_template_group_status_<?php echo $group['sid'];?>">
                                                                    <input type="hidden" id="sid" name="sid" value="<?php echo $group['sid'];?>" />
                                                                    <input type="hidden" id="action" name="action" value="switch_template_group_status" />
                                                                    <input type="hidden" id="status" name="status" value="<?php echo $group['status'];?>" />
                                                                </form>
                                                                <?php if ($group['sid'] != 1){?>
                                                                    <input type="button" id="btn-delete" onclick="fSwitchGroupStatus(this);" class="<?php echo ($group['status'] == 1 ? 'hr-delete-btn' : 'hr-edit-btn'); ?>" value="<?php echo ($group['status'] == 1 ? 'Deactivate' : 'Activate'); ?>" data-sid="<?php echo $group['sid'];?>" />
                                                                <?php } ?>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="hr-promotions table-responsive">
                                            <div class="heading-title page-title">
                                                <h1 class="page-title"><i class="fa fa-list-alt"></i>Titles</h1>
                                            </div>
                                            <table>
                                                <thead>
                                                    <tr>

                                                        <th class="col-xs-8">Title</th>
                                                        <th class="col-xs-1">Sort Order</th>
                                                        <th class="col-xs-1">Color</th>
                                                        <?php if(check_access_permissions_for_view($security_details, 'add_edit_job_listing_templates')){ ?>
                                                            <th width="1%" colspan="3" class="actions">Actions</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($templates as $template){?>
                                                        <tr>
                                                            <td><?php echo $template['title']?></td>
                                                            <td><?php echo $template['sort_order']?></td>
                                                            <td>
                                                                <div style="width: 100%; height: 30px; background: <?=$template["color_code"];?>"></div>
                                                            </td>

                                                            <?php if(check_access_permissions_for_view($security_details, 'add_edit_job_listing_templates')){ ?>
                                                            <td>
                                                                <a href="<?php echo base_url('manage_admin/job_title_templates/edit') . '/' . $template['sid']; ?>" class="hr-edit-btn" >EDIT</a>
                                                            </td>
                                                            <?php } ?>
                                                            <?php if(check_access_permissions_for_view($security_details, 'fDeleteTemplate')){ ?>
                                                                <td>
                                                                    <form method="post" action="<?php echo base_url('manage_admin/job_title_templates/add')?>" id="form_delete_template_<?php echo $template['sid'];?>">
                                                                        <input type="hidden" id="sid" name="sid" value="<?php echo $template['sid'];?>" />
                                                                        <input type="hidden" id="action" name="action" value="delete_job_listing_template" />
                                                                    </form>
                                                                    <input type="button" id="btn-delete" onclick="fDeleteTemplate(this);" class="hr-delete-btn" value="DELETE" data-sid="<?php echo $template['sid'];?>" />
                                                                </td>
                                                            <?php } ?>
                                                            <?php if(check_access_permissions_for_view($security_details, 'fSwitchStatus')){ ?>
                                                                <td>
                                                                    <form method="post" action="<?php echo base_url('manage_admin/job_title_templates/add')?>" id="form_switch_template_status_<?php echo $template['sid'];?>">
                                                                        <input type="hidden" id="sid" name="sid" value="<?php echo $template['sid'];?>" />
                                                                        <input type="hidden" id="action" name="action" value="switch_template_status" />
                                                                        <input type="hidden" id="status" name="status" value="<?php echo $template['status'];?>" />
                                                                    </form>
                                                                    <input type="button" id="btn-delete" onclick="fSwitchStatus(this);" class="<?php echo ($template['status'] == 1 ? 'hr-delete-btn' : 'hr-edit-btn'); ?>" value="<?php echo ($template['status'] == 1 ? 'Deactivate' : 'Activate'); ?>" data-sid="<?php echo $template['sid'];?>" />
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
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
    <script>
        function fDeleteTemplate(source){
            var sid = $(source).attr('data-sid');
            alertify.confirm(
                'Are you Sure!',
                'Are you sure you want to delete this Title?',
                function () {
                    //yes
                    $('#form_delete_template_' + sid).submit();
                },
                function () {
                    //no
                }
            ).set({
                    labels: {
                        'ok' : 'Yes',
                        'cancel' : 'No'
                    }
                });
        }

        function fSwitchStatus(source){
            var sid = $(source).attr('data-sid');
            $('#form_switch_template_status_' + sid).submit();
        }

        function fDeleteTemplateGroup(source){
            var sid = $(source).attr('data-sid');
            alertify.confirm(
                'Are you Sure!',
                'Are you sure you want to delete this Group?',
                function () {
                    //yes
                    $('#form_delete_template_group_' + sid).submit();
                },
                function () {
                    //no
                }
            ).set({
                    labels: {
                        'ok' : 'Yes',
                        'cancel' : 'No'
                    }
                });
        }

        function fSwitchGroupStatus(source){
            var sid = $(source).attr('data-sid');
            $('#form_switch_template_group_status_' + sid).submit();
        }
    </script>