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

        <input type="hidden" class="form-control" readonly id="jsemployeeId" value="<?php echo $session['employer_detail']['sid']?>" placeholder="MM/DD/YYYY"/>

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
        <div class="col-sm-12">
            <span class="pull-right">
                <button class="btn btn-black csF16 csB7 jsCGCloseModal">Cancel</button>
                <button class="btn btn-orange csF16 csB7 jsCGSaveEmployeeGoal">Save</button>
            </span>
        </div>
    </div>
</div>