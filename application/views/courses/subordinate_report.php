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
                                            <!-- Courses Filter  -->
                                            <div class="col-xs-12  col-md-4">
                                                <?php if (!empty($subordinateInfo["courses"])) { ?>
                                                    <label><strong>Courses</strong></label>
                                                    <select id="jsSubordinateCourses" multiple style="width: 100%">
                                                        <option value="all">All</option>
                                                        <?php foreach ($subordinateInfo["courses"] as $course) { ?>
                                                            <option value="<?php echo $course['sid']; ?>"><?php echo $course['course_title']; ?></option>
                                                        <?php } ?>   
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
                            <div class="panel-heading">
                                <strong>Team Report: Visual Representation</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="panel panel-default">
                                            <div class="panel-body" style="background: #e6e7ff; border-radius: 4px;">
                                                <?php 
                                                    $total = $subordinateInfo["total_course"];
                                                    $completed = $subordinateInfo["completed"];
                                                    $unCompleted = $total - $completed;
                                                    $percentage = $total != 0 ? round(($completed/$total * 100), 0, PHP_ROUND_HALF_UP).'%' : '0%';
                                                ?>
                                                <h2 style="">Completed Courses: <span style="color: #ef6c34;" id="jsOverViewTrainings"><?=$percentage?></span></h2>
                                                <h3 style="margin-bottom: 0px;"><span id="jsOverViewCourseDueSoon"><?=$unCompleted?></span> Courses Due Soon</h3>
                                                <h3 style="margin-top: 0px;"><span id="jsOverViewCourseTotal"><?=$total?></span> Courses Total</h3>
                                            </div>
                                        </div>
                                    </div>    

                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div id="container1"></div>
                                            </div>    
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        </div>    

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Team Report</strong>
                            </div>
                            <div class="panel-body">

                                <div class="row" style="margin-bottom:10px;">
                                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                                        
                                    </div>

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
                                                                    <th>Passed Course(s)</th>
                                                                    <th>Due Soon</th>
                                                                    <th>Past Due</th>
                                                                    <th>Ready To Start</th>
                                                                    <th>Courses in Progress</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="jsSubordinateList">
                                                                <?php if (!empty($subordinateInfo["employees"])) { ?>
                                                                    <?php foreach ($subordinateInfo["employees"] as $employee) { ?>
                                                                        <?php //if ($employee['job_title_sid'] > 0) { ?>
                                                                            <?php 
                                                                                $teamId = $employee['team_sid'];
                                                                                $departmentId = $employee['department_sid'];
                                                                                $assignCourses = !empty($employee['assign_courses']) ? explode(",", $employee['assign_courses']) : [];
                                                                                $courseCount = !empty($assignCourses) ? count($assignCourses) : 0;
                                                                                $courseCountText = $courseCount > 1 ? $courseCount." courses assigned" : $courseCount." course assigned";
                                                                                $departmentName =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["department_name"] : "N/A";
                                                                                $teamName =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["name"] : "N/A";
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
                                                                                <td class="_csVm"><?php echo $departmentName; ?></td>
                                                                                <td class="_csVm"><?php echo $teamName; ?></td>
                                                                                <td class="_csVm"><?php echo isset($employee['coursesInfo']) ? $employee['coursesInfo']['total_course'] : 0; ?></td></td>
                                                                                <td class="_csVm"><?php echo isset($employee['coursesInfo']) ? $employee['coursesInfo']['completed'] : 0; ?></td>
                                                                                <td class="_csVm"><?php echo isset($employee['coursesInfo']) ? $employee['coursesInfo']['expire_soon'] : 0; ?></td>
                                                                                <td class="_csVm"><?php echo isset($employee['coursesInfo']) ? $employee['coursesInfo']['expired'] : 0; ?></td>
                                                                                <td class="_csVm"><?php echo isset($employee['coursesInfo']) ? $employee['coursesInfo']['ready_to_start'] : 0; ?></td>
                                                                                <td class="_csVm"><?php echo isset($employee['coursesInfo']) ? $employee['coursesInfo']['started'] : 0; ?></td>
                                                                                <td class="_csVm">
                                                                                    <!-- <a href="<?php echo base_url('lms/subordinate/courses/'.$employee['employee_sid']); ?>" class="btn btn-info btn-block csRadius5 csF16">
                                                                                        <i class="fa fa-eye"></i> View
                                                                                    </a> -->
                                                                                    <a href="<?php echo base_url('lms/subordinate/dashboard/'.$employee['employee_sid']); ?>" class="btn btn-info btn-block csRadius5 csF16">
                                                                                        <i class="fa fa-eye"></i> View
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php //} ?>    
                                                                    <?php } ?>
                                                                <?php } else { ?> 
                                                                    <tr>
                                                                    <td colspan="5">
                                                                        <p class="alert alert-info text-center">
                                                                            No employee(s) found.
                                                                        </p>
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
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    var uniqueKey = "<?php echo $uniqueKey; ?>";
    var haveSubordinate = "<?php echo $haveSubordinate; ?>";
    var employerId = <?php echo $employer_sid; ?>;
    var departments = "<?php echo $filters['departments']; ?>";
    var teams = "<?php echo $filters['teams']; ?>";
    var employees = "<?php echo $filters['employees']; ?>";
    var courses = "<?php echo $filters['courses']; ?>";
    var baseURL = "<?= base_url(); ?>";
    var totalCourses = <?=$subordinateInfo["total_course"]?>;
    var dueSoonCourses = <?=$subordinateInfo["expire_soon"]?>;
    var pastDueCourses = <?=$subordinateInfo["expired"]?>;
    var inProgressCourses = <?=$subordinateInfo["started"]?>;
    var completedCourses = <?=$subordinateInfo["completed"]?>;
    var readyToStart = <?=$subordinateInfo["ready_to_start"]?>;
</script>