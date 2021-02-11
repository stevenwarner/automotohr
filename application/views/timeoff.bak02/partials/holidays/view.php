<!-- View Page -->
<div class="js-page p10" id="js-page-view" <?=$page != 'view' ? 'style="display: none;"' : '';?>>
    <div class="row mg-lr-0">
        <div class="border-top-section border-bottom csHeader">
            <div class="col-xs-6 col-lg-6">
                <div class="pto-top-heading-left pl0">
                    <p>Holidays</p>
                </div>
            </div>
            <div class="col-xs-6 col-lg-6">
                <div class="pto-top-heading-right text-right pr0">
                    <a id="js-add-holiday-btn" href="javascript:void(0)" class="dashboard-link-btn2 cs-btn-add">
                        <span><i class="fa fa-plus"></i></span>&nbsp; ADD HOLIDAY
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
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label>Year</label>
                            <div>
                                <select class="invoice-fields" name="template" id="js-filter-year">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="pull-right">
                            <br>
                            <button id="btn_apply" type="button"
                                class="btn btn-success js-apply-filter-btn">APPLY</button>
                            <button id="btn_reset" type="button"
                                class="btn btn-black js-reset-filter-btn">RESET</button>
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
                                <th class="col-sm-2">Holiday</th>
                                <th class="col-sm-2">Icon</th>
                                <th class="col-sm-2">Holiday Year</th>
                                <th class="col-sm-2">Holiday Date</th>
                                <th class="col-sm-2">Created At</th>
                                <th class="col-sm-2">Action</th>
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