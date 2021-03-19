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
                        <button class="btn btn-orange btn-lg mt0 jsCreateGoal csF16">
                            <i class="fa fa-plus-circle csF16" aria-hidden="true"></i> Create a Goal
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
                            <li><a href="javascript:void(0)" data-id="1" class="jsVGType csF18 csB7 active">Individual Goals</a></li>
                            <li><a href="javascript:void(0)" data-id="4" class="jsVGType csF18 csB7">Company Goals</a></li>
                            <li><a href="javascript:void(0)" data-id="3" class="jsVGType csF18 csB7">Department Goals</a></li>
                            <li><a href="javascript:void(0)" data-id="2" class="jsVGType csF18 csB7">Team Goals</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="csPageBoxBody p10">
                
                <div class="csIPLoader jsIPLoader" data-page="goal_view"><i aria-hidden="true" class="fa fa-circle-o-notch fa-spin"></i></div>
                <div class="jsGoalWrap"></div>
            </div>
        </div>
    </div>
</div>