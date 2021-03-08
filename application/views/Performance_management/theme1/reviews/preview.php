<div class="container-fluid">
    <!--  -->
    <div class="row">
        <!-- Left sidebar -->
        <div class="col-sm-2 col-xs-12">
            <!-- Heading -->
            <div class="csPageHeading">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="csEVBox">
                            <figure>
                                <img src="<?=randomData('img');?>"
                                    class="csRadius50" />
                            </figure>
                            <div class="csEBoxText">
                                <h4 class="mb0"><strong><?=randomData('name');?></strong></h4>
                                <p class="mb0">(QA) [Admin Plus]</p>
                                <p class="">Jan 01 2021, Tuesday</p>
                                <a href="" class="btn btn-orange btn-xs cdRadius5">View Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content Area -->
        <div class="col-sm-7 col-xs-12">
            <!-- Main Content Area -->
            <div class="csPageBox csRadius5">
                <!-- Header -->
                <div class="csPageBoxHeader p10">
                    <h3 class="mt0">
                        <strong>Review 1</strong>
                    </h3>
                </div>
                <!-- Body -->
                <div class="csPageBoxBody p10">
                    <?php for($i = 1; $i < 10; $i++){ ?>
                    <div class="csFeedbackViewBox <?=$i == 9 ? 'bbn' : '';?>">
                        <h4 class="pa10 pb10"><strong>Question <?=$i;?></strong></h4>
                        <ul>
                            <li class="active">
                                <div class="csFeedbackViewBoxTab">
                                    <p class="mb0">1</p>
                                    <p>Strongly Agree</p>
                                </div>
                            </li>
                            <li>
                                <div class="csFeedbackViewBoxTab">
                                    <p class="mb0">2</p>
                                    <p>Agree</p>
                                </div>
                            </li>
                            <li>
                                <div class="csFeedbackViewBoxTab">
                                    <p class="mb0">3</p>
                                    <p>Neutral</p>
                                </div>
                            </li>
                            <li>
                                <div class="csFeedbackViewBoxTab">
                                    <p class="mb0">4</p>
                                    <p>Disagree</p>
                                </div>
                            </li>
                            <li>
                                <div class="csFeedbackViewBoxTab">
                                    <p class="mb0">5</p>
                                    <p>Strongly disagree</p>
                                </div>
                            </li>
                        </ul>
                        <h4 class="mb0"><strong>Please add any additional feedback you'd like to note for this
                                person?</strong>
                        </h4>
                        <p>Please leave comments to explain your rating choice.</p>

                        <div class="csFeedbackViewBoxComment">
                            <div class="row">
                                <div class="col-sm-5 col-xs-12">
                                    <h5><strong>Share Feedback</strong></h5>
                                    <div class="csShareBox">
                                        <img src="<?=randomData('img');?>"
                                            class="csRadius50" />
                                        <i class="fa fa-angle-double-right"></i>
                                        <img src="<?=randomData('img');?>"
                                            class="csRadius50" />
                                    </div>

                                </div>
                                <div class="col-sm-7 col-xs-12 ma10">
                                    <div><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum unde at nulla, magni numquam provident? Repudiandae incidunt corrupti porro exercitationem sapiente non natus. Temporibus animi molestiae sint quas facilis sapiente!</p></div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <!-- <div class="clearfix"></div> -->
                    </div>
                    <?php } ?>
                </div>
                <!-- Footer -->
                <div class="csPageBoxFooter p10">
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="csBTNBox ma10">
                                <a href="" class="btn btn-black btn-lg">Close</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>