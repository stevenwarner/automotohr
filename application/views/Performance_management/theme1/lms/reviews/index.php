<div class="container-fluid">
    <!--  -->
    <div class="row">
        <!-- Content Area -->
        <div class="col-sm-12 col-xs-12">
            <!-- Content Area -->
            <div class="csPageBox csRadius5">
                <!-- Heading -->
                 <!-- Heading -->
                 <div class="csPageBoxHeader pa10 pl10 pr10">
                    <div class="row">
                        <div class="col-sm-7">
                            <ul>
                                <li><a href="javascript:void(0)" data-target="mine" class="jsTabShifter  csF16 csB7 active">Mine</a></li>
                                <li><a href="javascript:void(0)" data-target="assigned" class="jsTabShifter  csF16 csB7 ">Assigned</a></li>
                                <li><a href="javascript:void(0)" data-target="feedback" class="jsTabShifter  csF16 csB7 ">Feedback</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Body  -->
                <div class="csPageBoxBody p10">
                    <!-- Data -->
                    <div class="csPageBodyData">
                        <!-- Loader -->
                        <div class="csIPLoader jsIPLoader" data-page="review_listing"><em
                                class="fa fa-circle-o-notch fa-spin"></em></div>
                        <!-- Mine -->
                        <div id="mine" class="jsTabShifterTab">
                            <div class="table-reponsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="column">Review</th>
                                            <th scope="column">Reviewer</th>
                                            <th scope="column">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsReviewWraps">
                                        <?php foreach($Reviews['MyReviews'] as $review){?>
                                            <tr>
                                                <td><?=$review['review_title'];?></td>
                                                <td>
                                                    <div class="csEBox">
                                                        <figure>
                                                            <img src="<?=$employees[$review['reviewer_sid']]['img'];?>"
                                                                class="csRadius50" alt=""/>
                                                        </figure>
                                                        <div class="csEBoxText">
                                                            <h4 class="mb0"><strong><?=$employees[$review['reviewer_sid']]['name'];?></strong></h4>
                                                            <p><?=$employees[$review['reviewer_sid']]['role'];?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="<?=base_url('performance-management/employee/review/'.($review['review_sid']).'/'.($review['reviewee_sid']).'');?>" class="btn btn-black"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Assigned -->
                        <div id="assigned" class="jsTabShifterTab dn">
                            <div class="table-reponsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="column">Review</th>
                                            <th scope="column">Reviewee</th>
                                            <th scope="column">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsReviewWraps">
                                        <?php foreach($Reviews['AssignedReviews'] as $review){
                                            if($review['is_manager'] == 1) {continue;} ?>
                                            <tr>
                                                <td><?=$review['review_title'];?></td>
                                                <td>
                                                    <div class="csEBox">
                                                        <figure>
                                                            <img src="<?=$employees[$review['reviewee_sid']]['img'];?>"
                                                                class="csRadius50" alt=""/>
                                                        </figure>
                                                        <div class="csEBoxText">
                                                            <h4 class="mb0"><strong><?=$employees[$review['reviewee_sid']]['name'];?></strong></h4>
                                                            <p><?=$employees[$review['reviewee_sid']]['role'];?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <td>
                                                    <a href="<?=base_url('performance-management/employee/review/'.($review['review_sid']).'/'.($review['reviewee_sid']).'/'.($review['reviewer_sid']).'');?>" class="btn btn-black"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- feedback -->
                        <div id="feedback"  class="jsTabShifterTab dn">
                            <div class="table-reponsive">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="column">Review</th>
                                            <th scope="column">Reviewee</th>
                                            <th scope="column">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsReviewWraps">
                                        <?php foreach($Reviews['AssignedReviews'] as $review){
                                            if($review['is_manager'] == 0) {continue;} ?>
                                            <tr>
                                                <td><?=$review['review_title'];?></td>
                                                <td>
                                                    <div class="csEBox">
                                                        <figure>
                                                            <img src="<?=$employees[$review['reviewee_sid']]['img'];?>"
                                                                class="csRadius50" alt=""/>
                                                        </figure>
                                                        <div class="csEBoxText">
                                                            <h4 class="mb0"><strong><?=$employees[$review['reviewee_sid']]['name'];?></strong></h4>
                                                            <p><?=$employees[$review['reviewee_sid']]['role'];?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <td>
                                                    <a href="<?=base_url('performance-management/employee/review/'.($review['review_sid']).'/'.($review['reviewee_sid']).'/'.($review['reviewer_sid']).'');?>" class="btn btn-black"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
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
</div>

