<?php
$ariaExpanded = $this->input->get("page") == 'products' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'products' ? "in" : "";
$contentToShow = $pageContent["page"]["sections"]["product"];
$products = $pageContent["page"]["sections"]["products"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsProductPage" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                WHY SHOULD YOU SWITCH TO AUTOMOTOHR?
            </strong>
        </h4>
    </div>
    <div id="jsProductPage" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsProductPageForm">
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="mainHeading" id="mainHeading" class="form-control" placeholder="Payroll" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>
                <br />
                <!--  -->
                <?php if ($products) { ?>
                    <?php foreach ($products as $key => $value) { ?>
                        <div class="row" data-key="<?= $key; ?>">
                            <div class="col-sm-3 <?= $value["direction"] == "left_to_right" ? "col-sm-push-7" : ""; ?>">
                                <?= getSourceByType(
                                    $value["sourceType"],
                                    $value["sourceFile"]
                                ); ?>
                            </div>
                            <div class="col-sm-7 <?= $value["direction"] == "left_to_right" ? "col-sm-pull-3" : ""; ?>">
                                <h3><?= convertToStrip($value["mainHeading"]); ?></h3>
                                <p><?= convertToStrip($value["details"]); ?></p>
                            </div>
                            <div class="col-sm-2 text-center" style="margin-top: 50px">
                                <button class="btn btn-warning jsEditHomeProduct" type="button">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                    &nbsp;Edit
                                </button>

                                <?php
                                if ($value["status"] == "1") {
                                    $deleteText = "De-Activate";
                                    $btn = "btn-danger";
                                    $statusText="Active";
                                    $statusColor="text-success";
                                } else if ($value["status"] == "0") {
                                    $deleteText = "Activate";
                                    $btn = "btn-success";                                  
                                    $statusText="De Active";
                                    $statusColor="text-danger";
                                } else {
                                    $deleteText = "De-Activate";
                                    $btn = "btn-danger";
                                    $statusText="Active";
                                    $statusColor="text-success";
                                } ?>
                                <button class="btn <?= $btn ?> jsDeleteHomeProduct" type="button" data-status="<?= $value["status"]; ?>">
                                    &nbsp;<?php echo $deleteText; ?>
                                </button>
                                <p class="<?=$statusColor?>"><strong>[<?=$statusText?>]</strong></p>
                                <!--
                                <button class="btn btn-danger jsDeleteHomeProduct" type="button">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    &nbsp;Delete
                                </button> -->

                            </div>
                        </div>
                        <hr />
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsProductPageBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
                <button class="btn btn-success jsProductPageAddProductBtn" type="button">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    &nbsp;Add a Product
                </button>
            </div>
        </form>
    </div>
</div>