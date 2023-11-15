<?php
$ariaExpanded = $this->input->get("page") == 'section_8' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_8' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_8"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection8" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Experience
            </strong>
        </h4>
    </div>
    <div id="jsSection8" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection8Form">
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
                        Button Link&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonSlug" value="<?= $contentToShow["buttonSlug"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Button Text&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonText" value="<?= $contentToShow["buttonText"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection8File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection8Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section8 = {
        sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>"
    };
</script>