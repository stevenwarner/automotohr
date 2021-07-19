<!--  -->
<div class="col-sm-9 col-xs-12">
    <!--  -->
    <div class="panel panel-theme">
        <div class="panel-body">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col" class="csF16 csB7">Review</th>
                        <th scope="col" class="csF16 csB7">Reviewer</th>
                        <th scope="col" class="csF16 csB7">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(!empty($reviews)){
                            foreach($reviews as $review){
                                ?>
                                <tr 
                                    data-id="<?=$review['review_sid'];?>"
                                    data-reviwee="<?=$review['reviewee_sid'];?>"
                                    data-reviwer="<?=$review['reviewer_sid'];?>"
                                >
                                    <td><?=ucwords($review['review_title']);?></td>
                                    <td><?=ucwords($review['first_name'].' '.$review['last_name']);?></td>
                                    <td>
                                        <button class="btn btn-orange csF16 csB7 jsViewFeedback" title="View Manager's Feedback" placement="top">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else{
                            ?>
                            <tr>
                                <td colspan="3">
                                    <p class="csF16 csB7 text-center">No reviews found</p>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var reviews = <?=json_encode($reviews);?>;
</script>