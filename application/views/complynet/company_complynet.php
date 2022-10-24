<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <!--  -->
                <?php $this->load->view('loader', ['props' => 'id="jsPayrollLoader"']); ?>
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <?php $function_names = array('my_profile', 'login_password', 'my_referral_network', 'order_history', 'list_packages_addons_invoices', 'cc_management', 'job_products_report'); ?>
                        <?php if (check_access_permissions_for_view($security_details, $function_names)) { ?>
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php echo $title; ?>                                <a class="btn btn-success pull-right" href="<?php echo base_url("manage_admin/complynet/report") ?>"><i class="fa fa-bar-chart"></i> Report</a>
</span>

                            </div>
                        <?php } ?> <br> <br>

                       




                        <!-- Company Selection -->
                        <div class="row" style="display: none;">
                            <div class="col-md-12 col-sm-12">
                                <label>Select Company <span class="text-danger">*</span></label>
                                <select id="jsParentCompany" style="width: 100%">
                                    <option value="0">[Please Select]</option>
                                    <?php if ($companies) :
                                        foreach ($companies as $company) : ?>
                                            <option value="<?= $company['sid']; ?>">
                                                <?= $company['CompanyName']; ?>
                                            </option>
                                    <?php endforeach;
                                    endif; ?>
                                </select>
                            </div>
                        </div>
                        <!-- Loader -->
                        <?php $this->load->view('loader', ['props' => 'id="jsManageComplyNet"']); ?>

                        <!-- Main Content area -->
                        <div class="jsContentArea hidden">
                            <hr />
                            <!-- Company Information -->
                            <div class="panel panel-success companyInfo">
                                <div class="panel-heading">
                                    <h4 style="padding: 0; margin: 0;"><strong>Basic Information</strong></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <caption></caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">AutomotoHR's Company</th>
                                                            <th scope="col">ComplyNet's Company</th>
                                                            <th scope="col">Linked At</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="csVm">
                                                                <strong>Glockner</strong> <br>
                                                                <span>Id: 1256</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <strong>Glockner COM</strong><br />
                                                                <span>Id: 1234-4567-91236-98745</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <span>Oct 20th, Wednesday, 2022</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <strong class="text-success">ACTIVE</strong>
                                                            </td>
                                                            <td class="csVm">
                                                                <button class="btn btn-success">View</button>
                                                                <button class="btn btn-warning">Edit</button>
                                                                <button class="btn btn-danger">Disable</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="panel panel-success locationInfo">
                                <div class="panel-heading">
                                    <h4 style="padding: 0; margin: 0;"><strong>Locations Information</strong></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <button class="btn btn-success jsLinkLocation">Link Location</button>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <caption></caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">AutomotoHR's Location</th>
                                                            <th scope="col">ComplyNet's Location</th>
                                                            <th scope="col">Linked At</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="csVm">
                                                                <strong>PO BOX 123, Street 1, California, USA</strong>
                                                                <br>
                                                                <span>Id: 3596</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <strong>PO BOX 123, Street 1, California, USA</strong>
                                                                <br />
                                                                <span>Id: 1234-4567-91236-98745</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <span>Oct 20th, Wednesday, 2022</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <strong class="text-success">ACTIVE</strong>
                                                            </td>
                                                            <td class="csVm">
                                                                <button class="btn btn-warning">Edit</button>
                                                                <button class="btn btn-danger">Delete</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Departments Information -->
                            <div class="panel panel-success departmentInfo">
                                <div class="panel-heading">
                                    <h4 style="padding: 0; margin: 0;"><strong>Departments Information</strong></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <button class="btn btn-success jsAddDepartment">Link Department</button>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <caption></caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">AutomotoHR's Department</th>
                                                            <th scope="col">ComplyNet's Department</th>
                                                            <th scope="col">Linked At</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="csVm">
                                                                <strong>PO BOX 123, Street 1, California, USA</strong>
                                                                <br>
                                                                <span>Id: 3596</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <strong>PO BOX 123, Street 1, California, USA</strong>
                                                                <br />
                                                                <span>Id: 1234-4567-91236-98745</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <button class="btn btn-warning">Edit</button>
                                                                <button class="btn btn-danger">Delete</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- JobRole Information -->
                            <div class="panel panel-success jobRoleInfo">
                                <div class="panel-heading">
                                    <h4 style="padding: 0; margin: 0;"><strong>Job Role Information</strong></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <button class="btn btn-success jsAddJobRole">Link Job Role</button>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <caption></caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">AutomotoHR's Job Role</th>
                                                            <th scope="col">ComplyNet's Job Role</th>
                                                            <th scope="col">Linked At</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="csVm">
                                                                <strong>John Doe (Employee)</strong>
                                                                <br>
                                                                <span>Id: 3596</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <strong>John Doe</strong>
                                                                <br />
                                                                <span>Id: 1234-4567-91236-98745</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <button class="btn btn-warning">Edit</button>
                                                                <button class="btn btn-danger">Disable</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Employees Information -->
                            <div class="panel panel-success jobEmployeeInfo">
                                <div class="panel-heading">
                                    <h4 style="padding: 0; margin: 0;"><strong>Employees Information</strong></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <button class="btn btn-success jsAddEmployee">Link Employee</button>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <caption></caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">AutomotoHR's Employee</th>
                                                            <th scope="col">ComplyNet's Employee</th>
                                                            <th scope="col">Linked At</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="csVm">
                                                                <strong>John Doe (Employee)</strong>
                                                                <br>
                                                                <span>Id: 3596</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <strong>John Doe</strong>
                                                                <br />
                                                                <span>Id: 1234-4567-91236-98745</span>
                                                            </td>
                                                            <td class="csVm">
                                                                <button class="btn btn-danger ">Disable</button>
                                                            </td>
                                                        </tr>
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
    </div>
</div>


<link rel="stylesheet" href="<?= base_url('assets/css/SystemModel.css'); ?>">
<style>
    .csVm {
        vertical-align: middle !important;
    }
</style>
<script>
    window.companySid = '<?php echo $company_sid ?>';
</script>
<!--  -->
<script type="text/javascript" src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/SystemModal.js'); ?>"></script>
<script src="<?= base_url(_m('assets/2022/js/complynet/company', 'js', time())); ?>"></script>