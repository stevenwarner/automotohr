<?php
$ariaExpanded = $this->input->get("page") == 'highlights' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'highlights' ? "in" : "";
$contentToShow = $pageContent["page"]["sections"]["specialhighlights"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsHighlights" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                AI Recruiter
            </strong>
        </h4>
    </div>
    <div id="jsHighlights" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsHighlightsForm">
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="mainheading" id="mainheading" class="form-control" placeholder="What we offer?" value="<?= $contentToShow["mainheading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Sub heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="heading" id="heading" class="form-control" placeholder="What we offer?" value="<?= $contentToShow["heading"]; ?>" />
                </div>

                <div class="form-group">
                    <label>
                        Details&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea name="details" id="details" class="form-control" placeholder="Take these simple steps to achieve success today!"><?= $contentToShow["details"]; ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        Status&nbsp;
                    </label>
                    <select name="status" class="form-control">
                        <option value="1" <?= $contentToShow["status"] == '1' ? ' selected' : ''; ?>>Enable</option>
                        <option value="0" <?= $contentToShow["status"] == '0' ? ' selected' : ''; ?>>Disable</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Button text&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="btnText" id="btnText" class="form-control" placeholder="Get Started" value="<?= $contentToShow["btnText"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Button link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="btnSlug" id="btnSlug" class="form-control" placeholder="#" value="<?= $contentToShow["btnSlug"]; ?>" />
                </div>



            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsHighlightsBt">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>