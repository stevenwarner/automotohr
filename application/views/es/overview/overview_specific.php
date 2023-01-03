<!-- Main page -->
<div class="csPage">
    
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt20">
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
                        <div class="col-sm-12">
                            <a href="<?php echo base_url("employee/surveys/overview"); ?>" class="btn _csB1 _csF2 _csR5 _csMt20 pull-right"><i class="fa fa-arrow-left _csF16" aria-hidden="true"></i>&nbsp;Go Back To Overview</a>
                        </div>
                    </div>
                    <!-- Running surveys -->
                    <div class="panel panel-default _csMt30">
                        <div class="panel-heading">
                            <h3 class="_csM0 _csF16">
                                <span id="jsSurveysType">Finished</span> Engagements (<span id="jsSurveysCount">0</span>) 
                            </h3>
                            <p class="_csF12 _csF4 _csMt10">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> <span id="jsSurveysTypeInfo">The engagements whose end date passes away.</span>
                            </p>    
                        </div>
                        <div class="panel-body" id="jsSurveysSection">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>