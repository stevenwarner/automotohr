<?php
    $r = !empty($visibility['visibility_roles']) ? explode(',', $visibility['visibility_roles']) : [];
    $d = !empty($visibility['visibility_departments']) ? explode(',', $visibility['visibility_departments']) : [];
    $t = !empty($visibility['visibility_teams']) ? explode(',', $visibility['visibility_teams']) : [];
    $e = !empty($visibility['visibility_employees']) ? explode(',', $visibility['visibility_employees']) : [];
?>

<div class="container">
    <!--  -->
    <div class="row">
        <div class="col-sm-12">
            <label class="csF16 csB7">Roles</label>
        </div>
        <div class="col-sm-12">
            <select id="jsVRoles" multiple>
                <?php
                    foreach($company_roles as $index => $role){
                        ?>
                        <option value="<?=$index;?>" <?=in_array($index, $r) ? 'selected' : '';?>><?=$role;?></option>
                        <?php
                    } 
                ?>
            </select>
        </div>
    </div>

    <!--  -->
    <div class="row"><br>
        <div class="col-sm-12">
            <label class="csF16 csB7">Departments</label>
        </div>
        <div class="col-sm-12">
            <select id="jsVDepartments" multiple>
                <?php
                    foreach($company_dt['Departments'] as $index => $v){
                        ?>
                        <option value="<?=$v['Id'];?>" <?=in_array($v['Id'], $d) ? 'selected' : '';?>><?=$v['Name'];?></option>
                        <?php
                    } 
                ?>
            </select>
        </div>
    </div>

     <!--  -->
     <div class="row"><br>
        <div class="col-sm-12">
            <label class="csF16 csB7">Teams</label>
        </div>
        <div class="col-sm-12">
            <select id="jsVTeams" multiple>
                <?php
                    foreach($company_dt['Teams'] as $index => $v){
                        ?>
                        <option value="<?=$v['Id'];?>" <?=in_array($v['Id'], $t) ? 'selected' : '';?>><?=$v['Name'];?></option>
                        <?php
                    } 
                ?>
            </select>
        </div>
    </div>
     
    <!--  -->
    <div class="row">
        <br>
        <div class="col-sm-12">
            <label class="csF16 csB7">Employees</label>
        </div>
        <div class="col-sm-12">
            <select id="jsVEmployees" multiple>
                <?php
                    foreach($company_employees as $index => $v){
                        ?>
                        <option value="<?=$v['Id'];?>" <?=in_array($v['Id'], $e) ? 'selected' : '';?>><?=$v['Name'].' '.$v['Role'];?></option>
                        <?php
                    } 
                ?>
            </select>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-sm-12">
            <button class="btn btn-orange csF16 jsUpdateVisibility">
                Update
            </button>
        </div>
    </div>
</div>