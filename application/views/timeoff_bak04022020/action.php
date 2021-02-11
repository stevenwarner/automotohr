<?php 
	// _e($Request, true);
?>

<!-- <div class="wrapper-outer"> -->
    <div class="main">
        <div class="container">
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                <div class="public-view-section">
                    <div class="public-view-section-heading-content">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Time Off, <b><?=$Request['Info']['full_name'];?></b></h3>
                            </div>
                        </div>
                    </div>
                    <div class="public-view-section-content">
                        <div class="row padding-bottom">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-wieght-light">Type</label>
                                    <select id="js-types"></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-wieght-light">Policy</label>
                                    <select id="js-policies"></select>
                                </div>
                            </div>
                        </div>

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
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-wieght-light">Status</label>
                                    <select id="js-status">
                                        <option <?=$Request['Info']['level_status'] == 'approved' ? 'selected="true"' : '';?> value="approved">Approved</option>
                                        <option <?=$Request['Info']['level_status'] == 'rejected' ? 'selected="true"' : '';?> value="rejected">Rejected</option>
                                    </select>
                                </div>
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


                        <div class="row js-fmla-wrap">
                            <hr />
                            <div class="col-sm-12 col-sm-12">
                                <label for="">Is this time off under FMLA? <span class="cs-required">*</span></label>
                                <br />
                                <label class="control control--radio font-normal">
                                    No
                                    <input class="js-fmla-check" name="fmla" value="no" type="radio" <?=$Request['Info']['fmla'] == '' || $Request['Info']['fmla'] == NULL ? 'checked="true"' : '';?> />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    Yes
                                    <input class="js-fmla-check" name="fmla" value="yes" type="radio" <?=$Request['Info']['fmla'] != '' && $Request['Info']['fmla'] != NULL ? 'checked="true"' : '';?> />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;

                                <div class="js-fmla-box"  <?=$Request['Info']['fmla'] != '' && $Request['Info']['fmla'] != NULL ? '' : 'style="display: none;"' ;?>>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Designation Notice
                                        <input class="js-fmla-type-check" name="fmla_type" value="designation" type="radio" <?=$Request['Info']['fmla'] == 'designation' ? 'checked="true"' : '';?> />
                                        <div class="control__indicator"></div>
                                    </label> 
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Certification of Health Care Provider for Employee’s Serious Health Condition 
                                        <input class="js-fmla-type-check" name="fmla_type" value="health" type="radio" <?=$Request['Info']['fmla'] == 'health' ? 'checked="true"' : '';?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Notice of Eligibility and Rights & Responsibilities 
                                        <input class="js-fmla-type-check" name="fmla_type" value="medical" type="radio" <?=$Request['Info']['fmla'] == 'medical' ? 'checked="true"' : '';?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr />

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

                        <div class="row">
                            <div class="col-sm-12 padding-bottom margin-bottom">
                            	<label>Reason</label>
                            	<div>
                                	<?=$Request['Info']['reason'] != '' ? $Request['Info']['reason'] : 'N/A';?>
                            	</div>
                            </div>
                        </div>

                        <hr />
                        <div class="row">
                            <div class="col-lg-12 padding-bottom border-bottom margin-bottom">
                                <div class="form-group">
                                	<label>Comment</label>
                                    <textarea class="ckeditor" style="padding:5px; height:200px; width:100%;" class="form-control" name="message" id="message"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="btn-filter-reset">
                                    <button id="js-save-btn" type="button" class="btn btn-apply">Respond</button>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attachments  -->
                <div class="public-view-section">
                    <div class="public-view-section-content" id="js-attachment-data-area">
                    </div>
                </div>

                <!--  -->
                <?php if(sizeof($Request['History'])) { ?>
                <div class="public-view-section">
                    <div class="public-view-section-heading-content">
                    	 <div class="row">
                            <div class="col-md-12">
                                <h3>History</h3>
                            </div>
                        </div>
                	</div>
                    <div class="public-view-section-content" id="js-history-data-area">
                    	<div id="js-history-data-append"></div>
                	</div>
                </div>
            	<?php } ?> 


            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                <div class="public-view-section">
                    <div class="alert alert-warning cs-warning-box">
                        <p><strong>Policy</strong> <br />Represents which rule is implemented.</p>
                        <p><strong>Status</strong> <br />Represents the current state of Time-off.</p>
                        <p><strong>Requested Time</strong> <br />Represents the timeslot on which the employee will be un-available.</p>
                        <p><strong>Date Time</strong> <br />Represents the date on which employee will be un-available.</p>
                        <p><strong>Partial Leave</strong> <br />Represents wether the one is taking full-day or half-day.</p>
                        <p><strong>Reason</strong> <br />Represents why the one is taking time-off.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->

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



<?php $this->load->view('timeoff/scripts/action'); ?>

<style>
    .cs-required{ font-weight: bold; color: #cc0000; }
    #js-fmla-modal .btn-info{ background-color: #81b431 !important; border-color: #81b431 !important; }
    .full-width{ float: none; }
    .select2-container--default .select2-selection--single{
        background-color: #fff !important;
        border: 1px solid #aaa !important;
        padding-left: 8px !important;
        padding-right: 20px !important;
    }
</style>