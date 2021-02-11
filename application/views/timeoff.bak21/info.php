<?php 
	// _e($Request, true);
?>

<!-- <div class="wrapper-outer"> -->
    <div class="main">
        <div class="container">
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                <?php if(isset($AlertBox)){ ?>
                <div class="public-view-section">
                    <div class="alert alert-info">You have cancelled this Time-off request.</div>
                </div>
                <?php } ?>

                <div class="public-view-section">
                    <div class="public-view-section-heading-content">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Time Off, <b><?=$Request['Info']['full_name'];?></b> <span class="pull-right"><?=isset($Expired) ? '<b>(Expired)</b>' : '';?></span></h3>
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
                                    <p><?=$Request['Info']['policy_title'];?></p>
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
                        <?php if($Params['action'] != 'cancel') { ?>
                        <hr />
                        <div class="row">
                            <div class="col-lg-12 padding-bottom border-bottom margin-bottom">
                                <div class="form-group">
                                	<label>Comment</label>
                                	<p><?=$Request['Info']['reason'] != '' ? $Request['Info']['reason'] : 'N/A';?></p>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
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


<?php $this->load->view('timeoff/scripts/action'); ?>

<script>
    $('input, textarea, select').prop('disabled', true);
</script>
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