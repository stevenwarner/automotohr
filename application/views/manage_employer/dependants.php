<?php if (!$load_view) { ?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <button 
                                            class="btn btn-success JsSendReminderEmailLI form-control"
                                            data-id="<?=$user_sid;?>"
                                            data-type="<?=$user_type;?>"
                                            data-slug="dependents"
                                        >Send An Email Reminder</button>
                                </div>
                                <div class="col-sm-8">
                                    <span class="pull-right" id="jsGeneralDocumentArea"></span>
                                </div>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                            <br />
                                <div class="table-responsive table-outer">
                                    <?php if ($emergency_contacts) { ?>
                                        <div class="table-wrp">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <!--<th><input name="" type="checkbox" value="" id="selectall"></th>-->
                                                        <th width="30%">Name</th>
                                                        <th>Phone Number</th>
                                                        <th>Address</th>
                                                        <th>Relationship</th>
                                                        <th width="1%" colspan="2" class="last-col">Actions</th>
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($emergency_contacts as $emergency_contact) { ?>
                                                        <tr>
                                                            <!--<td> <input name="ej_active[]" type="checkbox" value="<?php //echo $emergency_contact['sid'];         ?>" id="<?php //echo $emergency_contact['sid'];         ?>"  class="checkbox1"></td>-->
                                                            <td width="30%"><?php echo $emergency_contact['first_name'] . ' ' . $emergency_contact['last_name']; ?></td>
                                                            <td><?=$emergency_contact['phone'];?></td>
                                                            <td><?php echo $emergency_contact['address']; ?></td>
                                                            <td><?php echo $emergency_contact['relationship']; ?></td>
                                                            <td>
                                                                <?php if($type == 'employee') {?>
                                                                    <a class="btn btn-success btn-block btn-sm" href="<?php echo base_url('edit_dependant_information') . '/' . $emergency_contact['sid'] . '/employee/' . $sid; ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                                <?php }elseif($type == 'applicant'){?>
                                                                    <a class="btn btn-success btn-block btn-sm" href="<?php echo base_url('edit_dependant_information') . '/' . $emergency_contact['sid'] . '/applicant/' . $sid . '/' .$job_list_sid; ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                                <?php }?>
                                                            </td>
                                                            <td>
                                                                <form id="form_delete_dependent_<?php echo $emergency_contact['sid']; ?>" method="post" action="<?php echo current_url(); ?>" enctype="multipart/form-data">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="delete_dependent" />
                                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $emergency_contact['company_sid']; ?>" />
                                                                    <input type="hidden" id="users_type" name="users_type" value="<?php echo $emergency_contact['users_type']; ?>" />
                                                                    <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $emergency_contact['users_sid']; ?>" />
                                                                    <input type="hidden" id="dependent_sid" name="dependent_sid" value="<?php echo $emergency_contact['sid']; ?>" />
                                                                    <button type="button" class="btn btn-danger btn-block btn-sm" onclick="func_delete_dependent(<?php echo $emergency_contact['sid']; ?>);">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>                                            
                                                </tbody>
                                            </table>
                                        </div>

                                    <?php } else { ?>
                                        <div class="no-job-found">
                                            <ul>
                                                <li>
                                                    <h3 style="text-align: center;">Dependent not found! </h3>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>                        
                                </div>
                                <div class="btn-panel">  
                                    <?php if ($this->uri->segment(2) != null && $this->uri->segment(3) != null) { ?>
                                        <a class="delete-all-btn active-btn" id="ej_active" href="<?php echo base_url('add_dependant_information') . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/' . $this->uri->segment(4); ?>">+ Add Dependent</a>
                                    <?php } else { ?>
                                        <a class="delete-all-btn active-btn" id="ej_active" href="<?php echo base_url('add_dependant_information'); ?>">+ Add Dependant</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>

            </div>


        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript">

    function func_delete_dependent(dependent_sid) {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you sure you want to delete this dependent?',
            function () {
                $('#form_delete_dependent_' + dependent_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function validate_form() {
        $("#loginform").validate({
            ignore: ":hidden:not(select)",
            rules: {
                username: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\-]+$/
                },
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                username: {
                    required: 'Username is required',
                    pattern: 'Please provide valid username'
                },
                email: {
                    required: 'Please provide Valid email'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
</script>

<?php } else { ?>
    <?php $this->load->view('onboarding/dependents'); ?>
<?php } ?>

<?php $this->load->view('hr_documents_management/general_document_assignment_single', [
    'generalActionType' => 'dependents',
    'companySid' => $company_sid,
    'userSid' => $user_sid,
    'userType' => $user_type
]);?>