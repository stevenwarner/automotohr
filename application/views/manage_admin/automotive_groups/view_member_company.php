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
                                        <h1 class="page-title"><i class="fa fa-users"></i> Corporate Groups</h1>
                                        <a href="<?php echo base_url('manage_admin/corporate_groups'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Corporate Groups</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><?php echo $page_title; ?>&nbsp;<?php echo $automotive_group['group_name']; ?></h1>
                                            <a href="<?php echo base_url('manage_admin/corporate_groups/member_companies/' . $automotive_group['sid']); ?>" class="btn btn-success pull-right full-on-small"> Member Companies</a>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="clear"></div>
                                                <div class="table-responsive table-custom">
                                                    <table class="table table-stripped table-hover table-bordered">
                                                        <tbody>
                                                        <tr>
                                                            <th class="col-xs-3">Company Name</th>
                                                            <td><?php echo ucwords($member_company_info['company_name']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3">Address</th>
                                                            <td><?php echo ucwords($member_company_info['location_address']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3">City</th>
                                                            <td><?php echo ucwords($member_company_info['location_city']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3">State</th>
                                                            <td>
                                                                <?php $temp = db_get_state_name($member_company_info['location_state']); ?>
                                                                <?php echo ucwords($temp['state_name']); ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3">Zip Code</th>
                                                            <td><?php echo $member_company_info['location_zipcode']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3">Country</th>
                                                            <td>
                                                                <?php $temp = db_get_country_name($member_company_info['location_country']); ?>
                                                                <?php echo ucwords($temp['country_name']); ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3">Is Registered in <?php echo STORE_NAME; ?></th>
                                                            <td><?php echo ($member_company_info['is_registered_in_ahr'] == 1 ? '<span style="color:green;">Yes</span>' : '<span style="color:red;">No</span>'); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th class="col-xs-3">Registered Company Name</th>
                                                            <?php $temp = ( get_company_details($member_company_info['company_sid'])); ?>
                                                            <td><?php echo (isset($member_company_info['company_sid']) && $member_company_info['company_sid'] > 0 ? ucwords($temp['CompanyName']) : ''); ?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="clear"></div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <strong>Primary Contact</strong>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-stripped table-hover table-bordered">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="col-xs-3">Name</th>
                                                                        <td><?php echo ucwords($member_company_info['pri_contact_name']); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="col-xs-3">Phone</th>
                                                                        <td><?php echo ucwords($member_company_info['pri_contact_phone']); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="col-xs-3">Email</th>
                                                                        <td><?php echo $member_company_info['pri_contact_email']; ?></td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="clear"></div>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <strong>Secondary Contact</strong>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-stripped table-hover table-bordered">
                                                                <tbody>
                                                                <tr>
                                                                    <th class="col-xs-3">Name</th>
                                                                    <td><?php echo ucwords($member_company_info['sec_contact_name']); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="col-xs-3">Phone</th>
                                                                    <td><?php echo ucwords($member_company_info['sec_contact_phone']); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="col-xs-3">Email</th>
                                                                    <td><?php echo $member_company_info['sec_contact_email']; ?></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="clear"></div>
                                                <div class="table-responsive">
                                                    <table class="table table-stripped table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <th class="col-xs-3">Notes</th>
                                                                <td class="col-xs-9"><?php echo ($member_company_info['short_description']); ?></td>
                                                            </tr>
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
</div>