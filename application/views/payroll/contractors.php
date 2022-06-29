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
                            <button class="btn btn-orange" id="jsPayrollContractorAddBtn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Contractor</button>
                        </div>
                    </div>
                <div class="">
                    <span class="pull-left">
                        <h3 class="">Payroll Contractors</h3>
                    </span>
                    <span class="pull-right">
                        <h3 id="jsPayrollContractorCount" class="">Total: 0</h3>
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
                                            <th scope="col" class="text-right">Type</th>
                                            <th scope="col" class="text-right">Wage Type</th>
                                            <th scope="col" class="text-right">Hourly Rate</th>
                                            <th scope="col" class="text-right">EIN</th>
                                            <th scope="col" class="text-right">Business Name</th>
                                            <th scope="col" class="text-right">Self Onboarding</th>
                                            <th scope="col" class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsPayrollContractorBox"></tbody>
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