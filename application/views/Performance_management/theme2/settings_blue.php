<?php
    $roles = json_decode($settings['roles'], true);
    $departments = json_decode($settings['departments'], true);
    $teams = json_decode($settings['teams'], true);
    $employees = json_decode($settings['employees'], true);
?>
<div class="col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
    <!--  -->
    <div class="csIPLoader jsIPLoader" data-page="settings">
        <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>
    </div>

    <!-- Visibility -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="background-color: #81b431;">
            <p class="csF16 csB7 csW mb0">Visibility <small>(Who can manage reviews)</small></p>
        </div>
        <div class="panel-body jsPageBody" data-page="visibility">
            <!-- Roles -->
            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <label class="csF16 csB7">Role(s) <i class="fa fa-question-circle-o jsHintBtn" data-target="title"
                            aria-hidden="true"></i></label>
                    <p class="csF14 jsHintBody" data-hint="title">The selected Role(s) can manage this review.</p>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <select id="jsReviewRolesInp" multiple>
                        <?php   foreach(getRoles() as $index => $role): ?>
                        <option value="<?=$index;?>" <?=!empty($roles) && in_array($index, $roles) ? 'selected' : '';?>><?=$role;?></option>
                        <?php   endforeach; ?>
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
                        review.</p>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <select id="jsReviewDepartmentsInp" multiple>
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
                        review.</p>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <select id="jsReviewTeamsInp" multiple>
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
                    <p class="csF14 jsHintBody" data-hint="title">The selected Employee(s) can manage this review.</p>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <select id="jsReviewEmployeesInp" multiple>
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
                <button class="btn btn-success jsUpdateSettings"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update</button>
            </span>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
