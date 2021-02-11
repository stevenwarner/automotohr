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
                                        <h1 class="page-title" style="float: none;"><i class="fa fa-envelope-square"></i><?=rtrim(ucwords(str_replace(array('_', 'Templates'), ' ', $group_options[$group])), 's');?> Templates <a href="<?=base_url('manage_admin/email_templates');?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i>Back</a></h1>
                                    </div>
                                    <?php if(check_access_permissions_for_view($security_details, 'add_email_templates_group')){ ?>
                                        <div class="hr-add-new-template">
                                            <?php echo form_open('manage_admin/email_templates'); ?>
                                                <fieldset>
                                                    <legend>Add a New Email Template</legend>
                                                    <ul>
                                                        <li style="width: 90%;">
                                                            <label style="width: 15%">Template Name</label>
                                                            <div class="hr-fields-wrap" style="width: 85%;">
                                                                <?php echo form_input(array('class'=>'hr-template-name','name'=>'name','required'=>'required'), set_value('name'));?>
                                                                <?php echo form_error('name'); ?>
                                                            </div>
                                                        </li>
                                                        <li class="hidden">
                                                            <label>Group Name</label>
                                                            <div class="hr-fields-wrap">
                                                                <?php echo form_dropdown('group', array($group => $group), set_value('group'),' class="hr-template-name"'); ?>
                                                                <?php echo form_error('group'); ?>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <input type="hidden" name="txtgroup" value="<?=$group;?>" />
                                                            <?php echo form_submit(array('class' => 'hr-btn-add'), 'Add'); ?>
                                                        </li>
                                                    </ul>
                                                </fieldset>
                                            <?php echo form_close(); ?>
                                        </div>
                                    <?php } ?>
                                    <!-- Add New Email Template Start -->
                                    <!--<div class="hr-add-new-template">
                                        <?php //echo form_open(''); ?>
                                        <fieldset>
                                            <legend>Add a New Email Template</legend>
                                            <ul>
                                                <li>
                                                    <label>Template Name</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php //echo form_input(array('class' => 'hr-template-name', 'name' => 'name'), set_value('name')); ?>
                                                        <?php //echo form_error('name'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Group Name</label>
                                                    <div class="hr-fields-wrap">
                                                        <select class="hr-template-name" name="group">
                                                            <option value="">Select Group Name</option>
                                                            <option value="user">User Emails</option>
                                                            <option value="listing">Listing Emails</option>
                                                            <option value="product">Product Emails</option>
                                                            <option value="alerts">Email Alerts</option>
                                                            <option value="other">Other Emails</option>
                                                        </select>
                                                        <?php //echo form_error('group'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php //echo form_submit(array('class' => 'hr-btn-add'), 'Add'); ?>
                                                </li>
                                            </ul>
                                        </fieldset>
                                        <?php //echo form_close(); ?>
                                    </div>-->
                                    <!-- Add New Email Template End -->
                                    <!-- Search Result table Start -->
                                    <form action="" method="post">
                                        <div class="table-responsive table-outer">
                                            <div class="hr-template-result">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Template Name</th>
                                                            <th>Status</th>
                                                            <?php $function_name = array('edit_email_templates_view', 'delete_email_templates_view', );?>
                                                            <?php if(check_access_permissions_for_view($security_details, $function_name)){ ?>
                                                                <th class="last-col" colspan="2" valign="center">Action</th>
                                                            <?php } ?>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($data as $value) { ?>
                                                            <tr id="parent_<?= $value['sid'] ?>">
                                                                <td width="80%"><?php echo $value['name']; ?></td>
                                                                <td><label class="label label-<?=$value['status'] == 1 ? 'success' : 'danger';?>"><?=$value['status'] == 1 ? 'Active' : 'InActive';?></label></td>
                                                                <?php if(check_access_permissions_for_view($security_details, 'edit_email_templates_view')){ ?>
                                                                    <td width="10%" class="last-col">
                                                                        <?php echo anchor('manage_admin/email_templates/edit_email_templates_view/' . $value['sid'], '<input class="hr-edit-btn" type="button" value="Edit">'); ?>
                                                                    </td>
                                                                <?php } ?>
                                                                <?php if(check_access_permissions_for_view($security_details, 'delete_email_templates_view')){ ?>
                                                                    <td>
                                                                        <input class="hr-delete-btn" type="button" id="<?= $value['sid'] ?>" value="Delete" onclick="return deleteEmailTemplate(this.id)" name="button">
                                                                    </td>
                                                                <?php } ?>
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
    <script>
        function deleteEmailTemplate(id){
            url = "<?= base_url() ?>manage_admin/email_templates/delete_email_templates_view";
            alertify.confirm('Confirmation', "Are you sure you want to delete this Email Template?",
                    function () {
                        $.post(url, {action: 'delete', sid: id})
                                .done(function (data) {
                                    $("#parent_" + id).remove();
                                });
                        alertify.success('Selected Email Template have been Deleted.');
                    },
                    function () {
                        alertify.error('Canceled');
                    });
        }
    </script>