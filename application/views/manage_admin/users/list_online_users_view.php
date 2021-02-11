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
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title;?></h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <h1 class="hr-registered text-center text-info">Total Employers</h1>
                                                </div>
                                                <div class="text-center hr-innerpadding text-info">
                                                    <p></p>
                                                    <i class="fa fa-user" style="font-size: 50px;"></i>
                                                    <p></p>
                                                    <p>
                                                    <strong>
                                                        <?php echo $total_users; ?>
                                                    </strong>
                                                    </p>
                                                    <p></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <h1 class="hr-registered text-center text-info">Active Employers</h1>
                                                </div>
                                                <div class="text-center hr-innerpadding text-info">
                                                    <p></p>
                                                    <i class="fa fa-user" style="font-size: 50px;"></i>
                                                    <p></p>
                                                    <p>
                                                    <strong>
                                                        <?php echo $active_users; ?>
                                                    </strong>
                                                    </p>
                                                    <p></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <h1 class="hr-registered text-center text-success">Online Employers</h1>
                                                </div>
                                                <div class="text-center hr-innerpadding text-success">
                                                    <p></p>
                                                    <i class="fa fa-user" style="font-size: 50px;"></i>
                                                    <p></p>
                                                    <p>
                                                    <p>
                                                        <strong>
                                                            <?php echo count($online_users); ?>
                                                        </strong>

                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header">
                                                    <h1 class="hr-registered pull-left">List of all online Users</h1>
                                                    <span class="pull-right"><strong>Current Date Time :</strong> <?php echo date('m/d/Y H:i:s'); ?></span>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="table-responsive hr-innerpadding">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th class="col-xs-2">Employer</th>
                                                            <th class="col-xs-2">Company</th>
                                                            <th class="col-xs-2">IP Address</th>
                                                            <th class="col-xs-3">User Agent</th>
                                                            <th class="col-xs-1">Last Activity</th>
                                                            <th class="col-xs-2 text-center">Status</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php if(!empty($online_users)) { ?>
                                                            <?php foreach($online_users as $user) { ?>
                                                                <tr>
                                                                    <td class="text-left"><?php echo ucwords($user['employer_name']);?></td>
                                                                    <td class="text-left"><?php echo ucwords($user['company_name']);?></td>
                                                                    <td class="text-left"><?php echo $user['employer_ip'];?></td>
                                                                    <td class="text-left"><?php echo $user['user_agent'];?></td>
                                                                    <td class="text-left"><?php echo date('m/d/Y H:i:s', strtotime($user['action_timestamp']));?></td>
                                                                    <td class="text-center">
                                                                        <span style="border: thin solid #81b431; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px; padding: 3px 9px; display: inline-block; color: #81b431;"><i class="fa fa-user"></i> Online</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan="6" class="text-center">
                                                                    <span class="no-data">No Employers Online at this Time</span>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
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