<?php if(!empty($companies)): ?>
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
<?php endif; ?>

<?php if(!empty($companies)): 
    foreach ($companies as $company) { ?>
        <div class="hr-box">
            <div class="hr-box-header bg-header-green">
                <h1 class="hr-registered pull-left"><span class="text-success"><?php echo ucwords($company['company_name']); ?></span></h1>
            </div>
            <div class="table-responsive hr-innerpadding daily-activity" style="position: relative;">
                <div class="csIPLoader jsIPLoader<?=$company['company_sid'];?>">
                    <i class="fa fa-circle-o fa-spin" aria-hidden="true"></i>
                </div>
                <table class="table table-bordered  table-condensed" style="min-height: 150px;">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col" class="text-left col-lg-2">Employer Name</th>
                            <th scope="col" class="text-center col-lg-2">Total Time</th>
                            <th scope="col" class="text-center col-lg-2">IP Address</th>
                            <th scope="col" class="text-center col-lg-2">Login Duration</th>
                            <th scope="col" class="text-left col-lg-4">User Agent</th>
                        </tr>
                    </thead>
                    <tbody id="jsCompanyBody<?=$company['company_sid'];?>">
                        
                    </tbody>
                </table>
            </div>
        </div>

<?php }
else:
    ?>
    <p class="alert alert-info text-center">No records found</p>
    <?php
endif; ?>

<?php if(!empty($companies_logs)): ?>
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
<?php endif; ?>




<?php if(!empty($companies)): ?>

<script>
    $(function TrackerReportLogger(){
        //
        var companiesOBJ = <?=json_encode(array_column($companies, 'company_sid'));?>;
        //
        var start_date = "<?=$start_date;?>";
        var end_date = "<?=$end_date;?>";
        //
        var current = 0;
        //
        var total = companiesOBJ.length;
        //
        var xhr = null;
        //
        StartEmployeeReportProcess();
        //
        function StartEmployeeReportProcess(){
            //
            var index = current;
            //
            if(index > total){
                //
                console.log('The process is completed');
                //
                return;
            }
            //
            GetEmployees(companiesOBJ[index]);
        }
        //
        function GetEmployees(companyId){
            //
            if(xhr !== null){
                setTimeout(function(){
                    GetEmployees(companyId);
                }, 1000);
                return;
            }
            //
            xhr = 
            $.post(
                "<?=base_url("manage_admin/reports/daily_activity_report/get_employee/");?>/"+companyId,{
                    start_date: start_date,
                    end_date: end_date
                }
            ).done(function(resp){
                //
                xhr = null;
                //
                if(resp.data.length){
                    //
                    var html = '';
                    //
                    resp.data.map(function(record, index){
                        html += '<tr>';
                        html += '   <td class="text-left text-success" style="vertical-align: middle;" rowspan="'+(Object.keys(record.ips).length + 1)+'">';
                        html += record.employee_name;
                        html += '   </td>';
                        html += '   <td class="text-left text-success" style="vertical-align: middle;" rowspan="'+(Object.keys(record.ips).length + 1)+'">';
                        html += record.time_spent+' Minutes spent';
                        html += '   </td>';
                        html += '</tr>';
                        $.each(record.ips, function(k,v){
                            html += '<tr>';
                            html += '   <td class="text-left text-success" style="vertical-align: middle;">'+(k.replace(/_/g, '.'))+'</td>';
                            html += '   <td class="text-left text-success" style="vertical-align: middle;">'+(v.time_spent)+' Minutes Spent</td>';
                            html += '   <td class="text-left text-success" style="vertical-align: middle;">'+(v.user_agent)+'</td>';
                            html += '</tr>';
                        });
                    });
                    //
                    $('#jsCompanyBody'+(companyId)+'').html(html);
                } else{
                    //
                    $('#jsCompanyBody'+(companyId)+'').html('<tr><td colspan="4"><p class="alert alert-info text-center">No records found</p></td></tr>');
                }
                //
                $('.jsIPLoader'+(companyId)+'').hide(0);
                //
                current++;
                //
                StartEmployeeReportProcess();
            });
        }
    });
</script>

<?php endif;?>


<style>
    /* Common loader CSS */
    
    .csIPLoader {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        width: 100%;
        background: rgba(255, 255, 255, .9);
        z-index: 2;
    }
    
    .csIPLoader>i {
        position: relative;
        top: 50%;
        left: 50%;
        font-size: 50px;
        color: #fd7a2a;
        transform: translate(-50%, -50%);
    }
    
    .csIPLoader div {
        position: relative;
        top: 100px;
    }
    
    .csIPLoader div p {
        color: #333;
        text-align: center;
    }
    
    .csIPLoader div p i {
        font-size: 50px;
        color: #fd7a2a;
    }
</style>