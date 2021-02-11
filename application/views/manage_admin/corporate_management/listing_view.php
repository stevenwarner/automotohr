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
                                        <h1 class="page-title"><i class="fa fa-group"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Dashboard</a>
                                    </div>
                                    <!-- search form -->
                                    <div class="hr-search-criteria <?php echo ($flag == 1) ? 'opened' : "" ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" <?php echo ($flag == 1) ? "style='display:block'" : "" ?>>
                                        <form method="GET" action="">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                <label>Corporate Name:</label>
                                                <input type="text" name="CompanyName" value="<?= empty($search['CompanyName']) ? "" : $search['CompanyName']; ?>"  class="invoice-fields">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                <label>Status:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="active" id="active">
                                                        <option value="1" selected>Active</option>
                                                        <option value="0" <?php echo (isset($search['active']) && $search['active'] == 0) ? 'selected' : ''; ?>>In-Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                                <input type="submit" class="btn btn-success" value="Search">
                                                <a href="<?php echo base_url('manage_admin/corporate_management'); ?>" class="btn btn-success">Clear</a>
                                            </div>

                                        </form>
                                    </div>
                                    <!-- search form -->
                                    <div class="col-xs-12 col-sm-12">
                                        <?php
                                        if (isset($links)) {
                                            echo $links;
                                        }
                                        ?>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <?php if(check_access_permissions_for_view($security_details, 'add_corporate_site')) { ?>
                                                    <div class="heading-title">
                                                        <a href="<?php echo base_url('manage_admin/corporate_management/add_corporate_site'); ?>" class="btn btn-success pull-right full-on-small">Add Corporate Career Site</a>
                                                    </div>
                                                <?php } ?>
                                                <div class="clear"></div>
                                                <div class="table-responsive">
                                                    <table class="table table-stripped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-5 text-left">Corp. Company Name</th>
                                                                <th class="col-xs-4 text-left">Automotive Group</th>
                                                                <th class="col-xs-1 text-center">Status</th>
                                                                <?php if(check_access_permissions_for_view($security_details, array('add_corporate_site', 'edit_corporate_site'))) { ?>
                                                                <th class="col-xs-2 text-center" colspan="3">Actions</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($corporate_companies)) { ?>
                                                                <?php foreach ($corporate_companies as $corporate_company) { ?>
                                                                    <tr>
                                                                        <td class="text-left">
                                                                            <span>
                                                                                <?php echo ucwords($corporate_company['CompanyName']); ?>
                                                                            </span>
                                                                            <br />
                                                                            <span class="text-success">
                                                                                <small>
                                                                                    URL : <?php echo $corporate_company['career_page_details']['sub_domain']; ?>
                                                                                </small>
                                                                            </span>
                                                                        </td>

                                                                        <td>
                                                                            <?php echo $corporate_company['group_name']; ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if ($corporate_company['active'] == 1) { ?>
                                                                                <span style='color:green;'>Active</span>
                                                                            <?php } else { ?>
                                                                                <span style='color:red;'>In-Active</span>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <?php if(check_access_permissions_for_view($security_details, 'edit_corporate_site')) { ?>
                                                                        <td class='text-center'>
                                                                            <form id="<?php echo $corporate_company['sid']; ?>" method="post" enctype="multipart/form-data" action='<?php echo base_url('manage_admin/corporate_management/ajax_responder'); ?>'>
                                                                                <input type="hidden" name="perform_action" id="perform_action" value="change_company_status" />
                                                                                <input type="hidden" name="corporate_company_sid" id="corporate_company_sid" value="<?php echo $corporate_company['sid']; ?>" />
                                                                                <button name='company_status' id='company_status' data-toggle="tooltip" data-placement="top" class="btn btn-<?php echo ($corporate_company['active'] == 1) ? 'success' : 'danger'; ?> btn-sm" value="<?php echo ($corporate_company['active'] == 1) ? '0' : '1'; ?>">
                                                                                    <?php echo ($corporate_company['active'] == 1) ? 'Deactivate' : 'Activate'; ?>
                                                                                </button>
                                                                            </form>
                                                                        </td>
                                                                        <?php } ?>
                                                                        <?php if(check_access_permissions_for_view($security_details, 'edit_corporate_site')) { ?>
                                                                            <td>
                                                                                <a href='<?php echo base_url('manage_admin/corporate_management/edit_corporate_site') . '/' . $corporate_company['sid']; ?>' name='edit_company' id='edit_company' data-toggle="tooltip" data-placement="top" class="btn btn-success btn-sm">
                                                                                    <i class="fa fa-pencil"></i>
                                                                                </a>
                                                                            </td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td class="text-center" colspan="4">
                                                                        <span class='no-data'>
                                                                            No Corporate Companies Found
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                        <?php
                                        if (isset($links)) {
                                            echo $links;
                                        }
                                        ?>
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