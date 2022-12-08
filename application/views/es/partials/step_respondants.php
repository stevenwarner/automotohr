<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5 jsQuestionListing">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3 class="_csF16">Survey Questions <span id="jsSurveyQuestionCount">(0)</span></h3>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                 <a href="<?php echo base_url("employee/surveys/create/".$survey_id."/questions"); ?>" class="btn _csB3 _csF2 _csR5 _csMt10 _csF16">
                    <i class="fa fa-long-arrow-left _csF16" aria-hidden="true"></i>&nbsp;Go back to Question
                </a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!--  -->
        <div class="row">
            <!-- Filter -->
            <div class="col-md-3 col-sm-12">
                <form>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Included</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Employees</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row _csMt10">
                        <div class="col-sm-12">
                            <label>Departments / Teams</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row _csMt10">
                        <div class="col-sm-12">
                            <label>Roles</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row _csMt10">
                        <div class="col-sm-12">
                            <label>Job Titles</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row _csMt10">
                        <div class="col-sm-12">
                            <label>Employment Types</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row _csMt10">
                        <div class="col-sm-12">
                            <label>Excluded</label>
                        </div>
                    </div>
                    <div class="row _csMt10">
                        <div class="col-sm-12">
                            <label>Employees</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row _csMt10">
                        <div class="col-sm-12">
                            <label>Hire Date</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="row _csMt10">
                        <div class="col-sm-12">
                            <button class="btn _csF2 _csB4 form-control _csR5">Apply Filter</button>
                            <button class="btn _csF2 _csB1 form-control _csR5 _csMt10">Clear Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Employee Listing -->
            <div class="col-md-9 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr class="_csB4 _csF2">
                                <th scope="col">Employee</th>
                                <th scope="col">Department/Team</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="col">Mubashir Ahmed</th>
                                <td>Development</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr />
        <!--  -->
        <div class="row">
            <div class="col-sm-12 text-right">
                <button class="btn _csF2 _csB1 _csR5">Cancel</button>
                <button class="btn _csF2 _csB4 _csR5">Save</button>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                 <a href="<?php echo base_url("employee/surveys/create/".$survey_id."/questions"); ?>" class="btn _csB3 _csF2 _csR5 _csMt10 _csF16" >
                    <i class="fa fa-long-arrow-left _csF16" aria-hidden="true"></i>&nbsp;Go back to Question
                </a>
            </div>
        </div>
    </div>
</div>