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
                                        <h1 class="page-title"><i class="fa fa-file-code-o"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="hr-add-new-promotions">
                                        <?php echo form_open_multipart('', array('class' => 'form-horizontal')); ?>
                                        <ul>
                                            <li>
                                                <label>Product Name <span class="hr-required">*</span></label>				
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-full-width-field">
                                                        <?php echo form_input('name', set_value('name'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('name'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Product Type</label>
                                                <div class="hr-fields-wrap">
                                                    <select class="hr-form-fileds" name="product_type">
                                                        <option <?php if (set_value('product_type') != NULL && set_value('product_type') == 'background-checks') { ?> selected="selected" <?php } ?>value="background-check">Background Check</option>
                                                        <option <?php if (set_value('product_type') != NULL && set_value('product_type') == 'behavioral-assessment') { ?> selected="selected" <?php } ?>value="behavioral-assessment">Behavioral Assessment</option>
                                                        <option <?php if (set_value('product_type') != NULL && set_value('product_type') == 'development-fee') { ?> selected="selected" <?php } ?>value="development-fee">Development Fee</option>
                                                        <option <?php if (set_value('product_type') != NULL && set_value('product_type') == 'drug-testing') { ?> selected="selected" <?php } ?>value="drug-testing">Drug Testing</option>
                                                        <!--<option  --><?php //if (set_value('product_type') != NULL && set_value('product_type') == 'enterprise-theme') { ?><!-- selected="selected" --><?php //} ?><!--value="enterprise-theme">Our Platforms</option>-->
                                                        <option  <?php if (set_value('product_type') != NULL && set_value('product_type') == 'account-package') { ?> selected="selected" <?php } ?>value="account-package">Our Platforms</option>
                                                        <option <?php if (set_value('product_type') != NULL && set_value('product_type') == 'facebook-api') { ?> selected="selected" <?php } ?>value="facebook-api">Facebook API</option>
                                                        <option <?php if (set_value('product_type') != NULL && set_value('product_type') == 'job-board') { ?> selected="selected" <?php } ?> value="job-board">Job Board</option>
                                                        <option <?php if (set_value('product_type') != NULL && set_value('product_type') == 'pay-per-job') { ?> selected="selected" <?php } ?>value="pay-per-job">Pay Per Job</option>
                                                        <option <?php if (set_value('product_type') != NULL && set_value('product_type') == 'reference-checks') { ?> selected="selected" <?php } ?>value="reference-checks">Reference Checks</option>
                                                        <option <?php if (set_value('product_type') != NULL && set_value('product_type') == 'video-interview') { ?> selected="selected" <?php } ?>value="video-interview">Video Interview</option>
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Product Brand</label>
                                                <div class="hr-fields-wrap">
                                                    <select class="hr-form-fileds" name="product_brand">
                                                        <option <?php if (set_value('product_brand') != NULL && set_value('product_brand') == "") { ?> selected="selected" <?php } ?> value="">Select Product Brand</option>
                                                        <option <?php if (set_value('product_brand') != NULL && set_value('product_brand') == 'ams') { ?> selected="selected" <?php } ?>value="ams">AutomotoSocial</option>
                                                        <option <?php if (set_value('product_brand') != NULL && set_value('product_brand') == 'indeed') { ?> selected="selected" <?php } ?>value="indeed">Indeed</option>
                                                        <option <?php if (set_value('product_brand') != NULL && set_value('product_brand') == 'ziprecruiter') { ?> selected="selected" <?php } ?>value="ziprecruiter">Zip Recruiter</option>
                                                        <option <?php if (set_value('product_brand') != NULL && set_value('product_brand') == 'job2career') { ?> selected="selected" <?php } ?>value="job2career">Job2Career</option>
                                                        <option <?php if (set_value('product_brand') != NULL && set_value('product_brand') == 'juju') { ?> selected="selected" <?php } ?>value="juju">JUJU</option>
                                                        <option <?php if (set_value('product_brand') != NULL && set_value('product_brand') == 'careerbuilder') { ?> selected="selected" <?php } ?> value="careerbuilder">Career Builder</option>
                                                        <option <?php if (set_value('product_brand') != NULL && set_value('product_brand') == 'accurate') { ?> selected="selected" <?php } ?> value="accurate">Accurate</option>
                                                        <option <?php if (set_value('product_brand') != NULL && set_value('product_brand') == 'assurehire') { ?> selected="selected" <?php } ?> value="assurehire">AssureHire</option>
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Product Image</label>				
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-half-width-field">
                                                        <?php echo form_upload('product_image', 'class="hr-form-fileds"'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Short Description</label>
                                                <div class="hr-fields-wrap">
                                                    <textarea style="padding:10px; height:200px;" class="hr-form-fileds" name="short_description"><?php echo set_value('short_description'); ?></textarea>
                                                    <?php echo form_error('short_description'); ?>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Detailed Description</label>
                                                <div class="hr-fields-wrap">
                                                    <textarea   style="padding:10px; height:200px;" class="hr-form-fileds ckeditor" name="detailed_description"><?php echo set_value('detailed_description'); ?></textarea>
                                                    <?php echo form_error('detailed_description'); ?>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Sale Price <span class="hr-required">*</span></label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-full-width-field">
                                                        <?php echo form_input('price', set_value('price'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('price'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Cost Price <span class="hr-required">*</span></label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-full-width-field">
                                                        <?php echo form_input('cost_price', set_value('cost_price'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('cost_price'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Number Of Listings <span class="hr-required">*</span></label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-full-width-field">
                                                        <?php echo form_input('number_of_postings', set_value('number_of_postings'), 'class="hr-form-fileds"'); ?>
<!--                                                        <small>Leave empty or 0 for unlimited posting</small>-->
                                                        <?php echo form_error('number_of_postings'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li style="display: none">
                                                <label>Start Date</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-full-width-field">
                                                        <?php echo form_input('availability_from', set_value('availability_from'), 'class="hr-form-fileds" id="startdate" readonly'); ?>
                                                        <?php echo form_error('availability_from'); ?>
                                                    </div>
                                                    <button class="ui-datepicker-trigger" type="button"><i class="fa fa-calendar"></i></button>						
                                                </div>
                                            </li>
                                            <li  style="display: none">
                                                <label>End Date</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-full-width-field">
                                                        <?php echo form_input('availability_to', set_value('availability_to'), 'class="hr-form-fileds" id="enddate" readonly'); ?>
                                                        <?php echo form_error('availability_to'); ?>
                                                    </div>
                                                    <button class="ui-datepicker-trigger" type="button"><i class="fa fa-calendar"></i></button>						
                                                </div>
                                            </li>
                                            <li>
                                                <label>Expire In Days <span class="hr-required">*</span></label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-full-width-field">
                                                        <?php echo form_input('expiry_days', set_value('expiry_days'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('expiry_days'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Product Sort Order</label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-full-width-field">
                                                        <input type="number" name='sort_order' value="<?php echo set_value('sort_order', 1) ?>" min="0" step="any" class="hr-form-fileds"/>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Product Site URL</label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-full-width-field">
                                                        <?php echo form_input('url', set_value('url'), 'class="hr-form-fileds"'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Package Code</label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-full-width-field">
                                                        <?php echo form_input('package_code', set_value('package_code'), 'class="hr-form-fileds"'); ?>
                                                    </div>
                                                </div>
                                            </li>  
                                            <li>
                                                <label>Daily Job Listing</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <?php echo form_checkbox('daily', 'daily', set_checkbox('daily', 'daily'), ' style="width:10%;"'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Featured</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <?php echo form_checkbox('featured', 'featured', set_checkbox('featured', 'featured'), ' style="width:10%;"'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Active</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <?php echo form_checkbox('active', 'active', set_checkbox('active', 'active'), ' style="width:10%;"'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Show in Market</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <?php echo form_checkbox('in_market', 'in_market', set_checkbox('in_market', 'in_market'), ' style="width:10%;"'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label for="includes_deluxe_theme">Include Deluxe Theme</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <?php echo form_checkbox('includes_deluxe_theme', 'includes_deluxe_theme', set_checkbox('includes_deluxe_theme', 'includes_deluxe_theme'), ' style="width:10%;"'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label for="includes_facebook_api">Include Facebook API</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <?php echo form_checkbox('includes_facebook_api', 'includes_facebook_api', set_checkbox('includes_facebook_api', 'includes_facebook_api'), ' style="width:10%;"'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Maximum Employees</label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-full-width-field">
                                                        <input type="number" name='maximum_number_of_employees' value="<?php echo set_value('maximum_number_of_employees', 100) ?>" min="0" step="any" class="hr-form-fileds"/>
                                                    </div>
                                                </div>
                                            </li>
                                            <li><input type="submit" value="Add Product" class="site-btn"></li>
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

<script>
    $(document).ready(function () {
        CKEDITOR.replace('detailed_description');
    });
</script>