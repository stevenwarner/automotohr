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
                                        <h1 class="page-title"><i class="fa fa-tags"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/job_categories_manager')?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Job Listing Categories</a>
                                    </div>
                                    <div class="add-new-company">
                                        <?php $temp = (isset($job_category['value']) ? 'Update Job Listing Category' : 'Add New Job Listing Category'); ?>

                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><?php echo $temp; ?></h1>
                                        </div>
                                        <form method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($job_category['value']) ? $job_category['value'] : ''); ?>
                                                        <label for="category_name">Job Listing Category Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('category_name', set_value('category_name', $temp), ' class="hr-form-fileds" id="category_name" '); ?>
                                                        <?php echo form_error('category_name'); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php $temp = (isset($job_category['value']) ? 'Update Job Listing Category' : 'Add New Job Listing Category'); ?>

                                                    <button type="submit" class="btn btn-success"><?php echo $temp; ?></button>
                                                    <a class="black-btn btn full-on-small" href="<?php echo base_url('manage_admin/job_categories_manager'); ?>">Cancel</a>
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
</div>
