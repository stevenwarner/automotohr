
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_pto_left_view'); ?>
                    <div id="js-employee-off-box-desktop"></div>
                </div>

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- Top Bar -->
                    <div class="right-content">
                        <div class="row mg-lr-0">
                            <div class="border-top-section border-bottom">
                               <div class="col-xs-6 col-lg-6">
                                    <div class="pto-top-heading-left pl0">
                                        <p>Settings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- General Settings -->
                        <div class="margin-custom">
                            <div class="">
                                <div class="col-sm-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">General Settings</h4>
                                    <div class="pto-allowed-years">
                                        <div class="timeline-content-settings">
                                            <label class="font-wieght-light">Default Time Slot (Hours)</label>
                                            <div class="row">
                                                <div class="col-sm-5 col-xs-12">
                                                    <input type="text" class="form-control" aria-label="Amount (rounded to the nearest dollar)" id="js-default-time-slot-hours">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="col-sm-5  margin-top">
                                    <div class="form-group">
                                        <label class="font-wieght-light">Time Slot Format</label>
                                        <div class="hr-select-dropdown">
                                            <select class="invoice-fields" name="template" id="js-formats"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="col-lg-12 checkbox-styling-setting info-styling-custom margin-top">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="checkbox-sizing" id="js-approval-check"> <span>All Approvals Needed</span>
                                        <div class="control__indicator"></div>
                                    </label>

                                    <a  href="javascript:void(0)"
                                        title="Note"
                                        data-trigger="hover"
                                        data-toggle="popovers"
                                        data-placement="right"
                                        data-content="If checked then all assigned leads of employee will need to Approve/Reject Time off in order to move to the next level. This does not apply to Approver."
                                    >
                                        <div class="info-custom">&nbsp;<i class="fa fa-question-circle"></i></div>
                                    </a>
                                </div>
                            <div class="">
                                <div class="col-lg-12 checkbox-styling-setting info-styling-custom margin-top">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="checkbox-sizing" name="send_email" id="js-send-email-check"> <span>Do not send notification email</span>
                                        <div class="control__indicator"></div>
                                    </label>
                            </div>
                               <!--  <div class="col-lg-12 checkbox-styling-setting info-styling-custom">
                                    <label>
                                        <input type="checkbox" class="checkbox-sizing" id="js-email-check"> <span>Email Receiver</span>
                                    </label>
                                    <a href="javascript:void(0)" data-toggle="tooltip" title="If this check is enabled then all the current level supervisors gets notified on every change." class="custom-tooltip">
                                        <div class="info-custom">&nbsp;<i class="fa fa-question-circle"></i></div>
                                    </a>
                                </div> -->
                            </div>

                            <div class="clearfix"></div>
                            <div class="bottom-section-pto">
                                <div class="btn-button-section">
                                    <button id="js-save-btn" type="button" class="btn btn-save">SAVE</button>
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
<?php $this->load->view('timeoff/scripts/settings'); ?>
