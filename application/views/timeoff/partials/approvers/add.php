<!-- Add Page--->
<div class="js-page p10" id="js-page-add" <?=$page != 'add' ? 'style="display: none;"' : '';?>>
    <div class="row mg-lr-0">
        <div class="border-top-section border-bottom csHeader">
            <div class="col-xs-6 col-lg-6">
                <div class="pto-top-heading-left pl0">
                    <p>Add Approver</p>
                </div>
            </div>
            <div class="col-xs-6 col-lg-6">
                <div class="pto-top-heading-right text-right pr0">
                <button class="btn btn-orange jsEmployeeQuickProfile"><i class="fa fa-users"></i> Employee Profile</button>
                    <button class="btn btn-success manage_my_team">Manage Teams</button>
                    <a href="javascript:void(0)" class="dashboard-link-btn2 cs-btn-add js-view-type-btn">
                        <span><i class="fa fa-arrow-circle-left"></i></span>&nbsp; VIEW APPROVERS
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="p10">
        
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <label>Employee <span class="cs-required">*</span></label>
                <div class="">
                    <select class="invoice-fields" id="js-employee-add"></select>
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <label>Select type<span class="cs-required">*</span></label>
                <div style="margin-top: 5px;">
                    <label class="control control--radio">
                        Department &nbsp;&nbsp;
                        <input type="radio" name="js-is-department-add" class="js-is-department-add" value="1" />
                        <div class="control__indicator"></div>
                    </label>
                    <label class="control control--radio">
                        Team
                        <input type="radio" name="js-is-department-add" class="js-is-department-add" value="0" />
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
        </div>
        <br />

        <div class="row js-department-row-add" style="display: none;">
            <div class="col-sm-6 col-xs-12">
                <label>Departments <span class="cs-required">*</span></label>
                <div class="">
                    <select class="invoice-fields" id="js-departments-add" multiple="true"></select>
                </div>
            </div>
        </div>
        
        <div class="row js-team-row-add" style="display: none;">
            <div class="col-sm-6 col-xs-12">
                <label>Teams <span class="cs-required">*</span></label>
                <div class="">
                    <select class="invoice-fields" id="js-teams-add" multiple="true"></select>
                </div>
            </div>
        </div>

        <br />

        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="">
                    <label class="control control--checkbox">
                        <input type="checkbox" id="js-approve-100-percent-add" />
                        Can approve - 100%
                        <span class="control__indicator"></span>
                    </label>
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="">
                    <label class="control control--checkbox">
                        <input type="checkbox" id="js-archive-check-add" />
                        Deactivate
                        <span class="control__indicator"></span>
                    </label>
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-sm-12">
                <div class="">
                    <hr />
                    <button type="button" class="btn btn-black js-view-type-btn">Cancel</button>
                    <button id="js-save-add-btn" type="button" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>