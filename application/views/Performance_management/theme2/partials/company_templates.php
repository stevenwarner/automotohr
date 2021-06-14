<div class="panel panel-theme">
    <div class="panel-heading">
        <h3 class="csF16 csB7 mt0 mb0 csW">
            Default Template(s) - <?=count($system_templates);?> templates found
            <span class="pull-right">
                <i class="fa fa-minus-circle csF20 jsPageBTN csW" data-target="company_templates" aria-hidden="true"></i>
            </span>
        </h3>
    </div>
    <div class="panel-body jsPageBody" data-page="company_templates">
        <!--  -->
        <?php if(!empty($system_templates)): ?>
        <div class="row">
            <div class="col-sm-12">
<<<<<<< HEAD
                <p class="csF16"><button class="btn btn-xs btn-black"><i class="fa fa-eye"
                            aria-hidden="true"></i></button>&nbsp;View the template question(s).</p>
                <p class="csF16"><button class="btn btn-xs btn-black"><i class="fa fa-check-circle"
                            aria-hidden="true"></i></button>&nbsp;Select the template to start with.</p>
=======
                <p class="csF14"><strong><em>Note:</em></strong></p>
                <p class="csF14"><button class="btn btn-xs btn-black"><i class="fa fa-eye"
                            aria-hidden="true"></i></button>&nbsp; View all the questions.</p>
                <p class="csF14"><button class="btn btn-xs btn-black"><i class="fa fa-check-circle"
                            aria-hidden="true"></i></button>&nbsp; start the review from the this template.</p>
>>>>>>> d5bced39... Added creatae review step 1 on blue screen
            </div>
        </div>
        <div class="row">
            <?php  
                    foreach($system_templates as $template):
                        $questionsCount = count(json_decode($template['questions'], true));
                ?>
            <div class="col-sm-4 col-xs-12">
                <div class="csPageBox csTemplateWrap csRadius5" data-id="<?=$template['sid'];?>" data-type="company"
                    data-name="<?=$template['name'];?>">
                    <div class="csPageBoxHeader pa10 pb10">
                        <ul class="text-right">
                            <li>
                                <button class="btn btn-black btn-xs jsTemplateQuestionsView" title="View all questions."
                                    placement="top"><i class="fa fa-eye csF16" aria-hidden="true"></i></button>
                            </li>
                            <li>
<<<<<<< HEAD
                                <button class="btn btn-black btn-xs jsTemplateQuestionsSelect" title="Use this template for review."
=======
                                <button class="btn btn-black btn-xs" title="Use this template for review."
>>>>>>> d5bced39... Added creatae review step 1 on blue screen
                                    placement="top"><i class="fa fa-check-circle csF16" aria-hidden="true"></i></button>
                            </li>
                        </ul>
                    </div>
                    <div class="csPageBoxBody p10">
                        <h3 class="csF16 csB7">
                            <?=$template['name'];?>
                        </h3>
                    </div>
                    <div class="csPageBoxFooter ban p10">
                        <p style="font-size: 50px;" class="mb0"><?=$questionsCount;?>
                        <sub style="font-size:25px;">Questions</sub></p>
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