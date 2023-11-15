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
                Cost Savings and Data Accuracy
            </strong>
        </h4>
    </div>
    <div id="jsSection5" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection5Form">
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
                    <input type="file" name="file" id="jsSection5File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
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
                            <input type="file" name="point1File" id="jsSection5Point1File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
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
                            <input type="file" name="point2File" id="jsSection5Point2File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
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
                            <input type="file" name="point3File" id="jsSection5Point3File" class="hidden" accept="<?= ALLOWED_EXTENSIONS; ?>" />
                        </div>
                    </div>
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

    const section5Points = {
        jsSection5Point1File: {
            sourceType: "<?= $contentToShow["headingPoint1Type"] ?? "upload"; ?>",
            sourceFile: "<?= $contentToShow["headingPoint1File"] ?? "" ?>"
        },
        jsSection5Point2File: {
            sourceType: "<?= $contentToShow["headingPoint2Type"] ?? "upload"; ?>",
            sourceFile: "<?= $contentToShow["headingPoint2File"] ?? "" ?>"
        },
        jsSection5Point3File: {
            sourceType: "<?= $contentToShow["headingPoint3Type"] ?? "upload"; ?>",
            sourceFile: "<?= $contentToShow["headingPoint3File"] ?? "" ?>"
        },

    };
</script>