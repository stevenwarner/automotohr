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
                                                    <div class="hr-setting-page">
                                                        <?php echo form_open('manage_admin/job_title_groups/add'); ?>
                                                        <input type="hidden" id="sid" name="sid" value="<?php echo $group['sid']; ?>" />
                                                        <input type="hidden" name="old_name" value="<?php echo $group['name']; ?>" />
                                                        <input type="hidden" id="action" name="action" value="save_job_listing_template_group" />
                                                        <ul>
                                                            <li>
                                                                <label>Template Title</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input('name', set_value('name', $group['name']), 'class="hr-form-fileds"'); ?>
                                                                    <?php echo form_error('name'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Description</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input('description', set_value('description', $group['description']), 'class="hr-form-fileds"'); ?>
                                                                    <?php echo form_error('description'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="hr-promotions table-responsive">
                                                                    <div class="heading-title page-title">
                                                                        <h1 class="page-title"><i class="fa fa-list-alt"></i>Mark Available Titles</h1>
                                                                    </div>
                                                                    <table>
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="col-xs-1"></th>
                                                                            <th class="col-xs-11">Title</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php foreach($titles as $title){?>
                                                                            <tr>
                                                                                <td>
                                                                                    <?php echo form_checkbox('templates[]', $title['sid'], set_checkbox('templates' , $title['sid'], in_array($title['sid'], $titlesArray)));?>
                                                                                    <?php echo form_error('title'); ?>
                                                                                </td>
                                                                                <td><?php echo $title['title']?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo base_url('manage_admin/job_title_templates'); ?>" class="site-btn"><i class="fa fa-reply"></i>&nbsp;Back</a>
                                                                <?php echo form_submit('save_group','Save', array('class'=>'site-btn'));?>
                                                            </li>
                                                        </ul>
                                                        <?php echo form_close(); ?>
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