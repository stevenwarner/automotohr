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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?>
                                        </h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Company</a>
                                    </div>

                                    <div class="edit-template-from-main">
                                        <div class="add-new-company">
                                            <div class="heading-title page-title">
                                                <h1 class="page-title"><?php echo ucwords($company_info['CompanyName']); ?></h1>
                                            </div>
                                            <form action="<?php echo base_url('manage_admin/companies/manage_maintenance_mode/' . $company_sid); ?>" method="post">
                                                <?php echo form_open('', array('class' => 'form-horizontal')); ?>

                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                <input type="hidden" id="portal_sid" name="portal_sid" value="<?php echo $portal_sid; ?>" />

                                                <ul>
                                                    <li>
                                                        <?php echo form_label('Title <span class="hr-required">*</span>', 'page_title'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php $temp = (isset($maintenance_mode_detail['page_title']) ? $maintenance_mode_detail['page_title'] : '' ); ?>
                                                            <?php echo form_input('page_title', set_value('page_title', $temp), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('page_title'); ?>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <?php echo form_label('Content <span class="hr-required">*</span>', 'page_content'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php $temp = html_entity_decode(isset($maintenance_mode_detail['page_content']) ? $maintenance_mode_detail['page_content'] : '' ); ?>
                                                            <?php echo form_textarea('page_content', set_value('page_content', $temp, false), 'class="ckeditor"'); ?>
                                                            <?php echo form_error('page_content'); ?>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <?php echo form_label('Status', 'page_content'); ?>
                                                        <label class="control control--radio admin-access-level">
                                                            <input name="maintenance_mode_status" value="1" type="radio" <?php echo set_radio('maintenance_mode_status', 1, $maintenance_mode_enabled_default); ?> />
                                                            Enabled
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label></label>
                                                        <label class="control control--radio admin-access-level">
                                                            <input name="maintenance_mode_status" value="0" type="radio" <?php echo set_radio('maintenance_mode_status', 1, $maintenance_mode_disabled_default); ?> />
                                                            Disabled
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>

                                                    <li>
                                                        <input type="submit" name="submit" value="Save" class="search-btn">
                                                    </li>
                                                </ul>
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
    </div>
</div>

