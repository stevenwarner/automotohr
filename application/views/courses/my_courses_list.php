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
                    <!-- Running surveys -->
                    <div class="panel panel-default _csMt10">
                        <div class="panel-heading _csP0">
                            <!-- Nav tabs -->
                            <ul class="_csPageTabs">
                                <li>
                                    <a href="javascript:void(0)" data-survey_type="pending"  id="jsPendingTab" class="active jsSurveyTab" data-toggle="tab">
                                        Pending <span id="jsPendingTabCount">(0)<span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" data-survey_type="completed" id="jsCompletedTab" class="jsSurveyTab" data-toggle="tab">
                                        Completed <span id="jsCompletedTabCount">(0)<span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                        <div class="panel-body">
                            <!-- Tab panes -->
                            <div class="tab-pane" id="jsAssignedSurveysSection">
                                <div class="table-responsive">
                                    <div class="row">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $this->load->view('courses/partials/loader'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>