<!-- Main page -->
<div class="csPage">
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt10">
        <div class="container-fluid">
            <!--  -->
            <div class="row">
   
                <!--  -->
                <div class="col-md-12 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?php echo base_url("employee/surveys/create"); ?>" class="btn _csB4 _csF2 _csR5  _csF16"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Survey</a>
                        </div>
                    </div>
                    <!-- Running surveys -->
                    <div class="panel panel-default _csMt10">
                        <div class="panel-heading _csP0">
                            <!-- Nav tabs -->
                            <ul class="_csPageTabs">
                                <li>
                                    <a href="#jsActive" class="active" data-toggle="tab">Active (2)</a>
                                </li>
                                <li>
                                    <a href="#jsDraft" data-toggle="tab">Draft (5)</a>
                                </li>
                                <li>
                                    <a href="#jsFinished" data-toggle="tab">Finished (5)</a>
                                </li>
                            </ul>

                        </div>
                        <div class="panel-body">
                            <!-- Tab panes -->
                            <div class="tab-pane" id="jsActive">
                                <div class="table-responsive">

                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <div class="panel panel-default " data-id="1" data-title="Test Survey">
                                                <div class="panel-heading  _csB4 _csF2">
                                                    <b>jghj</b>
                                                    <span class="pull-right">
                                                        <a class="btn _csB4 _csF2 _csR5  _csF16 " title="Start the review" placement="top" href="<?= base_url("employee/surveys/surveys/12"); ?>">
                                                            <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                        </a>

                                                    </span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="panel-body">
                                                    <p class="_csF14"><b>Title</b></p>
                                                    <p class="_csF14">Test test</p>
                                                    <hr />
                                                    <p class="_csF14"><b>Cycle Period</b></p>
                                                    <p class="_csF14">12/12/2002 </p>
                                                    <hr />
                                                    <p class="_csF14"><b>Reviewer(s) Progress ?</b></p>
                                                    <p class="_csF14">The percentage of reviewers who have submitted the review. Click to view details.</p>
                                                    <p class="_csF3"><b>10% Completed</b></p>

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
    </div>
</div>