<?php
$ariaExpanded = $this->input->get("page") == 'section_3' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_3' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_3"];
$teams = $pageContent["page"]["sections"]["teams"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection3" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Team
            </strong>
        </h4>
    </div>
    <div id="jsSection3" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection3Form">
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" id="mainHeading" name="mainHeading" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>
                <br />
                <!--  -->
                <?php if ($teams) { ?>
                    <?php foreach ($teams as $key => $value) { ?>
                        <div class="row" data-key="<?= $key; ?>">
                            <div class="col-sm-3">
                                <?= getSourceByType(
                                    $value["sourceType"],
                                    $value["sourceFile"]
                                ); ?>
                            </div>
                            <div class="col-sm-7">
                                <h3><?= convertToStrip($value["mainHeading"]); ?></h3>
                                <p><?= convertToStrip($value["details"]); ?></p>
                            </div>
                            <div class="col-sm-2 text-center" style="margin-top: 50px">
                                <button class="btn btn-warning jsEditPage" type="button">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                    &nbsp;Edit
                                </button>
                                <button class="btn btn-danger jsDeletePage" type="button">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    &nbsp;Delete
                                </button>
                            </div>
                        </div>
                        <hr />
                    <?php } ?>
                <?php } ?>

            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection3Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
                <button class="btn btn-success" id="jsSection3AddBtn" type="button">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Add a Team member
                </button>
            </div>
        </form>
    </div>
</div>