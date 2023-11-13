<?php
$ariaExpanded = $this->input->get("page") == 'process' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'process' ? "in" : "";
$contentToShow = $pageContent["page"]["sections"]["section9"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsProcess" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Our Process
            </strong>
        </h4>
    </div>
    <div id="jsProcess" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsProcessForm">
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
                        Steps&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="headingSub1" id="headingSub1" class="form-control" value="<?= $contentToShow["headingSub1"]; ?>" />
                    <br />
                    <input type="text" name="headingSub2" id="headingSub2" class="form-control" value="<?= $contentToShow["headingSub2"]; ?>" />
                    <br />
                    <input type="text" name="headingSub3" id="headingSub3" class="form-control" value="<?= $contentToShow["headingSub3"]; ?>" />
                    <br />
                    <input type="text" name="headingSub4" id="headingSub4" class="form-control" value="<?= $contentToShow["headingSub4"]; ?>" />
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
                <button class="btn btn-success jsProcessBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>