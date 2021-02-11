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
                                        <div class="col-lg-12">
                                          <a href="<?php echo site_url('manage_admin/users/add_subaccount');?>" class="btn btn-primary">Add Sub-Account</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-lg-12" style="margin-top: 10px;">
                                      <?php
                                      if(!empty($users))
                                      {
                                        echo '<table class="table table-hover table-bordered table-condensed">';
                                        echo '<tr><td>ID</td><td>Username</td></td><td>Name</td><td>Email</td><td>Last login</td><td>Operations</td></tr>';
                                        foreach($users as $user)
                                        {
                                          echo '<tr>';
                                          echo '<td>'.$user->id.'</td><td>'.$user->username.'</td><td>'.$user->first_name.' '.$user->last_name.'</td></td><td>'.$user->email.'</td><td>'.date('m-d-Y H:i:s', $user->last_login).'</td><td>';
                                          if($current_user->id != $user->id) echo anchor('manage_admin/users/edit_profile/'.$user->id,'<span class="glyphicon glyphicon-pencil"></span>').' '.anchor('manage_admin/users/delete/'.$user->id,'<span class="glyphicon glyphicon-remove"></span>');
                                          else echo '&nbsp;';
                                          echo '</td>';
                                          echo '</tr>';
                                        }
                                        echo '</table>';
                                      }
                                      ?>
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