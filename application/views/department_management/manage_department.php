<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php if($this->session->userdata('logged_in')['company_detail']['ems_status']){?>
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                <?php } else { ?>
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                <?php } ?> 
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <a href="<?php echo base_url('department_management'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Department Management
                        </a>
                        <?php echo $department_name; ?>
                    </span>
                </div>
                <!-- insert department button -->
                <div class="btn-panel text-right">
                    <div class="row">
                        <a class="btn btn-success" href="<?php echo base_url('department_management/add_edit_team').'/'.$department_sid; ?>">+ Add New Team</a>
                    </div>
                </div>
                <!-- insert department button -->
                <!-- main table view start -->
                <div class="dashboard-conetnt-wrp">
                    <div class="table-responsive table-outer">
                        <div id="show_no_jobs">   
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col-lg-3">Team Name</th>
                                        <th class="col-lg-3">Team Description</th> 
                                        <th class="col-lg-3">Team Lead(s)</th>
                                        <th class="col-lg-3">Approver(s)</th>
                                        <th class="col-lg-3">Reporting Manager(s)</th>
                                        <th class="col-lg-3 text-center">Actions</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <?php if (!isset($teams) || empty($teams)) { ?>
                                        <tr>
                                            <span class="applicant-not-found">No team found</span>
                                        <tr>
                                    <?php } else { ?>
                                        <?php foreach ($teams as $team) { ?>
                                            <tr id='row_<?php echo $team['sid']; ?>'>
                                                <td><?php echo ucwords($team['name']); ?></td>
                                                <td><?php echo html_entity_decode($team['description']); ?></td>
                                                <?php 
                                                    $spName = '<ul style="padding-left: 15px;">';
                                                    if(!empty($team['team_lead'])){
                                                        $t = explode(',', $team['team_lead']);
                                                        foreach($t as $f) $spName .= '<li><a href="'.(base_url('employee_profile/'.($f).'')).'" target="_blank" style="color: #000;">'.remakeEmployeeName(db_get_employee_profile($f)[0]). '</a> </li>';
                                                    } else{
                                                        $spName .= '-';
                                                    }
                                                    $spName .= '</ul>';
                                                    //
                                                    $approvers = '<ul style="padding-left: 15px;">';
                                                    if(!empty($team['approvers'])){
                                                        $t = explode(',', $team['approvers']);
                                                        foreach($t as $f) $approvers .= '<li><a href="'.(base_url('employee_profile/'.($f).'')).'" target="_blank" style="color: #000;">'.remakeEmployeeName(db_get_employee_profile($f)[0]). '</a> </li>';
                                                    } else{
                                                        $approvers .= '-';
                                                    }
                                                    $approvers .= '</ul>';

                                                    //
                                                    $rm = '<ul style="padding-left: 15px;">';
                                                    if(!empty($team['reporting_managers'])){
                                                        $t = explode(',', $team['reporting_managers']);
                                                        foreach($t as $f) $rm .= '<li><a href="'.(base_url('employee_profile/'.($f).'')).'" target="_blank" style="color: #000;">'.remakeEmployeeName(db_get_employee_profile($f)[0]). '</a> </li>';
                                                    } else{
                                                        $rm .= '-';
                                                    }
                                                    $rm .= '</ul>';
                                                ?>
                                                <td><?php echo $spName; ?></td>
                                                <td><?php echo $approvers; ?></td>
                                                <td><?php echo $rm; ?></td>
                                                <td class="text-center">
                                                    <a href="<?php echo base_url('department_management/add_edit_team').'/'.$department_sid.'/'.$team['sid']; ?>" class="btn btn-primary btn-sm" >
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="delete_team(this);" data-team-sid="<?php echo $team['sid']; ?>" class="btn btn-danger btn-sm" >
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <a href="<?php echo base_url('department_management/assign_employee').'/'.$department_sid.'/'.$team['sid']; ?>" class="btn btn-success btn-sm" >
                                                        Assign Employee
                                                    </a> 
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- main table view end -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function delete_team(source) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Delete this team?',
            function () {
                var team_sid = $(source).attr('data-team-sid');
                var myurl = "<?= base_url() ?>department_management/delete_department_and_team/team/"+team_sid;
            
                $.ajax({
                    type: "GET",
                    url: myurl,
                    async : false,
                    success: function (data) {
                        $('#row_'+team_sid).remove();
                        alertify.success('Team Deleted Successfully!');
                    },
                    error: function (data) {

                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    }


</script>
