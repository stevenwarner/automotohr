<div class="container-fluid">
    <!-- Heading -->
    <div class="csPageHeading">
        <div class="row">
            <div class="col-sm-12">
                <a href="<?=purl('reviews');?>" class="btn btn-black"><i class="fa fa-long-arrow-left"></i> All
                    Reviews</a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1>
                    <span class="csBTNBox">
                        <div class="dropdown">
                            <button class="btn btn-orange btn-lg dropdown-toggle" type="button" id="dropdownMenu1"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Actions <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu csUL" aria-labelledby="dropdownMenu1" style="left: -80%">
                                <li><a href="#"><i class="fa fa-plus-circle"></i> Add Reviewee</a></li>
                                <li><a href="#"><i class="fa fa-download"></i> Download Report</a></li>
                                <li><a href="#"><i class="fa fa-print"></i> Print</a></li>
                                <li><a href="#"><i class="fa fa-clock-o"></i> Change Due Date</a></li>
                                <li><a href="#"><i class="fa fa-pencil-square-o"></i> Edit Review Name</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#"><i class="fa fa-stop"></i> End Review</a></li>
                            </ul>
                        </div>
                    </span>
                    <strong>Review 1 <span class="btn alert-success">Running</span></strong> <br />
                    <p>Last Run by Mubashir Ahmed on Jan 01 2021, Sunday</p>
                </h1>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="row">
        <!-- Content Area -->
        <div class="col-sm-12 col-xs-12">
            <!-- Content Area -->
            <div class="csPageBox csRadius5">
                <!-- Heading -->
                <div class="csPageBoxHeader pa10 pl10 pr10">
                    <div class="row">
                        <div class="col-sm-6">
                            <ul>
                                <li><a href="" class="active">Reviewees (16)</a></li>
                                <li><a href="">Reviewers (5)</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-3">
                            <select id="jsFilterDepartments">
                                <option value="">All Departments</option>
                                <option value="">Department 1</option>
                                <option value="">Department 2</option>
                                <option value="">Department 3</option>
                                <option value="">Department 4</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select id="jsFilterTeams">
                                <option value="">All Teams</option>
                                <option value="">Team 1</option>
                                <option value="">Team 2</option>
                                <option value="">Team 3</option>
                                <option value="">Team 4</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Body  -->
                <div class="csPageBoxBody p10">
                    <!-- Data -->
                    <div class="csPageBodyProgress pt10">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3><strong>Reviewers Progress</strong></h3>
                                <div class="progress csRadius100">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                        aria-valuemax="100" style="width: 20%;"></div>
                                </div>
                                <ul>
                                    <li>
                                        <span class="csRadius50 active"></span>
                                        80% Completed
                                    </li>
                                    <li>
                                        <span class="csRadius50"></span>
                                        20% Not Completed
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <h3><strong>Manager Feedback Progress</strong></h3>
                                <div class="progress csRadius100">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0"
                                        aria-valuemax="100" style="width: 100%;"></div>
                                </div>
                                <ul>
                                    <li>
                                        <span class="csRadius50 active"></span>
                                        100% Completed
                                    </li>
                                    <li>
                                        <span class="csRadius50"></span>
                                        0% Not Completed
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="csPageBodyData">
                        <div class="table-sreponsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-sm-4">Reviewee</th>
                                        <th class="col-sm-2">Review Period</th>
                                        <th class="col-sm-2">Reviewer Progress</th>
                                        <th class="col-sm-2">Manager Feedback Progress</th>
                                        <th class="col-sm-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="csEBox">
                                                <figure>
                                                    <img src="<?=randomData('img');?>"
                                                        class="csRadius50" />
                                                </figure>
                                                <div class="csEBoxText">
                                                    <h4 class="mb0"><strong><?=randomData('name');?></strong></h4>
                                                    <p class="">(QA) [Admin Plus]</p>
                                                    <a href="" class="btn btn-black btn-xs cdRadius5">View Profile</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p>Jan 01 - Jan 05</p>
                                        </td>
                                        <td>
                                            <p>no reviewers</p>
                                        </td>
                                        <td>
                                            <div class="csPBox">
                                                <ul class="mb0">
                                                    <li><img src="<?=randomData('img');?>" class="csRadius50"></li>
                                                    <li><img src="<?=randomData('img');?>" class="csRadius50"></li>
                                                    <li><img src="<?=randomData('img');?>" class="csRadius50"></li>
                                                </ul>
                                                <span>60% Not Shared</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="csBTNBox">

                                                <a href="<?=purl('reviewer_feedback/1/1');?>" class="btn btn-black"><i class="fa fa-eye"></i> View</a>
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu1"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                        <li><a href="#">Remove Revieww</a></li>
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="#">Settings</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="csEBox">
                                                <figure>
                                                    <img src="<?=randomData('img');?>"
                                                        class="csRadius50" />
                                                </figure>
                                                <div class="csEBoxText">
                                                    <h4 class="mb0"><strong><?=randomData('name');?></strong></h4>
                                                    <p class="">(QA) [Admin Plus]</p>
                                                    <a href="" class="btn btn-black btn-xs cdRadius5">View Profile</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p>Jan 01 - Jan 05</p>
                                        </td>
                                        <td>
                                        <div class="csPBox">
                                                <ul class="mb0">
                                                    <li><img src="<?=randomData('img');?>" class="csRadius50"></li>
                                                    <li><img src="<?=randomData('img');?>" class="csRadius50"></li>
                                                    <li><img src="<?=randomData('img');?>" class="csRadius50"></li>
                                                </ul>
                                                <span>60% Not Shared</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="csPBox">
                                                <ul class="mb0">
                                                    <li><img src="<?=randomData('img');?>" class="csRadius50"></li>
                                                    <li><img src="<?=randomData('img');?>" class="csRadius50"></li>
                                                    <li><img src="<?=randomData('img');?>" class="csRadius50"></li>
                                                </ul>
                                                <span>60% Not Shared</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="csBTNBox">

                                                <a href="<?=purl('feedback/1/1');?>" class="btn btn-black"><i class="fa fa-eye"></i> View</a>
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu1"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                        <li><a href="#">Remove Revieww</a></li>
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="#">Settings</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!--  -->
                    <div class="clearfix"></div>
                </div>
                <!--  -->
                <div class="csPageBoxFooter p10">
                    <div class="row">
                        <div class="col-sm-6"><strong class="mt10">Showing 10 of 100</strong></div>
                        <div class="col-sm-6">
                            <ul class="pagination csPagination">
                                <li class="page-item"><a href="javascript:void(0)">First</a></li>
                                <li class="page-item"><a href="javascript:void(0)">&laquo;</a></li>
                                <li class="page-item"><a href="javascript:void(0)">1</a></li>
                                <li class="page-item"><a href="javascript:void(0)">2</a></li>
                                <li class="page-item"><a href="javascript:void(0)">3</a></li>
                                <li class="page-item"><a href="javascript:void(0)">4</a></li>
                                <li class="page-item"><a href="javascript:void(0)">&raquo;</a></li>
                                <li class="page-item"><a href="javascript:void(0)">Last</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>