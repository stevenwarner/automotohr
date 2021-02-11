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
                                        <?php echo form_open('',array('class'=>'form-horizontal'));?>
                                            <ul>
                                                <li>
                                                    <label>
                                                        Promotion Code
                                                        <span class="hr-required">*</span>
                                                    </label>				
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-half-width-field">
                                                            <?php   echo form_input('code',set_value('code'),'class="hr-form-fileds"'); 
                                                                    echo form_error('code');
                                                            ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>
                                                        Discount
                                                        <span class="hr-required">*</span>
                                                    </label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-half-width-field">
                                                            <?php   echo form_input('discount',set_value('discount'),'class="hr-form-fileds"');  
                                                                    echo form_error('code');
                                                            ?>
                                                        </div>
                                                        <div class="hr-half-width-field">
                                                            <div class="hr-select-dropdown">
                                                                <?php 
                                                                $options = array(
                                                                        'percentage'    => '%',
                                                                        'fixed'         => '$'
                                                                        );
                                                                echo form_dropdown('type', $options, set_value('type'),' class="invoice-fields"');
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Maximum Uses</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-half-width-field">
                                                            <?php echo form_input('maximum_uses',set_value('maximum_uses'),'class="hr-form-fileds"'); ?>
                                                            <small>Leave empty or zero for unlimited uses</small>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Start Date</label>
                                                    <div class="hr-fields-wrap registration-date">
                                                        <div class="hr-half-width-field">
                                                            <?php   echo form_input('start_date',set_value('start_date'),'class="hr-form-fileds" id="startdate" readonly'); 
                                                                    echo form_error('start_date');
                                                            ?>
                                                            <small>Leave blank to disable start date restrictions</small>
                                                        </div>
                                                        <button class="ui-datepicker-trigger" type="button"><i class="fa fa-calendar"></i></button>						
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>End Date</label>
                                                    <div class="hr-fields-wrap registration-date">
                                                        <div class="hr-half-width-field">
                                                            <?php   echo form_input('end_date',set_value('end_date'),'class="hr-form-fileds" id="enddate" readonly'); 
                                                                    echo form_error('end_date');
                                                            ?>
                                                            <small>Leave blank for none</small>
                                                        </div>
                                                        <button class="ui-datepicker-trigger" type="button"><i class="fa fa-calendar"></i></button>						
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Active</label>
                                                    <div class="hr-fields-wrap registration-date">
                                                        <div class="hr-register-date">
                                                            <?php echo form_checkbox('active','active', set_checkbox('active','active')); ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li><input type="submit" value="Add Promotional Code" class="site-btn"></li>
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