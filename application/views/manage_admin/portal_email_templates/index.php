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
                                        <h1 class="page-title"><i class="fa fa-users"></i>Portal Email Templates</h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Company</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="hr-box-header"></div>
                                        <div class="table-responsive table-outer">
                                            <table class="table table-bordered table-stripped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left col-xs-10">Name</th>
                                                        <th class="text-left col-xs-1">Auto Responder</th>
                                                        <th class="text-center col-xs-1">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($portal_email_templates)) { ?>
                                                        <?php foreach($portal_email_templates as $template) { ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo $template['template_name']; ?>
                                                                </td>
                                                                <td style="color: <?php echo ($template['enable_auto_responder'] == 1 ? 'green' : 'red'); ?>;">
                                                                    <?php echo ($template['enable_auto_responder'] == 1 ? 'Enabled' : 'Disabled'); ?>
                                                                </td>
                                                                <td>
                                                                    <a href="<?php echo base_url('manage_admin/portal_email_templates/edit/' . $template['sid']); ?>" class="hr-edit-btn" >Edit</a>
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
