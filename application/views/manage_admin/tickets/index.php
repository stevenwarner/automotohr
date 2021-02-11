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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="dashboard-conetnt-wrp">
                                        <div class="box-wrapper">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="dash-box">
                                                        <div class="dashboard-widget-box">
                                                            <figure><i class="fa fa-ticket"></i></figure>
                                                            <h2 class="post-title">
                                                                <a href="<?php echo base_url('manage_admin/support_tickets/lists/awaiting'); ?>">Awaiting Response Tickets</a>
                                                            </h2>
                                                            <div class="count-box">
                                                                <small>Total Tickets: <?php echo $awaiting_count; ?></small>
                                                            </div>
                                                            <div class="button-panel">
                                                                <a class="btn btn-success" href="<?php echo base_url('manage_admin/support_tickets/lists/awaiting'); ?>">View Ticket(s)</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="dash-box">
                                                        <div class="dashboard-widget-box">
                                                            <figure><i class="fa fa-ticket"></i></figure>
                                                            <h2 class="post-title">
                                                                <a href="<?php echo base_url('manage_admin/support_tickets/lists/feedback'); ?>">Feedback Required Tickets</a>
                                                            </h2>
                                                            <div class="count-box">
                                                                <small>Total Tickets: <?php echo $feedback_count; ?></small>
                                                            </div>

                                                            <div class="button-panel">
                                                                <a class="btn btn-success" href="<?php echo base_url('manage_admin/support_tickets/lists/feedback'); ?>">View Ticket(s)</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="dash-box">
                                                        <div class="dashboard-widget-box">
                                                            <figure><i class="fa fa-ticket"></i></figure>
                                                            <h2 class="post-title">
                                                                <a href="<?php echo base_url('manage_admin/support_tickets/lists/answered'); ?>">Answered Tickets</a>
                                                            </h2>
                                                            <div class="count-box">
                                                                <small>Total Tickets: <?php echo $answered_count; ?></small>
                                                            </div>

                                                            <div class="button-panel"><!-- site-btn -->
                                                                <a class="btn btn-success" href="<?php echo base_url('manage_admin/support_tickets/lists/answered'); ?>">View Ticket(s)</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="dash-box">
                                                        <div class="dashboard-widget-box">
                                                            <figure><i class="fa fa-ticket"></i></figure>
                                                            <h2 class="post-title">
                                                                <a href="<?php echo base_url('manage_admin/support_tickets/lists/awaiting'); ?>">Close Response Tickets</a>
                                                            </h2>
                                                            <div class="count-box">
                                                                <small>Total Tickets: <?php echo $close_count; ?></small>
                                                            </div>
                                                            <div class="button-panel">
                                                                <a class="btn btn-success" href="<?php echo base_url('manage_admin/support_tickets/lists/closed'); ?>">View Ticket(s)</a>
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
        </div>
    </div>
</div>