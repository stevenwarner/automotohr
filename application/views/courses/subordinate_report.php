<div class="main jsmaincontent">
    <div class="container-fluid">
        <div style="position: relative; min-height: 500px;">
        
            <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>

            <div class="row">
                <div class="col-lg-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    </div>
                </div>
         
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="page-header">
                        <h1 class="section-ttile">
                            <?php echo $title; ?>
                            <div style="float: right;">
                                <a href="<?php echo base_url('lms/courses/my_lms_dashboard'); ?>" class="btn btn-black csRadius5"><i class="fa fa-arrow-left"></i> Back to my Courses</a>
                            </div>
                        </h1>
                    </div>

                    <?php if ($haveSubordinate == 'yes') { ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section-inner">
                                    <div class="heading-sec">
                                        <div class="row">
                                            <!-- Department Filter  -->
                                            <?php if (!empty($subordinateInfo['departments'])) { ?>
                                                <div class="col-xs-12 col-md-4">
                                                    <label><strong>Department(s)</strong></label>
                                                    <select id="jsSubordinateDepartments" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php foreach ($subordinateInfo['departments'] as $departments) { ?>
                                                            <option value="<?php echo $departments["sid"]; ?>"><?php echo $departments["name"]; ?></option>
                                                        <?php } ?> 
                                                    </select>
                                                </div>
                                            <?php } ?> 
                                            <!-- Team Filter -->
                                            <div class="col-xs-12 <?php echo empty($subordinateInfo['departments']) ? "col-md-6" : "col-md-4"; ?>">
                                                <?php if (!empty($subordinateInfo['teams'])) { ?>
                                                    <label><strong>Team(s)</strong></label>
                                                    <select id="jsSubordinateTeams" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php foreach ($subordinateInfo['teams'] as $teams) { ?>
                                                            <option value="<?php echo $teams["sid"]; ?>"><?php echo $teams["name"]; ?></option>
                                                        <?php } ?> 
                                                    </select>
                                                <?php } ?> 
                                            </div>
                                            <!-- Employee Filter  -->
                                            <div class="col-xs-12 <?php echo empty($subordinateInfo['departments']) ? "col-md-6" : "col-md-4"; ?>">
                                                <?php if (!empty($subordinateInfo["employees"])) { ?>
                                                    <label><strong>Employee</strong></label>
                                                    <select id="jsSubordinateEmployees" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php foreach ($subordinateInfo["employees"] as $employee) { ?>
                                                            <? //if ($employee['job_title_sid'] > 0) { ?>
                                                                <option value="<?php echo $employee['employee_sid']; ?>"><?php echo $employee['full_name']; ?></option>
                                                            <?php } ?> 
                                                        <?php //} ?>     
                                                    </select>
                                                <?php } ?>     
                                            </div>
                                        </div>    
                                        <div class="row">    
                                            <div class="col-xs-12 col-md-3" style="float: right;">
                                                <label><strong>&nbsp;</strong></label>
                                                <div class="row">
                                                    <!-- filters buttons -->
                                                    <div class="col-lg-6">
                                                        <a href="javascript:;" class="btn btn-info btn-block csRadius5 jsSearchEmployees csF16">
                                                            <i class="fa fa-filter" aria-hidden="true"></i> Search
                                                        </a>
                                                    </div>    
                                                    <div class="col-lg-6">
                                                        <a href="<?php echo base_url('lms/courses/report'); ?>" class="btn btn-black btn-block csRadius5 csF16">
                                                            <i class="fa fa-times" aria-hidden="true"></i> Clear
                                                        </a>
                                                    </div> 
                                                </div>    
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 20px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h1 class="panel-heading-text text-medium">
                                        <strong>Course(s) Statuses</strong>
                                    </h1>
                                </div>
                                <div class="panel-body">
                                    <div id="courses_count_chart"></div>
                                </div>
                            </div>
                            <div class="panel-heading">
                                <strong>Team Members Report</strong>
                            </div>
                            <div class="panel-body">

                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9"></div>

                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right">
                                        <button type="button" class="btn btn-info btn-orange csRadius5 csF16 jsSendReminderEmail">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i> Send Reminder Email                                        
                                        </button>
                                    </div>
                                </div>

                                <div class="section-inner">
                                    <div class="heading-sec">
                                        <?php  if ($haveSubordinate == "yes") { ?>
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead style="background-color: #fd7a2a;">
                                                                <tr>
                                                                    <th>
                                                                        <label class="control control--checkbox">
                                                                            <input type="checkbox" class="jsCheckAll" />
                                                                            <div class="control__indicator" style="margin-top: -10px;"></div>
                                                                        </label>
                                                                    </th>
                                                                    <th>Employee Name</th>
                                                                    <th>Department</th>
                                                                    <th>Team</th>
                                                                    <th>Course Count</th>
                                                                    <th>Courses in Progress</th>
                                                                    <th>Ready To Start</th>
                                                                    <th>Past Due</th>
                                                                    <th>Due Soon</th>
                                                                    <th>Passed Courses</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="jsSubordinateList">
                                                            <?php if (!empty($subordinateInfo["employees"])) { ?>
                                                                <?php
                                                                $employee_names = [];
                                                                $statuses = [];
                                                                ?>
                                                                <?php foreach ($subordinateInfo["employees"] as $employee) {
                                                                    //
                                                                    $assignCourses = !empty($employee['assign_courses']) ? explode(",", $employee['assign_courses']) : [];
                                                                    $courseCount = !empty($assignCourses) ? count($assignCourses) : 0;
                                                                    $courseCountText = $courseCount > 1 ? $courseCount." courses assigned" : $courseCount." course assigned";
                                                                    // graph data
                                                                    $employee_names[] = $employee['full_name'];
                                                                    $statuses['in_progress'] += $employee['in_progress'];
                                                                    $statuses['ready_to_start'] += $employee['ready_to_start'];
                                                                    $statuses['past_due'] += $employee['past_due'];
                                                                    $statuses['expire_soon'] += $employee['expire_soon'];
                                                                    $statuses['passed'] += $employee['passed'];
                                                                    // labels for graph
                                                                    $formattedKeys = array_map(function($key) {
                                                                        return ucwords(str_replace('_', ' ', $key)); // Capitalize the first letter and replace underscores with spaces
                                                                    }, array_keys($statuses));
                                                                    $labels = json_encode($formattedKeys);
                                                                    // labels for graph
                                                                    $values = json_encode(array_values($statuses));
                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php if ($courseCount > 0) { ?>
                                                                                <label class="control control--checkbox">
                                                                                    <input type="checkbox" class="jsSelectSubordinate" name="employees_ids[]" value="<?php echo $employee['employee_sid']; ?>" />
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            <?php } else { ?>
                                                                                <label class="control control--checkbox">
                                                                                    <input type="checkbox" value="" disabled/>
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="_csVm js-employee-name"><b><?php echo $employee['full_name']; ?></b></td>
                                                                        <td class="_csVm"><?php echo $employee['department_name']; ?></td>
                                                                        <td class="_csVm"><?php echo $employee['team_name']; ?></td>
                                                                        <td class="_csVm"><?php echo $courseCountText; ?></td>
                                                                        <td class="_csVm"><?php echo $employee['in_progress']; ?></td>
                                                                        <td class="_csVm"><?php echo $employee['ready_to_start']; ?></td>
                                                                        <td class="_csVm"><?php echo $employee['past_due']; ?></td>
                                                                        <td class="_csVm"><?php echo $employee['expire_soon']; ?></td>
                                                                        <td class="_csVm"><?php echo $employee['passed']; ?></td>
                                                                        <td class="_csVm">
                                                                            <a href="<?php echo base_url('lms/subordinate/dashboard/'.$employee['employee_sid']); ?>" class="btn btn-info btn-block csRadius5 csF16">
                                                                                <i class="fa fa-eye"></i> View
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td colspan="11">
                                                                        <p class="alert alert-info text-center">No employee(s) found.</p>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php  } else { ?>
                                            <?php echo "No record found!"; ?>
                                        <?php  } ?> 
                                    </div>
                                </div>
        
                            </div>
                        </div>

                    <?php } else { ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="alert alert-info text-center">
                                    No subordinate employee found.
                                </p>
                            </div>
                        </div>
                    <?php } ?>     
                </div> 
            </div>
        </div>
    </div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    Highcharts.chart('courses_count_chart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Employee Course Statuses',
            align: 'left'
        },
        subtitle: {
            text:
                'Show Status Count of Courses',
            align: 'left'
        },
        xAxis: {
            categories: <?php echo $labels; ?>,
            crosshair: true,
            accessibility: {
                description: 'Employees'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Counts'
            }
        },
        tooltip: {
            valueSuffix: ' Total'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Statuses',
                data: <?php echo $values; ?>
            }
        ]
    });
</script>
<script>
    var uniqueKey = "<?php echo $uniqueKey; ?>";
    var haveSubordinate = "<?php echo $haveSubordinate; ?>";
    var employerId = <?php echo $employer_sid; ?>;
    var departments = "<?php echo $filters['departments']; ?>";
    var teams = "<?php echo $filters['teams']; ?>";
    var employees = "<?php echo $filters['employees']; ?>";
    var baseURL = "<?= base_url(); ?>";
</script>