<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="csF14 csB9 mt0 mb0">
            Company Template(s)  - <?=count($templates['personal']);?> templates found
            <span class="pull-right">
                <i class="fa fa-minus-circle csF20 jsPageBTN" data-target="personal_templates" aria-hidden="true"></i>
            </span>
        </h3>
    </div>
    <div class="panel-body jsPageBody" data-page="personal_templates" >
        <!--  -->
        <?php if(!empty($templates['personal'])): ?>
            <div class="row">
                <div class="col-sm-12">
                <p class="csF14"><strong><em>Note:</em></strong></p>
                    <p class="csF14"><button class="btn btn-xs btn-black"><i class="fa fa-eye"
                                aria-hidden="true"></i></button>&nbsp; View all the questions.</p>
                    <p class="csF14"><button class="btn btn-xs btn-black"><i class="fa fa-check-circle"
                                aria-hidden="true"></i></button>&nbsp; Use the template for the review.</p>
                </div>
            </div>
            <div class="row">
                <?php  
                    foreach($templates['personal'] as $template):
                        $questionsCount = count(json_decode($template['questions'], true));
                ?>
                <div class="col-sm-4 col-xs-12">
                    <div class="csPageBox csTemplateWrap csRadius5" 
                        data-id="<?=$template['sid'];?>" 
                        data-type="personal"
                        data-name="<?=$template['name'];?>">
                        <div class="csPageBoxHeader pa10 pb10">
                            <ul class="text-right">
                                <li>
                                    <button class="btn btn-black btn-xs jsTemplateQuestionsView" title="View all questions." placement="top"><i class="fa fa-eye csF16" aria-hidden="true"></i></button>
                                </li>
                                <li>
<<<<<<< HEAD
<<<<<<< HEAD
                                    <button class="btn btn-black btn-xs jsTemplateQuestionsSelect" title="Use this template for review." placement="top"><i class="fa fa-check-circle csF16" aria-hidden="true"></i></button>
=======
                                    <button class="btn btn-black btn-xs" title="Use this template for review." placement="top"><i class="fa fa-check-circle csF16" aria-hidden="true"></i></button>
>>>>>>> d5bced39... Added creatae review step 1 on blue screen
=======
                                    <button class="btn btn-black btn-xs jsTemplateQuestionsSelect" title="Use this template for review." placement="top"><i class="fa fa-check-circle csF16" aria-hidden="true"></i></button>
>>>>>>> 2798fc44... Added review part of Perfoemance management
                                </li>
                            </ul>
                        </div>
                        <div class="csPageBoxBody p10">
                            <h3 class="csF16 csB9">
                                <?=$template['name'];?>
                            </h3>
                        </div>
                        <div class="csPageBoxFooter ban p10">
                            <p style="font-size: 70px;"><?=$questionsCount;?> <sub style="font-size:31px;">Questions</sub></p>
                        </div>
                    </div>
                </div>
                <?php   endforeach; ?>
            </div>
            <?php else: ?>
            <!-- Error Box -->
            <div class="csErrorBox">
                <i aria-hidden="true" class="fa fa-info-circle"></i>
                <p>There are currently no custom templates.</p>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>