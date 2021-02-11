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
                            <div class="col-sm-4 col-sm-12">
                                <div class="form-group">
                                    <label>Date <span class="cs-required">*</span></label>
                                    <input readonly="true" type="text" id="js-edit-modal-date" class="form-control js-request-date" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row" id="js-edit-modal-time"></div>
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
                        <hr />
                        <!-- Section Three -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-12">
                                <label>Is partial leave? <span class="cs-required">*</span></label>
                                <br />
                                <label class="control control--radio font-normal">
                                    Yes
                                    <input class="js-edit-modal-partial-check" name="partial" value="yes" type="radio" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    No
                                    <input class="js-edit-modal-partial-check" name="partial" value="no" type="radio" checked="true" />
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
</style>