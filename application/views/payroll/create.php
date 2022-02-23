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
                        <h1 class="m0 p0 csB7">
                            Run Payroll
                        </h1>
                        <hr />
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php if(!empty($period) && !empty($payroll)) : ?>
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <p style="font-size: 70px;" class="mb0"><i class="fa fa-money" aria-hidden="true"></i></p>
                            <p class="csF26 csB7">Regular Payroll (<?=formatDateToDB($period['start_date'], DB_DATE, DATE);?> - <?=formatDateToDB($period['end_date'], DB_DATE, DATE);?>)</p>
                            <p class="csF18">Please run payroll by <strong><?=GUSTO_PAYROLL_TIME;?></strong> on <strong><?=formatDateToDB($payroll['payroll_deadline'], DB_DATE, DATE);?></strong> to pay your employees for their hard work. They’ll receive their funds on <strong><?=formatDateToDB($payroll['check_date'], DB_DATE, DATE);?></strong>. If you miss this deadline, your employees’ direct deposit will be delayed.</p>
                            <a href="<?=base_url('payroll/run/'.($payroll['payroll_id']).'');?>" class="btn btn-orange">Run Regular Payroll</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <p style="font-size: 70px;" class="mb0"><i class="fa fa-trophy csFC3" aria-hidden="true"></i></p>
                            <p class="csF26 csB7">Your Employees Have Been Paid on Time!</p>
                            <p class="csF18">You do not have any pending payrolls to run. An automatic email will notify you when it is time to run another regular payroll. In the meantime, carry on with a smile.</p>
                            <a href="<?=base_url('payroll/history/');?>" disabled class="btn btn-orange">Payroll History</a>
                        </div>
                    </div>
                <?php endif; ?>
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
            window.location = window.location.origin + '/payroll/create/'+$('#jsPayrollSelect').val()+'/'+$('#jsPayrollSelect option[value="'+($('#jsPayrollSelect').val())+'"]').data('version')+'?step=1';
        });
    });
</script>