<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i>Company Dashboard</h1>
                    <!--                <div class="heading-title page-title">-->
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Dashboard</a>
                    <!--                </div>-->
                </div>
                <div class="bt-panel">
                    <?php if($company['complynet_status']){?>
                        <a href="<?php echo base_url().'complynet/'.$company['sid']; ?>" class="btn btn-success">ComplyNet</a>
                    <?php }?>
                    <a href="<?php echo base_url().'company_jobs/jobs/'.$company['sid']; ?>" class="btn btn-success">View Jobs</a>
                    <a href="<?php echo base_url().'company_jobs/job_applicants/'.$company['sid']; ?>" class="btn btn-success">View Job Applicants</a>
                    <a href="<?php echo $career_website; ?>" class="btn btn-success" target="_blank">View Career Site</a>
                </div>
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <h1 class="hr-registered pull-left">Company Details </h1>
                        <!--<div class="pull-right"><a href="javascript:;" class="btn btn-default btn-sm pull-right">Edit</a></div>-->
                    </div>
                    <div class="table-responsive hr-innerpadding">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th class="col-lg-2">Company Name</th>
                                <th class="col-lg-3">Address</th>
                                <th class="col-lg-2">Joining Date</th>
                                <th class="col-lg-2">Phone Number</th>
                                <th class="col-lg-3">Website</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $company['CompanyName']; ?></td>
                                    <td>
                                        <?php echo isset($company['Location_Address']) ? $company['Location_Address'] . ', ' : ''; ?>
                                        <?php echo isset($company['Location_City']) ? $company['Location_City'] . ', ' : ''; ?>
                                        <?php echo isset($location_info['state_name']) ? $location_info['state_name'] . ', ' : ''; ?>
                                        <?php echo isset($location_info['country_name']) ? $location_info['country_name'] : ''; ?>
                                    </td>
<!--                                    <td>--><?php //echo my_date_format($company['registration_date']); ?><!--</td>-->
                                    <td><?php echo reset_datetime(array(
                                            'datetime' => $company['registration_date'],
                                            // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                            // 'format' => 'h:iA', //
                                            'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                            'from_timezone' => $executive_user['timezone'], //
                                            '_this' => $this
                                        )) ?></td>
                                    <td><?=phonenumber_format($company['PhoneNumber']);?></td>
                                    <td><?php echo $company['WebSite']; ?></td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>	
                <!-- ************************* -->
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <h1 class="hr-registered pull-left">Company Employee ( <?php echo sizeof($employees); ?> )</h1>
                    </div>                                     
                    <div class="table-responsive hr-innerpadding">
                        <form method="POST" id="multiple_actions_employer" name="multiple_actions">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Contact Name</th>
                                        <th>Email</th>
                                        <th class="text-center">Registration Date</th>
                                        <th class="text-center">Status</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($employees) > 0) {
                                        foreach ($employees as $employee) {
                                            ?>
                                            <tr>
                                                <td><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></td>
                                                <!--<td><?php echo $employee['username']; ?></td>-->
                                                <td><?php echo $employee['email']; ?></td>
<!--                                                <td class="text-center">--><?php //echo my_date_format($employee['registration_date']); ?><!--</td>-->
                                                <td><?php echo reset_datetime(array(
                                                        'datetime' => $employee['registration_date'],
                                                        // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                        // 'format' => 'h:iA', //
                                                        'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                        'from_timezone' => $executive_user['timezone'], //
                                                        '_this' => $this
                                                    )) ?></td>
                                                <td class="text-center <?php echo ($employee['active'] == '1') ? 'Active' : 'Inactive'; ?>"><label><?php echo ($employee['active'] == '1') ? 'Active' : 'Inactive'; ?></label></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <div class="no-data">No employee found.</div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <input type="hidden" value="multiple_action" name="execute">
                            <input type="hidden" value="employer" name="type" id="type">
                        </form>
                    </div>
                </div>
                <!-- ************************* -->
                <!-- Company Admin Invoices -->
                <!-- ************************************************************************************************************ -->
                <div class="table-title-heading">
                    <h1 class="page-title">Company Admin Invoices</h1>
                </div>		
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <h1 class="hr-registered pull-left">Invoice Summary</h1>
                    </div>
                    <div class="table-responsive hr-innerpadding">
                        <div class="scrollable-area">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th class="text-center">Value</th>
                                        <th class="text-center">Discount</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (sizeof($admin_invoices) > 0) { ?>
                                        <?php foreach ($admin_invoices as $admin_invoice) { ?>
                                            <tr>
                                                <td class="col-lg-6">
                                                    <div class="invoice-date">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <!--                                                    <div class="dotted-border">
                                                                                                                        <div class="row">
                                                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Company</strong></div>
                                                                                                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo $admin_invoice['company_name']; ?></div>
                                                                                                                        </div>  
                                                                                                                    </div>-->
                                                                <div class="dotted-border">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Date</strong></div>
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
<!--                                                                            --><?php //echo my_date_format($admin_invoice['created']); ?>
                                                                            <?php echo reset_datetime(array(
                                                                                'datetime' => $admin_invoice['created'],
                                                                                // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                                                // 'format' => 'h:iA', //
                                                                                'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                                                'from_timezone' => $executive_user['timezone'], //
                                                                                '_this' => $this
                                                                            )) ?>
                                                                        </div>
                                                                    </div>  
                                                                </div>
                                                                <div class="dotted-border">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Invoice #</strong></div>
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo $admin_invoice['invoice_number']; ?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="dotted-border">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Payment Status</strong></div>
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 <?php echo ucwords($admin_invoice['payment_status']); ?>"><?php echo ucwords($admin_invoice['payment_status']); ?></div>
                                                                    </div>
                                                                </div>       
                                                            </div>
                                                        </div>
                                                        <h5><strong>Item Summary</strong></h5>
                                                        <ul class="item-name-summary">
                                                            <?php if (sizeof($admin_invoice['item_names']) > 0) { ?>
                                                                <?php foreach ($admin_invoice['item_names'] as $item) { ?>
                                                                    <li><?php echo $item['item_name']; ?></li>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <li>No items found.</li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <!-- Start Invoice Summary -->
                                                <td class="text-right col-xs-2"><?php echo  '$' . number_format($admin_invoice['value'], 2, '.', ','); ?></td>
                                                <td class="text-right col-xs-2"><?php echo  '$' . number_format($admin_invoice['discount_amount'], 2, '.', ','); ?></td>
                                                <td class="text-right col-xs-2">
                                                    <?php if ($admin_invoice['is_discounted'] == 1) { ?>
                                                    <?php echo  '$' . number_format($admin_invoice['total_after_discount'], 2, '.', ','); ?>
                                                    <?php } else { ?>
                                                    <?php echo  '$' . number_format($admin_invoice['value'], 2, '.', ','); ?>
                                                    <?php } ?>
                                                </td>
                                                <!-- End Invoice Summary -->

                                                    <!--                                    <td>
                                                                                            <a class="hr-edit-btn invoice-links" href="javascript:;">View Invoice</a>
                                                                                        </td>-->
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <div class="no-data">No invoices found.</div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- ************************************************************************************************************ -->
                <!-- Company Marketplace Invoices -->
                <!-- ************************************************************************************************************ -->
                <div class="table-title-heading">
                    <h1 class="page-title">Company Marketplace Invoices</h1>
                </div>
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <h1 class="hr-registered pull-left">Invoice Summary</h1>
                    </div>
                    <div class="table-responsive hr-innerpadding">
                        <div class="scrollable-area">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="1"></th>
                                        <th class="text-center">Value</th>
                                        <th class="text-center">Discount</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (sizeof($marketplace_invoices) > 0) { ?>
                                        <?php foreach ($marketplace_invoices as $marketplace_invoice) { ?>
                                            <tr>
                                                <td class="col-lg-6">
                                                    <div class="invoice-date">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <!--<div class="dotted-border">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Company</strong></div>
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">Packaging</div>
                                                                    </div>  
                                                                </div>-->
                                                                <div class="dotted-border">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Date</strong></div>
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
<!--                                                                            --><?php //echo my_date_format($marketplace_invoice['date']); ?>
                                                                            <?php echo reset_datetime(array(
                                                                                'datetime' => $marketplace_invoice['date'],
                                                                                // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                                                // 'format' => 'h:iA', //
                                                                                'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                                                'from_timezone' => $executive_user['timezone'], //
                                                                                '_this' => $this
                                                                            )) ?>
                                                                        </div>
                                                                    </div>  
                                                                </div>
                                                                <div class="dotted-border">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Invoice #</strong></div>
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><?php echo $marketplace_invoice['sid']; ?></div>
                                                                    </div>
                                                                </div>
                                                                <div class="dotted-border">
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6"><strong>Payment Status</strong></div>
                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 <?php echo ucwords($marketplace_invoice['status']); ?>"><?php echo ucwords($marketplace_invoice['status']); ?></div>
                                                                    </div>
                                                                </div>       
                                                            </div>
                                                        </div>
                                                        <h5><strong>Item Summary</strong></h5>
                                                        <ul class="item-name-summary">
                                                            <?php if (sizeof($marketplace_invoice['item_names']) > 0) { ?>
                                                                <?php foreach ($marketplace_invoice['item_names'] as $item) { ?>
                                                                    <li><?php echo $item['name']; ?></li>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <li>No items found.</li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <!-- Start Invoice Summary -->
                                                <td class="text-right col-xs-2"><?php echo '$' . number_format($marketplace_invoice['sub_total'], 2, '.', ','); ?></td>
                                                <td class="text-right col-xs-2"><?php echo '$' . number_format($marketplace_invoice['total_discount'], 2, '.', ','); ?></td>
                                                <td class="text-right col-xs-2"><?php echo '$' . number_format($marketplace_invoice['total'], 2, '.', ','); ?></td>
                                                <!-- End Invoice Summary -->
                                                <!--<td>
                                                    <a class="hr-edit-btn invoice-links" href="javascript:;">View Invoice</a>
                                                </td>-->
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <div class="no-data">No invoices found.</div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- ************************************************************************************************************ -->					
            </div>
        </div>
    </div>
</div>	