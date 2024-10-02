<!-- Feedback Listing -->
 <?php
   if ($load_view) {

    $panelHeading = 'background-color: #3554DC';
} else {
    $panelHeading = 'background-color: #81b431';
}
 
 ?>
<div id="jsReviewQuestionListBox">
    <div class="panel panel-theme">
        <div class="panel-heading" style="<?=$panelHeading?>">
            <p class="csF16 csB7 csW mb0">
                Sharing Feedback
            </p>
        </div>
        <!--  -->
        <div class="panel-body jsPageBody" id="jsReviewQuestionListArea" data-page="basic">
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF14 csB7 csInfo">Tell us how you want reporting managers to share feedback with their reports.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label class="control control--radio csF16">
                        <input type="radio" name="jsReviewShareFeedback" class="jsReviewShareFeedback" value="1" /> The reporting manager summarizes all reviews and shares the summary with their report
                        <div class="control__indicator"></div>
                    </label> <br>
                    <label class="control control--radio csF16">
                        <input type="radio" name="jsReviewShareFeedback" class="jsReviewShareFeedback" value="0" /> Nothing is shared with their report
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="row">
        <div class="col-sm-12">
            <div class="bbb"></div>
            <br />
            <button class="btn btn-black csF16 jsPageSectionBtn" data-to="questions"><i
                    class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp; Back To Questions</button>
            <span class="pull-right">
                <button class="btn btn-success csF16" id="jsReviewSaveBtn"><i class="fa fa-arrow-circle-o-right"
                        aria-hidden="true"></i>&nbsp; Save & Finish</button>
            </span>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
