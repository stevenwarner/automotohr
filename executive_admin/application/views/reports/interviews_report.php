<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i><?php echo $title; ?></h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard/reports/' . $company_sid); ?>">
                        <i class="fa fa-long-arrow-left"></i> 
                        Back to Reports
                    </a>
                </div>
                <?php if (isset($users_events) && sizeof($users_events) > 0) { ?>
                <div class="col-xs-12 col-sm-12 margin-top">
                    <div class="row">
                        <div class="bt-panel">
                            <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                                <i class="fa fa-print" aria-hidden="true"></i> 
                                Print
                            </a>
                            <form method="post" id="export" name="export">
                                <input type="hidden" name="submit" value="Export" />
                                <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                            </form>
                        </div>                                                               
                    </div>
                </div>
                <?php } ?>
                <!-- table -->                
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <h1 class="hr-registered pull-left"><span class="text-success">Interviews Report</span></h1>
                    </div>
                    <div class="col-xs-12 table-responsive hr-innerpadding" id="print_div">
                        <?php if (isset($users_events) && sizeof($users_events) > 0) { ?>
                            <?php foreach ($users_events as $user_event) { ?>
                                <div class="row job-per-month-row">
                                    <div class="col-lg-2 col-md-4 col-xs-12 col-sm-12">
                                        <div class="month-name">
                                            <?php echo ucwords($user_event['first_name'] . ' ' . $user_event['last_name']); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-8 col-xs-12 col-sm-12">
                                        <table class="table table-bordered table-stripped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-5">Interview Scheduled For</th>
                                                    <th class="col-xs-5">Interview Status</th>
                                                    <th class="col-xs-2 text-center">Interview Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($user_event['events'])) { ?>
                                                    <?php foreach ($user_event['events'] as $event) { ?>
                                                        <tr>
                                                            <td><?php echo ucwords($event['applicant_first_name'] . ' ' . $event['applicant_last_name']); ?></td>
                                                            <td>
                                                <?php           if($have_status == true) {
                                                                    if (empty($event['applicant_jobs_list'])) {
                                                                        if(!empty($event['applicant_job_sid'])) {
                                                                            $status_sid = $event['applicant_job_sid'];
                                                                            $status_info = get_interview_status_by_parent_id($status_sid );
                                                                        } else {
                                                                            $status_info = array();
                                                                            $status_info['name'] = 'Status Not Found';
                                                                            $status_info['css_class'] = 'status_not_found';
                                                                        }
                                                                    } else {
                                                                        $status_array = explode(',', $event['applicant_jobs_list']); 
                                                                        $status_sid = $status_array[0];
                                                                        $status_info = get_interview_status($status_sid ); 
                                                                    }

                                                                    if(empty($status_info)) { 
                                                                        $status_info = array();
                                                                        $status_info['name'] = 'Status Not Found';
                                                                        $status_info['css_class'] = 'status_not_found'; 
                                                                        $status_info['bar_bgcolor'] = NULL; 
                                                                    }  

                                                                    $bgcolor = '';

                                                                    if($status_info['bar_bgcolor'] != NULL) {
                                                                        $bar_bgcolor = $status_info['bar_bgcolor'];
                                                                        $bgcolor = "background-color: $bar_bgcolor";
                                                                    } ?>

                                                                <div class="contacts_label contacts_label_padding_bottom <?php echo $status_info['css_class']?>" style="<?php echo $bgcolor; ?>">
                                                                        <?php echo ucwords($status_info['name']); ?>
                                                                    </div> 
                                                        <?php   } else { // don't have custom status enabled 
                                                                    if(empty($event['applicant_jobs_list'])) {
                                                                        $status_sid = $event['applicant_job_sid'];
                                                                        $field_id = 'portal_job_applications_sid';
                                                                    } else {
                                                                        $status_array = explode(',', $event['applicant_jobs_list']); 
                                                                        $status_sid = $status_array[0];
                                                                        $field_id = 'sid';
                                                                    }

                                                                    $default_status = get_default_interview_status($status_sid, $field_id);

                                                                    if ($default_status == 'Contacted') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom contacted"><?= $default_status ?></div>
                                                                <?php } elseif ($default_status == 'Candidate Responded') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom responded"><?= $default_status ?></div>
                                                                <?php } elseif ($default_status == 'Qualifying') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom qualifying"><?= $default_status ?></div>
                                                                <?php } elseif ($default_status == 'Submitted') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom submitted"><?= $default_status ?></div>
                                                                <?php } elseif ($default_status == 'Interviewing') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom interviewing"><?= $default_status ?></div>
                                                                <?php } elseif ($default_status == 'Offered Job') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom offered"><?= $default_status ?></div>
                                                                <?php } elseif ($default_status == 'Not In Consideration') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom notin"><?= $default_status ?></div>
                                                                <?php } elseif ($default_status == 'Client Declined') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom decline"><?= $default_status ?></div>
                                                                <?php } elseif ($default_status == 'Placed/Hired' || $default_status == 'Ready to Hire') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom placed">Ready to Hire</div>
                                                                <?php } elseif ($default_status == 'Not Contacted Yet') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom not_contacted"><?= $default_status ?></div>
                                                                <?php } elseif ($default_status == 'Future Opportunity') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom future_opportunity"><?= $default_status ?></div>
                                                                <?php } elseif ($default_status == 'Left Message') { ?>
                                                                        <div class="contacts_label contacts_label_padding_bottom left_message"><?= $default_status ?></div>
                                                                <?php } ?>
                                                        <?php } ?>
                                                            </td>
                                                            <td class="text-center">
<!--                                                                --><?php //echo date('m/d/Y', strtotime($event['date'])); ?>
                                                                <?php echo reset_datetime(array(
                                                                    'datetime' => $event['date'],
                                                                    // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                                    // 'format' => 'h:iA', //
                                                                    'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                                    'from_timezone' => $executive_user['timezone'], //
                                                                    '_this' => $this
                                                                )) ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr><td colspan="3">No Interviews Found</td></tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>   
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>                                
                            <div class="text-center" colspan="2">
                                <div class="no-data">No users found.</div>                                              
                            </div>                                        
                        <?php } ?>
                    </div>
                </div>                
                <!-- table -->
                <?php if (isset($users_events) && sizeof($users_events) > 0) { ?>
                <div class="col-xs-12 col-sm-12 margin-top">
                    <div class="row">
                        <div class="bt-panel">
                            <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                                <i class="fa fa-print" aria-hidden="true"></i> 
                                Print
                            </a>
                            <form method="post" id="export" name="export">
                                <input type="hidden" name="submit" value="Export" />
                                <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                            </form>
                        </div>                                                               
                    </div>
                </div>
                <?php } ?>
            </div>                                  
        </div>
    </div>
</div>
<script type="text/javascript">
    function print_page(elem)
    {
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo $companyName; ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
    }
</script>