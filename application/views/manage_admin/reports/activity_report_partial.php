<?php if ($report_type == 'daily') { ?>
    <div class="col-xs-12 col-sm-12 margin-top">
        <div class="row">
            <div class="bt-panel">
                <a href="javascript:;" class="btn btn-success" onclick="print_page('#main_container_for_ajax_response');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                <form method="post" id="form_export_csv_file" name="form_export_csv_file">
                    <input type="hidden" id="perform_action" name="perform_action" value="export_csv_file" />
                    <input type="hidden" id="hidden_report_date" name="report_date" value="<?php echo $report_date; ?>" />
                </form>
                <button class="btn btn-success" onclick="fExportCSV();">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    Export
                </button>
            </div>
        </div>
    </div>
<?php } elseif ($report_type == 'weekly') { ?>
    <div class="col-xs-12 col-sm-12 margin-top">
        <div class="row">
            <div class="bt-panel">
                <a href="javascript:;" class="btn btn-success" onclick="print_page('#main_container_for_ajax_response');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                <form method="post" id="form_export_csv_file" name="form_export_csv_file">
                    <input type="hidden" id="perform_action" name="perform_action" value="export_csv_file" />
                    <input type="hidden" id="hidden_start_date" name="start_date" value="<?php echo $start_date; ?>" />
                    <input type="hidden" id="hidden_end_date" name="end_date" value="<?php echo $end_date; ?>" />
                </form>
                <button class="btn btn-success" onclick="fExportCSV();">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    Export
                </button>
            </div>
        </div>
    </div>
<?php } ?>

<?php foreach ($companies_logs as $company_log) { ?>

    <?php $employers_logs = $company_log['activities_data']; ?>

    <?php if (!empty($employers_logs)) { ?>

        <div class="hr-box">
            <div class="hr-box-header bg-header-green">
                <h1 class="hr-registered pull-left"><span class="text-success"><?php echo ucwords($company_log['CompanyName']); ?></span></h1>
            </div>
            <div class="table-responsive hr-innerpadding daily-activity">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-left col-lg-2">Employer Name</th>
                            <th class="text-center col-lg-2">Total Time</th>
                            <th class="text-center col-lg-2">IP Address</th>
                            <th class="text-center col-lg-2">Login Duration</th>
                            <th class="text-left col-lg-4">User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employers_logs as $employers_log) { ?>
                            <?php $activity_logs = $employers_log['activity_logs']; ?>
                            <tr>
                                <td class="text-left text-success" style="vertical-align: middle;" rowspan="<?php echo count($activity_logs) + 1; ?>"><?php echo $employers_log['employer_name']; ?></td>
                                <td class="text-center text-success" style="vertical-align: middle;" rowspan="<?php echo count($activity_logs) + 1; ?>"><?php echo $employers_log['total_time_spent']; ?> Min</td>
                            </tr>
                            <?php foreach ($activity_logs as $key => $activity_log) { ?>
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;"><?php echo str_replace('_', '.', $key); ?></td>
                                    <td class="text-center" style="vertical-align: middle;"><?php echo $activity_log['time_spent']; ?> Min</td>
                                    <td class="text-left" style="vertical-align: middle;"><?php echo $activity_log['act_details']['user_agent']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php } ?>

<?php } ?>


<div class="col-xs-12 col-sm-12 margin-top">
    <div class="row">
        <div class="bt-panel">
            <a href="javascript:;" class="btn btn-success" onclick="print_page('#main_container_for_ajax_response');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
            <button class="btn btn-success" onclick="fExportCSV();">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                Export
            </button>
        </div>
    </div>
</div>
