<!-- View Page -->
<div class="js-page p10" id="js-page-view" <?=$page != 'view' ? 'style="display: none;"' : '';?>>
    <div class="csPageHeader">
        <div class="row">
            <div class="col-sm-3">
                <h4>Types</h4>
            </div>
            <div class="col-sm-9">
                <span class="pull-right">
                    <a id="js-add-type-btn" href="javascript:void(0)" class="btn btn-orange">
                        <span><i class="fa fa-plus-circle"></i></span>&nbsp; ADD A TYPE
                    </a>
                </span>
            </div>
        </div>
    </div>
    <!-- Nav -->
    <div class="csPageNav">
        <div class="row">
            <div class="col-sm-12">
                <span class="pull-right">
                    <button class="btn btn-success btn-theme jsFilterBtn" data-target="jsFilterBoxHolidays">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </span>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="csPageBody">
        <!-- Filter -->
        <div class="csBalanceBox csShadow csRadius5 jsFilterBoxHolidays dn">
            <div class="col-sm-3">
                <label>Types</label>
                <select class="invoice-fields" name="template" id="js-filter-types"></select>
            </div>
            <div class="col-sm-3">
                <label>Status</label>
                <select class="invoice-fields csRadius100" id="js-filter-status">
                    <option value="-1">All</option>
                    <option value="1">Active</option>
                    <option value="0">In-Active</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label>From Date</label>
                <input type="text" readonly="" class="invoice-fields csRadius100" id="js-filter-from-date" />
            </div>
            <div class="col-sm-3">
                <label>To Date</label>
                <input type="text" readonly="" class="invoice-fields csRadius100" id="js-filter-to-date" />
            </div>
            <div class="col-sm-12">
                <span class="pull-right">
                    <br />
                    <button id="btn_apply" type="button" class="btn btn-orange js-apply-filter-btn">APPLY</button>
                    <button id="btn_reset" type="button"
                        class="btn btn-black btn-theme js-reset-filter-btn">RESET</button>
                </span>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="csTabMenu">
            <div class="row">
                <div class="col-sm-12">
                    <ul>
                        <li class="active-tab active">
                            <a href="javascript:void(0)" class="js-tab" data-type="active">Active</a>
                        </li>
                        <li class="active-tab">
                            <a href="javascript:void(0)" class="js-tab" data-type="archived">Deactivated</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Listing area -->
        <div class="csTabContent">
            <br />
            <div class="js-ip-pagination"></div>
            <hr />
            <div class="csLisitingArea"></div>
            <hr />
            <div class="js-ip-pagination"></div>

            <div class="clearfix"></div>
        </div>

    </div>
</div>