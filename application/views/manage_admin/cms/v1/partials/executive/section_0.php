<?php
$ariaExpanded = $this->input->get("page") == 'section_0' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_0' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_0"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection0" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Main
            </strong>
        </h4>
    </div>
    <div id="jsSection0" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection0Form">
            <div class="panel-body">

                <div class=" form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="mainHeading" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Details&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" class="form-control" name="details"><?= $contentToShow["details"]; ?></textarea>
                </div>
                <div class=" form-group">
                    <label>
                        Button text&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonText" value="<?= $contentToShow["buttonText"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonLink" value="<?= $contentToShow["buttonLink"]; ?>" />
                </div>

                <div class=" form-group">
                    <label>
                        Button text (Cancel)&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonTextCancel" value="<?= $contentToShow["buttonTextCancel"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button link (Cancel)&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonLinkCancel" value="<?= $contentToShow["buttonLinkCancel"]; ?>" />
                </div>

                <div class=" form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection0File" class="hidden" accept="image/*" />
                </div>

            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection0Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section0 = {
        sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>"
    };
</script>