<div class="container-fluid">
    <div class="csPageBox csRadius5">
        <!-- Header -->
        <div class="csPageBoxHeader p10">
            <h4>
                <strong>Create Goal</strong>
                <span class="csBTNBox">
                    <button class="btn btn-black btn-lg mtn8">
                        <i class="fa fa-long-arrow-left"></i> All Goals
                    </button>
                </span>
            </h4>
        </div>
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
                        <input type="text" class="form-control csRadius100" placeholder="Improve Sales"/>
                    </div>
                </div>
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label>Description</label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <textarea class="form-control"></textarea>
                    </div>
                </div>
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Start Date - Due Date</label>
                    </div>
                    <div class="col-sm-3 col-xs-12 pr0">
                        <input type="text" readonly class="form-control csRadius100" placeholder="MM/DD/YYYY"  />
                    </div>
                    <div class="col-sm-1 col-xs-12 pl0 pl0 pr0">
                        <span>-</span>
                    </div>
                    <div class="col-sm-3 col-xs-12 pl0">
                        <input type="text" readonly class="form-control csRadius100" placeholder="MM/DD/YYYY"  />
                    </div>
                </div>
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Type <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <select  class="select2">
                            <option value="">Individual</option>
                            <option value="">Team</option>
                            <option value="">Department</option>
                            <option value="">Company</option>
                        </select>
                    </div>
                </div>
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Individual <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <select  class="select2">
                            <option value=""><?=randomData('name');?></option>
                            <option value=""><?=randomData('name');?></option>
                            <option value=""><?=randomData('name');?></option>
                            <option value=""><?=randomData('name');?></option>
                        </select>
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
                        <select  class="select2">
                            <option value="">None</option>
                            <option value="">Percentage (%)</option>
                            <option value="">Volume (Numeric)</option>
                            <option value="">Dollers ($)</option>
                        </select>
                    </div>
                </div>
                
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Target <span class="csRequired"></span></label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" class="form-control csRadius100" />
                    </div>
                </div>
                
                <!--  -->
                <div class="row mb10">
                    <div class="col-sm-3 col-xs-12">
                        <label class="pa10">Goal Alignment </label>
                    </div>
                    <div class="col-sm-9 col-xs-12">
                        <a href="" class="btn btn-link pl0">
                            <i class="fa fa-plus-circle"></i> Select Goal
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="csPageBoxFooter p10">
            <span class="csBTNBox">
                <button class="btn btn-black btn-lg">Cancel</button>
                <button class="btn btn-orange btn-lg">Create Goal</button>
            </span>
            <div class="clearfix"></div>
        </div>
    </div>
</div>