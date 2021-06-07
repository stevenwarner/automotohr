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

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>
                    <strong>Visibility</strong>&nbsp;<i class="fa fa-question-circle-o csClickable jsHintBtn" aria-hidden="true"  data-target="visibilty"></i>
                    <p class="jsHintBody" data-hint="visibilty"><br /><?=getUserHint('visibility_hint');?></p>
                </h5>
            </div>
            <div class="panel-body">
                <!-- Payroll -->
                <label class="control control--checkbox">
                    Visible To Payroll
                    <input type="checkbox" name="visible_to_payroll" class="js-payroll-offer-letter-add" <?php echo isset($document_info['visible_to_payroll']) && $document_info['visible_to_payroll'] == 1 ? 'checked="checked"' : ''; ?> value="1"/>
                    <div class="control__indicator"></div>
                </label>
                <hr />
                <!-- Roles -->
                <label>Roles</label>
                <select multiple="true" id="js-roles" name="selected_roles[]">
                    <?php foreach(getRoles() as $key => $role) { ?>
                        <option value="<?=$key;?>" <?=in_array($key, $selectedRoles) ? 'selected="true"' : '';?>><?=$role;?></option>
                    <?php } ?>
                </select>
                <br />
                <!-- Departments -->
                <label>Departments</label>
                <select multiple="true" id="js-specific-department-visibility" name="selected_departments[]">
                    <?php foreach($departments as $key => $department) { ?>
                        <option value="<?=$department['sid'];?>" <?=in_array($department['sid'], $selectedDepartments) ? 'selected="true"' : '';?>><?=$department['name'];?></option>
                    <?php } ?>
                </select>
                <br />
                <!-- Teams -->
                <label>Teams</label>
                <select multiple="true" id="js-specific-team-visibility" name="selected_teams[]">
                    <?php foreach($teams as $key => $team) { ?>
                        <option value="<?=$team['sid'];?>" <?=in_array($team['sid'], $selectedTeams) ? 'selected="true"' : '';?>><?=$team['name'];?></option>
                    <?php } ?>
                </select>
                <br />
                <!-- Employees -->
                <label>Employees</label>
                <select multiple="true" id="js-specific-employee-visibility" name="selected_employees[]">
                    <?php foreach($employeesList as $key => $employee) { ?>
                        <option value="<?=$employee['sid'];?>" <?=in_array($employee['sid'], $selectedEmployees) ? 'selected="true"' : '';?>><?=remakeEmployeeName($employee);?></option>
                    <?php } ?>
                </select>
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