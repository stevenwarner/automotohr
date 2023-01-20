<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                        <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                        <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div id="col_chart" class=""></div></div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <?php if(isset($users_events) && sizeof($users_events) > 0) { ?>
                                        <div class="col-xs-12">
                                            <div class="box-view reports-filtering">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="form-group">
                                                            <form method="post" id="export" name="export">
                                                                <input type="submit" name="submit" class="submit-btn pull-right" value="Export" />
                                                            </form>
                                                            <a href="javascript:;" class="submit-btn pull-right" onclick="print_page('#print_div');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="col-xs-12" id="print_div">
                                            <?php foreach($users_events as $user_event) {?>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <div class="month-name">
                                                            <?php echo ucwords($user_event['first_name'] . ' ' . $user_event['last_name']);?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                        <div class="table-responsive table-outer">
                                                            <div class="border-none mylistings-wrp">
                                                                <table class="table table-bordered table-stripped table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="">Interview Scheduled For</th>
                                                                            <th class="col-xs-5">Interview Status</th>
                                                                            <th class="col-xs-2">Interview Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if(!empty($user_event['events'])) { ?>
                                                                            <?php foreach($user_event['events'] as $event) { ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <?php echo ucwords($event['applicant_first_name'] . ' ' . $event['applicant_last_name']); ?>
                                                                                    </td>
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
                                                                                    <td>
                                                                                        <?php echo date_with_time($event['date']); ?>  
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else {?>
                                                                            <tr><td colspan="2">No Interviews Found</td></tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <!--<div id="pie_chart" class=""></div>-->
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
<script type="text/javascript">
    function print_page(elem) {
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');
        
        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome-animation.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/responsive.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/jquery-ui.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/images/favi-icon.png'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/select2.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close(); 
        mywindow.focus(); 
    }
</script>

