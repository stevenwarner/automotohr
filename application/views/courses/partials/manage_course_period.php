<div id="jsCourseManageModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header _csB4">
                <h4 class="modal-title">Manage Course Period</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 _csMt10">
                        <label>Course Period <span class="text-danger">*</span></label>
                        <p>When the course will start and end.</p>
                    </div>
                </div>  
                <input type="hidden" class="form-control" id="jsCourseId" readonly>
                <div class="row">  
                    <div class="col-md-5 col-sm-12 _csMt10">
                        <label>Start Date<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jsStartDate" placeholder="MM/DD/YYYY" readonly>
                    </div>
                    <div class="col-md-2 col-sm-12 text-center _csVm _csMt10">
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-5 col-sm-12 _csMt10">
                        <label>End Date<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jsEndDate" placeholder="MM/DD/YYYY" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn _csF2 _csB1 _csR5 jsCancelManageDate" data-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="button" class="btn _csF2 _csB4 _csR5 jsSaveCourseDates">Update</button>
            </div>
        </div>
    </div>
</div>