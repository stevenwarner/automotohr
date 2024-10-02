<?php if($load_view){

$panelHeading='background-color: #3554DC';

}else{
$panelHeading='background-color: #81b431';
}
?>
<div class="col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
    <!--  -->
    <div class="panel panel-theme">
        <!--  -->
        <div class="panel-heading mt0 mb0 pb0" style="<?=$panelHeading?>">
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <h5 class="csF16 csW csB7">
                        Template(s)
                    </h5>
                </div>
                <div class="col-xs-12 col-md-10">
                    <span class="pull-right">
                        <a href="<?=purl('template/create')?>" class="btn btn-orange csF16 csB7">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Create A Template
                        </a>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="panel-body">
           
            <!--  -->
            <div class="row">
            <?php
                //
                if(!empty($templates)):
                    //
                    foreach($templates as $template):
                       
                        ?>
                        
                            <div class="col-md-4 col-xs-12">
                                <div class="panel panel-theme jsReviewBox" >
                                    <div class="panel-heading pl5 pr5" style="<?=$panelHeading?>">
                                        <span class="pull-right">
                                        <a href="<?=purl('template/create/'.$template['sid']);?>" class="btn btn-black csF16 btn-xs "  title="Edit Template" placement="top">
                                            <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                        </a>
                                        </span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="panel-body">
                                        <p class="csF14 csB7 mb0">Title</p>
                                        <p class="csF14"><?=$template['name'];?></p>
                                        <hr>
                                        <p class="csF14 csB7 mb0">Questions(s) Length </p>
                                        <p class="csF14"><?=count(json_decode($template['questions']), true);?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                    endforeach;
                else:
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="csF16 text-center">
                                No review(s) found.
                            </p>
                        </div>
                    </div>
                    <?php
                endif;
                ?>
            </div>  
        </div>
    </div>
</div>

<script>
    var employees = <?=json_encode($company_employees);?>;
</script>