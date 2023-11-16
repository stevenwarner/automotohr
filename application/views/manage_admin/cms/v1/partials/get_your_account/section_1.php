<?php
$ariaExpanded = $this->input->get("page") == 'section_1' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_1' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_1"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection1" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                WHAT WE OFFER?
            </strong>
        </h4>
    </div>
    <div id="jsSection1" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection1Form">
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
                        Sub heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="subHeading" value="<?= $contentToShow["subHeading"]; ?>" />
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
                        Point 1&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="point1" value="<?= $contentToShow["point1"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Point 2&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="point2" value="<?= $contentToShow["point2"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Point 3&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="point3" value="<?= $contentToShow["point3"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Point 4&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="point4" value="<?= $contentToShow["point4"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Point 5&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="point5" value="<?= $contentToShow["point5"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Point 6&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="point6" value="<?= $contentToShow["point6"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection1File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
                </div>

            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection1Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section1 = {
        sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>"
    };
</script>