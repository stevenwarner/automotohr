<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title"><i class="fa fa-file-o"></i>Company Billing Contacts</h1>
                                        <a href="<?php echo base_url('manage_admin/companies')?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Companies</a>
                                        <?php if(isset($company_sid) && $company_sid > 0) { ?>
                                            <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid)?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                        <?php } ?>
                                    </div>

                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <a class="site-btn" href="<?php echo base_url('manage_admin/company_billing_contacts/add/0/' . $company_sid)?>">Add New</a>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="heading-title">
                                                    <h1 class="page-title text-center"><?php echo ucwords($company_name); ?></h1>
                                                </div>


                                                <div class="hr-box-header"></div>
                                                <div class="table-responsive table-outer">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <!--<th class="text-left col-xs-4" >Company Name</th>-->
                                                                <th class="text-left col-xs-1" >Title</th>
                                                                <th class="text-left col-xs-3" >Contact Name</th>
                                                                <th class="text-left col-xs-3" >Email Address</th>
                                                                <!--<th class="text-left col-xs-3" >Billing Address</th>-->
                                                                <th class="text-left col-xs-3" >Phone Number</th>
                                                                <th class="text-center col-xs-2" colspan="2">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(!empty($company_billing_contacts)) { ?>
                                                                <?php foreach($company_billing_contacts as $company_billing_contact) { ?>
                                                                    <tr>
                                                                        <!--<td><?php echo ucwords($company_billing_contact['company_name']); ?></td>-->
                                                                        <td><?php echo ucwords($company_billing_contact['title']); ?></td>
                                                                        <td><?php echo ucwords($company_billing_contact['contact_name']); ?></td>
                                                                        <td><?php echo $company_billing_contact['email_address']; ?></td>
                                                                        <!--<td><?php echo $company_billing_contact['billing_address']; ?></td>-->
                                                                        <td><?php echo $company_billing_contact['phone_number']; ?></td>
                                                                        <td>
                                                                            <a class="hr-edit-btn" href="<?php echo base_url('manage_admin/company_billing_contacts/edit/' . $company_billing_contact['sid'] . '/' . $company_sid)?>">Edit</a>
                                                                        </td>
                                                                        <td>
                                                                            <form id="form_delete_contact_<?php echo $company_billing_contact['sid']; ?>" method="post">
                                                                                <?php echo form_hidden('company_sid', $company_billing_contact['company_sid']); ?>
                                                                                <?php echo form_hidden('company_billing_contact_sid', $company_billing_contact['sid']); ?>
                                                                                <?php echo form_hidden('perform_action', 'delete_company_billing_contact'); ?>
                                                                                <button type="button" class="hr-delete-btn" onclick="fDeleteContact(<?php echo $company_billing_contact['sid']?>);">Delete</button>
                                                                            </form>

                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="hr-box-header hr-box-footer"></div>
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
    function fDeleteContact(contact_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this Billing Contact?',
            function () {
                $('#form_delete_contact_' + contact_sid).submit();
            },
            function () {

            }
        );
    }
</script>