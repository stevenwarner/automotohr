<div class="csPageWrap">
    <!-- Nav bar -->
    <div class="container-fluid">
        <?php $this->load->view('payroll/navbar'); ?>
    </div>
    <br>
    <!--  -->
    <div class="row">
        <div class="container-fluid">
            <!-- Side Bar -->
            <?php $this->load->view('employee_info_sidebar_ems'); ?>
            <!-- Main Content Area -->
            <div class="col-md-9">
                <!-- Main Content Area -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB9">
                            Run Payroll
                            <span class="pull-right">
                                <a href="<?=current_url().'/onetime';?>" class="btn btn-orange csF16 csB7">
                                    <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                                    Run A One-time Payroll
                                </a>
                            </span>
                        </h1>
                        <hr>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-4">
                        <p class="csF16 csB9">
                            Time to run a few payrolls
                        </p>
                        <p class="csF16">
                            Please let your team know if they’ll be paid later than expected.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <br>
                    <!--  -->
                    <div class="col-sm-4">
                        <label class="csF16 csB7">
                            Regular Payroll
                        </label>
                    </div>
                </div>
                    <div class="row">
                        <!--  -->
                        <div class="col-sm-4">
                            <select id="jsPayrollSelect">
                                <option value="0">[Select Payroll]</option>
                                <?php 
                                    if(!empty($UnProcessedPayrolls)):
                                        foreach($UnProcessedPayrolls as $payroll):
                                ?>
                                    <option value="<?=$payroll['payroll_uuid'];?>"><?=formatDateToDB(
                                        $payroll['pay_period']['start_date'],
                                        DB_DATE,
                                        DATE
                                    ).' - '.formatDateToDB(
                                        $payroll['pay_period']['end_date'],
                                        DB_DATE,
                                        DATE
                                    );?></option>
                                <?php 
                                        endforeach;
                                    endif;
                                ?>
                                
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <br>
                        <!--  -->
                        <div class="col-sm-4">
                            <span class="pull-right">
                                <button class="btn btn-orange jsPayrollSubmit">
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp; Run Payroll
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(function(){
        //
        $('#jsPayrollSelect').select2({ minimumResultsForSearch: -1});
        //
        $('.jsPayrollSubmit').click(function(event){
            //
            event.preventDefault();
            //
            if($('#jsPayrollSelect').val() == 0){
                alertify.alert('WARNING!', 'Please, select a payroll to proceed.');
                return;
            }
            //
            window.location = window.location.origin + '/payroll/create/'+$('#jsPayrollSelect').val()+'?step=1';
        });
    });
</script>