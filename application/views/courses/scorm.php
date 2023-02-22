
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
                                        <strong class="._csF1">PENDING</strong>
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
                                    <div class="col-md-3 tutorial-nav">
                                        <ul id="jsScormChapterLinks">       
                                        </ul>   
                                    </div> 
                                    <div class="col-md-9">
                                        <iframe id="jsScormCourse" src="" width="100%" height="660px"></iframe>
                                    </div>
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

<!-- Define Scorm Variables -->
<script type="text/javascript">
    var SCORM_XML = <?=json_encode($scorm);?>;
    var SCORM_PATH = '<?php echo $scorm_path; ?>';
    var SCORM_CONTENT = SCORM_XML.sequencing[SCORM_XML.startPoint]["parameter"];
    var SCORM_CHAPTER = SCORM_XML.sequencing[SCORM_XML.startPoint]["title"];
    var SCORM_LEVEL = 0;
</script>