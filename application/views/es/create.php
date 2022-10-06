<!-- Main page -->
<div class="csPage">
    <!-- Sidebar -->
    <?php $this->load->view('2022/sidebar'); ?>
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt10">
        <div class="container-fluid">
            <!--  -->
            <div class="row _csMt10">
                <div class="col-md-8 col-sm-12">
                    <h3 class="_csF26">Let's get started</h3>
                </div>
                <div class="col-md-4 col-sm-12 text-right _csVm">
                    <a href="" class="btn _csB4 _csF16 _csF2 _csR5 _csMt20">
                        <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancel
                    </a>
                </div>
            </div>
            <!--  -->
            <div class="row _csMt30 _csMb20">
                <div class="col-md-12 col-sm-12">
                    <div class="row line">
                        <div class="col-xs-3 stepText1">Getting Started</div>
                        <div class="col-xs-3 stepText2">Details</div>
                        <div class="col-xs-3 stepText3">Questions</div>
                        <div class="col-xs-3 stepText4">Respondents</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="step step1 active"></div>
                        </div>
                        <div class="col-xs-3">
                            <div class="step step2"></div>
                        </div>
                        <div class="col-xs-3">
                            <div class="step step3"></div>
                        </div>
                        <div class="col-xs-3">
                            <div class="step step4"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!--  -->
            <div class="panel panel-default _csMt10">
                <div class="panel-heading">
                    <h3 class="_csF16">Select a Survey Template</h3>
                </div>
                <div class="panel-body">
                    <p class="_csF14">Select the best template based on the suggestions below or create your own survey from the ground up.</p>
                    <strong class="_csF14 text-danger">Need help getting started your survey? <span class="_csF3">Check out this how-to guide.</span></strong>
                    <hr>
                    <!--  -->
                    <h3 class="_csF14">TEMPLATES</h3>
                    <p class="_csF14"><?=STORE_NAME;?> templates are industry standard and capture the most important aspects of engagement. If you edit any question here, it will impact the comparison capabilities in the future.</p>
                    <hr>
                    <!--  -->
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="csESBox _csBD _csBD6 _csR5 _csP10">
                                <img src="<?=base_url("assets/2022/images/es/pulse_check.svg");?>" alt=""/>
                                <p class="_csF14">Pulse Check</p>
                                <br>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>