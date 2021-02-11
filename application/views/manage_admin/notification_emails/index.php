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
                                        <h1 class="page-title"><i class="fa fa-envelope"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies/manage_company') . '/' . $company_sid; ?>"><i class="fa fa-long-arrow-left"></i> Back to Manage Company Dashboard</a>
                                    </div>
                                    <div class="dashboard-conetnt-wrp">
                                        <div class="add-new-company">
                                            <div class="box-wrapper">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="dash-box">
                                                            <div class="dashboard-widget-box">
                                                                <figure><i class="fa fa-envelope"></i></figure>
                                                                <h2 class="post-title">
                                                                    <a href="<?php echo base_url('manage_admin/notification_emails/billing_invoice_notifications') . '/' . $company_sid; ?>">Billing and Invoice Notifications</a>
                                                                </h2>
                                                                <div class="count-box">
                                                                    <small>View Billing and Invoice Notifications</small>
                                                                </div>
                                                                <div class="button-panel">
                                                                    <a class="site-btn" href="<?php echo base_url('manage_admin/notification_emails/billing_invoice_notifications') . '/' . $company_sid; ?>">View</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="dash-box">
                                                            <div class="dashboard-widget-box">
                                                                <figure><i class="fa fa-envelope"></i></figure>
                                                                <h2 class="post-title">
                                                                    <a href="<?php echo base_url('manage_admin/notification_emails/new_applicant_notifications') . '/' . $company_sid; ?>">New Applicant Notifications</a>
                                                                </h2>
                                                                <div class="count-box">
                                                                    <small>View New Applicant Notifications</small>
                                                                </div>

                                                                <div class="button-panel">
                                                                    <a class="site-btn" href="<?php echo base_url('manage_admin/notification_emails/new_applicant_notifications') . '/' . $company_sid; ?>">View</a>
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
</div>