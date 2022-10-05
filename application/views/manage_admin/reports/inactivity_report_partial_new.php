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
            var start_date = "<?=$start_date;?>";
            var end_date = "<?=$end_date;?>";
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
                    "<?=base_url("manage_admin/reports/daily_inactivity_report/ajax_responder");?>",
                    {
                        "perform_action": "get_company_employee_report",
                        company_sid: companyId,
                        "report_date" : '<?php echo $report_date; ?>'
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
                            //
                            show_html = 1;
                            //
                            html += '<tr>';
                            html += '   <td style="vertical-align: middle;">';
                            html +=     record.job_title ? record.job_title.toUpperCase() : 'Not Available';
                            html += '   </td>';
                            html += '   <td style="vertical-align: middle;">';
                            html +=     record.access_level.toUpperCase();
                            html += '   </td>';
                            html += '   <td style="vertical-align: middle;">';
                            html +=     record.first_name.toUpperCase()+' '+record.last_name.toUpperCase();
                            html += '   </td>';
                            html += '   <td style="vertical-align: middle;">';
                            html +=     record.email;
                            html += '   </td>';
                            html += '   <td style="vertical-align: middle;">';
                            html +=     record.PhoneNumber ? record.PhoneNumber.toUpperCase() : 'Not Available';
                            html += '   </td>';
                            html += '</tr>';
                            //
                        });
                        //
                        $('#jsCompanyBody'+(companyId)+'').html(html);
                        
                    } else {
                        //
                        $('#jsCompanyBlock'+(companyId)+'').hide();
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