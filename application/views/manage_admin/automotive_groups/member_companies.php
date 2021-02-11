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
                                            <h1 class="page-title">
                                                <?php echo $page_title; ?><?php echo $automotive_group['group_name']; ?>
                                            </h1>

                                            <a href="<?php echo base_url('manage_admin/corporate_groups/add_member_company/' . $automotive_group['sid']); ?>" class="btn btn-success pull-right full-on-small">New Member Company</a>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="table-responsive table-custom">
                                            <table class="table table-bordered table-hover table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-3">Company Name</th>
                                                        <th class="col-xs-2">Primary Contact</th>
                                                        <th class="col-xs-2">Primary Email</th>
                                                        <th class="col-xs-2">Registered In AHR</th>
                                                        <th class="col-xs-1 text-center" colspan="3">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($member_companies)) { ?>
                                                        <?php foreach($member_companies as $member_company) { ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo ucwords($member_company['company_name']);?>
                                                                </td>
                                                                <td>
                                                                    <?php echo ucwords($member_company['pri_contact_name']);?>
                                                                </td>
                                                                <td>
                                                                    <?php echo ucwords($member_company['pri_contact_email']);?>
                                                                </td>
                                                                <td>
                                                                    <?php echo ($member_company['is_registered_in_ahr'] == 1 ? '<span class="Paid">Yes</span>' : '<span class="Unpaid">No</span>');?>
                                                                </td>
                                                                <td>
                                                                    <a data-toggle="tooltip" data-placement="top" title="View Member Company Details" href="<?php echo base_url('manage_admin/corporate_groups/view_member_company/' . $member_company['automotive_group_sid'] . '/' . $member_company['sid']); ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                                                </td>
                                                                <td>
                                                                    <a data-toggle="tooltip" data-placement="top" title="Edit Member Company Details" href="<?php echo base_url('manage_admin/corporate_groups/edit_member_company/' . $member_company['automotive_group_sid'] . '/' . $member_company['sid']); ?>" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                                                                </td>
                                                                <td>
                                                                    <form id="form_delete_member_company_<?php echo $member_company['sid']; ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_member_company" />
                                                                        <input type="hidden" id="member_company_sid" name="member_company_sid" value="<?php echo $member_company['sid']; ?>" />
                                                                        <button type="button" data-toggle="tooltip" data-placement="top" title="Delete Member Company" onclick="fDeleteMemberCompany(<?php echo $member_company['sid']; ?>, '<?php echo $member_company['company_name']; ?>')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="no-data" colspan="5" id="group">No Records Found</td>
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
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    function fDeleteMemberCompany(member_company_sid, member_company_name) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete "' + member_company_name + '" ?',
            function () {
                $('#form_delete_member_company_' + member_company_sid).submit();
            },
            function () {

            });
    }
</script>