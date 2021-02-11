<?php 
    $selectedRoles = isset($document_info['is_available_for_na']) && !empty($document_info['is_available_for_na']) ? explode(',', $document_info['is_available_for_na']) : [];
    $selectedEmployees = isset($document_info['allowed_employees']) && !empty($document_info['allowed_employees']) ? explode(',', $document_info['allowed_employees']) : [];
    $selectedDepartments = isset($document_info['allowed_departments']) && !empty($document_info['allowed_departments']) ? explode(',', $document_info['allowed_departments']) : [];
    $selectedTeams = isset($document_info['allowed_teams']) && !empty($document_info['allowed_teams']) ? explode(',', $document_info['allowed_teams']) : [];
    $roles = [
        'hiring_manager' => 'Hiring Manager',
        'admin' => 'Admin'
    ];
?>

<!-- Visibility to Roles and Employees -->
<div class="row">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                <strong>Visibility</strong>
            </div>
            <div class="hr-box-innerpadding">
                <!-- Roles -->
                <div class="col-xs-12">
                    <br />
                    <label>Select Roles <i class="fa fa-question-circle" data-toggle="propover" title="Note" data-content="This document will be visible to the selected Roles."></i></label>
                    <select multiple="true" id="js-roles" name="selected_roles[]">
                        <?php foreach($roles as $key => $role) { ?>
                            <option value="<?=$key;?>" <?=in_array($key, $selectedRoles) ? 'selected="true"' : '';?>><?=$role;?></option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Employees -->
                <div class="col-xs-12">
                    <label>Select Specific Employee(s) <i class="fa fa-question-circle" data-toggle="propover" title="Note" data-content="This document will be visible to the selected Employee(s)."></i></label>
                    <select multiple="true" id="js-specific-employee-visibility" name="selected_employees[]">
                        <?php foreach($employeesList as $key => $employee) { ?>
                            <?php if($employee['access_level_plus'] == 1 || $employee['access_level'] == 'Manager' || $employee['access_level'] == 'Employee') continue; ?>
                            <option value="<?=$employee['sid'];?>" <?=in_array($employee['sid'], $selectedEmployees) ? 'selected="true"' : '';?>><?=remakeEmployeeName($employee);?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Departments -->
                <div class="col-xs-12">
                    <label>Select Department(s) <i class="fa fa-question-circle" data-toggle="propover" title="Note" data-content="This document will be visible to the selected departments(s)."></i></label>
                    <select multiple="true" id="js-specific-department-visibility" name="selected_departments[]">
                        <option value="-1">All</option>
                        <?php foreach($departments as $key => $department) { ?>
                            <option value="<?=$department['sid'];?>" <?=in_array($department['sid'], $selectedDepartments) ? 'selected="true"' : '';?>><?=$department['name'];?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Teams -->
                <div class="col-xs-12">
                    <label>Select Team(s) <i class="fa fa-question-circle" data-toggle="propover" title="Note" data-content="This document will be visible to the selected teams(s)."></i></label>
                    <select multiple="true" id="js-specific-team-visibility" name="selected_teams[]">
                        <option value="-1">All</option>
                        <?php foreach($teams as $key => $team) { ?>
                            <option value="<?=$team['sid'];?>" <?=in_array($team['sid'], $selectedTeams) ? 'selected="true"' : '';?>><?=$team['name'];?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<style>.select2-container--default .select2-selection--multiple .select2-selection__rendered{ height: auto !important;}</style>

<script>
    $('#js-roles').select2({
        closeOnSelect: false
    });
    $('#js-specific-employee-visibility').select2({
        closeOnSelect: false
    });
    $('#js-specific-department-visibility').select2({
        closeOnSelect: false
    });
    $('#js-specific-team-visibility').select2({
        closeOnSelect: false
    });
    $('[data-toggle="propover"]').popover({
        trigger: 'hover',
        placement: 'right'
    });
</script>