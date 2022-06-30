<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">EEO form Candidates</span>
                    </div>
                    <div class="row">
                        <div class="applicant-reg-date">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <form id="form-filters" method="post" enctype="multipart/form-data" action="<?php echo base_url('eeo/export_excel'); ?>">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-col-100">
                                                <label for="startdate">Applicant Name</label>
                                                <?php $keyword = $keyword != 'all' ? $keyword : ''; ?>
                                                <input type="text" id="keyword" class="invoice-fields" name="keyword" value="<?php echo $keyword; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-col-100">
                                                <label for="startdate">Start Date</label>
                                                <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="Start Date" readonly="" value="<?php echo $startdate; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-col-100">
                                                <label for="enddate">End Date</label>
                                                <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="End Date" readonly="" value="<?php echo $enddate; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <label for="enddate">Opt Type</label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" name="opt_type" id="opt_type">
                                                    <option value="all" <?php echo $opt_type == 'all' ? 'selected="selected"' : "" ?>>All Applicants</option>
                                                    <option value="no" <?php echo $opt_type == 'no' ? 'selected="selected"' : "" ?>>Opted Out Applicants</option>
                                                    <option value="yes" <?php echo $opt_type == 'yes' ? 'selected="selected"' : "" ?>>Opted In Applicants</option>
                                                    <option value="other" <?php echo $opt_type == 'other' ? 'selected="selected"' : "" ?>>Opt Status Not Available</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="report-btns">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <button type="button" class="form-btn" onclick="fclearDateFilters();">Clear Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="report-btns">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <button type="submit" class="form-btn">Export CSV</button>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <button type="button" class="form-btn" onclick="fApplyDateFilters();">Apply Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hr-box">
                        <div class="hr-innerpadding">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php if ($total_records > 0) { ?>
                                        <span class="pull-left">
                                            <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                        </span>
                                    <?php } ?>
                                    <span class="pull-right">
                                        <?php echo $links; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Opt Status</th>
                                            <th>
                                                EEO Information
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($eeo_candidates)) { ?>
                                            <tr>
                                                <td colspan="4">
                                                    <span class="no-data">No Applicants</span>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <?php foreach ($eeo_candidates as $item) { ?>
                                                <tr id="manual_row<?= $item["application_list_sid"] ?>">
                                                    <td><?php echo isset($item["date_applied"]) ? reset_datetime(array('datetime' => $item["date_applied"], '_this' => $this)) : ''; ?></td>
                                                    <td>
                                                        <strong><?php echo ucwords($item['first_name'] . '&nbsp;' . $item['last_name']); ?></strong>
                                                        <small class="text-success">( <?php echo $item['applicant_type']; ?> )</small>
                                                        <br />
                                                        <small>IP: <?php echo $item["ip_address"]; ?></small>
                                                        <br />
                                                        <small>Job Title : <?php echo isset($item["job_title"]) && !empty($item["job_title"]) ? $item["job_title"] : 'Not Available'; ?></small>
                                                    </td>
                                                    <td>
                                                        <?php $opt_status = $item['eeo_form']; ?>
                                                        <?php if ($opt_status == 'Yes') { ?>
                                                            <span class="text-success">Opted In</span>
                                                        <?php } else if ($opt_status == 'No') { ?>
                                                            <span class="text-danger">Opted Out</span>
                                                        <?php } else if ($opt_status == null) { ?>
                                                            <span class="">Not Available</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <table class="table table-bordered table-condensed table-hover">
                                                            <tbody>
                                                                <tr>
                                                                    <th class="col-xs-4">Us Citizen</th>
                                                                    <td class="col-xs-8"><?php echo $item['eeo_form'] != null && !empty($item["us_citizen"]) ? $item["us_citizen"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Visa Status</th>
                                                                    <td><?php echo $item['eeo_form'] != null && !empty($item["visa_status"]) ? $item["visa_status"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Group Status</th>
                                                                    <td><?php echo $item['eeo_form'] != null && !empty($item["group_status"]) ? $item["group_status"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Veteran</th>
                                                                    <td><?php echo $item['eeo_form'] != null && !empty($item["veteran"]) ? $item["veteran"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Disability</th>
                                                                    <td><?php echo $item['eeo_form'] != null && !empty($item["disability"]) ? $item["disability"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Gender</th>
                                                                    <td><?php echo $item['eeo_form'] != null && !empty($item["gender"]) ? $item["gender"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>

                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php if ($total_records > 0) { ?>
                                        <span class="pull-left">
                                            <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                        </span>
                                    <?php } ?>
                                    <span class="pull-right">
                                        <?php echo $links; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php //echo '<pre>'; print_r($eeo_candidates);
?>
<script type="text/javascript">
    jQuery(function() {
        $("#startdate").datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() + 1);
                $("#enddate").datepicker("option", "minDate", dt);
            }
        }).on('focusin', function() {
            $(this).prop('readonly', true);
        }).on('focusout', function() {
            $(this).prop('readonly', false);
        });

        $("#enddate").datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date(),
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() - 1);
                $("#startdate").datepicker("option", "maxDate", dt);
            }
        }).on('focusin', function() {
            $(this).prop('readonly', true);
        }).on('focusout', function() {
            $(this).prop('readonly', false);
        });
    });

    function fApplyDateFilters() {
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();
        var keyword = $('#keyword').val();
        var opt_type = $('#opt_type').val();
        var url = '<?php echo base_url('eeo'); ?>';

        keyword = keyword != '' && keyword != null && keyword != undefined ? encodeURIComponent(keyword) : 'all';

        startDate = startDate != '' && startDate != null && startDate != undefined ? encodeURIComponent(startDate) : 'all';

        endDate = endDate != '' && endDate != null && endDate != undefined ? encodeURIComponent(endDate) : 'all';

        opt_type = opt_type != '' && opt_type != null && opt_type != undefined ? encodeURIComponent(opt_type) : 'all';

        url += '/' + keyword + '/' + opt_type + '/' + startDate + '/' + endDate

        console.log(url);

        window.location = url;
    }

    function excel_export() {
        console.log('i am in ajx');
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();
        var keyword = $('#keyword').val();
        var opt_type = $('#opt_type').val();

        var url = '<?php echo base_url('eeo/export_excel'); ?>';
        var dataToSend = {
            'startDate': startDate,
            'endDate': endDate,
            'keyword': keyword,
            'opt_type': opt_type,
            'action': 'export_csv'
        };

        var myRequest;
        myRequest = $.ajax({
            url: url,
            data: dataToSend,
            type: 'POST'
        });

        myRequest.done(function(response) {
            console.log(response);
        });
    }

    function fclearDateFilters() {
        var url = '<?php echo base_url(); ?>' + 'eeo/all/all';
        window.location = url;
    }
</script>