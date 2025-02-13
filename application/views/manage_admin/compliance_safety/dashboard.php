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
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/report_types/add') ?>" class="btn btn-success"><i class="fa fa-plus-circle"> </i> Add A Report Type</a>
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/incident_types/add') ?>" class="btn btn-success"><i class="fa fa-plus-circle"> </i> Add An Incident Type</a>
                                        </div>
                                    </div>
                                    <?php $this->load->view("manage_admin/compliance_safety/partials/report_types"); ?>
                                    <?php $this->load->view("manage_admin/compliance_safety/partials/incident_types"); ?>


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
    $(function() {

        let XHR = null;

        $(document).on("click", ".jsDeactivateReportType", function(event) {
            event.preventDefault();
            const reference = $(this);
            const recordId = $(this).prop("id");
            alertify
                .confirm(
                    "Are you sure you want to deactivate this Report Type?",
                    function() {
                        changeStatus("report", recordId, "off")
                    }
                )
        });

        $(document).on("click", ".jsActivateReportType", function(event) {
            event.preventDefault();
            const reference = $(this);
            const recordId = $(this).prop("id");
            alertify
                .confirm(
                    "Are you sure you want to activate this Report Type?",
                    function() {
                        changeStatus("report", recordId, "on")
                    }
                )
        });

        $(document).on("click", ".jsDeactivateIncidentTYpe", function(event) {
            event.preventDefault();
            const reference = $(this);
            const recordId = $(this).prop("id");
            alertify
                .confirm(
                    "Are you sure you want to deactivate this Report Incident Type?",
                    function() {
                        changeStatus("incident", recordId, "off")
                    }
                )
        });

        $(document).on("click", ".jsActivateIncidentTYpe", function(event) {
            event.preventDefault();
            const reference = $(this);
            const recordId = $(this).prop("id");
            alertify
                .confirm(
                    "Are you sure you want to activate this Report Incident Type?",
                    function() {
                        changeStatus("incident", recordId, "on")
                    }
                )
        });

        function changeStatus(option, recordId, status) {
            $
                .ajax({
                    url: window.location.origin + "/manage_admin/compliance_safety/handle_status/" + recordId,
                    method: "POST",
                    data: {
                        status: status,
                        id: recordId,
                        type: option
                    }
                })
                .always()
                .fail(function(resp) {
                    alerify.alert(
                        "Errors!"
                    );
                })
                .done(function(resp) {
                    alertify.alert(
                        "Success!",
                        resp.message,
                        function() {
                            window.location.href = window.location.href;
                        }
                    );
                });
        }

    });
    // $(document).ready(function() {
    //     $('.type').click(function() {
    //         var id = $(this).attr('id');
    //         var status = $(this).attr('src');
    //         if (status == 'Disable') {
    //             $.ajax({
    //                 type: 'GET',
    //                 data: {
    //                     status: 0
    //                 },
    //                 9 url: '<?= base_url('manage_admin/reports/incident_reporting/enable_disable_type') ?>/' + id,
    //                 success: function(data) {
    //                     data = JSON.parse(data);
    //                     if (data.message == 'updated') {
    //                         $('#status-' + id).html('In Active');
    //                         $('#' + id).removeClass('btn-danger');
    //                         $('#' + id).addClass('btn-primary');
    //                         $('#' + id).html('<i class="fa fa-toggle-on"></i>');
    //                         $('#' + id).attr('src', 'Enable');
    //                         $('#' + id).attr('title', 'Enable Type');
    //                     }
    //                 },
    //                 error: function() {

    //                 }
    //             });
    //         } else if (status == 'Enable') {

    //             $.ajax({
    //                 type: 'GET',
    //                 data: {
    //                     status: 1
    //                 },
    //                 url: '<?= base_url('manage_admin/reports/incident_reporting/enable_disable_type') ?>/' + id,
    //                 success: function(data) {
    //                     data = JSON.parse(data);
    //                     if (data.message == 'updated') {
    //                         $('#status-' + id).html('Active');
    //                         $('#' + id).removeClass('btn-primary');
    //                         $('#' + id).addClass('btn-danger');
    //                         $('#' + id).html('<i class="fa fa-toggle-off"></i>');
    //                         $('#' + id).attr('src', 'Disable');
    //                         $('#' + id).attr('title', 'Disable Type');
    //                     }
    //                 },
    //                 error: function() {

    //                 }
    //             });
    //         }
    //     });

    // });
</script>