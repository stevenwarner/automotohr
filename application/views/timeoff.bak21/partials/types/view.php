<!-- View Page -->
<div class="js-page p10" id="js-page-view" <?=$page != 'view' ? 'style="display: none;"' : '';?>>
    <div class="row mg-lr-0">
        <div class="border-top-section border-bottom csHeader">
            <div class="col-xs-6 col-lg-6">
                <div class="pto-top-heading-left pl0">
                    <p>Types</p>
                </div>
            </div>
            <div class="col-xs-6 col-lg-6">
                <div class="pto-top-heading-right text-right pr0">
                    <a id="js-add-type-btn" href="javascript:void(0)" class="dashboard-link-btn2 cs-btn-add">
                        <span><i class="fa fa-plus"></i></span>&nbsp; ADD TYPE
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="row mg-lr-0">
        <div class="pto-tabs">
            <ul class="nav nav-tabs">
                <li class="active btn-active js-tab" data-type="active"><a data-toggle="tab">Active</a></li>
                <li class="tab-btn js-tab" data-type="archived"><a data-toggle="tab">Deactivated</a></li>
                <button id="btn_apply_filter" type="button" class="btn btn-apply-filter"><i
                        class="fa fa-sliders"></i>FILTER</button>
            </ul>
            <div class="filter-content">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Types</label>
                            <div>
                                <select class="invoice-fields" name="template" id="js-filter-types"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label>Status</label>
                        <div>
                            <select class="invoice-fields" name="template" id="js-filter-status">
                                <option value="-1">All</option>
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Date From</label>
                            <div class="pto-policy-date ">
                                <input type="text" readonly="" class="invoice-fields"
                                id="js-filter-from-date" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label>Date to</label>
                        <div class="pto-policy-date ">
                            <input type="text" readonly="" class="invoice-fields" id="js-filter-to-date" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="btn-filter-reset pull-right">
                            <br />
                            <button id="btn_reset" type="button"
                                class="btn btn-reset js-reset-filter-btn">RESET</button>
                            <button id="btn_apply" type="button"
                                class="btn btn-apply js-apply-filter-btn">APPLY</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Tab -->
    <div class="active-content">
        <!-- Pagination Top -->
        <div class="js-ip-pagination mb10"></div>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped table-condensed pto-policy-table csCustomTableHeader">
                        <thead class="js-table-head">
                            <tr>
                                <th class="col-sm-4">Type</th>
                                <th class="col-sm-4">Policies</th>
                                <th class="col-sm-3">Created On</th>
                                <th class="col-sm-1">Action</th>
                            </tr>
                        </thead>
                        <tbody id="js-data-area">
                            <tr class="js-error-row"></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Pagination Bottom -->
        <hr />
        <div class="js-ip-pagination"></div>
    </div>
</div>