<div class="container-fluid">
    <!-- Heading -->
    <div class="csPageHeading">
        <div class="row">
            <div class="col-sm-3">
                <select id="jsFilterReviewType">
                    <option value="-1">All Reviews</option>
                    <option value="review">Assigned To Me</option>
                    <option value="feedback">Manager Feedback For You</option>
                </select>
            </div>
            <div class="col-sm-9">
                <span class="csBTNBox">
                    <a href="<?=purl('review/create');?>" class="btn btn-orange btn-lg"><i class="fa fa-plus-circle"></i>
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
                        <div class="col-sm-7">
                            <ul>
                                <li><a href="javascript:void(0)" data-id="active" class="jsTabShifter active">Active</a></li>
                                <li><a href="javascript:void(0)" data-id="draft" class="jsTabShifter ">Draft</a></li>
                                <li><a href="javascript:void(0)" data-id="archived" class="jsTabShifter ">Archived</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-4">
                            <select id="jsFilterReviewName"></select>
                        </div>
                        <div class="col-sm-1">
                            <span class="pull-right">
                                <button class="btn btn-black jsFilterBTN" data-target="jsFilterBox"><em class="fa fa-filter"></em> Filter</button>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Body  -->
                <div class="csPageBoxBody p10">
                    <!-- Filter -->
                    <div class="csPageBodyFilter pt10 pb10 dn" id="jsFilterBox">
                        <!--  -->
                        <div class="row">
                            <!--  -->
                            <div class="col-sm-3">
                                <label>Status</label>
                                <select class="form-control" id="jsFilterStatus">
                                    <option value="-1">All</option>
                                    <option value="1">Started</option>
                                    <option value="0">Ended</option>
                                </select>
                            </div>
                            <!--  -->
                            <div class="col-sm-3">
                                <label>Start Date</label>
                                <input type="text" class="form-control" placeholder="M/D/Y" readonly id="jsFilterStartDate" />
                            </div>
                            <!--  -->
                            <div class="col-sm-3">
                                <label>End Date</label>
                                <input type="text" class="form-control" placeholder="M/D/Y" readonly id="jsFilterEndDate" />
                            </div>
                            <!--  -->
                            <div class="col-sm-3">
                                <br />
                                <span class="csBTNBox">
                                    <button class="btn btn-orange btn-lg jsFilterApplyBtn">Apply</button>
                                    <button class="btn btn-black btn-lg jsFilterResetBtn">Reset</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Data -->
                    <div class="csPageBodyData">
                        <!-- Loader -->
                        <div class="csIPLoader jsIPLoader" data-page="review_listing"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                        <div class="table-sreponsive">
                            <table class="table table-striped table-condensed">
                                <caption></caption>
                                <thead>
                                    <tr>
                                        <th scope="column" class="col-sm-4 csTableTH">Review</th>
                                        <th scope="column" class="col-sm-2 csTableTH">Start Date</th>
                                        <th scope="column" class="col-sm-2 csTableTH">Reviewer progress</th>
                                        <th scope="column" class="col-sm-2 csTableTH">Manager Feedback progress</th>
                                        <th scope="column" class="col-sm-2 csTableTH"></th>
                                    </tr>
                                </thead>
                                <tbody id="jsReviewWrap"></tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!--  -->
                    <div class="clearfix"></div>
                </div>
                <!--  -->
                <div class="csPageBoxFooter jsPageBoxFooter p10"></div>
            </div>
        </div>
    </div>
</div>