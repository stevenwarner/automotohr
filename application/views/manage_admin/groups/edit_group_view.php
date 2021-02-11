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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title;?></h1>
                                    </div>
                                    <div class="row">
                                      <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="hr-user-form">
                                          <?php echo form_open('',array('class'=>'form-horizontal'));?>
                                            <div class="field-row field-row-autoheight">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-4 col-xs-12 col-sm-4">
                                                      <label class="valign-middle">Group name</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-8 col-xs-12 col-sm-8">
                                                      <?php
                                                        echo form_input('group_name',set_value('group_name',$group->name),'class="invoice-fields"');
                                                        echo form_error('group_name');
                                                      ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field-row field-row-autoheight">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-4 col-xs-12 col-sm-4">
                                                      <label class="valign-middle">Group description</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-8 col-xs-12 col-sm-8">
                                                      <?php
                                                        echo form_input('group_description',set_value('group_description',$group->description),'class="invoice-fields"');
                                                        echo form_error('group_description');
                                                        echo form_hidden('group_id',set_value('group_id',$group->id));
                                                      ?>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="field-row field-row-autoheight">                                                    
                                                <?php   if($group->name!='admin'){ ?>
                                                <?php   $available_permissions = array_column($permissions, 'function_name'); ?>
                                                            <?php   foreach($modules as $key=>$module){
                                                                        $is_header      = $module['is_header'];
                                                                        $status         = $module['status'];
                                                                        $module_name    = $module['module_name'];
                                                                        if($is_header=='true'){
                                                                            switch ($status) {
                                                                                    case 'top':
                                                                                        echo '<div class="hr-box">
                                                                                            <div class="hr-box-header bg-header-green">
                                                                                                <h4 class="hr-registered pull-left">'.$module_name.'</h4>
                                                                                            </div>
                                                                                            <div class="table-responsive hr-innerpadding">
                                                                                                <table class="table table-bordered">
                                                                                                    
                                                                                                    <tbody>';
                                                                                        $row_count = 0;
                                                                                        break;
                                                                                    case 'top/bottom':
                                                                                        echo '</tbody>
                                                                                                    </table>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="hr-box">
                                                                                                    <div class="hr-box-header bg-header-green">
                                                                                                        <h4 class="hr-registered pull-left">'.$module_name.'</h4>
                                                                                                    </div>
                                                                                                    <div class="table-responsive hr-innerpadding">
                                                                                                    <table class="table table-bordered">
                                                                                                        
                                                                                                        <tbody>';
                                                                                        $row_count = 0;
                                                                                        break;
                                                                                    case 'bottom':
                                                                                        echo '</tbody>
                                                                                            </table>
                                                                                            </div>
                                                                                        </div>';
                                                                                        $row_count = 1;
                                                                                        break;
                                                                                }
                                                                        }
                                                                        if($status=='enable'){ 
                                                                            if($row_count<1){ 
                                                                                echo '<tr>';
                                                                            } ?>
                                                                                    <td width="50%">
                                                                                        <label class="control control--checkbox font-normal">
                                                                                            <?php echo $module['display_name'];?>
                                                                                            <?php if (in_array($module['function_name'], $available_permissions)) { // hello world?>
                                                                                                <input type="checkbox" name="function_name[]" value="<?php echo $module['function_name'];?>"  checked="checked" /> 
                                                                                            <?php } else { ?>
                                                                                                <input type="checkbox" name="function_name[]" value="<?php echo $module['function_name'];?>"  /> 
                                                                                            <?php } ?>
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </td>
                                                                                
                                                                                <?php 
                                                                                    $row_count++; 
                                                                                    if($row_count==2){ 
                                                                                        echo '</tr>';
                                                                                        $row_count = 0;
                                                                                } ?>
                                                                    <?php }
                                                                    } 
                                                                } ?>    
                                            </div> 
                                            <div class="text-right field-row field-row-autoheight">
                                               <?php echo form_submit('submit', 'Save Group', 'class="site-btn"');?>
                                            </div>
                                        <?php echo form_close();?>
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
