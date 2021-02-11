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
                                        <h1 class="page-title"><i class="fa fa-group"></i>Turnover Cost Calculator Logs</h1>
                                        <a href="<?php echo base_url('manage_admin'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Dashboard</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <span class="hr-registered"><strong>Calculation Logs</strong></span>
                                                        <span class="hr-registered pull-right">Page Visits : <?php echo $page_visit_count; ?></span>
                                                    </div>
                                                    <div class="hr-box-body hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-stripped">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center col-xs-1" rowspan="2">Date</th>
                                                                    <th class="text-center col-xs-3" rowspan="2">Client</th>
                                                                    <th class="text-center col-xs-2" colspan="2">Number Of</th>
                                                                    <th class="text-center col-xs-3" colspan="2">Annual Turnover %</th>
                                                                    <th class="text-center col-xs-3" colspan="2">Annual Turnover Cost $</th>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-center col-xs-1">Employees</th>
                                                                    <th class="text-center col-xs-1">Sales Reps</th>
                                                                    <th class="text-center">Employee</th>
                                                                    <th class="text-center">Sales Rep.</th>
                                                                    <th class="text-center">Employee</th>
                                                                    <th class="text-center">Sales Rep.</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php if(!empty($calculations)) { ?>
                                                                    <?php foreach($calculations as $calculation) { ?>
                                                                        <tr>
                                                                            <td class="text-center"><?php echo convert_date_to_frontend_format($calculation['date_created']); ?></td>
                                                                            <td class="text-left">
                                                                                <ul class="list-unstyled">
                                                                                    <li>
                                                                                        <span class="text-success"><i class="fa fa-arrow-right"></i></span>&nbsp;&nbsp;<span><strong><?php echo ucwords($calculation['first_name'] . ' ' . $calculation['last_name']); ?></strong></span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <small><span class="text-success"><i class="fa fa-building"></i></span>&nbsp;&nbsp;<span><?php echo ucwords($calculation['dealership_name']); ?></span></small>
                                                                                    </li>
                                                                                    <li>
                                                                                        <small><span class="text-success"><i class="fa fa-envelope"></i></span>&nbsp;&nbsp;<span><?php echo strtolower($calculation['email']); ?></span></small>
                                                                                    </li>
                                                                                </ul>
                                                                            </td>
                                                                            <td class="text-center"><?php echo $calculation['number_of_employees']; ?></td>
                                                                            <td class="text-center"><?php echo $calculation['number_of_sales_reps']; ?></td>
                                                                            <td class="text-center"><?php echo number_format($calculation['employee_annual_turnover_percentage'], 2); ?> %</td>
                                                                            <td class="text-center"><?php echo number_format($calculation['sales_reps_annual_turnover_percentage'], 2); ?> %</td>
                                                                            <td class="text-center">$ <?php echo number_format($calculation['calculated_employee_turnover_cost'], 2); ?></td>
                                                                            <td class="text-center">$ <?php echo number_format($calculation['calculated_sales_rep_turnover_cost'], 2); ?></td>
                                                                        </tr>
                                                                    <?php } ?>
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
    </div>
</div>
