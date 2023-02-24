<style>
    /* */
    .cs-tab li > a{ color: #000000; }
    .cs-tab li.active > a{ background-color: #3554dc !important; color: #ffffff !important; }
    .cs-applicant-box i{ position: absolute; top: 50%; right: 30px; font-size: 20px; margin-top: -16px; color: #3554dc; }
    .cs-custom-input{ margin-bottom: 10px; }
    .cs-custom-input input{ height: 40px;}
    .cs-custom-input .input-group-addon{ background: 0; padding: 0; border: none; }
    .cs-custom-input .input-group-addon > input{ margin: 0; border-radius: 0; }
    .cs-error, .cs-required{ font-weight: bolder; color: #cc0000; }
    .cs-dropzone{ position: relative; display: inline-block; width: 100%; }
    .cs-drag-overlay{ position: absolute; top: 0; bottom: 0; left: 0; right: 0; width: 100%; background-color:  rgba(255,255,255,.7); z-index: 10; display: none; }
    .cs-drag-overlay p{ line-height: 40px; font-size: 18px; }
    .select2-container--default .select2-selection--single{ border: 1px solid #aaaaaa !important;  padding: 3px 5px !important; }
</style>
<br />
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container">
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
                                                    <strong>Employee / Applicant pending employer section.</strong>
                                                </h4>
                                                <hr>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <div role="tabpanel" id="js-main-page">
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs cs-tab js-tab" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#employee-box" aria-controls="tab" role="tab" data-toggle="tab">Employees (<?=count($employee_pending);?>)</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#applicant-box" aria-controls="home" role="tab" data-toggle="tab">Applicants (<?=count($applicant_pending);?>)</a>
                                                </li>
                                            </ul>

                                            <br />

                                            <!-- Employee, Applicant boxes -->
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <!-- Tab panes -->
                                                    <div class="tab-content">
                                                        <!-- Employee Box -->
                                                        <div role="tabpanel" class="tab-pane active" id="employee-box">
                                                            <?php if (!empty($employee_pending)) { ?>
                                                                <div class="table-responsive full-width table-outer">
                                                                    <table class="table  table-striped table-condensed table-bordered">
                                                                        <caption></caption>
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col" class="col-lg-3">Employee Name</th>
                                                                                <th scope="col" class="col-lg-4">Document Name</th>
                                                                                <th scope="col" class="col-lg-2 text-center">Assigned Date</th>
                                                                                <th scope="col" class="col-lg-2 text-center">Filled Date</th>
                                                                                <th scope="col" class="col-lg-2 text-center">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach ($employee_pending as $document) { ?>
                                                                                <tr class="">
                                                                                    <td class="col-lg-4">
                                                                                        <?php 
                                                                                            $user_type = '';
                                                                                            $user_name = '';
                                                                                            if ($document['user_type'] == 'applicant') {
                                                                                                $user_type = 'Applicant';
                                                                                                $user_name = get_applicant_name($document['user_sid']);
                                                                                            } else {
                                                                                                $user_type = 'Employee';        
                                                                                                $user_name = getUserNameBySID($document['user_sid']);
                                                                                            }
                                                                                            echo $user_name ."<br /> <b>(".$user_type.")</b>";
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-3">
                                                                                        <?php
                                                                                            echo $document['document_name'] . '&nbsp; <br />';
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-2  text-center">
                                                                                        <?php
                                                                                            if (isset($document['sent_date']) && $document['sent_date'] != '0000-00-00 00:00:00') {
                                                                                                echo reset_datetime(array('datetime' => $document['sent_date'], '_this' => $this));
                                                                                            }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-2  text-center">
                                                                                        <?php
                                                                                            if (isset($document['filled_date']) && $document['filled_date'] != '0000-00-00 00:00:00') {
                                                                                                echo reset_datetime(array('datetime' => $document['filled_date'], '_this' => $this));
                                                                                            }
                                                                                        ?>
                                                                                    </td>  
                                                                                    <td class="col-lg-2">
                                                                                        <a class="btn btn-success" target="_blank" href="<?php echo  base_url('hr_documents_management/documents_assignment/employee').'/'.$document['user_sid']; ?>">
                                                                                            View
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            <?php } else { ?>
                                                                <h1 class="section-ttile text-center"> No Employer Section Pending </h1>   
                                                            <?php } ?>
                                                        </div>
                                                        <!-- Applicant Box -->
                                                        <div role="tabpanel" class="tab-pane cs-applicant-box" id="applicant-box">
                                                            <?php if (!empty($applicant_pending)) { ?>
                                                                <div class="table-responsive full-width table-outer">
                                                                    <table class="table table-striped table-condensed table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="col-lg-3">Applicant Name</th>
                                                                                <th class="col-lg-4">Document Name</th>
                                                                                <th class="col-lg-2 text-center">Assigned Date</th>
                                                                                <th class="col-lg-2 text-center">Filled Date</th>
                                                                                <th class="col-lg-2 text-center">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach ($applicant_pending as $document) { ?>
                                                                                <tr class="">
                                                                                    <td class="col-lg-4">
                                                                                        <?php 
                                                                                            $user_type = '';
                                                                                            $user_name = '';
                                                                                            if ($document['user_type'] == 'applicant') {
                                                                                                $user_type = 'Applicant';
                                                                                                $user_name = get_applicant_name($document['user_sid']);
                                                                                            } else {
                                                                                                $user_type = 'Employee';        
                                                                                                $user_name = getUserNameBySID($document['user_sid']);
                                                                                            }
                                                                                            echo $user_name ."<br /> <b>(".$user_type.")</b>";
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-3">
                                                                                        <?php
                                                                                            echo $document['document_name'] . '&nbsp; <br />';
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-2  text-center">
                                                                                        <?php
                                                                                            if (isset($document['sent_date']) && $document['sent_date'] != '0000-00-00 00:00:00') {
                                                                                                echo reset_datetime(array('datetime' => $document['sent_date'], '_this' => $this));
                                                                                            }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-2  text-center">
                                                                                        <?php
                                                                                            if (isset($document['filled_date']) && $document['filled_date'] != '0000-00-00 00:00:00') {
                                                                                                echo reset_datetime(array('datetime' => $document['filled_date'], '_this' => $this));
                                                                                            }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="col-lg-2">
                                                                                        <a class="btn btn-success" target="_blank" href="<?php echo  base_url('hr_documents_management/documents_assignment/applicant').'/'.$document['user_sid']; ?>">
                                                                                            View
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            <?php } else { ?>
                                                                <h1 class="section-ttile text-center"> No Employer Section Pending </h1>   
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


<style>
    .btn-success{
        background-color: #3554dc;
    }
</style>