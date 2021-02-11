<script src="<?=base_url("assets/js/moment.min.js")?>"></script>
<link rel="stylesheet" href="<?=base_url('assets');?>/css/timeoffstyle.css">
<!-- Modal -->
<div class="modal fade" id="js-edit-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content model-content-custom">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="js-edit-modal-header"></h4>
            </div>
            <div class="modal-body full-width modal-body-custom">
                <div class="row">
                    <div class="pto-tabs">
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs" id="js-edit-modal-tabs"></ul>
                        </div>
                    </div>
                </div>
                <br />
                <!-- Main Page -->
                <div class="tab-content js-em-page" id="js-detail-page">
                    <div>
                        <!-- Section One -->
                        <div class="row">
                            <div class="col-sm-6 col-sm-12">
                                <div class="employee-info">
                                    <figure>
                                        <img id="js-eme-img" class="img-circle emp-image" />
                                    </figure>
                                    <div class="text cs-info-text">
                                        <h4 id="js-eme-name"></h4>
                                        <p><a href="" id="js-eme-id" target="_blank"></a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Types <span class="cs-required">*</span> </label>
                                    <select id="js-edit-modal-types"></select>
                                </div>
                                <div class="form-group js-policy-box">
                                    <label>Policies <span class="cs-required">*</span> </label>
                                    <select id="js-edit-modal-policies"></select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <!-- Section Two -->
                        <div class="row">
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label for="">Start Date <span class="cs-required">*</span></label>
                                    <input readonly="true" type="text" id="js-timeoff-start-date-edit" class="form-control js-request-start-date-edit" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label for="">End Date <span class="cs-required">*</span></label>
                                    <input readonly="true" type="text" id="js-timeoff-end-date-edit" class="form-control js-request-end-date-edit" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Status <span class="cs-required">*</span></label>
                                <select id="js-edit-modal-status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="cancelled">Canceled</option>
                                </select>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row" id="js-timeoff-date-box-edit" style="display: none;">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Duration Type</th>
                                                <th>Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <!--  -->
                        <div class="row js-fmla-wrap">
                            <hr />
                            <div class="col-sm-12 col-sm-12">
                                <label for="">Is this time off under FMLA? <span class="cs-required">*</span></label>
                                <br />
                                <label class="control control--radio font-normal">
                                    No
                                    <input class="js-fmla-check" name="fmla" value="no" type="radio" checked="true" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    Yes
                                    <input class="js-fmla-check" name="fmla" value="yes" type="radio" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;

                                <div class="js-fmla-box" style="display: none;">
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Designation Notice
                                        <input class="js-fmla-type-check" name="fmla_type" value="designation" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label> 
                                    &nbsp;<i title="Designation Notice" data-content="<?=FMLA_DESIGNATION;?>" class="fa fa-question-circle js-popover"></i>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Certification of Health Care Provider for Employee’s Serious Health Condition 
                                        <input class="js-fmla-type-check" name="fmla_type" value="health" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Certification of Health Care" data-content="<?=FMLA_CERTIFICATION_OF_HEALTH;?>" class="fa fa-question-circle js-popover"></i>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Notice of Eligibility and Rights & Responsibilities 
                                        <input class="js-fmla-type-check" name="fmla_type" value="medical" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Notice of Eligibility and Rights & Responsibilities" data-content="<?=FMLA_RIGHTS;?>" class="fa fa-question-circle js-popover"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Section Four -->
                            <div class="row js-vacation-row-edit" style="display: none;">
                                <hr />
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Emergency Contact Number</label>
                                        <input type="text" class="form-control" id="js-vacation-contact-number-edit" />
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Return Date</label>
                                        <input type="text" class="form-control" id="js-vacation-return-date-edit" readonly="true" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Alternate Temporary Address</label>
                                        <input type="text" class="form-control" id="js-vacation-address-edit" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section Four -->
                            <div class="row js-bereavement-row-edit" style="display: none;">
                                <hr />
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Relationship</label>
                                        <input type="text" class="form-control" id="js-bereavement-relationship-edit" />
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Return Date</label>
                                        <input type="text" class="form-control" id="js-bereavement-return-date-edit" readonly="true" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section Four -->
                            <div class="row js-compensatory-row-edit" style="display: none;">
                                <hr />
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Compensation Date</label>
                                        <input type="text" class="form-control" id="js-compensatory-return-date-edit" readonly="true" />
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Compensation Start Time</label>
                                        <input type="text" class="form-control" id="js-compensatory-start-time-edit" readonly="true" />
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Compensation End Time</label>
                                        <input type="text" class="form-control" id="js-compensatory-end-time-edit" readonly="true" />
                                    </div>
                                </div>
                            </div>
                        <hr />
                        <!-- Section Four -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label>Reason</label>
                                    <div id="js-edit-modal-reason"></div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <!-- Section Five -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label>Comments</label>
                                    <textarea id="js-edit-modal-comment"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <!-- Section Six -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-12">
                                <div class="progress-bar-custom" id="js-edit-modal-progress-bar">
                                   <div class="progress-bar-tooltip">
                                       <div class="progress">
                                           <div class="progress-bar progress-bar-success progress-bar-success-blue" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0%">
                                               <div class="sr-only"></div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="progress-percent progress-percent-custom"><span>0</span>% Completed</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- History Page -->
                <div class="js-em-page" id="js-history-page">
                    <!--  -->
                    <div id="js-history-data-area">
                        <div id="js-history-data-append"></div>
                    </div>
                </div>

                 <!-- Attachments Page -->
                <div class="js-em-page" id="js-attachment-page">
                    <!--  -->
                    <div id="js-attachment-data-area">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-rounded" id="js-edit-modal-save-btn">RESPOND</button>
                <button type="button" class="btn btn-default btn-rounded" data-dismiss="modal" id="js-cancel-btn">CLOSE</button>
            </div>
        </div>
  </div>
</div>

<style>
.employee-info .cs-info-text {
    padding-left: 30px;
}
.employee-info figure {
    width: 60px;
    height: 60px;
}
.employee-info .text h4{
    font-weight: 600;
    font-size: 20px;
    margin: 0;
}
.employee-info .text a{ color: #aaa;}
.employee-info .text p{
    color: #818181;
    font-weight: normal;
    font-size: 18px;
    margin: 0;
}
.ui-datepicker-unselectable .ui-state-default{ background-color: #555555; border-color: #555555; }
#ui-datepicker-div{ z-index: 1051 !important; }
</style>