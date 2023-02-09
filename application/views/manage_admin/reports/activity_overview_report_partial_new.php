<?php if (!empty($companies)) { ?>
    <?php foreach($companies as $company) { ?>
        <div class="hr-box" id="jsCompanyBlock<?=$company['sid'];?>">
            <div class="hr-box-header bg-header-green">
                <h1 class="hr-registered pull-left"><span class="text-success"><?php echo ucwords($company['CompanyName']); ?></span></h1>
            </div>
            <div class="table-responsive hr-innerpadding daily-activity" style="position: relative;">
                <div class="csIPLoader jsIPLoader<?=$company['sid'];?>">
                    <i class="fa fa-circle-o fa-spin" aria-hidden="true"></i>
                </div>
                <table class="table table-bordered  table-condensed" style="min-height: 150px;">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-left col-xs-2">Job Title</th>
                            <th class="text-left col-xs-2">Access Level</th>
                            <th class="text-left col-xs-3">Name</th>
                            <th class="text-left col-xs-2">Email</th>
                            <th class="text-left col-xs-2">Phone</th>
                        </tr>
                    </thead>
                    <tbody id="jsCompanyBody<?=$company['sid'];?>">
                        
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>

    <script>
        $(function TrackerReportLogger(){
            //
            var companiesOBJ = <?=json_encode(array_column($companies, 'sid'));?>;
            //
            var current = 0;
            var isTerminated = 0;
            var url = "";
            var megaOBJ = {};
            var reportType = '<?php echo $report_type; ?>';
            //
            var total = companiesOBJ.length;
            //
            var xhr = null;
            //
            window.stopProcess = stopProcess;
            //
            StartEmployeeReportProcess();
            //
            function stopProcess () {
                $('#main_container_for_ajax_response').html("");
                companiesOBJ = [];
                current = 0;
                total = 0;
                isTerminated = 1;
                //
                if(xhr !== null){
                    xhr.abort();
                    xhr = null;
                }
            }
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
                if(isTerminated == 1){
                    return stopProcess();
                }
                //
                if(xhr !== null){
                    setTimeout(function(){
                        GetEmployees(companyId);
                    }, 1000);
                    return;
                }
                //
                if (reportType == "daily") {
                    url = "<?=base_url("manage_admin/reports/daily_activity_overview_report/ajax_responder");?>";
                    //
                    var megaOBJ = {
                        "perform_action": "get_company_employee_report",
                        "company_sid": companyId,
                        "report_date" : '<?php echo $report_date; ?>'
                    }
                }
                //
                if (reportType == "weekly") {
                    url = "<?=base_url("manage_admin/reports/weekly_activity_overview_report/ajax_responder");?>";
                    //
                    var megaOBJ = {
                        "perform_action": "get_company_employee_report",
                        "company_sid": companyId,
                        "start_date" : '<?php echo $start_date; ?>',
                        "end_date" : '<?php echo $end_date; ?>'
                    }
                }
                //
                xhr = 
                $.post(
                    url,
                    megaOBJ
                ).done(function(resp){
                    //
                    xhr = null;
                    var html = '';
                    var show_html = 0;
                    //
                    if (resp.data.active_employees.length) {
                        //
                        show_html = 1;
                        //
                        html += '<tr class="bg-success">';
                        html += '   <th rowspan="'+(resp.data.active_employees.length+1)+'" style="vertical-align: middle;"><span class="text-success">Active</span></th>';
                        html += '</tr>';
                        //
                        resp.data.active_employees.map(function(record, index){
                            //
                            html += '<tr class="bg-success">';
                            html += '   <td style="vertical-align: middle;">'+record.job_title+'</td>';
                            html += '   <td style="vertical-align: middle;">'+record.access_level+'</td>';
                            html += '   <td style="vertical-align: middle;">'+record.employee_name+'</td>';
                            html += '   <td style="vertical-align: middle;">'+record.email+'</td>';
                            html += '   <td style="vertical-align: middle;">'+record.PhoneNumber+'</td>';
                            html += '</tr>';
                            //
                        });
                    } else {
                        html += '<tr class="bg-success">';
                        html += '   <th rowspan="'+(resp.data.active_employees.length+1)+'" style="vertical-align: middle;"><span class="text-success">Active</span></th>';
                        html += '       <td colspan="5" class="text-center">';
                        html += '           <div class="no-data">No Active Employers</div>';
                        html += '       </td>';
                        html += '</tr>';
                    }
                    //
                    if (resp.data.inactive_employees.length) {
                        //
                        show_html = 1;
                        //
                        html += '<tr class="bg-danger">';
                        html += '   <th rowspan="'+(resp.data.inactive_employees.length+1)+'" style="vertical-align: middle;"><span class="text-success">Inactive</span></th>';
                        html += '</tr>';
                        //
                        resp.data.inactive_employees.map(function(record, index){
                            //
                            html += '<tr class="bg-danger">';
                            html += '   <td style="vertical-align: middle;">'+record.job_title+'</td>';
                            html += '   <td style="vertical-align: middle;">'+record.access_level+'</td>';
                            html += '   <td style="vertical-align: middle;">'+record.employee_name+'</td>';
                            html += '   <td style="vertical-align: middle;">'+record.email+'</td>';
                            html += '   <td style="vertical-align: middle;">'+record.PhoneNumber+'</td>';
                            html += '</tr>';
                            //
                        });
                    } else {
                        html += '<tr class="bg-danger">';
                        html += '   <th rowspan="'+(resp.data.inactive_employees.length+1)+'" style="vertical-align: middle;"><span class="text-success">Inactive</span></th>';
                        html += '       <td colspan="5" class="text-center">';
                        html += '           <div class="no-data">No Inactive Employers</div>';
                        html += '       </td>';
                        html += '</tr>';
                    }
                    //
                    if (show_html == 1) {
                        $('#jsCompanyBody'+(companyId)+'').html(html);
                        $('.jsIPLoader'+(companyId)+'').hide(0);
                    } else {
                        $('#jsCompanyBlock'+(companyId)+'').hide();
                    }
                    //
                    current++;
                    //
                    StartEmployeeReportProcess();
                });
            }
        });
    </script>
<?php } ?>


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