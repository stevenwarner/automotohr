<?php $this->load->view('timeoff/includes/header'); ?>


<div class="csPageMain">
    <div class="container-fluid">
        <div class="csPageWrap csRadius5 csShadow">
            <!-- Loader -->
            <div class="csIPLoader jsIPLoader" data-page="setting"><i class="fa fa-circle-o-notch fa-spin"></i></div>
            <!-- Page Header -->
            <div class="csPageHeader">
                <div class="row">
                    <div class="col-sm-12">
                        <h4><strong>Settings</strong></h4>
                    </div>
                </div>
            </div>
            <!-- Content Area -->
            <div class="csPageBody">
                <!--  -->
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="font-wieght-light">Default Time Slot (Hours)</label>
                            <input type="text" class="form-control" aria-label="Amount (rounded to the nearest dollar)"
                                id="js-default-time-slot-hours">
                        </div>
                    </div>
                </div>

                <!--  -->
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control control--checkbox">
                                Update employees time slot
                                <input type="checkbox" name="js-for-all-employees" class="js-for-all-employees" />
                                <div class="control__indicator"></div>
                                <span title="Note" data-trigger="hover" data-toggle="popovers" data-placement="right"
                                    data-content="If checked then all employee(s) time slot is changed to the selected one." style="display: inline-block;">
                                    <div class="info-custom">&nbsp;<i class="fa fa-question-circle"></i></div>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                
                <!--  -->
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <hr />
                            <label>
                                Time Slot Format
                            </label>
                            <select name="template" id="js-formats"></select>
                        </div>
                    </div>
                </div>
                
                <!--  -->
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <hr />
                            <label>
                                Week Off Day(s)
                            </label>
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

                <!--  -->
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <hr />
                            <label>
                                Select Theme
                            </label>
                            <select name="themes" id="js-themes">
                                <option value="1">Theme 1 (List)</option>
                                <option value="2">Theme 2 (Box)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!--  -->
                <div class="form-group dn">
                    <div class="row">
                        <div class="col-sm-6">
                            <hr />
                            <label class="control control--checkbox">
                                <input type="checkbox" class="checkbox-sizing" name="send_email"
                                id="js-send-email-check">
                                <span>Do not send notifications</span>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!--  -->
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <hr />
                            <label class="control control--checkbox">
                                <input type="checkbox" class="checkbox-sizing" name="send_email"
                                id="js-team-visibility-check">
                                <span>Employees can see their colleagues' time offs.</span>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!--  -->
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <hr />
                            <button id="js-save-btn" type="button" class="btn btn-orange btn-theme ml10">Update Settings</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>