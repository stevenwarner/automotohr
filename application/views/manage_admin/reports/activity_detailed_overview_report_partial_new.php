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
                            <th class="col-xs-1 text-center"></th>
                            <th class="col-xs-2 text-left">Job Title<br />Access Level</th>
                            <th class="text-left">Contact Information</th>
                            <th class="text-left" colspan="2">Activity Details</th>
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
                xhr = 
                $.post(
                    "<?=base_url("manage_admin/reports/daily_activity_detailed_overview_report/ajax_responder");?>",
                    {
                        "perform_action": "get_company_employee_report",
                        "company_sid": companyId,
                        "report_date" : '<?php echo $report_date; ?>'
                    }
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
                        html += '<tr>';
                        html += '   <th rowspan="'+(resp.data.active_employees.length+1)+'" style="vertical-align: middle;"><span>Active</span></th>';
                        html += '</tr>';
                        //
                        resp.data.active_employees.map(function(record, index){
                            //
                            html += '<tr>';
                            html += '   <td>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-black-tie"></i> &nbsp;'+record.job_title+'</p>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-lock"></i> &nbsp;'+record.access_level+'</p>';
                            html += '   </td>';
                            html += '   <td>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-user"></i> &nbsp;'+record.employee_name+'</p>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-envelope"></i> &nbsp;'+record.email+'</p>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-phone"></i> &nbsp;'+record.PhoneNumber+'</p>';
                            html += '   </td>';
                            html += '   <td>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-clock-o"></i> &nbsp;'+record.total_time+'</p>';
                            html += '   </td>';
                            html += '   <td>';
                            $.each(record.ips, function(ip_address, ip_detail) {
                                html += '       <p style="font-size: 12px;">'+ip_address+', &nbsp;'+ip_detail.minutes+' Mins,&nbsp'+ip_detail.user_agent+'</p>';
                            });
                            html += '   </td>';
                            html += '</tr>';
                            //
                        });
                    } else {
                        html += '<tr>';
                        html += '   <td rowspan="'+(resp.data.active_employees.length+1)+'" style="vertical-align: middle;"><span class="">Active</span></td>';
                        html += '   <td colspan="5" class="text-center">';
                        html += '       <div class="no-data">No Active Employers</div>';
                        html += '   </td>';
                        html += '</tr>';
                    }
                    //
                    if (resp.data.inactive_employees.length) {
                        //
                        show_html = 1;
                        //
                        html += '<tr>';
                        html += '   <th rowspan="'+(resp.data.inactive_employees.length+1)+'" style="vertical-align: middle;"><span>Inactive</span></th>';
                        html += '</tr>';
                        //
                        resp.data.inactive_employees.map(function(record, index){
                            //
                            html += '<tr>';
                            html += '   <td>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-black-tie"></i> &nbsp;'+record.job_title+'</p>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-lock"></i> &nbsp;'+record.access_level+'</p>';
                            html += '   </td>';
                            html += '   <td>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-user"></i> &nbsp;'+record.employee_name+'</p>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-envelope"></i> &nbsp;'+record.email+'</p>';
                            html += '       <p style="font-size: 12px;"><i class="fa fa-phone"></i> &nbsp;'+record.PhoneNumber+'</p>';
                            html += '   </td>';
                            html += '   <td class="text-center" colspan="2"><span class="no-data">No Activity</span></td>';
                            html += '</tr>';
                            //
                        });
                    } else {
                        html += '<tr>';
                        html += '   <td rowspan="'+(resp.data.active_employees.length+1)+'" style="vertical-align: middle;"><span class="">Active</span></td>';
                        html += '   <td colspan="5" class="text-center">';
                        html += '       <div class="no-data">No Active Employers</div>';
                        html += '   </td>';
                        html += '</tr>';
                    }
                    //
                    if (show_html == 1) {
                        console.log(html)
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