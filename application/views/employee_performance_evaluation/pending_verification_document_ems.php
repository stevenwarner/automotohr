<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <a href="<?=base_url('employee_management_system');?>" class="btn btn-info csRadius5">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Dashboard
                </a>
            </div>
        </div>
        <br>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="dashboard-conetnt-wrp">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <!-- / -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h4 class="alert alert-default" style="padding: 0">
                                                    <strong>Employee pending Performance verification section.</strong>
                                                </h4>
                                                <hr>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <div role="tabpanel" id="js-main-page">
                                            <!-- Employee, Applicant boxes -->
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <!-- Tab panes -->
                                                    <div class="tab-content">
                                                        <!-- Employee Box -->
                                                        <div role="tabpanel" class="tab-pane active" id="employee-box">
                                                        <?php if (!empty($pendingDocuments)) { ?>
                                                                <div class="table-responsive full-width table-outer">
                                                                    <table class="table table-bordered">
                                                                        <caption></caption>
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col" class="col-lg-3">Employee Name</th>
                                                                                <th scope="col" class="col-lg-4">Section</th>
                                                                                <th scope="col" class="col-lg-2 text-center">Assigned Date</th>
                                                                                <th scope="col" class="col-lg-2 text-center">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach ($pendingDocuments as $document) { ?>
                                                                                <tr class="">
                                                                                    <td class="col-lg-4">
                                                                                        <?php 
                                                                                            echo "<b>(".getUserNameBySID($document['employee_sid']).")</b>";
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-3">
                                                                                        <?php
                                                                                            if ($document['section'] == 1) {
                                                                                                echo "Section One";
                                                                                            }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-2  text-center">
                                                                                        <?php
                                                                                            if (isset($document['created_at']) && $document['created_at'] != '0000-00-00 00:00:00') {
                                                                                                echo formatDateToDB($document['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                                            }
                                                                                        ?>
                                                                                    </td> 
                                                                                    <td class="col-lg-2 text-center">
                                                                                        <a class="btn btn-info csRadius5" target="_blank" href="<?php echo  base_url('fillable/epe/verification/documents').'/'.$document['employee_sid'].'/'.$document['section']; ?>">
                                                                                            View
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            <?php } else { ?>
                                                                <h1 class="section-ttile text-center"> No Verification Performance Document Pending </h1>   
                                                            <?php } ?>
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
    </div>
</div>