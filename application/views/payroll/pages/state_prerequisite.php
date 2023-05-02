<!--  -->
<div class="container">
    <div class="csPageWrap">
        <!-- Heading -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="csF18 csB7">
                    State Prerequisite are missing               
                </h1>
            </div>
        </div>
        <!-- Body -->
        <?php if ($work_address == 0) { ?>
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF16">
                        Employee working address is missing.
                    </p>

                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <a class="btn btn-orange csF16 js jsNavBarAction" data-id="employee_profile" href="javascript:void(0)">Personal details</a>          
                </div>
            </div>
        <?php } ?>
        <?php if ($federal_tax == 0) {?>
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF16">
                        Employee Federal Tax Information is missing.
                    </p>

                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <a class="btn btn-orange csF16 js jsNavBarAction" data-id="employee_federal_tax" href="javascript:void(0)">Federal tax</a>          
                </div>
            </div>
        <?php } ?>    
    </div>
</div>

<!--  -->
<div class="container">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee_state_tax", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            State tax information
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7" id="JSStateName">
                            
                        </h1>
                        <p>
                        The information below is used to determine the state tax rate for Maya.
                        </p>
                    </div>
                </div>
                <br>
                <div id="JSQusetionSection">
                   
                </div>    
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-black csF16 csB7 jsPayrollEmployeeOnboard" data-employee_id="<?php echo $employee_sid; ?>" data-level="3">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSaveEmployeeStateTax">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
