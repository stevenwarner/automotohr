<!-- View Page -->
<div class="js-page p10" id="js-page-view">
    <div class="row mg-lr-0">
        <div class="border-top-section border-bottom csHeader">
            <div class="col-xs-12">
                <div class="">
                    <p>Balances <small>(As Of Today)</small></p>
                </div>
            </div>
            <hr />
            <div class="col-xs-12">
                <div class="">
                    <a class="btn btn-success jsMobileBTN" target="_blank" href="<?=base_url('timeoff/print/balance/0');?>"><i class="fa fa-print"></i> Print</a>
                    <a class="btn btn-success jsMobileBTN" target="_blank" href="<?=base_url('timeoff/download/balance/0');?>"><i class="fa fa-download"></i> Download</a>
                    
                    <?php if($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) { ?>
                    <button class="btn btn-success jsMobileBTN jsImportBalance">Import Consumed Balance</button>
                    <button class="btn btn-success jsMobileBTN manage_my_team">Manage Teams</button>
                    <!-- <input type="checkbox" class="jsEditResetCheckbox" checked data-toggle="toggle"
                        data-on="As Of Today" data-off="Calendar Year" data-onstyle="success" data-offstyle="danger" /> -->
                    <?php } ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>


    <div class="row mg-lr-0">
        <div class="pto-tabs cs-bl-tabs">
            <ul class="nav nav-tabs">
                <button id="btn_apply_filter" type="button" class="btn btn-apply-filter"><i class="fa fa-sliders"></i>FILTER</button>
            </ul>
            <div class="filter-content">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="">Employee(s)</label>
                            <div class="">
                                <select class="invoice-fields" id="js-filter-employee"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="">Policies</label>
                            <div class="">
                                <select class="invoice-fields" id="js-filter-policies" multiple="true"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="pull-right">
                            <br />
                            <button id="btn_reset" type="button"
                                class="btn btn-black js-reset-filter-btn">RESET</button>
                            <button id="btn_apply" type="button"
                                class="btn btn-success js-apply-filter-btn">APPLY</button>
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
                                <th class="col-sm-3">Employee</th>
                                <th class="col-sm-2">Work Anniversary</th>
                                <th class="col-sm-2">Allowed Time</th>
                                <th class="col-sm-2">Consumed Time</th>
                                <th class="col-sm-2">Remaining Time</th>
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

<?php $this->load->view('timeoff/partials/popups/team_management'); ?>