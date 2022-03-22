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
                                                        <tr>
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
                                                                <a href="<?=base_url('payroll/history/'.($history['sid']).'');?>" class="btn btn-orange">
                                                                    <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View details
                                                                </a>
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