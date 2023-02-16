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
                        <div class="col-sm-12 text-right">
                            <a href="<?php echo base_url("lms_courses/create"); ?>" class="btn _csB4 _csF2 _csR5  _csF16"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Course</a>
                        </div>
                    </div>
                    <!-- Running surveys -->
                    <div class="panel panel-default _csMt10">
                        <div class="panel-heading _csP0">
                            <!-- Nav tabs -->
                            <ul class="_csPageTabs">
                                <li>
                                    <a href="javascript:void(0)" data-survey_type="running"  id="jsRunningTab" class="active jsCoursesTab" data-toggle="tab">
                                        Running <span id="jsRunningTabCount">(0)<span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" data-survey_type="finished" id="jsFinishedTab" class="jsCoursesTab" data-toggle="tab">
                                        Finished <span id="jsFinishedTabCount">(0)<span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" data-survey_type="assigned" id="jsAssignedTab" class="jsCoursesTab" data-toggle="tab">
                                        Assigned <span id="jsAssignedTabCount">(0)<span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" data-survey_type="draft" id="jsDraftTab" class="jsCoursesTab" data-toggle="tab">
                                        Draft <span id="jsDraftTabCount">(0)<span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                        <div class="panel-body">
                            <!-- Tab panes -->
                            <div class="tab-pane" id="jsCompanyCoursesSection">
                                <div class="table-responsive">
                                    <div class="row">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $this->load->view('courses/partials/manage_course_period'); ?>
                        <?php $this->load->view('courses/partials/loader'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>