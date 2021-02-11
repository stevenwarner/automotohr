<!-- View Page -->
<div class="js-page p10" id="js-page-add" <?=$page != 'add' ? 'style="display: none;"' : '';?>>
    <div class="row mg-lr-0">
        <div class="border-top-section border-bottom csHeader">
            <div class="col-xs-6 col-lg-6">
                <div class="pto-top-heading-left pl0">
                    <p>Holidays</p>
                </div>
            </div>
            <div class="col-xs-6 col-lg-6">
                <div class="pto-top-heading-right text-right pr0">
                    <a href="javascript:void(0)" class="dashboard-link-btn2 cs-btn-add js-view-holiday-btn">
                        <span><i class="fa fa-arrow-circle-left"></i></span>&nbsp; VIEW HOLIDAYS
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>



    <!-- Active Tab -->
    <div class="active-content">
        <div class="row margin-top">
            <div class="col-sm-6 col-xs-12">
                <label>Year <span class="cs-required">*</span></label>
                <div class="">
                    <select class="invoice-fields" id="js-year-add"></select>
                </div>
            </div>
        </div>

        <div class="row margin-top">
            <div class="col-sm-6 col-xs-12">
                <label>Holiday <span class="cs-required">*</span></label>
                <div class="">
                    <input type="text" class="form-control" id="js-holiday-add" />
                </div>
            </div>
        </div>

        <div class="row margin-top">
            <div class="col-sm-6 col-xs-12">
                <label>Holiday Icon</label>
                <div>
                    <div class="cs-icon-box hidden" id="js-icon-plc-box-add" style="width: 50px;">
                        <img src="" id="js-icon-plc-add" />
                        <span style="position: absolute; left: 80px; top: 40px; color: #cc1100;"
                            id="js-icon-remove-add"><i class="fa fa-close"></i></span>
                    </div>
                    <input type="hidden" id="js-holiday-icon-add" />
                    <button class="btn btn-success" id="js-icon-add"> Choose Icon</button>
                </div>
            </div>
        </div>

        <div class="row margin-top">
            <div class="col-sm-3 col-xs-12">
                <label>Start Date <span class="cs-required">*</span></label>
                <div class="">
                    <input type="text" class="form-control" id="js-from-date-add" readonly="true" />
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <label>End Date <span class="cs-required">*</span></label>
                <div class="">
                    <input type="text" class="form-control" id="js-to-date-add" readonly="true" />
                </div>
            </div>
        </div>

        <div class="row margin-top">
            <div class="col-sm-6 col-xs-12">
                <label>Allow work on this holiday</label>
            </div>
            <div class="col-sm-12">
                <label class="control control--radio">
                    <input type="radio" name="allow_work_on_holiday" class="allow_work_on_holiday" value="0">No&nbsp;
                    <div class="control__indicator"></div>
                </label>
                <label class="control control--radio">
                    <input type="radio" name="allow_work_on_holiday" class="allow_work_on_holiday" value="1"
                        checked="true">Yes
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>

        <div class="row margin-top hidden">
            <div class="col-sm-6 col-xs-12">
                <label>Frequency</label>
                <div class="">
                    <select id="js-frequency-add">
                        <option value="yearly">Yearly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row margin-top hidden">
            <div class="col-sm-6 col-xs-12">
                <label>Sort Order</label>
                <div class="">
                    <input type="text" class="form-control" id="js-sort-order-add" value="1" />
                </div>
            </div>
        </div>

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
                    <br />
                    <button type="button" class="btn btn-black js-view-holiday-btn">CANCEL</button>
                    <button id="js-save-add-btn" type="button" class="btn btn-success ml0">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>