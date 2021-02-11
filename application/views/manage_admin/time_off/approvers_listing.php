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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Time Off</h1>
                                    </div>
                                    <!-- Search Table Start -->
                                    <!-- Search Table End -->
                                    <!-- Email Logs Start -->
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Company Time Off Approvers</h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                               <div class="col-xs-12">
                                                    <span class="pull-right">
                                                        <button class="btn btn-success" onclick="window.location.href='<?php echo base_url('manage_admin/manage_policies/'.$company_sid); ?>' ">Manage Polices</button>
                                                        <button class="btn btn-success" onclick="window.location.href='<?php echo base_url('manage_admin/manage_time_off_icons/'.$company_sid); ?>' ">Manage Icons</button>
                                                        <!-- <button class="btn btn-success" onclick="window.location.href='<?php echo base_url('manage_admin/time_off/time_off_settings/'.$company_sid); ?>' ">Settings</button> -->
                                                       <button class="btn btn-black" onclick="window.location.href='<?php echo base_url('manage_admin/company_module/1') ?>' ">Back</button>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <!-- <div class="row">
                                                <div class="col-xs-12">
                                                    <button class="btn btn-success add_approver pull-right" data-cid="<?php echo $company_sid; ?>">Add Approver</button>
                                                </div>
                                            </div> -->        
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead class="heading-grey js-table-head">
                                                                <tr>
                                                                    <th scope="col">Approver</th>
                                                                    <th scope="col">Approve Percentage</th>
                                                                    <th scope="col">Department / Team</th>
                                                                    <th scope="col">Created On</th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(!empty($company_approvers)) { ?>
                                                                <?php foreach ($company_approvers as $approver) { ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="employee-info">
                                                                                <figure>
                                                                                    <?php 
                                                                                        if (!empty($approver['profile_picture'])) {
                                                                                            $image_path = 'https://automotohrattachments.s3.amazonaws.com/'.$approver['profile_picture'];
                                                                                        } else {
                                                                                            $image_path = 'http://localhost/ahr/assets/images/img-applicant.jpg';
                                                                                        }
                                                                                    ?>
                                                                                    <img src="<?php echo $image_path; ?>" class="img-circle emp-image" />
                                                                                </figure>
                                                                                <div class="text">
                                                                                    <h4><?php echo $approver['employee_name']; ?></h4>
                                                                                    <p><?php echo $approver['employee_role']; ?></p>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="text">
                                                                                <?php if($approver['approver_percentage'] == 1) { ?>
                                                                                    <p>100%</p>
                                                                                <?php } else { ?> 
                                                                                    <p>50%</p>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="text">
                                                                                <p><?php echo ucwords($approver['department_team_name']); ?></p>
                                                                            </div>
                                                                        </td>
                                                                        <td><?= date_with_time($approver['created_at']); ?></td>
                                                                        <td>
                                                                            <label class="switch">
                                                                                <input type="checkbox" class="manage_approver" id="<?php echo $company_sid.'_'.$approver['sid']; ?>" data-appid="<?php echo $approver['sid']; ?>" data-cid="<?php echo $company_sid; ?>"  data-employee-name="<?php echo $approver['employee_name']; ?>" <?php echo $approver['is_archived'] != 1 ? 'checked' : ''; ?> >
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                            <br/>
                                                                            <!-- <a href="javascript:void(0)" data-toggle="tooltip" title="Edit Approver" class="action-edit js-edit-row-btn">   <i class="fa fa-pencil-square-o fa-fw icon_blue"></i>
                                                                            </a> -->
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php }  else { ?>
                                                                <tr>
                                                                    <td class="text-center" colspan="5">
                                                                        <span class="no-data">No Approvers Found</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-right">
                                                       <button class="btn btn-black" onclick="window.location.href='<?php echo base_url('manage_admin/company_module/1') ?>' ">Back</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Email Logs End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Approver -->
<div id="approver_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="approver_modal_title"></h4>
            </div>
            <div id="approver_modal_body" class="modal-body">
                <!-- <div class="loader" id="add_form_upload_document_loader" style="display: none;">
                    <i class="fa fa-spinner fa-spin"></i>
                </div> -->
              
                <div class="row margin-top">
                    <div class="col-sm-12">
                        <label>Approver <span class="cs-required">*</span></label>
                        <div class="">
                            <select class="invoice-fields" id="js-employee-add"></select>
                        </div>
                    </div>
                </div>

                <div class="row margin-top">
                    <div class="col-sm-12">
                        <label>Select type<span class="cs-required">*</span></label>
                        <div style="margin-top: 5px;">
                            <label class="control control--radio">
                                Department &nbsp;&nbsp;
                                <input type="radio" name="js-is-department-add" value="1" />
                                <div class="control__indicator"></div>
                            </label>
                            <label class="control control--radio">
                                Team
                                <input type="radio" name="js-is-department-add" value="0" />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row margin-top js-department-row-add" style="display: none;">
                    <div class="col-sm-12">
                        <label>Departments <span class="cs-required">*</span></label>
                        <div class="">
                            <select class="invoice-fields" id="js-departments-add" multiple="true"></select>
                        </div>
                    </div>
                </div>

                <div class="row margin-top js-team-row-add" style="display: none;">
                    <div class="col-sm-12">
                        <label>Teams <span class="cs-required">*</span></label>
                        <div class="">
                            <select class="invoice-fields" id="js-teams-add" multiple="true"></select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="checkbox-styling">
                            <label class="control control--checkbox">
                                <input type="checkbox" id="js-approve-100-percent-add" />
                                Can approve 100%
                                <span class="control__indicator"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="checkbox-styling">
                            <label class="control control--checkbox">
                                <input type="checkbox" id="js-archive-check-add" />
                                Deactivate
                                <span class="control__indicator"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 button_section">
                        <button type="button" class="btn btn-success" id="js-submit-approver-form-btn">APPLY</button>
                        <button type="button" class="btn btn-black js-view-page-btn" data-dismiss="modal" aria-label="Close">CANCEL</button>
                        <button type="button" class="btn btn-success pull-right manage_my_team">Manage Team</button>
                    </div>
                </div>
            </div>
            <div id="approver_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<style>
    .checkbox-styling label {
        display: block;
        float: left;
        font-weight: 500;
        font-size: 16px;
        margin-top: 20px;
    }

    .control {
        display: inline-block;
        position: relative;
        padding-left: 30px;
        cursor: pointer;
        font-size: 14px;
        width: auto !important;
    }

    .button_section {
        margin-top: 20px;
    }

    .add_approver{
        margin-bottom: 6px;
    }

    .icon_blue {
        color: green;
        text-shadow: 1px 1px 1px #ccc;
        font-size: 2em;
    }

    .btn-black {
        color: #fff;
        background: #000;
    }

    .btn-black:hover {
        color: #fff;
        background: #686868;
    }

    .employee-info figure img {
        border-radius: 3px !important;
    }
    .employee-info figure img {
        width: 100%;
        height: 100%;
        border-radius: 100%;
    }

    .img-circle {
        max-width: 100%;
        height: auto;
        display: block;
    }

    .employee-info figure {
        width: 50px !important;
        height: 50px !important;
    }

    .employee-info figure {
        float: left;
        width: 50px;
        height: 50px;
        border-radius: 100%;
        border: 1px solid #ddd;
    }

    .employee-info .text {
        margin: 0 0 0 60px;
    }
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }

    .switch input { 
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: green;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
</style>

<script>
    $('.manage_approver').on('click', function () {
        var approver_sid = $(this).attr('data-appid');
        var company_sid = $(this).attr('data-cid');
        var employee_name = $(this).attr('data-employee-name');
        var input_id = $(this).attr('id');
        if($(this).prop('checked') == true){
            request_handler(approver_sid,company_sid,1, employee_name);
        } else {
            alertify.confirm('Confirmation', 'Are you sure you want to deactivate "<b>'+employee_name+'</b>" approver?',
                function () {
                    request_handler(approver_sid,company_sid,0, employee_name);
                },
                function () {
                    $("#"+input_id).prop('checked', true);
                }
            );
        }
    });

    function request_handler (approver_sid, company_sid, is_archived, title) {
        var baseURI = "<?=base_url();?>manage_admin/time_off/handler";

        var formData = new FormData();
        formData.append('approver_sid', approver_sid);
        formData.append('company_sid', company_sid);
        formData.append('is_archived', is_archived);
        formData.append('action', 'change_approver_status');

        $.ajax({
            url: baseURI,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false
        }).done(function(resp){
            var successMSG = 'You have successfully activated <b>"'+title+'"</b>.';
            if(status == 0){
                successMSG = 'You have successfully deactivated <b>"'+title+'"</b>.'; 
            }

            alertify.alert('SUCCESS!', successMSG, function(){
                    
            });
        });
    }

    $(".add_approver").on('click', function () {
        $("#approver_modal_title").text('Add Approver');
        // $("#approver_modal").modal({show:true});
        $('#approver_modal').appendTo("body").modal('show');
    });
</script>
