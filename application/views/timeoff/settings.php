<?php $this->load->view('timeoff/includes/header'); ?>


<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
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
                        <div class="">
                            <!-- Loader -->
                            <div class="csIPLoader jsIPLoader" data-page="setting"><i
                                    class="fa fa-circle-o-notch fa-spin"></i></div>
                            <div class="">
                                <div class="col-sm-12">
                                    <h4 class="timeline-title allowed-time-off-title-custom">General Settings</h4>
                                    <div class="pto-allowed-years">
                                        <div class="timeline-content-settings">
                                            <label class="font-wieght-light">Default Time Slot (Hours)</label>
                                            <div class="row">
                                                <div class="col-sm-5 col-xs-12">
                                                    <input type="text" class="form-control"
                                                        aria-label="Amount (rounded to the nearest dollar)"
                                                        id="js-default-time-slot-hours">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="col-sm-5 checkbox-styling-setting info-styling-custom margin-top">
                                    <div class="form-group">
                                        <label class="control control--checkbox">
                                            Update employees time slot
                                            <input type="checkbox" name="js-for-all-employees"
                                                class="js-for-all-employees" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        <a href="javascript:void(0)" title="Note" data-trigger="hover"
                                            data-toggle="popovers" data-placement="right"
                                            data-content="If checked then all employee(s) time slot is changed to the selected one.">
                                            <div class="info-custom">&nbsp;<i class="fa fa-question-circle"></i></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="col-sm-12"></div>
                                <div class="col-sm-5  margin-top">
                                    <div class="form-group">
                                        <label class="font-wieght-light">Time Slot Format</label>
                                        <div>
                                            <select class="invoice-fields" name="template" id="js-formats"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="col-sm-12"></div>
                                <div class="col-sm-5  margin-top">
                                    <div class="form-group">
                                        <label class="font-wieght-light">Week Off Day(s)</label>
                                        <div>
                                            <select multiple name="template" id="js-off-days">
                                                <option value="monday">Monday</option>
                                                <option value="tuesday">Tuesday</option>
                                                <option value="wednesday">Wednesday</option>
                                                <option value="thursday">Thursday</option>
                                                <option value="friday">Friday</option>
                                                <option value="saturday">Saturday</option>
                                                <option value="sunday">Sunday</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--  -->
                            <div class="">
                                <div class="col-sm-12"></div>
                                <div class="col-sm-5  margin-top">
                                    <div class="form-group">
                                        <label class="font-wieght-light">Select Theme</label>
                                        <div>
                                            <select class="invoice-fields" name="themes" id="js-themes">
                                                <option value="1">Theme 1 (List)</option>
                                                <option value="2">Theme 2 (Box)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <!-- <div class="col-lg-12 checkbox-styling-setting info-styling-custom margin-top">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="checkbox-sizing" id="js-approval-check"> <span>All
                                            Approvals Needed</span>
                                        <div class="control__indicator"></div>
                                    </label>

                                    <a href="javascript:void(0)" title="Note" data-trigger="hover"
                                        data-toggle="popovers" data-placement="right"
                                        data-content="If checked then all assigned leads of employee will need to Approve/Reject Time off in order to move to the next level. This does not apply to Approver.">
                                        <div class="info-custom">&nbsp;<i class="fa  fa-question-circle"></i></div>
                                    </a>
                                </div> -->
                                <div class="dn">
                                    <div class="col-lg-12 checkbox-styling-setting info-styling-custom margin-top">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" class="checkbox-sizing" name="send_email"
                                                id="js-send-email-check"> <span>Do not send notifications</span>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>

                                <!--  -->
                                <div>
                                    <div class="col-lg-12 checkbox-styling-setting info-styling-custom margin-top">
                                        <label class="control control--checkbox">
                                            <input type="checkbox" class="checkbox-sizing" name="team_visibility"
                                                id="js-team-visibility-check"> <span>Employees can see their colleagues' time offs.</span>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="bottom-section-pto">
                                    <hr />
                                    <button id="js-save-btn" type="button" class="btn btn-success ml10">Update</button>
                                    <button id="jsSettingsLog" type="button" class="btn btn-success ml10">View Log</button>

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
</div>