<!-- <div class="main jsmaincontent">
    <div class="container"> -->
<div class="main csPageWrap">
    <div class="container-fluid">
        <div style="position: relative">
            <?php //$this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>
            <?php $this->load->view('courses/partials/my_course_filter'); ?>
            <!--  -->
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                </div>
            </div>
            <!--  -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
                        <h1 class="section-ttile">
                            <?php echo $page_title; ?>
                            <div style="float: right;">
                                <?php if ($page == "my_courses_history") { ?>
                                    <a href="<?php echo base_url('lms/courses/my_lms_dashboard'); ?>" class="btn btn-info btn-orange csRadius5 csF16"><i class="fa fa-laptop"></i> Active Courses</a>
                                <?php } else if ($page == "employee_courses_history") { ?>   
                                    <?php if ($reviewAs == 'non_plus') { ?>
                                        <a href="<?php echo base_url('lms/subordinate/dashboard/'.$subordinateId); ?>" class="btn btn-info btn-orange csRadius5 csF16"><i class="fa fa-laptop"></i> Active Courses</a>
                                    <?php } else if ($reviewAs == 'plus') { ?>
                                        <a href="<?php echo base_url('lms/employee/courses/dashboard/'.$subordinateId); ?>" class="btn btn-info btn-orange csRadius5 csF16"><i class="fa fa-laptop"></i> Active Courses</a>
                                    <?php } ?>        
                                <?php } ?>
                                <?php if ($haveSubordinate == "yes") { ?>
                                        <a href="<?php echo base_url('lms/courses/report'); ?>" class="btn btn-info btn-orange csRadius5 csF16"><i class="fa fa-pie-chart"></i> Team Report</a>
                                <?php } ?>
                            </div>
                        </h1>
                    </div>
                </div>
            </div>
            <!--  -->
            <!--  -->
            <div class="col-sm-3"> 
                <?php if ($page == "employee_courses_history") { ?> 
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-heading-text text-medium">
                                <strong><?php echo $type == "non_plus" ? "Subordinate" : "Employee"; ?> Info</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <img style="width: 90px; height: 90px; border-radius: 50% !important;" src="<?= getImageURL($subordinateInfo["profile_picture"]); ?>" alt="" />
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-small weight-6 myb-0" style="font-size: 14px;">
                                        <?= remakeEmployeeName($subordinateInfo, true, true); ?>
                                    </p>
                                    <p class="text-small">
                                        <?= remakeEmployeeName($subordinateInfo, false); ?>
                                    </p>
                                    <p class="text-small">
                                        <?= $subordinateInfo['email']; ?>
                                    </p>
                                </div>
                            </div>
                            <!-- Sidebar head -->

                        </div>
                    </div>
                <?php } ?>    
                <!--  -->
                <?php if ($history) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="panel-heading-text text-medium">
                                <strong>History Course(s)</strong>
                            </h1>
                        </div>
                        <div class="panel-body">
                            <div id="historyContainer"></div>
                        </div>
                    </div>
                <?php } ?>    
            </div>
            <!--  -->
            <!--  -->
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="csTabContent">
                            <div class="csLisitingArea">
                                <div class="csBoxWrap jsBoxWrap">

                                    <?php if ($history) { ?>
                                        <?php foreach ($history as $course) { ?>
                                            <!-- Course History Start -->
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <h1 class="panel-heading-text text-medium">
                                                                <strong>
                                                                    <?php echo $course['course_title']; ?>
                                                                </strong>
                                                            </h1>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <?php foreach ($course['history'] as $item) { ?>
                                                            <div class="col-sm-4">
                                                                <article class="article-sec">
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-xs-12">
                                                                            <?php if ($item['course_banner']) { ?>
                                                                                <img src="<?php echo AWS_S3_BUCKET_URL.$item['course_banner']; ?>" style="height: 214px !important;" />
                                                                            <?php } else { ?>   
                                                                                <img src="https://automotohrattachments.s3.amazonaws.com/default_course_banner-Uk2W5O.jpg" style="height: 214px !important;" />
                                                                            <?php } ?>    
                                                                        </div>
                                                                    </div>
                                                                    <h1 style="height: 58px;">
                                                                        <?php 
                                                                            echo $course['course_title'];
                                                                            // if ($item['lesson_status'] == "incomplete") {
                                                                            //     echo "Incomplete Course";
                                                                            // } else if ($item['lesson_status'] == "completed") {
                                                                            //     echo "Completed Course";
                                                                            // }
                                                                        ?>
                                                                    </h1>
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-xs-12">
                                                                            <p class="csColumSection"><strong>COURSE STATUS</strong></p>
                                                                            
                                                                            <?php if ($item['lesson_status'] == "completed") { ?>
                                                                                <?php if ($item['course_status'] == "passed") { ?>
                                                                                    <p class="text-success">Passed</p>
                                                                                <?php } else { ?>
                                                                                    <p class="text-danger">Failed</p>
                                                                                <?php } ?> 
                                                                            <?php } else { ?>
                                                                                <p>N/A</p>   
                                                                            <?php } ?>  
                                                                        </div>
                                                                        <div class="col-md-6 col-xs-12">
                                                                            <p class="csColumSection"><strong>LANGUAGE</strong></p>
                                                                            <p>
                                                                                <?php if ($item['course_language']) { ?>
                                                                                    <p><?php echo ucfirst($item['course_language']); ?></p>
                                                                                <?php } else { ?>
                                                                                    <p >English</p>
                                                                                <?php } ?> 
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <hr style="margin-top: 0px !important; color: 000;"> 

                                                                    <div class="row">
                                                                        <div class="col-md-6 col-xs-12">
                                                                            <p class="csColumSection"><strong>START DATE</strong></p>
                                                                            <p><?= formatDateToDB($item['created_at'], DB_DATE_WITH_TIME, DATE); ?></p>
                                                                        </div>
                                                                        <div class="col-md-6 col-xs-12">
                                                                            <p class="csColumSection"><strong>END DATE</strong></p>
                                                                            <p><?= formatDateToDB($item['updated_at'], DB_DATE_WITH_TIME, DATE); ?></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-xs-12 text-center">
                                                                            <p>&nbsp;</p>
                                                                            <?php if ($page == 'my_courses_history') { ?>
                                                                                <a class="btn btn-info csRadius5 csF16" href="<?php echo base_url("lms/courses/my_history_preview/".$item['sid']); ?>">
                                                                                    <i class="fa fa-eye"></i>
                                                                                    View Content
                                                                                </a>
                                                                            <?php } else if ($page == 'employee_courses_history') { ?>
                                                                                <?php if ($reviewAs == 'non_plus') { ?>
                                                                                    <a class="btn btn-info csRadius5 csF16" href="<?php echo base_url("lms/courses/subordinate_history_preview/".$item['sid']."/".$subordinateId); ?>">
                                                                                        <i class="fa fa-eye"></i>
                                                                                        View Content
                                                                                    </a>
                                                                                <?php } else if ($reviewAs == 'plus') { ?>
                                                                                    <a class="btn btn-info csRadius5 csF16" href="<?php echo base_url("lms/courses/employee_history_preview/".$item['sid']."/".$subordinateId); ?>">
                                                                                        <i class="fa fa-eye"></i>
                                                                                        View Content
                                                                                    </a>
                                                                                <?php } ?>
                                                                            <?php } ?>    
                                                                        </div>
                                                                    </div>
                                                                </article>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Course History End -->
                                        <?php } ?>     
                                    <?php } else { ?>
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <h3>No Course History Found!</h3>
                                            </div>
                                        </div>        
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div>
</div>


<script src="https://code.highcharts.com/highcharts.js"></script> 
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    var page = "<?php echo $page; ?>";
    var subordinateId = "<?php echo $subordinate_sid; ?>";
    var subordinateName = "<?php echo $subordinateName; ?>";
    var reviewAs = "<?php echo $type; ?>";
    var courseCategories = <?php echo json_encode($categories); ?>;
    var categoriesValues = <?php echo json_encode($categoriesValues); ?>;

    Highcharts.chart('historyContainer', {
		chart: {
			type: 'bar'
		},
		title: {
			text: 'Course History'
		},
		xAxis: {
			categories: courseCategories,
			title: {
				text: 'Courses'
			}
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Course Count'
			}
		},
		series: [{
			name: 'Course History Count',
			data: categoriesValues
		}]
	});
</script>