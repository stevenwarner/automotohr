<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><a class="dashboard-link-btn" href="<?php echo base_url('my_settings'); ?>"><i class="fa fa-chevron-left"></i> Settings</a><?php echo $title; ?></span>

                        </div>
                    </div>
                    <div class="dashboard-conetnt-wrp">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="universal-form-style-v2">
                                <form method="post">
                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                    <input type="hidden" id="portal_sid" name="portal_sid" value="<?php echo $portal_sid; ?>" />

                                    <ul>
                                        <li class="form-col-100">
                                            <?php echo form_label('Page Title <span class="hr-required">*</span>', 'page_title'); ?>
                                            <?php $temp = (isset($maintenance_mode_detail['page_title']) ? $maintenance_mode_detail['page_title'] : '' ); ?>
                                            <?php echo form_input('page_title', set_value('page_title', $temp), 'class="invoice-fields"'); ?>
                                            <?php echo form_error('page_title'); ?>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <?php echo form_label('Page Content <span class="hr-required">*</span>', 'page_content'); ?>
                                            <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                            <?php $temp = html_entity_decode(isset($maintenance_mode_detail['page_content']) ? $maintenance_mode_detail['page_content'] : '' ); ?>
                                            <?php echo form_textarea('page_content', set_value('page_content', $temp, false), 'class="ckeditor"'); ?>
                                            <?php echo form_error('page_content'); ?>
                                        </li>

                                        <li class="form-col-100 autoheight">
                                            <label></label>
                                            <div class="row">
                                                <div class="col-xs-2">
                                                    <label class="control control--radio admin-access-level">
                                                        <input name="maintenance_mode_status" value="1" type="radio" <?php echo set_radio('maintenance_mode_status', 1, $maintenance_mode_enabled_default); ?> />
                                                        Enabled
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-xs-2">
                                                    <label class="control control--radio admin-access-level">
                                                        <input name="maintenance_mode_status" value="0" type="radio" <?php echo set_radio('maintenance_mode_status', 1, $maintenance_mode_disabled_default); ?> />
                                                        Disabled
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <label></label>

                                        </li>

                                        <li class="form-col-100">
                                            <label></label>
                                            <input value="Save" class="submit-btn" type="submit">
                                            <a class="submit-btn btn-cancel" href="<?php echo base_url('my_settings'); ?>">Cancel</a>
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