<div class="panel panel-default" id="jsPlanEnrollmentSection">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <h1 class="csF16 m0" style="padding-top: 10px;">
                    <i class="fa fa-clipboard" aria-hidden="true"></i>&nbsp;
                    <strong class="jsPlanStepHeading">
                        Add a Enrollment Details
                    </strong>
                </h1>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <p class="text-danger"><em><strong>What information do you need to gather from employees and/or their dependents?</strong></em></p>
        <form action="">
            <!--  -->
            <hr>
            <div class="form-group">
                <label class="csF16">Does your carrier require any of these employee details?</label>
                <p><em><strong>Select any that apply:</strong></em></p>
                <div class="checkbox cs_full_width">
                    <label class="control control--checkbox">
                        &nbsp;&nbsp;Have you used tobacco products (such as cigarettes, cigars, snuff, chew or pipe) or any nicotine delivery system in the past 12 months?
                        <input type="checkbox" class="jsEmployeeDetails" value="1">
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="checkbox cs_full_width">
                    <label class="control control--checkbox">
                        &nbsp;&nbsp;Actively at work?
                        <input type="checkbox" class="jsEmployeeDetails" value="2">
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="checkbox cs_full_width">
                    <label class="control control--checkbox">
                        &nbsp;&nbsp;Do you usually work at least 30 hours a week for this employer?
                        <input type="checkbox" class="jsEmployeeDetails" value="3">
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="checkbox cs_full_width">
                    <label class="control control--checkbox">
                        &nbsp;&nbsp;Primary Language
                        <input type="checkbox" class="jsEmployeeDetails" value="4">
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label class="csF16">Does your carrier require any of these dependent details?</label>
                <p><em><strong>Select any that apply:</strong></em></p>
                <div class="checkbox cs_full_width">
                    <label class="control control--checkbox">
                        &nbsp;&nbsp;Have you used tobacco products (such as cigarettes, cigars, snuff, chew or pipe) or any nicotine delivery system in the past 12 months?
                        <input type="checkbox" class="jsDependentDetails" value="1">
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="checkbox cs_full_width">
                    <label class="control control--checkbox">
                        &nbsp;&nbsp;Are you permanently disabled?
                        <input type="checkbox" class="jsDependentDetails" value="2">
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="checkbox cs_full_width">
                    <label class="control control--checkbox">
                        &nbsp;&nbsp;Full-Time Student?
                        <input type="checkbox" class="jsDependentDetails" value="3">
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label class="csF16">Do you need to provide plan specific legal text during enrollment?</label>
                <div class="radio cs_full_width">
                    <label class="control control--radio">
                        &nbsp;&nbsp;No
                        <input type="radio" name="dependent_details" class="jsDependentDetails" value="no">
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class="radio cs_full_width">
                    <label class="control control--radio">
                        &nbsp;&nbsp;Yes
                        <input type="radio" name="dependent_details" class="jsDependentDetails" value="yes">
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>