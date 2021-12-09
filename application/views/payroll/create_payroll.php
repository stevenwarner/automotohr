<?php 
    $step1 = 
    $step2 = 
    $step4 = 
    $step3 = 0;
    //
    if($step == 4){
        $step1 =
        $step2 = 
        $step3 =
        $step4 = 100;
    } else if($step == 3){
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
                    <div class="col-sm-4">
                        <h1 class="m0 p0 csB7">
                            Run Payroll
                        </h1>
                    </div>
                    <div class="col-sm-8 text-right">
                        <p class="csF16 csB7 mb0">Pay Period</p>
                        <span class="csFC2">
                            <?=formatDateToDB($Payroll['pay_period']['start_date'], DB_DATE, DATE);?> - 
                            <?=formatDateToDB($Payroll['pay_period']['end_date'], DB_DATE, DATE);?>
                        </span>
                    </div>
                </div>
                <hr />
                <!-- Steps -->
                <div class="row">
                    <div class="col-sm-3">
                        <div class="progress mb0">
                            <div class="progress-bar csBG3" role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                aria-valuemax="100" style="width:<?=$step1;?>%">
                            </div>
                        </div>
                        <p class="csF14 csB7" style="margin-top: 5px;">1. Hours and earnings</p>
                    </div>

                    <div class="col-sm-3">
                        <div class="progress mb0">
                            <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" style="width:<?=$step2;?>%">
                            </div>
                        </div>
                        <p class="csF14 csB7" style="margin-top: 5px;">2. Time off</p>
                    </div>

                    <div class="col-sm-3">
                        <div class="progress mb0">
                            <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" style="width:<?=$step3;?>%">
                            </div>
                        </div>
                        <p class="csF14 csB7" style="margin-top: 5px;">3. Review and submit</p>
                    </div>

                    <div class="col-sm-3">
                        <div class="progress mb0">
                            <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" style="width:<?=$step4;?>%">
                            </div>
                        </div>
                        <p class="csF14 csB7" style="margin-top: 5px;">4. Confirmation</p>
                    </div>
                </div>
                <?php $step == 1 ? $this->load->view('payroll/partials/create_step_1') : '';?>
                <?php $step == 2 ? $this->load->view('payroll/partials/create_step_4') : '';?>
                <?php $step == 3 ? $this->load->view('payroll/partials/create_step_2') : '';?>
                <?php $step == 4 ? $this->load->view('payroll/partials/create_step_3') : '';?>
            </div>
        </div>
    </div>
</div>

<!-- Add Models -->
<link rel="stylesheet" href="<?=base_url('assets/css/SystemModel.css');?>">
<script src="<?=base_url("assets/js/SystemModal.js");?>"></script>