<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-6">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title;?></h1>
                                    </div>
                                    <div class="row">
                                      <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="hr-user-form">
                                          <?php echo form_open('',array('class'=>'form-horizontal'));?>
                                          <ul>
                                            <li>
                                              <?php echo form_label('Group name','group_name');?>
                                              <div class="hr-fields-wrap">
                                                  <?php
                                                    echo form_input('group_name',set_value('group_name'),'class="hr-form-fileds"');
                                                    echo form_error('group_name');
                                                  ?>
                                              </div>
                                            </li>
                                            <li>
                                              <?php echo form_label('Group description','group_description'); ?>
                                              <div class="hr-fields-wrap">
                                                  <?php
                                                    echo form_input('group_description',set_value('group_description'),'class="hr-form-fileds"');
                                                    echo form_error('group_description');
                                                  ?>
                                              </div>
                                            </li>
                                            <li>
                                               <?php echo form_submit('submit', 'Add group', 'class="site-btn"');?>
                                            </li>
                                          </ul>
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