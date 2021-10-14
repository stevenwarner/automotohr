<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Employees
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Add your employees
                        </h1>
                        <p class="csF16">
                            You'll need to gather information from your employeees so we can add them to payroll and set up direct deposit. (If you'd prefer printed paychecks, you can skip the banking information.) Feel free to enter some or all of your employees before proceeding to next step. 
                        </p>
                        <ul style="padding-left: 18px;">
                            <li>Personal information (i.e. name, SSN)</li>
                            <li>Work information (i.e. start data, compensation)</li>
                            <li>Tax detail (i.e. filling status)</li>
                            <li>Banking information (i.e. account number)</li>
                        </ul>
                        <p>
                            Need help? <a href="javascript:;">Find more info here</a>
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-orange csF16 csB7 jsEmployeeOnboardCancel">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsAddCompanyEmployee">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;
                            Add employee
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
