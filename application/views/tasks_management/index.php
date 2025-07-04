<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <div class="message-action">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <div class="hr-items-count">
                                    <strong class="messagesCounter">&nbsp;</strong>
                                </div> 
                            </div>                            
                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                <div class="message-action-btn">
                                    <a class="submit-btn" href="<?php echo base_url('task_management/assign_applicant') ?>" >Assign Applicant</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hr-box">
                        <div class="hr-box-header">
                            <strong>Applicants Assigned by Me</strong>
                        </div>
                        <div class="hr-innerpadding">
                            <div class="table-responsive table-outer">
                                <div class="table-wrp">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Applicant Name</th>
                                                <th>Assigned To</th>
                                                <th>Assigned Date</th>
                                                <th class="message-btn">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php   if (!empty($applicants_assigned_by_me)) { ?>
                                            <?php   foreach ($applicants_assigned_by_me as $assigned_by_me) { ?>
                                                        <tr id="parent_<?=$assigned_by_me['sid']?>">
                                                            <td><!--<a href="<?php //echo base_url('/applicant_profile/' . $assigned_by_me['applicant_sid']); ?>" title="View <?php //echo $assigned_by_me['applicant_name']; ?>"><?php //echo $assigned_by_me['applicant_name'];?></a>--><?php echo $assigned_by_me['applicant_name'];?></td>
                                                            <td><?php echo remakeEmployeeName($assigned_by_me);?></td>
                                                            <td><?=reset_datetime(array('datetime' => $assigned_by_me['assigned_date'], '_this' => $this));?></td>
                                                            <td class="message-btn">
                                                                <a href="<?php echo base_url('task_management/details/'.$assigned_by_me['sid']);?>" class="btn btn-success">Details</a>
                                                                <a href="<?php echo base_url('applicant_profile/' . $assigned_by_me['applicant_sid']);?>" title="View <?php echo $assigned_by_me['applicant_name']; ?>" class="btn btn-success"><i class="fa fa-eye"></i></a>
                                                                <a href="javascript:;" onclick="remove_assignment(<?= $assigned_by_me['sid'] ?>)" title="Un-assign <?php echo $assigned_by_me['applicant_name']; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
                                                            </td>
                                                        </tr>
                                            <?php   } ?>
                                            <?php } else { ?>
                                                    <tr>
                                                        <td colspan="4">No Applicants assigned by you</td>
                                                    </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                   </div>
                    <div class="hr-box">
                        <div class="hr-box-header">
                            <strong>Applicants Assigned to Me</strong>
                        </div>
                        <div class="hr-innerpadding">
                            <div class="table-responsive table-outer">
                                <div class="table-wrp">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Applicant Name</th>
                                                <th>Assigned By</th>
                                                <th>Assigned Date</th>
                                                <th class="message-btn">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php   if (!empty($assigned_applicants)) { ?>
                                            <?php       foreach ($assigned_applicants as $assigned_applicant) { ?>
                                                            <tr>
                                                                <td><!--<a href="<?php //echo base_url('applicant_profile/' . $assigned_applicant['applicant_sid']); ?>" title="View <?php //echo $assigned_applicant['applicant_name']; ?>"><?php //echo $assigned_applicant['applicant_name'];?></a>-->
                                                                    <?php echo $assigned_applicant['applicant_name'];?></td>
                                                                <td><?php echo remakeEmployeeName($assigned_applicant );?></td>
                                                                <td><?=reset_datetime(array('datetime' => $assigned_applicant['assigned_date'], '_this' => $this));?></td>
                                                                <td class="message-btn">
                                                                    <a href="<?php echo base_url('task_management/details/'.$assigned_applicant['sid']);?>" class="btn btn-success">Details</a>
                                                                    <a href="<?php echo base_url('applicant_profile/' . $assigned_applicant['applicant_sid']);?>" title="View <?php echo $assigned_applicant['applicant_name']; ?>" class="btn btn-success"><i class="fa fa-eye"></i></a>
                                                                </td>
                                                            </tr>
                                            <?php       } ?>
                                            <?php   } else { ?>
                                                        <tr>
                                                            <td colspan="4">No Applicants assigned to you</td>
                                                        </tr>
                                            <?php   } ?>
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
<script type="text/javascript">
    function remove_assignment(id) {
        url = "<?= base_url() ?>task_management/perform_action";
        alertify.confirm('Confirmation', "Are you sure you want to Un-Assigned this applicant?",
                function () {
                    $.post(url, {action: 'deactivate', sid: id})
                            .done(function (data) {
                                console.log(data);
                                $("#parent_" + id).remove();
                                alertify.success('hello world');
                            });
                },
                function () {
                    alertify.error('Canceled');
                });
    }
</script>