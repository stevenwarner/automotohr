<?php
$ariaExpanded = $this->input->get("page") == 'sectionTag' . $tagIndex ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'sectionTag' . $tagIndex ? "in" : "";
//
?>
<!-- section  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSectionTag<?= $tagIndex; ?>" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                <?= $tagData["title"]; ?>
            </strong>
            <span class="pull-right">
                <button class="btn btn-danger jsDeleteSection" data-index="<?= $tagIndex; ?>">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                    &nbsp;Delete Section
                </button>
            </span>
        </h4>
    </div>
    <div id="jsSectionTag<?= $tagIndex; ?>" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body jsDraggable">
            <?php
            if ($tagData["cards"]) {
                foreach ($tagData["cards"] as $index => $card) {
            ?>
                <div class="jsCardsSortOrder_<?= $tagIndex; ?>" data-key="<?= $index; ?>" data-index="<?= $tagIndex; ?>">
                    <div class="row" data-key="<?= $index; ?>" data-index="<?= $tagIndex; ?>">
                        <div class="col-sm-10">
                            <h3><?= convertToStrip($card["title"]); ?></h3>
                            <p><?= convertToStrip($card["details"]); ?></p>
                            <br />
                            <a href="<?= generateLink($card["buttonLink"]); ?>" class="btn btn-default">
                                <?= convertToStrip($card["buttonText"]); ?>
                            </a>
                        </div>
                        <div class="col-sm-2 text-center" style="margin-top: 50px">
                            <button class="btn btn-warning jsEditTagCard" type="button">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                                &nbsp;Edit
                            </button>
                            <button class="btn btn-danger jsDeleteTagCard" type="button">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                &nbsp;Delete
                            </button>
                        </div>
                    </div>
                    <hr />
                </div>    
            <?php
                }
            }
            ?>
        </div>
        <div class="panel-footer text-center">
            <button class="btn btn-success jsAddCardSection" type="button" data-index="<?= $tagIndex; ?>">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                &nbsp;Add a Card Section
            </button>
        </div>
    </div>
</div>