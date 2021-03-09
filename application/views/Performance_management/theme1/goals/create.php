<div class="container">
    <div class="csPageSection jsPageSection" data-key="main">
        <!-- Body -->
        <div class="csPageBoxBody p10">
            <div class="csForm">
                <h6 class="mb10">GENERAL DETAILS</h6>
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Name <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" class="form-control csRadius100" placeholder="Improve Sales" id="jsCGTitle"/>
                    </div>
                </div>
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label>Description</label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <textarea class="form-control" id="jsCGDescription"></textarea>
                    </div>
                </div>
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Start Date - Due Date</label>
                    </div>
                    <div class="col-sm-3 col-xs-12 pr0">
                        <input type="text" readonly class="form-control csRadius100" id="jsCGStartDate" placeholder="MM/DD/YYYY"  />
                    </div>
                    <div class="col-sm-1 col-xs-12 pl0 pl0 pr0">
                        <p class="text-center" style="font-size: 25px; font-weight: 900;">-</p>
                    </div>
                    <div class="col-sm-3 col-xs-12 pl0">
                        <input type="text" readonly class="form-control csRadius100" id="jsCGEndDate" placeholder="MM/DD/YYYY"  />
                    </div>
                </div>
                <!--  -->
                <div class="row mb10 jsCGTypeBox">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Type <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <select id="jsCGType">
                            <option value="0">[Select a type]</option>
                            <option value="4">Company</option>
                            <option value="3">Department</option>
                            <option value="1">Individual</option>
                            <option value="2">Team</option>
                        </select>
                    </div>
                </div>
                <!--  -->
                <div class="row mb10 jsCGBoxIndividual dn">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Individual <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <select id="jsCGEmployee"></select>
                    </div>
                </div>

                <!--  -->
                <div class="row mb10 jsCGBoxTeam dn">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Team <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <select id="jsCGTeam"></select>
                    </div>
                </div>
                
                <!--  -->
                <div class="row mb10 jsCGBoxDepartment dn">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Department <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <select id="jsCGDepartment"></select>
                    </div>
                </div>

                <!--  -->
                <div class="bbb mt10 mb10"></div>

                <h5 class="mb10">MEASUREMENT AND ALIGNMENT</h5>

                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Measure Using <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <select id="jsCGMeasure">
                            <option value="1">Percentage (%)</option>
                            <option value="2">Volume (Numeric)</option>
                            <option value="3">Dollars ($)</option>
                        </select>
                    </div>
                </div>
                
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Target <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" class="form-control csRadius100" id="jsCGTarget"/>
                    </div>
                </div>
                
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Goal Alignment </label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <a href="javascript:void(0)" class="btn btn-link pl0 jsPageSectionBTN" data-target="align" id="jsCGAlign">
                            <i class="fa fa-plus-circle"></i> Select a Goal
                        </a>
                    </div>
                </div>

                <!--  -->
                <div class="bbb mt10 mb10"></div>

                <h5 class="mb10">VISIBILITY</h5>

                <div class="row mb10 jsCGBoxVisibilty">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Who has access</label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 ma10">
                                <label>Roles</label>
                                <select multiple id="jsCGVisibilityRoles"></select> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 ma10">
                                <label>Teams</label>
                                <select multiple id="jsCGVisibilityTeams"></select> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 ma10">
                                <label>Departments</label>
                                <select multiple id="jsCGVisibilityDepartments"></select> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 ma10">
                                <label>Employees</label>
                                <select multiple id="jsCGVisibilityEmployees"></select> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="csPageBoxFooter p10">
            <span class="csBTNBox">
                <button class="btn btn-black btn-lg jsModalCancel">Cancel</button>
                <button class="btn btn-orange btn-lg jsCGSave">Save</button>
            </span>
            <div class="clearfix"></div>
        </div>
    </div>

    <!--  -->
    <div class="csPageSection jsPageSection dn" data-key="align">
        <div class="jsAlignBox">
            <!--  -->
            <div class="csPageBoxBody p10"></div>
        </div>
        <!--  -->
        <div class="csPageBoxFooter p10">
            <span class="csBTNBox">
                <button class="btn btn-black btn-lg jsCGBack jsPageSectionBTN" data-target="main">Back</button>
            </span>
            <div class="clearfix"></div>
        </div>
    </div>
</div>