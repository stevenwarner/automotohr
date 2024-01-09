<?php
$ariaExpanded = $this->input->get("page") == 'section_4' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_4' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_4"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection4" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Hiring and Onboarding Made Effortless
            </strong>
        </h4>
    </div>
    <div id="jsSection4" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection4Form">
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="mainHeading" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>

                <div class="form-group">
                    <label>
                        Source&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="file" name="file" id="jsSection4File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
                </div>

                <div class="form-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>
                                <strong>
                                    Point 1
                                </strong>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <label>
                                Heading&nbsp;
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="text" class="form-control" name="headingPoint1" value="<?= $contentToShow["headingPoint1"]; ?>" />
                            <br />
                            <label>
                                Details&nbsp;
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="text" class="form-control" name="detailsPoint1" value="<?= $contentToShow["detailsPoint1"]; ?>" />
                            <br />
                            <label>
                                Source&nbsp;
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="file" name="point1File" id="jsSection4Point1File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>
                                <strong>
                                    Point 2
                                </strong>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <label>
                                Heading&nbsp;
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="text" class="form-control" name="headingPoint2" value="<?= $contentToShow["headingPoint2"]; ?>" />
                            <br />
                            <label>
                                Details&nbsp;
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="text" class="form-control" name="detailsPoint2" value="<?= $contentToShow["detailsPoint2"]; ?>" />
                            <br />
                            <label>
                                Source&nbsp;
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="file" name="point2File" id="jsSection4Point2File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>
                                <strong>
                                    Point 3
                                </strong>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <label>
                                Heading&nbsp;
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="text" class="form-control" name="headingPoint3" value="<?= $contentToShow["headingPoint3"]; ?>" />
                            <br />
                            <label>
                                Details&nbsp;
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="text" class="form-control" name="detailsPoint3" value="<?= $contentToShow["detailsPoint3"]; ?>" />
                            <br />
                            <label>
                                Source&nbsp;
                                <strong class="text-danger">*</strong>
                            </label>
                            <input type="file" name="point3File" id="jsSection4Point3File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection4Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const section4 = {
        sourceType: "<?= $contentToShow["sourceType"] ?? "upload"; ?>",
        sourceFile: "<?= $contentToShow["sourceFile"] ?? "" ?>"
    };

    const section4Points = {
        jsSection4Point1File: {
            sourceType: "<?= $contentToShow["headingPoint1Type"] ?? "upload"; ?>",
            sourceFile: "<?= $contentToShow["headingPoint1File"] ?? "" ?>"
        },
        jsSection4Point2File: {
            sourceType: "<?= $contentToShow["headingPoint2Type"] ?? "upload"; ?>",
            sourceFile: "<?= $contentToShow["headingPoint2File"] ?? "" ?>"
        },
        jsSection4Point3File: {
            sourceType: "<?= $contentToShow["headingPoint3Type"] ?? "upload"; ?>",
            sourceFile: "<?= $contentToShow["headingPoint3File"] ?? "" ?>"
        },

    };
</script>