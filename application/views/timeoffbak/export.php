<?php $this->load->view('timeoff/includes/header'); ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">Export Time off</span>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <form id="form_export_employees" class="form-wrp" enctype="multipart/form-data" method="post" action="javascript:void(0)">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <label>Employee(s)</label>
                                                    <select class="form-control" name="app_sid" id="app_sid">
                                                        <?php if (!empty($allEmp)) { ?>
                                                            <option value="all" <?php if (in_array('all', $allEmp)) { ?> selected="selected" <?php } ?>>All Employees</option>
                                                            <?php foreach ($allEmp as $value) { ?>
                                                                <option value="<?= $value['user_id'] ?>">
                                                                    <?php echo remakeEmployeeName($value, true); ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option value="">No employee found</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <label>Status</label>
                                                    <select class="form-control" multiple="multiple" name="status[]" id="status">
                                                        <option value="all" selected>All</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="approved">Approved</option>
                                                        <option value="rejected">Rejected</option>
                                                        <option value="cancelled">Canceled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="field-row">
                                                    <label>Archive</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="form-control" name="archive" id="archive">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="field-row">
                                                    <label class="">Date From</label>
                                                    <input class="form-control" tabindex="-1" autocomplete="off" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="field-row">
                                                    <label class="">Date To</label>
                                                    <input class="form-control" tabindex="-1" autocomplete="off" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-12 col-xs-3 col-sm-3 col-sm-offset-9">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="btn btn-block btn-success">Export</button>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--  -->
                    <div class="row" id="js-progress-block" style="display: none;">
                        <div class="col-sm-12 d-block">
                            <h3>Fetching employees....</h3>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-success" role="progressbar" aria-valuenow="100%" aria-valuemin="100%" aria-valuemax="100%" style="width: 100%;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/chosen.jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/moment.min.js"></script>
<script>
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });


    $(document).ready(function() {

        //
        var pbr = $('#js-progress-block');

        $('#app_sid').select2();
        $('#status').select2();

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy'
        }).val();

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeYear: true,
            changeMonth: true,
            onSelect: function(value) {
                //console.log(value);
                $('#end_date_applied').datepicker('option', 'minDate', value);
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeYear: true,
            changeMonth: true,
            onSelect: function(value) {
                //console.log(value);
                $('#start_date_applied').datepicker('option', 'maxDate', value);
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());


        var filterOBJ = {
            action: 'export',
            companySid: "<?= $company_sid; ?>",
            employees: ['all'],
            status: ['all'],
            archive: 'no',
            fromDate: $("#start_date_applied").val(),
            toDate: $("#end_date_applied").val()
        };
        //
        function getNewFilter() {
            filterOBJ = {
                action: 'export',
                companySid: "<?= $company_sid; ?>",
                employees: $('#app_sid').val() === null ? ['all'] : $('#app_sid').val().split(','),
                status: $('#status').val() === null ? ['all'] : $('#status').val(),
                archive: $('#archive').val(),
                fromDate: $("#start_date_applied").val() === '' ? moment().format('YYYY-MM-DD') : moment($("#start_date_applied").val(), "MM-DD-YYYY").format('YYYY-MM-DD'),
                toDate: $("#end_date_applied").val() === '' ? moment().format('YYYY-MM-DD') : moment($("#end_date_applied").val(), "MM-DD-YYYY").format('YYYY-MM-DD')
            };
        }
        //
        $('#form_export_employees').submit(function(e) {
            e.preventDefault();
            fetchList();
        });

        //
        function fetchList() {
            exportData = {};
            keys = [];
            total = 0;
            index = 0;
            current = 0;
            fileName = moment().unix();
            //
            getNewFilter();
            //
            progress(true, 59, 'Fetching employees.');
            //
            let ii = 0;
            //
            $.post("<?= base_url('/timeoff/handler'); ?>", filterOBJ, (resp) => {
                progress(true, 100, 'Fetching employees.');
                if (resp.Status === true && resp.Data.length > 0) {
                    startExportProcess(resp.Data);
                } else {
                    //
                    alertify.alert("WARNING!", "No time-off found.");
                    progress(false, 0, '');
                }
            });
        }

        var exportData = {};
        var keys = [];
        var current = 0;
        var index = 0;
        var total = 0;
        var xhr = null;
        var fileName = moment().unix();
        //
        function resetData(timeoffs) {
            timeoffs.map((timeoff) => {
                if (exportData[timeoff.employee_sid] === undefined) {
                    exportData[timeoff.employee_sid] = {};
                    exportData[timeoff.employee_sid]['employee'] = {
                        sid: timeoff.employee_sid,
                        name: timeoff.first_name + ' ' + timeoff.last_name
                    };
                    exportData[timeoff.employee_sid]['timeoffs'] = [];
                }
                exportData[timeoff.employee_sid]['timeoffs'].push(timeoff.sid);
            });
            //
            keys = Object.keys(exportData);
        }

        // Step 1
        function startExportProcess(timeoffs) {
            //
            progress(true, 20, 'Preparing data.');
            // Rearrange data by user
            resetData(timeoffs);
            //
            progress(true, 100, 'Preparing data.');
            //
            step2();
        }

        //
        function step2() {
            if (keys[current] === undefined) {
                progress(false, 0);
                window.location.href = "<?= base_url('download_export_timeoff'); ?>/" + fileName;
                return;
            }
            //
            const row = exportData[keys[current]];
            //
            progress(true, 30, `Adding <strong>${row.employee.name}</strong> to export. <strong>${current + 1}</strong> out of <strong>${keys.length}</strong> `);
            //
            step3(row);
            console.log(row);
        }



        //
        function step3(row) {
            $.post("<?= base_url("timeoff/handler"); ?>", {
                action: 'export_by_sids',
                companySid: "<?= $company_sid; ?>",
                fileName: fileName,
                employeeName: row.employee.name.replace(/[^a-zA-Z]/g, '_').toLowerCase(),
                ids: row.timeoffs
            }, (resp) => {
                if (resp.Status === true) {
                    //
                    progress(true, 100, '', false);
                    current++;
                    setTimeout(step2, 1000);
                }
            }).fail(() => {
                setTimeout(() => {
                    step3(row);
                }, 1000);
            });
        }


        //
        function step4() {
            $.post("<?= base_url('timeoff/handler'); ?>", {
                action: 'get_export_link',
                companySid: "<?= $company_sid; ?>",
                fileName: fileName,
                employeeName: row.employee.name.replace(/[^a-zA-Z]/g, '_').toLowerCase(),
            }, (resp) => {
                //
                console.log(resp.Link);
                if (resp.Status === true) {}
            })
        }

        function progress(s, c, m, r) {
            if (s === true) pbr.fadeIn(500);
            else pbr.fadeOut(500);
            m = m === undefined ? 'Please wait, while we are processing your request.' : m;
            pbr.find('div.progress-bar').attr('aria-valuenow', c + '%');
            pbr.find('div.progress-bar').attr('aria-valuein', c + '%');
            pbr.find('div.progress-bar').css('width', c + '%');
            pbr.find('div.progress-bar').attr('aria-valuemax', '100%');
            if (r === undefined) pbr.find('h3').html(m);
        }

    });
</script>