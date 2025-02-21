<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <a id="search_btn" href="<?php echo base_url('manage_admin/compliance_safety/incident_types/add_new_question/' . $inc_id) ?>" class="btn btn-success"><i class="fa fa-plus-square"> </i> Add New Question</a>
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/compliance_safety/dashboard') ?>" class="btn btn-success"><i class="fa fa-arrow-left"> </i> Go Back</a>

                                        </div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Question</th>
                                                                <th>Type</th>
                                                                <th>Status</th>
                                                                <th class="last-col" width="1%" colspan="3">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!--All records-->

                                                            <?php if (sizeof($questions) > 0) {
                                                                foreach ($questions as $question) {
                                                            ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?= $question['label'] ?>
                                                                        </td>

                                                                        <td>
                                                                            <?= ucfirst($question['question_type']); ?>
                                                                        </td>

                                                                        <td id="status-<?= $question['id'] ?>">
                                                                            <?= $question['status'] == 1 ? 'Active' : 'In Active' ?>
                                                                        </td>

                                                                        <!--                                                                --><?php //if (check_access_permissions_for_view($security_details, 'edit_question')) { 
                                                                                                                                                ?>
                                                                        <td><?php echo anchor('manage_admin/compliance_safety/incident_types/edit_question/' . $question['id'], 'Edit', 'class="btn btn-success btn-sm" title="Edit Incident"'); ?></td>
                                                                        <!--                                                                --><?php //} 
                                                                                                                                                ?>
                                                                        <!--                                                                --><?php //if (check_access_permissions_for_view($security_details, 'disable_question')) { 
                                                                                                                                                ?>
                                                                        <td>
                                                                            <?= $question['status'] == 1 ? '<a href="javascipt:;"
                                                                           class="btn btn-sm btn-danger type" id="' . $question['id'] . '"
                                                                           title="Disable Type">Disable</a>'
                                                                                :
                                                                                '<a href="javascipt:;"
                                                                           class="btn btn-sm btn-primary type" id="' . $question['id'] . '"
                                                                           title="Enable Type">Enable</a>' ?>
                                                                        </td>
                                                                        <!--                                                                --><?php //} 
                                                                                                                                                ?>
                                                                    </tr>
                                                                <?php }
                                                            } else { ?>
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        <span class="no-data">No Questions Found</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <input type="hidden" name="execute" value="multiple_action">
                                                    <input type="hidden" id="type" name="type" value="employer">
                                                </form>
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.type').click(function() {
            var id = $(this).attr('id');
            var status = $(this).html();
            if (status == 'Disable') {
                $.ajax({
                    type: 'GET',
                    data: {
                        status: 0
                    },
                    url: '<?= base_url('manage_admin/compliance_safety/incident_types/enable_disable_question') ?>/' + id,
                    success: function(data) {
                        data = JSON.parse(data);
                        if (data.message == 'updated') {
                            $('#status-' + id).html('In Active');
                            $('#' + id).removeClass('btn-danger');
                            $('#' + id).addClass('btn-primary');
                            $('#' + id).html('Enable');
                        }
                    },
                    error: function() {

                    }
                });
            } else if (status == 'Enable') {
                $.ajax({
                    type: 'GET',
                    data: {
                        status: 1
                    },
                    url: '<?= base_url('manage_admin/compliance_safety/incident_types/enable_disable_question') ?>/' + id,
                    success: function(data) {
                        data = JSON.parse(data);
                        if (data.message == 'updated') {
                            $('#status-' + id).html('Active');
                            $('#' + id).removeClass('btn-primary');
                            $('#' + id).addClass('btn-danger');
                            $('#' + id).html('Disable');
                        }
                    },
                    error: function() {

                    }
                });
            }

        });

    });
</script>