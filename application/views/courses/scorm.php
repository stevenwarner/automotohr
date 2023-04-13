
<!-- Main page -->
<div class="csPage">
    <!--  -->
    <?php $this->load->view('courses/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt10">
        <div class="container-fluid">
            <!--  -->
            <div class="row">
                <!--  -->
                <div class="col-md-3 col-sm-12">
                    <!-- Sidebar -->
                    <?php $this->load->view('2022/sidebar'); ?>
                </div>
                <!--  -->
                <div class="col-md-9 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <span class="col-sm-12 text-right">
                            <a class="btn _csB1 _csF2 _csR5 _csF16 _csMr10" href="<?=base_url('lms_courses/my_courses');?>">
                                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp; Back to Courses 
                            </a>
                        </span>
                    </div>

                    <div class="panel panel-default _csMt10">
                        <div class="panel-body">
                            <!-- Basic -->
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div>
                                        <p class="_csF16 _csB2">
                                            <b><?php echo $course['title']; ?></b>
                                        </p>
                                        <p class="_csF14">
                                            <?php echo $course['display_start_date'].' - '.$course['display_end_date']; ?>
                                            <br />
                                            <?php echo ' Due in '.$course['daysLeft']; ?>
                                        </p>
                                        <p class="_csF14 _csB2">
                                            <?php echo $course['description']; ?>
                                        </p>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-md-6 col-xs-12">
                                    <span class="pull-right _csF16 _csB2">
                                        <strong class="._csF1">
                                            <?php echo $courseStatus == 'course_completed' ? 'COMPLETED' : 'PENDING'; ?>
                                        </strong>
                                    </span>
                                </div>
                            </div>      
                        </div>
                    </div>

                    <!-- Scorm Screen -->
                    <div class="row" >
                        <br />
                        <div class="col-xs-12">
                            <div class="panel panel-theme">
                                <!-- Scorm -->
                                <div class="panel-heading _csB1">
                                    <p class="_csF14 _csF2">
                                        <b><?php echo $scorm['title']; ?></b>
                                    </p>
                                </div>
                                <div class="panel-body">
                                    <?php if ($courseStatus == 'course_pending') { ?>
                                        <div class="col-md-3 tutorial-nav">
                                            <ul id="jsScormChapterLinks">       
                                            </ul>   
                                        </div> 
                                        <div class="col-md-9">
                                            <iframe id="jsScormCourse" src="" width="100%" height="660px"></iframe>
                                        </div>
                                    <?php } else if ($courseStatus == 'course_completed') { ?>
                                        <div class="row">    
                                            <div class="col-md-12 col-xs-12"> 
                                                <?php if (!empty($scormData)) { ?>
                                                    <?php if ($version == '2004') { ?>
                                                        <table class="table table-striped table-condensed">
                                                            <caption></caption>
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Chapter</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Type</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Status</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Time Spent</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Total Marks</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Obtain marks</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Attempted At</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Chapter Result</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($scormData)) { ?>
                                                                    <?php foreach ($scormData as $chapter) { ?>
                                                                        <tr>
                                                                            <td style="vertical-align: middle">
                                                                                <p class="_csF14">
                                                                                    <b><?php echo $chapter['title']; ?></b>
                                                                                </p>
                                                                            </td>
                                                                            <?php if (isset($chapter['success_status']) && !empty($chapter['success_status'])) { ?>
                                                                                <td style="vertical-align: middle">
                                                                                    <p class="_csF14">
                                                                                        <b><?php echo strtoupper($chapter['type']); ?></b>
                                                                                    </p>
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <?php 
                                                                                        if ($chapter['completion_status'] == "completed") {
                                                                                            echo "Completed";
                                                                                        } else {
                                                                                            echo "Incomplete";
                                                                                        }
                                                                                    ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <?php echo gmdate("H:i:s", $chapter['spent_seconds']); ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <?php echo isset($chapter['score_max']) ? $chapter['score_max'] : '-'; ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <?php echo isset($chapter['score_raw']) ? $chapter['score_raw'] : '-'; ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <?php echo $chapter['attempted_date']; ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle" class="<?php echo $chapter['success_status']  == 'failed' ? 'text-danger' : 'text-success';?>">
                                                                                    <p class="_csF14">
                                                                                        <b><?php echo strtoupper($chapter['success_status']); ?></b>
                                                                                          
                                                                                    </p>
                                                                                </td>
                                                                            <?php } else { ?>
                                                                                <td colspan="7" style="vertical-align: middle">
                                                                                    <p class="text-center">
                                                                                        <b><?php echo 'Skip chapter and jump to quiz!'; ?></b>
                                                                                    </p>
                                                                                </td>      
                                                                            <?php } ?>  
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="8" class="text-center">
                                                                            <p>
                                                                                <b>
                                                                                    Course is not completed yet!
                                                                                </b>    
                                                                            </p>        
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table> 
                                                    <?php } else { ?>
                                                        <table class="table table-striped table-condensed">
                                                            <caption></caption>
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Course Name</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Status</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Time Spent</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Total Marks</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Obtain marks</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Attempted At</th>
                                                                    <th scope="col" class="_csF16 _csB1 _csF2">Result</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($scormData)) { ?>
                                                                    <?php foreach ($scormData as $chapter) { ?>
                                                                        <tr>
                                                                            <td style="vertical-align: middle">
                                                                                <p class="_csF14">
                                                                                    <b><?php echo $chapter['title']; ?></b>
                                                                                </p>
                                                                            </td>
                                                                            <?php if (isset($chapter['lesson_status']) && !empty($chapter['lesson_status'])) { ?>
                                                                                <td style="vertical-align: middle">
                                                                                    <?php 
                                                                                        if ($chapter['completion_status'] == "completed") {
                                                                                            echo "Completed";
                                                                                        } else {
                                                                                            echo "Incomplete";
                                                                                        }
                                                                                    ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <?php echo gmdate("H:i:s", $chapter['spent_seconds']); ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <?php echo isset($chapter['score_max']) && $chapter['score_max'] != 'score_max'? $chapter['score_max'] : '-'; ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <?php echo isset($chapter['score_raw']) && $chapter['score_raw'] != 'score_raw' ? $chapter['score_raw'] : '-'; ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle">
                                                                                    <?php echo $chapter['attempted_date']; ?>
                                                                                </td>
                                                                                <td style="vertical-align: middle" class="<?php echo $chapter['lesson_status']  == 'failed' ? 'text-danger' : 'text-success';?>">
                                                                                    <p class="_csF14">
                                                                                        <b><?php echo strtoupper($chapter['lesson_status']); ?></b>
                                                                                          
                                                                                    </p>
                                                                                </td>
                                                                            <?php } else { ?>
                                                                                <td colspan="7" style="vertical-align: middle">
                                                                                    <p class="text-center">
                                                                                        <b><?php echo 'Skip chapter and jump to quiz!'; ?></b>
                                                                                    </p>
                                                                                </td>      
                                                                            <?php } ?>  
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="8" class="text-center">
                                                                            <p>
                                                                                <b>
                                                                                    Course is not completed yet!
                                                                                </b>    
                                                                            </p>        
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } ?>   
                                                <?php } ?>  
                                            </div>
                                        </div> 
                                    <?php } ?>    
                                </div>
                                <!-- End Scorm -->
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <span class="col-sm-12 text-right">
                            <a class="btn _csB1 _csF2 _csR5 _csF16 _csMr10 _csMb10" href="<?=base_url('lms_courses/my_courses');?>">
                                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp; Back to Courses 
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('courses/partials/loader'); ?>

<!-- Define Scorm Variables -->
<script type="text/javascript">
    var SCORM_XML = <?php echo json_encode($scorm); ?>;
    var SCORM_LAUNCH_FILE = SCORM_XML.launchFile;
    var SCORM_PATH = SCORM_XML.path;
    var SCORM_DIR = SCORM_XML.dir;
    var SCORM_PARAM_KEY = SCORM_XML.paramKey;
    var SCORM_CONTENT = SCORM_XML.sequencing[SCORM_XML.lastChapter]["paramValue"];
    var SCORM_CHAPTER = SCORM_XML.sequencing[SCORM_XML.lastChapter]["title"];
    var SCORM_LEVEL = parseInt(SCORM_XML.lastChapter);
    var LAST_CHAPTER = SCORM_XML.lastChapter;
    var LAST_LOCATION = SCORM_XML.lastLocation != 0 ? SCORM_XML.lastLocation : '';
    var SUSPEND_DATA = SCORM_XML.suspend_data.length  ? SCORM_XML.suspend_data : '';
    //
    var BASE_URI = "<?php echo rtrim(base_url(), '/'); ?>/lms_courses/handler";
    var COMPANY_SID = <?php echo $session['company_detail']['sid'] ?? 0 ?>;
    var EMPLOYEE_SID = <?php echo $session['employer_detail']['sid'] ?? 0 ?>;
    var COURSE_SID = <?php echo $course_sid ?? 0 ?>;
    //
</script>