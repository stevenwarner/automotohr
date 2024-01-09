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
                Ready to become an AutomotoHR Affiliate Partner?
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
                        Heading 3&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="heading3" value="<?= $contentToShow["heading3"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Details 3&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" class="form-control" name="details3"><?= $contentToShow["details3"]; ?></textarea>
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
