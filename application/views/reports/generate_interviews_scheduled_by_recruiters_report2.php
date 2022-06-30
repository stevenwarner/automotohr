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
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="applicant-reg-date">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <form id="form-filters" method="post" enctype="multipart/form-data" action="">
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="form-col-100">
                                                                <label for="startdate">Start Date</label>
                                                                <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="Start Date" readonly="" value="<?php echo set_value('startdate', date('m-d-Y', strtotime($startdate))); ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="form-col-100">
                                                                <label for="enddate">End Date</label>
                                                                <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="End Date" readonly="" value="<?php echo set_value('enddate', date('m-d-Y', strtotime($enddate))); ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <div class="report-btns">
                                                                <div class="row">
                                                                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                                                        &nbsp;
                                                                    </div>
                                                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                                        <button class="form-btn" id="btn_apply_filters" onclick="fApplyDateFilters();">Filter</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div id="col_chart" class=""></div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <?php if (isset($events) && sizeof($events) > 0) { ?>
                                            <div class="col-xs-12">
                                                <div class="box-view reports-filtering">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <form method="post" id="export" name="export">
                                                                    <input type="submit" name="submit" class="submit-btn pull-right" value="Export CSV" />
                                                                </form>
                                                                <a href="javascript:;" class="submit-btn pull-right" onclick="print_page('#print_div');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php       } ?>
                                        <div class="col-xs-12" id="print_div">
                                            <?php foreach ($events as $employer_id => $employee_events) { ?>
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <div class="month-name">
                                                            <?php echo ucwords($employer_name[$employer_id]); ?>
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
                                                                        <?php foreach ($employee_events as $event) {  ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <?php $user_sid = $event['applicant_job_sid'];

                                                                                    if ($event['users_type'] == 'employee') {
                                                                                        echo ucwords($employer_name[$user_sid]);
                                                                                    } else {
                                                                                        echo ucwords($applicant_names[$user_sid]);
                                                                                    } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if ($have_status == true) {
                                                                                        if (empty($event['applicant_jobs_list'])) {
                                                                                            if (!empty($event['applicant_job_sid'])) {
                                                                                                $status_sid = $event['applicant_job_sid'];
                                                                                                $status_info = get_interview_status_by_parent_id($status_sid);
                                                                                            } else {
                                                                                                $status_info = array();
                                                                                                $status_info['name'] = 'Status Not Found';
                                                                                                $status_info['css_class'] = 'status_not_found';
                                                                                            }
                                                                                        } else {
                                                                                            $status_array = explode(',', $event['applicant_jobs_list']);
                                                                                            $status_sid = $status_array[0];
                                                                                            $status_info = get_interview_status($status_sid);
                                                                                        }

                                                                                        if (empty($status_info)) {
                                                                                            $status_info = array();
                                                                                            $status_info['name'] = 'Status Not Found';
                                                                                            $status_info['css_class'] = 'status_not_found';
                                                                                            $status_info['bar_bgcolor'] = NULL;
                                                                                        }

                                                                                        $bgcolor = '';

                                                                                        if ($status_info['bar_bgcolor'] != NULL) {
                                                                                            $bar_bgcolor = $status_info['bar_bgcolor'];
                                                                                            $bgcolor = "background-color: $bar_bgcolor";
                                                                                        } ?>

                                                                                        <div class="contacts_label contacts_label_padding_bottom <?php echo $status_info['css_class'] ?>" style="<?php echo $bgcolor; ?>">
                                                                                            <?php echo ucwords($status_info['name']); ?>
                                                                                        </div>
                                                                                        <?php   } else { // don't have custom status enabled 
                                                                                        if (empty($event['applicant_jobs_list'])) {
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
                                                                                    <?= reset_datetime(array('datetime' => $event['date'], '_this' => $this)); ?>
                                                                                </td>
                                                                            </tr>
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
    $('#startdate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();
    $('#enddate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();

    function fApplyDateFilters() {
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();
        var url = '<?php echo base_url(); ?>' + 'reports/generate_interviews_scheduled_by_recruiters' + '/' + encodeURI(startDate) + '/' + encodeURI(endDate) + '/';
        $('#form-filters').attr('action', url);
        $('#form-filters').submit();

    }

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