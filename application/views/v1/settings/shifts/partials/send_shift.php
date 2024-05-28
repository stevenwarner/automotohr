<form action="javascript:void(0)" id="jsSendShiftNotificationForm" autocomplete="off">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text weight-6">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    Send Shift to Employees
                </h2>
            </div>
            <div class="panel-body" style="min-height: 100px;">

                <div class="row" id="TableData">
                    <br>
                    <div class="col-sm-3">
                        <label>Departments / Teams</label>
                        <br>
                        <?= get_company_departments_teams_dropdown($company_sid, 'teamIds', $filter_team ?? 0); ?>
                    </div>
                    <div class="col-sm-3">
                        <label>Job Title</label>
                        <br>
                        <?= get_jobTitle_dropdown_for_search($company_sid, 'jobtitleIds') ?>
                    </div>
                    <div class="col-sm-6 text-right">
                        <span class="pull-right">
                            <br>
                            <button id="btn_apply" type="button" class="btn btn-orange js-apply-filter">APPLY</button>
                            <button id="btn_reset" type="button" class="btn btn-black btn-theme js-reset-filter">RESET</button>
                        </span>
                    </div>
                </div>
                <!--  -->

                <input type="hidden" class="form-control" name="shiftId" id="jssendShiftId" value="" />
                <input type="hidden" class="form-control" name="shiftDate" id="jssendShiftDate" value="" />


            </div>

        </div>


        <!-- -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    Employees
                </h2>
            </div>
            <div class="panel-body">

                <!--  -->
                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-orange jsSelectAll" type="button">
                            Select all
                        </button>
                        <button class="btn btn-black jsRemoveAll" type="button">
                            Clear all
                        </button>
                    </div>
                </div>

                <hr>
                <div id='employeeList'>  </div>
            </div>
        </div>

        <div class="panel-footer text-right">
            <button class="btn btn-orange jsSendShiftNotification">
                <i class="fa fa-save" aria-hidden="true"></i>
                &nbsp;Send Shift
            </button>
            <button class="btn btn-black jsModalCancel" type="button">
                <i class="fa fa-times-circle" aria-hidden="true"></i>
                &nbsp;Cancel
            </button>
        </div>

    </div>


</form>