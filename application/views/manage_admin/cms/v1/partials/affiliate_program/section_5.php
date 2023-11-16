<?php
$ariaExpanded = $this->input->get("page") == 'section_5' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_5' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_5"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection5" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Drive purchases with our conversion-optimized funnels
            </strong>
        </h4>
    </div>
    <div id="jsSection5" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection5Form">
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
                        Heading 1&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="heading1" value="<?= $contentToShow["heading1"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Details 1&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" class="form-control" name="details1"><?= $contentToShow["details1"]; ?></textarea>
                </div>

                <div class=" form-group">
                    <label>
                        Heading 2&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="heading2" value="<?= $contentToShow["heading2"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Details 2&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" class="form-control" name="details2"><?= $contentToShow["details2"]; ?></textarea>
                </div>


                <div class=" form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection5File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
                </div>

            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection5Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section5 = {
        sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>"
    };
</script>