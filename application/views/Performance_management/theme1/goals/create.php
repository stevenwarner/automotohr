<div class="">
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
                        <span class="text-center">-</span>
                    </div>
                    <div class="col-sm-3 col-xs-12 pl0">
                        <input type="text" readonly class="form-control csRadius100" id="jsCGEndDate" placeholder="MM/DD/YYYY"  />
                    </div>
                </div>
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Type <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <select id="jsCGType">
                            <option value="0">[Select a type]</option>
                            <option value="1">Individual</option>
                            <option value="2">Team</option>
                            <option value="3">Department</option>
                            <option value="4">Company</option>
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
                <div class="bbb mt10 mb10"></div>

                <h5 class="mb10">MEASUREMENT AND ALIGNMENT</h5>

                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Measure Using <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <select id="jsCGMeasure">
                            <option value="0">None</option>
                            <option value="1">Percentage (%)</option>
                            <option value="2">Volume (Numeric)</option>
                            <option value="3">Dollers ($)</option>
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