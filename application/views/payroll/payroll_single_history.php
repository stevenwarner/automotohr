<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <!-- Sidebar -->
            <?php $this->load->view('employee_info_sidebar_ems'); ?>
            <!--  -->
            <div class="col-sm-9 col-xs-12">
                <!--  -->
                <div class="">
                    <span class="pull-left">
                        <h3 class="">Regular Payroll Summary | <?=formatDateToDB($Payroll['check_date'], DB_DATE, DATE);?> </h3>
                    </span>
                </div>
                <!--  -->
                <div class="row">
                    <!--  -->
                    <div class="col-sm-8">
                        <div class="jumbotron p10">
                            <h4>Payroll receipt #<?=$Payroll['payroll_uuid'];?></h4>
                        </div>
                    </div>
                    <!--  -->
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="jumbotron p10">
                                    <h4 class="p0 m0"><strong>Payroll Details</strong></h4>
                                    <dl>
                                        <br>
                                        <dt>Pay date</dt>
                                        <dd><?=formatDateToDB($Payroll['check_date'], DB_DATE, DATE);?></dd>
                                        <br>
                                        <dt>Pay period</dt>
                                        <dd><?=formatDateToDB($Payroll['pay_period']['start_date'], DB_DATE, DATE);?> - <?=formatDateToDB($Payroll['pay_period']['end_date'], DB_DATE, DATE);?></dd>
                                        <br>
                                        <dt>Processed on</dt>
                                        <dd><?=formatDateToDB($Payroll['processed_date'], DB_DATE, DATE);?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabs -->
                <div class="jsPageTabContainer">
                    <!-- Hours and earnings -->
                    <div class="jsPageTab" data-page="hours">
                        <!-- Tax & deductions -->
                        <?php $this->load->view('payroll/partials/partial_create_step_2_taxes'); ?>
                        <!-- Worked hours -->
                        <?php $this->load->view('payroll/partials/partial_create_step_2_worked_hours'); ?>
                        <!-- Company pay -->
                        <?php $this->load->view('payroll/partials/partial_create_step_2_companies_pay'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>