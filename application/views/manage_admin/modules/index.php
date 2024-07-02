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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Modules</h1>
                                    </div>
                                    <div class="hr-search-criteria opened">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <!-- Search Table Start -->
                                    <div class="hr-search-main" style="display: block;">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <?php $uri_name = $this->uri->segment(3) ?>
                                                    <label>Module Name</label>
                                                    <input type="text" class="invoice-fields" name="module_name" value="<?php echo $uri_name != '' && $uri_name != 'all' ? $uri_name : ''  ?>" id="module_name" value="" />
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <?php $uri_stage = $this->uri->segment(6) ?>
                                                    <label>Stage</label>
                                                    <select name="stage" class="js-from full_width" id="stage">
                                                        <option value="all" <?php echo $uri_stage == 'all' ? 'selected' : '' ?>>All</option>
                                                        <option value="staging" <?php echo $uri_stage == 'staging' ? 'selected' : '' ?>>Staging</option>
                                                        <option value="production" <?php echo $uri_stage == 'production' ? 'selected' : '' ?>>Production</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <div class="field-row">
                                                    <?php $uri_disabled = $this->uri->segment(4) ?>
                                                    <label>Disabled </label><br>
                                                    <select name="is_disabled" class="js-from full_width" id="is_disabled">
                                                        <option value="all" <?php echo $uri_disabled == 'all' ? 'selected' : '' ?>>All</option>
                                                        <option value="1" <?php echo $uri_disabled == '1' ? 'selected' : '' ?>>Yes</option>
                                                        <option value="0" <?php echo $uri_disabled == '0' ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="field-row">
                                                    <?php $uri_ems = $this->uri->segment(5) ?>
                                                    <label>Available on EMS</label><br>
                                                    <select name="is_ems_module" class="js-from full_width" id="is_ems_module">
                                                        <option value="all" <?php echo $uri_ems == 'all' ? 'selected' : '' ?>>All</option>
                                                        <option value="1" <?php echo $uri_ems == '1' ? 'selected' : '' ?>>Yes</option>
                                                        <option value="0" <?php echo $uri_ems == '0' ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-lg-12 text-right">
                                            <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                                            <a class="btn btn-success" href="<?php echo base_url("manage_admin/modules") ?>">Reset Filters</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Search Table End -->
                                <!-- Email Logs Start -->
                                <div class="hr-box">
                                    <div class="hr-box-header bg-header-green">
                                        <span class="pull-left">
                                            <h1 class="hr-registered">Modules</h1>
                                        </span>

                                    </div>
                                    <div class="hr-innerpadding">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <span class="pull-left">
                                                    <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                                </span>
                                                <span>
                                                    <?php echo $links; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-2 text-center">Module Name</th>
                                                                <th class="col-xs-2 text-center">Stage</th>
                                                                <th class="col-xs-1 text-center">Disabled</th>
                                                                <th class="col-xs-2 text-center">Available on EMS</th>
                                                                <th class="col-xs-4 text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($module_data as $module) { ?>
                                                                <tr>
                                                                    <td><?php echo ucfirst($module['module_name']) ?></td>
                                                                    <td><?php echo ucfirst($module['stage']) ?></td>
                                                                    <td>
                                                                        <p class="text-<?php echo $module['is_disabled'] == 1 ? "danger" : "success" ?>"><?php echo $module['is_disabled'] == 1 ? "Yes" : "No" ?></p>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-<?php echo $module['is_ems_module'] == 0 ? "danger" : "success" ?>"><?php echo $module['is_ems_module'] == 0 ? "No" : "Yes" ?>
                                                                        <p></p>
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn btn-success" onclick="window.location.href = '<?php echo base_url('manage_admin/edit_module/' . $module['sid']) ?>' ">Edit</button>
                                                                        <?php if (strtolower($module['module_slug']) != 'payroll') { ?>
                                                                            &nbsp;
                                                                            <button class="btn btn-success" onclick="window.location.href='<?php echo base_url('manage_admin/company_module/' . $module['sid']) ?>'">Companies</button>
                                                                            <?php if (strtolower($module['module_slug']) == 'timeoff') { ?>
                                                                                <a href="<?= base_url('manage_admin/manage_time_off_icons/51'); ?> " class="btn btn-success">Manage Help Text</a>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <span class="pull-left">
                                                    <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                                </span>
                                                <span>
                                                    <?php echo $links; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Email Logs End -->
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
    //
    let XHR = null;
    //
    $(".js-from").select2();
    //
    function resend(id) {
        alertify.dialog('confirm')
            .set({
                'title ': 'Confirmation',
                'labels': {
                    ok: 'Yes',
                    cancel: 'No'
                },
                'message': 'Are you sure you want to Resend this Email?',
                'onok': function() {
                    url = "<?= base_url('manage_admin/resend_email') ?>" + '/' + id;
                    window.location.href = url;
                },
                'oncancel': function() {
                    alertify.error('Cancelled!');
                }
            }).show();
    }
    // 
    function generate_url() {
        var module_name = $('#module_name').val().trim();
        var is_disabled = $('#is_disabled').val().trim();
        var is_ems_module = $('#is_ems_module').val().trim();
        var stage = $('#stage').val().trim();
        module_name = module_name != '' && module_name != null && module_name != undefined && module_name != 0 ? encodeURIComponent(module_name) : 'all';
        is_disabled = is_disabled != '' && is_disabled != null && is_disabled != undefined ? encodeURIComponent(is_disabled) : 'all';
        is_ems_module = is_ems_module != '' && is_ems_module != null && is_ems_module != undefined ? encodeURIComponent(is_ems_module) : 'all';
        stage = stage != '' && stage != null && stage != undefined && stage != 0 ? encodeURIComponent(stage) : 'all';

        var url = '<?php echo base_url('manage_admin/modules'); ?>' + '/' + module_name + '/' + is_disabled + '/' + is_ems_module + '/' + stage;
        $('#btn_apply_filters').attr('href', url);
    }
    // 
    $(document).ready(function() {
        $('#btn_apply_filters').click(function() {
            generate_url();
        });

        /**
         * trigger click event
         */
        $(".jsRefreshToken").click(function(event) {
            //
            event.preventDefault();
            //
            if (XHR !== null) {
                return;
            }
            //
            XHR = $.ajax({
                    url: '<?php echo base_url("refresh/gusto/OAuthToken"); ?>',
                    method: "get",
                })
                .always(function() {
                    XHR = null;
                })
                .done(function(resp) {
                    alertify.confirm("Confirmation", "Are you sure you want to refresh OAuth token?",
                        function() {
                            openInNewTab(resp.url);
                        },
                        function() {

                        });

                });
        });

        function openInNewTab(url) {
            window.open(url, '_blank').focus();
        }


    });
</script>
<style>
    .full_width {
        width: 100%;
    }
</style>