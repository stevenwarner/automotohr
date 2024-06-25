<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                                        <?php echo form_open('manage_admin/job_title_templates/add'); ?>
                                                        <input type="hidden" id="sid" name="sid" value="<?php echo $template['sid']; ?>" />
                                                        <input type="hidden" id="action" name="action" value="save_job_title_template" />
                                                        <ul>
                                                            <li>
                                                                <label>Job Title</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'title'), set_value('text', $template['title'])); ?>
                                                                    <?php echo form_error('title'); ?>
                                                                </div>
                                                            </li>

                                                            <li>
                                                                <label>ComplyNet Job Title</label>
                                                                <div class="hr-fields-wrap">
                                                                    <select name="complynet_job_title" id="complynet_job_title" class="invoice-fields">
                                                                        <option <?= $template["complynet_job_title"] == null ? 'selected' : ''; ?> value="null">
                                                                            Please select job title
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'BDC Person' ? 'selected' : ''; ?> value="BDC Person">
                                                                            BDC Person
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Body Shop Estimator' ? 'selected' : ''; ?> value="Body Shop Estimator">
                                                                            Body Shop Estimator
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Body Shop Manager' ? 'selected' : ''; ?> value="Body Shop Manager">
                                                                            Body Shop Manager
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Body Shop Tech' ? 'selected' : ''; ?> value="Body Shop Tech">
                                                                            Body Shop Tech
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Cashier' ? 'selected' : ''; ?> value="Cashier">
                                                                            Cashier
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'CFO' ? 'selected' : ''; ?> value="CFO">
                                                                            CFO
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Detail Manager' ? 'selected' : ''; ?> value="Detail Manager">
                                                                            Detail Manager
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Detailer' ? 'selected' : ''; ?> value="Detailer">
                                                                            Detailer
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'F&I Manager' ? 'selected' : ''; ?> value="F&I Manager">
                                                                            F&I Manager
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'F&I Writer' ? 'selected' : ''; ?> value="F&I Writer">
                                                                            F&I Writer
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Fixed Operations Director' ? 'selected' : ''; ?> value="Fixed Operations Director">
                                                                            Fixed Operations Director
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'GM' ? 'selected' : ''; ?> value="GM">
                                                                            GM
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'HR Assistant' ? 'selected' : ''; ?> value="HR Assistant">
                                                                            HR Assistant
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'HR Manager' ? 'selected' : ''; ?> value="HR Manager">
                                                                            HR Manager
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'IT' ? 'selected' : ''; ?> value="IT">
                                                                            IT
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Office Employee' ? 'selected' : ''; ?> value="Office Employee">
                                                                            Office Employee
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Office Manager' ? 'selected' : ''; ?> value="Office Manager">
                                                                            Office Manager
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Owner' ? 'selected' : ''; ?> value="Owner">
                                                                            Owner
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Parts Desk' ? 'selected' : ''; ?> value="Parts Desk">
                                                                            Parts Desk
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Parts Driver' ? 'selected' : ''; ?> value="Parts Driver">
                                                                            Parts Driver
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Parts Manager' ? 'selected' : ''; ?> value="Parts Manager">
                                                                            Parts Manager
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Parts Sales' ? 'selected' : ''; ?> value="Parts Sales">
                                                                            Parts Sales
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Parts Shipper' ? 'selected' : ''; ?> value="Parts Shipper">
                                                                            Parts Shipper
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Porter' ? 'selected' : ''; ?> value="Porter">
                                                                            Porter
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Receptionist' ? 'selected' : ''; ?> value="Receptionist">
                                                                            Receptionist
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Sales Employee' ? 'selected' : ''; ?> value="Sales Employee">
                                                                            Sales Employee
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Sales Manager' ? 'selected' : ''; ?> value="Sales Manager">
                                                                            Sales Manager
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Sales Person' ? 'selected' : ''; ?> value="Sales Person">
                                                                            Sales Person
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Service Advisor' ? 'selected' : ''; ?> value="Service Advisor">
                                                                            Service Advisor
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Service Director' ? 'selected' : ''; ?> value="Service Director">
                                                                            Service Director
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Service Manager' ? 'selected' : ''; ?> value="Service Manager">
                                                                            Service Manager
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Service Office' ? 'selected' : ''; ?> value="Service Office">
                                                                            Service Office
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Service Tech' ? 'selected' : ''; ?> value="Service Tech">
                                                                            Service Tech
                                                                        </option>
                                                                        <option <?= $template["complynet_job_title"] == 'Warranty Clerk' ? 'selected' : ''; ?> value="Warranty Clerk">
                                                                            Warranty Clerk
                                                                        </option>
                                                                    </select>
                                                                </div>

                                                            </li>
                                                            <li>
                                                                <label>Sort Order</label>
                                                                <div class="hr-fields-wrap">
                                                                    <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'sort_order'), set_value('text', $template['sort_order'])); ?>
                                                                    <?php echo form_error('title'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label>Job Color</label>
                                                                <div class="hr-fields-wrap">
                                                                    <input type="color" name="color_code" value="<?=$template["color_code"] ?? "";?>">
                                                                    <?php echo form_error('color_code'); ?>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <a href=" <?php echo base_url('manage_admin/job_title_templates'); ?>" class="site-btn"><i class="fa fa-reply"></i>&nbsp;Back</a>
                                                                    <?php echo form_submit('setting_submit', 'Save', array('class' => 'site-btn')); ?>
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