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
                            Payroll History
                        </h1>
                    </div>
                </div>
                <!--  -->
                <br>
                
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#regular" aria-controls="regular" role="tab" data-toggle="tab">Regular Payroll</a>
                        </li>
                        <li role="presentation" disabled class="disabled">
                            <a href="javascript:void(0);">Off-cycle Payroll</a>
                        </li>
                        <li role="presentation" disabled class="disabled">
                            <a href="javascript:void(0);">Contractor Payroll</a>
                        </li>
                    </ul>
                
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="regular">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <caption></caption>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Check date</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Period</th>
                                                    <th scope="col">Total debited</th>
                                                    <th scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(!empty($payrollHistory)) { ?>
                                                    <?php foreach($payrollHistory as $history){ ?>
                                                        <?php $payroll = json_decode($history['payroll_json']); ?>
                                                        <tr 
                                                            data-id="<?=$payroll->payroll_id;?>"
                                                            data-sd="<?=formatDateToDB($payroll->pay_period->start_date, DB_DATE, DATE);?>"
                                                            data-ed="<?=formatDateToDB($payroll->pay_period->end_date, DB_DATE, DATE);?>"
                                                            data-dd="<?=formatDateToDB($payroll->payroll_deadline, DB_DATE, DATE);?>"
                                                        >
                                                            <td class="vam">
                                                                <?=formatDateToDB($payroll->check_date, DB_DATE, DATE);?>
                                                            </td>
                                                            <td class="vam">
                                                                <?=$history['type'];?>
                                                            </td>
                                                            <td class="vam">
                                                                <?=formatDateToDB($payroll->pay_period->start_date, DB_DATE, DATE);?> - <?=formatDateToDB($payroll->pay_period->end_date, DB_DATE, DATE);?>
                                                            </td>
                                                            <td class="vam">
                                                                $<?=number_format($payroll->totals->company_debit, 2, '.', ',');?>
                                                            </td>
                                                            <td class="vam">
                                                                <!-- <a href="<?=base_url('payroll/history/'.($history['sid']).'');?>" class="btn btn-orange">
                                                                    <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View details
                                                                </a> -->
                                                                -
                                                                <?php if($payroll->payroll_deadline > date('Y-m-d', strtotime('now')) ): ?>
                                                                    <button class="btn btn-black jsCancelPayroll">Cancel Payroll</button>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
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

<script>
    $(function(){
        //
        $('.jsCancelPayroll').click(function(event){
            //
            event.preventDefault();
            //
            var data = $(this).closest('tr').data();
            //
            alertify.confirm(
                'You may cancel <strong>'+(data.sd)+' - '+(data.ed)+'</strong> payroll now and run it again later. Just note that your employees will be paid late if you don’t run it by <strong><?=GUSTO_PAYROLL_TIME;?></strong> on <strong>'+(data.dd)+'</strong>.<br><br><strong>Don’t want to lose all your data?</strong><br>Rest assured—we’ll save all the info you entered for this payroll, in case you need to re-run it.', 
                function(){
                    CancelPayroll(data.id);
                }
            ).setHeader('Cancel Payroll Confirmation');
        });
        
        //
        function CancelPayroll(payrollId){
            //
            ml(true, 'main_loader', 'Please wait, while we cancel the payroll.');
            //
            $.post(
                "<?=base_url("cancel_payroll");?>", {
                    payrollId: payrollId
                }
            ).done(function(resp){
                alertify.alert("Success!", "Payroll canceled.", function(){
                    window.location.href = window.location.origin + '/payroll/run';
                });
            });
        }
    });
</script>