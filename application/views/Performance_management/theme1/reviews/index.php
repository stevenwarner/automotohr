<div class="container-fluid">
    <!-- Heading -->
    <div class="csPageHeading">
        <div class="row">
            <div class="col-sm-3">
                <select id="jsFilterReviewType">
                    <option value="all">All Reviews</option>
                    <option value="review">Assigned To Me</option>
                    <option value="feedback">Manager Feedback For You</option>
                </select>
            </div>
            <div class="col-sm-9">
                <span class="csBTNBox">
                    <a href="<?=purl('review/create');?>" class="btn btn-orange"><i class="fa fa-plus-circle"></i>
                        Create a Review</a>
                </span>
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
                        <div class="col-sm-8">
                            <ul>
                                <li><a href="" class="active">Active</a></li>
                                <li><a href="">Draft</a></li>
                                <li><a href="">Archived</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-3">
                            <select id="jsFilterReviewName">
                                <option value="">Review 1</option>
                                <option value="">Review 2</option>
                                <option value="">Review 3</option>
                                <option value="">Review 4</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <a href="" class="btn btn-black"><i class="fa fa-filter"></i> Filter</a>
                        </div>
                    </div>
                </div>
                <!-- Body  -->
                <div class="csPageBoxBody p10">
                    <!-- Filter -->
                    <div class="csPageBodyFilter pt10 pb10 dn">
                        <!--  -->
                        <div class="row">
                            <!--  -->
                            <div class="col-sm-3">
                                <label>Status</label>
                                <select class="form-control"></select>
                            </div>
                            <!--  -->
                            <div class="col-sm-3">
                                <label>Start Date</label>
                                <input type="text" class="form-control" readonly />
                            </div>
                            <!--  -->
                            <div class="col-sm-3">
                                <label>End Date</label>
                                <input type="text" class="form-control" readonly />
                            </div>
                            <!--  -->
                            <div class="col-sm-3">
                                <br />
                                <span class="csBTNBox">
                                    <button class="btn btn-orange">Apply</button>
                                    <button class="btn btn-black">Reset</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Data -->
                    <div class="csPageBodyData">
                        <div class="table-sreponsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-sm-4">Review</th>
                                        <th class="col-sm-2">Start Date</th>
                                        <th class="col-sm-2">Reviewer progress</th>
                                        <th class="col-sm-2">Manager Feedback progress</th>
                                        <th class="col-sm-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>Review 1 <a class="btn btn-xs alert-success">Running</a></p>
                                        </td>
                                        <td>
                                            <p>Jan 01 2021, Sunday</p>
                                        </td>
                                        <td>
                                            <div class="progress csRadius100">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="0"
                                                    aria-valuemin="50" aria-valuemax="100" style="width: 50%;"></div>
                                            </div>
                                            <small>50%</small>
                                        </td>
                                        <td>
                                            <div class="progress csRadius100">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="0"
                                                    aria-valuemin="50" aria-valuemax="100" style="width: 0%;"></div>
                                            </div>
                                            <small>0%</small>
                                        </td>
                                        <td>
                                            <div class="csBTNBox">

                                                <a href="<?=purl('review/1');?>" class="btn btn-black"><i class="fa fa-eye"></i> View</a>
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu1"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                        <li><a href="#">Download Report</a></li>
                                                        <li><a href="#">Print</a></li>
                                                        <li><a href="#">Save As Template</a></li>
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="#">End Review</a></li>
                                                        <li><a href="#">Archive Review</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Review 2 <a class="btn btn-xs alert-warning">Pending</a></p>
                                        </td>
                                        <td>
                                            <p>Jan 01 2021, Sunday</p>
                                        </td>
                                        <td>

                                            <small>0%</small>
                                        </td>
                                        <td>
                                            <div class="progress csRadius100">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="0"
                                                    aria-valuemin="50" aria-valuemax="100" style="width: 0%;"></div>
                                            </div>
                                            <small>0%</small>
                                        </td>
                                        <td>
                                            <div class="csBTNBox">

                                                <a href="<?=purl('review/2');?>" class="btn btn-black"><i class="fa fa-eye"></i> View</a>
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu1"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                        <li><a href="#">Download Report</a></li>
                                                        <li><a href="#">Print</a></li>
                                                        <li><a href="#">Save As Template</a></li>
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="#">End Review</a></li>
                                                        <li><a href="#">Archive Review</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Review 3 <a class="btn btn-xs alert-danger">Ended</a></p>
                                        </td>
                                        <td>
                                            <p>Jan 01 2021, Sunday</p>
                                        </td>
                                        <td>
                                            <div class="progress csRadius100">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="0"
                                                    aria-valuemin="50" aria-valuemax="100" style="width: 0%;"></div>
                                            </div>
                                            <small>0%</small>
                                        </td>
                                        <td>
                                            <div class="progress csRadius100">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="0"
                                                    aria-valuemin="50" aria-valuemax="100" style="width: 60%;"></div>
                                            </div>
                                            <small>60%</small>
                                        </td>
                                        <td>
                                            <div class="csBTNBox">

                                                <a href="<?=purl('review/3');?>" class="btn btn-black"><i class="fa fa-eye"></i> View</a>
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu1"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                        <li><a href="#">Download Report</a></li>
                                                        <li><a href="#">Print</a></li>
                                                        <li><a href="#">Save As Template</a></li>
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="#">End Review</a></li>
                                                        <li><a href="#">Archive Review</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Review 4</p>
                                        </td>
                                        <td>
                                            <p>Jan 01 2021, Sunday</p>
                                        </td>
                                        <td>
                                            <div class="progress csRadius100">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="0"
                                                    aria-valuemin="50" aria-valuemax="100" style="width: 100%;"></div>
                                            </div>
                                            <small>100%</small>
                                        </td>
                                        <td>
                                            <div class="progress csRadius100">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="0"
                                                    aria-valuemin="50" aria-valuemax="100" style="width: 100%;"></div>
                                            </div>
                                            <small>100%</small>
                                        </td>
                                        <td>
                                            <div class="csBTNBox">

                                                <a href="<?=purl('review/4');?>" class="btn btn-black"><i class="fa fa-eye"></i> View</a>
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu1"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                        <li><a href="#">Download Report</a></li>
                                                        <li><a href="#">Print</a></li>
                                                        <li><a href="#">Save As Template</a></li>
                                                        <li role="separator" class="divider"></li>
                                                        <li><a href="#">End Review</a></li>
                                                        <li><a href="#">Archive Review</a></li>
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