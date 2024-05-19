<style>
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
                <label class="col-sm-12">
                    <br>
                    <span class="text-large">
                        Additional Comments, Feedback - Managers Comments:
                    </span>
                    <textarea name="additional_comment_one" rows="10" class="form-control"><?= $section_3['additional_comment_one'] ?? '' ?></textarea>
                </label>
                <!-- Question End -->
                <!-- Question Start -->
                <label class="col-sm-12">
                    <br>
                    <span class="text-large">
                        Additional Comments, Feedback - Managers Comments:
                    </span>
                    <textarea name="additional_comment_two" rows="10" class="form-control"><?= $section_3['additional_comment_two'] ?? '' ?></textarea>
                </label>
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