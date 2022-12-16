<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <!-- Heading -->
                            <div class="heading-title page-title">
                                <h1 class="page-title" style="width: 100%;"><i class="fa fa-users" aria-hidden="true"></i><?php echo $page_title; ?></h1>
                                <a class="site-btn pull-right" href="<?php echo base_url("manage_admin/complynet/reports") ?>"><i class="fa fa-bar-chart"></i> Report</a>
                            </div>

                            <div class="clearfix"></div>

                            <br />
                            <!-- Company Selection -->
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <label>Select Company <span class="text-danger">*</span></label>
                                    <select id="automotoHRCompany" style="width: 100%">
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


<br>

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                <label>ComplyNet Location <span class="text-danger">*</span></label>
                                    <select id="complyNetLocation" style="width: 100%">
                                        <option value="0">Select Location</option>
                                        <?php foreach ($locations as $locationRow) { ?>
                                            <option value="<?php echo $locationRow['Id']; ?>"><?php echo $locationRow['Name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>





                            <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <div class="col-sm-12 text-right">
                                                <br><button class="btn btn-success jsSaveLinkLocationNew">Save</button> <button class="btn btn-black jsModalCancel">Cancel</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>





                                <!-- Loader Start -->
<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;"></div>
    </div>
</div>
<!-- Loader End -->


                       




                            <!-- Loader -->
                            <?php $this->load->view('loader', ['props' => 'id="jsManageComplyNet"']); ?>

                            <!-- Main Content area -->
                            <div class="jsContentArea hidden">
                                <hr />
                                <!-- Company Information -->
                                <div class="panel panel-success companyInfo hidden">
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
</div>


<script>
    $(document).ready(function() {
        $('#automotoHRCompany').select2();
        $('#complyNetLocation').select2();
    });
</script>



<script type="text/javascript" src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
<script src="<?=base_url('assets/js/SystemModal.js');?>"></script>
<script src="<?=base_url(_m('assets/2022/js/complynet/adminnew', 'js', time()));?>"></script>