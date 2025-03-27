<?php $this->load->view('timeoff/includes/header'); ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="right-content">
                        <!-- loader -->
                        <div class="loader js-ilcr" style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; width: 100%; background: rgba(255,255,255,.8); z-index: 9999 !important; display: none;">
                            <i class="fa fa-spinner fa-spin" style="text-align: center; top: 50%; left: 50%; font-size: 40px; position: relative;"></i>
                        </div>
                        <!-- Header -->
                        <div class="border-top-section border-bottom">
                            <div class="col-xs-6 col-lg-6">
                                <div class="pto-top-heading-left">
                                    <p>Create Time Off Request</p>
                                </div>
                            </div>
                        </div>
                        <!-- Body -->
                        <div class="row m15">
                            <!-- Select Employee -->
                            <div class="col-sm-8" style="margin: 20px 0; ">
                                <select id="js-employee-ul"></select>
                                <!-- <div id="js-selected-employee"></div>
                                <div class="form-group img-dropdown" id="js-select-employee">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Select an Employee<span class="hr-select-dropdown"></span></a>
                                    <ul class="dropdown-menu cs-custom-dd" id="js-employee-ul"></ul>
                                </div> -->
                            </div>
                        </div>
                        <!-- Rows  -->
                        <div class="pto-tabs" style="padding-top: 10px; padding-bottom: 10px; display: none;" id="js-main-box">
                            <!--  -->
                            <div class="row m15">
                                <div class="col-sm-5">
                                    <div class="employee-add-heading">
                                        <label>Policy <span class="cs-required">*</span></label>
                                        <select id="js-policies"></select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Start Date - End Date <span class="cs-required">*</span></label>
                                        <div class="row">
                                            <div class="col-sm-6" style="padding-right: 0">
                                                <input readonly="true" type="text" id="js-timeoff-start-date" class="form-control js-request-start-date" style="height: 43px; border-right:0; border-top-right-radius: 0; border-bottom-right-radius: 0" />
                                            </div>
                                            <div class="col-sm-6" style="padding-left: 0">
                                                <input readonly="true" type="text" id="js-timeoff-end-date" class="form-control js-request-end-date" style="height: 43px; border-left:0;  border-top-left-radius: 0; border-bottom-left-radius: 0" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="employee-add-heading">
                                        <label>Status <span class="cs-required">*</span></label>
                                        <select id="js-status">
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                            <option value="cancelled">Canceled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!--  -->
                            <div class="row" id="js-timeoff-date-box" style="display: none;">
                                <hr />
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
                            <!-- <div class="row m15">
                                <div class="col-sm-12">
                                    <h4>Is this for a partial day?</h4>
                                    <div>
                                        <label class="control control--radio">
                                            <input type="radio" name="js-partial-leave" class="js-partial-leave" value="no" checked="true" />No&nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio">
                                            <input type="radio" name="js-partial-leave" class="js-partial-leave" value="yes" />Yes
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-12" id="js-partial-leave-box" style="display: none;">
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Start Time <span class="cs-required">*</span></label>
                                            <input type="text" id="js-start-partial-time" class="form-control" readonly="true" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label>End Time <span class="cs-required">*</span></label>
                                            <input type="text" id="js-end-partial-time" class="form-control" readonly="true" />
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Note</label></label>
                                            <input type="text" class="form-control" id="js-partial-note" />
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <!--  -->
                            <div class="row m15">
                                <hr />
                                <div class="col-sm-12">
                                    <h4>Send email notifications?</h4>
                                </div>
                                <div class="col-sm-12">
                                    <label class="control control--radio">
                                        <input type="radio" name="js-send-email" class="js-send-email" value="no" />No&nbsp;
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio">
                                        <input type="radio" name="js-send-email" class="js-send-email" value="yes" checked="true" />Yes
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="row m15 js-fmla-wrap" style="display: none;">
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
                                            <input class="js-fmla-type-check" name="fmla_check" value="designation" type="radio" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        &nbsp;<i title="Designation Notice" data-content="<?= FMLA_DESIGNATION; ?>" class="fa fa-question-circle js-popover"></i>
                                        <br />
                                        <label class="control control--radio font-normal">
                                            Certification of Health Care Provider for Employee’s Serious Health Condition
                                            <input class="js-fmla-type-check" name="fmla_check" value="health" type="radio" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        &nbsp;<i title="Certification of Health Care" data-content="<?= FMLA_CERTIFICATION_OF_HEALTH; ?>" class="fa fa-question-circle js-popover"></i>
                                        <br />
                                        <label class="control control--radio font-normal">
                                            Notice of Eligibility and Rights & Responsibilities
                                            <input class="js-fmla-type-check" name="fmla_check" value="medical" type="radio" />
                                            <div class="control__indicator"></div>
                                        </label>
                                        &nbsp;<i title="Notice of Eligibility and Rights & Responsibilities" data-content="<?= FMLA_RIGHTS; ?>" class="fa fa-question-circle js-popover"></i>
                                    </div>
                                </div>
                            </div>


                            <!-- Section Four -->
                            <div class="row m15 js-vacation-row" style="display: none;">
                                <hr />
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Emergency Contact Number</label>
                                        <input type="text" class="form-control" id="js-vacation-contact-number" />
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Return Date</label>
                                        <input type="text" class="form-control" id="js-vacation-return-date" readonly="true" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Alternate Temporary Address</label>
                                        <input type="text" class="form-control" id="js-vacation-address" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section Four -->
                            <div class="row m15 js-bereavement-row" style="display: none;">
                                <hr />
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Relationship</label>
                                        <input type="text" class="form-control" id="js-bereavement-relationship" />
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Return Date</label>
                                        <input type="text" class="form-control" id="js-bereavement-return-date" readonly="true" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section Four -->
                            <div class="row m15 js-compensatory-row" style="display: none;">
                                <hr />
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Compensation Date</label>
                                        <input type="text" class="form-control" id="js-compensatory-return-date" readonly="true" />
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Compensation Start Time</label>
                                        <input type="text" class="form-control" id="js-compensatory-start-time" readonly="true" />
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-12">
                                    <div class="form-group">
                                        <label>Compensation End Time</label>
                                        <input type="text" class="form-control" id="js-compensatory-end-time" readonly="true" />
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row m15">
                                <hr />
                                <div class="col-sm-12">
                                    <h4>Reason</h4>
                                </div>
                                <div class="col-sm-12">
                                    <textarea id="js-reason"></textarea>
                                </div>
                            </div>
                            <hr />
                            <!--  -->
                            <div class="row m15">
                                <div class="col-sm-12">
                                    <h4>Comment</h4>
                                </div>
                                <div class="col-sm-12">
                                    <textarea id="js-comment"></textarea>
                                </div>
                            </div>
                            <!-- Supporting Documents -->
                            <hr />
                            <!--  -->
                            <div class="row m15">
                                <div class="col-sm-12">
                                    <h4>
                                        Supporting documents
                                        <span class="pull-right">
                                            <button class="btn btn-success js-timeoff-attachment"><i class="fa fa-plus"></i>&nbsp; Add Document</button>
                                        </span>
                                    </h4>
                                </div>
                                <div class="col-sm-12">
                                    <br />
                                    <div class="reponsive">
                                        <table class="table table-striped table-bordered" id="js-attachment-listing">
                                            <thead>
                                                <tr>
                                                    <th>Document Title</th>
                                                    <th class="col-sm-3">Document Type</th>
                                                    <th class="col-sm-2">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="js-no-records">
                                                    <td colspan="3">
                                                        <p class="alert alert-info text-center">You haven't attached any documents yet.</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <!--  -->
                            <div class="row m15" id="js-btn-box">
                                <div class="col-lg-12">
                                    <div class="btn-filter-reset full-width">
                                        <button id="js-cancel-btn" type="button" class="btn btn-reset">CANCEL</button>
                                        <button id="js-save-btn" type="button" class="btn btn-apply">CREATE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Section -->
                    <div class="employee-bottom-section">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="employee-add-heading">
                                    <p>Employee Section</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h5> Instructions: To ensure that your information remains completely confidential, and to ensure that Family or Medical leave is properly designated, there are three forms available for you to choose from if you are requesting time off from work.</h5>
                                <ul>
                                    <li>To request time off for Vacation, Personal Time, Jury day, or any other reason not related to your health, use the Non-Medical Time-Off Request,
                                    </li>

                                    <li>To request time off for a health-related reason such as visits to a health-care provider, Workers Compensation, etc. use the Medical Time-Off Request form. The confidential medical information reported on that form will be securely filed.
                                    </li>

                                    <li>To request Family or Medical leave, first review the family & Medical Leave policy. Then use a Family & Medical Leave Request form. The confidential medical information reported on that form will be securely filed.
                                    </li>

                                </ul>
                                <h5><strong>Note:</strong> Allowed supporting document types <b>.doc, .docx, .pdf, .png, .jpg, .jpeg</b></h5>
                            </div>
                        </div>
                    </div>

                    <div class="employee-bottom-section">
                        <div class="col-sm-12 bg-danger p10">
                            <span><strong><em>Note: Represents the policies that are not available to employees until they meet the Accrual or Qualify. <br /><br />To see why, click '<i class="fa fa-question-circle"></i>' icon next to the policy title.</strong></em></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FMLA model -->
<div class="modal fade" id="js-fmla-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="float: none;">
                <!-- FMLA forms -->
                <div class="js-page" id="js-fmla">
                    <span class="pull-right" style="margin: 5px 10px 20px;"><button class="btn btn-info btn-5 js-shift-page">Back</button></span>

                    <div class="js-form-area" data-type="health" style="display: none;">
                        <?php $this->load->view('timeoff/fmla/employee/health'); ?>
                    </div>
                    <div class="js-form-area" data-type="medical" style="display: none;">
                        <?php $this->load->view('timeoff/fmla/employee/medical'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php $this->load->view('timeoff/loader'); ?>
<?php $this->load->view('timeoff/scripts/create_timeoff'); ?>

<style>
    #js-fmla-modal .btn-info {
        background-color: #81b431 !important;
        border-color: #81b431 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 50% !important;
        transform: translateY(-50%);
    }
</style>