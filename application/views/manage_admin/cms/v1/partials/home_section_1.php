<?php
$ariaExpanded = $this->input->get("page") == 'home_section_1' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'home_section_1' ? "in" : "";
$contentToShow = $pageContent["page"]["sections"]["section1"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsHomeSection1" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                What we offer?
            </strong>
        </h4>
    </div>
    <div id="jsHomeSection1" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsHomeSection1Form">
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="jsMainHeading" id="jsMainHeading" class="form-control" placeholder="What we offer?" value="<?= $contentToShow["mainheading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Sub heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="jsSubHeading" id="jsSubHeading" class="form-control" placeholder="What we offer?" value="<?= $contentToShow["heading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Details&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea name="jsDetails" id="jsDetails" class="form-control" rows="5" placeholder="What we offer?"><?= $contentToShow["headingDetail"]; ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        Points&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="bullet1" id="bullet1" class="form-control" value="<?= $contentToShow["bullet1"]; ?>" />
                    <br />
                    <input type="text" name="bullet2" id="bullet2" class="form-control" value="<?= $contentToShow["bullet2"]; ?>" />
                    <br />
                    <input type="text" name="bullet3" id="bullet3" class="form-control" value="<?= $contentToShow["bullet3"]; ?>" />
                    <br />
                    <input type="text" name="bullet4" id="bullet4" class="form-control" value="<?= $contentToShow["bullet4"]; ?>" />
                    <br />
                    <input type="text" name="bullet5" id="bullet5" class="form-control" value="<?= $contentToShow["bullet5"]; ?>" />
                    <br />
                    <input type="text" name="bullet6" id="bullet6" class="form-control" value="<?= $contentToShow["bullet6"]; ?>" />
                </div>


                <div class="form-group">
                    <label>
                        Button text&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="jsButtonText" id="jsButtonText" class="form-control" placeholder="Explore Our Solutions" value="<?= $contentToShow["btnText"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Button link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="jsButtonSlug" id="jsButtonSlug" class="form-control" placeholder="Explore Our Solutions" value="<?= $contentToShow["btnSlug"]; ?>" />
                </div>

                <div class="form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" class="hidden" name="file" id="jsFileContainerHomeSection1" accept="image/*, video/mp4, video/mov" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsHomeSection1Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const homeSection1FileObj = {
        sourceType: "<?= $contentToShow["sourceType"]; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"]; ?>",
    };
</script>