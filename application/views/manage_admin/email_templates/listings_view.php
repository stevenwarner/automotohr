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
                                            <?php echo form_open(''); ?>
                                                <fieldset>
                                                    <legend>Add a New Email Template</legend>
                                                    <ul>
                                                        <li>
                                                            <label>Template Name</label>
                                                            <div class="hr-fields-wrap">
                                                                <?php echo form_input(array('class'=>'hr-template-name','name'=>'name'), set_value('name'));?>
                                                                <?php echo form_error('name'); ?>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <label>Group Name</label>
                                                            <div class="hr-fields-wrap">
                                                                <?php echo form_dropdown('group', $group_options, set_value('group'),' class="hr-template-name"'); ?>
                                                                <?php echo form_error('group'); ?>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <?php echo form_submit(array('class' => 'hr-btn-add'), 'Add'); ?>
                                                        </li>
                                                    </ul>
                                                </fieldset>
                                            <?php echo form_close(); ?>
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
                                                                <td width="90%"><?=$group_options[$value['group']];?></td>
                                                                <?php if(check_access_permissions_for_view($security_details, 'email_templates_view')){ ?>
                                                                    <td width="10%" class="last-col">
                                                                        <?php echo anchor('manage_admin/email_templates/email_templates_view/' . $value['group'], '<input class="hr-edit-btn" type="button" value="View Template Mails">'); ?>
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
</div>