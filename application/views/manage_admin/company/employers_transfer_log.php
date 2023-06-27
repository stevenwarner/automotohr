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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <br><br>
                                    <div class="hr-innerpadding">
                                        <div class="table-responsive">
                                            <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Employee</th>
                                                            <th>From Company</th>
                                                            <th>To Company</th>
                                                            <th>Documents</th>
                                                            <th>Transfer Date</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php //if (!empty($employers)) {
                                                        ?>
                                                            <?php //foreach ($employers as $key => $value) {
                                                            ?>
                                                                <tr id="parent_<?= $value['sid'] ?>">
                                                                    <td class="text-center">
                                                                        dsfsdf
                                                                    </td>
                                                                    <td>
                                                                        sdfsdf
                                                                    </td>
                                                                    <td>
                                                                        sdfsdf
                                                                    </td>
                                                                    <td >
                                                                        fsdfsdfsf
                                                                    </td>

                                                                    <td>
                                                                        fgdfggdfg
                                                                    </td>


                                                                </tr>
                                                            <?php //} ?>
                                                        <?php //} else {  ?>
                                                            <tr>
                                                                <td colspan="8" class="text-center">
                                                                    <span class="no-data">No Employers Found</span>
                                                                </td>
                                                            </tr>
                                                        <?php //} ?>
                                                    </tbody>
                                                </table>
                                                <input type="hidden" name="execute" value="multiple_action">
                                                <input type="hidden" id="type" name="type" value="employer">
                                            </form>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="hr-items-count">
                                            <strong class="employerCount"><?php echo $total_employers; ?></strong> Employers
                                            <p><?php if ($total_rows != 0) {
                                                    echo 'Displaying <b>' . $offset . ' - ' . $end_offset . '</b> of ' . $total_rows . ' records';
                                                } ?></p>
                                        </div>
                                        <?php echo $links; ?>
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
    $(document).keypress(function(e) {
        if (e.which == 13) { // enter pressed
            $('#search_btn').click();
        }
    });

    $(document).on('click', '.send_credentials', function(e) {
        var sid = $(this).attr('data-attr');
        var name = $(this).attr('data-name');
        console.log('ID: ' + sid);
        console.log('Name: ' + name);
        var url = "<?= base_url() ?>manage_admin/employers/send_login_credentials";
        alertify.confirm('Confirmation', "Are you sure you want to send Email to Employee?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'sendemail',
                        sid: sid,
                        name: name
                    },
                    success: function(data) {
                        if (data == 'success') {
                            alertify.success('Email with generate password link is sent.');
                        } else {
                            alerty.error('there was error');
                        }
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    $(document).on('click', '.deactive_employee', function(e) {
        var id = $(this).attr('data-attr');
        var url = "<?= base_url() ?>manage_admin/employers/change_status";
        alertify.confirm('Confirmation', "Are you sure you want to deactivate this Employee?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'deactive',
                        sid: id
                    },
                    success: function() {
                        alertify.success('Employee have been De-Activated.');
                        $('#' + id).removeClass('deactive_employee');
                        $('#' + id).removeClass('btn-warning');
                        $('#' + id).addClass('active_employee');
                        $('#' + id).addClass('btn-success');
                        $('#' + id).attr('title', 'Enable Employee');
                        $('#' + id).find('i').removeClass('fa-ban');
                        $('#' + id).find('i').addClass('fa-shield');
                        //                        window.location.href = '<?php //echo current_url()
                                                                            ?>//';
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    $(document).on('click', '.active_employee', function(e) {
        var id = $(this).attr('data-attr');
        var url = "<?= base_url() ?>manage_admin/employers/change_status";
        alertify.confirm('Confirmation', "Are you sure you want to activate this Employee?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'active',
                        sid: id
                    },
                    success: function() {
                        alertify.success('Employee have been Activated.');
                        $('#' + id).removeClass('active_employee');
                        $('#' + id).removeClass('btn-success');
                        $('#' + id).addClass('deactive_employee');
                        $('#' + id).addClass('btn-warning');
                        $('#' + id).attr('title', 'Disable Employee');
                        $('#' + id).find('i').removeClass('fa-shield');
                        $('#' + id).find('i').addClass('fa-ban');
                        //                        window.location.href = '<?php //echo current_url()
                                                                            ?>//';
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    });

    $(document).ready(function() {
        $('#keyword').on('keyup', update_url);
        $('#keyword').on('blur', update_url);
        $('#contact_name').on('keyup', update_url);
        $('#contact_name').on('blur', update_url);
        $('#company-name').on('keyup', update_url);
        $('#company-name').on('blur', update_url);
        $('#active').on('change', update_url);
        $('#search_btn').on('click', function(e) {
            e.preventDefault();
            update_url();
            window.location = $(this).attr('href').toString();
        });
    });

    function update_url() {
        var url = '<?php echo base_url('manage_admin/employers/'); ?>';
        var keyword = $('#keyword').val();
        var company_name = $('#company-name').val();
        var status = $('#active').val();
        var contact_name = $('#contact_name').val();

        keyword = keyword == '' ? 'all' : keyword;
        company_name = company_name == '' ? 'all' : company_name;
        contact_name = contact_name == '' ? 'all' : contact_name;
        url = url + '/' + encodeURIComponent(keyword) + '/' + status + '/' + encodeURIComponent(company_name) + '/' + encodeURIComponent(contact_name);
        $('#search_btn').attr('href', url);
    }

    function employerLogin(userId) {
        url_to = "<?= base_url() ?>manage_admin/employers/employer_login";
        $.post(url_to, {
                action: "login",
                sid: userId
            })
            .done(function() {
                window.open("<?= base_url('dashboard') ?>", '_blank');
            });
    }

    function deleteEmployer(id) {
        url = "<?= base_url() ?>manage_admin/employers/employer_task";
        alertify.confirm('Confirmation', "Are you sure you want to delete this Employee?",
            function() {
                $.post(url, {
                        action: 'delete',
                        sid: id
                    })
                    .done(function(data) {
                        employerCounter = parseInt($(".employerCount").html());
                        employerCounter--;
                        $(".employerCount").html(employerCounter);
                        $("#parent_" + id).remove();
                        alertify.success('Selected employee have been Deleted.');
                    });
            },
            function() {
                alertify.error('Canceled');
            });
    }

    function deactive_employee(id) {
        var url = "<?= base_url() ?>manage_admin/employers/change_status";
        alertify.confirm('Confirmation', "Are you sure you want to deactivate this Employee?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'deactive',
                        sid: id
                    },
                    success: function() {
                        alertify.success('Employee have been De-Activated.');
                        console.log(url);
                        return false;
                        window.location.href = '<?php current_url() ?>';
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    }

    function active_employee(id) {
        var url = "<?= base_url() ?>manage_admin/employers/change_status";
        alertify.confirm('Confirmation', "Are you sure you want to activate this Employee?",
            function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        action: 'active',
                        sid: id
                    },
                    success: function() {
                        alertify.success('Employee have been Activated.');
                        window.location.href = '<?php current_url() ?>';
                    },
                    error: function() {

                    }
                });
            },
            function() {
                alertify.error('Canceled');
            });
    }

    $('[data-placement="top"]').popover({
        placement: 'top',
        trigger: 'hover'
    });
</script>
<!--  -->
<link rel="stylesheet" href="<?= base_url("assets/css/SystemModel.css"); ?>">
<script src="<?= base_url("assets/js/SystemModal.js"); ?>"></script>

<script>
    // ComplyNet
    $(document).on("click", ".jsAddEmployeeToComplyNet", function(event) {
        //
        event.preventDefault();
        //
        let employeeId = $(this).data("id");
        let companyId = $(this).data("cid");

        //
        return alertify.confirm(
            "Are you sure you want to sync this employee with ComplyNet.<br />In case the employee is not found on ComplyNet, the system will add the employee to ComplyNet.",
            function() {
                addEmployeeToComplyNet(companyId, employeeId)
            }
        );
    });

    function addEmployeeToComplyNet(companyId, employeeId) {
        //

        Modal({
                Id: "jsModelEmployeeToComplyNet",
                Title: "Add Employee To ComplyNet",
                Body: '<div class="container"><div id="jsModelEmployeeToComplyNetBody"><p class="alert alert-info text-center">Please wait while we are syncing employee with ComplyNet. It may take a few moments.</p></div></div>',
                Loader: "jsModelEmployeeToComplyNetLoader",
            },
            function() {
                //
                $.post(window.location.origin + "/cn/" + companyId + "/employee/sync", {
                        employeeId: employeeId,
                        companyId: companyId,
                    })
                    .success(function(resp) {
                        //
                        if (resp.hasOwnProperty("errors")) {
                            //
                            let errors = '';
                            errors += '<strong class="text-danger">';
                            errors += '<p><em>In order to sync employee with ComplyNet the following details are required.';
                            errors += ' Please fill these details from employee\'s profile.</em></p><br />';
                            errors += resp.errors.join("<br />");
                            errors += '</strong>';
                            //
                            $('#jsModelEmployeeToComplyNetBody').html(errors);
                        } else {
                            $('#jsModelEmployeeToComplyNet .jsModalCancel').trigger('click');
                            alertify.alert(
                                'Success',
                                'You have successfully synced the employee with ComplyNet',
                                window.location.reload
                            );
                        }
                    })
                    .fail(window.location.reload);
                ml(false, "jsModelEmployeeToComplyNetLoader");
            }
        );
    }
</script>