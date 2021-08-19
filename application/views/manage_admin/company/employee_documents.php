<?php ?>
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
                                        <h1 class="page-title"><i class="fa fa-files-o"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/employers/edit_employer')."/".$employee_detail["sid"]; ?>" class="btn black-btn float-right">Back</a>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> 
                                    <div class="heading-title page-title">
                                        <h1 class="page-title">Company Name : <?php echo $companyName; ?></h1>
                                        <br>
                                        <h1 class="page-title">Employee Name : <?php echo $employeeName; ?></h1>
                                    </div> 
                                </div> 
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="panel panel-default" style="position: relative;">
                                        <div class="panel-heading">
                                            <span><strong>General Document(s)</strong></span>
                                        </div>
                                        <div class="panel-body" style="min-height: 200px;">
                                            <!-- Data -->
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Document Name</th>
                                                            <th class="text-center">Assigned On</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <!--  -->
                                                    <tbody id="jsGeneralDocumentBody">
                                                        <?php if (!empty($documents)) { ?>
                                                            <?php foreach ($documents as $key => $document) { ?>
                                                                <tr data-id="dependents" data-key="0">
                                                                    <td>Dependents</td>
                                                                    <td class="text-center jsAssignedOn">
                                                                        <i class="fa fa-times fa-2x text-danger"></i>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button class="btn btn-success jsGeneralDocumentAssign" data-type="assign" title="Assign this document">Assign</button>
                                                                        
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>  
                                                        <?php } else { ?>  
                                                            <tr>
                                                                <td colspan="3" class="text-center">
                                                                    <strong>No document assign yet!</strong>
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
                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
