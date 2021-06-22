
<br />
<div class="row p10">
    <!--  -->
    <div class="col-md-12 col-sm-12 col-xs-12">
        <!--  -->
        <div class="row">
            <div class="col-sm-4 col-xs-12 col-sm-offset-8">
                <input type="text" readonly placeholder="MM/DD/YYYY - MM/DD/YYY" id="jsRangeDate" class="form-control csCP" />
                <div class="pa10">
                    <i class="fa fa-info-circle csF16 csB7 csCP" aria-hidden="true"></i>&nbsp; Report is generated according to this date range.
                </div>
            </div>
        </div>
        <br />
        <!-- Overall -->
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-11 col-xs-11">
                        <!--  -->
                        <h3 class="csF16 csB7 csW mt0 mb0 pa10 pb10">
                            Overall Report
                        </h3>
                    </div>
                    <div class="col-md-1 col-xs-1">
                        <div class="pull-right pa10">
                            <i class="fa fa-minus-circle csF18 csB7 csW" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <!-- Graph 1 -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div>
                            <canvas id="jsTimeoffPieGraph" height="300px"></canvas>
                        </div>
                        <h3 class="csF16 text-center">
                            <i class="fa fa-info-circle csF16 csB7" aria-hidden="true"></i>&nbsp;Based on started review(s).
                        </h3>
                    </div>
                    <!-- Graph 2 -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div>
                            <canvas id="jsTimeoffPieGraph1" height="300px"></canvas>
                        </div>
                        <h3 class="csF16 text-center">
                            <i class="fa fa-info-circle csF16 csB7" aria-hidden="true"></i>&nbsp;Based on not completed review(s).
                        </h3>
                    </div>
                    <!-- Graph 3 -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div>
                            <canvas id="jsTimeoffPieGraph2" height="300px"></canvas>
                        </div>
                        <h3 class="csF16 text-center">
                            <i class="fa fa-info-circle csF16 csB7" aria-hidden="true"></i>&nbsp;Review(s) By Status.
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department(s) -->
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-11 col-xs-11">
                        <h3 class="csF16 csB7 csW mt0 mb0 pa10 pb10">
                            Department(s) Report
                        </h3>
                    </div>
                    <div class="col-md-1 col-xs-1">
                        <div class="pull-right pa10 csCP">
                            <i class="fa fa-minus-circle csF18 csB7 csW jsHintBtn" data-target="jsDepartmentHint" aria-hidden="true"></i>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="panel-body jsHintBody" data-hint="jsDepartmentHint">
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="csF16 csB7 mt0 mb0 pa10 pb10 csW">
                                    Sales <br>
                                    <span class="csF14">(Last Review Ran on 3rd March Tue, 2021)</span>
                                </h3>
                            </div>
                            <div class="panel-body pl0 pr0 pb0" style="background-color: #f1f1f1">
                                <div class="p10">
                                    <ul class="csBoxMenu">
                                        <li>
                                            10 Review(s) Completed.
                                        </li>
                                        <li>
                                            5 Review(s) In Progress.
                                        </li>
                                        <li>
                                            2 Review(s) Scheduled.
                                        </li>
                                    </ul>
                                </div>
                                <div class="p10">
                                    <p class="csF14">
                                        <strong class="csF16">Overall Status</strong> <br>
                                        The result is calculated based on the 
                                    </p>
                                </div>
                                <div>
                                    <div id="jsGraph3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="csF16 csB7 mt0 mb0 pa10 pb10 csW">
                                    Development <br>
                                    <span class="csF14">(No Review Ran)</span>
                                </h3>
                            </div>
                            <div class="panel-body pl0 pr0 pb0" style="background-color: #f1f1f1">
                                <div class="p10">
                                    <ul class="csBoxMenu">
                                        <li>
                                            0 Review(s) Completed.
                                        </li>
                                        <li>
                                            0 Review(s) In Progress.
                                        </li>
                                        <li>
                                            0 Review(s) Scheduled.
                                        </li>
                                    </ul>
                                </div>
                                <div class="p10">
                                    <p class="csF14">
                                        <strong class="csF16">Overall Status</strong> <br>
                                        The result is calculated based on the 
                                    </p>
                                </div>
                                <div>
                                    <div id="jsGraph4"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="csF16 csB7 mt0 mb0 pa10 pb10 csW">
                                    Marketing <br>
                                    <span class="csF14">(Last Review Ran on 3rd March Tue, 2021)</span>
                                </h3>
                            </div>
                            <div class="panel-body pl0 pr0 pb0" style="background-color: #f1f1f1">
                                <div class="p10">
                                    <ul class="csBoxMenu">
                                        <li>
                                            0 Review(s) Completed.
                                        </li>
                                        <li>
                                            1 Review(s) In Progress.
                                        </li>
                                        <li>
                                            0 Review(s) Scheduled.
                                        </li>
                                    </ul>
                                </div>
                                <div class="p10">
                                    <p class="csF14">
                                        <strong class="csF16">Overall Status</strong> <br>
                                        The result is calculated based on the 
                                    </p>
                                </div>
                                <div>
                                    <div id="jsGraph5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team(s) -->
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-11 col-xs-11">
                        <h3 class="csF16 csB7 csW mt0 mb0 pa10 pb10">
                            Team(s) Report
                        </h3>
                    </div>
                    <div class="col-md-1 col-xs-1">
                        <div class="pull-right pa10 csCP">
                            <i class="fa fa-minus-circle csF18 csB7 csW jsHintBtn" data-target="jsDepartmentHint" aria-hidden="true"></i>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="panel-body jsHintBody" data-hint="jsDepartmentHint">
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="csF16 csB7 mt0 mb0 pa10 pb10 csW">
                                    Sales <br>
                                    <span class="csF14">(Last Review Ran on 3rd March Tue, 2021)</span>
                                </h3>
                            </div>
                            <div class="panel-body pl0 pr0 pb0" style="background-color: #f1f1f1">
                                <div class="p10">
                                    <ul class="csBoxMenu">
                                        <li>
                                            10 Review(s) Completed.
                                        </li>
                                        <li>
                                            5 Review(s) In Progress.
                                        </li>
                                        <li>
                                            2 Review(s) Scheduled.
                                        </li>
                                    </ul>
                                </div>
                                <div class="p10">
                                    <p class="csF14">
                                        <strong class="csF16">Overall Status</strong> <br>
                                        The result is calculated based on the 
                                    </p>
                                </div>
                                <div>
                                    <div id="jsGraph3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="csF16 csB7 mt0 mb0 pa10 pb10 csW">
                                    Development <br>
                                    <span class="csF14">(No Review Ran)</span>
                                </h3>
                            </div>
                            <div class="panel-body pl0 pr0 pb0" style="background-color: #f1f1f1">
                                <div class="p10">
                                    <ul class="csBoxMenu">
                                        <li>
                                            0 Review(s) Completed.
                                        </li>
                                        <li>
                                            0 Review(s) In Progress.
                                        </li>
                                        <li>
                                            0 Review(s) Scheduled.
                                        </li>
                                    </ul>
                                </div>
                                <div class="p10">
                                    <p class="csF14">
                                        <strong class="csF16">Overall Status</strong> <br>
                                        The result is calculated based on the 
                                    </p>
                                </div>
                                <div>
                                    <div id="jsGraph4"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="csF16 csB7 mt0 mb0 pa10 pb10 csW">
                                    Marketing <br>
                                    <span class="csF14">(Last Review Ran on 3rd March Tue, 2021)</span>
                                </h3>
                            </div>
                            <div class="panel-body pl0 pr0 pb0" style="background-color: #f1f1f1">
                                <div class="p10">
                                    <ul class="csBoxMenu">
                                        <li>
                                            0 Review(s) Completed.
                                        </li>
                                        <li>
                                            1 Review(s) In Progress.
                                        </li>
                                        <li>
                                            0 Review(s) Scheduled.
                                        </li>
                                    </ul>
                                </div>
                                <div class="p10">
                                    <p class="csF14">
                                        <strong class="csF16">Overall Status</strong> <br>
                                        The result is calculated based on the 
                                    </p>
                                </div>
                                <div>
                                    <div id="jsGraph5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee(s) -->
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-11 col-xs-11">
                        <h3 class="csF16 csB7 csW mt0 mb0 pa10 pb10">
                            Employee(s) Report
                        </h3>
                    </div>
                    <div class="col-md-1 col-xs-1">
                        <div class="pull-right pa10 csCP">
                            <i class="fa fa-minus-circle csF18 csB7 csW jsHintBtn" data-target="jsDepartmentHint" aria-hidden="true"></i>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="panel-body jsHintBody" data-hint="jsDepartmentHint">
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="csF16 csB7 mt0 mb0 pa10 pb10 csW">
                                    Sales <br>
                                    <span class="csF14">(Last Review Ran on 3rd March Tue, 2021)</span>
                                </h3>
                            </div>
                            <div class="panel-body pl0 pr0 pb0" style="background-color: #f1f1f1">
                                <div class="p10">
                                    <ul class="csBoxMenu">
                                        <li>
                                            10 Review(s) Completed.
                                        </li>
                                        <li>
                                            5 Review(s) In Progress.
                                        </li>
                                        <li>
                                            2 Review(s) Scheduled.
                                        </li>
                                    </ul>
                                </div>
                                <div class="p10">
                                    <p class="csF14">
                                        <strong class="csF16">Overall Status</strong> <br>
                                        The result is calculated based on the 
                                    </p>
                                </div>
                                <div>
                                    <div id="jsGraph3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="csF16 csB7 mt0 mb0 pa10 pb10 csW">
                                    Development <br>
                                    <span class="csF14">(No Review Ran)</span>
                                </h3>
                            </div>
                            <div class="panel-body pl0 pr0 pb0" style="background-color: #f1f1f1">
                                <div class="p10">
                                    <ul class="csBoxMenu">
                                        <li>
                                            0 Review(s) Completed.
                                        </li>
                                        <li>
                                            0 Review(s) In Progress.
                                        </li>
                                        <li>
                                            0 Review(s) Scheduled.
                                        </li>
                                    </ul>
                                </div>
                                <div class="p10">
                                    <p class="csF14">
                                        <strong class="csF16">Overall Status</strong> <br>
                                        The result is calculated based on the 
                                    </p>
                                </div>
                                <div>
                                    <div id="jsGraph4"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="csF16 csB7 mt0 mb0 pa10 pb10 csW">
                                    Marketing <br>
                                    <span class="csF14">(Last Review Ran on 3rd March Tue, 2021)</span>
                                </h3>
                            </div>
                            <div class="panel-body pl0 pr0 pb0" style="background-color: #f1f1f1">
                                <div class="p10">
                                    <ul class="csBoxMenu">
                                        <li>
                                            0 Review(s) Completed.
                                        </li>
                                        <li>
                                            1 Review(s) In Progress.
                                        </li>
                                        <li>
                                            0 Review(s) Scheduled.
                                        </li>
                                    </ul>
                                </div>
                                <div class="p10">
                                    <p class="csF14">
                                        <strong class="csF16">Overall Status</strong> <br>
                                        The result is calculated based on the 
                                    </p>
                                </div>
                                <div>
                                    <div id="jsGraph5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--  -->
                <hr />
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <p class="csF16">Showing 3 of 100</p>
                    </div>
                    <div class="col-md-8 col-xs-12">
                        <ul class="csPaginationMenu text-right">
                            <li><a href="">1</a></li>
                            <li class="active"><a href="">2</a></li>
                            <li><a href="">3</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>