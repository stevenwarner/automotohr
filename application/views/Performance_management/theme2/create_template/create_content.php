<div class="col-md-12 col-sm-12">
    <!-- Assigned -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="background-color: #81b431;">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        Create A Template <span id="jsReviewTitleTxt"></span>
                    </h5>
                </div>
            </div>
        </div>

        <div class="panel-body pa0 pl0 pr0">
            
            <?php $this->load->view("{$pp}loader", ['key' => 'template']); ?>
            <div class="jsPageContainer p10" style="position: relative">
             
                <!-- Step 4 -->
                <div class="jsPageSection" data-page="schedule">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="csF16 csB7">Name <span class="csRequired"></span></label><br>
                            <input type="text" class="form-control" id="jsTemplateName" value="<?=!empty($template) ? $template['name'] : '';?>" />
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <?php if(!empty($template)) {?>        
        <div class="panel-body pa0 pl0 pr0">            
            <?php $this->load->view("{$pp}loader", ['key' => 'template']); ?>
            <div class="jsPageContainer p10" style="position: relative">             
                <!-- Step 4 -->
                <div class="jsPageSection" data-page="questions">
                    <!-- Reviewees -->
                    <?php $this->load->view("{$pp}create_template/questions"); ?>
                </div>                
            </div>
        </div>
        <?php } ?>

        <div class="panel-footer">
            <span class="pull-right">
                <button class="btn btn-success csF16 csB7 <?=empty($template) ? 'jsTemplateSave' : '';?>" <?=!empty($template) ? 'id="jsReviewQuestionsSaveBtn"' : '';?>>
                    <i class="fa fa-save" aria-hidden="true"></i>&nbsp;Save & <?=empty($template) ? 'Next' : 'Finish';?>
                </button>
            </span>
            <div class="clearfix"></div>
        </div>
    </div>
