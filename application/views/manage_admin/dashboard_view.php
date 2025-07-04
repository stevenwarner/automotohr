<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <?php if (in_array('full_access', $security_details) || in_array('invoices_panel', $security_details)) { ?>
                            <div class="hr-stats">									
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php if ($is_admin == true) { ?>
                                            <div class="hr-statblock">
                                                <h2>
                                                    <a href="<?php echo base_url('manage_admin/financial_reports/monthly_sales') ?>">
                                                        $<?php echo number_format($total_earning_this_month, 2, '.', ','); ?>
                                                        <p>This Month</p>
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="hr-statblock">
                                                <h2>
                                                    <a href="<?php echo base_url('manage_admin/financial_reports/yearly_sales') ?>">
                                                        $<?php echo number_format($total_earning_this_year, 2, '.', ','); ?>
                                                        <p>This Year</p>
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="hr-statblock">
                                                <h2>
                                                    <a href="<?php echo base_url('manage_admin/financial_reports/yearly_sales_comparison') ?>">
                                                        $<?php echo number_format($total_earning_overall, 2, '.', ','); ?>
                                                        <p>Total Earnings</p>
                                                    </a>
                                                </h2>

                                            </div>
                                        <?php } ?>
                                        <div class="hr-statblock">
                                            <h2>
                                                <a href="javascript:void(0);">
                                                    <?php echo $active_number_of_rooftops; ?>
                                                    <p>Active Rooftops</p>
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="hr-statblock">
                                            <h2>
                                                <a href="javascript:void(0);">
                                                    <?php echo $total_active_employers; ?>
                                                    <p>Employers</p>
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="hr-statblock">
                                            <h2>
                                                <a href="javascript:void(0);">
                                                    <?php echo $total_job_listings; ?>
                                                    <p>Active Jobs</p>
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="hr-statblock">
                                            <h2>
                                                <a href="javascript:void(0);">
                                                    <?php echo $total_job_applications; ?>
                                                    <p>Job Applications</p>
                                                </a>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <?php if (in_array('full_access', $security_details) || in_array('list_companies', $security_details)) { ?>
                                    <div class="col-lg-4 col-md-6 col-xs-12 col-sm-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <h1 class="hr-registered">Registered Companies: <?php echo $total_companies; ?></h1>
                                            </div>
                                            <div class="table-responsive hr-innerpadding">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th align="center">Companies</th>
                                                            <th align="center">Active</th>
                                                            <th align="center">In-active</th>
                                                            <th align="center">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Today</td>
                                                            <td align="center"><?php echo $today_active_companies; ?></td>
                                                            <td align="center"><?php echo $today_not_active_companies; ?></td>
                                                            <td align="center"><?php echo $today_active_companies + $today_not_active_companies; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>This Week</td>
                                                            <td align="center"><?php echo $week_active_companies; ?></td>
                                                            <td align="center"><?php echo $week_not_active_companies; ?></td>
                                                            <td align="center"><?php echo $week_active_companies + $week_not_active_companies; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>This Month</td>
                                                            <td align="center"><?php echo $month_active_companies; ?></td>
                                                            <td align="center"><?php echo $month_not_active_companies; ?></td>
                                                            <td align="center"><?php echo $month_active_companies + $month_not_active_companies; ?></td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td>This Year</td>
                                                            <td align="center"><?php echo $year_active_companies; ?></td>
                                                            <td align="center"><?php echo $year_not_active_companies; ?></td>
                                                            <td align="center"><?php echo $year_active_companies + $year_not_active_companies; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Total</strong></td>
                                                            <td align="center"><strong><b><?php echo $total_active_companies; ?></b></strong></td>
                                                            <td align="center"><strong><b><?php echo $total_not_active_companies; ?></b></strong></td>
                                                            <td align="center"><strong><b><?php echo $total_active_companies + $total_not_active_companies; ?></b></strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (in_array('full_access', $security_details) || in_array('list_employers', $security_details)) { ?>
                                    <div class="col-lg-5 col-md-6 col-xs-12 col-sm-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <h1 class="hr-registered">Registered Employers: <?php echo $total_employers; ?></h1>
                                            </div>
                                            <div class="table-responsive hr-innerpadding">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Employers</th>
                                                            <th class="text-center">Active</th>
                                                            <th class="text-center">In-active</th>
                                                            <th class="text-center">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">Today</td>
                                                            <td class="text-center"><?php echo $today_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $today_not_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $today_active_employers + $today_not_active_employers; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">This Week</td>
                                                            <td class="text-center"><?php echo $week_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $week_not_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $week_active_employers + $week_not_active_employers; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">This Month</td>
                                                            <td class="text-center"><?php echo $month_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $month_not_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $month_active_employers + $month_not_active_employers; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">This Year</td>
                                                            <td class="text-center"><?php echo $year_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $year_not_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $year_active_employers + $year_not_active_employers; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center"><strong>Total</strong></td>
                                                            <td class="text-center"><strong><b><?php echo $total_active_employers ?></b></strong></td>
                                                            <td class="text-center"><strong><b><?php echo $total_not_active_employers ?></b></strong></td>
                                                            <td class="text-center"><strong><b><?php echo $total_active_employers + $total_not_active_employers ?></b></strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?> 
                                <?php if (in_array('full_access', $security_details)) { ?>
                                <div class="col-lg-3 col-md-12 col-xs-12 col-sm-12">
                                    <div class="hr-box online-users">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered text-center text-success">Applications Details</h1>
                                        </div>
                                        <div class="text-center hr-innerpadding text-success" style="padding: 5px;">
                                            <div class="table-responsive hr-innerpadding" style="padding: 0px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-left"><a href="<?php echo base_url('manage_admin/reports/applicant_source_report_daily'); ?>"><strong>Today</strong></a></td> 
                                                            <td class="text-center"><?php echo $today_applicants; ?></td>                                                           
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>This Week</strong></td>
                                                            <td class="text-center"><?php echo $this_week_applicants; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>This Month</strong></td>
                                                            <td class="text-center"><?php echo $this_month_applicants; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>This Year</strong></td>
                                                            <td class="text-center"><?php echo $this_year_applicants; ?></td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td class="text-left"><strong>All Applications</strong></td>
                                                            <td class="text-center"><?php echo $total_job_applications; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?> 
                            </div>
                            <div class="row">
                                <?php if ($is_admin == true) { //Handle Security Check here ?>
                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <h1 class="hr-registered">Paid Invoices Summary</h1>
                                            </div>
                                            <div class="table-responsive hr-innerpadding">
                                                <table class="table table-striped table-hover table-bordered" >
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Invoices</th>
                                                            <th class="text-center">Admin</th>
                                                            <th class="text-center">Marketplace</th>
                                                            <th class="text-center">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">Today</td>
                                                            <td class="text-center">$ <?php echo number_format($paid_admin_invoices_today, 2); ?></td>
                                                            <td class="text-center">$ <?php echo number_format($paid_marketplace_invoices_today, 2); ?></td>
                                                            <td class="text-center">$ <?php echo number_format($paid_admin_invoices_today + $paid_marketplace_invoices_today, 2); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">This Week</td>
                                                            <td class="text-center">$ <?php echo number_format($paid_admin_invoices_this_week, 2); ?></td>
                                                            <td class="text-center">$ <?php echo number_format($paid_marketplace_invoices_this_week, 2); ?></td>
                                                            <td class="text-center">$ <?php echo number_format($paid_admin_invoices_this_week + $paid_marketplace_invoices_this_week, 2); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">
                                                                <a href="<?php echo base_url('manage_admin/financial_reports/monthly_sales'); ?>">This Month</a>
                                                            </td>
                                                            <td class="text-center">$ <?php echo number_format($paid_admin_invoices_this_month, 2); ?></td>
                                                            <td class="text-center">$ <?php echo number_format($paid_marketplace_invoices_this_month, 2); ?></td>
                                                            <td class="text-center">$ <?php echo number_format($paid_admin_invoices_this_month + $paid_marketplace_invoices_this_month, 2); ?></td>
                                                        </tr>
    <!--                                                        <tr>-->
    <!--                                                            <td><strong>Total</strong></td>-->
    <!--                                                            <td align="center"><strong><b>$ --><?php //echo number_format($paid_admin_invoices_total_overall, 2);   ?><!--</b></strong></td>-->
    <!--                                                            <td align="center"><strong><b>$ --><?php //echo number_format($paid_marketplace_invoices_total_overall, 2);   ?><!--</b></strong></td>-->
    <!--                                                            <td align="center"><strong><b>$ --><?php //echo number_format($paid_admin_invoices_total_overall + $paid_marketplace_invoices_total_overall, 2);   ?><!--</b></strong></td>-->
                                                        <!--                                                        </tr>-->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12"></div>
                                <?php } ?>
                                <?php if (in_array('full_access', $security_details)) { ?>
                                <div class="col-lg-3 col-md-12 col-xs-12 col-sm-12">
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered text-center text-success">Rooftop Details</h1>
                                        </div>
                                        <div class="text-center hr-innerpadding text-success" style="padding: 5px;">
                                            <div class="table-responsive hr-innerpadding" style="padding: 0px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-left"><strong>Active Rooftops</strong></td>
                                                            <td class="text-center"><?php echo $active_number_of_rooftops; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>In-Active Rooftops</strong></td>
                                                            <td class="text-center"><?php echo $inactive_number_of_rooftops; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Total Rooftops</strong></td>
                                                            <td class="text-center"><?php echo $active_number_of_rooftops + $inactive_number_of_rooftops; ?></td>
                                                        </tr> 
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <?php if ($is_admin == true) { //Handle Security Check here ?>
                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                                        <div class="hr-dashboardBlocks">
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    <h1 class="hr-registered">Unpaid Invoices Summary</h1>
                                                </div>
                                                <div class="table-responsive hr-innerpadding">
                                                    <table class="table table-striped table-hover table-bordered" >
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Invoices</th>
                                                                <th class="text-center">Admin</th>
                                                                <th class="text-center">Marketplace</th>
                                                                <th class="text-center">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center">Today</td>
                                                                <td class="text-center">$<?php echo number_format($unpaid_admin_invoices_today, 2); ?></td>
                                                                <td class="text-center">$<?php echo number_format($unpaid_marketplace_invoices_today, 2); ?></td>
                                                                <td class="text-center">$<?php echo number_format($unpaid_admin_invoices_today + $unpaid_marketplace_invoices_today, 2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center">This Week</td>
                                                                <td class="text-center">$<?php echo number_format($unpaid_admin_invoices_this_week, 2); ?></td>
                                                                <td class="text-center">$<?php echo number_format($unpaid_marketplace_invoices_this_week, 2); ?></td>
                                                                <td class="text-center">$<?php echo number_format($unpaid_admin_invoices_this_week + $unpaid_marketplace_invoices_this_week, 2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center"><a href="<?php echo base_url('manage_admin/financial_reports/monthly_unpaid_invoices'); ?>">This Month</a></td>
                                                                <td class="text-center">$<?php echo number_format($unpaid_admin_invoices_this_month, 2); ?></td>
                                                                <td class="text-center">$<?php echo number_format($unpaid_marketplace_invoices_this_month, 2); ?></td>
                                                                <td class="text-center">$<?php echo number_format($unpaid_admin_invoices_this_month + $unpaid_marketplace_invoices_this_month, 2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center"><a href="<?php echo base_url('manage_admin/invoice/pending_invoices'); ?>">Total Unpaid</a></td>
                                                                <td align="center"><strong><b>$<?php echo number_format($unpaid_admin_invoices_overall, 2); ?></b></strong></td>
                                                                <td align="center"><strong><b>$<?php echo number_format($unpaid_marketplace_invoices_overall, 2); ?></b></strong></td>
                                                                <td align="center"><strong><b>$<?php echo number_format($unpaid_admin_invoices_overall + $unpaid_marketplace_invoices_overall, 2); ?></b></strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                    
                                    
                                    
                                    
                                    
                                    <div class="hr-box online-users">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered text-center text-success">Online Employers</h1>
                                        </div>
                                        <div class="text-center hr-innerpadding text-success">
                                            <p></p>
                                            <p></p>                                            
                                            <p><i class="fa fa-users" style="font-size: 50px;"></i></p>
                                            <p>
                                            <p style="font-size: 20px;"><strong><?php echo $total_online_employees_count; ?></strong></p>
                                            <p><a href="<?php echo base_url('manage_admin/users/who_is_online'); ?>" class="btn btn-success">View Details</a></p>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="row">
                                <?php if (in_array('full_access', $security_details) || in_array('list_companies', $security_details)) { ?>
                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <h1 class="hr-registered">Job Listing Summary: <?php echo $total_jobs; ?></h1>
                                            </div>
                                            <div class="text-center hr-innerpadding text-success" style="padding: 5px;">
                                                <div class="table-responsive hr-innerpadding" style="padding: 0px;">
                                                    <table class="table table-striped table-hover table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-left"><strong>Active Jobs</strong></td>
                                                                <td class="text-center"><?php echo $total_job_listings; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left"><strong>In-Active Jobs</strong></td>
                                                                <td class="text-center"><?php echo $inactive_job_listing; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left"><strong>Organic Jobs</strong></td>
                                                                <td class="text-center"><?php echo $organic_job_listing; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left"><strong>Total Jobs</strong></td>
                                                                <td class="text-center"><?php echo $total_job_listings + $inactive_job_listing; ?></td>
                                                            </tr> 
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- <?php if (in_array('full_access', $security_details) || in_array('list_employers', $security_details)) { ?>
                                    <div class="col-lg-5 col-md-6 col-xs-12 col-sm-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <h1 class="hr-registered">Registered Employers: <?php echo $total_employers; ?></h1>
                                            </div>
                                            <div class="table-responsive hr-innerpadding">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Employers</th>
                                                            <th class="text-center">Active</th>
                                                            <th class="text-center">In-active</th>
                                                            <th class="text-center">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">Today</td>
                                                            <td class="text-center"><?php echo $today_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $today_not_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $today_active_employers + $today_not_active_employers; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">This Week</td>
                                                            <td class="text-center"><?php echo $week_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $week_not_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $week_active_employers + $week_not_active_employers; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">This Month</td>
                                                            <td class="text-center"><?php echo $month_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $month_not_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $month_active_employers + $month_not_active_employers; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center">This Year</td>
                                                            <td class="text-center"><?php echo $year_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $year_not_active_employers; ?></td>
                                                            <td class="text-center"><?php echo $year_active_employers + $year_not_active_employers; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center"><strong>Total</strong></td>
                                                            <td class="text-center"><strong><b><?php echo $total_active_employers ?></b></strong></td>
                                                            <td class="text-center"><strong><b><?php echo $total_not_active_employers ?></b></strong></td>
                                                            <td class="text-center"><strong><b><?php echo $total_active_employers + $total_not_active_employers ?></b></strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>  -->
                                <!-- <?php if (in_array('full_access', $security_details)) { ?>
                                <div class="col-lg-3 col-md-12 col-xs-12 col-sm-12">
                                    <div class="hr-box online-users">
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered text-center text-success">Applications Details</h1>
                                        </div>
                                        <div class="text-center hr-innerpadding text-success" style="padding: 5px;">
                                            <div class="table-responsive hr-innerpadding" style="padding: 0px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-left"><a href="<?php echo base_url('manage_admin/reports/applicant_source_report_daily'); ?>"><strong>Today</strong></a></td> 
                                                            <td class="text-center"><?php echo $today_applicants; ?></td>                                                           
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>This Week</strong></td>
                                                            <td class="text-center"><?php echo $this_week_applicants; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>This Month</strong></td>
                                                            <td class="text-center"><?php echo $this_month_applicants; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>This Year</strong></td>
                                                            <td class="text-center"><?php echo $this_year_applicants; ?></td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td class="text-left"><strong>All Applications</strong></td>
                                                            <td class="text-center"><?php echo $total_job_applications; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>  -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>