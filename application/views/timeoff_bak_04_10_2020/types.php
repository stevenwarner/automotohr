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
                                        <p>Time Off Types</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right text-right">
                                        <a id="js-add-type-btn" href="javascript:void(0)" class="dashboard-link-btn2">
                                            <span><i class="fa fa-plus"></i></span>&nbsp; ADD TYPE
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
                                        <div class="col-lg-7">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="">Types</label>
                                                        <div class="">
                                                            <select class="invoice-fields" name="template" id="js-filter-types"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="">Employees</label>
                                                        <div class="">
                                                            <select class="invoice-fields" name="template" id="js-filter-employee"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label class="">Date From</label>
                                                        <div class="pto-type-date ">
                                                            <input type="text" readonly="" class="invoice-fields" id="js-filter-from-date" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="">Date to</label>
                                                    <div class="pto-type-date ">
                                                        <input type="text" readonly="" class="invoice-fields" id="js-filter-to-date" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="">Status</label>
                                                    <div class="">
                                                        <select class="invoice-fields" name="template" id="js-filter-status">
                                                            <option value="-1">All</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">In-Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="btn-filter-reset">
                                                <button id="btn_reset" type="button" class="btn btn-reset js-reset-filter-btn">RESET</button>
                                                <button id="btn_apply" type="button" class="btn btn-apply js-apply-filter-btn">APPLY</button>
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
                                    <table class="table table-bordered pto-type-table">
                                        <thead class="heading-grey">
                                            <tr>
                                                <th scope="col">Type Name</th>
                                                <th scope="col">Policies</th>
                                                <th scope="col">Created By</th>
                                                <th scope="col">Created At</th>
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
                        <div class="row mg-lr-0">
                            <div class="border-top-section border-bottom">
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-left pl0">
                                        <p>Add Type</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right-add-type text-right">
                                        <div class="btn-filter-reset">
                                            <button type="button" class="btn btn-reset js-view-type-btn"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW TYPES</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row123 margin-custom">
                            <div class="">
                                <label>Type <span class="cs-required">*</span></label>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="text" value="" class="form-control" id="js-type-add" placeholder="FMLA" />
                                    </div>
                                </div>
                            </div>
                        </div>

                       <!--  <div class="row123 margin-custom">
                            <div class="">
                                <label class="">Status</label>
                                <div class="">
                                    <select class="invoice-fields" name="template" id="js-status-add">
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div>
                            </div>
                        </div> -->


                        <div class="row123 margin-custom">
                            <div class="">
                                <label>Policies</label>
                                <select id="js-policies-add" multiple="true"></select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12 checkbox-styling margin-top">
                                <label  class="control control--checkbox">
                                    <input type="checkbox" class="checkbox-sizing" id="js-archived-add" /> 
                                    Deactivate
                                    <span class="control__indicator"></span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="bottom-section-pto">
                                    <div class="btn-button-section">
                                        <button id="js-save-add-btn" type="button" class="btn btn-save">APPLY</button>
                                        <button type="button" class="btn btn-cancel js-view-type-btn">CANCEL</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Page--->
                    <div class="right-content-2 js-page" id="js-page-edit" <?=$page != 'edit' && $planSid != null ? 'style="display: none;"' : '';?>>
                       <div class="row mg-lr-0">
                            <div class="border-top-section border-bottom">
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-left pl0">
                                        <p>Edit Type</p>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-right-add-type text-right">
                                        <div class="btn-filter-reset">
                                            <button type="button" class="btn btn-reset js-view-type-btn"><span><i class="fa fa-angle-left"></i></span>&nbsp;VIEW TYPES</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row123 margin-custom">
                            <div class="">
                                <label>Type <span class="cs-required">*</span></label>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="text" value="" class="form-control" id="js-type-edit" placeholder="FMLA" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="row123 margin-custom">
                            <div class="">
                                <label class="">Status</label>
                                <div class="">
                                    <select class="invoice-fields" name="template" id="js-status-edit">
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div>
                            </div>
                        </div> -->


                        <div class="row123 margin-custom">
                            <div class="">
                                <label>Policies</label>
                                <select id="js-policies-edit" multiple="true"></select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12 checkbox-styling margin-top">
                                <label  class="control control--checkbox">
                                    <input type="checkbox" class="checkbox-sizing" id="js-archived-edit" /> 
                                    Deactivate
                                    <span class="control__indicator"></span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="bottom-section-pto">
                                    <div class="btn-button-section">
                                        <input type="hidden" id="js-type-id-edit" />
                                        <button id="js-save-edit-btn" type="button" class="btn btn-save">APPLY</button>
                                        <button type="button" class="btn btn-cancel js-view-type-btn">CANCEL</button>
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
<?php $this->load->view('timeoff/scripts/types'); ?>
