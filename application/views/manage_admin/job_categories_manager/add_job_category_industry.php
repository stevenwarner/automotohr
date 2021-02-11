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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-tags"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/job_categories_manager/job_category_industries')?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Job Category Industries</a>
                                    </div>
                                    <div class="add-new-company">
                                        <?php $temp = (isset($job_category_industry['sid']) ? 'Update Job Category Industry' : 'Add New Job Category Industry'); ?>
                                        <form method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="field-row">
                                                        <?php $temp = (isset($job_category_industry['industry_name']) ? $job_category_industry['industry_name'] : ''); ?>
                                                        <label for="group_name">Industry Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('industry_name', set_value('industry_name', $temp), ' class="hr-form-fileds" id="group_name" '); ?>
                                                        <?php echo form_error('industry_name'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <?php $temp = (isset($job_category_industry['short_description']) ? $job_category_industry['short_description'] : ''); ?>
                                                        <label for="group_name">Description</label>
                                                        <?php echo form_textarea('short_description', set_value('short_description', $temp), ' class="hr-form-fileds field-row-autoheight" id="short_description" '); ?>
                                                        <?php echo form_error('short_description'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php $temp = (isset($job_category_industry['sid']) ? 'Update' : 'Submit'); ?>
                                                    <button type="submit" class="btn btn-success"><?php echo $temp; ?></button>
                                                    <a class="black-btn btn full-on-small" href="<?php echo base_url('manage_admin/job_categories_manager/job_category_industries'); ?>">Cancel</a>
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
