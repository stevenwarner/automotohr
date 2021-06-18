<?php $this->load->view('timeoff/includes/header'); ?>

<style>
    .form-group label{
        font-size: 16px !important;
    }
    table.table thead tr th{
        font-size: 16px !important;

    }
    table.table tbody tr td,
    table.table tbody tr td a.btn{
        font-size: 14px !important;
    }

    .timeoff_count{
        width: 40px;
        height: 40px;
        background: #000;
        padding: 4px;
        color: #fff;
        line-height: 15px;
        border-radius: 100%;
    }
</style>

<?php 
    $jobTitles = [];
    $employees = '';
    $departments = '';
    $teams = '';
    //
    $departmentArray = $teamArray = [];
    //
    if(!empty($company_employees)){
        foreach($company_employees as $key => $emp){
            $employees .= '<option value="'.($emp['sid']).'">'.( remakeEmployeeName($emp) ).'</option>';
            //
            $company_employees[$key]['DepartmentIds'] = array_values($emp['DepartmentIds']);
            $company_employees[$key]['TeamIds'] = array_values($emp['TeamIds']);
            //
            $slug = trim(preg_replace('/[^a-zA-Z]/i', '', strtolower($emp['job_title'])));
            //
            if(!empty($slug)){
                $jobTitles[$slug] = [ 
                    $slug,
                    $emp['job_title'] 
                ];
            }
        }
        //
        asort($jobTitles);
    }
    //
    if(!empty($DT['Departments'])){
        foreach($DT['Departments'] as $v){
            $departments .= '<option value="'.($v['department_id']).'">'.( $v['title'] ).'</option>';
            //
            $departmentArray[$v['department_id']] = $v['title'];
        }
    }
    //
    if(!empty($DT['Teams'])){
        foreach($DT['Teams'] as $v){
            $teams .= '<option value="'.($v['team_id']).'">'.( $v['title'] ).'</option>';
            //
            $teamArray[$v['team_id']] = $v['title'];
        }
    }
?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="csPIPage">
                        <!-- Loader -->
                        <div class="csIPLoader jsIPLoader dn" data-page="report"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i></div>

                        <!--  -->
                        <div class="csPageWrap">
                            <div class="csPageHeader pl10 pr10 pb10">
                                <h3> <strong>Report</strong>
                                    <span class="pull-right">
                                        <a class="btn btn-success" href="javascript:;" id="view_report"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Report</a>
                                        <a class="btn btn-success jsReportLinkBulk" target="_blank" href="<?=base_url("timeoff/report/print");?>"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print Report</a>
                                        <a class="btn btn-success jsReportLinkBulk" target="_blank" href="<?=base_url("timeoff/report/download");?>"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Report</a>
                                    </span>
                                </h3>
                            </div>
                            <div class="csPageBody p10">
                                <div class="row">
                                    <!-- Filter -->
                                    <div class="col-sm-3 col-xs-12">
                                        <!--  -->
                                        <div class="form-group bbb">
                                            <label><strong>Filter Employees</strong></label>
                                        </div>
                                        <!--  -->
                                        <div class="form-group">
                                            <label>Individual Employees</label>
                                            <select id="jsIndividualEmployees" multiple="true"><?=$employees;?></select>
                                        </div>
                                        <!--  -->
                                        <div class="form-group">
                                            <label>Departments</label>
                                            <select id="jsDepartments" multiple="true"><?=$departments;?></select>
                                        </div>
                                        <!--  -->
                                        <div class="form-group">
                                            <label>Teams</label>
                                            <select id="jsTeams" multiple="true"><?=$teams;?></select>
                                        </div>
                                        <!--  -->
                                        <div class="form-group">
                                            <label>Job Titles</label>
                                            <select id="jsJobTitles" multiple="true">
                                            <?php 
                                            if(!empty($jobTitles)){
                                                foreach($jobTitles as $job){ ?>
                                                <option value="<?=$job[0];?>"><?=$job[1];?></option>
                                            <?php }    
                                            } ?>
                                            </select>
                                        </div>
                                        <!--  -->
                                        <div class="form-group">
                                            <label>Employment Types</label>
                                            <select id="jsEmploymentTypes" multiple="true">
                                                <option value="fulltime">Full-time</option>
                                                <option value="parttime">Part-time</option>
                                            </select>
                                        </div>
                                        <!--  -->
                                        <div class="form-group">
                                            <label>New Hires</label>
                                            <select id="jsNewHires">
                                                <option value="0">[Select]</option>
                                                <option value="30">Up to 30 days from hire date</option>
                                                <option value="60">Up to 60 days from hire date</option>
                                                <option value="90">Up to 90 days from hire date</option>
                                            </select>
                                        </div>
                                        <!--  -->
                                        <div class="form-group">
                                            <button class="btn btn-black form-control" id="jsReportClearFilter">Clear Filter</button>
                                        </div>
                                    </div>
                                    <!-- Employee listing area -->
                                    <div class="col-sm-9 col-xs-12">
                                        <!--  -->
                                        <div class="form-group">
                                            <label>Select Period</label>
                                            <div class="row">
                                                <form action="" method="POST">
                                                    <div class="col-sm-3 col-xs-12">
                                                        <?php $sfd = !empty($start_date) ? $start_date : ''; ?>
                                                        <input type="text" readonly="true" class="form-control" name="reportStartDate" id="jsReportStartDate" value="<?php echo $sfd; ?>"/>
                                                    </div>
                                                    <div class="col-sm-1 hidden-xs">
                                                        <p class="ma10" style="text-align: center;"><i class="fa fa-minus" aria-hidden="true"></i></p>
                                                    </div>
                                                    <div class="col-sm-3 col-xs-12">
                                                        <?php $efd = !empty($end_date) ? $end_date : ''; ?>
                                                        <input type="text" readonly="true" class="form-control" name="reportEndDate" id="jsReportEndDate" value="<?php echo $efd; ?>"/>
                                                    </div>
                                                    <div class="col-sm-2 col-xs-12">
                                                        <button class="btn btn-success form-control">Apply Filter</button>
                                                    </div>
                                                </form>
                                                
                                                <div class="col-sm-2 col-xs-12">
                                                    <a href="<?php echo base_url('timeoff/report'); ?>" class="btn btn-black form-control">Clear Filter</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <div class="table-responsive">
                                            <table class="table table-striped table-condensed">
                                                <caption></caption>
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Employee Name / Role / ID</th>
                                                        <th scope="col">Department</th>
                                                        <th scope="col">Team</th>
                                                        <th scope="col">Time Off</th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    if(!empty($company_employees)) { 
                                                        foreach($company_employees as $emp) { ?>
                                                    <tr class="jsReportEmployeeRow" data-id="<?=$emp['sid'];?>">
                                                        <td>
                                                            <strong><?=ucwords($emp['first_name'].' '.$emp['last_name']);?></strong>
                                                            <br /><?=remakeEmployeeName($emp, false);?>
                                                            <br><?=!empty($emp['employee_number']) ? $emp['employee_number'] : $emp['sid'];?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                if(!empty($emp['DepartmentIds'])){
                                                                    //
                                                                    $t = '';
                                                                    foreach($emp['DepartmentIds'] as $v){
                                                                        $t .= $departmentArray[$v].', ';
                                                                    }
                                                                    //
                                                                    echo rtrim($t, ', ');
                                                                } else{
                                                                    echo 'N/A';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                if(!empty($emp['TeamIds'])){
                                                                    //
                                                                    $t = '';
                                                                    foreach($emp['TeamIds'] as $v){
                                                                        $t .= $teamArray[$v].', ';
                                                                    }
                                                                    //
                                                                    echo rtrim($t, ', ');
                                                                } else{
                                                                    echo 'N/A';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <span class="timeoff_count" data-status="hide" data-id="timeoff_<?php echo $emp['sid']; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo count($emp['timeoffs']).' Time off'; ?>"><?php echo count($emp['timeoffs']); ?></span>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-success jsReportLink" target="_blank" href="<?=base_url("timeoff/report/print/".( $emp['sid'] )."");?>">
                                                                <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print Report
                                                            </a>
                                                            <a class="btn btn-success jsReportLink" target="_blank" href="<?=base_url("timeoff/report/download/".( $emp['sid'] )."");?>">
                                                                <i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Report
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php if (!empty($emp['timeoffs'])) { ?>
                                                        <!-- <div class="row" > -->
                                                            <tr class="timeoff_<?php echo $emp['sid']; ?>" style="display: none;">
                                                                <th>Policy Name</th>
                                                                <th>Request Time</th>
                                                                <th>Start Time</th>
                                                                <th>End Time</th>
                                                                <th>Request Status</th>
                                                            </tr>
                                                            <?php foreach ($emp['timeoffs'] as $timeoff) { ?>
                                                                <tr class="timeoff_<?php echo $emp['sid']; ?>" style="display: none;">
                                                                    <td><?php echo $timeoff['policy_name']; ?></td>
                                                                    <?php 
                                                                        $hours = floor($timeoff['requested_time'] / 60); 
                                                                        if ($hours > 1) {
                                                                            $hours = $hours.' Hours';
                                                                        } else {
                                                                            $hours = $hours.' Hour';
                                                                        }
                                                                    ?>
                                                                    <td><?php echo $hours; ?></td>
                                                                    <td><?php echo DateTime::createfromformat('Y-m-d', $timeoff['request_from_date'])->format('m/d/Y'); ?></td>
                                                                    <td><?php echo DateTime::createfromformat('Y-m-d', $timeoff['request_to_date'])->format('m/d/Y'); ?></td>
                                                                    <td><?php echo $timeoff['status']; ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <!-- </div>    -->
                                                    <?php } ?>
                                                    <?php 
                                                        }
                                                    } ?>
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

<div class="modal fade" id="js-view-report-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="float: none;">
                <div class="row">
            <div class="col-xs-6">
                <p>Company: <strong><?=$session['company_detail']['CompanyName'];?></strong></p>
                <p>Employee Name: <strong><?=ucwords($session['employer_detail']['first_name'].' '.$session['employer_detail']['last_name']);?> <?=remakeEmployeeName($session['employer_detail'], false);?></strong></p>
                <p>Report Period: <strong>
                <?php 
                    if($this->input->get('start', true) && $this->input->get('end', true)){
                        echo $this->input->get('start', true).' - '.$this->input->get('end', true);
                    } else if($this->input->get('start', true)){
                        echo $this->input->get('start', true).' - N/A';
                    } else if($this->input->get('end', true)){
                        echo 'N/A - '.$this->input->get('end', true);
                    } else{
                        echo 'N/A';
                    }
                    ?>
                </strong>
                <p>
            </div>
            <div class="col-xs-6">
               <p class="text-right">Report Date <strong><?=date('m/d/Y H:i', strtotime('now'));?></strong></p>
            </div>
            <hr />
        </div>
        <table class="table table-striped table-condensed table-bordered">
            <caption></caption>
            <thead>
                <tr>
                    <th scope="col">Employee</th>
                    <th scope="col">Policy</th>
                    <th scope="col">Time Taken</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($company_employees)) { ?>
                    <?php foreach($company_employees as $employee) { ?>
                        
                            <?php 
                                $employee_name = ucwords($employee['first_name'].' '.$employee['last_name']);
                                $employee_role = remakeEmployeeName($employee, false);
                                $employee_no = !empty($employee['employee_number']) ? $employee['employee_number'] : $employee['sid'];
                            ?>
                            <? foreach ($employee['timeoffs'] as $timeoff) { ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?php echo $employee_name; ?>
                                        </strong>   
                                        <br>
                                        <?php echo $employee_role; ?>
                                        <br>
                                        <?php echo $employee_no; ?>
                                    </td>
                                    <td><?php echo $timeoff['policy_name']; ?></td>
                                    <?php 
                                        $hours = floor($timeoff['requested_time'] / 60); 
                                        if ($hours > 1) {
                                            $hours = $hours.' Hours';
                                        } else {
                                            $hours = $hours.' Hour';
                                        }
                                    ?>
                                    <td><?php echo $hours; ?></td>
                                    <td>
                                        <?php echo DateTime::createfromformat('Y-m-d', $timeoff['request_from_date'])->format('m/d/Y'); ?>
                                    </td>
                                    <td>
                                        <?php echo DateTime::createfromformat('Y-m-d', $timeoff['request_to_date'])->format('m/d/Y'); ?>
                                    </td>
                                    <td><?php echo $timeoff['status']; ?></td>
                                </tr>
                            <?php } ?>
                        
                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan="5">Sorry, no records found.</td></tr>;
                <?php } ?>
            </tbody>
        </table>
                
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
<script>
    //
    let employeeList = <?=json_encode($company_employees);?>;

    $('#view_report').on("click", function () {
        $("#js-view-report-modal").modal('show')
    });

    $('.timeoff_count').on('click', function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');

        if (status == 'hide') {
            $('.'+id).show();
            $(this).attr('data-status', 'show');
        } else {
            $('.'+id).hide();
            $(this).attr('data-status', 'hide');
        }
        
    })
</script>