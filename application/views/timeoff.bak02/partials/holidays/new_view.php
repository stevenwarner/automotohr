<!-- View Page -->
<div class="js-page p10" id="js-page-view" <?=$page != 'view' ? 'style="display: none;"' : '';?>>
    <!-- Header -->
    <div class="csPageHeader">
        <div class="row">
            <div class="col-sm-3">
                <h4>Holidays</h4>
            </div>
            <div class="col-sm-9">
                <span class="pull-right">
                    <a id="js-add-holiday-btn" href="javascript:void(0)" class="btn btn-orange">
                        <span><i class="fa fa-plus-circle"></i></span>&nbsp; ADD A HOLIDAY
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
            <div class="col-sm-10">
                <label>Select year</label>
                <select name="template" id="js-filter-year"></select>
            </div>
            <div class="col-sm-2">
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
