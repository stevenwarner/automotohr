<link rel="stylesheet" href="<?= base_url("assets/v1/app/css/2023.css"); ?>" />
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('attendance/2022/attendance_left_navbar'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="row _csB7 _csPt10 _csPb10 _csM0">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <div class="hr-select-dropdown">
                                                <select name="cc_type" id="cc_type" class="invoice-fields-select" required>
                                                    <option value="">Please Select Pay Period</option>
                                                    <option value="visa">1st Oct - 31st Oct, 2023</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8 text-left">
                                            <a href="#" class="btn btn-gray csF16 csB7">
                                                <i aria-hidden="true" class="fa fa-chevron-left"></i>
                                                Sarah Coverdale
                                            </a>
                                            <a href="#" class="btn btn-gray csF16 csB7">
                                                James Black
                                                <i aria-hidden="true" class="fa fa-chevron-right"></i>
                                            </a>
                                        </div> 
                                    </div>    
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                            <a href="#" class="btn btn-gray csF16 csB7">
                                                Add Time Off Request
                                            </a>
                                            <a href="#" class="btn btn-gray csF16 csB7">
                                                Unapprove
                                            </a>
                                            <a href="#" class="btn btn-orange csF16 csB7">
                                                Approve TimeSheet
                                            </a>
                                        </div> 
                                    </div> 
                                </div>
                            </div>    
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <p class="_csMt10"><span>Reporting: <strong>Web/Mobile TimeKeeper</strong></span>&nbsp;&nbsp;<span>Status: <strong>Not yet Approved</strong></span></p>    
                        </div>    
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="row _csP10">
                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                    <div class="avatar">
                                        <span class="image_holder">
                                            <?php if(isset($employeeImage) && !empty($employeeImage)) { ?>
                                                <img src="<?php echo $employeeImage; ?>" class="table-image" alt="">
                                            <?php } else { ?>
                                                <img src="<?php echo base_url() . '/images/employee.png'; ?>" class="table-image" alt="">
                                            <?php } ?>
                                        </span>
                                    </div> 
                                </div>
                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                    <p class="_csPtb24">
                                        <strong><?php echo $employeeName;?></strong>
                                        <br>
                                        <span>Hourly</span>
                                    </p>
                                </div>
                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12 _csP0">
                                            <p class="_csPtb24">
                                                <strong>0.00</strong>
                                                <br>
                                                <span>Total Hours</span>
                                            </p>
                                        </div> 
                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                            <p class="_csPtb24">
                                                <strong>0.00</strong>
                                                <br>
                                                <span>Regular</span>
                                            </p>
                                        </div> 
                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                            <p class="_csPtb24">
                                                <strong>0.00</strong>
                                                <br>
                                                <span>Ovretime</span>
                                            </p>
                                        </div> 
                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                            <p class="_csPtb24">
                                                <strong>0.00</strong>
                                                <br>
                                                <span>DOT</span>
                                            </p>
                                        </div> 
                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                            <p class="_csPtb24">
                                                <strong>0.00</strong>
                                                <br>
                                                <span>Holiday</span>
                                            </p>
                                        </div> 
                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                            <p class="_csPtb24">
                                                <strong>0.00</strong>
                                                <br>
                                                <span>PTO</span>
                                            </p>
                                        </div> 
                                    <div> 
                                </div>
                            </div> 
                        </div>    
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="row _tb _bb">
                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                    <label for="date" class="_csM0 _csPtb18">Date</label>
                                </div>
                                <div class="col-lg-11 col-md-11 col-xs-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                    <label for="allSelect" class="control control--radio">
                                                        <input id="allSelect" type="radio">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                                    <label for="activity" class="_csM0 _csPtb18">Activity</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                                    <label for="time_in" class="_csM0 _csPtb18">Time In</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                                    <label for="time_out" class="_csM0 _csPtb18">Time Out</label>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 _lb">
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                                    <label for="over_night" class="_csM0 _csPtb18">Overnight</label>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                                    <label for="duration" class="_csM0 _csPtb18">Duration</label>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                    <label for="camera" class="_csM0 _csPtb18"><i class="fa fa-camera"></i></label>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                    <label for="map" class="_csM0 _csPtb18"><i class="fa fa-map"></i></label>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                <label for="file" class="_csM0 _csPtb18"><i class="fa fa-file-text"></i></label>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                    <label for="attribution" class="_csM0 _csPtb18">Attribution</label>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                    <label for="trash" class="_csM0 _csPtb18"><i class="fa fa-trash"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>        
                                </div>
                            </div> 
                            <?php for($i = 0; $i < 5; $i++) { ?>  
                                <div class="row _bb">
                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12 text-right">
                                        <p class="_csPtb24">
                                            <strong>Wed, Oct 25</strong>
                                            <br>
                                            <span>Total: 0h 00m (0.00hrs)</span>
                                        </p>
                                        <a href="#" class="btn btn-orange csF16 csB7">
                                            Approve
                                        </a>
                                    </div>
                                    <div class="col-lg-11 col-md-11 col-xs-12 col-sm-12 _lb">
                                        <div class="row _bb">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                <div class="row">
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <label for="allSelect" class="control control--radio">
                                                            <input id="allSelect" type="radio">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12 _csP0 text-center">
                                                        <p class="_csPS">Work Hours</p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                                        <p class="_csPS">09:00 AM</p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                                        <p class="_csPS">10:00 AM</p>
                                                    </div>
                                                </div>        
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 _lb">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                                        <p class="_csPS">No</p>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                                        <p class="_csPS">1h 00m</p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-camera"></i></p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-map"></i></p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-file-text"></i></p>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><?php echo substr("Last updated by test on 10/26/2023 10:52 AM",0,25) . '...'; ?></p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-trash"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="row _bb">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                <div class="row">
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <label for="allSelect" class="control control--radio">
                                                            <input id="allSelect" type="radio">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12 _csP0 text-center">
                                                        <div class="hr-selectbox">
                                                            <select name="activity_type" class="invoice-fields-select" required>
                                                                <option value="">Select</option>
                                                                <option value="work_hour">Work Hours</option>
                                                                <option value="work_hour">Break In</option>
                                                                <option value="work_hour">Break Out</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                                        <p class="_csPS">09:00 AM</p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                                        <p class="_csPS">10:00 AM</p>
                                                    </div>
                                                </div>        
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 _lb">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                                        <p class="_csPS">No</p>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                                        <p class="_csPS">1h 00m</p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-camera"></i></p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-map"></i></p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-file-text"></i></p>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><?php echo substr("Last updated by test on 10/26/2023 10:52 AM",0,25) . '...'; ?></p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-trash"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                        <div class="row _bb">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                <div class="row">
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <label for="allSelect" class="control control--radio">
                                                            <input id="allSelect" type="radio">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12 _csP0 text-center">
                                                        <div class="hr-selectbox">
                                                            <select name="activity_type" class="invoice-fields-select" required>
                                                                <option value="">Select</option>
                                                                <option value="work_hour">Work Hours</option>
                                                                <option value="work_hour">Break In</option>
                                                                <option value="work_hour">Break Out</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                                        <input name="eventstarttime" id="eventstarttime" readonly="readonly" type="text" class="form-control">
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                                        <input name="eventstarttime" id="eventstarttime" readonly="readonly" type="text" class="form-control">
                                                    </div>
                                                </div>        
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 _lb">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                                        <div class="hr-selectbox">
                                                            <select name="activity_type" class="invoice-fields-select" required>
                                                                <option value="">Select</option>
                                                                <option value="work_hour">Work Hours</option>
                                                                <option value="work_hour">Break In</option>
                                                                <option value="work_hour">Break Out</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12">
                                                        <p class="_csPS">1h 00m</p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-camera"></i></p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-map"></i></p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-file-text"></i></p>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><?php echo substr("Last updated by test on 10/26/2023 10:52 AM",0,25) . '...'; ?></p>
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                        <p class="_csPS"><i class="fa fa-trash"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>          
                                    </div>
                                </div>  
                            <?php } ?>        
                        </div>
                    </div>      
                    <!--  -->
                </div>
            </div>
        </div>
    </div>
</div>
