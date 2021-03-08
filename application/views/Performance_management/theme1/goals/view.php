<div class="container-fluid">
    <div class="pa10">
        <!-- Header -->
        <div class="csPageBoxHeader p10 bbn">
            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <select id="jsVGStatus">
                        <option value="-1">All</option>
                        <option value="1">Ongoing Goals</option>
                        <option value="0">Closed Goals</option>
                    </select>
                </div>
                <div class="col-sm-3 col-xs-12">
                    <select id="jsVGEmployee">
                        <option value="-1">All</option>
                    </select>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <span class="csBTNBox">
                        <button class="btn btn-orange btn-lg mt0 jsCreateGoal ">
                            <i class="fa fa-plus-circle"></i> Create a Goal
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="csPageBox">
            <div class="csPageBoxHeader pa10 pl10 pr10">
                <div class="row">
                    <div class="col-sm-12 col-sm-12">
                        <ul>
                            <li><a href="javascript:void(0)" data-id="1" class="jsVGType active">My Goals</a></li>
                            <?php  if($level == 0) { ?>
                            <?php  if(!empty($permission['teamIds'])) { ?>
                                <li><a href="javascript:void(0)" data-id="2" class="jsVGType">Team Goals</a></li>
                            <?php  } ?>
                            <?php  if(!empty($permission['departmentIds'])) { ?>
                                <li><a href="javascript:void(0)" data-id="3" class="jsVGType">Department Goals</a></li>
                            <?php  } }else{ ?>
                            <li><a href="javascript:void(0)" data-id="4" class="jsVGType">Company Goals</a></li>
                            <li><a href="javascript:void(0)" data-id="3" class="jsVGType">Department Goals</a></li>
                            <li><a href="javascript:void(0)" data-id="2" class="jsVGType">Team Goals</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="csPageBoxBody p10 jsGoalWrap"></div>
            <!--  -->
            <div class="csPageBoxFooter p10 dn">
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