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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $title; ?></h1>
                                    </div>
                                    <br />
                                    <div class="add-new-promotions">
                                        <div class="row">
                                            <div class="col-xs-6"></div>

                                    <?php   if (check_access_permissions_for_view($security_details, 'unblocked_select')) { ?>
                                                <div class="col-xs-3">
                                                    <button onclick="func_unblock_selected();" type="button" class="btn btn-success btn-block">Unblock Selected</button>
                                                </div>
                                    <?php   } ?>

                                    <?php   if (check_access_permissions_for_view($security_details, 'block_new_email')) { ?>
                                                <div class="col-xs-3">
                                                    <a href="<?php echo base_url('manage_admin/blocked_applicants/add_applicant'); ?>" type="button" class="btn btn-success btn-block">Block New Email</a>
                                                </div>
                                    <?php   } ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th class="col-xs-5 text-center">Applicant Email</th>
                                                            <th class="col-xs-4 text-center">Blocked From</th>
                                                            <th class="col-xs-2 text-center">Date Blocked</th>
                                                            <th class="col-xs-1 text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                    <?php               if (!empty($blocked_applicants)) {
                                                            foreach ($blocked_applicants as $key => $blocked_applicant) { ?>
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <label class="control control--checkbox font-normal">
                                                                            <input class="applicants" name="applicants[]" value="<?php echo $blocked_applicant['sid']; ?>" data-company_sid="<?php echo $blocked_applicant['company_sid']; ?>" type="checkbox">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </td>
                                                                    <td><?php echo $blocked_applicant['applicant_email']; ?></td>
                                                                    <td><?php echo 'Entire Server'; ?></td>
                                                                    <td><?php echo date_with_time($blocked_applicant['date_blocked']); ?></td>

                                            <?php                   if (check_access_permissions_for_view($security_details, 'unblock')) { ?>
                                                                        <td>
                                                                            <form id="form_unblock_applicant_<?php echo $blocked_applicant['sid']; ?>" method="post" action="<?php echo current_url(); ?>">
                                                                                <input type="hidden" id="perform_action" name="perform_action" value="unblock_applicant" />
                                                                                <input type="hidden" id="applicant_email" name="applicant_email" value="<?php echo $blocked_applicant['applicant_email']; ?>" />
                                                                                <button onclick="func_unblock_applicant(<?php echo $blocked_applicant['sid']; ?>);" type="button" class="btn btn-success btn-sm">Unblock</button>
                                                                            </form>
                                                                        </td>
                                            <?php                   } ?>
                                                                </tr>
                                            <?php           } ?>
                                            <?php       } else { ?>
                                                            <tr>
                                                                <td colspan="5" class="text-center">
                                                                    <span class="no-data">No Applicants Blocked</span>
                                                                </td>
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
</div>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function func_unblock_applicant(sid) {
        alertify.confirm(
                'Are you sure?',
                'Are you sure you want to Unblock this email address?',
                function () {
                    $('#form_unblock_applicant_' + sid).submit();
                },
                function () {
                    alertify.error('Cancelled!');
                });
    }

    function func_unblock_selected() {
        alertify.confirm(
                'Are you sure?',
                'Are you sure you want to Unblock Selected email address?',
                function () {
                    var selected = $('.applicants:checked');
                    var sids = [];

                    $(selected).each(function () {
                        sids.push($(this).val());
                    });

                    if (sids.length > 0) {
                        var my_request;
                        my_request = $.ajax({
                            url: '<?php echo base_url('manage_admin/blocked_applicants'); ?>',
                            method: 'POST',
                            data: {'perform_action': 'bulk_unblock_applicants', 'sids': sids}
                        });

                        my_request.done(function (response) {
                            if (response == 'success') {
                                window.location = window.location.href;
                            }
                        });
                    }
                },
                function () {
                    alertify.error('Cancelled!');
                });
    }

</script>
