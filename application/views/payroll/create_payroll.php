<?php 
    $step1 = 
    $step2 = 
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
            <!-- Side Bar -->
            <?php $this->load->view('employee_info_sidebar_ems'); ?>
            <!-- Main Content Area -->
            <div class="col-md-9">
                <!-- Main Content Area -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB9">
                            Run Payroll
                        </h1>
                        <hr>
                    </div>
                </div>
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
                        <p class="csF14 csB7" style="margin-top: 5px;">2. Review and submit</p>
                    </div>

                    <div class="col-sm-3">
                        <div class="progress mb0">
                            <div class="progress-bar csBG3" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" style="width:<?=$step3;?>%">
                            </div>
                        </div>
                        <p class="csF14 csB7" style="margin-top: 5px;">2. Confirmation</p>
                    </div>
                </div>
                <?php $step == 1 ? $this->load->view('payroll/partials/create_step_1') : '';?>
                <?php $step == 2 ? $this->load->view('payroll/partials/create_step_2') : '';?>
            </div>
        </div>
    </div>
</div>

<!-- Add Models -->
<link rel="stylesheet" href="<?=base_url('assets/css/SystemModel.css');?>">
<script src="<?=base_url("assets/js/SystemModal.js");?>"></script>

<script>
    $(function(){
        //
        var step = "<?=$step;?>";
        //
        const MAIN_LOADER = 'main_loader';
        //
        var isAJAX = null;
        //
        function fetchPayrollView(){
            //
            if(isAJAX !== null){
                return;
            }
            //
            ml(true, MAIN_LOADER);
            //
            isAJAX = $.get(
                "<?=base_url("get_payroll_step");?>/"+step
            ).done(function(resp){
                //
                isAJAX = null;
                //
                console.log(resp);
            });
        }
        // 
        // fetchPayrollView();
    });
</script>