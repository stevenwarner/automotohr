<!-- View Page -->
<div id="js-page-add" <?=$page != 'add' ? 'style="display: none;"' : '';?> class="js-page">

    <div class="csPageHeader">
        <div class="row">
            <div class="col-sm-3">
                <h4>Add Holiday</h4>
            </div>
            <div class="col-sm-9">
                <span class="pull-right">
                    <a href="javascript:void(0)" class="btn btn-orange js-view-holiday-btn">
                        <span><i class="fa fa-arrow-circle-left"></i></span>&nbsp; VIEW HOLIDAYS
                    </a>
                </span>
            </div>
        </div>
    </div>

    <div class="csPageBody">

        <!--  -->
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Year <span class="cs-required">*</span></label>
                    <select id="js-year-add"></select>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group">
            <div class="row">
                <hr />
                <div class="col-sm-6">
                    <label>Holiday <span class="cs-required">*</span></label>
                    <input type="text" class="form-control csRadius100" id="js-holiday-add" />
                </div>
            </div>
        </div>


        <!--  -->
        <div class="form-group">
            <div class="row">
                <hr />
                <div class="col-sm-6">
                    <label>Holiday Icon</label>
                    <div>
                        <div class="cs-icon-box hidden" id="js-icon-plc-box-add" style="width: 50px;">
                            <img src="" id="js-icon-plc-add" />
                            <span style="position: absolute; left: 80px; top: 40px; color: #cc1100;"
                                id="js-icon-remove-add"><i class="fa fa-close"></i></span>
                        </div>
                        <input type="hidden" id="js-holiday-icon-add" />
                        <button class="btn btn-success btn-theme" id="js-icon-add"> Choose Icon</button>
                    </div>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group">
            <div class="row">
                <hr />
                <div class="col-sm-3">
                    <label>Start Date <span class="cs-required">*</span></label>
                    <input type="text" class="form-control csRadius100" id="js-from-date-add" readonly="true" />
                </div>
                <div class="col-sm-3">
                    <label>End Date <span class="cs-required">*</span></label>
                    <input type="text" class="form-control csRadius100" id="js-to-date-add" readonly="true" />
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group">
            <div class="row">
                <hr />
                <div class="col-sm-6">
                    <label>Allow work on this holiday</label> <br />
                    <label class="control control--radio">
                        <input type="radio" name="allow_work_on_holiday" class="allow_work_on_holiday"
                            value="0">No&nbsp;
                        <div class="control__indicator"></div>
                    </label>
                    <label class="control control--radio">
                        <input type="radio" name="allow_work_on_holiday" class="allow_work_on_holiday" value="1"
                            checked="true">Yes
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group hidden">
            <div class="row">
                <hr />
                <div class="col-sm-6">
                    <label>Frequency</label>
                    <select id="js-frequency-add">
                        <option value="yearly">Yearly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group hidden">
            <div class="row">
                <hr />
                <div class="col-sm-6">
                    <label>Sort Order</label>
                    <input type="text" class="form-control" id="js-sort-order-add" value="1" />
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group">
            <div class="row">
                <hr />
                <div class="col-sm-6">
                    <label class="control control--checkbox">
                        <input type="checkbox" id="js-archive-check-add" />
                        Deactivate
                        <span class="control__indicator"></span>
                    </label>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group">
            <div class="row">
                <hr />
                <div class="col-sm-6">
                    <button type="button" class="btn btn-black btn-theme js-view-holiday-btn">CANCEL</button>
                    <button id="js-save-add-btn" type="button" class="btn btn-orange ml0">SAVE HOLIDAY</button>
                </div>
            </div>
        </div>
    </div>
</div>