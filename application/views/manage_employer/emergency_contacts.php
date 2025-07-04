
<?php

//$load_view = 'old';
//
//if ($this->session->userdata('logged_in')) {
//    if (!isset($session)) {
//        $session = $this->session->userdata('logged_in');
//    }
//    $access_level = $session['employer_detail']['access_level'];
//
//    if ($access_level == 'Employee') {
//        $load_view = 'new';
//    }
//}

?>
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
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <button 
                                            class="btn btn-success JsSendReminderEmailLI form-control"
                                            data-id="<?=$user_sid;?>"
                                            data-type="<?=$user_type;?>"
                                            data-slug="emergency-contact"
                                        >Send An Email Reminder</button>
                                    </div>
                                    <div class="col-sm-8">
                                        <span class="pull-right" id="jsGeneralDocumentArea"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <br />
                                <div class="table-responsive table-outer">
                                    <?php if ($emergency_contacts) { ?>
                                        <?php echo form_open('', array('id' => 'loginform')); ?>
                                        <div class="table-wrp">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th width="30%">Name</th>
                                                        <th>Relationship</th>
                                                        <th>Phone Number</th>
                                                        <th>Address</th>
                                                        <th>Priority</th>
                                                        <th width="1%" colspan="2" class="last-col">Actions</th>
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($emergency_contacts as $emergency_contact) { ?>
                                                        <tr>
                                                            <td width="30%"><?php echo $emergency_contact['first_name'] . ' ' . $emergency_contact['last_name']; ?></td>
                                                            <td><?php echo $emergency_contact['Relationship']; ?></td>
                                                            <td><?=$emergency_contact['PhoneNumber']; ?></td>
                                                            <td><?php echo $emergency_contact['Location_Address']; ?></td>
                                                            <td><?php echo $emergency_contact['priority']; ?></td>
                                                            <?php if($type == 'employee'){?>
                                                                <td><a class="action-btn" href="<?php echo base_url('edit_emergency_contacts') . '/' . $emergency_contact['sid'] . '/employee/' . $emp_app_sid; ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                        <span class="btn-tooltip">Edit</span>
                                                                    </a>
                                                                </td>
                                                            <?php } elseif($type == 'applicant'){?>
                                                                <td><a class="action-btn" href="<?php echo base_url('edit_emergency_contacts') . '/' . $emergency_contact['sid'] . '/applicant/' . $emp_app_sid . '/' .$job_list_sid; ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                        <span class="btn-tooltip">Edit</span>
                                                                    </a>
                                                                </td>
                                                            <?php }?>
                                                            <td>
                                                                <a class="action-btn clone-job remove delete-contact" data-attr="<?= $type;?>" data-id="<?= $emergency_contact['sid'];?>" data-key="<?= $emp_app_sid;?>" href="javascript:;" id="">
                                                                    <i class="fa fa-remove"></i>
                                                                    <span class="btn-tooltip">Delete</span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>                                            
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php echo form_close(); ?>
                                    <?php } else { ?>
                                        <div class="no-job-found">
                                            <ul>
                                                <li>
                                                    <h3 style="text-align: center;">Emergency Contacts not found! </h3>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>                        
                                </div>
                                <?php if ($emergency_contacts_count < 3) { ?>
                                    <div class="btn-panel">  
                                        <?php if ($this->uri->segment(2) != null && $this->uri->segment(3) != null && $this->uri->segment(4) != null) { ?>
                                            <a class="delete-all-btn active-btn" id="ej_active" href="<?php echo base_url('add_emergency_contacts') . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/' . $this->uri->segment(4); ?>">+ Add Emergency Contact</a>
                                        <?php } else { ?>
                                            <a class="delete-all-btn active-btn" id="ej_active" href="<?php echo base_url('add_emergency_contacts') . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3); ?>">+ Add Emergency Contact</a>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
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
    $(document).on('click','.delete-contact',function(){
        var type = $(this).attr('data-attr');
        var id = $(this).attr('data-id');
        var key = $(this).attr('data-key');

        alertify.confirm(
            'Confirm Deletion!',
            'Are you sure, You want to delete this Emergency Contact?',
            function(){
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('emergency_contacts/ajax_handler');?>',
                    data:{
                        users_type:  type,
                        users_sid:   key,
                        contact_sid: id
                    },
                    success: function(response){
                        if(response == 'deleted'){
                            window.location.href = window.location.href;
                        }else{
                            alertify.error('Unauthorized!');
                        }
                    },
                    error: function(){
                        alertify.error('Something went wrong');
                    }
                })
            },
            function (){
                alertify.error('Cancelled');
            }
        )
    });
</script>

<?php }  else if ($load_view == 'new') { ?>
    <?php $this->load->view('onboarding/emergency_contacts'); ?>
<?php } ?>

<?php $this->load->view('hr_documents_management/general_document_assignment_single', [
    'generalActionType' => 'emergency_contacts',
    'companySid' => $company_sid,
    'userSid' => $user_sid,
    'userType' => $user_type
]);?>