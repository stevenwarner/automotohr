<?php
$ariaExpanded = $this->input->get("page") == 'home_section_2' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'home_section_2' ? "in" : "";
$contentToShow = $pageContent["page"]["sections"]["section2"];
$products = $pageContent["page"]["sections"]["section2"]["products"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsHomeSection2" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                WHY SHOULD YOU SWITCH TO AUTOMOTOHR?
            </strong>
        </h4>
    </div>
    <div id="jsHomeSection2" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsHomeSection2Form">
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="jsMainHeading" id="jsMainHeading" class="form-control" placeholder="What we offer?" value="<?= $contentToShow["mainheading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Sub heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="jsSubHeading" id="jsSubHeading" class="form-control" placeholder="What we offer?" value="<?= $contentToShow["heading"]; ?>" />
                </div>
                <br />
                <!--  -->
                <div class="panel-body jsDraggable">
                    <?php if ($products) { ?>
                        <?php foreach ($products as $key => $value) { ?>

                            <div class="jsCardsSortOrder_<?= $tagIndex; ?>" data-key="<?= $key; ?>" data-index="<?= $tagIndex; ?>">
                                <div class="row" data-key="<?= $key; ?>">
                                    <div class="col-sm-3 <?= $value["direction"] == "left_to_right" ? "col-sm-push-7" : ""; ?>">
                                        <?= getSourceByType(
                                            $value["sourceType"],
                                            $value["sourceFile"]
                                        ); ?>
                                    </div>
                                    <div class="col-sm-7 <?= $value["direction"] == "left_to_right" ? "col-sm-pull-3" : ""; ?>">
                                        <h3><?= convertToStrip($value["mainHeading"]); ?></h3>
                                        <h4><?= convertToStrip($value["subHeading"]); ?></h4>
                                        <p><?= convertToStrip($value["details"]); ?></p>
                                        <a href="<?= main_url($value["buttonLink"]); ?>" target="_blank" class="btn btn-success">
                                            <?= $value["buttonText"]; ?>
                                        </a>
                                    </div>
                                    <div class="col-sm-2 text-center" style="margin-top: 50px">

                                        <button class="btn btn-danger jsDeleteHomeProduct">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                            &nbsp;Delete
                                        </button>
                                        <?php if ($value["status"] == '0') { ?>

                                            <button class="btn btn-success jsActivateHomeProductSection" style="margin-top: 5px">
                                                &nbsp;Activate
                                            </button>

                                        <?php } else { ?>
                                            <button class="btn btn-danger jsDeactivateHomeProduct" style="margin-top: 5px">
                                                &nbsp;De-Activate
                                            </button>

                                        <?php } ?>

                                        <button class="btn btn-warning jsEditHomeProduct" style="margin-top: 5px">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                            &nbsp;Edit
                                        </button>

                                    </div>
                                </div>
                                <hr />
                            </div>

                        <?php } ?>
                    <?php } ?>
                </div>

                  <?php $this->load->view('loader', [
                                "props" => 'id="jsMainLoader"'
                            ]); ?>

            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsHomeSection2Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
                <button class="btn btn-success jsHomeSection2AddProductBtn">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    &nbsp;Add a Product
                </button>
            </div>
        </form>
    </div>
</div>