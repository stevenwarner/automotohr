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
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php echo $title; ?>                                
                                <a class="dashboard-link-btn-right" href="<?php echo base_url("manage_admin/complynet/report") ?>"><i class="fa fa-bar-chart"></i> Report</a>
                            </span>
                        </div>
                        <br> 
                        <br>
                        <!-- Company Selection -->
                        <div class="row jsCompanySection">
                            <div class="col-md-12 col-sm-12">
                                <label>Select Company <span class="text-danger">*</span></label>
                                <select id="jsParentCompany" style="width: 100%">
                                    <option value="0">[Please Select]</option>
                                    <?php if ($company_detail) { ?>
                                        <option value="<?php echo $company_detail['sid']; ?>">
                                            <?php echo $company_detail['CompanyName']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- Loader -->
                        <?php $this->load->view('loader', ['props' => 'id="jsManageComplyNet"']); ?>

                        <!-- Main Content area -->

                        <hr />

                        <!-- Company Information -->
                        <div class="panel panel-success companyBasicInfo">
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
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($complyNetCompanyDetail)) { ?>
                                                        <tr>
                                                            <td class="csVm">
                                                                <strong><?php echo $complyNetCompanyDetail["automotohr_name"]; ?></strong> <br>
                                                                <span>Id: <?php echo $complyNetCompanyDetail["automotohr_id"]; ?></span>
                                                            </td>
                                                            <td class="csVm">
                                                                <strong><?php echo $complyNetCompanyDetail["complynet_name"]; ?></strong><br />
                                                                <span>Id: <?php echo $complyNetCompanyDetail["complynet_id"]; ?></span>
                                                            </td>
                                                            <td class="csVm">
                                                                <span><?php echo $complyNetCompanyDetail["created_at"]; ?></span>
                                                            </td>
                                                            <td class="csVm">
                                                                <?php if ($complyNetCompanyDetail["status"] == 1) { ?>
                                                                    <strong class="text-success">ACTIVE</strong>
                                                                <?php } else { ?> 
                                                                    <strong class="text-warning">DEACTIVE</strong>
                                                                <?php } ?>    
                                                                
                                                            </td>
                                                        </tr>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="text-center" colspan="3">
                                                                <?php echo $company_detail['CompanyName']; ?> not linked with complyNet
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="jsContentArea hidden">

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

<div id="jsCompanyComplyNetLoader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we are getting data...
        </div>
    </div>
</div>


<link rel="stylesheet" href="<?= base_url('assets/css/SystemModel.css'); ?>">
<style>
    .csVm {
        vertical-align: middle !important;
    }
</style>
<!--  -->
<script type="text/javascript" src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/SystemModal.js'); ?>"></script>
<script src="<?= base_url(_m('assets/2022/js/complynet/admin', 'js', time())); ?>"></script>
<!--  -->
<script>
    <?php if (!empty($complyNetCompanyDetail)) { ?>
        $('#jsCompanyComplyNetLoader').show(); 
        //
        $(document).ready(function(){
            var companySid = <?php echo $company_detail['sid']; ?>;
            $("#jsParentCompany").val(companySid ).trigger("change");
            $("#jsParentCompany").select2("enable", false);
            $('#jsCompanyComplyNetLoader').hide(); 
            setTimeout(function(){
                $(".jsCompanySection").addClass('hidden');
            },5000);
        });
    <?php } else { ?> 
        $(".jsCompanySection").addClass('hidden');   
    <?php } ?>    
</script>