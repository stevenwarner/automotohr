<div class="jsAddTimeOff">
    <div class="container-fluid">
        <div class="js-page" data-page="main">
            <!-- Policy -->
            <div class="row">
                <div class="col-xs-12">
                    <label>Select a policy <span class="cs-required">*</span></label>
                    <select id="jsEditPolicy"></select>
                </div>
            </div>
            <hr />
            <!-- Date -->
            <div class="row">
                <div class="col-xs-12">
                    <label>Select a date <span class="cs-required">*</span></label>
                    <input id="jsStartDateEdit" class="form-control" type="text" readonly placeholder="Start date" />
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <p class="text-center">TO</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <input id="jsEndDateEdit" class="form-control" type="text" readonly placeholder="End date" />
                </div>
            </div>
            <hr />

            <div class="row">
                <!--  -->
                <div class="col-xs-12">
                    <label>Status <span class="cs-required">*</span></label>
                    <select id="jsStatusEdit">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <hr />

            <!-- Date Range -->
            <div class="jsDurationBox dn">
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Day type</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <hr />
            </div>

            <!-- Reason -->
            <div class="row">
                <div class="col-xs-12">
                    <label>Why are you taking this time off? </label>
                    <textarea id="jsReasonEdit"></textarea>
                </div>
            </div>
            <hr />

            <!-- Comment -->
            <div class="row">
                <div class="col-xs-12">
                    <label>Comment </label>
                    <textarea id="jsCommentEdit"></textarea>
                </div>
            </div>
            <hr />

            <!--  -->
            <div class="row jsEmailBoxEdit">
                <div class="col-xs-12">
                    <label>Send notification to approvers?</label>
                </div>
                <div class="col-xs-12">
                    <label class="control control--radio">
                        <input type="radio" name="js-send-emailEdit" class="js-send-emailEdit" value="no" />No
                        <div class="control__indicator"></div>
                    </label>&nbsp;
                    <label class="control control--radio">
                        <input type="radio" name="js-send-emailEdit" class="js-send-emailEdit" value="yes"
                            checked="true" />Yes
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
            <hr />

            <!-- Buttons -->
            <div class="row">
                <div class="col-xs-6 pl0">
                    <button class="btn btn-black jsMobileBTN jsModalCancel" data-ask="no">Cancel</button>
                </div>
                <div class="col-xs-6 pr0">
                    <button class="btn btn-orange jsMobileBTN jsEditTimeOffBTN">Update</button>
                </div>
            </div>
            <!-- Extra Fields -->
            <div class="jsExtraFields">
                <!-- Section Four -->
                <div class="row js-vacation-row dn">
                    <hr />
                    <div class="col-xs-12 col-xs-12">
                        <div class="form-group">
                            <label>Emergency Contact Number</label>
                            <input type="text" class="form-control" id="js-vacation-contact-number" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-xs-12">
                        <div class="form-group">
                            <label>Return Date</label>
                            <input type="text" class="form-control" id="js-vacation-return-date" readonly="true" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-xs-12">
                        <div class="form-group">
                            <label>Alternate Temporary Address</label>
                            <input type="text" class="form-control" id="js-vacation-address" />
                        </div>
                    </div>
                </div>

                <!-- Section Four -->
                <div class="row js-bereavement-row dn">
                    <hr />
                    <div class="col-xs-12 col-xs-12">
                        <div class="form-group">
                            <label>Relationship</label>
                            <input type="text" class="form-control" id="js-bereavement-relationship" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-xs-12">
                        <div class="form-group">
                            <label>Return Date</label>
                            <input type="text" class="form-control" id="js-bereavement-return-date" readonly="true" />
                        </div>
                    </div>
                </div>

                <!-- Section Four -->
                <div class="row js-compensatory-row dn">
                    <hr />
                    <div class="col-xs-12 col-xs-12">
                        <div class="form-group">
                            <label>Compensation Date</label>
                            <input type="text" class="form-control" id="js-compensatory-return-date" readonly="true" />
                        </div>
                    </div>
                    <div class="col-xs-3 col-xs-12">
                        <div class="form-group">
                            <label>Compensation Start Time</label>
                            <input type="text" class="form-control" id="js-compensatory-start-time" readonly="true" />
                        </div>
                    </div>
                    <div class="col-xs-3 col-xs-12">
                        <div class="form-group">
                            <label>Compensation End Time</label>
                            <input type="text" class="form-control" id="js-compensatory-end-time" readonly="true" />
                        </div>
                    </div>
                </div>
            </div>


            <!-- Side bar -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="csSidebar csRadius5">
                        <!-- Sidebar head -->
                        <div class="csSidebarHead csRadius5 csRadiusBL0 csRadiusBR0">
                            <div id="jsEmployeeInfoEdit"></div>
                        </div>
                        <div class="csSidebarApproverSection">
                            <h4>Approvers</h4>
                            <div id="jsApproversListingEdit" class="p10"></div>
                            <!--  -->
                            <div id="jsApproversListingoteEdit" class="p10">
                                <hr />
                                <p><strong>Info:</strong></p>
                                <p><i class="fa fa-check-circle text-success"></i>&nbsp; Approved by the approver.</p>
                                <p><i class="fa fa-times-circle text-danger"></i>&nbsp; Rejected by the approver.</p>
                                <p><i class="fa fa-clock-o"></i>&nbsp; Waiting for approver to approve/reject.</p>
                            </div>
                        </div>
                        <div class="csSidebarApproverSection">
                            <h4>AS Of Today</h4>
                            <div id="jsAsOfTodayPolicies"></div>
                        </div>
                        <!-- Sidebar who is off today section   -->
                        <div class="csSidebarApproverSection jsOffOutSideMenu"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>