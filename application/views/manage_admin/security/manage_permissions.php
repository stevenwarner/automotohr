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
                                    <div class="dashboard-content">
                                        <div class="dash-inner-block">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                                    </div>                                                    
                                                    <div class="hr-promotions table-responsive">
                                                        <label>Security Description</label>
                                                        <?php   echo form_open('',array('class'=>'form-horizontal'));?>
                                                        <div class="hr-fields-wrap">
                                                            <textarea style="padding:10px; height:200px; background: #fff;" class="hr-form-fileds" name="description"><?php echo $description; ?></textarea>
                                                        </div>
                                                        <?php   if($access_level!='Admin') { ?>
                                                        <?php   $available_permissions = array_column($permissions, 'function_name'); ?>
                                                        <?php   //$row_count = 0; ?>
                                                                <?php   foreach($modules as $key=>$module){
                                                                            $is_header      = $module['is_header'];
                                                                            $status         = $module['status'];
                                                                            $module_name    = $module['module_name'];
                                                                            
                                                                            if($access_level == 'Employee' && $module_name == 'Calendar / Events') {
                                                                                $module_name .= '&nbsp;<span style="color:#000;">[Please note: Employee can only Update, Cancel, Delete or Re Schedule their own Events]</span>';
                                                                            }

                                                                            if($is_header=='true') {
                                                                                switch ($status) {
                                                                                        case 'top':
                                                                                            echo '<div class="hr-promotions table-responsive">
                                                                                                    <table>
                                                                                                        <thead>
                                                                                                            <tr style="height:50px;">
                                                                                                                <th style="background: #81b431; color: white;" colspan="4">'.$module_name.'</th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody>';
                                                                                            $row_count = 0;
                                                                                            break;
                                                                                        case 'top/bottom':
                                                                                            echo '</tbody>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                    <div class="hr-promotions table-responsive">
                                                                                                        <table>
                                                                                                            <thead>
                                                                                                                <tr style="height:50px;">
                                                                                                                    <th style="background: #81b431; color: white;" colspan="4">'.$module_name.'</th>
                                                                                                                </tr>
                                                                                                            </thead>
                                                                                                            <tbody>';
                                                                                            $row_count = 0;
                                                                                            break;
                                                                                        case 'bottom':
                                                                                            echo '</tbody>
                                                                                                </table>
                                                                                            </div>';
                                                                                            $row_count = 1;
                                                                                            break;
                                                                                    }
                                                                            }
                                                                            
                                                                            if($status=='enable') { 
                                                                                if($row_count <1 ) { 
                                                                                    echo '<tr style="background: white;">';
                                                                                } ?>
                                                                                        <td width="10%">
                                                                                        <div class="hr-register-date">
                                                                                            <?php if (in_array($module['function_name'], $available_permissions)) { // hello world?>
                                                                                                <input type="checkbox" name="function_name[]" value="<?php echo $module['function_name'];?>" class="hr-form-fileds" checked="checked" />
                                                                                            <?php } else { ?>
                                                                                                <input type="checkbox" name="function_name[]" value="<?php echo $module['function_name'];?>" class="hr-form-fileds" />
                                                                                            <?php } ?>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td width="40%"><?php echo $module['display_name'];?></td>
                                                                                    
                                                                        <?php           $row_count++;
                                                                        
                                                                                        if($row_count==2) { 
                                                                                            echo '</tr>';
                                                                                            $row_count = 0;
                                                                                        } ?>
                                                                        <?php }
                                                                        } 
                                                                } ?>
                                                    </div>
                                                    <div class="add-new-promotions" style="text-align: right;">
                                                        <a href="<?=base_url()?>manage_admin/security_settings" class="site-btn">Back to Permissions</a>
                                                        <input type="hidden" name="action" value="true">
                                                        <!--<input type="hidden" name="access_level" value="<?php echo $access_level; ?>">-->
                                                        <?php echo form_submit('submit', 'Save Permissions', 'class="site-btn"');?>
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
</div>