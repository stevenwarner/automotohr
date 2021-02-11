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
                                    <label class="font-wieght-light">Policy</label>
                                    <p><?=$Request['Info']['policy_title'];?></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-wieght-light">Status</label>
                                    <p><?=ucwords($Request['Info']['status']);?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">    
                            <div class="col-lg-6">
                                <label class="font-wieght-light">Requested Time</label>
                                <p><?=$Request['Info']['timeoff_breakdown']['text'];?></p>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-wieght-light">Date</label>
                                	<p><?=DateTime::createFromFormat('Y-m-d', $Request['Info']['requested_date'])->format('m-d-Y');?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 padding-top">
                                <label class="font-wieght-light">Partial Leave</label>
                                <p><?=$Request['Info']['is_partial_leave'] == 1 ? 'Yes' : 'No';?></p>
                            </div>

                            <div class="partial-leave-options" id="js-partial-box"  >
                                <div class="col-lg-12">
                                    <div class="employee-add-heading">
                                        <p>I will be absent during these hours</p>
                                    </div> 
                                    <div class="form-group">
                                        <div class="form-group">
                                			<p><?=$Request['Info']['partial_note'];?></p>
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