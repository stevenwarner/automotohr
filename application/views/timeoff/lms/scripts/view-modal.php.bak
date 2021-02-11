<script src="<?=base_url("assets/js/moment.min.js")?>"></script>
<link rel="stylesheet" href="<?=base_url('assets');?>/css/timeoffstyle.css">
<link rel="stylesheet" href="<?=base_url('assets');?>/css/fmla.css">
<style>
    .employee-info figure img{ border-radius: 3px !important; }
</style>
<!-- Modal -->
<div class="modal fade" id="js-timeoff-view-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content model-content-custom">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Time off Request </h4>
            </div>
            <div class="modal-body full-width modal-body-custom">
                <div class="tab-content">
                    <div id="" class="">
                        <br />
                        <!-- Section One -->
                        <div class="row">
                            <div class="col-sm-6 col-sm-12">
                                <div class="employee-info">
                                    <figure>
                                        <img src="<?php 
                                            if($employerData['profile_picture'] == ''){
                                                    echo base_url('assets/images/img-applicant.jpg');
                                                }else{
                                                    echo AWS_S3_BUCKET_URL.$employerData['profile_picture'];      
                                                }
                                         ?>" class="img-sqaure emp-image" />
                                    </figure>
                                    <div class="text cs-info-text">
                                        <h4><?=$employerData['first_name'].' '.$employerData['last_name'];?></h4>
                                        <p><a href="<?=base_url('employee_profile');?>/<?=$employerData['sid'];?>" target="_blank">Id: <?=$employerData['employee_number'] ? $employerData['employee_number'] : $employerData['sid'];?></a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Type  </label>
                                    <input id="js-types-view" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>Policy  </label>
                                    <input id="js-policies-view" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <!-- Section Two -->
                        <div class="row">
                            <hr />
                            <div class="col-sm-12">
                                <!-- <div class="form-group">
                                    <label>Date <span class="cs-required">*</span></label>
                                </div> -->
                                <div class="row">
                                    <div class="col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="">Start Date <span class="cs-required">*</span></label>
                                            <input readonly="true" type="text" id="js-timeoff-start-date-view" class="form-control js-request-start-date-view" />
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="">End Date <span class="cs-required">*</span></label>
                                            <input readonly="true" type="text" id="js-timeoff-end-date-view" class="form-control js-request-end-date-view" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="js-timeoff-date-box-view" style="display: none;">
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
                            </div>
                        </div>
                       
                        <!-- Section Three -->
                        <div class="row js-fmla-wrap-view" style="display: none;">
                            <hr />
                            <div class="col-sm-12 col-sm-12">
                                <label for="">Is this time off under FMLA? </label>
                                <br />
                                <label class="control control--radio font-normal">
                                    No
                                    <input class="js-fmla-check-view" name="fmla" value="no" type="radio" checked="true" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    Yes
                                    <input class="js-fmla-check-view" name="fmla" value="yes" type="radio" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;

                                <div class="js-fmla-box-view" style="display: none;">
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Designation Notice
                                        <input class="js-fmla-type-check-view" name="fmla_check" value="designation" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label> 
                                    &nbsp;<i title="Designation Notice" data-content="<?=FMLA_DESIGNATION;?>" class="fa fa-question-circle js-popover"></i>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Certification of Health Care Provider for Employee’s Serious Health Condition 
                                        <input class="js-fmla-type-check-view" name="fmla_check" value="health" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Certification of Health Care" data-content="<?=FMLA_CERTIFICATION_OF_HEALTH;?>" class="fa fa-question-circle js-popover"></i>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Notice of Eligibility and Rights & Responsibilities 
                                        <input class="js-fmla-type-check-view" name="fmla_check" value="medical" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Notice of Eligibility and Rights & Responsibilities" data-content="<?=FMLA_RIGHTS;?>" class="fa fa-question-circle js-popover"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-vacation-row-view" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Emergency Contact Number</label>
                                    <input type="text" class="form-control" id="js-vacation-contact-number-view" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="text" class="form-control" id="js-vacation-return-date-view" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Alternate Temporary Address</label>
                                    <input type="text" class="form-control" id="js-vacation-address-view" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-bereavement-row-view" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Relationship</label>
                                    <input type="text" class="form-control" id="js-bereavement-relationship-view" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="text" class="form-control" id="js-bereavement-return-date-view" readonly="true" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-compensatory-row-view" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation Date</label>
                                    <input type="text" class="form-control" id="js-compensatory-return-date-view" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation Start Time</label>
                                    <input type="text" class="form-control" id="js-compensatory-start-time-view" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation End Time</label>
                                    <input type="text" class="form-control" id="js-compensatory-end-time-view" readonly="true" />
                                </div>
                            </div>
                        </div>
                        <!-- Section Four -->
                        <div class="row">
                            <hr />
                            <div class="col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label>Reason</label>
                                    <div id="js-reason-view"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="row m15">
                        <hr />
                        <div class="col-sm-12">
                            <h4>
                                Supporting documents
                            </h4>
                        </div>
                        <div class="col-sm-12">
                            <br />
                            <div class="reponsive">
                                <table class="table table-striped table-bordered" id="js-attachment-listing-view">
                                    <thead>
                                        <tr>
                                            <th>Document Title</th>
                                            <th class="col-sm-3">Document Type</th>
                                            <th class="col-sm-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="js-no-records-view">
                                            <td colspan="3">
                                                <p class="alert alert-info text-center">You haven't attached any documents yet.</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-5 js-btn-view" data-dismiss="modal">Close</button>
            </div>
        </div>
  </div>
</div>

<script>
     //
    function remakeRangeRowsView(data){
        var startDate = $('#js-timeoff-start-date-view').val(),
        endDate = $('#js-timeoff-end-date-view').val();
        //
        var d = {};
        //
        if(typeof(data) === 'object'){
            startDate = data.startDate;
            endDate = data.endDate;
            data.days.map(function(v,i){ d[v.date] = v; });
        }
        //
        $('#js-timeoff-date-box-view').hide();
        $('#js-timeoff-date-box-view tbody tr').remove();
        //
        if(startDate == '' || endDate == '') { return; }
        //
        startDate =  moment(startDate);
        endDate = moment(endDate);
        var diff = endDate.diff(startDate, 'days');
        //
        var rows = '';
        var i = 0,
        il = diff;
        for(i;i<=il;i++){
            var sd = moment(startDate).add(i, 'days');
            var ld = d[sd.format('MM-DD-YYYY')];
            rows += '<tr data-id="'+( i )+'" data-date="'+( sd.format('MM-DD-YYYY') )+'">';
            rows += '    <th style="vertical-align: middle">'+( sd.format('MMMM Do, YYYY') )+'</th>';
            rows += '    <th style="vertical-align: middle">';
            rows += '        <div>';
            rows += '            <label class="control control--radio">';
            rows += '                Full Day';
            rows += '                <input type="radio" name="'+( i )+'_day_type_edit" value="fullday" '+( ld !== undefined && ld.partial == 'fullday' ? 'checked="true"' : ld == undefined ? 'checked="true"' : '' )+' />';
            rows += '                <span class="control__indicator"></span>';
            rows += '            </label>';
            rows += '            <label class="control control--radio">';
            rows += '                Partial Day';
            rows += '                <input type="radio" name="'+( i )+'_day_type_edit" value="partialday" '+( ld !== undefined && ld.partial != 'fullday' ? 'checked="true"' : '' )+' />';
            rows += '                <span class="control__indicator"></span>';
            rows += '            </label>';
            rows += '        </div>';
            rows += '    </th>';
            rows += '    <th>';
            rows += '        <div class="rowd" id="row_'+( i )+'_edit">';
            rows +=          setTimeViewEdit('#row_'+( i )+'', '-el-view'+i, ld);
            rows += '        </div>';
            rows += '    </th>';
            rows += '</tr>';
            //
        }
        //
        $('#js-timeoff-date-box-view tbody').html(rows);
        $('#js-timeoff-date-box-view').show();
    }

    //
    function setTimeViewEdit(target, prefix, data){
        var row = '';
        if(policies[0]['format'] == 'D:H:M'){
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.days : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.minutes : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(policies[0]['format'] == 'D:H'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.days : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(policies[0]['format'] == 'H:M'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.minutes : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }else if(policies[0]['format'] == 'H'){
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }else{
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.minutes : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        //
        if(prefix !== undefined) return row;
        $(target).html(row);
    }
</script>

<style>
.cs-required{
    color:#cc0000;
    font-weight:bold;
}
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
.invoice-fields {
    float: left;
    width: 100%;
    height: 40px;
    border: 1px solid #ccc;
    border-radius: 5px;
    color: #000;
    padding: 0 5px;
    background-color: #eee;
}
.js-attachment-edit,
.js-attachment-view,
.js-attachment-delete,
.js-timeoff-attachment,
.btn-5{ border-radius: 5px !important; }

.modal{ overflow-y: auto !important; }
.ui-datepicker-unselectable .ui-state-default{ background-color: #555555; border-color: #555555; }
input[disabled]{ background-color: #ffffff !important; }
</style>
