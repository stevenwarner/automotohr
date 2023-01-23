<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="page-header-area">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('attendance'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Back
                        </a>
                        <?php echo $title; ?>
                        <span class="beta-label">beta</span>
                    </span>
                </div>
                <div class="dashboard-conetnt-wrp">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="panel-group-wrp">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                <h4 class="panel-title">
                                                    Advanced Search Filters <span class="glyphicon glyphicon-plus"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="universal-form-style-v2">
                                                        <ul class="row">
                                                            <li class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                                                <label>Employee</label>
                                                                <div class="hr-select-dropdown">
                                                                <?php   $selected_profile = false;
                                                                        $temp = $default_selected;
                                                                        if($this->uri->segment(3)){
                                                                            $temp = $this->uri->segment(3);
                                                                        } ?>
                                                                    <select class="invoice-fields" name="employee_sid" id="employee_sid">
                                                                        <option value="<?php echo $employer_sid; ?>">Please Select</option>
                                                                        <?php  if(!empty($employees)) { ?>
                                                                            <?php foreach($employees as $employee) { ?>
                                                                                <?php $selected_profile = ($temp == $employee['sid'] ? true : false);?>
                                                                                <option <?php echo set_select('employee_sid', $employee['sid'], $selected_profile); ?> value="<?php echo $employee['sid']; ?>"><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></option>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                            <li class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                                                <label>Week</label>
                                                                <div class="hr-select-dropdown">
                                                                    <?php $temp = $this->uri->segment(5);?>
                                                                    <select class="invoice-fields" name="week_number" id="week_number">
                                                                        <option value="<?php echo date('W'); ?>">Please Select</option>
                                                                        <?php  if(!empty($weeks)) { ?>
                                                                            <?php foreach($weeks as $key => $week) { ?>
                                                                                <?php $default_selected = $temp == $key ? true : false;?>
                                                                                <option <?php echo set_select('week_number', $key, $default_selected); ?> value="<?php echo $key; ?>"><?php echo $week; ?></option>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </li>

                                                            <li class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                                <label>&nbsp;</label>
                                                                <a id="my_search_btn" href="" class="submit-btn btn-equalizer">Search</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <div class="hr-registered pull-left">
                                        <span class=""><?php echo $subtitle; ?></span>
                                    </div>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <?php if(!empty($employee_info)) { ?>
                                        <div class="row">
                                            <div class="col-xs-9">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover">
                                                        <tbody>
                                                        <tr>
                                                            <th class="col-xs-3">Name</th>
                                                            <td class="col-xs-6"><?php echo $employee_info['first_name'] . ' ' . $employee_info['last_name']; ?></td>
                                                        </tr>
                                                        <?php if(!empty($employee_info['position_info'])) { ?>
                                                            <tr>
                                                                <th class="col-xs-3">Position</th>
                                                                <td class="col-xs-6"><?php echo $employee_info['position_info']['position_name']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3">Department</th>
                                                                <td class="col-xs-6"><?php echo $employee_info['position_info']['department_name']; ?></td>
                                                            </tr>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <th class="col-xs-3">Position</th>
                                                                <td class="col-xs-6"><?php echo $employee_info['job_title']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="col-xs-3">Department</th>
                                                                <td class="col-xs-6">Not Assigned</td>
                                                            </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <th class="col-xs-3">Employee Since</th>
                                                            <td class="col-xs-6"><?php echo convert_date_to_frontend_format($employee_info['registration_date']); ?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="img-thumbnail pull-right">
                                                    <?php if(!empty($employee_info['profile_picture'])) { ?>
                                                        <img src="<?php echo AWS_S3_BUCKET_URL . $employee_info['profile_picture']; ?>" class="img-responsive img-rounded" />
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url('assets/images/default_pic.jpg'); ?>" class="img-responsive img-rounded" />
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                    <?php } ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Regular Time<br/>(HH:MM)</th>
                                                <th class="text-center">Break Time<br/>(HH:MM)</th>
                                                <th class="text-center">Overtime<br/>(HH:MM)</th>
                                                <th class="text-center">Total Time<br/>(HH:MM)</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(!empty($attendance_records)) { ?>
                                                <?php foreach($attendance_records as $key => $attendance_record) { ?>
                                                        <?php if(!empty($attendance_record)) { ?>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <?php echo date('m/d/Y', strtotime($attendance_record['attendance_date'])); ?>
                                                                </td>
<!--                                                                <td class="text-center">
                                                                    <?php //echo str_pad($attendance_record['daily_time_quota_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad(0, 2, 0, STR_PAD_LEFT); ?>
                                                                </td>-->
                                                                <td class="text-center">
                                                                    <?php echo str_pad($attendance_record['regular_clocked_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad($attendance_record['regular_clocked_minutes'], 2, 0, STR_PAD_LEFT); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo str_pad($attendance_record['total_break_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad($attendance_record['total_break_minutes'], 2, 0, STR_PAD_LEFT); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo str_pad($attendance_record['total_over_quota_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad($attendance_record['total_over_quota_minutes'], 2, 0, STR_PAD_LEFT); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo str_pad($attendance_record['total_after_break_hours'], 2, 0, STR_PAD_LEFT) . ':' . str_pad($attendance_record['total_after_break_minutes'], 2, 0, STR_PAD_LEFT); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a class="btn btn-success btn-sm" href="<?php echo base_url('attendance/manage_attendance/' . $attendance_record['employer_sid'] . '/' . date('Y_m_d', strtotime($attendance_record['attendance_date'])))?>">Manage</a>
                                                                </td>
                                                            </tr>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <?php echo date('m/d/Y', strtotime($key)); ?>
                                                                </td>
                                                                <td colspan="4">
                                                                    <span class="">No Time Logged</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a class="btn btn-success btn-sm" href="<?php echo base_url('attendance/manage_attendance/' . $employer_sid . '/' . date('Y_m_d', strtotime($key)))?>">Manage</a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">
                                                        <span class="no-data">No Logs Found</span>
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
<script>
    $(document).ready(function () {
        $('#employee_sid, #week_number').on('change', function () {
            var employee_sid = $('#employee_sid').val();
            var week_number = $('#week_number').val();
            var year = <?php echo date('Y'); ?>;
            var my_url = '<?php echo base_url('attendance/weekly_attendance'); ?>/' + employee_sid + '/' + year + '/' + week_number;
            $('#my_search_btn').attr('href', my_url);
        }).trigger('change');
    });
</script>