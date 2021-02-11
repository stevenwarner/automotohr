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
                                        <?php   $active = '';
                                                $start_date = '';
                                                $end_date = '';

                                                if($promotion['active']==1){
                                                    $active = 'checked';
                                                }

                                                if(!empty($promotion['start_date'])){
                                                    $start_date = explode('-',$promotion['start_date']);
                                                    $start_date = $start_date['1']."-".substr($start_date['2'],0,2)."-".$start_date['0'];
                                                }

                                                if(!empty($promotion['end_date'])){
                                                    $end_date = explode('-',$promotion['end_date']);
                                                    $end_date = $end_date['1']."-".substr($end_date['2'],0,2)."-".$end_date['0'];
                                                } ?>
                                        
                                        <?php echo form_open('',array('class'=>'form-horizontal'));?>
                                            <ul>
                                                <li>
                                                    <label>Promotion Code <span class="hr-required">*</span></label>				
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-half-width-field">
                                                            <?php echo form_input('code',set_value('code',$promotion['code']),'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('code'); ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Discount <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-half-width-field">
                                                            <?php echo form_input('discount',set_value('discount',$promotion['discount']),'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('code'); ?>
                                                        </div>
                                                        <div class="hr-half-width-field">
                                                            <div class="hr-select-dropdown">
                                                                <?php   $options = array('percentage'    => '%',
                                                                                         'fixed'         => '$');
                                                                    echo form_dropdown('type', $options, set_value('type',$promotion['type']),' class="invoice-fields"'); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Maximum Uses</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-half-width-field">
                                                            <?php echo form_input('maximum_uses',set_value('maximum_uses',$promotion['maximum_uses']),'class="hr-form-fileds"'); ?>
                                                            <small>Leave empty or zero for unlimited uses</small>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Start Date</label>
                                                    <div class="hr-fields-wrap registration-date">
                                                        <div class="hr-half-width-field">
                                                            <?php   echo form_input('start_date',set_value('start_date',$start_date),'class="hr-form-fileds" id="startdate" readonly'); ?>
                                                            <?php echo form_error('start_date'); ?>
                                                            <small>Leave blank to disable start date restrictions</small>
                                                        </div>
                                                        <button class="ui-datepicker-trigger" type="button"><i class="fa fa-calendar"></i></button>						
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>End Date</label>
                                                    <div class="hr-fields-wrap registration-date">
                                                        <div class="hr-half-width-field">
                                                            <?php echo form_input('end_date',set_value('end_date', $end_date),'class="hr-form-fileds" id="enddate" readonly'); ?>
                                                            <?php echo form_error('end_date'); ?>
                                                            <small>Leave blank for none</small>
                                                        </div>
                                                        <button class="ui-datepicker-trigger" type="button"><i class="fa fa-calendar"></i></button>						
                                                    </div>
                                                </li>
                                                <?php if(in_array('full_access', $security_details) || in_array('activate_deactivate_promotion', $security_details)){ ?>
                                                    <li>
                                                        <label>Active</label>
                                                        <div class="hr-fields-wrap registration-date">
                                                            <div class="hr-register-date">
                                                                <input type="checkbox" name="active" value="active" class="hr-form-fileds" style="width:10%;" <?php echo $active; ?>/>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php } else { ?>
                                                    <?php if($active=='checked'){ ?>
                                                        <input type="hidden" name="active" value="1">
                                                    <?php } ?>
                                                <?php } ?>
                                                <li>
                                                    <input type="hidden" name="sid" value="<?php echo $promotion['sid']; ?>" class="site-btn">
                                                    <input type="submit" value="Update Promotional Code" class="site-btn">
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