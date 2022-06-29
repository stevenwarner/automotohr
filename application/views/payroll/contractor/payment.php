<?php 
    $step = 1;
    $step1 = 
    $step2 = 
    $step3 = 0;
    //
    if($step == 3){
        $step1 = 
        $step2 = 
        $step3 = 100;
    } else if($step == 2){
        $step1 = 
        $step2 = 100;
    } else if($step == 1){
        $step1 = 100;
    }
?>
<div class="csPageWrap">
    <!-- Nav bar -->
    <div class="container-fluid">
        <?php $this->load->view('payroll/navbar'); ?>
    </div>
    <br>
    <!--  -->
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content Area -->
            <div class="col-md-12">
                <!-- Main Content Area -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="m0 p0 csB7">
                            Pay contractors
                        </h1>
                    </div>
                </div>
                <hr />
                <!-- Steps -->
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="progress mb0">
                            <div class="progress-bar csBG3" role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                aria-valuemax="100" style="width:<?=$step1;?>%">
                            </div>
                        </div>
                        <p class="csF14 csB7" style="margin-top: 5px;">1. Enter payments</p>
                    </div>

                    <div class="col-sm-4 col-xs-12">
                        <div class="progress mb0">
                            <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" style="width:<?=$step2;?>%">
                            </div>
                        </div>
                        <p class="csF14 csB7" style="margin-top: 5px;">2. Review</p>
                    </div>

                    <div class="col-sm-4 col-xs-12">
                        <div class="progress mb0">
                            <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" style="width:<?=$step3;?>%">
                            </div>
                        </div>
                        <p class="csF14 csB7" style="margin-top: 5px;">3. Confirmation</p>
                    </div>
                </div>
                <!--  -->
                <?php $step == 1 ? $this->load->view('payroll/contractor/step1') : '';?>
            </div>
        </div>
    </div>
</div>