<?php $this->load->view('main/static_header'); ?>
<body>
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="credit-card-authorization">
                    <div class="top-logo text-center">
                        <img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
                    </div>
                    <!-- page print -->
                        <?php //if($pre_fill != 'pre_fill') { ?>
                        <div class="top-logo" id="print_div">
                            <a href="javascript:;" class="btn btn-success" onclick="print_page('.container');">
                                <i class="fa fa-print" aria-hidden="true"></i> Print or Save
                            </a>
                        </div>
                        <?php //} ?>
                    <!-- page print -->
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>COMPANY CONTACTS FORM</span>
                    <div class="end-user-agreement-wrp recurring-payment-authorization">
                        <form id="form_company_contacts" action="<?php echo base_url('form_company_contacts/' . $verification_key . '/' . $pre_fill); ?>" method="post" enctype="multipart/form-data">
                           <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                               <div class="card-boxes">

                               </div>
                               <div class="card-box-inner" style="min-height:auto; padding:25px 0;">
                                   <div class="card-fields-row">
                                       <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                           <label>Company Name</label>
                                       </div>
                                       <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                           <input type="text" class="invoice-fields" name="company_name" id="company_name" value="<?php echo set_value('company_name', $document_record['company_name']); ?>" <?php echo $readonly; ?> />
                                       </div>
                                   </div>

                                   <div class="card-fields-row">
                                       <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                           <label>Site / Platform Start Activation Date</label>
                                       </div>
                                       <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                           <input type="text" class="invoice-fields startdate" name="platform_activation_date" id="platform_activation_date" value="<?php echo set_value('platform_activation_date', convert_date_to_frontend_format($document_record['platform_activation_date'])); ?>" <?php echo $readonly; ?> />
                                       </div>
                                   </div>
                                   <div class="card-fields-row">
                                       <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                           <label>Company Team Onboarding GoToMeeting Date</label>
                                       </div>
                                       <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                           <input type="text" class="invoice-fields startdate" name="team_onboarding_date" id="team_onboarding_date" value="<?php echo set_value('team_onboarding_date', convert_date_to_frontend_format($document_record['team_onboarding_date'])); ?>" <?php echo $readonly; ?> />
                                       </div>
                                       <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                           <label>Time</label>
                                       </div>
                                       <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                           <select style="width:100%;" class="invoice-fields"  name="team_onboarding_time" id="team_onboarding_time" <?php echo $disabled; ?> >
                                                <option value="">Please Select</option>
                                                <?php echo generate_select_options_for_time($document_record['team_onboarding_time']); ?>
                                            </select>
                                       </div>
                                   </div>
                                   <div class="card-fields-row">
                                       <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                           <label>I.T Setup GoToMeeting Date</label>
                                       </div>
                                       <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                           <input type="text" class="invoice-fields startdate" name="it_meeting_date" id="it_meeting_date" value="<?php echo set_value('it_meeting_date', convert_date_to_frontend_format($document_record['it_meeting_date'])); ?>" <?php echo $readonly; ?> />
                                       </div>
                                       <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                           <label>Time</label>
                                       </div>
                                       <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                           <select style="width:100%;" class="invoice-fields"  name="it_meeting_time" id="it_meeting_time" <?php echo $disabled; ?>>
                                                <option value="">Please Select</option>
                                                <?php echo generate_select_options_for_time($document_record['it_meeting_time']); ?>
                                            </select>
                                       </div>
                                   </div>
                                   <div class="card-fields-row">
                                       <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                           <label>Creative Person GoToMeeting Date</label>
                                       </div>
                                       <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                           <input type="text" class="invoice-fields startdate" name="cp_meeting_date" id="cp_meeting_date" value="<?php echo set_value('cp_meeting_date', convert_date_to_frontend_format($document_record['cp_meeting_date'])); ?>" <?php echo $readonly; ?> />
                                       </div>
                                       <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                           <label>Time</label>
                                       </div>
                                       <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                            <select style="width:100%;" class="invoice-fields"  name="cp_meeting_time" id="cp_meeting_time" <?php echo $disabled; ?>>
                                                <option value="">Please Select</option>
                                                <?php echo generate_select_options_for_time($document_record['cp_meeting_time']); ?>
                                            </select>
                                       </div>
                                   </div>
                               </div>
                           </div>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="card-boxes">
                                    <div class="top-header-area">
                                        <h2 class="credit-card-form-heading text-center">Main Contact Person</h2>
                                        <p>( Primary point of contact person in your store that can be the liaison with setup and on-boarding )</p>
                                    </div>
                                    <input type="hidden" name="employees[0][company_sid]" value="<?php echo $company_sid; ?>" />
                                    <input type="hidden" name="employees[0][form_document_company_contacts_sid]" value="<?php echo $form_document_company_contacts_sid; ?>" />
                                    <input type="hidden" name="employees[0][contact_type]" value="main" />
                                    <div class="card-box-inner">
                                        <div class="card-fields-row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>First Name</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                <?php $db_value = empty($document_record['main_contact']) ? '' : $document_record['main_contact']['first_name'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[0][first_name]" value="<?php echo set_value('employees[0][first_name]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[0][first_name]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>Last Name</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                <?php $db_value = empty($document_record['main_contact']) ? '' : $document_record['main_contact']['last_name'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[0][last_name]"  value="<?php echo set_value('employees[0][last_name]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[0][last_name]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                <?php $db_value = empty($document_record['main_contact']) ? '' : $document_record['main_contact']['primary_email'] ; ?>
                                                <input data-rule-email="true"  data-msg-email="Please Provide a valid Email"  type="email" class="invoice-fields" name="employees[0][primary_email]" value="<?php echo set_value('employees[0][primary_email]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[0][primary_email]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>Alt Email</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                <?php $db_value = empty($document_record['main_contact']) ? '' : $document_record['main_contact']['secondary_email'] ; ?>
                                                <input data-rule-email="true" data-msg-email="Please Provide a valid Email"  type="email" class="invoice-fields" name="employees[0][secondary_email]" value="<?php echo set_value('employees[0][secondary_email]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[0][secondary_email]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>Mobile</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                <?php $db_value = empty($document_record['main_contact']) ? '' : $document_record['main_contact']['mobile_number'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[0][mobile_number]" value="<?php echo set_value('employees[0][mobile_number]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[0][mobile_number]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>Telephone</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                <?php $db_value = empty($document_record['main_contact']) ? '' : $document_record['main_contact']['telephone'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[0][telephone]" value="<?php echo set_value('employees[0][telephone]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[0][telephone]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>Title</label>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                <?php $db_value = empty($document_record['main_contact']) ? '' : $document_record['main_contact']['title'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[0][title]" value="<?php echo set_value('employees[0][title]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[0][title]');?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="card-boxes">
                                    <div class="top-header-area">
                                        <h2 class="credit-card-form-heading text-center">IT Person</h2>
                                        <p>( This person will need to have access to the admin section of your company website. They will also need to have admin access to your company Facebook page for the set up of your Facebook recruiting application. This IT setup process generally takes approx 30 minutes and is quite simple )</p>
                                    </div>
                                    <input type="hidden" name="employees[1][company_sid]" value="<?php echo $company_sid; ?>" />
                                    <input type="hidden" name="employees[1][form_document_company_contacts_sid]" value="<?php echo $form_document_company_contacts_sid; ?>" />
                                    <input type="hidden" name="employees[1][contact_type]" value="it_person" />
                                    <div class="card-box-inner">
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>First Name</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['it_person']) ? '' : $document_record['it_person']['first_name'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[1][first_name]" value="<?php echo set_value('employees[1][first_name]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[1][first_name]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Last Name</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['it_person']) ? '' : $document_record['it_person']['last_name'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[1][last_name]"  value="<?php echo set_value('employees[1][last_name]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[1][last_name]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['it_person']) ? '' : $document_record['it_person']['primary_email'] ; ?>
                                                <input data-rule-email="true"  data-msg-email="Please Provide a valid Email"  type="email" class="invoice-fields" name="employees[1][primary_email]" value="<?php echo set_value('employees[1][primary_email]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[1][primary_email]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Alt Email</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['it_person']) ? '' : $document_record['it_person']['secondary_email'] ; ?>
                                                <input data-rule-email="true"  data-msg-email="Please Provide a valid Email"  type="email" class="invoice-fields" name="employees[1][secondary_email]" value="<?php echo set_value('employees[1][secondary_email]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[1][secondary_email]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Mobile</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['it_person']) ? '' : $document_record['it_person']['mobile_number'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[1][mobile_number]" value="<?php echo set_value('employees[1][mobile_number]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[1][mobile_number]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Telephone</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['it_person']) ? '' : $document_record['it_person']['telephone'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[1][telephone]" value="<?php echo set_value('employees[1][telephone]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[1][telephone]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Title</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['it_person']) ? '' : $document_record['it_person']['title'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[1][title]" value="<?php echo set_value('employees[1][title]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[1][title]');?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="card-boxes">
                                    <div class="top-header-area">
                                        <h2 class="credit-card-form-heading text-center">Creative Person</h2>
                                        <p>( This person is most often in your Marketing or advertising dept. The most important characteristic is someone that understands your company culture and brand message and can assist with adding company pictures, videos, highlighted employee profiles and your company's branding message )</p>
                                    </div>
                                    <input type="hidden" name="employees[2][company_sid]" value="<?php echo $company_sid; ?>" />
                                    <input type="hidden" name="employees[2][form_document_company_contacts_sid]" value="<?php echo $form_document_company_contacts_sid; ?>" />
                                    <input type="hidden" name="employees[2][contact_type]" value="creative_person" />
                                    <div class="card-box-inner">
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>First Name</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['creative_person']) ? '' : $document_record['creative_person']['first_name'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[2][first_name]" value="<?php echo set_value('employees[2][first_name]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[2][first_name]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Last Name</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['creative_person']) ? '' : $document_record['creative_person']['last_name'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[2][last_name]"  value="<?php echo set_value('employees[2][last_name]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[2][last_name]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['creative_person']) ? '' : $document_record['creative_person']['primary_email'] ; ?>
                                                <input data-rule-email="true"  data-msg-email="Please Provide a valid Email"  type="email" class="invoice-fields" name="employees[2][primary_email]" value="<?php echo set_value('employees[2][primary_email]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[2][primary_email]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Alt Email</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['creative_person']) ? '' : $document_record['creative_person']['secondary_email'] ; ?>
                                                <input data-rule-email="true"  data-msg-email="Please Provide a valid Email"  type="email" class="invoice-fields" name="employees[2][secondary_email]" value="<?php echo set_value('employees[2][secondary_email]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[2][secondary_email]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Mobile</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['creative_person']) ? '' : $document_record['creative_person']['mobile_number'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[2][mobile_number]" value="<?php echo set_value('employees[2][mobile_number]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[2][mobile_number]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Telephone</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['creative_person']) ? '' : $document_record['creative_person']['telephone'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[2][telephone]" value="<?php echo set_value('employees[2][telephone]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[2][telephone]');?>
                                            </div>
                                        </div>
                                        <div class="card-fields-row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label>Title</label>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                <?php $db_value = empty($document_record['creative_person']) ? '' : $document_record['creative_person']['title'] ; ?>
                                                <input type="text" class="invoice-fields" name="employees[2][title]" value="<?php echo set_value('employees[2][title]', $db_value); ?>" <?php echo $readonly; ?> />
                                                <?php echo form_error('employees[2][title]');?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-default" style="margin: 25px 0 0 0;">
                                    <div class="panel-heading" style="display: inline-block; width: 100%;">
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                                <h4 style="margin: 0;"><strong>Staff Members</strong></h4>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                <?php if($document_record['status'] != 'signed') { ?>
                                                <a class="add-new-member-btn" href="javascript:;"><i class="fa fa-plus"></i> Add Additional Staff Members</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body" style="padding:0 15px 8px 0;">
                                        <div class="top-header-area">
                                            <p>( Please list all of the people that will be using the platform, that you would like to be a part of the initial training sessions. Don't worry though if you forget to add folks to the list they can always be registered for our ongoing training sessions. The initial training session will generally be conducted through a GoToMeeting and will be a deep dive into all facets and best practices of <?php echo STORE_NAME; ?> )</p>
                                        </div>
                                        <div class="staff-members">
                                            <?php if(!empty($document_record['staff_members'])) { ?>
                                                <?php $count = 3; ?>
                                                <?php foreach($document_record['staff_members'] as $key => $staff_member) { ?>
                                                    <div class="row-staff" id="row-staff<?php echo $count; ?>">
                                                        <input type="hidden" name="employees[<?php echo $count; ?>][company_sid]" value="<?php echo $company_sid; ?>" />
                                                        <input type="hidden" name="employees[<?php echo $count; ?>][form_document_company_contacts_sid]" value="<?php echo $form_document_company_contacts_sid; ?>" />
                                                        <input type="hidden" name="employees[<?php echo $count; ?>][contact_type]" value="staff_member" />
                                                        <div class="row">
                                                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <div class="card-fields-row">
                                                                        <label>First Name</label>
                                                                        <input type="text" class="invoice-fields" name="employees[<?php echo $count; ?>][first_name]" value="<?php echo $staff_member['first_name']; ?>" <?php echo $readonly; ?>/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <div class="card-fields-row">
                                                                        <label>Last Name</label>
                                                                        <input type="text" class="invoice-fields" name="employees[<?php echo $count; ?>][last_name]" value="<?php echo $staff_member['last_name']; ?>" <?php echo $readonly; ?>/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <div class="card-fields-row">
                                                                        <label>Email</label>
                                                                        <input data-rule-email="true"  data-msg-email="Please Provide a valid Email"  type="email" class="invoice-fields" name="employees[<?php echo $count; ?>][primary_email]" value="<?php echo $staff_member['primary_email']; ?>" <?php echo $readonly; ?>/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <div class="card-fields-row">
                                                                        <label>Alt Email</label>
                                                                        <input data-rule-email="true"  data-msg-email="Please Provide a valid Email"  type="email" class="invoice-fields" name="employees[<?php echo $count; ?>][secondary_email]" value="<?php echo $staff_member['secondary_email']; ?>" <?php echo $readonly; ?>/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                    <div class="card-fields-row">
                                                                        <label>Mobile</label>
                                                                        <input type="text" class="invoice-fields" name="employees[<?php echo $count; ?>][mobile_number]" value="<?php echo $staff_member['mobile_number']; ?>" <?php echo $readonly; ?>/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                    <div class="card-fields-row">
                                                                        <label>Telephone</label>
                                                                        <input type="text" class="invoice-fields" name="employees[<?php echo $count; ?>][telephone]" value="<?php echo $staff_member['telephone']; ?>" <?php echo $readonly; ?>/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                    <div class="card-fields-row">
                                                                        <label>Title</label>
                                                                        <input type="text" class="invoice-fields" name="employees[<?php echo $count; ?>][title]" value="<?php echo $staff_member['title']; ?>" <?php echo $readonly; ?>/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php if($document_record['status'] != 'signed') { ?>
                                                        <a class="del-record" href="javascript:row_staff_del(<?php echo $count; ?>);"><i class="fa fa-times"></i></a>
                                                    <?php } ?>
                                                    </div>
                                                    <?php $count++; ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="row-staff">
                                                    <input type="hidden" name="employees[3][company_sid]" value="<?php echo $company_sid; ?>" />
                                                    <input type="hidden" name="employees[3][form_document_company_contacts_sid]" value="<?php echo $form_document_company_contacts_sid; ?>" />
                                                    <input type="hidden" name="employees[3][contact_type]" value="staff_member" />
                                                    <div class="row">
                                                        <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <div class="card-fields-row">
                                                                    <label>First Name</label>
                                                                    <input type="text" class="invoice-fields" name="employees[3][first_name]" value=""/>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <div class="card-fields-row">
                                                                    <label>Last Name</label>
                                                                    <input type="text" class="invoice-fields" name="employees[3][last_name]" value=""/>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <div class="card-fields-row">
                                                                    <label>Email</label>
                                                                    <input data-rule-email="true"  data-msg-email="Please Provide a valid Email"  type="email" class="invoice-fields" name="employees[3][primary_email]" value=""/>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <div class="card-fields-row">
                                                                    <label>Alt Email</label>
                                                                    <input data-rule-email="true"  data-msg-email="Please Provide a valid Email"  type="email" class="invoice-fields" name="employees[3][secondary_email]" value=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                <div class="card-fields-row">
                                                                    <label>Mobile</label>
                                                                    <input type="text" class="invoice-fields" name="employees[3][mobile_number]" value=""/>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                <div class="card-fields-row">
                                                                    <label>Telephone</label>
                                                                    <input type="text" class="invoice-fields" name="employees[3][telephone]" value=""/>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                <div class="card-fields-row">
                                                                    <label>Title</label>
                                                                    <input type="text" class="invoice-fields" name="employees[3][title]" value=""/>
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
                            <?php if($document_record['status'] != 'signed') { ?>
                            <div class="card-fields-row" id="signed">
                                <div class="col-lg-6 col-lg-offset-3">
                                    <button type="button" onclick="fValidateForm();" class="page-heading">Save</button>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="">
                                <?php $uri_segment = $this->uri->segment(3); ?>
                                <?php if ($uri_segment == 'view' || $uri_segment == null) { ?>
                                    <div class="row">
                                        <div class="col-lg-6 text-center">
                                            <p class="" style="font-size:14px; text-align: center!important;">IP Address</p>

                                            <p style="font-size:14px; text-align: center!important;">
                                                <?php if(!empty($ip_track)) { ?>
                                                    <?php echo $ip_track['ip_address']; ?>
                                                <?php } else { ?>
                                                    <?php echo $_SERVER['REMOTE_ADDR']; ?>
                                                <?php } ?>
                                            </p>
                                        </div>
                                        <div class="col-lg-6 text-center">
                                            <p class="" style="font-size:14px; text-align: center!important;">Date/Time</p>

                                            <p style="font-size:14px; text-align: center!important;">
                                                <?php if(!empty($ip_track)) { ?>
                                                    <?php echo date('m/d/Y h:i A', strtotime($ip_track['document_timestamp'])); ?>
                                                <?php } else { ?>
                                                    <?php echo date('m/d/Y h:i A'); ?>
                                                <?php } ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    <?php if($document_record['status'] != 'signed') { ?>
    $('.startdate').datepicker({
      dateFormat: 'mm/dd/yy',
      changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
    }).val();
    <?php } ?>

    <?php if(!empty($document_record['staff_members'])) { ?>
      var id = <?php echo intval(count($document_record['staff_members'])) + 2; ?>;
    <?php } else { ?>
      var id = 3;
    <?php } ?>
    
    $('.add-new-member-btn').click(function(){
        id++;
        var row = '';
        row += '<div class="row-staff" id="row-staff'+ id +'">';

        row += '<input type="hidden" name="employees[' + id + '][company_sid]" value="<?php echo $company_sid; ?>" />';
        row += '<input type="hidden" name="employees[' + id + '][form_document_company_contacts_sid]" value="<?php echo $form_document_company_contacts_sid; ?>" />';
        row += '<input type="hidden" name="employees[' + id + '][contact_type]" value="staff_member" />';

        row += '<div class="row">';
        row += '<div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">';
        row += '<div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">';
        row += '<div class="card-fields-row">';
        row += '<label>First Name</label>';
        row += '<input class="invoice-fields" name="employees[' + id + '][first_name]" value="" type="text">';
        row += '</div>';
        row += '</div>';
        row += '<div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">';
        row += '<div class="card-fields-row">';
        row += '<label>Last Name</label>';
        row += '<input class="invoice-fields" name="employees[' + id + '][last_name]" value="" type="text">';
        row += '</div>';
        row += '</div>';
        row += '<div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">';
        row += '<div class="card-fields-row">';
        row += '<label>Email</label>';
        row += '<input data-rule-email="true" data-msg-email="Please Provide a valid Email" class="invoice-fields" name="employees[' + id + '][primary_email]" value="" type="email">';
        row += '</div>';
        row += '</div>';
        row += '<div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">';
        row += '<div class="card-fields-row">';
        row += '<label>Alt Email</label>';
        row += '<input data-rule-email="true" data-msg-email="Please Provide a valid Email"  class="invoice-fields" name="employees[' + id + '][secondary_email]" value="" type="email">';
        row += '</div>';
        row += '</div>';
        row += '</div>';
        row += '<div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">';
        row += '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
        row += '<div class="card-fields-row">';
        row += '<label>Mobile</label>';
        row += '<input class="invoice-fields" name="employees[' + id + '][mobile_number]" value="" type="text">';
        row += '</div>';
        row += '</div>';
        row += '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
        row += '<div class="card-fields-row">';
        row += '<label>Telephone</label>';
        row += '<input class="invoice-fields" name="employees[' + id + '][telephone]" value="" type="text">';
        row += '</div>';
        row += '</div>';
        row += '<div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">';
        row += '<div class="card-fields-row">';
        row += '<label>Title</label>';
        row += '<input class="invoice-fields" name="employees[' + id + '][title]" value="" type="text">';
        row += '</div>';
        row += '</div>';
        row += '</div>';
        row += '</div>';
        row += '<a class="del-record" href="javascript:;" onclick="row_staff_del('+id+');"><i class="fa fa-times"></i></a>';
        row += '</div>';
        //console.log("add");
        $('.staff-members').append(row);
    });
  });
  function row_staff_del(id){
    $('#row-staff'+ id).remove();
  }

  function fValidateForm(){
    $('#form_company_contacts').validate();

    if($('#form_company_contacts').valid()){
      $('#form_company_contacts').submit();
    }
  }
  
    // print page button
    function print_page(elem)
    {
        $('form input[type=text]').each(function() {
            $(this).attr('value', $(this).val());
        });
        
        // hide the signed button
        $('#signed').hide();
        $('#print_div').hide();
        $('.add-new-member-btn').hide();
        
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');
        
        mywindow.document.write('<html><head><title>' + '<?php echo $page_title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery-ui-datepicker-custom.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css'); ?>" type="text/css" />');        
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/responsive.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close(); 
        mywindow.focus(); 
        
        // display the button again
        $('#signed').show();
        $('#print_div').show();
        $('.add-new-member-btn').show();
    }
</script>
</body>
</html>