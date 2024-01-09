<?php
$ariaExpanded = $this->input->get("page") == 'section_2' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_2' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_2"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection2" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Award
            </strong>
        </h4>
    </div>
    <div id="jsSection2" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection2Form">
            <div class="panel-body">
                <div class=" form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" id="mainHeading" name="mainHeading" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>

                <div class=" form-group">
                    <label>
                        Details&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" class="form-control" id="details2" name="details"><?= $contentToShow["details"]; ?></textarea>
                </div>
                <div class=" form-group">
                    <label class="control control--checkbox">
                        <input type="checkbox" name="status" id="jsSection2Status" <?= $contentToShow["status"] ? "checked" :""; ?> /> Show this section on front end?
                        <div class="control__indicator"></div>
                    </label>
                </div>
                <div class=" form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection2File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
                </div>

            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection2Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section2 = {
        sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>"
    };
</script>