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
                                    <label class="font-wieght-light">Policy</label>
                                    <select id="js-policies"></select>
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

                        <div class="row">    
                            <div class="col-lg-6">
                                <label class="font-wieght-light">Requested Time</label>
                                <div class="row">
                                	<div id="js-time"></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-wieght-light">&nbsp;</label> <br />

                                    <label class="font-wieght-light">Date</label>
                                    <div class="pto-plan-date hr-select-dropdown">                                     
                                        <input type="text" readonly="true" class="form-control" value="<?=$Request['Info']['requested_date'];?>" id="js-date" />
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 padding-top">
                                <label class="font-wieght-light">Is this for a partial day?</label>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group autoheight">
                                    <label class="control control--radio">
                                        No
                                        <input class="js-partial-leave" type="radio" name="marriage_status" value="no"  <?=$Request['Info']['is_partial_leave'] == 0 ? 'checked="checked"' : '' ;?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group autoheight">
                                    <label class="control control--radio">
                                        Yes
                                        <input class="js-partial-leave" type="radio" name="marriage_status" value="yes" <?=$Request['Info']['is_partial_leave'] == 1 ? 'checked="checked"' : '' ;?>>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="partial-leave-options" id="js-partial-box"  >
                                <div class="col-lg-12">
                                    <div class="employee-add-heading">
                                        <p>I will be absent during these hours</p>
                                    </div> 
                                    <div class="form-group">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-custom-height" value="<?=$Request['Info']['partial_leave_note'];?>" id="js-partial-note" />
                                        </div> 
                                    </div>
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

<style>
    .cs-required{ font-weight: bold; color: #cc0000; }
</style>