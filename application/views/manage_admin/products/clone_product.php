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
                                                    <div class="hr-width-field">
                                                        <?php echo form_input('name', set_value('name', $edit_data['name']), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('name'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Product Type</label>
                                                <div class="hr-fields-wrap">
                                                    <select class="hr-form-fileds" name="product_type">
                                                        <option <?php if (set_value('product_type', $edit_data['product_type']) != NULL && set_value('product_type', $edit_data['product_type']) == 'background-checks') { ?> selected="selected" <?php } ?> value="background-checks">Background Check</option>
                                                        <option <?php if (set_value('product_type', $edit_data['product_type']) != NULL && set_value('product_type', $edit_data['product_type']) == 'behavioral-assessment') { ?> selected="selected" <?php } ?> value="behavioral-assessment">Behavioral Assessment</option>
                                                        <option <?php if (set_value('product_type', $edit_data['product_type']) != NULL && set_value('product_type', $edit_data['product_type']) == 'development-fee') { ?> selected="selected" <?php } ?>value="development-fee">Development Fee</option>
                                                        <option <?php if (set_value('product_type', $edit_data['product_type']) != NULL && set_value('product_type', $edit_data['product_type']) == 'drug-testing') { ?> selected="selected" <?php } ?> value="drug-testing">Drug Testing</option>
                                                        <option <?php if (set_value('product_type', $edit_data['product_type']) != NULL && set_value('product_type', $edit_data['product_type']) == 'account-package') { ?> selected="selected" <?php } ?>value="account-package">Our Platforms</option>
                                                        <option <?php if (set_value('product_type', $edit_data['product_type']) != NULL && set_value('product_type', $edit_data['product_type']) == 'facebook-api') { ?> selected="selected" <?php } ?>value="facebook-api">Facebook API</option>
                                                        <option <?php if (set_value('product_type', $edit_data['product_type']) != NULL && set_value('product_type', $edit_data['product_type']) == 'job-board') { ?> selected="selected" <?php } ?> value="job-board">Job Board</option>
                                                        <option <?php if (set_value('product_type', $edit_data['product_type']) != NULL && set_value('product_type', $edit_data['product_type']) == 'pay-per-job') { ?> selected="selected" <?php } ?>value="pay-per-job">Pay Per Job</option>
                                                        <option <?php if (set_value('product_type', $edit_data['product_type']) != NULL && set_value('product_type', $edit_data['product_type']) == 'reference-checks') { ?> selected="selected" <?php } ?> value="reference-checks">Reference Checks</option>
                                                        <option <?php if (set_value('product_type', $edit_data['product_type']) != NULL && set_value('product_type', $edit_data['product_type']) == 'video-interview') { ?> selected="selected" <?php } ?> value="video-interview">Video Interview</option>
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Product Brand</label>
                                                <div class="hr-fields-wrap">
                                                    <select class="hr-form-fileds" name="product_brand">
                                                        <option <?php if (set_value('product_brand', $edit_data['product_brand']) != NULL && set_value('product_brand', $edit_data['product_brand']) == "") { ?> selected="selected" <?php } ?> value="">Select Product Brand</option>
                                                        <option <?php if (set_value('product_brand', $edit_data['product_brand']) != NULL && set_value('product_brand', $edit_data['product_brand']) == 'ams') { ?> selected="selected" <?php } ?>value="ams">AutomotoSocial</option>
                                                        <option <?php if (set_value('product_brand', $edit_data['product_brand']) != NULL && set_value('product_brand', $edit_data['product_brand']) == 'indeed') { ?> selected="selected" <?php } ?>value="indeed">Indeed</option>
                                                        <option <?php if (set_value('product_brand', $edit_data['product_brand']) != NULL && set_value('product_brand', $edit_data['product_brand']) == 'ziprecruiter') { ?> selected="selected" <?php } ?>value="ziprecruiter">Zip Recruiter</option>
                                                        <option <?php if (set_value('product_brand', $edit_data['product_brand']) != NULL && set_value('product_brand', $edit_data['product_brand']) == 'job2career') { ?> selected="selected" <?php } ?>value="job2career">Job2Career</option>
                                                        <option <?php if (set_value('product_brand', $edit_data['product_brand']) != NULL && set_value('product_brand', $edit_data['product_brand']) == 'juju') { ?> selected="selected" <?php } ?>value="juju">JUJU</option>
                                                        <option <?php if (set_value('product_brand', $edit_data['product_brand']) != NULL && set_value('product_brand', $edit_data['product_brand']) == 'careerbuilder') { ?> selected="selected" <?php } ?> value="careerbuilder">Career Builder</option>
                                                        <option <?php if (set_value('product_brand', $edit_data['product_brand']) != NULL && set_value('product_brand', $edit_data['product_brand']) == 'accurate') { ?> selected="selected" <?php } ?> value="accurate">Accurate</option>
                                                        <option <?php if (set_value('product_brand', $edit_data['product_brand']) != NULL && set_value('product_brand', $edit_data['product_brand']) == 'assurehire') { ?> selected="selected" <?php } ?> value="assurehire">AssureHire</option>
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Product Image</label>				
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-half-width-field">
                                                        <?php if (isset($edit_data['product_image']) && $edit_data['product_image'] != "") { ?>
                                                            <div id="remove_image" class="logo-box">
                                                                <div><img src="<?php echo AWS_S3_BUCKET_URL . $edit_data['product_image']; ?>" width="150" height="150"></div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php echo form_upload('product_image', 'class="hr-form-fileds"'); ?>
                                                    </div>
                                                </div>
                                                <input type="hidden" value="<?php echo $edit_data['product_image']; ?>" name="old_picture">
                                            </li>
                                            <li>
                                                <label>Short Description</label>
                                                <div class="hr-fields-wrap">
                                                    <textarea style="padding:10px; height:200px;" class="hr-form-fileds" name="short_description"><?php echo set_value('short_description', $edit_data['short_description']); ?></textarea>
                                                    <?php echo form_error('short_description'); ?>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Detailed Description</label>
                                                <div class="hr-fields-wrap">
                                                    <textarea style="padding:10px; height:200px;" class="hr-form-fileds" name="detailed_description"><?php echo set_value('detailed_description', $edit_data['detailed_description']); ?></textarea>
                                                    <?php echo form_error('detailed_description'); ?>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Price <span class="hr-required">*</span></label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-half-width-field">
                                                        <?php echo form_input('price', set_value('price', $edit_data['price']), 'class="hr-form-fileds"'); ?>
                                                        <!--<small>Leave empty or 0 for unlimited posting</small>-->
                                                        <?php echo form_error('price'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Number Of Listings <span class="hr-required">*</span></label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-half-width-field">
                                                        <?php echo form_input('number_of_postings', set_value('number_of_postings', $edit_data['number_of_postings']), 'class="hr-form-fileds"'); ?>
                                                        <!--<small>Leave empty or zero for free</small>-->
                                                        <?php echo form_error('number_of_postings'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li style="display: none">
                                                <label>Start Date</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-half-width-field">
                                                        <?php echo form_input('availability_from', set_value('availability_from', $edit_data['availability_from']), 'class="hr-form-fileds" id="startdate" readonly'); ?>
                                                        <?php echo form_error('availability_from'); ?>
                                                        <!--<small>Leave blank to disable start date restrictions</small>-->
                                                    </div>
                                                    <button class="ui-datepicker-trigger" type="button"><i class="fa fa-calendar"></i></button>						
                                                </div>
                                            </li>
                                            <li style="display: none">
                                                <label>End Date</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-half-width-field">
                                                        <?php echo form_input('availability_to', set_value('availability_to', $edit_data['availability_to']), 'class="hr-form-fileds" id="enddate" readonly'); ?>
                                                        <?php echo form_error('availability_to'); ?>                                                        
                                                        <!--<small>Leave blank for none</small>-->
                                                    </div>
                                                    <button class="ui-datepicker-trigger" type="button"><i class="fa fa-calendar"></i></button>						
                                                </div>
                                            </li>
                                            <li>
                                                <label>Expire In Days <span class="hr-required">*</span></label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-half-width-field">
                                                        <?php echo form_input('expiry_days', set_value('expiry_days', $edit_data['expiry_days']), 'class="hr-form-fileds"'); ?>
                                                        <!--<small>Set empty or zero for never expire</small>-->
                                                        <?php echo form_error('expiry_days'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Product Sort Order</label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-half-width-field">
                                                        <input type="number" name='sort_order' value="<?php echo set_value('sort_order', $edit_data['sort_order']) ?>" min="0" step="any" class="hr-form-fileds"/>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Product Site URL</label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-half-width-field">
                                                        <?php echo form_input('url', set_value('url', $edit_data['url']), 'class="hr-form-fileds"'); ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Package Code</label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-half-width-field">
                                                        <?php echo form_input('package_code', set_value('package_code', $edit_data['package_code']), 'class="hr-form-fileds"'); ?>
                                                        <!--<small>Set empty or zero for never expire</small>-->
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Daily Job Listing</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <input type="checkbox" name="daily" value="daily" class="hr-form-fileds" style="width:10%;" <?php echo $edit_data['daily']; ?>/> 
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Featured</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <input type="checkbox" name="featured" value="featured" class="hr-form-fileds" style="width:10%;" <?php echo $edit_data['featured']; ?>/> 
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Active</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <input type="checkbox" name="active" value="active" class="hr-form-fileds" style="width:10%;" <?php echo $edit_data['active']; ?>/> 
                                                    </div>
                                            </li>
                                            <li>
                                                <label>Show in Market</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <input type="checkbox" name="in_market" value="in_market" class="hr-form-fileds" style="width:10%;" <?php echo $edit_data['in_market']; ?>/> 
                                                    </div>
                                            </li>
                                            <li>
                                                <label>Include Deluxe Theme</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <input type="checkbox" name="includes_deluxe_theme" value="includes_deluxe_theme" class="hr-form-fileds" style="width:10%;" <?php echo $edit_data['includes_deluxe_theme']; ?>/>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Include Facebook API</label>
                                                <div class="hr-fields-wrap registration-date">
                                                    <div class="hr-register-date">
                                                        <input type="checkbox" name="includes_facebook_api" value="includes_facebook_api" class="hr-form-fileds" style="width:10%;" <?php echo $edit_data['includes_facebook_api']; ?>/>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Maximum Employees</label>
                                                <div class="hr-fields-wrap">
                                                    <div class="hr-half-width-field">
                                                        <?php echo form_input('maximum_number_of_employees', set_value('maximum_number_of_employees', $edit_data['maximum_number_of_employees']), 'class="hr-form-fileds"'); ?>
                                                        <!--<small>Set empty or zero for never expire</small>-->
                                                    </div>
                                                </div>
                                            </li>
                                            <li><input type="submit" value="Clone Product" class="site-btn"></li>
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