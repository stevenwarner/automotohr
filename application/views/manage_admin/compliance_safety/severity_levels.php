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
                                        <h1 class="page-title"><i class="fa fa-users"></i>Severity Levels</h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/dashboard') ?>"
                                                class="btn btn-success"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/report_types/add') ?>"
                                                class="btn btn-success"><i class="fa fa-plus-circle"> </i> Add A Report
                                                Type</a>
                                            <a href="<?php echo base_url('manage_admin/compliance_safety/incident_types/add') ?>"
                                                class="btn btn-success"><i class="fa fa-plus-circle"> </i> Add An
                                                Incident Type</a>
                                        </div>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <div class="hr-registered">
                                                Compliance Severity Levels
                                            </div>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Severity Level</th>
                                                            <th>Background Color</th>
                                                            <th>Text Color</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!--All records-->

                                                        <?php if ($severity_levels) {
                                                            foreach ($severity_levels as $type) {
                                                                ?>
                                                                <tr data-id="<?= $type["sid"]; ?>">
                                                                    <td style="vertical-align: middle;">
                                                                        <input type="text" value="<?= $type['level'] ?>"
                                                                            class="form-control jsLevel">

                                                                    </td>
                                                                    <td style="vertical-align: middle;">
                                                                        <input type="color" name="bg_color"
                                                                            class="form-control jsBgColor"
                                                                            value="<?= $type['bg_color'] ?>" />
                                                                    </td>
                                                                    <td style="vertical-align: middle;">
                                                                        <input type="color" name="txt_color"
                                                                            class="form-control jsTxtColor"
                                                                            value="<?= $type['txt_color'] ?>" />
                                                                    </td>
                                                                </tr>
                                                            <?php }
                                                        } else { ?>
                                                            <tr>
                                                                <td colspan="3" class="text-center">
                                                                    <span class="no-data">No Compliance Report Types
                                                                        Found</span>
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
    $(function () {

        let XHR = null;

        let debounceTimer;
        $(document).on("keyup", ".jsLevel", function (event) {
            event.preventDefault();
            const recordId = $(this).closest("tr").data("id");
            const val = $(this).val();

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                updateLevel(recordId, val, "level");
            }, 600); // 300ms debounce delay
        });

        $(document).on("change", ".jsBgColor", function (event) {
            event.preventDefault();
            const recordId = $(this).closest("tr").data("id");
            const val = $(this).val();
            updateLevel(recordId, val, "bg");
        });
        $(document).on("change", ".jsTxtColor", function (event) {
            event.preventDefault();
            const recordId = $(this).closest("tr").data("id");
            const val = $(this).val();
            updateLevel(recordId, val, "tx");
        });


        function updateLevel(recordId, val, option) {
            $
                .ajax({
                    url: window.location.origin + "/manage_admin/compliance_safety/handle_severity_level/" + recordId,
                    method: "POST",
                    data: {
                        cl: val,
                        type: option,
                        id: recordId
                    }
                })
                .always()
                .fail(function (resp) {
                    alerify.alert(
                        "Errors!"
                    );
                })
                .done(function (resp) {
                    alertify.success(
                        resp.message
                    );
                });
        }

    });
</script>