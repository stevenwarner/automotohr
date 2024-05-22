<style>
    .jsSectionThree:nth-child(even) {
        background: #eee;
    }
    
    textarea {
        resize: none;
    }
</style>
<br />
<div class="container">
    <form action="" id="jsSectionThreeForm">
        <?php if ($section_3_status == "uncompleted") { ?>
            <div class="panel panel-default">
                <div class="panel-footer text-right">
                    <button class="btn btn-orange jsSaveSectionThree">Save</button>
                </div>
            </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="m0">
                    <strong>
                        Employee Section 3: The Year in Review
                    </strong>
                </h3>
            </div>
            <div class="panel-body">
                <!-- Question Start -->
                <div class="row jsSectionThree  mrg-bottom-20">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group autoheight">
                            <h3>
                                <strong>
                                    Additional Comments, Feedback - Managers Comments:
                                </strong>
                            </h3>
                            <textarea name="additional_comment_one" rows="10" <?= $readonly ?> class="form-control"><?= $section_3['additional_comment_one'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>
                <!-- Question End -->

                <!-- Question Start -->
                <div class="row jsSectionThree  mrg-bottom-20">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group autoheight">
                            <h3>
                                <strong>
                                    Additional Comments, Feedback - Managers Comments:
                                </strong>
                            </h3>
                            <textarea name="additional_comment_two" rows="10" <?= $readonly ?> class="form-control"><?= $section_3['additional_comment_two'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>
                <!-- Question End -->
            </div>
        </div>

        <?php if ($section_3_status == "uncompleted") { ?>
            <div class="panel panel-default">
                <div class="panel-footer text-right">
                    <button class="btn btn-orange jsSaveSectionThree">Save</button>
                </div>
            </div>
        <?php } ?>
    </form>
</div>