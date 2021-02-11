<?php if ($report_type == 'weekly') { ?>
    <div class="col-xs-12 col-sm-12 margin-top">
        <div class="row"><?php // echo $links;         ?>
            <?php if (!empty($employers_logs)) { ?>
            <div class="bt-panel">
                <a href="javascript:;" class="btn btn-success" onclick="print_page('#main_container_for_ajax_response');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                <form method="post" id="form_export_csv" name="form_export_csv">
                    <input type="hidden" id="perform_action" name="perform_action" value="export_csv_file" />
                    <input type="hidden" id="hidden_start_date" name="start_date" value="<?php echo $start_date ?>" />
                    <input type="hidden" id="hidden_end_date" name="end_date" value="<?php echo $end_date ?>" />
                    <input type="hidden" id="hidden_company_sid" name="company_sid" value="<?php echo $company_sid ?>" />
                </form>
                <button class="btn btn-success" onclick="fExportCSV();">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    Export
                </button>
            </div>
            <?php } ?>
        </div>
    </div>
<?php } elseif ($report_type == 'daily') { ?>
    <div class="col-xs-12 col-sm-12 margin-top">
        <div class="row"><?php // echo $links;         ?>
            <?php if (!empty($employers_logs)) { ?>
            <div class="bt-panel">
                <a href="javascript:;" class="btn btn-success" onclick="print_page('#main_container_for_ajax_response');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                <form method="post" id="form_export_csv" name="form_export_csv">
                    <input type="hidden" id="perform_action" name="perform_action" value="export_csv_file" />
                    <input type="hidden" id="hidden_company_sid" name="company_sid" value="<?php echo $company_sid ?>" />
                    <input type="hidden" id="hidden_report_date" name="report_date" value="<?php echo $report_date ?>" />
                </form>
                <button class="btn btn-success" onclick="fExportCSV();">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    Export
                </button>
            </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>


<?php if (!empty($employers_logs)) { ?>

    <?php foreach ($employers_logs as $employers_log) { ?>
        <div class="hr-box">
            <div class="hr-box-header bg-header-green">
                <h1 class="hr-registered pull-left"><span class="text-success"><?php echo $employers_log['employer_name']; ?></span></h1>
                <div class="pull-right"><strong>Total : </strong> <?php echo $employers_log['total_time_spent']; ?> Minutes</div>
            </div>
            <div class="table-responsive hr-innerpadding">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center col-lg-2">IP Address</th>
                            <th class="text-center col-lg-2">Login Duration</th>
                            <th class="col-lg-8">User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employers_log['activity_logs'] as $key => $log) { ?>
                            <tr>
                                <td class="text-center"><?php echo str_replace('_', '.', $key); ?></td>
                                <td class="text-center"><?php echo $log['time_spent']; ?> Minutes</td>
                                <td class="text-left"><?php echo $log['act_details']['user_agent']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>

<?php } else { ?>
    <div class="text-center">
        <span class="no-data">
            No Activity
        </span>
    </div>
<?php } ?>

<?php if (!empty($employers_logs)) { ?>
<div class="col-xs-12 col-sm-12 margin-top">
    <div class="row"><?php // echo $links;         ?>
        <div class="bt-panel">
            <a href="javascript:;" class="btn btn-success" onclick="print_page('#main_container_for_ajax_response');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
            <button class="btn btn-success" onclick="fExportCSV();">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                Export
            </button>
        </div>
    </div>
</div>
<?php } ?>