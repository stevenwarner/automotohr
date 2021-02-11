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
                                        <div class="col-lg-4 col-lg-offset-4">
                                          <h1>Edit Profile</h1>
                                          <?php echo form_open('',array('class'=>'form-horizontal')); ?>
                                            <div class="form-group">
                                              <?php
                                              echo form_label('First name','first_name');
                                              echo form_error('first_name');
                                              echo form_input('first_name',set_value('first_name',$user->first_name),'class="form-control"');
                                              ?>
                                            </div>
                                            <div class="form-group">
                                              <?php
                                              echo form_label('Last name','last_name');
                                              echo form_error('last_name');
                                              echo form_input('last_name',set_value('last_name',$user->last_name),'class="form-control"');
                                              ?>
                                            </div>
                                            <div class="form-group">
                                              <?php
                                              echo form_label('Company','company');
                                              echo form_error('company');
                                              echo form_input('company',set_value('company',$user->company),'class="form-control"');
                                              ?>
                                            </div>
                                            <div class="form-group">
                                              <?php
                                              echo form_label('Phone','phone');
                                              echo form_error('phone');
                                              echo form_input('phone',set_value('phone',$user->phone),'class="form-control"');
                                              ?>
                                            </div>
                                            <div class="form-group">
                                              <?php
                                              echo form_label('Username','username');
                                              echo form_error('username');
                                              echo form_input('username',set_value('username',$user->username),'class="form-control" readonly');
                                              ?>
                                            </div>
                                            <div class="form-group">
                                              <?php
                                              echo form_label('Email','email');
                                              echo form_error('email');
                                              echo form_input('email',set_value('email',$user->email),'class="form-control" readonly');
                                              ?>
                                            </div>
                                            <div class="form-group">
                                              <?php
                                              echo form_label('Change password','password');
                                              echo form_error('password');
                                              echo form_password('password','','class="form-control"');
                                              ?>
                                            </div>
                                            <div class="form-group">
                                              <?php
                                              echo form_label('Confirm changed password','password_confirm');
                                              echo form_error('password_confirm');
                                              echo form_password('password_confirm','','class="form-control"');
                                              ?>
                                            </div>
                                            <?php echo form_submit('submit', 'Save profile', 'class="btn btn-primary btn-lg btn-block"');?>
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