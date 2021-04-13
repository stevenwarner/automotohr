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
                                                <div class="col-sm-3 col-xs-12">
                                                    <input type="text" readonly="true" class="form-control" id="jsReportStartDate" />
                                                </div>
                                                <div class="col-sm-1 hidden-xs">
                                                    <p class="ma10" style="text-align: center;"><i class="fa fa-minus" aria-hidden="true"></i></p>
                                                </div>
                                                <div class="col-sm-3 col-xs-12">
                                                    <input type="text" readonly="true" class="form-control" id="jsReportEndDate" />
                                                </div>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <div class="table-responsive">
                                            <table class="table table-striped table-condensed">
                                                <caption></caption>
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Employee #</th>
                                                        <th scope="col">Employee Name / Role</th>
                                                        <th scope="col">Department</th>
                                                        <th scope="col">Team</th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    if(!empty($company_employees)) { 
                                                        foreach($company_employees as $emp) { ?>
                                                    <tr class="jsReportEmployeeRow" data-id="<?=$emp['sid'];?>">
                                                        <td><?=!empty($emp['employee_number']) ? $emp['employee_number'] : $emp['sid'];?></td>
                                                        <td>
                                                            <strong><?=ucwords($emp['first_name'].' '.$emp['last_name']);?></strong>
                                                            <br /><?=remakeEmployeeName($emp, false);?>
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
                                                            <a class="btn btn-success jsReportLink" target="_blank" href="<?=base_url("timeoff/report/print/".( $emp['sid'] )."");?>">
                                                                <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print Report
                                                            </a>
                                                            <a class="btn btn-success jsReportLink" target="_blank" href="<?=base_url("timeoff/report/download/".( $emp['sid'] )."");?>">
                                                                <i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Report
                                                            </a>
                                                        </td>
                                                    </tr>
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

<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
<script>
    //
    let employeeList = <?=json_encode($company_employees);?>;
</script>