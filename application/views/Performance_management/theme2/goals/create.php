<div class="container">
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Title <span class="csRequired"></span>
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <input type="text" class="form-control" placeholder="Improvement" id="jsCGTitle" />
        </div>
    </div>

    <div class="row">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Description
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <textarea  class="form-control" rows="3" required="required" placeholder="Why this goal is created?" id="jsCGDescription"></textarea>
        </div>
    </div>

    <div class="row">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Period  <span class="csRequired"></span>
            </label>
        </div>
        <div class="col-md-4 col-xs-12 col-xs-12">
            <input type="text" class="form-control" readonly id="jsCGStartDate" placeholder="MM/DD/YYYY"/>
        </div>
        <div class="col-md-4 col-xs-12 col-xs-12">
            <input type="text" class="form-control" readonly id="jsCGEndDate" placeholder="MM/DD/YYYY"/>
        </div>
    </div>

    <div class="row">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Type <span class="csRequired"></span> 
                <p class="csF14 jsHintBody csF14 csB1" data-hint="title">Chosse for whom you are creating this goal.</p>
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <select id="jsCGType">
                <option value="0">Select</option>
                <option value="1">Company</option>
                <option value="2">Department</option>
                <option value="3">Team</option>
                <option value="4">Employee</option>
            </select>
        </div>
    </div>

    <div class="row dn" id="jsCGDR">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Departments <span class="csRequired"></span>
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <select id="jsCGDepartments" multiple>
            <?php
                if(!empty($company_dt['Departments'])){
                    foreach($company_dt['Departments'] as $index => $value){
                    ?>
                    <option value="<?=$value['Id'];?>"><?=$value['Name'];?></option>
                    <?php
                    }
                }
            ?>
            </select>
        </div>
    </div>

    <div class="row dn" id="jsCGTR">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Teams <span class="csRequired"></span>
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <select id="jsCGTeams" multiple>
            <?php
                if(!empty($company_dt['Teams'])){
                    foreach($company_dt['Teams'] as $index => $value){
                    ?>
                    <option value="<?=$value['Id'];?>"><?=$value['Name'];?></option>
                    <?php
                    }
                }
            ?>
            </select>
        </div>
    </div>

    <div class="row dn" id="jsCGER">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Employees <span class="csRequired"></span>
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <select  id="jsCGEmployees" multiple>
            <?php
                if(!empty($company_employees)){
                    foreach($company_employees as $index => $value){
                    ?>
                    <option value="<?=$value['Id'];?>"><?=$value['Name'];?> <?=$value['Role'];?></option>
                    <?php
                    }
                }
            ?>
            </select>
        </div>
    </div>

    <div class="row">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Measure Unit <span class="csRequired"></span>
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <select  id="jsCGGoalType">
                <option value="0">Select</option>
                <option value="1">Percentage</option>
                <option value="2">Volume</option>
                <option value="3">Dollar</option>
                <option value="4">Custom</option>
            </select>
            <!--  -->
            <input type="text" class="form-control dn" id="jsCGCustomGoalType" placeholder="boxes"/>
        </div>
    </div>

    <div class="row">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Target <span class="csRequired"></span>
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <!--  -->
            <input type="text" class="form-control" id="jsCGTarget" placeholder="100"/>
        </div>
    </div>

    <hr />
    <div class="row">
        <br />
        <div class="col-md-12 col-xs-12">
            <label class="csF16 csB7">
                Visibility
                <p>The selected roles, departments (Supervisors), teams (Team Leads), and employees will have access to this document.</p>
            </label>
        </div>
    </div>

    <div class="row">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Roles
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <select id="jsCGVRoles" multiple>
            <?php
                foreach(getRoles() as $index => $value){
                    ?>
                <option value="<?=$index;?>"><?=$value;?></option>
                    <?php
                }
            ?>
            </select>
        </div>
    </div>

    <div class="row">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Departments
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <select id="jsCGVDepartments" multiple>
            <?php
                if(!empty($company_dt['Departments'])){
                    foreach($company_dt['Departments'] as $index => $value){
                    ?>
                    <option value="<?=$value['Id'];?>"><?=$value['Name'];?></option>
                    <?php
                    }
                }
            ?>
            </select>
        </div>
    </div>

    <div class="row">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Teams
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <select id="jsCGVTeams" multiple>
            <?php
                if(!empty($company_dt['Teams'])){
                    foreach($company_dt['Teams'] as $index => $value){
                    ?>
                    <option value="<?=$value['Id'];?>"><?=$value['Name'];?></option>
                    <?php
                    }
                }
            ?>
            </select>
        </div>
    </div>

    <div class="row">
        <br />
        <div class="col-md-4 col-xs-12">
            <label class="csF16 csB7">
                Employees
            </label>
        </div>
        <div class="col-md-8 col-xs-12">
            <select  id="jsCGVEmployees" multiple>
            <?php
                if(!empty($company_employees)){
                    foreach($company_employees as $index => $value){
                    ?>
                    <option value="<?=$value['Id'];?>"><?=$value['Name'];?> <?=$value['Role'];?></option>
                    <?php
                    }
                }
            ?>
            </select>
        </div>
    </div>

    <hr />
    <div class="row">
        <div class="col-sm-12">
            <span class="pull-right">
                <button class="btn btn-black csF16 csB7 jsCGCloseModal">Cancel</button>
                <button class="btn btn-orange csF16 csB7 jsCGSaveGoal">Save</button>
            </span>
        </div>
    </div>
</div>