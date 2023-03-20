<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo ucwords($company_name); ?> - <?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Company</a>
                                    </div>

                                </div>
                            </div>
                            <br>
                          
                            <div class="row">
                            <form id="form_adp_report"  method="post" action="<?php echo current_url(); ?>">
                                <div class="col-sm-10">
                                    <label>Select a Company <strong class="text-danger">*</strong></label> <br>
                                    <select id="jsCompany" name="companySid" style="width: 100%;">
                                        <option value="0">[Select a Company]</option>
                                        <?php foreach ($companies as $company): ?>
                                            <option value="<?=$company['sid'];?>"><?=$company['CompanyName'];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                              </form>
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-2 text-right">
                                        <br />
                                        <button class="btn btn-success jsStartProcess">Get Report</button>
                                    </div>
                                </div>
                            </div>
                            
                        <br>

                            <div class="row">

                    <?php if($companysid !=0){?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <strong> Employees</strong>
                                </div>

                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#onadp"><b>On ADP</b></a></li>
                                    <li><a data-toggle="tab" href="#offadp"><b>Off ADP</b></a></li>
                                </ul>

                                <div class="tab-content">
                                    <div id="onadp" class="tab-pane fade in active">
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <caption></caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Name / Email</th>
                                                            <th scope="col">Associate ID</th>
                                                            <th scope="col">DateTime</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($employees)) {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4">
                                                                <p class="alert alert-info text-center">
                                                                    No employees on ADP yet.
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        } else { ?>

                                                        <?php foreach ($employees as $employee) {
                                                            ?>
                                                        <tr data-id="<?=$employee['sid']?>">
                                                            <td>
                                                                <?php
                                                                        echo '<strong>' . remakeEmployeeName($employee) . '</strong><br />';
                                                                        echo $employee['email'];
                                                                        ?>
                                                            </td>
                                                            <td><?= $employee['associate_oid']; ?></td>
                                                            <td><?= formatDateToDB($employee['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                            </td>
                                                          
                                                        </tr>
                                                        <?php
                                                            } ?>
                                                        <?php }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="offadp" class="tab-pane fade">
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <caption></caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Name / Email</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($offADPEmployees as $emp) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                    echo '<strong>' . remakeEmployeeName($emp) . '</strong><br />';
                                                                    echo $emp['email'];
                                                                    ?>
                                                            </td>
                                                            <td>
                                                                
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-success jsSyncSingleEmployee"
                                                                    data-id="<?= $emp['sid']; ?>">Add On ADP</button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    <?php } ?>
                        </div>

                        </div>

                    </div>
                </div>
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



<script>

  $("#jsCompany").select2();
  $("#jsCompany").val('<?php echo $companysid ;?>').select2();;

  $(".jsStartProcess").click(function (event) {
        //
        event.preventDefault();
        //
        companyId = parseInt($("#jsCompany").val().trim());
        //
        if (companyId === 0) {
            return alertify.alert("Please select a company.");
        }else{
            $('#loader_text_div').text('Processing');
            $('#document_loader').show();
            $('#form_adp_report').submit();
        }
       
    });

</script>