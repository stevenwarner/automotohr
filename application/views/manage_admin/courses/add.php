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
                                        <h1 class="page-title"><i class="fa fa-list"></i><?= $page_title?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/custom_job_feeds_management'); ?>"><i class="fa fa-long-arrow-left"></i> Back</a>
                                    </div>
                                    <br />
                                    <br />
                                    <br />
                                    <div style="min-height: 790px;">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label for="title">Title <span class="hr-required">*</span></label>
                                                                <?php echo form_input('title', set_value('title'), 'class="hr-form-fileds" id="jsCourseTitle"'); ?>
                                                                <?php echo form_error('title'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label for="status">Status</label>
                                                                <select name="status" id="jsCourseStatus" class="hr-form-fileds">
                                                                    <option value="1">Active</option>
                                                                    <option value="0">In Active</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="field-row">
                                                                <label for="jobtitle">Jobs</label>
                                                                <select multiple="multiple" name="jobtitle[]" id="jsJobTitle" class="hr-form-fileds">
                                                                    <?php foreach ($job_titles as $jobTitle) { ?>
                                                                        <option value="<?php echo $jobTitle['sid'];?>"><?php echo $jobTitle['title'];?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="field-row field-row-autoheight">
                                                                <label for="desc">Description</label>
                                                                <textarea name="desc" cols="40" rows="10" class="hr-form-fileds field-row-autoheight" spellcheck="false" id="jsCourseDescription"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <h4 class="hr-registered">Course Type</h4>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <div class="field-row">
                                                                    <label class="control control--radio">Manual
                                                                        <input type="radio" name="jsCourseChoice" class="jsCourseType" value="manual" checked="">
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <div class="field-row">
                                                                    <label class="control control--radio">Scorm
                                                                        <input type="radio" name="jsCourseChoice" class="jsCourseType" value="scorm">
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 jsScormFields">
                                                            <div class="field-row">
                                                                <label for="status">Scorm Version</label>
                                                                <select name="status" id="status" class="hr-form-fileds">
                                                                    <option value="12">1.2</option>
                                                                    <option value="20042nd">2004 2nd Edition</option>
                                                                    <option value="20043rd">2004 3rd Edition</option>
                                                                    <option value="20044th">2004 4td Edition</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 jsScormFields">
                                                            <h4 class="hr-registered">Upload Scorm</h4>
                                                            <input type="file" name="attachment" id="jsUploadScormFile" class="hidden" />
                                                        </div>
                                                        <input type="hidden" value="add" name="perform_action">

                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                            <button class="search-btn" id="jsSaveCourseInfo">
                                                                Add Course
                                                            </button>
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
<script type="text/javascript">
    
</script>
