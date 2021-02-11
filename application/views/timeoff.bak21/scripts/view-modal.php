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
                                    <label>Policies <span class="cs-required">*</span> </label>
                                    <select id="js-edit-modal-policies"></select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <!-- Section Two -->
                        <div class="row">
                            <hr />
                           
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
                                
                            <div class="col-sm-4">
                                <label>Status <span class="cs-required">*</span></label>
                                <select id="js-edit-modal-status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <!--  -->
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
                        <hr />
                        <!-- Section Three -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-12">
                                <label>Is this for a partial day? <span class="cs-required">*</span></label>
                                <br />
                                <label class="control control--radio font-normal">
                                    No
                                    <input class="js-edit-modal-partial-check" name="partial" value="no" type="radio" checked="true" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    Yes
                                    <input class="js-edit-modal-partial-check" name="partial" value="yes" type="radio" />
                                    <div class="control__indicator"></div>
                                </label>

                                <div id="js-edit-modal-note-box" style="display: none;">
                                    <br />
                                    <label>Note</label></label>
                                    <input type="text" class="form-control" id="js-edit-modal-note-input" />
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
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
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Certification of Health Care Provider for Employee’s Serious Health Condition 
                                        <input class="js-fmla-type-check" name="fmla_type" value="health" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Notice of Eligibility and Rights & Responsibilities 
                                        <input class="js-fmla-type-check" name="fmla_type" value="medical" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
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
                <button type="button" class="btn btn-default btn-rounded" data-dismiss="modal" id="js-cancel-btn">CLOSE</button>
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
</style>