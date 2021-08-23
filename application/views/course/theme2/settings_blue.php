<?php
    // $roles = isset($settings['roles']) && !empty($settings['roles']) ? json_decode($settings['roles'], true) : array();
    // $departments = isset($settings['departments']) && !empty($settings['departments']) ? json_decode($settings['departments'], true) : array();
    // $teams = isset($settings['teams']) && !empty($settings['teams']) ? json_decode($settings['teams'], true) : array();
    // $employees = isset($settings['employees']) && !empty($settings['employees']) ? json_decode($settings['employees'], true) : array();
    $roles = isset($settings['roles']) && !empty($settings['roles']) ? $settings['roles'] : array();
    $departments = isset($settings['departments']) && !empty($settings['departments']) ? $settings['departments'] : array();
    $teams = isset($settings['teams']) && !empty($settings['teams']) ? $settings['teams'] : array();
    $employees = isset($settings['employees']) && !empty($settings['employees']) ? $settings['employees'] : array();
?>
<div class="col-md-9 col-sm-12">
    <!--  -->
  <!--   <div class="csIPLoader jsIPLoader" data-page="settings">
        <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
    </div> -->

    <!-- Visibility -->
    <div class="panel panel-theme">
        <div class="panel-heading">
            <p class="csF16 csB7 csW mb0">Visibility <small>(Who can manage courses)</small></p>
        </div>
        <div class="panel-body jsPageBody" data-page="visibility">
            <!-- Roles -->
            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <label class="csF16 csB7">Role(s) <i class="fa fa-question-circle-o jsHintBtn" data-target="title"
                            aria-hidden="true"></i></label>
                    <p class="csF14 jsHintBody" data-hint="title">The selected Role(s) can manage this course.</p>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <select id="jsCourseRolesInp" multiple>
                        <?php if (!empty($roles)) { ?>
                            <?php   foreach(getRoles() as $index => $role): ?>
                            <option value="<?=$index;?>" <?=!empty($roles) && in_array($index, $roles) ? 'selected' : '';?>><?=$role;?></option>
                            <?php   endforeach; ?>
                        <?php } ?>    
                    </select>
                </div>
            </div>

            <!-- Departments -->
            <div class="row">
                <br />
                <div class="col-sm-4 col-xs-12">
                    <label class="csF16 csB7">Department(s) <i class="fa fa-question-circle-o jsHintBtn"
                            data-target="title" aria-hidden="true"></i></label>
                    <p class="csF14 jsHintBody" data-hint="title">The selected Department(s) supervisors can manage this
                        course.</p>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <select id="jsCourseDepartmentsInp" multiple>
                        <?php if(!empty($company_dt['Departments'])): ?>
                        <?php   foreach($company_dt['Departments'] as $department): ?>
                        <option value="<?=$department['Id'];?>" <?=!empty($departments) && in_array($department['Id'], $departments) ? 'selected' : '';?>><?=$department['Name'];?></option>
                        <?php   endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Teams -->
            <div class="row">
                <br />
                <div class="col-sm-4 col-xs-12">
                    <label class="csF16 csB7">Team(s) <i class="fa fa-question-circle-o jsHintBtn" data-target="title"
                            aria-hidden="true"></i></label>
                    <p class="csF14 jsHintBody" data-hint="title">The selected Team(s) team leads can manage this
                        course.</p>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <select id="jsCourseTeamsInp" multiple>
                        <?php if(!empty($company_dt['Teams'])): ?>
                        <?php   foreach($company_dt['Teams'] as $team): ?>
                        <option value="<?=$team['Id'];?>" <?=!empty($teams) && in_array($team['Id'], $teams) ? 'selected' : '';?>><?=$team['Name'];?></option>
                        <?php   endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Employees -->
            <div class="row">
                <br />
                <div class="col-sm-4 col-xs-12">
                    <label class="csF16 csB7">Employee(s) <i class="fa fa-question-circle-o jsHintBtn"
                            data-target="title" aria-hidden="true"></i></label>
                    <p class="csF14 jsHintBody" data-hint="title">The selected Employee(s) can manage this course.</p>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <select id="jsCourseEmployeesInp" multiple>
                        <?php if(!empty($company_employees)): ?>
                        <?php   foreach($company_employees as $employee): ?>
                        <option value="<?=$employee['Id'];?>" <?=!empty($employees) && in_array($employee['Id'], $employees) ? 'selected' : '';?>><?=$employee['Name'];?> <?=$employee['Role'];?></option>
                        <?php   endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <span class="pull-right">
                <button class="btn btn-orange jsUpdateSettings"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update</button>
            </span>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<script>
    $('#jsCourseRolesInp').select2({ closeOnSelect: false, minimumResultsForSearch: -1 });
    $('#jsCourseDepartmentsInp').select2({ closeOnSelect: false });
    $('#jsCourseTeamsInp').select2({ closeOnSelect: false });
    $('#jsCourseEmployeesInp').select2({ closeOnSelect: false });
</script>
