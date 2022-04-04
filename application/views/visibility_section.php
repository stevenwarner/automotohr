<?php
$selectedRoles = isset($settings['allowed_roles']) && !empty($settings['allowed_roles']) ? explode(',', $settings['allowed_roles']) : [];
$selectedEmployees = isset($settings['allowed_employees']) && !empty($settings['allowed_employees']) ? explode(',', $settings['allowed_employees']) : [];
$selectedDepartments = isset($settings['allowed_departments']) && !empty($settings['allowed_departments']) ? explode(',', $settings['allowed_departments']) : [];
$selectedTeams = isset($settings['allowed_teams']) && !empty($settings['allowed_teams']) ? explode(',', $settings['allowed_teams']) : [];
$roles = [
    'hiring_manager' => 'Hiring Manager',
    'admin' => 'Admin'
];
?>



<div class="row">
    <div class="col-sm-12">
        <!-- Roles -->
        <label class="csF14 csB4">Roles</label>
        <select multiple="true" id="js-roles" name="selected_roles[]">
            <?php foreach (getRoles() as $key => $role) { ?>
                <option value="<?= $key; ?>" <?= in_array($key, $selectedRoles) ? 'selected="true"' : ''; ?>><?= $role; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <br />
        <!-- Departments -->
        <label class="csF14 csB4">Departments</label>
        <select multiple="true" id="js-specific-department-visibility" name="selected_departments[]">
            <?php foreach ($departments as $key => $department) { ?>
                <option value="<?= $department['sid']; ?>" <?= in_array($department['sid'], $selectedDepartments) ? 'selected="true"' : ''; ?>><?= $department['name']; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <br />
        <!-- Teams -->
        <label class="csF14 csB4">Teams</label>
        <select multiple="true" id="js-specific-team-visibility" name="selected_teams[]">
            <?php foreach ($teams as $key => $team) { ?>
                <option value="<?= $team['sid']; ?>" <?= in_array($team['sid'], $selectedTeams) ? 'selected="true"' : ''; ?>><?= $team['name']; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <br />
        <!-- Employees -->
        <label class="csF14 csB4">Employees</label>
        <select multiple="true" id="js-specific-employee-visibility" name="selected_employees[]">
            <?php foreach ($employees as $emp) { ?>
                <option value="<?= $emp['sid']; ?>" <?= in_array($emp['sid'], $selectedEmployees) ? 'selected="true"' : ''; ?>><?= $emp['name'] . $emp['role']; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <br>
        <!-- Payroll -->
        <label class="control control--checkbox csF14 csB4">
            Visible To Payroll
            <input type="checkbox" name="is_visible_to_payroll" class="is_visible_to_payroll" <?php echo isset($settings['is_visible_to_payroll']) && $settings['is_visible_to_payroll'] == 1 ? 'checked="checked"' : ''; ?> value="1" />
            <div class="control__indicator"></div>
        </label>
    </div>
</div>

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: auto !important;
    }
</style>

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