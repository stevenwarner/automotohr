<?php
$ariaExpanded = $this->input->get("page") == 'section_3' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_3' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_3"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection3" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Banner 1
            </strong>
        </h4>
    </div>
    <div id="jsSection3" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection3Form">
            <div class="panel-body">

                <div class=" form-group">
                    <label>
                        Details&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" class="form-control" id="jsSection3Details" name="details"><?= $contentToShow["details"]; ?></textarea>
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
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection3File" class="hidden" accept="image/*" />
                </div>
                <div class=" form-group">
                    <label>
                        Logo&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection3FileLogo" class="hidden" accept="image/*" />
                </div>

            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection3Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section3 = {
        file: {
            sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
            sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>"
        },
        logo: {
            sourceType: "<?= $contentToShow["logoType"] ?? "upload"; ?>",
            sourceFile: "<?= $contentToShow["logoFile"] ?? "" ?>"
        }
    };
</script>