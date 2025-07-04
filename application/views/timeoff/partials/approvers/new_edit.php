<!-- Add Page--->
<div class="js-page" id="js-page-edit" <?=$page != 'edit' ? 'style="display: none;"' : '';?>>

    <div class="csPageHeader <?php echo $this->agent->is_mobile() ? 'csMobileWrap' : '';?>">
        <div class="row">
            <div class="col-sm-12">
                <h4>
                    Add an approver
                    <span class="pull-right">
                    <button class="btn btn-orange jsEmployeeQuickProfile"><i class="fa fa-users"></i> Employee Profile</button>
                        <button class="btn btn-orange manage_my_team"><i class="fa fa-users"></i> Manage Teams</button>
                        <a href="javascript:void(0)" class="btn btn-orange js-view-type-btn">
                            <span><i class="fa fa-arrow-circle-left"></i></span>&nbsp; VIEW APPROVERS
                        </a>
                    </span>
                </h4>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="csPageBody">
        <!--  -->
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Select employee(s) <span class="cs-required">*</span></label>
                    <select id="js-employee-edit"></select>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <hr />
                    <label>Select type<span class="cs-required">*</span></label> <br />
                    <label class="control control--radio">
                        Department &nbsp;&nbsp;
                        <input type="radio" name="js-is-department-edit" class="js-is-department-edit" value="1" />
                        <div class="control__indicator"></div>
                    </label>
                    <label class="control control--radio">
                        Team
                        <input type="radio" name="js-is-department-edit" class="js-is-department-edit" value="0" />
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group js-department-row-edit dn">
            <div class="row">
                <div class="col-sm-6">
                    <hr />
                    <label>Departments <span class="cs-required">*</span></label>
                    <select id="js-departments-edit" multiple="true"></select>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group js-team-row-edit dn">
            <div class="row">
                <div class="col-sm-6">
                    <hr />
                    <label>Teams <span class="cs-required">*</span></label>
                    <select id="js-teams-edit" multiple="true"></select>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <hr />
                    <label class="control control--checkbox">
                        <input type="checkbox" id="js-approve-100-percent-edit" />
                        Can approve - 100%
                        <span class="control__indicator"></span>
                    </label>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <hr />
                    <label class="control control--checkbox">
                        <input type="checkbox" id="js-archive-check-edit" />
                        Deactivate
                        <span class="control__indicator"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="csPageFooter">
        <!--  -->
        <div class="form-group p10">
            <div class="row">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-black btn-theme js-view-type-btn">Cancel</button>
                    <button id="js-save-edit-btn" type="button" class="btn btn-orange">Update</button>
                </div>
            </div>
        </div>
    </div>

</div>