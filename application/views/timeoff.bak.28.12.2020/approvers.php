<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_pto_left_view'); ?>
                    <div id="js-employee-off-box-desktop"></div>
                </div>

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- View Page -->
                    <div class="right-content js-page" id="js-page-view" <?=$page != 'view' ? 'style="display: none;"' : '';?>>
                        <div class="row mg-lr-0">
                            <div class="border-top-section border-bottom">
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-left pl0">
                                        <p>Time Off Approvers</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right text-right pr0 mr0">
                                        <a id="js-add-btn" href="javascript:void(0)" class="dashboard-link-btn2 cs-btn-add">
                                            <span><i class="fa fa-plus"></i></span>&nbsp; ADD APPROVER
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mg-lr-0">
                            <div class="pto-tabs">
                                <ul class="nav nav-tabs">
                                    <li class="active btn-active js-tab" data-type="active"><a data-toggle="tab">Active</a></li>
                                    <li class="tab-btn js-tab" data-type="archived"><a data-toggle="tab">Deactivated</a></li>
                                    <button id="btn_apply_filter" type="button" class="btn btn-apply-filter">FILTER</button>
                                </ul>
                                <div class="filter-content">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="">Employees</label>
                                                        <div class="">
                                                            <select class="invoice-fields" id="js-filter-employee"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="">Departments</label>
                                                        <div class="">
                                                            <select class="invoice-fields" id="js-filter-departments" multiple="true"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="">Teams</label>
                                                        <div class="">
                                                            <select class="invoice-fields" id="js-filter-teams" multiple="true"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 hidden">
                                                    <label class="">Status</label>
                                                    <div class="">
                                                        <select class="invoice-fields" id="js-filter-status">
                                                            <option value="-1">All</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">In-Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label class="">Date From</label>
                                                        <div class="pto-policy-date">
                                                            <input type="text" readonly="" class="invoice-fields" id="js-filter-from-date" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="">Date to</label>
                                                    <div class="pto-policy-date">
                                                        <input type="text" readonly="" class="invoice-fields" id="js-filter-to-date" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="btn-filter-reset pull-right">
                                                        <br />
                                                        <button id="btn_reset" type="button" class="btn btn-reset js-reset-filter-btn">RESET</button>
                                                        <button id="btn_apply" type="button" class="btn btn-apply js-apply-filter-btn">APPLY</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Active Tab -->
                        <div class="active-content">
                            <!-- Pagination Top -->
                            <div class="js-ip-pagination"></div>
                            <div class="row">
                                <div class="col-lg-12 table-responsive">
                                    <table class="table table-bordereds table-striped table-condensed pto-policy-table">
                                        <thead class="heading-grey js-table-head">
                                            <tr>
                                                <th scope="col">Approver</th>
                                                <th scope="col">Department / Team</th>
                                                <th scope="col">Created On</th>
                                                <!-- <th scope="col">Status</th> -->
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="js-data-area">
                                            <tr class="js-error-row"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Pagination Bottom -->
                            <div class="js-ip-pagination"></div>
                        </div>
                    </div>

                    <!-- Add Page--->
                    <div class="right-content-2 js-page" id="js-page-add" <?=$page != 'add' ? 'style="display: none;"' : '';?>>
                        <div class="js-top-bar">
                            <div class="row mg-lr-0">
                                <div class="border-top-section border-bottom">
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-left">
                                            <p>Add Approver</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-right-add-plan text-right">
                                            <div class="btn-filter-reset">
                                                <button type="button" class="btn btn-reset js-view-page-btn mr10"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW APPROVERS</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-content-area">
                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Approver <span class="cs-required">*</span></label>
                                    <div class="">
                                        <select class="invoice-fields" id="js-employee-add"></select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Select type<span class="cs-required">*</span></label>
                                    <div style="margin-top: 5px;">
                                        <label class="control control--radio">
                                            Department &nbsp;&nbsp;
                                            <input type="radio" name="js-is-department-add" value="1" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            Team
                                            <input type="radio" name="js-is-department-add" value="0" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top js-department-row-add" style="display: none;">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Departments <span class="cs-required">*</span></label>
                                    <div class="">
                                        <select class="invoice-fields" id="js-departments-add" multiple="true"></select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top js-team-row-add" style="display: none;">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Teams <span class="cs-required">*</span></label>
                                    <div class="">
                                        <select class="invoice-fields" id="js-teams-add" multiple="true"></select>
                                    </div>
                                </div>
                            </div>
                            <!-- 
                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Status</label>
                                    <div class="">
                                        <select class="invoice-fields" name="template" id="js-status-add">
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="checkbox-styling">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-archive-check-add" />
                                            Deactivate
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="bottom-section-pto">
                                        <div class="btn-button-section">
                                            <button id="js-save-add-btn" type="button" class="btn btn-save ml0">APPLY</button>
                                            <button type="button" class="btn btn-cancel js-view-page-btn">CANCEL</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Page--->
                    <div class="right-content-2 js-page" id="js-page-edit" <?=$page != 'edit' && $approverSid != null ? 'style="display: none;"' : '';?>>
                        <div class="js-top-bar">
                            <div class="row mg-lr-0">
                                <div class="border-top-section border-bottom">
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-left">
                                            <p>Edit Approver</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-lg-6">
                                        <div class="pto-top-heading-right-add-plan text-right">
                                            <div class="btn-filter-reset">
                                                <button type="button" class="btn btn-reset js-view-page-btn mr10"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW APPROVERS</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="js-content-area">
                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Approver <span class="cs-required">*</span></label>
                                    <div class="">
                                        <select class="invoice-fields" id="js-employee-edit"></select>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Select type<span class="cs-required">*</span></label>
                                    <div style="margin-top: 5px;">
                                        <label class="control control--radio">
                                            Department &nbsp;&nbsp;
                                            <input type="radio" name="js-is-department-edit" value="1" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            Team
                                            <input type="radio" name="js-is-department-edit" value="0" />
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top js-department-row-edit" style="display: none;">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Departments <span class="cs-required">*</span></label>
                                    <div class="">
                                        <select class="invoice-fields" id="js-departments-edit" multiple="true"></select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row margin-top js-team-row-edit" style="display: none;">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Teams <span class="cs-required">*</span></label>
                                    <div class="">
                                        <select class="invoice-fields" id="js-teams-edit" multiple="true"></select>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row margin-top">
                                <div class="col-sm-6 col-xs-12">
                                    <label>Status</label>
                                    <div class="">
                                        <select class="invoice-fields" name="template" id="js-status-edit">
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <div class="checkbox-styling">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" id="js-archive-check-edit" />
                                            Deactivate
                                            <span class="control__indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="bottom-section-pto">
                                        <div class="btn-button-section">
                                            <input type="hidden" id="js-id-edit" />
                                            <button id="js-save-edit-btn" type="button" class="btn btn-save ml0">APPLY</button>
                                            <button type="button" class="btn btn-cancel js-view-page-btn">CANCEL</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employee on off for mobile -->
                    <div id="js-employee-off-box-mobile"></div>
                </div>
            </div>
        </div>
    </div>

<?php $this->load->view('timeoff/loader'); ?>
<?php $this->load->view('timeoff/scripts/approvers'); ?>
