<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <!--  -->
            <div class="col-sm-12">
                 <!--  -->
                 <div class="row">
                        <div class="col-xs-12 text-right">
                            <button class="btn btn-orange" id="jsPayrollEmployeeAddBtn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Onboard Employee To Payroll</button>
                        </div>
                    </div>
                <div class="">
                    <span class="pull-left">
                        <h3 class="">Payroll Employees</h3>
                    </span>
                    <span class="pull-right">
                        <h3 id="jsPayrollEmployeesListingCount" class="">Total: 0</h3>
                    </span>
                </div>
                <div class="">
                   
                    <!--  -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Employee</th>
                                            <th scope="col" class="text-right">Payroll Id</th>
                                            <th scope="col" class="text-right">Onboard Status</th>
                                            <th scope="col" class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsPayrollEmployeesListingBox"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add System Model -->
<link rel="stylesheet" href="<?= base_url(_m("assets/css/SystemModel", 'css')); ?>">
<script src="<?= base_url(_m("assets/js/SystemModal")); ?>"></script>